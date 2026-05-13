<?php
// app/Mail/ReservationRejectedMail.php
// Email envoyé au CLIENT quand sa réservation est REFUSÉE
// Inclut le motif de refus fourni par le propriétaire

namespace App\Mail;

use App\Models\Reservation;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class ReservationRejectedMail extends Mailable
{
    use Queueable, SerializesModels;

    public Reservation $reservation;
    public string $motif; // Motif de refus saisi par le propriétaire

    /**
     * @param Reservation $reservation  La réservation refusée
     * @param string      $motif        Raison du refus (peut être vide)
     */
    public function __construct(Reservation $reservation, string $motif = '')
    {
        $this->reservation = $reservation->load(['machine', 'client', 'owner']);
        // Motif par défaut si le propriétaire n'en a pas saisi
        $this->motif = $motif ?: 'Le propriétaire n\'a pas précisé de motif.';
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: '❌ Réservation refusée — ' . $this->reservation->machine->name,
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.reservation_rejected',
            with: [
                'reservation' => $this->reservation,
                'machine'     => $this->reservation->machine,
                'client'      => $this->reservation->client,
                'motif'       => $this->motif,
            ],
        );
    }

    // Pas de pièce jointe pour un refus
    public function attachments(): array
    {
        return [];
    }
}