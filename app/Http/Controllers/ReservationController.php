<?php

namespace App\Http\Controllers;

use App\Models\Machine;
use App\Models\Reservation;
use Illuminate\Http\Request;
use Carbon\Carbon;

class ReservationController extends Controller
{
    // POST /api/reservations — client crée une réservation
    public function store(Request $request)
    {
        $data = $request->validate([
            'machine_id'  => 'required|exists:machines,id',
            'start_date'  => 'required|date|after_or_equal:today',
            'end_date'    => 'required|date|after_or_equal:start_date',
        ]);

        $machine = Machine::findOrFail($data['machine_id']);

        // 1. Vérifier disponibilité
        if (! $machine->isAvailableOn($data['start_date'], $data['end_date'])) {
            return response()->json([
                'message' => 'Machine déjà réservée sur cette période.'
            ], 422);
        }

        // 2. Calculer le prix total
        $totalPrice = $machine->calculatePrice($data['start_date'], $data['end_date']);

        // 3. Créer la réservation
        $reservation = Reservation::create([
            'client_id'  => $request->user()->id,
            'machine_id' => $machine->id,
            'start_date' => $data['start_date'],
            'end_date'   => $data['end_date'],
            'total_price'=> $totalPrice,
            'status'     => 'pending',
        ]);

        return response()->json(
            $reservation->load('machine', 'client'), 201
        );
    }

    // GET /api/reservations — liste selon le rôle
    public function index(Request $request)
    {
        $user = $request->user();

        $reservations = $user->isOwner()
            ? Reservation::forOwner($user->id)
                           ->with(['client:id,name,phone', 'machine'])
                           ->latest()->get()
            : $user->reservations()
                   ->with('machine.primaryImage')
                   ->latest()->get();

        return response()->json($reservations);
    }

    // PATCH /api/reservations/{reservation}/accept
    public function accept(Request $request, Reservation $reservation)
    {
        if ($request->user()->id !== $reservation->machine->owner_id) {
            return response()->json(['message' => 'Interdit'], 403);
        }
        $reservation->accept();
        return response()->json($reservation);
    }

    // PATCH /api/reservations/{reservation}/reject
    public function reject(Request $request, Reservation $reservation)
    {
        if ($request->user()->id !== $reservation->machine->owner_id) {
            return response()->json(['message' => 'Interdit'], 403);
        }
        $request->validate(['reason' => 'nullable|string']);
        $reservation->reject($request->reason ?? '');
        return response()->json($reservation);
    }

    // PATCH /api/reservations/{reservation}/complete
    public function complete(Request $request, Reservation $reservation)
    {
        if ($request->user()->id !== $reservation->machine->owner_id) {
            return response()->json(['message' => 'Interdit'], 403);
        }
        $reservation->complete();
        return response()->json($reservation);
    }
}