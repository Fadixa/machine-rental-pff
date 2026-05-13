<?php
// app/Mail/ReservationAcceptedMail.php
// Email envoyé au CLIENT quand sa réservation est ACCEPTÉE
// Contient le contrat PDF en pièce jointe

namespace App\Mail;

use App\Models\Reservation;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Mail\Mailables\Attachment;
use Illuminate\Queue\SerializesModels;
use Barryvdh\DomPDF\Facade\Pdf;

class ReservationAcceptedMail extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * La réservation concernée
     */
    public Reservation $reservation;

    /**
     * Constructeur — reçoit la réservation avec ses relations chargées
     */
    public function __construct(Reservation $reservation)
    {
        // Charger toutes les relations nécessaires au template
        $this->reservation = $reservation->load([
            'machine',
            'machine.images',
            'client',
            'owner',
        ]);
    }

    /**
     * Objet de l'email
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: '✅ Réservation confirmée — ' . $this->reservation->machine->name,
        );
    }

    /**
     * Contenu HTML de l'email (vue Blade)
     */
    public function content(): Content
    {
        return new Content(
            view: 'emails.reservation_accepted',
            with: [
                'reservation' => $this->reservation,
                'machine'     => $this->reservation->machine,
                'client'      => $this->reservation->client,
                'owner'       => $this->reservation->owner,
            ],
        );
    }

    /**
     * Pièces jointes — génère le contrat PDF à la volée via DomPDF
     */
    public function attachments(): array
    {
        // Générer le PDF du contrat (même template que le téléchargement manuel)
        $pdf = Pdf::loadView('pdf.contrat', [
            'reservation' => $this->reservation,
            'machine'     => $this->reservation->machine,
            'client'      => $this->reservation->client,
            'owner'       => $this->reservation->owner,
        ]);

        $nomFichier = 'contrat-rentify-' . $this->reservation->id . '.pdf';

        return [
            // Attachment depuis les données brutes du PDF généré
            Attachment::fromData(
                fn () => $pdf->output(),
                $nomFichier
            )->withMime('application/pdf'),
        ];
    }
}