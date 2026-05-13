<?php
// app/Mail/NewReservationMail.php
// Email envoyé au PROPRIÉTAIRE dès qu'un client fait une nouvelle demande
// Contient les détails de la demande + lien vers le dashboard

namespace App\Mail;

use App\Models\Reservation;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class NewReservationMail extends Mailable
{
    use Queueable, SerializesModels;

    public Reservation $reservation;

    public function __construct(Reservation $reservation)
    {
        $this->reservation = $reservation->load(['machine', 'client', 'owner']);
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: '🔔 Nouvelle demande de location — ' . $this->reservation->machine->name,
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.new_reservation',
            with: [
                'reservation' => $this->reservation,
                'machine'     => $this->reservation->machine,
                'client'      => $this->reservation->client,
                'owner'       => $this->reservation->owner,
                // Lien direct vers le dashboard propriétaire
                'dashboardUrl' => url('/dashboard/owner'),
            ],
        );
    }

    public function attachments(): array
    {
        return [];
    }
}