<?php
// app/Http/Controllers/ReservationController.php
// MODIFICATION : ajout des notifications email (Feature 3)
// Les méthodes store(), accept(), reject() reçoivent les envois Mail

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
    // ─────────────────────────────────────────────────────────────
    // CRÉER UNE RÉSERVATION → notifier le propriétaire
    // ─────────────────────────────────────────────────────────────
    public function store(Request $request)
    {
        $request->validate([
            'machine_id'  => 'required|exists:machines,id',
            'start_date'  => 'required|date|after_or_equal:today',
            'end_date'    => 'required|date|after:start_date',
        ]);

        $machine = Machine::findOrFail($request->machine_id);

        // Calculer le prix total en fonction des jours
        $jours = \Carbon\Carbon::parse($request->start_date)
                    ->diffInDays($request->end_date);

        $reservation = Reservation::create([
            'machine_id'  => $machine->id,
            'client_id'   => Auth::id(),
            'owner_id'    => $machine->owner_id,
            'start_date'  => $request->start_date,
            'end_date'    => $request->end_date,
            'total_price' => $machine->price_per_day * $jours,
            'status'      => 'pending',
        ]);

        // ── Notification email au propriétaire ──────────────────
        try {
            $owner = $reservation->owner; // Récupère le modèle owner
            Mail::to($owner->email)
                ->send(new NewReservationMail($reservation));

            Log::info("📧 Email NewReservation envoyé à {$owner->email} — Réservation #{$reservation->id}");
        } catch (\Exception $e) {
            // Ne pas bloquer la réservation si l'email échoue
            Log::error("❌ Échec email NewReservation: " . $e->getMessage());
        }
        // ────────────────────────────────────────────────────────

        return response()->json([
            'message'     => 'Réservation créée avec succès.',
            'reservation' => $reservation->load('machine', 'client', 'owner'),
        ], 201);
    }

    // ─────────────────────────────────────────────────────────────
    // ACCEPTER UNE RÉSERVATION → notifier le client avec PDF joint
    // ─────────────────────────────────────────────────────────────
    public function accept(Reservation $reservation)
    {
        // Vérifier que le propriétaire connecté est bien le bon
        if ($reservation->owner_id !== Auth::id()) {
            return response()->json(['message' => 'Action non autorisée.'], 403);
        }

        $reservation->update(['status' => 'accepted']);

        // ── Notification email au client (avec contrat PDF joint) ──
        try {
            $client = $reservation->client;
            Mail::to($client->email)
                ->send(new ReservationAcceptedMail($reservation));

            Log::info("📧 Email Accepted envoyé à {$client->email} — Réservation #{$reservation->id}");
        } catch (\Exception $e) {
            Log::error("❌ Échec email Accepted: " . $e->getMessage());
        }
        // ────────────────────────────────────────────────────────

        return response()->json([
            'message'     => 'Réservation acceptée.',
            'reservation' => $reservation->fresh(['machine', 'client', 'owner']),
        ]);
    }

    // ─────────────────────────────────────────────────────────────
    // REFUSER UNE RÉSERVATION → notifier le client avec le motif
    // ─────────────────────────────────────────────────────────────
    public function reject(Request $request, Reservation $reservation)
    {
        if ($reservation->owner_id !== Auth::id()) {
            return response()->json(['message' => 'Action non autorisée.'], 403);
        }

        // Le motif est optionnel — champ 'motif' dans le body JSON
        $motif = $request->input('motif', '');

        $reservation->update([
            'status' => 'rejected',
            'motif'  => $motif, // Assurez-vous que la colonne existe en migration
        ]);

        // ── Notification email au client avec le motif ──────────
        try {
            $client = $reservation->client;
            Mail::to($client->email)
                ->send(new ReservationRejectedMail($reservation, $motif));

            Log::info("📧 Email Rejected envoyé à {$client->email} — Réservation #{$reservation->id}");
        } catch (\Exception $e) {
            Log::error("❌ Échec email Rejected: " . $e->getMessage());
        }
        // ────────────────────────────────────────────────────────

        return response()->json([
            'message'     => 'Réservation refusée.',
            'reservation' => $reservation->fresh(['machine', 'client', 'owner']),
        ]);
    }

    // ─────────────────────────────────────────────────────────────
    // TÉLÉCHARGER LE CONTRAT PDF (méthode existante — inchangée)
    // ─────────────────────────────────────────────────────────────
    public function downloadContrat(Reservation $reservation)
    {
        // Seul le client concerné ou le propriétaire peut télécharger
        if (!in_array(Auth::id(), [$reservation->client_id, $reservation->owner_id])) {
            return response()->json(['message' => 'Accès refusé.'], 403);
        }

        $reservation->load('machine', 'client', 'owner');

        $pdf = Pdf::loadView('pdf.contrat', [
            'reservation' => $reservation,
            'machine'     => $reservation->machine,
            'client'      => $reservation->client,
            'owner'       => $reservation->owner,
        ]);

        $nomFichier = 'contrat-rentify-' . $reservation->id . '.pdf';

        return $pdf->download($nomFichier);
    }
}