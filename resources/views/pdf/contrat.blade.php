<!DOCTYPE html>
<html lang="fr">
<head>
<meta charset="UTF-8">
<style>
    * { box-sizing: border-box; margin: 0; padding: 0; }
    body { font-family: DejaVu Sans, sans-serif; font-size: 12px; color: #1a1a1a; background: #fff; }
 
    /* ── Header ── */
    .header { background: #0F1B2D; color: #fff; padding: 28px 36px; display: flex; justify-content: space-between; align-items: center; }
    .logo-mark { width: 44px; height: 44px; background: #F59E0B; border-radius: 8px; display: inline-block; text-align: center; line-height: 44px; font-size: 22px; font-weight: 900; color: #111; vertical-align: middle; margin-right: 12px; }
    .logo-text { display: inline-block; vertical-align: middle; }
    .logo-name { font-size: 22px; font-weight: 900; letter-spacing: -0.5px; }
    .logo-name span { color: #F59E0B; }
    .logo-sub { font-size: 10px; color: rgba(255,255,255,0.45); margin-top: 2px; }
    .contrat-ref { text-align: right; }
    .contrat-ref-label { font-size: 10px; color: rgba(255,255,255,0.5); text-transform: uppercase; letter-spacing: 1px; }
    .contrat-ref-num { font-size: 18px; font-weight: 900; color: #F59E0B; margin-top: 2px; }
    .contrat-ref-date { font-size: 10px; color: rgba(255,255,255,0.5); margin-top: 3px; }
 
    /* ── Orange bar ── */
    .orange-bar { height: 4px; background: #F59E0B; }
 
    /* ── Titre contrat ── */
    .contrat-title-wrap { text-align: center; padding: 22px 36px 14px; border-bottom: 1px solid #F0F0F0; }
    .contrat-title { font-size: 16px; font-weight: 900; color: #0F1B2D; letter-spacing: -0.3px; text-transform: uppercase; }
    .contrat-subtitle { font-size: 10px; color: #9CA3AF; margin-top: 4px; }
 
    /* ── Sections ── */
    .content { padding: 20px 36px; }
    .section { margin-bottom: 18px; }
    .section-title {
        font-size: 10px; font-weight: 900; color: #F59E0B;
        text-transform: uppercase; letter-spacing: 1.5px;
        border-bottom: 2px solid #F59E0B; padding-bottom: 5px; margin-bottom: 12px;
    }
 
    /* ── Grid 2 colonnes ── */
    .grid-2 { width: 100%; border-collapse: collapse; margin-bottom: 0; }
    .grid-2 td { width: 50%; vertical-align: top; padding: 0; }
    .info-card {
        background: #F9FAFB; border: 1px solid #F0F0F0; border-radius: 8px;
        padding: 14px 16px; margin: 0 6px 0 0;
    }
    .info-card:last-child { margin-right: 0; margin-left: 6px; }
    .info-card-title { font-size: 10px; font-weight: 700; color: #0F1B2D; text-transform: uppercase; letter-spacing: .8px; margin-bottom: 10px; }
    .info-row { display: block; margin-bottom: 6px; }
    .info-label { font-size: 9px; color: #9CA3AF; text-transform: uppercase; letter-spacing: .5px; }
    .info-val { font-size: 12px; font-weight: 600; color: #111827; margin-top: 1px; }
 
    /* ── Machine details ── */
    .machine-card {
        background: #0F1B2D; border-radius: 8px; padding: 16px 20px; margin-bottom: 16px;
        display: table; width: 100%;
    }
    .machine-emoji { font-size: 36px; display: table-cell; vertical-align: middle; width: 60px; }
    .machine-info { display: table-cell; vertical-align: middle; padding-left: 16px; }
    .machine-name { font-size: 16px; font-weight: 900; color: #fff; letter-spacing: -0.5px; }
    .machine-type { font-size: 10px; color: #F59E0B; font-weight: 700; text-transform: uppercase; letter-spacing: 1px; margin-top: 3px; }
    .machine-location { font-size: 11px; color: rgba(255,255,255,0.5); margin-top: 4px; }
    .machine-price-block { display: table-cell; vertical-align: middle; text-align: right; }
    .machine-price { font-size: 28px; font-weight: 900; color: #F59E0B; letter-spacing: -1px; }
    .machine-price-unit { font-size: 10px; color: rgba(255,255,255,0.4); }
 
    /* ── Dates & durée ── */
    .dates-row { background: #F9FAFB; border: 1px solid #F0F0F0; border-radius: 8px; padding: 14px 20px; margin-bottom: 16px; }
    .dates-grid { width: 100%; border-collapse: collapse; }
    .dates-grid td { text-align: center; padding: 0 16px; border-right: 1px solid #E5E7EB; }
    .dates-grid td:last-child { border-right: none; }
    .date-label { font-size: 9px; color: #9CA3AF; text-transform: uppercase; letter-spacing: .8px; margin-bottom: 4px; }
    .date-val { font-size: 14px; font-weight: 900; color: #0F1B2D; }
    .date-day { font-size: 9px; color: #9CA3AF; margin-top: 2px; }
 
    /* ── Prix total ── */
    .pricing-table { width: 100%; border-collapse: collapse; margin-bottom: 16px; }
    .pricing-table tr td { padding: 8px 14px; border-bottom: 1px solid #F5F5F5; font-size: 12px; }
    .pricing-table tr:last-child td { border-bottom: none; }
    .pricing-table .total-row td { background: #0F1B2D; color: #fff; font-weight: 700; font-size: 14px; border-radius: 0; }
    .pricing-table .total-row td:last-child { color: #F59E0B; font-size: 18px; font-weight: 900; text-align: right; }
    .pricing-table td:last-child { text-align: right; font-weight: 600; }
    .pricing-card { background: #F9FAFB; border: 1px solid #F0F0F0; border-radius: 8px; overflow: hidden; }
 
    /* ── Conditions ── */
    .conditions { background: #FFFBEB; border: 1px solid #FDE68A; border-radius: 8px; padding: 14px 16px; margin-bottom: 16px; }
    .conditions p { font-size: 10px; color: #78350F; line-height: 1.7; margin-bottom: 4px; }
    .conditions p:last-child { margin-bottom: 0; }
 
    /* ── Signatures ── */
    .signatures { width: 100%; border-collapse: collapse; margin-top: 20px; }
    .signatures td { width: 50%; vertical-align: top; padding: 0; }
    .sign-box { border: 1.5px solid #E5E7EB; border-radius: 8px; padding: 14px 16px; margin: 0 8px; min-height: 90px; }
    .sign-title { font-size: 10px; font-weight: 700; color: #0F1B2D; text-transform: uppercase; letter-spacing: .8px; margin-bottom: 4px; }
    .sign-name { font-size: 12px; color: #9CA3AF; margin-bottom: 40px; }
    .sign-line { border-top: 1px solid #D1D5DB; margin-top: 8px; }
    .sign-label { font-size: 9px; color: #9CA3AF; margin-top: 4px; }
 
    /* ── Footer ── */
    .pdf-footer { background: #F9FAFB; border-top: 1px solid #F0F0F0; padding: 12px 36px; text-align: center; }
    .pdf-footer p { font-size: 9px; color: #9CA3AF; margin-bottom: 3px; }
    .pdf-footer strong { color: #0F1B2D; }
 
    /* ── Status badge ── */
    .status-confirmed { background: #D1FAE5; color: #065F46; font-size: 10px; font-weight: 700; padding: 3px 10px; border-radius: 100px; display: inline-block; }
</style>
</head>
<body>
 
    {{-- ═══ HEADER ═══ --}}
    <div class="header">
        <div>
            <span class="logo-mark">R</span>
            <div class="logo-text">
                <div class="logo-name">Rent<span>ify</span></div>
                <div class="logo-sub">Plateforme de location d'engins · Maroc</div>
            </div>
        </div>
        <div class="contrat-ref">
            <div class="contrat-ref-label">Contrat N°</div>
            <div class="contrat-ref-num">CTR-{{ str_pad($reservation->id, 5, '0', STR_PAD_LEFT) }}</div>
            <div class="contrat-ref-date">Émis le {{ now()->format('d/m/Y') }}</div>
        </div>
    </div>
    <div class="orange-bar"></div>
 
    {{-- ═══ TITRE ═══ --}}
    <div class="contrat-title-wrap">
        <div class="contrat-title">Contrat de Location de Machine de Travaux Publics</div>
        <div class="contrat-subtitle">
            Document officiel — Rentify.ma &nbsp;|&nbsp;
            Statut : <span class="status-confirmed">Confirmée</span>
        </div>
    </div>
 
    <div class="content">
 
        {{-- ═══ PARTIES ═══ --}}
        <div class="section">
            <div class="section-title">Parties du contrat</div>
            <table class="grid-2">
                <tr>
                    <td>
                        <div class="info-card">
                            <div class="info-card-title">🙋 Le Client (Locataire)</div>
                            <span class="info-row">
                                <div class="info-label">Nom complet</div>
                                <div class="info-val">{{ $reservation->client->name ?? '—' }}</div>
                            </span>
                            <span class="info-row">
                                <div class="info-label">Email</div>
                                <div class="info-val">{{ $reservation->client->email ?? '—' }}</div>
                            </span>
                            <span class="info-row">
                                <div class="info-label">Téléphone</div>
                                <div class="info-val">{{ $reservation->client->phone ?? '—' }}</div>
                            </span>
                        </div>
                    </td>
                    <td>
                        <div class="info-card" style="margin-left:12px;margin-right:0">
                            <div class="info-card-title">🏗 Le Propriétaire (Bailleur)</div>
                            <span class="info-row">
                                <div class="info-label">Nom complet</div>
                                <div class="info-val">{{ $reservation->machine->owner->name ?? '—' }}</div>
                            </span>
                            <span class="info-row">
                                <div class="info-label">Email</div>
                                <div class="info-val">{{ $reservation->machine->owner->email ?? '—' }}</div>
                            </span>
                            <span class="info-row">
                                <div class="info-label">Téléphone</div>
                                <div class="info-val">{{ $reservation->machine->owner->phone ?? '—' }}</div>
                            </span>
                        </div>
                    </td>
                </tr>
            </table>
        </div>
 
        {{-- ═══ MACHINE ═══ --}}
        <div class="section">
            <div class="section-title">Machine concernée</div>
            <div class="machine-card">
                <div class="machine-emoji">🏗</div>
                <div class="machine-info">
                    <div class="machine-name">{{ $reservation->machine->name ?? '—' }}</div>
                    <div class="machine-type">{{ $reservation->machine->type ?? '' }} · {{ $reservation->machine->marque ?? '' }} {{ $reservation->machine->modele ?? '' }}</div>
                    <div class="machine-location">📍 {{ $reservation->machine->location ?? '—' }}</div>
                </div>
                <div class="machine-price-block">
                    <div class="machine-price">{{ number_format($reservation->machine->price_per_day, 0, ',', ' ') }}</div>
                    <div class="machine-price-unit">DH / jour</div>
                </div>
            </div>
        </div>
 
        {{-- ═══ PÉRIODE ═══ --}}
        <div class="section">
            <div class="section-title">Période de location</div>
            <div class="dates-row">
                <table class="dates-grid">
                    <tr>
                        <td>
                            <div class="date-label">📅 Date de début</div>
                            <div class="date-val">{{ \Carbon\Carbon::parse($reservation->start_date)->format('d/m/Y') }}</div>
                            <div class="date-day">{{ \Carbon\Carbon::parse($reservation->start_date)->locale('fr')->dayName }}</div>
                        </td>
                        <td>
                            <div class="date-label">📅 Date de fin</div>
                            <div class="date-val">{{ \Carbon\Carbon::parse($reservation->end_date)->format('d/m/Y') }}</div>
                            <div class="date-day">{{ \Carbon\Carbon::parse($reservation->end_date)->locale('fr')->dayName }}</div>
                        </td>
                        <td>
                            <div class="date-label">⏱ Durée totale</div>
                            <div class="date-val">{{ $reservation->nb_days ?? \Carbon\Carbon::parse($reservation->start_date)->diffInDays($reservation->end_date) + 1 }}</div>
                            <div class="date-day">jour(s)</div>
                        </td>
                        <td>
                            <div class="date-label">💳 Mode de paiement</div>
                            <div class="date-val">{{ $reservation->type_tarif === 'heure' ? 'À l\'heure' : 'À la journée' }}</div>
                            <div class="date-day">tarification</div>
                        </td>
                    </tr>
                </table>
            </div>
        </div>
 
        {{-- ═══ TARIFICATION ═══ --}}
        <div class="section">
            <div class="section-title">Détail financier</div>
            <div class="pricing-card">
                <table class="pricing-table">
                    @php
                        $nbJours = $reservation->nb_days ?? \Carbon\Carbon::parse($reservation->start_date)->diffInDays($reservation->end_date) + 1;
                        $prixJour = $reservation->machine->price_per_day ?? 0;
                        $base = $prixJour * $nbJours;
                        $frais = round($base * 0.05, 2);
                        $total = $reservation->total_price ?? ($base + $frais);
                    @endphp
                    <tr>
                        <td>{{ $reservation->machine->name ?? '—' }}</td>
                        <td>{{ number_format($prixJour, 0, ',', ' ') }} DH × {{ $nbJours }} jour(s)</td>
                        <td>{{ number_format($base, 0, ',', ' ') }} DH</td>
                    </tr>
                    <tr>
                        <td colspan="2">Frais de service Rentify (5%)</td>
                        <td>{{ number_format($frais, 0, ',', ' ') }} DH</td>
                    </tr>
                    <tr class="total-row">
                        <td colspan="2"><strong>TOTAL À PAYER</strong></td>
                        <td>{{ number_format($total, 0, ',', ' ') }} DH</td>
                    </tr>
                </table>
            </div>
        </div>
 
        {{-- ═══ CONDITIONS ═══ --}}
        <div class="section">
            <div class="section-title">Conditions générales</div>
            <div class="conditions">
                <p>• Le locataire s'engage à utiliser la machine conformément à sa destination et aux règles de sécurité en vigueur.</p>
                <p>• Toute dégradation de la machine due à une mauvaise utilisation sera à la charge du locataire.</p>
                <p>• Le bailleur certifie que la machine est en bon état de fonctionnement et conforme aux normes de sécurité.</p>
                <p>• En cas d'annulation, le locataire doit prévenir au moins 48h avant la date de début de location.</p>
                <p>• Le présent contrat est soumis au droit marocain. Tout litige sera soumis aux juridictions compétentes de Casablanca.</p>
                <p>• Rentify.ma agit en tant qu'intermédiaire et décline toute responsabilité pour les dommages causés pendant la période de location.</p>
            </div>
        </div>
 
        {{-- ═══ SIGNATURES ═══ --}}
        <div class="section">
            <div class="section-title">Signatures</div>
            <table class="signatures">
                <tr>
                    <td>
                        <div class="sign-box">
                            <div class="sign-title">Le Client (Locataire)</div>
                            <div class="sign-name">{{ $reservation->client->name ?? '—' }}</div>
                            <div class="sign-line"></div>
                            <div class="sign-label">Signature et date</div>
                        </div>
                    </td>
                    <td>
                        <div class="sign-box" style="margin-left:16px;margin-right:0">
                            <div class="sign-title">Le Propriétaire (Bailleur)</div>
                            <div class="sign-name">{{ $reservation->machine->owner->name ?? '—' }}</div>
                            <div class="sign-line"></div>
                            <div class="sign-label">Signature et date</div>
                        </div>
                    </td>
                </tr>
            </table>
        </div>
 
    </div>
 
    {{-- ═══ FOOTER ═══ --}}
    <div class="pdf-footer">
        <p><strong>Rentify.ma</strong> — Plateforme de location d'engins de travaux publics au Maroc</p>
        <p>contact@rentify.ma · +212 6 00 00 00 00 · Casablanca, Maroc</p>
        <p style="margin-top:6px;color:#D1D5DB">Document généré automatiquement le {{ now()->format('d/m/Y à H:i') }} — Contrat N° CTR-{{ str_pad($reservation->id, 5, '0', STR_PAD_LEFT) }}</p>
    </div>
 
</body>
</html>
 