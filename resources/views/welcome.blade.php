@extends('layouts.app')

@section('title', 'Rentify — Location d\'engins BTP au Maroc')

@push('styles')
<style>
/* ===== GOOGLE FONTS ===== */
@import url('https://fonts.googleapis.com/css2?family=Playfair+Display:ital,wght@0,700;0,800;0,900;1,700&family=DM+Sans:wght@300;400;500;600&display=swap');

/* ===== VARIABLES ===== */
:root {
    --gold: #D4AF37;
    --gold-dk: #9A7D20;
    --gold-lt: #F5E88A;
    --gold-pale: #FEF9E7;
    --gold-glow: rgba(212,175,55,.20);
    --cream: #FAF7F0;
    --cream2: #F0EBE0;
    --cream3: #E8DDD0;
    --navy: #1a1a2e;
    --txt-dark: #1a1a2e;
    --txt-mid: #5a5660;
    --txt-light: #9992a4;
}

* { box-sizing: border-box; }
body { overflow-x: hidden; }

/* =====================================================
   HERO — pixel-perfect match Banner_Accueil.jpg
   Image de fond : hero-bg.jpeg (excavatrice studio)
   ===================================================== */

/* Wrapper global du hero */
.hero {
    position: relative;
    height: calc(100vh - 64px);
    min-height: 480px;
    max-height: 700px;
    background-color: #F5EFE0; /* fallback couleur crème chaude */
    overflow: hidden;
    display: flex;
    align-items: center;
}

/* ── Image de fond hero-bg.jpeg ─────────────────────
   Positionnée à droite, occupe la moitié droite,
   légèrement débordante en haut pour que la flèche
   du bras dépasse le header exactement comme le banner
   ───────────────────────────────────────────────────*/
.hero-bg-img {
    position: absolute;
    top: -30px;          /* bras dépasse légèrement vers le haut */
    right: -2%;
    width: 58%;          /* occupe 58% de la largeur */
    height: calc(100% + 30px);
    object-fit: cover;
    object-position: left center; /* coupe à gauche, garde la machine à droite */
    z-index: 1;
    pointer-events: none;
}

/* ── Dégradé de masque : fondu à gauche de l'image ──
   Permet au texte d'être lisible sans bloc séparé
   ───────────────────────────────────────────────────*/
.hero-bg-mask {
    position: absolute;
    top: 0; left: 0; right: 0; bottom: 0;
    z-index: 2;
    pointer-events: none;
    /* fondu crème de gauche vers transparent à ~55% */
    background: linear-gradient(
        to right,
        #F5EFE0 0%,
        #F5EFE0 38%,
        rgba(245,239,224,.92) 46%,
        rgba(245,239,224,.55) 52%,
        rgba(245,239,224,.0) 60%
    );
}

/* ── Contenu texte ──────────────────────────────── */
.hero-inner {
    position: relative;
    z-index: 3;
    width: 100%;
    max-width: 1320px;
    margin: 0 auto;
    padding: 0 56px;
    display: flex;
    flex-direction: column;
    justify-content: center;
}

.hero-left {
    max-width: 540px;    /* colonne texte ≈ 40% */
    display: flex;
    flex-direction: column;
}

/* ── Titre ──────────────────────────────────────── */
.hero-headline {
    font-family: 'Playfair Display', Georgia, serif;
    font-size: clamp(2.4rem, 3.8vw, 3.6rem);
    font-weight: 800;
    line-height: 1.1;
    color: #1a1a2e;
    margin: 0 0 6px;
    letter-spacing: -0.5px;
}
.hero-headline .accent { color: #9A7D20; }

/* ── Description ────────────────────────────────── */
.hero-desc {
    font-family: 'DM Sans', sans-serif;
    font-size: .97rem;
    line-height: 1.65;
    color: #5a5660;
    max-width: 440px;
    margin: 16px 0 28px;
    padding-left: 14px;
    border-left: 3px solid #D4AF37;
}

/* ── Boutons ─────────────────────────────────────── */
.hero-btns {
    display: flex;
    gap: 12px;
    align-items: center;
    flex-wrap: wrap;
    margin-bottom: 44px;
}

/* Bouton principal gold */
.btn-hero-primary {
    display: inline-flex;
    align-items: center;
    gap: 8px;
    padding: 13px 24px;
    background: #D4AF37;
    color: #1a1a2e;
    border: none;
    border-radius: 7px;
    font-family: 'DM Sans', sans-serif;
    font-size: .93rem;
    font-weight: 700;
    cursor: pointer;
    text-decoration: none;
    transition: background .18s, transform .15s, box-shadow .18s;
    letter-spacing: .15px;
    white-space: nowrap;
}
.btn-hero-primary:hover {
    background: #9A7D20;
    color: #fff;
    transform: translateY(-1px);
    box-shadow: 0 6px 20px rgba(212,175,55,.3);
}
.btn-hero-primary svg { flex-shrink: 0; }

/* Bouton secondaire outline */
.btn-hero-secondary {
    display: inline-flex;
    align-items: center;
    gap: 8px;
    padding: 12px 22px;
    background: rgba(255,255,255,.7);
    color: #1a1a2e;
    border: 1.5px solid #E8DDD0;
    border-radius: 7px;
    font-family: 'DM Sans', sans-serif;
    font-size: .93rem;
    font-weight: 500;
    cursor: pointer;
    text-decoration: none;
    transition: border-color .18s, background .18s, transform .15s;
    white-space: nowrap;
    backdrop-filter: blur(4px);
}
.btn-hero-secondary:hover {
    border-color: #D4AF37;
    background: #FEF9E7;
    transform: translateY(-1px);
}

/* Bouton pill "100% Gratuit" */
.btn-hero-ghost {
    display: inline-flex;
    align-items: center;
    gap: 7px;
    padding: 12px 18px;
    background: rgba(255,255,255,.6);
    color: #5a5660;
    border: 1.5px solid #E8DDD0;
    border-radius: 50px;
    font-family: 'DM Sans', sans-serif;
    font-size: .88rem;
    font-weight: 500;
    cursor: pointer;
    text-decoration: none;
    transition: border-color .18s, color .18s;
    backdrop-filter: blur(4px);
    white-space: nowrap;
}
.btn-hero-ghost:hover { border-color: #D4AF37; color: #9A7D20; }
.btn-hero-ghost .check-icon {
    width: 18px; height: 18px;
    border-radius: 50%;
    background: #D4AF37;
    display: flex; align-items: center; justify-content: center;
    flex-shrink: 0;
}
.btn-hero-ghost .check-icon svg { width: 10px; height: 10px; }

/* ── Stats ───────────────────────────────────────── */
.hero-stats {
    display: flex;
    gap: 36px;
    align-items: flex-start;
    flex-wrap: wrap;
}
.stat-item {}
.stat-number {
    font-family: 'Playfair Display', serif;
    font-size: 1.8rem;
    font-weight: 800;
    color: #1a1a2e;
    line-height: 1;
    margin-bottom: 3px;
}
.stat-label {
    font-size: .78rem;
    color: #9992a4;
    font-weight: 400;
    letter-spacing: .3px;
}

/* ── Séparateur vertical entre stats ────────────── */
.stat-sep {
    width: 1px;
    height: 36px;
    background: rgba(212,175,55,.3);
    align-self: center;
}

/* ── Responsive ──────────────────────────────────── */
@media (max-width: 1024px) {
    .hero { max-height: none; height: auto; min-height: 480px; }
    .hero-bg-img { width: 52%; top: 0; height: 100%; }
    .hero-bg-mask {
        background: linear-gradient(to right,
            #F5EFE0 0%, #F5EFE0 35%,
            rgba(245,239,224,.9) 44%,
            rgba(245,239,224,.0) 58%);
    }
    .hero-inner { padding: 40px 32px; }
    .hero-left { max-width: 480px; }
    .hero-headline { font-size: clamp(2rem, 4vw, 2.8rem); }
}
@media (max-width: 768px) {
    .hero { min-height: auto; }
    .hero-bg-img {
        position: absolute;
        width: 100%;
        height: 100%;
        top: 0; right: 0;
        object-position: right center;
        opacity: .18; /* image en filigrane sur mobile */
    }
    .hero-bg-mask { background: rgba(245,239,224,.82); }
    .hero-inner { padding: 40px 20px 48px; }
    .hero-left { max-width: 100%; }
    .hero-btns { flex-direction: column; align-items: flex-start; gap: 10px; }
    .hero-stats { gap: 20px; }
    .stat-sep { display: none; }
}

/* ===== CATEGORIES SECTION ===== */
.section-categories {
    background: #fff;
    padding: 70px 0 80px;
    position: relative;
    z-index: 5;
}
.section-inner {
    max-width: 1400px;
    margin: 0 auto;
    padding: 0 60px;
}
.section-header {
    text-align: center;
    margin-bottom: 48px;
}
.section-tag {
    display: inline-block;
    background: var(--gold-pale);
    color: var(--gold-dk);
    border: 1px solid rgba(212,175,55,.3);
    border-radius: 50px;
    padding: 5px 16px;
    font-size: 0.8rem;
    font-weight: 600;
    letter-spacing: .6px;
    text-transform: uppercase;
    margin-bottom: 14px;
}
.section-title {
    font-family: 'Playfair Display', serif;
    font-size: clamp(1.8rem, 3vw, 2.6rem);
    font-weight: 800;
    color: var(--txt-dark);
    line-height: 1.2;
    margin-bottom: 12px;
}
.section-title .accent { color: var(--gold-dk); }
.section-sub {
    font-size: 1rem;
    color: var(--txt-mid);
    max-width: 500px;
    margin: 0 auto;
    line-height: 1.6;
}

.cat-grid {
    display: grid;
    grid-template-columns: repeat(5, 1fr);
    gap: 18px;
}
@media(max-width:1100px){ .cat-grid { grid-template-columns: repeat(3,1fr); } }
@media(max-width:700px){ .cat-grid { grid-template-columns: repeat(2,1fr); } }

.cat-card {
    background: var(--cream);
    border: 1.5px solid rgba(212,175,55,.15);
    border-radius: 14px;
    padding: 28px 20px 22px;
    display: flex;
    flex-direction: column;
    align-items: center;
    gap: 14px;
    cursor: pointer;
    text-decoration: none;
    transition: border-color .2s, transform .2s, box-shadow .2s;
    position: relative;
    overflow: hidden;
}
.cat-card::before {
    content: '';
    position: absolute;
    inset: 0;
    background: linear-gradient(135deg, rgba(212,175,55,.06) 0%, transparent 60%);
    opacity: 0;
    transition: opacity .2s;
}
.cat-card:hover { border-color: var(--gold); transform: translateY(-3px); box-shadow: 0 12px 32px rgba(212,175,55,.12); }
.cat-card:hover::before { opacity: 1; }

.cat-icon {
    width: 64px;
    height: 64px;
    border-radius: 14px;
    background: var(--gold-pale);
    border: 1.5px solid rgba(212,175,55,.25);
    display: flex;
    align-items: center;
    justify-content: center;
    overflow: hidden;
    transition: border-color .2s;
}
.cat-card:hover .cat-icon { border-color: var(--gold); }
.cat-icon img { width: 44px; height: 44px; object-fit: contain; }

.cat-name {
    font-size: 0.88rem;
    font-weight: 600;
    color: var(--txt-dark);
    text-align: center;
    line-height: 1.3;
}
.cat-count {
    position: absolute;
    top: 12px;
    right: 12px;
    background: var(--gold);
    color: var(--txt-dark);
    font-size: 0.7rem;
    font-weight: 700;
    padding: 3px 8px;
    border-radius: 50px;
    min-width: 24px;
    text-align: center;
    opacity: 0;
    transition: opacity .2s;
}
.cat-count.loaded { opacity: 1; }

/* ===== HOW IT WORKS ===== */
.section-how {
    background: var(--cream);
    padding: 80px 0;
}
.steps-grid {
    display: grid;
    grid-template-columns: repeat(4, 1fr);
    gap: 24px;
    margin-top: 48px;
    position: relative;
}
.steps-grid::before {
    content: '';
    position: absolute;
    top: 36px;
    left: calc(12.5% + 20px);
    right: calc(12.5% + 20px);
    height: 1.5px;
    background: repeating-linear-gradient(90deg, var(--gold) 0, var(--gold) 8px, transparent 8px, transparent 18px);
    z-index: 0;
}
@media(max-width:900px){ .steps-grid { grid-template-columns: repeat(2,1fr); } .steps-grid::before { display:none; } }
@media(max-width:560px){ .steps-grid { grid-template-columns: 1fr; } }

.step-card {
    background: #fff;
    border: 1.5px solid rgba(212,175,55,.15);
    border-radius: 16px;
    padding: 32px 24px 28px;
    text-align: center;
    position: relative;
    z-index: 1;
    transition: border-color .2s, transform .2s;
}
.step-card:hover { border-color: var(--gold); transform: translateY(-3px); }

.step-num {
    width: 52px;
    height: 52px;
    border-radius: 50%;
    background: var(--gold);
    color: var(--txt-dark);
    font-family: 'Playfair Display', serif;
    font-size: 1.3rem;
    font-weight: 800;
    display: flex;
    align-items: center;
    justify-content: center;
    margin: 0 auto 18px;
}
.step-title {
    font-family: 'Playfair Display', serif;
    font-size: 1.05rem;
    font-weight: 700;
    color: var(--txt-dark);
    margin-bottom: 10px;
}
.step-desc {
    font-size: 0.875rem;
    color: var(--txt-mid);
    line-height: 1.6;
}

/* ===== FEATURED MACHINES ===== */
.section-machines {
    background: #fff;
    padding: 80px 0;
}
.machines-grid {
    display: grid;
    grid-template-columns: repeat(3, 1fr);
    gap: 24px;
    margin-top: 48px;
}
@media(max-width:900px){ .machines-grid { grid-template-columns: repeat(2,1fr); } }
@media(max-width:560px){ .machines-grid { grid-template-columns: 1fr; } }

.machine-card {
    background: var(--cream);
    border: 1.5px solid rgba(212,175,55,.15);
    border-radius: 16px;
    overflow: hidden;
    text-decoration: none;
    color: inherit;
    transition: border-color .2s, transform .2s, box-shadow .2s;
    display: block;
}
.machine-card:hover { border-color: var(--gold); transform: translateY(-4px); box-shadow: 0 16px 40px rgba(212,175,55,.12); }

.machine-img {
    width: 100%;
    aspect-ratio: 4/3;
    background: var(--cream2);
    display: flex;
    align-items: center;
    justify-content: center;
    overflow: hidden;
    position: relative;
}
.machine-img img { width: 100%; height: 100%; object-fit: cover; transition: transform .3s; }
.machine-card:hover .machine-img img { transform: scale(1.04); }
.machine-img .status-badge {
    position: absolute;
    top: 12px;
    left: 12px;
    padding: 4px 10px;
    border-radius: 50px;
    font-size: 0.75rem;
    font-weight: 600;
}
.status-badge.available { background: #dcfce7; color: #15803d; }
.status-badge.unavailable { background: #fee2e2; color: #b91c1c; }

.machine-body { padding: 20px; }
.machine-type {
    font-size: 0.75rem;
    font-weight: 600;
    color: var(--gold-dk);
    text-transform: uppercase;
    letter-spacing: .5px;
    margin-bottom: 6px;
}
.machine-name {
    font-family: 'Playfair Display', serif;
    font-size: 1.1rem;
    font-weight: 700;
    color: var(--txt-dark);
    margin-bottom: 8px;
    line-height: 1.3;
}
.machine-city {
    font-size: 0.82rem;
    color: var(--txt-light);
    display: flex;
    align-items: center;
    gap: 4px;
    margin-bottom: 14px;
}
.machine-price {
    display: flex;
    align-items: baseline;
    gap: 4px;
}
.machine-price .amount {
    font-family: 'Playfair Display', serif;
    font-size: 1.4rem;
    font-weight: 800;
    color: var(--gold-dk);
}
.machine-price .per { font-size: 0.8rem; color: var(--txt-light); }

.machines-cta {
    text-align: center;
    margin-top: 40px;
}

/* ===== TRUST BAND ===== */
.section-trust {
    background: var(--gold-pale);
    border-top: 1px solid rgba(212,175,55,.2);
    border-bottom: 1px solid rgba(212,175,55,.2);
    padding: 40px 0;
}
.trust-inner {
    max-width: 1400px;
    margin: 0 auto;
    padding: 0 60px;
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 60px;
    flex-wrap: wrap;
}
.trust-item {
    display: flex;
    align-items: center;
    gap: 12px;
}
.trust-icon {
    width: 44px;
    height: 44px;
    border-radius: 10px;
    background: rgba(212,175,55,.2);
    display: flex;
    align-items: center;
    justify-content: center;
    flex-shrink: 0;
}
.trust-icon svg { color: var(--gold-dk); }
.trust-text strong {
    display: block;
    font-size: 0.9rem;
    font-weight: 600;
    color: var(--txt-dark);
}
.trust-text span {
    font-size: 0.8rem;
    color: var(--txt-mid);
}

/* ===== CTA SECTION ===== */
.section-cta {
    background: var(--cream);
    padding: 80px 0;
}
.cta-box {
    background: #fff;
    border: 1.5px solid rgba(212,175,55,.25);
    border-radius: 24px;
    padding: 64px;
    text-align: center;
    position: relative;
    overflow: hidden;
}
.cta-box::before {
    content: '';
    position: absolute;
    top: -80px; right: -80px;
    width: 260px; height: 260px;
    border-radius: 50%;
    background: rgba(212,175,55,.07);
}
.cta-box::after {
    content: '';
    position: absolute;
    bottom: -60px; left: -60px;
    width: 200px; height: 200px;
    border-radius: 50%;
    background: rgba(212,175,55,.07);
}
.cta-box .section-tag { margin-bottom: 16px; }
.cta-box .section-title { margin-bottom: 14px; }
.cta-box p { color: var(--txt-mid); max-width: 480px; margin: 0 auto 32px; line-height: 1.65; }
.cta-actions { display: flex; gap: 14px; justify-content: center; flex-wrap: wrap; position: relative; z-index: 1; }

/* ===== FOOTER ===== */
footer {
    background: var(--txt-dark);
    color: rgba(255,255,255,.75);
    padding: 60px 0 30px;
}
.footer-inner {
    max-width: 1400px;
    margin: 0 auto;
    padding: 0 60px;
}
.footer-grid {
    display: grid;
    grid-template-columns: 2fr 1fr 1fr 1fr;
    gap: 48px;
    margin-bottom: 48px;
}
@media(max-width:900px){ .footer-grid { grid-template-columns: 1fr 1fr; } }
@media(max-width:560px){ .footer-grid { grid-template-columns: 1fr; } }

.footer-brand img { height: 36px; filter: brightness(0) invert(1); margin-bottom: 14px; }
.footer-brand p { font-size: 0.875rem; line-height: 1.65; color: rgba(255,255,255,.55); max-width: 260px; }
.footer-col h4 { font-size: 0.8rem; font-weight: 700; text-transform: uppercase; letter-spacing: .8px; color: var(--gold); margin-bottom: 16px; }
.footer-col a { display: block; font-size: 0.875rem; color: rgba(255,255,255,.55); text-decoration: none; margin-bottom: 10px; transition: color .15s; }
.footer-col a:hover { color: var(--gold-lt); }

.footer-bottom {
    border-top: 1px solid rgba(255,255,255,.08);
    padding-top: 24px;
    display: flex;
    align-items: center;
    justify-content: space-between;
    flex-wrap: wrap;
    gap: 12px;
}
.footer-bottom p { font-size: 0.82rem; color: rgba(255,255,255,.4); }
.footer-bottom a { color: var(--gold); text-decoration: none; font-size: 0.82rem; }

/* ===== RESPONSIVE GÉNÉRAL ===== */
@media(max-width: 1024px) {
    .section-inner { padding: 0 32px; }
}
@media(max-width: 640px) {
    .section-inner { padding: 0 20px; }
    .trust-inner { padding: 0 20px; gap: 32px; }
    .cta-box { padding: 40px 24px; }
}
</style>
@endpush

@section('content')

{{-- ===== HERO ===== --}}
<section class="hero" id="hero">

    {{-- Image excavatrice studio (hero-bg.jpeg) — positionnée à droite --}}
    <img
        src="{{ asset('images/hero-bg.jpeg') }}"
        alt=""
        class="hero-bg-img"
        aria-hidden="true"
    >

    {{-- Masque dégradé crème → transparent pour lisibilité du texte --}}
    <div class="hero-bg-mask" aria-hidden="true"></div>

    {{-- Contenu texte --}}
    <div class="hero-inner">
        <div class="hero-left">

            <h1 class="hero-headline">
                Louez les meilleurs<br>
                <span class="accent">engins BTP</span> au Maroc
            </h1>

            <p class="hero-desc">
                Excavatrice, grue, bulldozer ou compacteur — trouvez l'engin qu'il vous faut à
                Casablanca, Rabat, Marrakech et partout au Maroc. Réservation simple,
                contrat PDF immédiat.
            </p>

            <div class="hero-btns" id="heroBtns">
                <a href="/machines" class="btn-hero-primary">
                    <svg width="15" height="15" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.2">
                        <circle cx="11" cy="11" r="8"/><path d="M21 21l-4.35-4.35"/>
                    </svg>
                    Voir les engins
                </a>
                <a href="/login" class="btn-hero-secondary" id="btnMonEspace">
                    <svg width="15" height="15" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/><circle cx="12" cy="7" r="4"/>
                    </svg>
                    Mon espace
                </a>
                <a href="/machines" class="btn-hero-ghost">
                    <span class="check-icon">
                        <svg viewBox="0 0 12 12" fill="none" stroke="#1a1a2e" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M2 6l3 3 5-5"/>
                        </svg>
                    </span>
                    100% Gratuit
                </a>
            </div>

            {{-- Stats --}}
            <div class="hero-stats" id="heroStats">
                <div class="stat-item">
                    <div class="stat-number" id="statMachines">120+</div>
                    <div class="stat-label">Engins disponibles</div>
                </div>
                <div class="stat-sep"></div>
                <div class="stat-item">
                    <div class="stat-number">8</div>
                    <div class="stat-label">Villes couvertes</div>
                </div>
                <div class="stat-sep"></div>
                <div class="stat-item">
                    <div class="stat-number" id="statClients">480+</div>
                    <div class="stat-label">Clients satisfaits</div>
                </div>
                <div class="stat-sep"></div>
                <div class="stat-item">
                    <div class="stat-number">98%</div>
                    <div class="stat-label">Satisfaction</div>
                </div>
            </div>

        </div>
    </div>{{-- /hero-inner --}}
</section>

{{-- ===== TRUST BAND ===== --}}
<div class="section-trust">
    <div class="trust-inner">
        <div class="trust-item">
            <div class="trust-icon">
                <svg width="22" height="22" fill="none" viewBox="0 0 24 24" stroke="#9A7D20" stroke-width="2">
                    <path d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/>
                </svg>
            </div>
            <div class="trust-text">
                <strong>Machines vérifiées</strong>
                <span>Propriétaires certifiés</span>
            </div>
        </div>
        <div class="trust-item">
            <div class="trust-icon">
                <svg width="22" height="22" fill="none" viewBox="0 0 24 24" stroke="#9A7D20" stroke-width="2">
                    <path d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                </svg>
            </div>
            <div class="trust-text">
                <strong>Contrat PDF immédiat</strong>
                <span>Dès acceptation</span>
            </div>
        </div>
        <div class="trust-item">
            <div class="trust-icon">
                <svg width="22" height="22" fill="none" viewBox="0 0 24 24" stroke="#9A7D20" stroke-width="2">
                    <path d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2z"/>
                </svg>
            </div>
            <div class="trust-text">
                <strong>Paiement sécurisé</strong>
                <span>Transactions protégées</span>
            </div>
        </div>
        <div class="trust-item">
            <div class="trust-icon">
                <svg width="22" height="22" fill="none" viewBox="0 0 24 24" stroke="#9A7D20" stroke-width="2">
                    <path d="M18.364 5.636l-3.536 3.536m0 5.656l3.536 3.536M9.172 9.172L5.636 5.636m3.536 9.192L5.636 18.364M21 12a9 9 0 11-18 0 9 9 0 0118 0zm-5 0a4 4 0 11-8 0 4 4 0 018 0z"/>
                </svg>
            </div>
            <div class="trust-text">
                <strong>Support 7j/7</strong>
                <span>Assistance rapide</span>
            </div>
        </div>
    </div>
</div>

{{-- ===== CATEGORIES ===== --}}
<section class="section-categories">
    <div class="section-inner">
        <div class="section-header">
            <span class="section-tag">Catalogue</span>
            <h2 class="section-title">Nos <span class="accent">catégories</span> d'engins</h2>
            <p class="section-sub">Du terrassement à la manutention, toutes les machines dont vous avez besoin.</p>
        </div>

        <div class="cat-grid" id="catGrid">
            @php
            $categories = [
                ['key'=>'excavatrice','label'=>'Excavatrice','img'=>'/images/img3.png'],
                ['key'=>'grue','label'=>'Grue','img'=>'/images/img4.png'],
                ['key'=>'bulldozer','label'=>'Bulldozer','img'=>'/images/img1.png'],
                ['key'=>'chargeuse','label'=>'Chargeuse','img'=>'/images/img2.png'],
                ['key'=>'compacteur','label'=>'Compacteur','img'=>'/images/img8.png'],
                ['key'=>'nacelle','label'=>'Nacelle','img'=>'/images/img5.png'],
                ['key'=>'tractopelle','label'=>'Tractopelle','img'=>'/images/img7.png'],
                ['key'=>'camion','label'=>'Camion BTP','img'=>'/images/img9.png'],
                ['key'=>'transport','label'=>'Transport','img'=>'/images/img11.png'],
                ['key'=>'niveleuse','label'=>'Niveleuse','img'=>'/images/img14.png'],
            ];
            @endphp

            @foreach($categories as $cat)
            <a href="/machines?type={{ $cat['key'] }}" class="cat-card">
                <span class="cat-count" id="count-{{ $cat['key'] }}">0</span>
                <div class="cat-icon">
                    <img src="{{ $cat['img'] }}" alt="{{ $cat['label'] }}"
                         onerror="this.style.display='none'">
                </div>
                <span class="cat-name">{{ $cat['label'] }}</span>
            </a>
            @endforeach
        </div>
    </div>
</section>

{{-- ===== HOW IT WORKS ===== --}}
<section class="section-how">
    <div class="section-inner">
        <div class="section-header">
            <span class="section-tag">Simple & Rapide</span>
            <h2 class="section-title">Comment ça <span class="accent">marche ?</span></h2>
            <p class="section-sub">Réservez votre engin en quelques minutes, sans paperasse.</p>
        </div>

        <div class="steps-grid">
            <div class="step-card">
                <div class="step-num">1</div>
                <div class="step-title">Parcourez le catalogue</div>
                <p class="step-desc">Filtrez par type, ville ou disponibilité. Comparez les tarifs en toute transparence.</p>
            </div>
            <div class="step-card">
                <div class="step-num">2</div>
                <div class="step-title">Faites une demande</div>
                <p class="step-desc">Choisissez vos dates et soumettez votre demande de réservation en un clic.</p>
            </div>
            <div class="step-card">
                <div class="step-num">3</div>
                <div class="step-title">Confirmation rapide</div>
                <p class="step-desc">Le propriétaire accepte ou refuse sous 24h. Vous êtes notifié instantanément.</p>
            </div>
            <div class="step-card">
                <div class="step-num">4</div>
                <div class="step-title">Contrat PDF</div>
                <p class="step-desc">Téléchargez votre contrat signé dès acceptation. Démarrez votre chantier !</p>
            </div>
        </div>
    </div>
</section>

{{-- ===== FEATURED MACHINES ===== --}}
<section class="section-machines">
    <div class="section-inner">
        <div class="section-header">
            <span class="section-tag">À la une</span>
            <h2 class="section-title">Engins <span class="accent">disponibles</span></h2>
            <p class="section-sub">Les machines les plus récemment ajoutées sur la plateforme.</p>
        </div>

        <div class="machines-grid" id="featuredMachines">
            {{-- Chargé dynamiquement --}}
            <div style="grid-column:1/-1; text-align:center; padding:40px; color: var(--txt-light);">
                <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="animation:spin 1s linear infinite; display:inline-block;">
                    <path d="M12 2v4M12 18v4M4.93 4.93l2.83 2.83M16.24 16.24l2.83 2.83M2 12h4M18 12h4M4.93 19.07l2.83-2.83M16.24 7.76l2.83-2.83"/>
                </svg>
                <p style="margin-top:8px; font-size:.9rem;">Chargement…</p>
            </div>
        </div>

        <div class="machines-cta">
            <a href="/machines" class="btn-hero-primary">
                Voir toutes les machines
                <svg width="16" height="16" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path d="M5 12h14M12 5l7 7-7 7"/>
                </svg>
            </a>
        </div>
    </div>
</section>

{{-- ===== CTA ===== --}}
<section class="section-cta" id="ctaSection">
    <div class="section-inner">
        <div class="cta-box">
            <span class="section-tag">Rejoignez Rentify</span>
            <h2 class="section-title">Prêt à louer ou à <span class="accent">proposer</span> vos engins ?</h2>
            <p>Que vous soyez propriétaire de machines ou responsable de chantier, Rentify connecte les deux. Inscription 100% gratuite.</p>
            <div class="cta-actions" id="ctaActions">
                <a href="/machines" class="btn-hero-primary">
                    <svg width="16" height="16" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <circle cx="11" cy="11" r="8"/><path d="M21 21l-4.35-4.35"/>
                    </svg>
                    Voir les engins
                </a>
                <a href="/register" class="btn-hero-secondary" id="ctaBtnRegister">
                    Créer mon compte gratuit
                </a>
            </div>
        </div>
    </div>
</section>


@endsection



@push('scripts')
<style>
@keyframes spin { to { transform: rotate(360deg); } }
</style>
<script>
(function(){
    const TYPE_PHOTO = {
        excavatrice:'/images/img3.png', grue:'/images/img4.png',
        bulldozer:'/images/img1.png',   chargeuse:'/images/img2.png',
        compacteur:'/images/img8.png',  nacelle:'/images/img5.png',
        tractopelle:'/images/img7.png', camion:'/images/img9.png',
        transport:'/images/img11.png',  niveleuse:'/images/img14.png',
    };

    /* ---- Auth state ---- */
    const user  = window.getUser ? window.getUser() : null;
    const token = window.getToken ? window.getToken() : null;
    const role  = user?.role || null;

    /* Bouton "Mon espace" → dashboard si connecté */
    const btnEspace = document.getElementById('btnMonEspace');
    if (btnEspace) {
        if (user) {
            btnEspace.href = role === 'admin' ? '/dashboard/admin'
                           : role === 'owner' ? '/dashboard/owner'
                           : '/dashboard/client';
            btnEspace.querySelector('svg').style.display = 'none';
            btnEspace.childNodes[btnEspace.childNodes.length - 1].textContent = ' Mon espace';
        }
        // toujours visible
    }

    /* CTA "Créer compte" → masquer si connecté */
    const ctaReg = document.getElementById('ctaBtnRegister');
    if (ctaReg && user) ctaReg.style.display = 'none';

    /* ---- Stats dynamiques ---- */
    async function loadStats(){
        try {
            const d = await window.API.get('/api/machines');
            const machines = Array.isArray(d) ? d : (Array.isArray(d?.data) ? d.data : []);
            const available = machines.filter(m => m.status === 'available').length;
            const el = document.getElementById('statMachines');
            if (el) el.textContent = available + '+';

            // Clients satisfaits ≈ réservations complétées (si admin) sinon valeur fictive
            const elC = document.getElementById('statClients');
            if (elC) elC.textContent = Math.max(machines.length * 4, 480) + '+';

            /* ---- Category counts ---- */
            const counts = {};
            machines.forEach(m => { counts[m.type] = (counts[m.type]||0) + 1; });
            // persist offline
            try { localStorage.setItem('rentify_cat_counts', JSON.stringify(counts)); } catch(e){}
            renderCounts(counts);

            /* ---- Featured machines ---- */
            renderFeatured(machines.slice(0, 6));

        } catch(e) {
            // offline fallback counts
            try {
                const saved = localStorage.getItem('rentify_cat_counts');
                if (saved) renderCounts(JSON.parse(saved));
            } catch(e2){}
            document.getElementById('statMachines').textContent = '120+';
            document.getElementById('statClients').textContent  = '480+';
            renderFeatured([]);
        }
    }

    function renderCounts(counts){
        Object.keys(counts).forEach(type => {
            const el = document.getElementById('count-' + type);
            if (el) { el.textContent = counts[type]; el.classList.add('loaded'); }
        });
    }

    function renderFeatured(machines){
        const grid = document.getElementById('featuredMachines');
        if (!grid) return;
        if (!machines.length) {
            grid.innerHTML = '<div style="grid-column:1/-1;text-align:center;padding:40px;color:var(--txt-light);">Aucune machine disponible pour le moment.</div>';
            return;
        }
        grid.innerHTML = machines.map(m => {
            const img   = m.image ? `/storage/${m.image}` : (TYPE_PHOTO[m.type] || '/images/img1.png');
            const price = m.price_per_day ? `<span class="amount">${Number(m.price_per_day).toLocaleString('fr-MA')}</span><span class="per"> DH/jour</span>` : '';
            const badge = m.status === 'available'
                ? '<span class="status-badge available">Disponible</span>'
                : '<span class="status-badge unavailable">Indisponible</span>';
            return `
            <a href="/machines/${m.id}" class="machine-card">
                <div class="machine-img">
                    <img src="${img}" alt="${m.name}" onerror="this.src='${TYPE_PHOTO[m.type]||'/images/img1.png'}'">
                    ${badge}
                </div>
                <div class="machine-body">
                    <div class="machine-type">${m.type||'Engin BTP'}</div>
                    <div class="machine-name">${m.name}</div>
                    <div class="machine-city">
                        <svg width="13" height="13" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                            <path d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                        </svg>
                        ${m.city||'Maroc'}
                    </div>
                    <div class="machine-price">${price}</div>
                </div>
            </a>`;
        }).join('');
    }

    loadStats();
})();
</script>
@endpush