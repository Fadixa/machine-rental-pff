<?php

namespace App\Http\Controllers;

use App\Models\Reservation;
use App\Models\Machine;
use App\Mail\ReservationAcceptedMail;
use App\Mail\ReservationRejectedMail;
use App\Mail\NewReservationMail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;
use Barryvdh\DomPDF\Facade\Pdf;

class ReservationController extends Controller
{
    public function index(Request $request)
    {
        $user = Auth::user();

        $query = Reservation::with([
            'machine:id,name,type,city,price_per_day,owner_id',
            'machine.owner:id,name,phone',
            'client:id,name,email',
        ]);

        if ($user->role === 'owner') {
            $query->whereHas('machine', function ($q) use ($user) {
                $q->where('owner_id', $user->id);
            });
        } else {
            $query->where('client_id', $user->id);
        }

        $reservations = $query->orderByDesc('created_at')->get()->map(function ($r) {
            $days = $r->start_date && $r->end_date
                ? \Carbon\Carbon::parse($r->start_date)->diffInDays($r->end_date)
                : 0;

            return [
                'id'         => $r->id,
                'machine_id' => $r->machine_id,
                'machine'    => [
                    'name'  => $r->machine->name  ?? '—',
                    'type'  => $r->machine->type  ?? '—',
                    'city'  => $r->machine->city  ?? '—',
                    'owner' => [
                        'name'  => $r->machine->owner->name  ?? '—',
                        'phone' => $r->machine->owner->phone ?? null,
                    ],
                ],
                'client'     => [
                    'name'  => $r->client->name  ?? '—',
                    'email' => $r->client->email ?? '—',
                ],
                'start_date'  => $r->start_date ? \Carbon\Carbon::parse($r->start_date)->format('Y-m-d') : null,
                'end_date'    => $r->end_date   ? \Carbon\Carbon::parse($r->end_date)->format('Y-m-d')   : null,
                'nb_days'     => $days,
                'total_price' => $r->total_price ?? ($r->machine->price_per_day * $days),
                'status'      => $r->status,
                'motif'       => $r->motif,
                'created_at'  => $r->created_at->format('d/m/Y'),
            ];
        });

        return response()->json($reservations);
    }

    public function store(Request $request)
    {
        $request->validate([
            'machine_id' => 'required|exists:machines,id',
            'start_date' => 'required|date|after_or_equal:today',
            'end_date'   => 'required|date|after:start_date',
        ]);

        $machine = Machine::findOrFail($request->machine_id);

        $jours = \Carbon\Carbon::parse($request->start_date)
                    ->diffInDays($request->end_date);

        $reservation = Reservation::create([
            'machine_id'  => $machine->id,
            'client_id'   => Auth::id(),
            'start_date'  => $request->start_date,
            'end_date'    => $request->end_date,
            'total_price' => $machine->price_per_day * $jours,
            'status'      => 'pending',
        ]);

        try {
            $owner = $machine->owner;
            Mail::to($owner->email)->send(new NewReservationMail($reservation));
            Log::info("📧 Email NewReservation envoyé à {$owner->email} — #{$reservation->id}");
        } catch (\Exception $e) {
            Log::error("❌ Échec email NewReservation: " . $e->getMessage());
        }

        return response()->json([
            'message'     => 'Réservation créée avec succès.',
            'reservation' => $reservation->load('machine', 'client'),
        ], 201);
    }

    public function accept(Reservation $reservation)
    {
        // ⚠️ owner via machine — pas owner_id direct
        if ($reservation->machine->owner_id !== Auth::id()) {
            return response()->json(['message' => 'Action non autorisée.'], 403);
        }

        $reservation->update(['status' => 'accepted']);

        try {
            $client = $reservation->client;
            Mail::to($client->email)->send(new ReservationAcceptedMail($reservation));
            Log::info("📧 Email Accepted envoyé à {$client->email} — #{$reservation->id}");
        } catch (\Exception $e) {
            Log::error("❌ Échec email Accepted: " . $e->getMessage());
        }

        return response()->json([
            'message'     => 'Réservation acceptée.',
            'reservation' => $reservation->fresh(['machine', 'client']),
        ]);
    }

    public function reject(Request $request, Reservation $reservation)
    {
        // ⚠️ owner via machine — pas owner_id direct
        if ($reservation->machine->owner_id !== Auth::id()) {
            return response()->json(['message' => 'Action non autorisée.'], 403);
        }

        $motif = $request->input('motif', '');
        $reservation->update(['status' => 'rejected', 'motif' => $motif]);

        try {
            $client = $reservation->client;
            Mail::to($client->email)->send(new ReservationRejectedMail($reservation, $motif));
            Log::info("📧 Email Rejected envoyé à {$client->email} — #{$reservation->id}");
        } catch (\Exception $e) {
            Log::error("❌ Échec email Rejected: " . $e->getMessage());
        }

        return response()->json([
            'message'     => 'Réservation refusée.',
            'reservation' => $reservation->fresh(['machine', 'client']),
        ]);
    }

    public function downloadContrat(Reservation $reservation)
    {
        $machineOwnerId = $reservation->machine->owner_id ?? null;

        if (Auth::id() !== $reservation->client_id && Auth::id() !== $machineOwnerId) {
            return response()->json(['message' => 'Accès refusé.'], 403);
        }

        $reservation->load('machine.owner', 'client');

        $pdf = Pdf::loadView('pdf.contrat', [
            'reservation' => $reservation,
            'machine'     => $reservation->machine,
            'client'      => $reservation->client,
            'owner'       => $reservation->machine->owner,
        ]);

        return $pdf->download('contrat-rentify-' . $reservation->id . '.pdf');
    }
}