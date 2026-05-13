{{-- resources/views/emails/reservation_accepted.blade.php --}}
{{-- Email HTML envoyé au client après acceptation de sa réservation --}}
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Réservation confirmée</title>
    <style>
        /* ── Reset & base ── */
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body {
            font-family: 'Segoe UI', Arial, sans-serif;
            background-color: #f0f4f8;
            color: #333;
        }

        /* ── Conteneur principal ── */
        .wrapper {
            max-width: 620px;
            margin: 30px auto;
            background: #ffffff;
            border-radius: 12px;
            overflow: hidden;
            box-shadow: 0 4px 20px rgba(0,0,0,0.10);
        }

        /* ── Header orange/navy ── */
        .header {
            background: linear-gradient(135deg, #0F1B2D 0%, #1a2f4a 100%);
            padding: 36px 40px;
            text-align: center;
        }
        .header .logo {
            font-size: 28px;
            font-weight: 800;
            color: #ffffff;
            letter-spacing: 1px;
        }
        .header .logo span { color: #F59E0B; }
        .header .badge {
            display: inline-block;
            margin-top: 14px;
            background: #F59E0B;
            color: #0F1B2D;
            font-weight: 700;
            font-size: 13px;
            padding: 6px 18px;
            border-radius: 20px;
            letter-spacing: 0.5px;
        }

        /* ── Corps de l'email ── */
        .body { padding: 36px 40px; }

        .greeting {
            font-size: 20px;
            font-weight: 600;
            color: #0F1B2D;
            margin-bottom: 12px;
        }
        .intro {
            font-size: 15px;
            color: #555;
            line-height: 1.7;
            margin-bottom: 28px;
        }

        /* ── Carte machine ── */
        .machine-card {
            background: #f8fafc;
            border: 1px solid #e2e8f0;
            border-left: 4px solid #F59E0B;
            border-radius: 8px;
            padding: 20px 24px;
            margin-bottom: 28px;
        }
        .machine-card h3 {
            font-size: 17px;
            color: #0F1B2D;
            font-weight: 700;
            margin-bottom: 14px;
        }
        .detail-row {
            display: flex;
            justify-content: space-between;
            padding: 8px 0;
            border-bottom: 1px solid #eee;
            font-size: 14px;
        }
        .detail-row:last-child { border-bottom: none; }
        .detail-label { color: #888; font-weight: 500; }
        .detail-value { color: #0F1B2D; font-weight: 600; }
        .detail-value.price { color: #F59E0B; font-size: 16px; }

        /* ── Info PDF ── */
        .pdf-notice {
            background: #fffbeb;
            border: 1px solid #fde68a;
            border-radius: 8px;
            padding: 16px 20px;
            font-size: 13px;
            color: #92400e;
            margin-bottom: 28px;
            line-height: 1.6;
        }
        .pdf-notice strong { color: #78350f; }

        /* ── Bouton CTA ── */
        .cta-wrap { text-align: center; margin-bottom: 30px; }
        .cta-btn {
            display: inline-block;
            background: #F59E0B;
            color: #0F1B2D !important;
            text-decoration: none;
            font-weight: 700;
            font-size: 15px;
            padding: 14px 36px;
            border-radius: 8px;
            letter-spacing: 0.3px;
        }

        /* ── Footer ── */
        .footer {
            background: #f8fafc;
            border-top: 1px solid #e2e8f0;
            padding: 22px 40px;
            text-align: center;
            font-size: 12px;
            color: #999;
            line-height: 1.8;
        }
        .footer a { color: #F59E0B; text-decoration: none; }
    </style>
</head>
<body>
<div class="wrapper">

    {{-- ── En-tête ── --}}
    <div class="header">
        <div class="logo">Rent<span>ify</span></div>
        <div class="badge">✅ Réservation confirmée</div>
    </div>

    {{-- ── Corps ── --}}
    <div class="body">

        <p class="greeting">Bonjour {{ $client->name }},</p>
        <p class="intro">
            Excellente nouvelle ! Votre demande de location a été
            <strong>acceptée par le propriétaire</strong>.<br>
            Vous trouverez ci-joint votre contrat de location en PDF,
            à conserver pour toute la durée de la location.
        </p>

        {{-- Détails de la réservation --}}
        <div class="machine-card">
            <h3>🚜 Détails de votre location</h3>

            <div class="detail-row">
                <span class="detail-label">Machine</span>
                <span class="detail-value">{{ $machine->name }}</span>
            </div>
            <div class="detail-row">
                <span class="detail-label">Catégorie</span>
                <span class="detail-value">{{ $machine->category ?? '—' }}</span>
            </div>
            <div class="detail-row">
                <span class="detail-label">Propriétaire</span>
                <span class="detail-value">{{ $reservation->owner->name }}</span>
            </div>
            <div class="detail-row">
                <span class="detail-label">Date de début</span>
                <span class="detail-value">
                    {{ \Carbon\Carbon::parse($reservation->start_date)->format('d/m/Y') }}
                </span>
            </div>
            <div class="detail-row">
                <span class="detail-label">Date de fin</span>
                <span class="detail-value">
                    {{ \Carbon\Carbon::parse($reservation->end_date)->format('d/m/Y') }}
                </span>
            </div>
            <div class="detail-row">
                <span class="detail-label">Durée</span>
                <span class="detail-value">
                    {{ \Carbon\Carbon::parse($reservation->start_date)
                        ->diffInDays($reservation->end_date) }} jour(s)
                </span>
            </div>
            <div class="detail-row">
                <span class="detail-label">Montant total</span>
                <span class="detail-value price">
                    {{ number_format($reservation->total_price, 2, ',', ' ') }} MAD
                </span>
            </div>
            <div class="detail-row">
                <span class="detail-label">N° Réservation</span>
                <span class="detail-value">#{{ str_pad($reservation->id, 6, '0', STR_PAD_LEFT) }}</span>
            </div>
        </div>

        {{-- Avertissement PDF joint --}}
        <div class="pdf-notice">
            📎 <strong>Contrat joint à cet email :</strong>
            Le fichier <em>contrat-rentify-{{ $reservation->id }}.pdf</em> est en pièce jointe.
            Téléchargez-le et conservez-le — il vous sera demandé lors de la remise du matériel.
        </div>

        {{-- Bouton vers tableau de bord --}}
        <div class="cta-wrap">
            <a href="{{ url('/dashboard/client') }}" class="cta-btn">
                Voir mon tableau de bord →
            </a>
        </div>

    </div>{{-- /body --}}

    {{-- ── Pied de page ── --}}
    <div class="footer">
        Cet email a été envoyé automatiquement par
        <a href="{{ url('/') }}">Rentify Maroc</a>.<br>
        Pour toute question, contactez-nous à
        <a href="mailto:support@rentify.ma">support@rentify.ma</a><br>
        &copy; {{ date('Y') }} Rentify — Tous droits réservés.
    </div>

</div>
</body>
</html>