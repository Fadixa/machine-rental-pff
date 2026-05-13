{{-- resources/views/emails/new_reservation.blade.php --}}
{{-- Email HTML envoyé au PROPRIÉTAIRE à chaque nouvelle demande de location --}}
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Nouvelle demande de location</title>
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
        .header .logo { font-size: 28px; font-weight: 800; color: #fff; }
        .header .logo span { color: #F59E0B; }
        .header .badge {
            display: inline-block; margin-top: 14px;
            background: #3b82f6; color: #fff;
            font-weight: 700; font-size: 13px;
            padding: 6px 18px; border-radius: 20px;
        }

        /* Alerte urgence */
        .alert-banner {
            background: #fffbeb; border-bottom: 2px solid #F59E0B;
            padding: 14px 40px; font-size: 14px;
            color: #92400e; text-align: center; font-weight: 600;
        }

        .body { padding: 36px 40px; }
        .greeting { font-size: 20px; font-weight: 600; color: #0F1B2D; margin-bottom: 12px; }
        .intro { font-size: 15px; color: #555; line-height: 1.7; margin-bottom: 28px; }

        /* Carte demande */
        .demand-card {
            background: #f8fafc; border: 1px solid #e2e8f0;
            border-left: 4px solid #3b82f6;
            border-radius: 8px; padding: 20px 24px; margin-bottom: 24px;
        }
        .demand-card h3 { font-size: 17px; color: #0F1B2D; font-weight: 700; margin-bottom: 14px; }
        .detail-row {
            display: flex; justify-content: space-between;
            padding: 9px 0; border-bottom: 1px solid #eee; font-size: 14px;
        }
        .detail-row:last-child { border-bottom: none; }
        .detail-label { color: #888; font-weight: 500; }
        .detail-value { color: #0F1B2D; font-weight: 600; }
        .detail-value.revenue { color: #16a34a; font-size: 16px; }

        /* Profil client */
        .client-card {
            background: #eff6ff; border: 1px solid #bfdbfe;
            border-radius: 8px; padding: 16px 20px; margin-bottom: 28px;
        }
        .client-card h4 { color: #1e40af; font-size: 14px; margin-bottom: 10px; }
        .client-info { font-size: 14px; color: #555; line-height: 2; }
        .client-info strong { color: #0F1B2D; }

        /* Boutons CTA */
        .cta-wrap { text-align: center; margin-bottom: 30px; }
        .cta-btn {
            display: inline-block; background: #F59E0B;
            color: #0F1B2D !important; text-decoration: none;
            font-weight: 700; font-size: 15px;
            padding: 14px 36px; border-radius: 8px; margin: 6px;
        }
        .cta-btn.secondary {
            background: #0F1B2D; color: #F59E0B !important;
        }

        /* Timer info */
        .timer-info {
            background: #fef2f2; border: 1px solid #fecaca;
            border-radius: 8px; padding: 14px 20px;
            font-size: 13px; color: #991b1b;
            margin-bottom: 28px; text-align: center;
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
        <div class="badge">🔔 Nouvelle demande de location</div>
    </div>

    {{-- Bandeau d'urgence --}}
    <div class="alert-banner">
        ⚡ Action requise — Veuillez répondre dans les 48h pour confirmer votre disponibilité
    </div>

    <div class="body">

        <p class="greeting">Bonjour {{ $owner->name }},</p>
        <p class="intro">
            Un client souhaite louer votre machine <strong>« {{ $machine->name }} »</strong>.
            Connectez-vous à votre tableau de bord pour <strong>accepter ou refuser</strong>
            cette demande avant qu'elle n'expire.
        </p>

        {{-- Détails de la demande --}}
        <div class="demand-card">
            <h3>📋 Détails de la demande</h3>
            <div class="detail-row">
                <span class="detail-label">Machine demandée</span>
                <span class="detail-value">{{ $machine->name }}</span>
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
                <span class="detail-label">Durée totale</span>
                <span class="detail-value">
                    {{ \Carbon\Carbon::parse($reservation->start_date)
                        ->diffInDays($reservation->end_date) }} jour(s)
                </span>
            </div>
            <div class="detail-row">
                <span class="detail-label">Revenu potentiel</span>
                <span class="detail-value revenue">
                    {{ number_format($reservation->total_price, 2, ',', ' ') }} MAD
                </span>
            </div>
            <div class="detail-row">
                <span class="detail-label">N° Demande</span>
                <span class="detail-value">#{{ str_pad($reservation->id, 6, '0', STR_PAD_LEFT) }}</span>
            </div>
        </div>

        {{-- Profil du client --}}
        <div class="client-card">
            <h4>👤 Profil du client demandeur</h4>
            <div class="client-info">
                <strong>Nom :</strong> {{ $client->name }}<br>
                <strong>Email :</strong> {{ $client->email }}<br>
                <strong>Téléphone :</strong> {{ $client->phone ?? 'Non renseigné' }}<br>
                <strong>Membre depuis :</strong>
                {{ \Carbon\Carbon::parse($client->created_at)->format('M Y') }}
            </div>
        </div>

        {{-- Avertissement délai --}}
        <div class="timer-info">
            ⏰ Sans réponse de votre part dans <strong>48 heures</strong>,
            la demande sera automatiquement annulée et le client en sera notifié.
        </div>

        {{-- CTAs --}}
        <div class="cta-wrap">
            <a href="{{ $dashboardUrl }}" class="cta-btn">
                ✅ Gérer cette demande →
            </a>
            <a href="{{ url('/machines') }}" class="cta-btn secondary">
                Voir mes annonces
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