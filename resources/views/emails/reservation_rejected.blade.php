{{-- resources/views/emails/reservation_rejected.blade.php --}}
{{-- Email HTML envoyé au client après refus de sa réservation --}}
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Réservation refusée</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: 'Segoe UI', Arial, sans-serif; background-color: #f0f4f8; color: #333; }
        .wrapper {
            max-width: 620px; margin: 30px auto;
            background: #fff; border-radius: 12px;
            overflow: hidden; box-shadow: 0 4px 20px rgba(0,0,0,0.10);
        }
        .header {
            background: linear-gradient(135deg, #0F1B2D 0%, #1a2f4a 100%);
            padding: 36px 40px; text-align: center;
        }
        .header .logo { font-size: 28px; font-weight: 800; color: #fff; letter-spacing: 1px; }
        .header .logo span { color: #F59E0B; }
        .header .badge {
            display: inline-block; margin-top: 14px;
            background: #ef4444; color: #fff;
            font-weight: 700; font-size: 13px;
            padding: 6px 18px; border-radius: 20px;
        }
        .body { padding: 36px 40px; }
        .greeting { font-size: 20px; font-weight: 600; color: #0F1B2D; margin-bottom: 12px; }
        .intro { font-size: 15px; color: #555; line-height: 1.7; margin-bottom: 28px; }

        /* Carte machine refusée */
        .machine-card {
            background: #fef2f2; border: 1px solid #fecaca;
            border-left: 4px solid #ef4444;
            border-radius: 8px; padding: 20px 24px; margin-bottom: 24px;
        }
        .machine-card h3 { font-size: 17px; color: #0F1B2D; font-weight: 700; margin-bottom: 14px; }
        .detail-row {
            display: flex; justify-content: space-between;
            padding: 8px 0; border-bottom: 1px solid #fee2e2;
            font-size: 14px;
        }
        .detail-row:last-child { border-bottom: none; }
        .detail-label { color: #888; font-weight: 500; }
        .detail-value { color: #0F1B2D; font-weight: 600; }

        /* Bloc motif de refus */
        .motif-block {
            background: #fff7ed; border: 1px solid #fed7aa;
            border-left: 4px solid #f97316;
            border-radius: 8px; padding: 18px 22px; margin-bottom: 28px;
        }
        .motif-block h4 { color: #c2410c; font-size: 14px; margin-bottom: 8px; }
        .motif-block p { color: #7c3aed; font-size: 15px; font-style: italic; line-height: 1.6; }

        /* Suggestions */
        .suggestions {
            background: #f0fdf4; border: 1px solid #bbf7d0;
            border-radius: 8px; padding: 18px 22px; margin-bottom: 28px;
        }
        .suggestions h4 { color: #166534; font-size: 14px; margin-bottom: 10px; }
        .suggestions ul { padding-left: 18px; }
        .suggestions li { font-size: 14px; color: #555; line-height: 1.8; }

        .cta-wrap { text-align: center; margin-bottom: 30px; }
        .cta-btn {
            display: inline-block; background: #F59E0B;
            color: #0F1B2D !important; text-decoration: none;
            font-weight: 700; font-size: 15px;
            padding: 14px 36px; border-radius: 8px;
        }
        .footer {
            background: #f8fafc; border-top: 1px solid #e2e8f0;
            padding: 22px 40px; text-align: center;
            font-size: 12px; color: #999; line-height: 1.8;
        }
        .footer a { color: #F59E0B; text-decoration: none; }
    </style>
</head>
<body>
<div class="wrapper">

    <div class="header">
        <div class="logo">Rent<span>ify</span></div>
        <div class="badge">❌ Réservation refusée</div>
    </div>

    <div class="body">

        <p class="greeting">Bonjour {{ $client->name }},</p>
        <p class="intro">
            Nous sommes désolés de vous informer que votre demande de location
            a été <strong>refusée par le propriétaire</strong>.
            Ne vous découragez pas — de nombreuses autres machines sont disponibles sur Rentify !
        </p>

        {{-- Détails de la réservation refusée --}}
        <div class="machine-card">
            <h3>🚜 Réservation concernée</h3>
            <div class="detail-row">
                <span class="detail-label">Machine</span>
                <span class="detail-value">{{ $machine->name }}</span>
            </div>
            <div class="detail-row">
                <span class="detail-label">Dates demandées</span>
                <span class="detail-value">
                    {{ \Carbon\Carbon::parse($reservation->start_date)->format('d/m/Y') }}
                    → {{ \Carbon\Carbon::parse($reservation->end_date)->format('d/m/Y') }}
                </span>
            </div>
            <div class="detail-row">
                <span class="detail-label">N° Réservation</span>
                <span class="detail-value">#{{ str_pad($reservation->id, 6, '0', STR_PAD_LEFT) }}</span>
            </div>
        </div>

        {{-- Motif du refus --}}
        <div class="motif-block">
            <h4>💬 Motif communiqué par le propriétaire :</h4>
            <p>« {{ $motif }} »</p>
        </div>

        {{-- Suggestions pour le client --}}
        <div class="suggestions">
            <h4>✨ Que faire maintenant ?</h4>
            <ul>
                <li>Rechercher une machine similaire dans notre catalogue</li>
                <li>Modifier vos dates et renouveler votre demande</li>
                <li>Contacter notre support si vous pensez qu'il y a une erreur</li>
            </ul>
        </div>

        <div class="cta-wrap">
            <a href="{{ url('/machines') }}" class="cta-btn">
                Parcourir le catalogue →
            </a>
        </div>

    </div>

    <div class="footer">
        Cet email a été envoyé automatiquement par
        <a href="{{ url('/') }}">Rentify Maroc</a>.<br>
        <a href="mailto:support@rentify.ma">support@rentify.ma</a>
        &nbsp;|&nbsp; &copy; {{ date('Y') }} Rentify
    </div>

</div>
</body>
</html>