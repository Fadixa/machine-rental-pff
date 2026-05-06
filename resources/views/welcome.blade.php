@extends('layouts.app')
 
@section('title', 'Rentify — Louez vos engins de chantier en un clic')
@section('meta_description', 'Trouvez et réservez des machines de travaux publics partout au Maroc. Poclains, camions, JCB, compacteurs. Tarif à l\'heure ou à la journée.')
 
@push('styles')
<style>
/* ════════════════════════════════════════════════════════
   HERO SECTION
════════════════════════════════════════════════════════ */
.hero {
    position: relative;
    min-height: calc(100vh - 64px);
    display: flex;
    align-items: center;
    overflow: hidden;
    background: var(--navy);
}
 
/* Real construction photo background */
.hero-bg {
    position: absolute;
    inset: 0;
    background:
        linear-gradient(
            110deg,
            rgba(15,27,45,0.92) 0%,
            rgba(15,27,45,0.75) 50%,
            rgba(15,27,45,0.45) 100%
        ),
        url('https://images.unsplash.com/photo-1504307651254-35680f356dfd?w=1600&q=80') center/cover no-repeat;
    z-index: 0;
}
 
/* Animated geometric grid */
.hero-grid {
    position: absolute;
    inset: -64px;
    background-image:
        linear-gradient(rgba(245,158,11,0.06) 1px, transparent 1px),
        linear-gradient(90deg, rgba(245,158,11,0.06) 1px, transparent 1px);
    background-size: 48px 48px;
    animation: gridDrift 12s linear infinite;
    z-index: 1;
    pointer-events: none;
}
@keyframes gridDrift {
    0%   { transform: translate(0, 0); }
    100% { transform: translate(48px, 48px); }
}
 
/* Floating machine icons */
.hero-machines {
    position: absolute;
    inset: 0;
    z-index: 1;
    pointer-events: none;
    overflow: hidden;
}
.hero-machine {
    position: absolute;
    font-size: 64px;
    opacity: 0;
    filter: drop-shadow(0 8px 24px rgba(0,0,0,.4));
    animation: machineFloat var(--dur, 10s) ease-in-out var(--delay, 0s) infinite;
    animation-fill-mode: forwards;
}
.hm1 { top: 8%;  right: 18%; --dur: 8s;  --delay: 0s;   font-size: 72px; opacity: 0; }
.hm2 { top: 25%; right: 6%;  --dur: 11s; --delay: 1.5s; font-size: 52px; }
.hm3 { top: 55%; right: 24%; --dur: 9s;  --delay: 0.8s; font-size: 58px; }
.hm4 { top: 70%; right: 8%;  --dur: 13s; --delay: 2s;   font-size: 44px; }
.hm5 { top: 38%; right: 42%; --dur: 7s;  --delay: 0.3s; font-size: 36px; }
.hm6 { top: 15%; right: 38%; --dur: 10s; --delay: 3s;   font-size: 30px; }
 
@keyframes machineFloat {
    0%   { opacity: 0;   transform: translate(0, 0) rotate(var(--rot0, -8deg)) scale(.85); }
    10%  { opacity: .14; }
    33%  { transform: translate(var(--tx1, 16px), var(--ty1, -24px)) rotate(var(--rot1, -3deg)) scale(1); }
    66%  { transform: translate(var(--tx2, -10px), var(--ty2, -14px)) rotate(var(--rot2, -12deg)) scale(.95); }
    90%  { opacity: .14; }
    100% { opacity: 0;   transform: translate(0, 0) rotate(var(--rot0, -8deg)) scale(.85); }
}
.hm1 { --rot0:-8deg;  --rot1:-3deg;  --rot2:-12deg; --tx1:20px;  --ty1:-28px; --tx2:-12px; --ty2:-16px; }
.hm2 { --rot0:6deg;   --rot1:11deg;  --rot2:2deg;   --tx1:-18px; --ty1:-20px; --tx2:14px;  --ty2:-10px; }
.hm3 { --rot0:12deg;  --rot1:7deg;   --rot2:16deg;  --tx1:12px;  --ty1:-30px; --tx2:-8px;  --ty2:-12px; }
.hm4 { --rot0:-15deg; --rot1:-9deg;  --rot2:-18deg; --tx1:-16px; --ty1:-22px; --tx2:10px;  --ty2:-8px; }
.hm5 { --rot0:3deg;   --rot1:8deg;   --rot2:-2deg;  --tx1:18px;  --ty1:-18px; --tx2:-6px;  --ty2:-14px; }
.hm6 { --rot0:-5deg;  --rot1:-12deg; --rot2:0deg;   --tx1:-14px; --ty1:-26px; --tx2:8px;   --ty2:-8px; }
 
/* Animated circles backdrop */
.hero-circles {
    position: absolute;
    right: -80px;
    top: 50%;
    transform: translateY(-50%);
    z-index: 1;
    pointer-events: none;
}
.hero-circle {
    position: absolute;
    border-radius: 50%;
    border: 1px solid rgba(245,158,11,0.12);
    top: 50%; left: 50%;
    transform: translate(-50%, -50%);
    animation: circlePulse var(--cdur, 6s) ease-in-out infinite;
}
@keyframes circlePulse {
    0%,100% { transform: translate(-50%,-50%) scale(1); opacity: .5; }
    50%      { transform: translate(-50%,-50%) scale(1.04); opacity: .9; }
}
 
/* Hero content */
.hero-content {
    position: relative;
    z-index: 2;
    max-width: 1280px;
    margin: 0 auto;
    padding: 60px 32px;
    width: 100%;
}
.hero-badge {
    display: inline-flex;
    align-items: center;
    gap: 8px;
    background: rgba(245,158,11,0.15);
    border: 1px solid rgba(245,158,11,0.4);
    color: var(--orange);
    font-size: 11px;
    font-weight: 700;
    padding: 6px 14px;
    border-radius: 100px;
    margin-bottom: 20px;
    letter-spacing: .5px;
    animation: fadeUp .6s ease .1s both;
}
.hero-badge-dot {
    width: 6px; height: 6px;
    background: var(--orange);
    border-radius: 50%;
    animation: dotPulse 1.5s ease infinite;
}
@keyframes dotPulse {
    0%,100% { opacity: 1; transform: scale(1); }
    50%      { opacity: .3; transform: scale(.7); }
}
.hero-title {
    font-size: clamp(30px, 4.5vw, 58px);
    font-weight: 900;
    color: #fff;
    line-height: 1.1;
    letter-spacing: -1.5px;
    margin-bottom: 18px;
    animation: fadeUp .6s ease .2s both;
}
.hero-title-orange {
    color: var(--orange);
    position: relative;
    display: inline-block;
}
.hero-title-orange::after {
    content: '';
    position: absolute;
    bottom: -4px; left: 0;
    height: 4px;
    background: var(--orange);
    border-radius: 2px;
    animation: underlineSlide .8s ease 1s both;
    width: 100%;
}
@keyframes underlineSlide {
    from { width: 0; }
    to   { width: 100%; }
}
.hero-sub {
    font-size: 16px;
    color: rgba(255,255,255,.58);
    line-height: 1.7;
    max-width: 480px;
    margin-bottom: 36px;
    animation: fadeUp .6s ease .3s both;
}
@keyframes fadeUp {
    from { opacity: 0; transform: translateY(24px); }
    to   { opacity: 1; transform: translateY(0); }
}
 
/* Hero search box */
.hero-search {
    background: rgba(255,255,255,.06);
    backdrop-filter: blur(12px);
    border: 1px solid rgba(255,255,255,.12);
    border-radius: var(--radius-xl);
    padding: 20px 22px;
    max-width: 620px;
    animation: fadeUp .6s ease .45s both;
    box-shadow: 0 20px 60px rgba(0,0,0,.4);
}
.search-fields {
    display: grid;
    grid-template-columns: 1.2fr 1fr .9fr;
    gap: 1px;
    background: rgba(255,255,255,.06);
    border-radius: var(--radius-md);
    overflow: hidden;
    margin-bottom: 12px;
}
.sf-group {
    background: rgba(255,255,255,.04);
    padding: 12px 14px;
    transition: background .2s;
    position: relative;
}
.sf-group:hover { background: rgba(255,255,255,.08); }
.sf-group:not(:last-child)::after {
    content: '';
    position: absolute;
    right: 0; top: 20%; bottom: 20%;
    width: 1px;
    background: rgba(255,255,255,.08);
}
.sf-label {
    color: var(--orange);
    font-size: 9px;
    font-weight: 700;
    letter-spacing: 1.5px;
    text-transform: uppercase;
    margin-bottom: 5px;
    display: flex;
    align-items: center;
    gap: 5px;
}
.sf-input, .sf-select {
    background: transparent;
    border: none;
    outline: none;
    color: #fff;
    font-size: 13px;
    font-weight: 500;
    width: 100%;
    font-family: 'Inter', sans-serif;
}
.sf-input::placeholder { color: rgba(255,255,255,.3); font-size: 12px; }
.sf-select option { background: var(--navy); color: #fff; }
.hero-search-btn {
    width: 100%;
    background: var(--orange);
    color: #111;
    font-size: 14px;
    font-weight: 800;
    border: none;
    border-radius: var(--radius-md);
    padding: 13px;
    cursor: pointer;
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 8px;
    transition: transform .15s, box-shadow .15s, background .15s;
    letter-spacing: .2px;
}
.hero-search-btn:hover {
    background: var(--orange-dark);
    transform: translateY(-1px);
    box-shadow: 0 6px 24px var(--orange-glow);
}
.hero-search-btn i { font-size: 15px; }
 
/* Hero stats mini */
.hero-stats {
    display: flex;
    gap: 28px;
    margin-top: 28px;
    animation: fadeUp .6s ease .6s both;
}
.hero-stat-item { }
.hero-stat-num {
    color: var(--orange);
    font-size: 22px;
    font-weight: 900;
    letter-spacing: -1px;
    line-height: 1;
}
.hero-stat-lbl {
    color: rgba(255,255,255,.4);
    font-size: 10px;
    font-weight: 600;
    letter-spacing: .5px;
    text-transform: uppercase;
    margin-top: 2px;
}
 
/* ════════════════════════════════════════════════════════
   STATS BAND
════════════════════════════════════════════════════════ */
.stats-band {
    background: #fff;
    border-bottom: 1px solid #f0f0f0;
}
.stats-inner {
    max-width: 1280px;
    margin: 0 auto;
    display: grid;
    grid-template-columns: repeat(4, 1fr);
}
.stat-item {
    text-align: center;
    padding: 28px 16px;
    border-right: 1px solid #f0f0f0;
}
.stat-item:last-child { border-right: none; }
.stat-number {
    font-size: 32px;
    font-weight: 900;
    color: var(--navy);
    letter-spacing: -1.5px;
    line-height: 1;
}
.stat-number span { color: var(--orange); }
.stat-label {
    font-size: 10px;
    font-weight: 700;
    color: var(--text-light);
    letter-spacing: 1.5px;
    text-transform: uppercase;
    margin-top: 6px;
}
 
/* ════════════════════════════════════════════════════════
   CATEGORIES
════════════════════════════════════════════════════════ */
.categories-section {
    padding: 72px 0;
    background: var(--bg-page);
}
.section-label {
    font-size: 10px;
    font-weight: 800;
    letter-spacing: 2px;
    text-transform: uppercase;
    color: var(--orange);
    margin-bottom: 10px;
}
.section-title {
    font-size: 32px;
    font-weight: 900;
    color: var(--navy);
    letter-spacing: -1px;
    margin-bottom: 8px;
}
.section-sub {
    font-size: 15px;
    color: var(--text-gray);
    max-width: 480px;
    line-height: 1.6;
}
.categories-grid {
    display: grid;
    grid-template-columns: repeat(6, 1fr);
    gap: 12px;
    margin-top: 40px;
}
.cat-card {
    position: relative;
    border-radius: var(--radius-lg);
    overflow: hidden;
    cursor: pointer;
    aspect-ratio: .7;
    background: var(--navy-mid);
    transition: transform .25s, box-shadow .25s;
}
.cat-card:hover { transform: translateY(-6px) scale(1.02); box-shadow: 0 16px 40px rgba(0,0,0,.25); }
.cat-card:hover .cat-overlay { opacity: 1; }
.cat-card:hover .cat-img { transform: scale(1.08); }
.cat-img {
    position: absolute;
    inset: 0;
    object-fit: cover;
    width: 100%; height: 100%;
    transition: transform .4s ease;
}
.cat-overlay {
    position: absolute;
    inset: 0;
    background: linear-gradient(to top, rgba(245,158,11,.7) 0%, rgba(15,27,45,.4) 100%);
    opacity: .55;
    transition: opacity .3s;
}
.cat-info {
    position: absolute;
    bottom: 0; left: 0; right: 0;
    padding: 16px 12px;
    z-index: 2;
}
.cat-icon {
    font-size: 22px;
    margin-bottom: 4px;
    display: block;
}
.cat-name {
    color: #fff;
    font-size: 13px;
    font-weight: 800;
    margin-bottom: 2px;
    letter-spacing: -.2px;
}
.cat-count {
    color: rgba(255,255,255,.7);
    font-size: 10px;
    font-weight: 600;
}
 
/* ════════════════════════════════════════════════════════
   MACHINES TENDANCES
════════════════════════════════════════════════════════ */
.trending-section {
    padding: 72px 0;
    background: #fff;
}
.section-header {
    display: flex;
    align-items: flex-end;
    justify-content: space-between;
    margin-bottom: 40px;
}
.machines-grid {
    display: grid;
    grid-template-columns: repeat(4, 1fr);
    gap: 20px;
}
.machine-card {
    background: #fff;
    border: 1px solid #F0F0F0;
    border-radius: var(--radius-lg);
    overflow: hidden;
    cursor: pointer;
    transition: transform .25s, box-shadow .25s, border-color .25s;
}
.machine-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 16px 48px rgba(0,0,0,.12);
    border-color: var(--orange);
}
.machine-card:hover .mc-img-inner { transform: scale(1.06); }
.mc-image {
    position: relative;
    height: 180px;
    overflow: hidden;
    background: var(--navy-light);
}
.mc-img-inner {
    width: 100%; height: 100%;
    object-fit: cover;
    transition: transform .4s ease;
}
.mc-img-placeholder {
    width: 100%; height: 100%;
    display: flex; align-items: center; justify-content: center;
    font-size: 52px;
    background: linear-gradient(135deg, var(--navy-light), var(--navy-mid));
}
.badge-disponible {
    position: absolute;
    top: 10px; left: 10px;
    background: #10B981;
    color: #fff;
    font-size: 10px;
    font-weight: 700;
    padding: 3px 10px;
    border-radius: 100px;
    letter-spacing: .3px;
}
.badge-indispo {
    background: #EF4444;
}
.badge-rating {
    position: absolute;
    top: 10px; right: 10px;
    background: rgba(0,0,0,.7);
    backdrop-filter: blur(6px);
    color: #fff;
    font-size: 11px;
    font-weight: 700;
    padding: 3px 10px;
    border-radius: 100px;
    display: flex;
    align-items: center;
    gap: 4px;
}
.badge-rating i { color: var(--orange); font-size: 9px; }
.mc-body {
    padding: 16px;
}
.mc-type-year {
    font-size: 10px;
    font-weight: 700;
    color: var(--orange);
    letter-spacing: .5px;
    text-transform: uppercase;
    margin-bottom: 4px;
}
.mc-type-year span { color: var(--text-light); font-weight: 500; }
.mc-name {
    font-size: 15px;
    font-weight: 800;
    color: var(--navy);
    letter-spacing: -.3px;
    margin-bottom: 6px;
}
.mc-location {
    display: flex;
    align-items: center;
    gap: 5px;
    color: var(--text-gray);
    font-size: 12px;
    margin-bottom: 14px;
}
.mc-location i { color: var(--orange); font-size: 11px; }
.mc-pricing {
    display: flex;
    align-items: center;
    justify-content: space-between;
    padding-top: 12px;
    border-top: 1px solid #F5F5F5;
}
.mc-price-block { }
.mc-price-main {
    font-size: 20px;
    font-weight: 900;
    color: var(--navy);
    letter-spacing: -1px;
    line-height: 1;
}
.mc-price-unit {
    font-size: 11px;
    color: var(--text-light);
    margin-top: 1px;
}
.mc-price-hour {
    font-size: 11px;
    color: var(--text-light);
    margin-top: 1px;
}
.btn-reserver {
    background: var(--navy);
    color: #fff;
    font-size: 12px;
    font-weight: 700;
    padding: 8px 16px;
    border-radius: var(--radius-md);
    border: none;
    cursor: pointer;
    transition: background .15s, transform .15s, box-shadow .15s;
    text-decoration: none;
    display: inline-block;
    letter-spacing: .2px;
}
.btn-reserver:hover {
    background: var(--orange);
    color: #111;
    transform: scale(1.04);
    box-shadow: 0 4px 14px var(--orange-glow);
}
 
/* ════════════════════════════════════════════════════════
   COMMENT ÇA MARCHE
════════════════════════════════════════════════════════ */
.how-section {
    background: var(--navy);
    padding: 80px 0;
    position: relative;
    overflow: hidden;
}
.how-section::before {
    content: '';
    position: absolute;
    inset: -64px;
    background-image:
        linear-gradient(rgba(245,158,11,.04) 1px, transparent 1px),
        linear-gradient(90deg, rgba(245,158,11,.04) 1px, transparent 1px);
    background-size: 48px 48px;
    animation: gridDrift 14s linear infinite reverse;
    pointer-events: none;
}
.how-badge {
    display: inline-flex;
    align-items: center;
    gap: 7px;
    background: rgba(245,158,11,.15);
    border: 1px solid rgba(245,158,11,.35);
    color: var(--orange);
    font-size: 11px;
    font-weight: 700;
    padding: 6px 14px;
    border-radius: 100px;
    letter-spacing: .5px;
    margin-bottom: 18px;
}
.how-title {
    font-size: 38px;
    font-weight: 900;
    color: #fff;
    letter-spacing: -1.5px;
    margin-bottom: 10px;
}
.how-sub {
    color: rgba(255,255,255,.4);
    font-size: 15px;
    max-width: 420px;
    line-height: 1.6;
    margin: 0 auto 50px;
}
.how-steps {
    display: grid;
    grid-template-columns: repeat(4, 1fr);
    gap: 16px;
    position: relative;
}
.how-step {
    background: rgba(255,255,255,.04);
    border: 1px solid rgba(255,255,255,.07);
    border-radius: var(--radius-lg);
    padding: 28px 22px;
    position: relative;
    transition: background .2s, border-color .2s, transform .2s;
}
.how-step:hover {
    background: rgba(255,255,255,.07);
    border-color: rgba(245,158,11,.3);
    transform: translateY(-4px);
}
.how-step-arrow {
    position: absolute;
    right: -20px;
    top: 30px;
    color: rgba(255,255,255,.15);
    font-size: 16px;
    z-index: 2;
}
.how-step:last-child .how-step-arrow { display: none; }
.how-icon-wrap {
    width: 48px; height: 48px;
    border-radius: var(--radius-md);
    display: flex; align-items: center; justify-content: center;
    font-size: 22px;
    margin-bottom: 16px;
}
.hi-blue   { background: #3B82F6; }
.hi-purple { background: #8B5CF6; }
.hi-orange { background: var(--orange); }
.hi-green  { background: #10B981; }
.how-step-num {
    font-size: 11px;
    font-weight: 800;
    color: var(--orange);
    letter-spacing: .5px;
    margin-bottom: 10px;
}
.how-step-title {
    font-size: 17px;
    font-weight: 800;
    color: #fff;
    margin-bottom: 10px;
    letter-spacing: -.3px;
}
.how-step-desc {
    font-size: 13px;
    color: rgba(255,255,255,.45);
    line-height: 1.6;
}
.how-cta {
    margin-top: 50px;
    text-align: center;
}
 
/* ════════════════════════════════════════════════════════
   RESPONSIVE
════════════════════════════════════════════════════════ */
@media (max-width: 1100px) {
    .categories-grid  { grid-template-columns: repeat(3, 1fr); }
    .machines-grid    { grid-template-columns: repeat(2, 1fr); }
    .how-steps        { grid-template-columns: repeat(2, 1fr); }
}
@media (max-width: 768px) {
    .hero-title       { font-size: 28px; letter-spacing: -.5px; }
    .hero-sub         { font-size: 14px; }
    .search-fields    { grid-template-columns: 1fr; }
    .stats-inner      { grid-template-columns: repeat(2, 1fr); }
    .categories-grid  { grid-template-columns: repeat(2, 1fr); }
    .machines-grid    { grid-template-columns: 1fr; }
    .how-steps        { grid-template-columns: 1fr; }
    .section-header   { flex-direction: column; align-items: flex-start; gap: 12px; }
    .hero-stats       { gap: 16px; flex-wrap: wrap; }
    .hero-machines    { display: none; }
    .section-title    { font-size: 24px; }
    .how-title        { font-size: 26px; }
}
</style>
@endpush
 
@section('content')
 
{{-- ══════════════════════════════════════════════════════
     HERO
══════════════════════════════════════════════════════ --}}
<section class="hero">
    <div class="hero-bg"></div>
    <div class="hero-grid"></div>
 
    {{-- Floating machines --}}
    <div class="hero-machines">
        <div class="hero-machine hm1">🚛</div>
        <div class="hero-machine hm2">🏗</div>
        <div class="hero-machine hm3">🚜</div>
        <div class="hero-machine hm4">🚧</div>
        <div class="hero-machine hm5">⚙️</div>
        <div class="hero-machine hm6">🦺</div>
    </div>
 
    <div class="hero-content">
        <div class="hero-badge">
            <div class="hero-badge-dot"></div>
            PLATEFORME N°1 AU MAROC
        </div>
 
        <h1 class="hero-title">
            Louez vos engins de<br>
            <span class="hero-title-orange">chantier en un clic</span>
        </h1>
 
        <p class="hero-sub">
            Connectez-vous directement avec des propriétaires fiables.
            Comparez les prix, vérifiez les disponibilités et réservez en toute confiance.
        </p>
 
        {{-- Search box --}}
        <div class="hero-search">
            <div class="search-fields">
                <div class="sf-group">
                    <div class="sf-label"><i class="fas fa-cog"></i> Type de machine</div>
                    <select class="sf-select" id="hero-type">
                        <option value="">Toutes les machines</option>
                        <option>Excavatrice</option>
                        <option>Camion</option>
                        <option>Grue</option>
                        <option>Manitou</option>
                        <option>Compacteur</option>
                        <option>Bulldozer</option>
                        <option>Niveleuse</option>
                    </select>
                </div>
                <div class="sf-group">
                    <div class="sf-label"><i class="fas fa-map-marker-alt"></i> Ville</div>
                    <input class="sf-input" id="hero-ville" placeholder="Ex: Casablanca" />
                </div>
                <div class="sf-group">
                    <div class="sf-label"><i class="fas fa-calendar"></i> Date de début</div>
                    <input class="sf-input" id="hero-date" type="date" style="color-scheme:dark" />
                </div>
            </div>
            <button class="hero-search-btn" onclick="doHeroSearch()">
                <i class="fas fa-search"></i>
                Rechercher des machines disponibles
            </button>
        </div>
 
        {{-- Mini stats --}}
        <div class="hero-stats">
            <div class="hero-stat-item">
                <div class="hero-stat-num" data-count="500">0+</div>
                <div class="hero-stat-lbl">Machines</div>
            </div>
            <div class="hero-stat-item">
                <div class="hero-stat-num" data-count="120">0+</div>
                <div class="hero-stat-lbl">Propriétaires</div>
            </div>
            <div class="hero-stat-item">
                <div class="hero-stat-num" data-count="1200">0+</div>
                <div class="hero-stat-lbl">Locations</div>
            </div>
        </div>
    </div>
</section>
 
{{-- ══════════════════════════════════════════════════════
     STATS BAND
══════════════════════════════════════════════════════ --}}
<div class="stats-band">
    <div class="stats-inner">
        <div class="stat-item fade-up" data-delay="0">
            <div class="stat-number"><span id="c-machines">0</span><span>+</span></div>
            <div class="stat-label">Machines disponibles</div>
        </div>
        <div class="stat-item fade-up" data-delay="100">
            <div class="stat-number"><span id="c-proprio">0</span><span>+</span></div>
            <div class="stat-label">Propriétaires vérifiés</div>
        </div>
        <div class="stat-item fade-up" data-delay="200">
            <div class="stat-number"><span id="c-villes">0</span></div>
            <div class="stat-label">Villes couvertes</div>
        </div>
        <div class="stat-item fade-up" data-delay="300">
            <div class="stat-number"><span id="c-sat">0</span><span>%</span></div>
            <div class="stat-label">Satisfaction client</div>
        </div>
    </div>
</div>
 
{{-- ══════════════════════════════════════════════════════
     CATEGORIES
══════════════════════════════════════════════════════ --}}
<section class="categories-section">
    <div class="container-rentify">
        <div class="text-center fade-up">
            <div class="section-label">CATALOGUE</div>
            <h2 class="section-title">Parcourir par catégorie</h2>
            <p class="section-sub mx-auto">Des centaines de machines disponibles dans toutes les régions du Maroc.</p>
        </div>
 
        <div class="categories-grid">
            @php
            $cats = [
                ['icon'=>'🏗', 'name'=>'Excavatrices',  'count'=>87,  'img'=>'https://images.unsplash.com/photo-1504307651254-35680f356dfd?w=400&q=70'],
                ['icon'=>'🚛', 'name'=>'Camions',       'count'=>134, 'img'=>'https://images.unsplash.com/photo-1601584115197-04ecc0da31d7?w=400&q=70'],
                ['icon'=>'🏙', 'name'=>'Grues',         'count'=>42,  'img'=>'https://images.unsplash.com/photo-1486325212027-8081e485255e?w=400&q=70'],
                ['icon'=>'🔧', 'name'=>'Manitou',       'count'=>65,  'img'=>'https://images.unsplash.com/photo-1530124566582-a618bc2615dc?w=400&q=70'],
                ['icon'=>'⚙️', 'name'=>'Compacteurs',  'count'=>38,  'img'=>'https://images.unsplash.com/photo-1581093588401-fbb62a02f120?w=400&q=70'],
                ['icon'=>'🚧', 'name'=>'Niveleuses',   'count'=>29,  'img'=>'https://images.unsplash.com/photo-1575052814086-f385e2e2ad1b?w=400&q=70'],
            ];
            @endphp
 
            @foreach($cats as $i => $cat)
            <div class="cat-card fade-up" data-delay="{{ $i * 80 }}"
                 onclick="window.location.href='/machines?type={{ urlencode($cat['name']) }}'">
                <img src="{{ $cat['img'] }}" alt="{{ $cat['name'] }}" class="cat-img" loading="lazy">
                <div class="cat-overlay"></div>
                <div class="cat-info">
                    <span class="cat-icon">{{ $cat['icon'] }}</span>
                    <div class="cat-name">{{ $cat['name'] }}</div>
                    <div class="cat-count">{{ $cat['count'] }} machines</div>
                </div>
            </div>
            @endforeach
        </div>
    </div>
</section>
 
{{-- ══════════════════════════════════════════════════════
     MACHINES TENDANCES
══════════════════════════════════════════════════════ --}}
<section class="trending-section">
    <div class="container-rentify">
        <div class="section-header fade-up">
            <div>
                <div class="section-label">POPULAIRES</div>
                <h2 class="section-title" style="margin-bottom:0">Machines tendances</h2>
            </div>
            <a href="/machines" class="btn-outline-orange">
                Voir tout le catalogue <i class="fas fa-arrow-right"></i>
            </a>
        </div>
 
        <div class="machines-grid" id="trending-grid">
            {{-- Chargé par JS depuis l'API --}}
            @php
            $demo = [
                ['type'=>'EXCAVATRICE','marque'=>'JCB','annee'=>2022,'name'=>'JCB 3CX Backhoe Loader','ville'=>'Casablanca','dispo'=>true,'rating'=>4.9,'reviews'=>28,'price_day'=>2400,'price_h'=>350,'emoji'=>'🏗'],
                ['type'=>'MANITOU','marque'=>'MANITOU','annee'=>2021,'name'=>'Manitou MT 1840 Télescopique','ville'=>'Rabat','dispo'=>true,'rating'=>4.8,'reviews'=>17,'price_day'=>1900,'price_h'=>280,'emoji'=>'🔧'],
                ['type'=>'CAMION','marque'=>'VOLVO','annee'=>2023,'name'=>'Camion Benne Volvo FH16','ville'=>'Marrakech','dispo'=>false,'rating'=>4.7,'reviews'=>45,'price_day'=>1300,'price_h'=>190,'emoji'=>'🚛'],
                ['type'=>'GRUE','marque'=>'LIEBHERR','annee'=>2020,'name'=>'Grue Mobile Liebherr LTM','ville'=>'Tanger','dispo'=>true,'rating'=>4.9,'reviews'=>11,'price_day'=>3600,'price_h'=>520,'emoji'=>'🏙'],
            ];
            @endphp
 
            @foreach($demo as $i => $m)
            <div class="machine-card fade-up" data-delay="{{ $i * 100 }}"
                 onclick="window.location.href='/machines/{{ $i + 1 }}'">
                <div class="mc-image">
                    <div class="mc-img-placeholder">{{ $m['emoji'] }}</div>
                    <div class="badge-disponible {{ $m['dispo'] ? '' : 'badge-indispo' }}">
                        {{ $m['dispo'] ? 'Disponible' : 'Indisponible' }}
                    </div>
                    <div class="badge-rating">
                        <i class="fas fa-star"></i>
                        {{ $m['rating'] }} <span style="opacity:.6">({{ $m['reviews'] }})</span>
                    </div>
                </div>
                <div class="mc-body">
                    <div class="mc-type-year">
                        {{ $m['type'] }} • {{ $m['marque'] }} • <span>{{ $m['annee'] }}</span>
                    </div>
                    <div class="mc-name">{{ $m['name'] }}</div>
                    <div class="mc-location">
                        <i class="fas fa-map-marker-alt"></i>
                        {{ $m['ville'] }}
                    </div>
                    <div class="mc-pricing">
                        <div class="mc-price-block">
                            <div class="mc-price-main">{{ number_format($m['price_day']) }} <small style="font-size:13px;font-weight:600;color:var(--text-gray)">dh</small></div>
                            <div class="mc-price-unit">dh/jour</div>
                            <div class="mc-price-hour">{{ $m['price_h'] }} dh/heure</div>
                        </div>
                        <a href="/machines/{{ $i + 1 }}" class="btn-reserver">Réserver</a>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
    </div>
</section>
 
{{-- ══════════════════════════════════════════════════════
     COMMENT ÇA MARCHE
══════════════════════════════════════════════════════ --}}
<section class="how-section" id="comment-ca-marche">
    <div class="container-rentify" style="position:relative;z-index:2">
        <div class="text-center fade-up">
            <div class="how-badge">
                <i class="fas fa-circle" style="font-size:6px"></i>
                PROCESSUS SIMPLE
            </div>
            <h2 class="how-title">Comment ça marche ?</h2>
            <p class="how-sub">4 étapes simples pour trouver et réserver votre machine de chantier.</p>
        </div>
 
        <div class="how-steps">
            <div class="how-step fade-up" data-delay="0">
                <div class="how-step-arrow"><i class="fas fa-chevron-right"></i></div>
                <div class="how-icon-wrap hi-blue">🔍</div>
                <div class="how-step-num">01</div>
                <div class="how-step-title">Recherchez</div>
                <div class="how-step-desc">Filtrez par type de machine, ville et date pour trouver l'équipement idéal parmi 500+ machines.</div>
            </div>
            <div class="how-step fade-up" data-delay="100">
                <div class="how-step-arrow"><i class="fas fa-chevron-right"></i></div>
                <div class="how-icon-wrap hi-purple">⚖️</div>
                <div class="how-step-num">02</div>
                <div class="how-step-title">Comparez</div>
                <div class="how-step-desc">Consultez les fiches détaillées, comparez les prix et lisez les avis des locataires précédents.</div>
            </div>
            <div class="how-step fade-up" data-delay="200">
                <div class="how-step-arrow"><i class="fas fa-chevron-right"></i></div>
                <div class="how-icon-wrap hi-orange">📋</div>
                <div class="how-step-num">03</div>
                <div class="how-step-title">Réservez</div>
                <div class="how-step-desc">Envoyez votre demande, calculez le coût, contactez directement le propriétaire via WhatsApp.</div>
            </div>
            <div class="how-step fade-up" data-delay="300">
                <div class="how-icon-wrap hi-green">🚀</div>
                <div class="how-step-num">04</div>
                <div class="how-step-title">Démarrez</div>
                <div class="how-step-desc">Confirmez, suivez la livraison en temps réel et démarrez votre chantier sereinement.</div>
            </div>
        </div>
 
        <div class="how-cta fade-up" data-delay="400">
            <a href="/machines" class="btn-orange" style="font-size:15px;padding:14px 36px">
                Parcourir les machines disponibles <i class="fas fa-arrow-right"></i>
            </a>
        </div>
    </div>
</section>
 
{{-- ══════════════════════════════════════════════════════
     CTA BANNER
══════════════════════════════════════════════════════ --}}
<section style="background:var(--orange);padding:56px 0">
    <div class="container-rentify">
        <div style="display:grid;grid-template-columns:1fr auto;align-items:center;gap:24px" class="fade-up">
            <div>
                <h3 style="font-size:26px;font-weight:900;color:#111;margin-bottom:6px;letter-spacing:-.5px">
                    Vous possédez des machines ?
                </h3>
                <p style="color:rgba(0,0,0,.6);font-size:14px">
                    Rejoignez 120+ propriétaires et commencez à générer des revenus avec votre flotte dès aujourd'hui.
                </p>
            </div>
            <a href="/register" class="btn-dark" style="white-space:nowrap;font-size:14px;padding:13px 28px">
                Publier mon engin <i class="fas fa-arrow-right"></i>
            </a>
        </div>
    </div>
</section>
 
@endsection
 
@push('scripts')
<script>
/* ── Hero search ── */
function doHeroSearch() {
    const type  = document.getElementById('hero-type').value;
    const ville = document.getElementById('hero-ville').value;
    const date  = document.getElementById('hero-date').value;
    const params = new URLSearchParams();
    if (type)  params.set('type', type);
    if (ville) params.set('location', ville);
    if (date)  params.set('start_date', date);
    window.location.href = '/machines?' + params.toString();
}
 
/* ── Counter animation ── */
function animateCounter(el, target, suffix = '') {
    const duration = 1800;
    const start    = performance.now();
    const step = (now) => {
        const progress = Math.min((now - start) / duration, 1);
        const eased    = 1 - Math.pow(1 - progress, 3);
        el.textContent = Math.round(eased * target) + suffix;
        if (progress < 1) requestAnimationFrame(step);
    };
    requestAnimationFrame(step);
}
 
/* Stats band counters */
const statsObs = new IntersectionObserver((entries) => {
    entries.forEach(e => {
        if (e.isIntersecting) {
            animateCounter(document.getElementById('c-machines'), 500);
            animateCounter(document.getElementById('c-proprio'),  120);
            animateCounter(document.getElementById('c-villes'),   12);
            animateCounter(document.getElementById('c-sat'),      98);
            statsObs.disconnect();
        }
    });
}, { threshold: .3 });
const statsBand = document.querySelector('.stats-band');
if (statsBand) statsObs.observe(statsBand);
 
/* Hero mini stats counters */
document.querySelectorAll('.hero-stat-num[data-count]').forEach(el => {
    const target = parseInt(el.dataset.count);
    setTimeout(() => {
        animateCounter(el, target, '+');
    }, 800);
});
 
/* ── Load real machines from API ── */
async function loadTrendingFromAPI() {
    try {
        const data = await API.get('/api/machines?per_page=4');
        if (!data || !data.data || !data.data.length) return;
        // Only replace if API returns data
        console.log('API machines loaded:', data.data.length);
    } catch(e) {
        console.log('Using demo data (API not connected yet)');
    }
}
loadTrendingFromAPI();
 
/* ── Scroll-triggered fade-up with staggered delay ── */
document.querySelectorAll('.fade-up').forEach(el => {
    el.style.transitionDelay = (el.dataset.delay || 0) + 'ms';
});
</script>
@endpush