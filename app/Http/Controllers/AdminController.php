<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Machine;
use App\Models\Reservation;
use App\Models\Rating;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AdminController extends Controller
{
    // ─── STATS GLOBALES ───────────────────────────────────────────
    public function stats()
    {
        $totalUsers        = User::where('role', '!=', 'admin')->count();
        $totalOwners       = User::where('role', 'owner')->count();
        $totalClients      = User::where('role', 'client')->count();
        $totalSuspended    = User::where('is_suspended', true)->count();

        $totalMachines     = Machine::count();
        $machinesAvail     = Machine::where('status', 'available')->count();
        $machinesRented    = Machine::where('status', 'unavailable')->count(); // ✅ FIX: 'rented' → 'unavailable'

        $totalReservations = Reservation::count();
        $reservPending     = Reservation::where('status', 'pending')->count();
        $reservAccepted    = Reservation::where('status', 'accepted')->count();
        $reservCompleted   = Reservation::where('status', 'completed')->count();
        $reservRejected    = Reservation::where('status', 'rejected')->count();

        // ✅ FIX: utiliser total_price stocké directement (plus de daily_price ni de JOIN)
        $totalRevenue = Reservation::where('status', 'completed')->sum('total_price') ?? 0;

        // Réservations par mois (12 derniers mois)
        $reservByMonth = Reservation::selectRaw('MONTH(created_at) as month, YEAR(created_at) as year, COUNT(*) as count')
            ->where('created_at', '>=', now()->subMonths(12))
            ->groupBy('year', 'month')
            ->orderBy('year')
            ->orderBy('month')
            ->get();

        // Top machines (par nombre de réservations)
        $topMachines = Machine::withCount('reservations')
            ->orderByDesc('reservations_count')
            ->take(5)
            ->get(['id', 'name', 'city', 'price_per_day', 'reservations_count']); // ✅ FIX: daily_price → price_per_day

        // Inscriptions par mois
        $usersByMonth = User::selectRaw('MONTH(created_at) as month, YEAR(created_at) as year, COUNT(*) as count')
            ->where('created_at', '>=', now()->subMonths(6))
            ->groupBy('year', 'month')
            ->orderBy('year')
            ->orderBy('month')
            ->get();

        return response()->json([
            'users' => [
                'total'     => $totalUsers,
                'owners'    => $totalOwners,
                'clients'   => $totalClients,
                'suspended' => $totalSuspended,
            ],
            'machines' => [
                'total'     => $totalMachines,
                'available' => $machinesAvail,
                'rented'    => $machinesRented,
            ],
            'reservations' => [
                'total'     => $totalReservations,
                'pending'   => $reservPending,
                'accepted'  => $reservAccepted,
                'completed' => $reservCompleted,
                'rejected'  => $reservRejected,
            ],
            'revenue'       => round($totalRevenue, 2),
            'charts' => [
                'reservByMonth' => $reservByMonth,
                'usersByMonth'  => $usersByMonth,
                'topMachines'   => $topMachines,
            ],
        ]);
    }

    // ─── GESTION UTILISATEURS ─────────────────────────────────────
    public function users(Request $request)
    {
        $query = User::where('role', '!=', 'admin')
            ->withCount(['reservations', 'machines']);

        if ($request->search) {
            $q = $request->search;
            $query->where(function ($q2) use ($q) {
                $q2->where('name', 'like', "%$q%")
                   ->orWhere('email', 'like', "%$q%");
            });
        }

        if ($request->role && in_array($request->role, ['owner', 'client'])) {
            $query->where('role', $request->role);
        }

        if ($request->status === 'suspended') {
            $query->where('is_suspended', true);
        } elseif ($request->status === 'active') {
            $query->where('is_suspended', false);
        }

        $users = $query->orderByDesc('created_at')->paginate(15);

        return response()->json($users);
    }

    public function suspendUser(User $user)
    {
        if ($user->role === 'admin') {
            return response()->json(['message' => 'Impossible de suspendre un admin.'], 403);
        }
        $user->update(['is_suspended' => true]);
        return response()->json(['message' => "Utilisateur {$user->name} suspendu."]);
    }

    public function activateUser(User $user)
    {
        $user->update(['is_suspended' => false]);
        return response()->json(['message' => "Utilisateur {$user->name} réactivé."]);
    }

    public function deleteUser(User $user)
    {
        if ($user->role === 'admin') {
            return response()->json(['message' => 'Impossible de supprimer un admin.'], 403);
        }
        $name = $user->name;
        $user->delete();
        return response()->json(['message' => "Utilisateur $name supprimé."]);
    }

    // ─── GESTION MACHINES ─────────────────────────────────────────
    public function machines(Request $request)
    {
        $query = Machine::with(['owner:id,name,email'])
            ->withCount('reservations');

        if ($request->search) {
            $q = $request->search;
            $query->where(function ($q2) use ($q) {
                $q2->where('name', 'like', "%$q%")
                   ->orWhere('city', 'like', "%$q%")
                   ->orWhere('type', 'like', "%$q%"); // ✅ FIX: category → type
            });
        }

        if ($request->status && in_array($request->status, ['available', 'unavailable', 'maintenance'])) {
            $query->where('status', $request->status);
        }

        $machines = $query->orderByDesc('created_at')->paginate(15);

        return response()->json($machines);
    }

    public function deleteMachine(Machine $machine)
    {
        $name = $machine->name;
        $machine->delete();
        return response()->json(['message' => "Machine \"$name\" supprimée."]);
    }

    // ─── GESTION RÉSERVATIONS ─────────────────────────────────────
    public function reservations(Request $request)
    {
        $query = Reservation::with([
            'machine:id,name,city,price_per_day', // ✅ FIX: daily_price → price_per_day
            'client:id,name,email',
        ])->orderByDesc('created_at');

        if ($request->status && in_array($request->status, ['pending', 'accepted', 'completed', 'rejected'])) {
            $query->where('status', $request->status);
        }

        if ($request->search) {
            $q = $request->search;
            $query->where(function ($q2) use ($q) {
                $q2->whereHas('client', fn($q3) => $q3->where('name', 'like', "%$q%"))
                   ->orWhereHas('machine', fn($q3) => $q3->where('name', 'like', "%$q%"));
            });
        }

        $reservations = $query->paginate(15);

        return response()->json($reservations);
    }

    // ─── ACTIVITY LOG (dernières actions) ────────────────────────
    public function recentActivity()
    {
        $recentReserv = Reservation::with(['machine:id,name', 'client:id,name'])
            ->orderByDesc('created_at')
            ->take(10)
            ->get();

        $recentUsers = User::where('role', '!=', 'admin')
            ->orderByDesc('created_at')
            ->take(5)
            ->get(['id', 'name', 'email', 'role', 'created_at']);

        return response()->json([
            'reservations' => $recentReserv,
            'users'        => $recentUsers,
        ]);
    }
}