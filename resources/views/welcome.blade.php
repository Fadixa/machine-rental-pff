@extends('layouts.app')

@section('title', 'Rentify — Location d\'engins BTP au Maroc')

@push('styles')
<style>
@import url('https://fonts.googleapis.com/css2?family=Playfair+Display:ital,wght@0,700;0,800;0,900;1,700&family=DM+Sans:wght@300;400;500;600&display=swap');

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

/* ===================================================
   HERO — machineAccueil.jpg plein écran exact
   =================================================== */
.hero {
    position: relative;
    width: 100%;
    /* Hauteur = ratio exact de l'image 1600x900 = 56.25% */
    aspect-ratio: 16 / 9;
    max-height: 90vh;
    min-height: 360px;
    overflow: hidden;
    display: flex;
    align-items: center;
    background-color: #F5EFE0;
}

/* Image pleine — rien coupé, tout visible */
.hero-bg-img {
    position: absolute;
    inset: 0;
    width: 100%;
    height: 100%;
    object-fit: fill;   /* fill = exactement les dimensions, zéro crop */
    z-index: 1;
    pointer-events: none;
    display: block;
}

/* Fondu gauche léger pour lisibilité texte — transparent à droite */
.hero-bg-mask {
    position: absolute;
    inset: 0;
    z-index: 2;
    pointer-events: none;
    background: linear-gradient(
        to right,
        rgba(245,239,224,.75) 0%,
        rgba(245,239,224,.60) 25%,
        rgba(245,239,224,.20) 42%,
        rgba(245,239,224,.00) 55%
    );
}

.hero-inner {
    position: relative;
    z-index: 3;
    width: 100%;
    max-width: 1320px;
    margin: 0 auto;
    padding: 0 56px;
}

.hero-left { max-width: 520px; }

.hero-headline {
    font-family: 'Playfair Display', Georgia, serif;
    font-size: clamp(2.6rem, 4vw, 3.8rem);
    font-weight: 800;
    line-height: 1.08;
    color: #1a1a2e;
    margin: 0 0 6px;
    letter-spacing: -0.5px;
}
.hero-headline .accent { color: #9A7D20; }

.hero-desc {
    font-family: 'DM Sans', sans-serif;
    font-size: .97rem;
    line-height: 1.65;
    color: #5a5660;
    max-width: 420px;
    margin: 16px 0 28px;
    padding-left: 14px;
    border-left: 3px solid #D4AF37;
}

.hero-btns {
    display: flex;
    gap: 12px;
    align-items: center;
    flex-wrap: wrap;
    margin-bottom: 44px;
}

.btn-hero-primary {
    display: inline-flex; align-items: center; gap: 8px;
    padding: 13px 24px;
    background: #D4AF37; color: #1a1a2e;
    border: none; border-radius: 7px;
    font-family: 'DM Sans', sans-serif; font-size: .93rem; font-weight: 700;
    cursor: pointer; text-decoration: none;
    transition: background .18s, transform .15s, box-shadow .18s;
    white-space: nowrap;
}
.btn-hero-primary:hover { background: #9A7D20; color: #fff; transform: translateY(-1px); box-shadow: 0 6px 20px rgba(212,175,55,.3); }

.btn-hero-secondary {
    display: inline-flex; align-items: center; gap: 8px;
    padding: 12px 22px;
    background: rgba(255,255,255,.7); color: #1a1a2e;
    border: 1.5px solid #E8DDD0; border-radius: 7px;
    font-family: 'DM Sans', sans-serif; font-size: .93rem; font-weight: 500;
    cursor: pointer; text-decoration: none;
    transition: border-color .18s, background .18s, transform .15s;
    white-space: nowrap; backdrop-filter: blur(4px);
}
.btn-hero-secondary:hover { border-color: #D4AF37; background: #FEF9E7; transform: translateY(-1px); }

.btn-hero-ghost {
    display: inline-flex; align-items: center; gap: 7px;
    padding: 12px 18px;
    background: rgba(255,255,255,.6); color: #5a5660;
    border: 1.5px solid #E8DDD0; border-radius: 50px;
    font-family: 'DM Sans', sans-serif; font-size: .88rem; font-weight: 500;
    cursor: pointer; text-decoration: none;
    transition: border-color .18s, color .18s;
    backdrop-filter: blur(4px); white-space: nowrap;
}
.btn-hero-ghost:hover { border-color: #D4AF37; color: #9A7D20; }
.btn-hero-ghost .check-icon {
    width: 18px; height: 18px; border-radius: 50%;
    background: #D4AF37;
    display: flex; align-items: center; justify-content: center; flex-shrink: 0;
}
.btn-hero-ghost .check-icon svg { width: 10px; height: 10px; }

.hero-stats { display: flex; gap: 36px; align-items: flex-start; flex-wrap: wrap; }
.stat-number {
    font-family: 'Playfair Display', serif; font-size: 1.8rem; font-weight: 800;
    color: #1a1a2e; line-height: 1; margin-bottom: 3px;
}
.stat-label { font-size: .78rem; color: #9992a4; font-weight: 400; letter-spacing: .3px; }
.stat-sep { width: 1px; height: 36px; background: rgba(212,175,55,.3); align-self: center; }

@media (max-width: 1024px) {
    .hero { min-height: 300px; }
    .hero-inner { padding: 40px 32px; }
    .hero-left { max-width: 420px; }
}
@media (max-width: 768px) {
    .hero { min-height: 220px; }
    .hero-bg-mask { background: rgba(245,239,224,.70); }
    .hero-inner { padding: 28px 20px 36px; }
    .hero-left { max-width: 100%; }
    .hero-btns { flex-direction: column; align-items: flex-start; gap: 10px; }
    .hero-stats { gap: 20px; }
    .stat-sep { display: none; }
}

/* ===== SHARED ===== */
.section-inner { max-width: 1400px; margin: 0 auto; padding: 0 60px; }
.section-header { text-align: center; margin-bottom: 48px; }
.section-tag {
    display: inline-block;
    background: var(--gold-pale); color: var(--gold-dk);
    border: 1px solid rgba(212,175,55,.3); border-radius: 50px;
    padding: 5px 16px; font-size: 0.8rem; font-weight: 600;
    letter-spacing: .6px; text-transform: uppercase; margin-bottom: 14px;
}
.section-title {
    font-family: 'Playfair Display', serif;
    font-size: clamp(1.8rem, 3vw, 2.6rem); font-weight: 800;
    color: var(--txt-dark); line-height: 1.2; margin-bottom: 12px;
}
.section-title .accent { color: var(--gold-dk); }
.section-sub { font-size: 1rem; color: var(--txt-mid); max-width: 500px; margin: 0 auto; line-height: 1.6; }

/* ===== TRUST BAND ===== */
.section-trust {
    background: var(--gold-pale);
    border-top: 1px solid rgba(212,175,55,.2);
    border-bottom: 1px solid rgba(212,175,55,.2);
    padding: 40px 0;
}
.trust-inner {
    max-width: 1400px; margin: 0 auto; padding: 0 60px;
    display: flex; align-items: center; justify-content: center; gap: 60px; flex-wrap: wrap;
}
.trust-item { display: flex; align-items: center; gap: 12px; }
.trust-icon {
    width: 44px; height: 44px; border-radius: 10px;
    background: rgba(212,175,55,.2);
    display: flex; align-items: center; justify-content: center; flex-shrink: 0;
}
.trust-text strong { display: block; font-size: 0.9rem; font-weight: 600; color: var(--txt-dark); }
.trust-text span { font-size: 0.8rem; color: var(--txt-mid); }

/* ===== CATEGORIES ===== */
.section-categories { background: #fff; padding: 70px 0 80px; position: relative; z-index: 5; }
.cat-grid { display: grid; grid-template-columns: repeat(5, 1fr); gap: 18px; }
@media(max-width:1100px){ .cat-grid { grid-template-columns: repeat(3,1fr); } }
@media(max-width:700px){ .cat-grid { grid-template-columns: repeat(2,1fr); } }

.cat-card {
    background: var(--cream); border: 1.5px solid rgba(212,175,55,.15); border-radius: 14px;
    padding: 28px 20px 22px;
    display: flex; flex-direction: column; align-items: center; gap: 14px;
    cursor: pointer; text-decoration: none;
    transition: border-color .2s, transform .2s, box-shadow .2s;
    position: relative; overflow: hidden;
}
.cat-card::before {
    content: ''; position: absolute; inset: 0;
    background: linear-gradient(135deg, rgba(212,175,55,.06) 0%, transparent 60%);
    opacity: 0; transition: opacity .2s;
}
.cat-card:hover { border-color: var(--gold); transform: translateY(-3px); box-shadow: 0 12px 32px rgba(212,175,55,.12); }
.cat-card:hover::before { opacity: 1; }
.cat-icon {
    width: 64px; height: 64px; border-radius: 14px;
    background: var(--gold-pale); border: 1.5px solid rgba(212,175,55,.25);
    display: flex; align-items: center; justify-content: center; overflow: hidden;
    transition: border-color .2s;
}
.cat-card:hover .cat-icon { border-color: var(--gold); }
.cat-icon img { width: 44px; height: 44px; object-fit: contain; }
.cat-name { font-size: 0.88rem; font-weight: 600; color: var(--txt-dark); text-align: center; line-height: 1.3; }
.cat-count {
    position: absolute; top: 12px; right: 12px;
    background: var(--gold); color: var(--txt-dark);
    font-size: 0.7rem; font-weight: 700; padding: 3px 8px; border-radius: 50px;
    min-width: 24px; text-align: center; opacity: 0; transition: opacity .2s;
}
.cat-count.loaded { opacity: 1; }

/* ===== HOW IT WORKS ===== */
.section-how { background: var(--cream); padding: 80px 0; }
.steps-grid {
    display: grid; grid-template-columns: repeat(4, 1fr); gap: 24px;
    margin-top: 48px; position: relative;
}
.steps-grid::before {
    content: ''; position: absolute; top: 36px;
    left: calc(12.5% + 20px); right: calc(12.5% + 20px); height: 1.5px;
    background: repeating-linear-gradient(90deg, var(--gold) 0, var(--gold) 8px, transparent 8px, transparent 18px);
    z-index: 0;
}
@media(max-width:900px){ .steps-grid { grid-template-columns: repeat(2,1fr); } .steps-grid::before { display:none; } }
@media(max-width:560px){ .steps-grid { grid-template-columns: 1fr; } }
.step-card {
    background: #fff; border: 1.5px solid rgba(212,175,55,.15); border-radius: 16px;
    padding: 32px 24px 28px; text-align: center; position: relative; z-index: 1;
    transition: border-color .2s, transform .2s;
}
.step-card:hover { border-color: var(--gold); transform: translateY(-3px); }
.step-num {
    width: 52px; height: 52px; border-radius: 50%;
    background: var(--gold); color: var(--txt-dark);
    font-family: 'Playfair Display', serif; font-size: 1.3rem; font-weight: 800;
    display: flex; align-items: center; justify-content: center; margin: 0 auto 18px;
}
.step-title { font-family: 'Playfair Display', serif; font-size: 1.05rem; font-weight: 700; color: var(--txt-dark); margin-bottom: 10px; }
.step-desc { font-size: 0.875rem; color: var(--txt-mid); line-height: 1.6; }

/* ===== FEATURED MACHINES ===== */
.section-machines { background: #fff; padding: 80px 0; }
.machines-grid { display: grid; grid-template-columns: repeat(3, 1fr); gap: 24px; margin-top: 48px; }
@media(max-width:900px){ .machines-grid { grid-template-columns: repeat(2,1fr); } }
@media(max-width:560px){ .machines-grid { grid-template-columns: 1fr; } }

.machine-card {
    background: var(--cream); border: 1.5px solid rgba(212,175,55,.15); border-radius: 16px;
    overflow: hidden; text-decoration: none; color: inherit;
    transition: border-color .2s, transform .2s, box-shadow .2s; display: block;
}
.machine-card:hover { border-color: var(--gold); transform: translateY(-4px); box-shadow: 0 16px 40px rgba(212,175,55,.12); }
.machine-img {
    width: 100%; aspect-ratio: 4/3; background: var(--cream2);
    overflow: hidden; position: relative;
}
.machine-img img { width: 100%; height: 100%; object-fit: cover; transition: transform .3s; }
.machine-card:hover .machine-img img { transform: scale(1.04); }
.machine-img .status-badge {
    position: absolute; top: 12px; left: 12px;
    padding: 4px 10px; border-radius: 50px; font-size: 0.75rem; font-weight: 600;
}
.status-badge.available { background: #dcfce7; color: #15803d; }
.status-badge.unavailable { background: #fee2e2; color: #b91c1c; }
.machine-body { padding: 20px; }
.machine-type { font-size: 0.75rem; font-weight: 600; color: var(--gold-dk); text-transform: uppercase; letter-spacing: .5px; margin-bottom: 6px; }
.machine-name { font-family: 'Playfair Display', serif; font-size: 1.1rem; font-weight: 700; color: var(--txt-dark); margin-bottom: 8px; line-height: 1.3; }
.machine-city { font-size: 0.82rem; color: var(--txt-light); display: flex; align-items: center; gap: 4px; margin-bottom: 14px; }
.machine-price { display: flex; align-items: baseline; gap: 4px; }
.machine-price .amount { font-family: 'Playfair Display', serif; font-size: 1.4rem; font-weight: 800; color: var(--gold-dk); }
.machine-price .per { font-size: 0.8rem; color: var(--txt-light); }
.machines-cta { text-align: center; margin-top: 40px; }

/* ===================================================
   À PROPOS — design exact comme preview image
   =================================================== */
.section-about {
    background: var(--cream);
    padding: 90px 0 80px;
}

/* Header centré */
.about-header {
    text-align: center;
    margin-bottom: 52px;
}
.about-header .section-tag { margin-bottom: 12px; }
.about-header .section-title { margin-bottom: 10px; }
.about-header .section-sub { max-width: 560px; }

/* Mission / Vision — 2 cards côte à côte */
.about-mv-grid {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 24px;
    margin-bottom: 64px;
}
@media(max-width:700px){ .about-mv-grid { grid-template-columns: 1fr; } }

.about-mv-card {
    background: #fff;
    border: 1.5px solid rgba(212,175,55,.2);
    border-radius: 18px;
    padding: 32px 28px;
    transition: border-color .2s, transform .2s;
}
.about-mv-card:hover { border-color: var(--gold); transform: translateY(-2px); }
.about-mv-card.vision {
    background: var(--gold-pale);
    border-color: rgba(212,175,55,.35);
}
.about-mv-icon {
    width: 48px; height: 48px; border-radius: 12px;
    background: var(--gold-pale); border: 1.5px solid rgba(212,175,55,.3);
    display: flex; align-items: center; justify-content: center;
    margin-bottom: 18px;
}
.about-mv-card.vision .about-mv-icon {
    background: rgba(212,175,55,.2);
    border-color: rgba(212,175,55,.4);
}
.about-mv-icon svg { color: var(--gold-dk); }
.about-mv-title {
    font-family: 'Playfair Display', serif;
    font-size: 1.1rem; font-weight: 700;
    color: var(--txt-dark); margin-bottom: 12px;
}
.about-mv-desc { font-size: 0.9rem; color: var(--txt-mid); line-height: 1.7; }

/* Nos valeurs — séparateur + 4 cards */
.about-values-wrap {
    margin-bottom: 64px;
}
.about-values-header {
    text-align: center;
    margin-bottom: 32px;
}
.about-values-header .section-tag { margin-bottom: 10px; }
.about-values-header .section-title { font-size: clamp(1.5rem, 2.5vw, 2.1rem); }

.about-values-grid {
    display: grid;
    grid-template-columns: repeat(4, 1fr);
    gap: 20px;
}
@media(max-width:900px){ .about-values-grid { grid-template-columns: repeat(2,1fr); } }
@media(max-width:560px){ .about-values-grid { grid-template-columns: 1fr; } }

.about-val-card {
    background: #fff;
    border: 1.5px solid rgba(212,175,55,.15);
    border-radius: 16px;
    padding: 28px 20px;
    text-align: center;
    transition: border-color .2s, transform .2s;
}
.about-val-card:hover { border-color: var(--gold); transform: translateY(-3px); }
.about-val-icon {
    width: 52px; height: 52px; border-radius: 50%;
    background: var(--gold-pale); border: 1.5px solid rgba(212,175,55,.25);
    display: flex; align-items: center; justify-content: center;
    margin: 0 auto 16px;
}
.about-val-icon svg { color: var(--gold-dk); }
.about-val-title { font-size: 0.95rem; font-weight: 700; color: var(--txt-dark); margin-bottom: 8px; }
.about-val-desc { font-size: 0.82rem; color: var(--txt-mid); line-height: 1.6; }

/* L'équipe — 3 cards */
.about-team-wrap {}
.about-team-header {
    text-align: center;
    margin-bottom: 32px;
}
.about-team-header .section-tag { margin-bottom: 10px; }
.about-team-header .section-title { font-size: clamp(1.5rem, 2.5vw, 2.1rem); }

.about-team-grid {
    display: grid;
    grid-template-columns: repeat(3, 1fr);
    gap: 24px;
}
@media(max-width:700px){ .about-team-grid { grid-template-columns: 1fr; } }

.about-team-card {
    background: #fff;
    border: 1.5px solid rgba(212,175,55,.15);
    border-radius: 18px;
    padding: 32px 24px 28px;
    text-align: center;
    transition: border-color .2s, transform .2s;
}
.about-team-card:hover { border-color: var(--gold); transform: translateY(-3px); }

.team-avatar {
    width: 64px; height: 64px; border-radius: 50%;
    background: var(--gold); color: var(--txt-dark);
    font-family: 'Playfair Display', serif; font-size: 1.5rem; font-weight: 800;
    display: flex; align-items: center; justify-content: center;
    margin: 0 auto 16px;
}
.team-avatar.avatar-img {
    background: var(--cream2);
    overflow: hidden;
}
.team-avatar.avatar-img img { width: 100%; height: 100%; object-fit: cover; }
.team-avatar.avatar-icon {
    background: var(--gold-pale);
    border: 1.5px solid rgba(212,175,55,.3);
}
.team-name {
    font-size: 0.97rem; font-weight: 700; color: var(--txt-dark); margin-bottom: 5px;
}
.team-role {
    font-size: 0.82rem; font-weight: 600; color: var(--gold-dk); margin-bottom: 10px;
}
.team-stack {
    font-size: 0.78rem; color: var(--txt-light);
    display: flex; align-items: center; justify-content: center;
    gap: 6px; flex-wrap: wrap;
}
.team-stack span {
    background: var(--gold-pale); color: var(--gold-dk);
    border-radius: 50px; padding: 2px 10px; font-size: 0.73rem; font-weight: 500;
}

/* ===== CTA ===== */
.section-cta { background: var(--cream); padding: 80px 0; }
.cta-box {
    background: #fff; border: 1.5px solid rgba(212,175,55,.25);
    border-radius: 24px; padding: 64px; text-align: center;
    position: relative; overflow: hidden;
}
.cta-box::before {
    content: ''; position: absolute; top: -80px; right: -80px;
    width: 260px; height: 260px; border-radius: 50%; background: rgba(212,175,55,.07);
}
.cta-box::after {
    content: ''; position: absolute; bottom: -60px; left: -60px;
    width: 200px; height: 200px; border-radius: 50%; background: rgba(212,175,55,.07);
}
.cta-box .section-tag { margin-bottom: 16px; }
.cta-box .section-title { margin-bottom: 14px; }
.cta-box p { color: var(--txt-mid); max-width: 480px; margin: 0 auto 32px; line-height: 1.65; }
.cta-actions { display: flex; gap: 14px; justify-content: center; flex-wrap: wrap; position: relative; z-index: 1; }

/* ===== FOOTER ===== */
footer { background: var(--txt-dark); color: rgba(255,255,255,.75); padding: 60px 0 30px; }
.footer-inner { max-width: 1400px; margin: 0 auto; padding: 0 60px; }
.footer-grid { display: grid; grid-template-columns: 2fr 1fr 1fr 1fr; gap: 48px; margin-bottom: 48px; }
@media(max-width:900px){ .footer-grid { grid-template-columns: 1fr 1fr; } }
@media(max-width:560px){ .footer-grid { grid-template-columns: 1fr; } }
.footer-brand img { height: 36px; filter: brightness(0) invert(1); margin-bottom: 14px; }
.footer-brand p { font-size: 0.875rem; line-height: 1.65; color: rgba(255,255,255,.55); max-width: 260px; }
.footer-col h4 { font-size: 0.8rem; font-weight: 700; text-transform: uppercase; letter-spacing: .8px; color: var(--gold); margin-bottom: 16px; }
.footer-col a { display: block; font-size: 0.875rem; color: rgba(255,255,255,.55); text-decoration: none; margin-bottom: 10px; transition: color .15s; }
.footer-col a:hover { color: var(--gold-lt); }
.footer-bottom {
    border-top: 1px solid rgba(255,255,255,.08); padding-top: 24px;
    display: flex; align-items: center; justify-content: space-between; flex-wrap: wrap; gap: 12px;
}
.footer-bottom p { font-size: 0.82rem; color: rgba(255,255,255,.4); }
.footer-bottom a { color: var(--gold); text-decoration: none; font-size: 0.82rem; }

@media(max-width:1024px){ .section-inner { padding: 0 32px; } }
@media(max-width:640px){
    .section-inner { padding: 0 20px; }
    .trust-inner { padding: 0 20px; gap: 32px; }
    .cta-box { padding: 40px 24px; }
}
</style>
@endpush

@section('content')

{{-- ===== HERO — Banner_Accueil.jpg pleine largeur ===== --}}
<section class="hero" id="hero">
    <img
        src="{{ asset('images/Banner_Accueil.jpg') }}"
        alt=""
        class="hero-bg-img"
        aria-hidden="true"
    >
    <div class="hero-bg-mask" aria-hidden="true"></div>
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
    </div>
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
            <div class="trust-text"><strong>Machines vérifiées</strong><span>Propriétaires certifiés</span></div>
        </div>
        <div class="trust-item">
            <div class="trust-icon">
                <svg width="22" height="22" fill="none" viewBox="0 0 24 24" stroke="#9A7D20" stroke-width="2">
                    <path d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                </svg>
            </div>
            <div class="trust-text"><strong>Contrat PDF immédiat</strong><span>Dès acceptation</span></div>
        </div>
        <div class="trust-item">
            <div class="trust-icon">
                <svg width="22" height="22" fill="none" viewBox="0 0 24 24" stroke="#9A7D20" stroke-width="2">
                    <path d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2z"/>
                </svg>
            </div>
            <div class="trust-text"><strong>Paiement sécurisé</strong><span>Transactions protégées</span></div>
        </div>
        <div class="trust-item">
            <div class="trust-icon">
                <svg width="22" height="22" fill="none" viewBox="0 0 24 24" stroke="#9A7D20" stroke-width="2">
                    <path d="M18.364 5.636l-3.536 3.536m0 5.656l3.536 3.536M9.172 9.172L5.636 5.636m3.536 9.192L5.636 18.364M21 12a9 9 0 11-18 0 9 9 0 0118 0zm-5 0a4 4 0 11-8 0 4 4 0 018 0z"/>
                </svg>
            </div>
            <div class="trust-text"><strong>Support 7j/7</strong><span>Assistance rapide</span></div>
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
                    <img src="{{ $cat['img'] }}" alt="{{ $cat['label'] }}" onerror="this.style.display='none'">
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

{{-- ===== FEATURED MACHINES (STATIC) ===== --}}
<section class="section-machines">
    <div class="section-inner">
        <div class="section-header">
            <span class="section-tag">À la une</span>
            <h2 class="section-title">Engins <span class="accent">disponibles</span></h2>
            <p class="section-sub">Les machines les plus récemment ajoutées sur la plateforme.</p>
        </div>
        <div class="machines-grid">
            <a href="/machines" class="machine-card">
                <div class="machine-img">
                    <img src="{{ asset('images/img1.png') }}" alt="Bulldozer CAT D6">
                    <span class="status-badge available">Disponible</span>
                </div>
                <div class="machine-body">
                    <div class="machine-type">Bulldozer</div>
                    <div class="machine-name">Bulldozer CAT D6</div>
                    <div class="machine-city">
                        <svg width="13" height="13" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/><path d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                        Casablanca
                    </div>
                    <div class="machine-price"><span class="amount">3 200</span><span class="per">DH/jour</span></div>
                </div>
            </a>
            <a href="/machines" class="machine-card">
                <div class="machine-img">
                    <img src="{{ asset('images/img4.png') }}" alt="Grue Liebherr LTM">
                    <span class="status-badge available">Disponible</span>
                </div>
                <div class="machine-body">
                    <div class="machine-type">Grue</div>
                    <div class="machine-name">Grue Liebherr LTM 1050</div>
                    <div class="machine-city">
                        <svg width="13" height="13" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/><path d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                        Rabat
                    </div>
                    <div class="machine-price"><span class="amount">5 500</span><span class="per">DH/jour</span></div>
                </div>
            </a>
            <a href="/machines" class="machine-card">
                <div class="machine-img">
                    <img src="{{ asset('images/img5.png') }}" alt="Nacelle Haulotte">
                    <span class="status-badge available">Disponible</span>
                </div>
                <div class="machine-body">
                    <div class="machine-type">Nacelle</div>
                    <div class="machine-name">Nacelle Haulotte HA16 RTJ</div>
                    <div class="machine-city">
                        <svg width="13" height="13" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/><path d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                        Marrakech
                    </div>
                    <div class="machine-price"><span class="amount">1 800</span><span class="per">DH/jour</span></div>
                </div>
            </a>
            <a href="/machines" class="machine-card">
                <div class="machine-img">
                    <img src="{{ asset('images/img7.png') }}" alt="Tractopelle JCB 3CX">
                    <span class="status-badge available">Disponible</span>
                </div>
                <div class="machine-body">
                    <div class="machine-type">Tractopelle</div>
                    <div class="machine-name">Tractopelle JCB 3CX</div>
                    <div class="machine-city">
                        <svg width="13" height="13" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/><path d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                        Fès
                    </div>
                    <div class="machine-price"><span class="amount">2 800</span><span class="per">DH/jour</span></div>
                </div>
            </a>
            <a href="/machines" class="machine-card">
                <div class="machine-img">
                    <img src="{{ asset('images/img8.png') }}" alt="Compacteur Bomag">
                    <span class="status-badge available">Disponible</span>
                </div>
                <div class="machine-body">
                    <div class="machine-type">Compacteur</div>
                    <div class="machine-name">Compacteur Bomag BW213 D</div>
                    <div class="machine-city">
                        <svg width="13" height="13" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/><path d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                        Tanger
                    </div>
                    <div class="machine-price"><span class="amount">2 100</span><span class="per">DH/jour</span></div>
                </div>
            </a>
            <a href="/machines" class="machine-card">
                <div class="machine-img">
                    <img src="{{ asset('images/img9.png') }}" alt="Camion Benne Mercedes">
                    <span class="status-badge unavailable">Indisponible</span>
                </div>
                <div class="machine-body">
                    <div class="machine-type">Camion BTP</div>
                    <div class="machine-name">Camion Benne Mercedes Actros</div>
                    <div class="machine-city">
                        <svg width="13" height="13" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/><path d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                        Agadir
                    </div>
                    <div class="machine-price"><span class="amount">1 600</span><span class="per">DH/jour</span></div>
                </div>
            </a>
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

{{-- ===== À PROPOS ===== --}}
<section class="section-about" id="a-propos">
    <div class="section-inner">

        {{-- Header principal --}}
        <div class="about-header">
            <span class="section-tag">À propos de nous</span>
            <h2 class="section-title">La plateforme BTP <span class="accent">pensée pour le Maroc</span></h2>
            <p class="section-sub">Rentify est née d'un constat simple : la location d'engins de chantier au Maroc manquait d'une solution digitale fiable, rapide et sécurisée.</p>
        </div>

        {{-- Mission / Vision --}}
        <div class="about-mv-grid">
            <div class="about-mv-card">
                <div class="about-mv-icon">
                    <svg width="22" height="22" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <circle cx="12" cy="12" r="3"/>
                        <path d="M12 1v4M12 19v4M4.22 4.22l2.83 2.83M16.95 16.95l2.83 2.83M1 12h4M19 12h4M4.22 19.78l2.83-2.83M16.95 7.05l2.83-2.83"/>
                    </svg>
                </div>
                <div class="about-mv-title">Notre Mission</div>
                <p class="about-mv-desc">Connecter les propriétaires d'engins BTP avec les entreprises et particuliers qui en ont besoin — simplement, rapidement et en toute sécurité. Chaque réservation génère un contrat PDF légal immédiat.</p>
            </div>
            <div class="about-mv-card vision">
                <div class="about-mv-icon">
                    <svg width="22" height="22" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/>
                        <circle cx="12" cy="12" r="3"/>
                    </svg>
                </div>
                <div class="about-mv-title">Notre Vision</div>
                <p class="about-mv-desc">Devenir la référence nationale de la location d'engins BTP, en offrant la meilleure expérience digitale du secteur au Maroc et en accompagnant la transformation numérique du BTP.</p>
            </div>
        </div>

        {{-- Nos valeurs --}}
        <div class="about-values-wrap">
            <div class="about-values-header">
                <span class="section-tag">Nos valeurs</span>
                <h3 class="section-title">Ce qui nous <span class="accent">distingue</span></h3>
            </div>
            <div class="about-values-grid">
                <div class="about-val-card">
                    <div class="about-val-icon">
                        <svg width="22" height="22" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/>
                        </svg>
                    </div>
                    <div class="about-val-title">Confiance</div>
                    <p class="about-val-desc">Contrats légaux, propriétaires vérifiés, avis authentiques.</p>
                </div>
                <div class="about-val-card">
                    <div class="about-val-icon">
                        <svg width="22" height="22" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path d="M13 10V3L4 14h7v7l9-11h-7z"/>
                        </svg>
                    </div>
                    <div class="about-val-title">Rapidité</div>
                    <p class="about-val-desc">Réservation en 2 minutes, contrat immédiat, réponse rapide.</p>
                </div>
                <div class="about-val-card">
                    <div class="about-val-icon">
                        <svg width="22" height="22" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                            <path d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                        </svg>
                    </div>
                    <div class="about-val-title">Proximité</div>
                    <p class="about-val-desc">Géolocalisation précise, engins proches de votre chantier.</p>
                </div>
                <div class="about-val-card">
                    <div class="about-val-icon">
                        <svg width="22" height="22" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                            <path d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                        </svg>
                    </div>
                    <div class="about-val-title">Transparence</div>
                    <p class="about-val-desc">Prix clairs, avis vérifiés, aucun frais caché.</p>
                </div>
            </div>
        </div>

        {{-- L'équipe --}}
        <div class="about-team-wrap">
            <div class="about-team-header">
                <span class="section-tag">L'équipe</span>
                <h3 class="section-title">Derrière <span class="accent">Rentify</span></h3>
            </div>
            <div class="about-team-grid">
                <div class="about-team-card">
                    <div class="team-avatar">F</div>
                    <div class="team-name">Fadwa Ait Iahbib</div>
                    <div class="team-role">Développeuse Full Stack</div>
                    <div class="team-stack">
                        <span>Laravel</span><span>Blade</span><span>JS</span>
                    </div>
                </div>
                <div class="about-team-card">
                    <div class="team-avatar">S</div>
                    <div class="team-name">Salma Najem</div>
                    <div class="team-role">Développeuse Full Stack</div>
                    <div class="team-stack">
                        <span>Laravel</span><span>Blade</span><span>JS</span>
                    </div>
                </div>
                <div class="about-team-card">
                    <div class="team-avatar avatar-icon">
                        <svg width="26" height="26" fill="none" viewBox="0 0 24 24" stroke="#9A7D20" stroke-width="1.8">
                            <rect x="2" y="3" width="20" height="14" rx="2"/><path d="M8 21h8M12 17v4"/>
                        </svg>
                    </div>
                    <div class="team-name">Rentify</div>
                    <div class="team-role">Projet de Fin d'Études</div>
                    <div class="team-stack">
                        <span>Développement Digital</span>
                    </div>
                </div>
            </div>
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
<script>
(function(){
    const user  = window.getUser  ? window.getUser()  : null;
    const role  = user?.role || null;

    const btnEspace = document.getElementById('btnMonEspace');
    if (btnEspace && user) {
        btnEspace.href = role === 'admin' ? '/dashboard/admin'
                       : role === 'owner' ? '/dashboard/owner'
                       : '/dashboard/client';
    }

    const ctaReg = document.getElementById('ctaBtnRegister');
    if (ctaReg && user) ctaReg.style.display = 'none';

    async function loadCounts(){
        try {
            const d = await window.API.get('/api/machines');
            const machines = Array.isArray(d) ? d : (Array.isArray(d?.data) ? d.data : []);
            const available = machines.filter(m => m.status === 'available').length;
            const el = document.getElementById('statMachines');
            if (el) el.textContent = available + '+';
            const elC = document.getElementById('statClients');
            if (elC) elC.textContent = Math.max(machines.length * 4, 480) + '+';
            const counts = {};
            machines.forEach(m => { counts[m.type] = (counts[m.type]||0) + 1; });
            try { localStorage.setItem('rentify_cat_counts', JSON.stringify(counts)); } catch(e){}
            renderCounts(counts);
        } catch(e) {
            try {
                const saved = localStorage.getItem('rentify_cat_counts');
                if (saved) renderCounts(JSON.parse(saved));
            } catch(e2){}
        }
    }

    function renderCounts(counts){
        Object.keys(counts).forEach(type => {
            const el = document.getElementById('count-' + type);
            if (el) { el.textContent = counts[type]; el.classList.add('loaded'); }
        });
    }

    loadCounts();
})();
</script>
@endpush