@extends('layouts.app')
@section('title', 'Détail machine — Rentify')

@push('styles')
<style>
.show-header {
    background: var(--navy); padding: 20px 0 16px;
    border-bottom: 1px solid var(--border);
}
.back-link {
    color: rgba(255,255,255,.5); font-size: 13px; font-weight: 500;
    text-decoration: none; display: inline-flex; align-items: center; gap: 6px;
    transition: color .2s;
}
.back-link:hover { color: var(--orange); }

.show-body {
    max-width: 1280px; margin: 0 auto; padding: 28px 32px;
    display: grid; grid-template-columns: 1fr 360px; gap: 28px; align-items: start;
}

/* ── Gallery ── */
.gallery-main {
    border-radius: var(--radius-lg); overflow: hidden;
    height: 360px; background: var(--navy-light);
    position: relative; cursor: zoom-in;
}
.gallery-main-img {
    width: 100%; height: 100%; object-fit: cover;
    transition: transform .4s ease;
}
.gallery-main:hover .gallery-main-img { transform: scale(1.04); }
.gallery-placeholder {
    width: 100%; height: 100%;
    display: flex; align-items: center; justify-content: center;
    font-size: 72px;
}
.gallery-nav {
    position: absolute; top: 50%; transform: translateY(-50%);
    display: flex; justify-content: space-between; width: 100%; padding: 0 12px;
    pointer-events: none;
}
.gallery-btn {
    width: 36px; height: 36px;
    background: rgba(0,0,0,.6); backdrop-filter: blur(6px);
    border-radius: 50%; border: none; color: #fff;
    cursor: pointer; pointer-events: all;
    display: flex; align-items: center; justify-content: center;
    font-size: 13px; transition: background .15s;
}
.gallery-btn:hover { background: var(--orange); }
.gallery-thumbs {
    display: flex; gap: 8px; margin-top: 10px;
}
.gallery-thumb {
    width: 72px; height: 56px; border-radius: 8px; overflow: hidden;
    border: 2px solid transparent; cursor: pointer; transition: border-color .15s;
    background: var(--navy-light); flex-shrink: 0;
    display: flex; align-items: center; justify-content: center; font-size: 24px;
}
.gallery-thumb.active { border-color: var(--orange); }
.gallery-thumb:hover   { border-color: rgba(245,158,11,.5); }

/* ── Machine info ── */
.machine-info { margin-top: 20px; }
.info-badge {
    display: inline-flex; align-items: center; gap: 6px;
    background: rgba(245,158,11,.12); border: 1px solid rgba(245,158,11,.3);
    color: var(--orange); font-size: 10px; font-weight: 800;
    padding: 4px 12px; border-radius: 100px; letter-spacing: .5px;
    text-transform: uppercase; margin-bottom: 10px;
}
.machine-title {
    font-size: 26px; font-weight: 900; color: var(--navy);
    letter-spacing: -.6px; margin-bottom: 8px;
}
.machine-meta {
    display: flex; align-items: center; gap: 16px;
    flex-wrap: wrap; margin-bottom: 14px;
}
.meta-rating { display: flex; align-items: center; gap: 5px; }
.meta-rating i { color: var(--orange); font-size: 13px; }
.meta-rating span { font-size: 14px; font-weight: 800; color: var(--navy); }
.meta-rating small { font-size: 12px; color: var(--text-light); }
.meta-dispo {
    display: flex; align-items: center; gap: 5px;
    font-size: 12px; font-weight: 700;
}
.dispo-dot { width: 8px; height: 8px; border-radius: 50%; }
.machine-desc {
    font-size: 14px; color: var(--text-gray); line-height: 1.7;
    margin-bottom: 20px;
}

/* Specs */
.specs-grid {
    display: grid; grid-template-columns: repeat(4,1fr); gap: 10px;
    margin-bottom: 20px;
}
.spec-card {
    background: #F9FAFB; border: 1px solid #F0F0F0;
    border-radius: var(--radius-md); padding: 14px 12px; text-align: center;
}
.spec-icon { font-size: 20px; margin-bottom: 6px; color: var(--orange); }
.spec-label { font-size: 10px; color: var(--text-light); font-weight: 600; text-transform: uppercase; letter-spacing: .5px; margin-bottom: 3px; }
.spec-value { font-size: 14px; font-weight: 800; color: var(--navy); }

/* Equipements */
.equip-grid {
    display: grid; grid-template-columns: 1fr 1fr; gap: 7px;
}
.equip-item {
    display: flex; align-items: center; gap: 8px;
    font-size: 13px; color: var(--text-gray);
}
.equip-item i { color: #10B981; font-size: 12px; }

/* Calendar */
.availability-cal {
    background: #fff; border: 1px solid #F0F0F0;
    border-radius: var(--radius-lg); padding: 18px; margin-top: 20px;
}
.cal-header {
    display: flex; align-items: center; justify-content: space-between; margin-bottom: 14px;
}
.cal-title { font-size: 14px; font-weight: 800; color: var(--navy); }
.cal-nav { display: flex; gap: 6px; }
.cal-nav-btn {
    width: 28px; height: 28px; border: 1px solid #E5E7EB;
    border-radius: 6px; background: #fff; cursor: pointer;
    display: flex; align-items: center; justify-content: center;
    font-size: 11px; color: var(--text-gray); transition: all .15s;
}
.cal-nav-btn:hover { border-color: var(--orange); color: var(--orange); }
.cal-days-header {
    display: grid; grid-template-columns: repeat(7,1fr);
    gap: 2px; margin-bottom: 6px;
}
.cal-day-hdr { text-align: center; font-size: 10px; font-weight: 700; color: var(--text-light); padding: 3px; }
.cal-days { display: grid; grid-template-columns: repeat(7,1fr); gap: 2px; }
.cal-day {
    aspect-ratio: 1; display: flex; align-items: center; justify-content: center;
    font-size: 11px; font-weight: 500; border-radius: 5px;
    cursor: default; color: var(--navy);
}
.cal-day.reserved { background: #FEE2E2; color: #991B1B; }
.cal-day.available { background: #D1FAE5; color: #065F46; cursor: pointer; }
.cal-day.available:hover { background: var(--orange); color: #fff; }
.cal-day.empty { visibility: hidden; }
.cal-legend { display: flex; gap: 16px; margin-top: 10px; }
.cal-legend-item { display: flex; align-items: center; gap: 5px; font-size: 11px; color: var(--text-gray); }
.leg-dot { width: 10px; height: 10px; border-radius: 3px; }

/* ── Booking sidebar ── */
.booking-sidebar {
    position: sticky; top: 90px;
    background: #fff; border: 1px solid #F0F0F0;
    border-radius: var(--radius-lg); overflow: hidden;
}
.booking-price-header {
    background: var(--navy); padding: 20px 22px;
}
.booking-price-main {
    font-size: 34px; font-weight: 900; color: #fff; letter-spacing: -1.5px; line-height: 1;
}
.booking-price-main span { color: var(--orange); font-size: 18px; font-weight: 600; }
.booking-price-sub { color: rgba(255,255,255,.4); font-size: 12px; margin-top: 4px; }
.booking-body { padding: 18px 20px; }
.booking-label {
    font-size: 10px; font-weight: 800; color: var(--text-light);
    letter-spacing: 1px; text-transform: uppercase; margin-bottom: 6px;
}

/* Mode tabs */
.mode-tabs {
    display: flex; background: #F3F4F6; border-radius: 8px;
    padding: 3px; margin-bottom: 16px;
}
.mode-tab {
    flex: 1; text-align: center; padding: 7px 4px;
    font-size: 12px; font-weight: 600; color: var(--text-gray);
    border-radius: 6px; cursor: pointer; transition: all .15s;
}
.mode-tab.active { background: #fff; color: var(--navy); box-shadow: var(--shadow-sm); }

/* Date input */
.booking-date-input {
    width: 100%; padding: 10px 12px;
    border: 1.5px solid #E5E7EB; border-radius: var(--radius-md);
    font-size: 13px; font-weight: 500; color: var(--navy);
    outline: none; transition: border-color .2s;
    font-family: 'Inter', sans-serif; margin-bottom: 14px;
}
.booking-date-input:focus { border-color: var(--orange); }

/* Duration stepper */
.duration-stepper {
    display: flex; align-items: center; justify-content: space-between;
    border: 1.5px solid #E5E7EB; border-radius: var(--radius-md);
    overflow: hidden; margin-bottom: 16px;
}
.step-btn {
    width: 40px; height: 40px; background: #F9FAFB;
    border: none; cursor: pointer; font-size: 18px;
    color: var(--navy); transition: background .15s;
    display: flex; align-items: center; justify-content: center;
}
.step-btn:hover { background: var(--orange); color: #fff; }
.step-val {
    font-size: 18px; font-weight: 900; color: var(--navy); min-width: 40px; text-align: center;
}

/* Price breakdown */
.price-breakdown {
    background: #F9FAFB; border-radius: var(--radius-md);
    padding: 14px; margin-bottom: 14px; font-size: 13px;
}
.pb-row {
    display: flex; justify-content: space-between; align-items: center;
    margin-bottom: 7px; color: var(--text-gray);
}
.pb-row:last-child { margin-bottom: 0; padding-top: 7px; border-top: 1px solid #E5E7EB; }
.pb-row:last-child span { color: var(--navy); font-size: 16px; font-weight: 900; }
.pb-row:last-child strong { color: var(--navy); }

/* Owner card */
.owner-card {
    display: flex; align-items: center; gap: 12px;
    background: #F9FAFB; border-radius: var(--radius-md);
    padding: 12px 14px; margin-bottom: 14px;
}
.owner-avatar {
    width: 40px; height: 40px; background: var(--orange);
    border-radius: 50%; display: flex; align-items: center; justify-content: center;
    font-size: 16px; font-weight: 800; color: #111; flex-shrink: 0;
}
.owner-name { font-size: 13px; font-weight: 700; color: var(--navy); }
.owner-meta { font-size: 11px; color: var(--text-light); }
.owner-badge {
    font-size: 10px; font-weight: 700; color: #10B981;
    display: inline-flex; align-items: center; gap: 3px;
}
.owner-actions { display: flex; gap: 7px; }
.btn-whatsapp {
    flex: 1; background: #25D366; color: #fff;
    font-size: 12px; font-weight: 700; border: none;
    border-radius: var(--radius-md); padding: 9px;
    cursor: pointer; display: flex; align-items: center; justify-content: center; gap: 5px;
    transition: opacity .15s; text-decoration: none;
}
.btn-whatsapp:hover { opacity: .9; color: #fff; }
.btn-call {
    flex: 1; background: #3B82F6; color: #fff;
    font-size: 12px; font-weight: 700; border: none;
    border-radius: var(--radius-md); padding: 9px;
    cursor: pointer; display: flex; align-items: center; justify-content: center; gap: 5px;
    transition: opacity .15s; text-decoration: none;
}
.btn-call:hover { opacity: .9; color: #fff; }

/* Tracking timeline */
.tracking {
    border-top: 1px solid #F0F0F0; padding-top: 16px; margin-top: 16px;
}
.tracking-title { font-size: 11px; font-weight: 800; color: var(--text-light); letter-spacing: 1px; text-transform: uppercase; margin-bottom: 12px; }
.track-item {
    display: flex; align-items: center; gap: 10px;
    margin-bottom: 10px; font-size: 12px;
}
.track-dot {
    width: 22px; height: 22px; border-radius: 50%;
    display: flex; align-items: center; justify-content: center;
    font-size: 10px; flex-shrink: 0;
}
.track-done { background: #10B981; color: #fff; }
.track-active { background: var(--orange); color: #fff; animation: trackPulse 1.5s ease infinite; }
.track-pending { background: #F0F0F0; color: var(--text-light); }
@keyframes trackPulse {
    0%,100% { box-shadow: 0 0 0 0 rgba(245,158,11,.4); }
    50%      { box-shadow: 0 0 0 6px rgba(245,158,11,0); }
}
.track-label { color: var(--navy); font-weight: 600; }
.track-sub { color: var(--text-light); font-size: 11px; }

/* ── Ratings section ── */
.ratings-section {
    max-width: 1280px; margin: 0 auto;
    padding: 0 32px 40px; margin-top: -8px;
}
.ratings-card {
    background: #fff; border: 1px solid #F0F0F0;
    border-radius: var(--radius-lg); padding: 24px 28px;
}
.ratings-header {
    display: flex; align-items: flex-start;
    justify-content: space-between; margin-bottom: 20px; flex-wrap: wrap; gap: 16px;
}
.ratings-title { font-size: 18px; font-weight: 900; color: var(--navy); margin-bottom: 3px; }
.ratings-count { font-size: 13px; color: var(--text-light); }
.ratings-scores { display: flex; gap: 28px; }
.score-block { text-align: center; }
.score-num { font-size: 32px; font-weight: 900; color: var(--orange); letter-spacing: -1.5px; line-height: 1; }
.score-stars { color: var(--orange); font-size: 13px; letter-spacing: -1px; }
.score-label { font-size: 11px; color: var(--text-light); font-weight: 600; margin-top: 2px; }

/* Leave review button */
.btn-leave-review {
    width: 100%; padding: 13px;
    background: var(--orange); color: #111;
    font-size: 14px; font-weight: 800; border: none;
    border-radius: var(--radius-md); cursor: pointer;
    display: flex; align-items: center; justify-content: center; gap: 8px;
    transition: background .15s, transform .15s; margin-bottom: 20px;
}
.btn-leave-review:hover { background: var(--orange-dark); transform: translateY(-1px); }

/* Review form */
.review-form {
    background: #F9FAFB; border: 1px solid #EFEFEF;
    border-radius: var(--radius-lg); padding: 20px 22px;
    margin-bottom: 20px; display: none;
    animation: fadeDown .3s ease;
}
.review-form.open { display: block; }
@keyframes fadeDown {
    from { opacity: 0; transform: translateY(-10px); }
    to   { opacity: 1; transform: translateY(0); }
}
.review-form-title { font-size: 14px; font-weight: 800; color: var(--navy); margin-bottom: 16px; }
.review-fields-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 16px; margin-bottom: 14px; }
.review-field-label {
    font-size: 10px; font-weight: 800; color: var(--text-light);
    letter-spacing: 1px; text-transform: uppercase; margin-bottom: 8px;
}
/* Star picker */
.star-picker { display: flex; gap: 4px; }
.star-pick {
    font-size: 22px; cursor: pointer; color: #D1D5DB;
    transition: color .1s, transform .1s; line-height: 1;
}
.star-pick:hover, .star-pick.on { color: var(--orange); transform: scale(1.15); }
.review-textarea {
    width: 100%; padding: 11px 14px;
    border: 1.5px solid #E5E7EB; border-radius: var(--radius-md);
    font-size: 13px; color: var(--navy); font-family: 'Inter', sans-serif;
    resize: vertical; min-height: 100px; outline: none;
    transition: border-color .2s; background: #fff;
}
.review-textarea:focus { border-color: var(--orange); }
.review-form-actions { display: flex; gap: 10px; margin-top: 14px; }
.btn-publish-review {
    flex: 1; padding: 11px; background: var(--navy); color: #fff;
    font-size: 13px; font-weight: 800; border: none;
    border-radius: var(--radius-md); cursor: pointer;
    transition: background .15s;
}
.btn-publish-review:hover { background: #0a1421; }
.btn-cancel-review {
    padding: 11px 20px; background: #fff;
    border: 1px solid #E5E7EB; border-radius: var(--radius-md);
    font-size: 13px; font-weight: 600; color: var(--text-gray);
    cursor: pointer; transition: border-color .15s;
}
.btn-cancel-review:hover { border-color: var(--navy); color: var(--navy); }
.review-name-input {
    width: 100%; padding: 11px 14px;
    border: 1.5px solid #E5E7EB; border-radius: var(--radius-md);
    font-size: 13px; color: var(--navy); font-family: 'Inter', sans-serif;
    outline: none; transition: border-color .2s; background: #fff; margin-bottom: 14px;
}
.review-name-input:focus { border-color: var(--orange); }

/* Review items */
.review-item {
    background: #fff; border: 1px solid #F0F0F0;
    border-radius: var(--radius-lg); padding: 18px 20px;
    margin-bottom: 12px; transition: box-shadow .2s;
}
.review-item:last-child { margin-bottom: 0; }
.review-item:hover { box-shadow: var(--shadow-sm); }
.review-item-header {
    display: flex; align-items: center;
    justify-content: space-between; margin-bottom: 10px;
}
.review-user { display: flex; align-items: center; gap: 12px; }
.review-avatar {
    width: 38px; height: 38px; border-radius: 50%;
    display: flex; align-items: center; justify-content: center;
    font-size: 15px; font-weight: 800; color: #111; flex-shrink: 0;
}
.review-name { font-size: 14px; font-weight: 700; color: var(--navy); }
.review-date { font-size: 11px; color: var(--text-light); margin-top: 1px; }
.review-global-stars { color: var(--orange); font-size: 14px; letter-spacing: -1px; }
.review-sub-ratings {
    display: flex; gap: 20px; margin-bottom: 10px;
}
.sub-rating-label { font-size: 10px; color: var(--text-light); font-weight: 700; letter-spacing: .5px; text-transform: uppercase; margin-bottom: 3px; }
.sub-rating-stars { color: var(--orange); font-size: 12px; letter-spacing: -1px; }
.review-comment { font-size: 13px; color: var(--text-gray); line-height: 1.65; margin-bottom: 10px; }
.review-helpful {
    display: flex; align-items: center; gap: 6px;
    font-size: 12px; color: var(--text-light); cursor: pointer;
    border: none; background: none; padding: 0; transition: color .15s;
}
.review-helpful:hover { color: var(--navy); }
.review-helpful i { font-size: 13px; }

@media (max-width: 900px) {
    .show-body { grid-template-columns: 1fr; }
    .booking-sidebar { position: static; }
    .specs-grid { grid-template-columns: repeat(2,1fr); }
    .ratings-section { padding: 0 16px 32px; }
    .review-fields-grid { grid-template-columns: 1fr; }
    .ratings-header { flex-direction: column; }
}
</style>
@endpush

@section('content')

<div class="show-header">
    <div class="container-rentify">
        <a href="/machines" class="back-link">
            <i class="fas fa-arrow-left"></i> Retour au catalogue
        </a>
    </div>
</div>

<div class="show-body" id="machine-detail">
    {{-- Loading state --}}
    <div style="grid-column:1/-1;text-align:center;padding:60px;color:var(--text-light)">
        <div style="font-size:36px;margin-bottom:12px;animation:spin 1s linear infinite;display:inline-block">⚙️</div>
        <div style="font-size:14px">Chargement de la fiche machine...</div>
    </div>
</div>

{{-- ══ RATINGS SECTION ══ --}}
<div class="ratings-section" id="ratings-section" style="display:none">
    <div class="ratings-card">
        <div class="ratings-header">
            <div>
                <div class="ratings-title">Avis &amp; Évaluations</div>
                <div class="ratings-count" id="ratings-count">0 avis vérifiés</div>
            </div>
            <div class="ratings-scores" id="ratings-scores"></div>
        </div>

        {{-- Button to open form --}}
        <button class="btn-leave-review" id="btn-leave-review" onclick="toggleReviewForm()">
            <i class="far fa-star"></i> Laisser un avis
        </button>

        {{-- Review form (hidden by default) --}}
        <div class="review-form" id="review-form">
            <div class="review-form-title" id="review-form-title">Votre avis sur la machine</div>

            <input type="text" class="review-name-input" id="review-name"
                   placeholder="Ex: Mohammed Alami">

            <div class="review-fields-grid">
                <div>
                    <div class="review-field-label">Note Propriétaire</div>
                    <div class="star-picker" id="stars-owner">
                        <span class="star-pick" onclick="setStars('owner',1)" onmouseover="hoverStars('owner',1)" onmouseout="resetStarsHover('owner')">★</span>
                        <span class="star-pick" onclick="setStars('owner',2)" onmouseover="hoverStars('owner',2)" onmouseout="resetStarsHover('owner')">★</span>
                        <span class="star-pick" onclick="setStars('owner',3)" onmouseover="hoverStars('owner',3)" onmouseout="resetStarsHover('owner')">★</span>
                        <span class="star-pick" onclick="setStars('owner',4)" onmouseover="hoverStars('owner',4)" onmouseout="resetStarsHover('owner')">★</span>
                        <span class="star-pick" onclick="setStars('owner',5)" onmouseover="hoverStars('owner',5)" onmouseout="resetStarsHover('owner')">★</span>
                    </div>
                </div>
                <div>
                    <div class="review-field-label">Note Matériel</div>
                    <div class="star-picker" id="stars-machine">
                        <span class="star-pick" onclick="setStars('machine',1)" onmouseover="hoverStars('machine',1)" onmouseout="resetStarsHover('machine')">★</span>
                        <span class="star-pick" onclick="setStars('machine',2)" onmouseover="hoverStars('machine',2)" onmouseout="resetStarsHover('machine')">★</span>
                        <span class="star-pick" onclick="setStars('machine',3)" onmouseover="hoverStars('machine',3)" onmouseout="resetStarsHover('machine')">★</span>
                        <span class="star-pick" onclick="setStars('machine',4)" onmouseover="hoverStars('machine',4)" onmouseout="resetStarsHover('machine')">★</span>
                        <span class="star-pick" onclick="setStars('machine',5)" onmouseover="hoverStars('machine',5)" onmouseout="resetStarsHover('machine')">★</span>
                    </div>
                </div>
            </div>

            <div class="review-field-label">Commentaire</div>
            <textarea class="review-textarea" id="review-comment"
                      placeholder="Décrivez votre expérience avec ce matériel et le propriétaire..."></textarea>

            <div class="review-form-actions">
                <button class="btn-publish-review" onclick="submitReview()">Publier l'avis</button>
                <button class="btn-cancel-review" onclick="toggleReviewForm()">Annuler</button>
            </div>
        </div>

        {{-- Reviews list --}}
        <div id="reviews-list"></div>
    </div>
</div>

@endsection

@push('scripts')
<script>
const machineId = {{ $id ?? 1 }};
let currentMode = 'jour';
let duration = 1;
let machineData = null;

async function loadMachine() {
    try {
        machineData = await API.get(`/api/machines/${machineId}`);
        renderMachine(machineData);
    } catch(e) {
        renderDemoMachine();
    }
}

function renderDemoMachine() {
    machineData = {
        id: machineId, type: 'Excavatrice', marque: 'JCB', annee: 2022,
        name: 'JCB 3CX Backhoe Loader', location: 'Casablanca',
        status: 'available', ratings_avg: 4.9, ratings_count: 28,
        price_per_day: 2400, price_per_hour: 350,
        description: 'Chargeuse-pelleteuse JCB 3CX en excellent état, entretenue régulièrement. Idéale pour les travaux de terrassement, excavation et chargement. Opérateur disponible sur demande.',
        owner: { name: 'Ahmed Benali', phone: '+212600000001', verified: true, rating: 4.9, total_rentals: 44 },
        images: []
    };
    renderMachine(machineData);
}

function renderMachine(m) {
    const dispo = m.status === 'available';
    const emojis = { Excavatrice:'🏗', Camion:'🚛', Grue:'🏙', Manitou:'🔧', Compacteur:'⚙️', Bulldozer:'🚧', Niveleuse:'🚜' };
    const emoji = emojis[m.type] || '🏗';
    const ownerInitial = (m.owner?.name || 'P').charAt(0).toUpperCase();

    document.getElementById('machine-detail').innerHTML = `
    <!-- LEFT COLUMN -->
    <div>
        <!-- Gallery -->
        <div class="gallery-main" id="gallery-main">
            <div class="gallery-placeholder">${emoji}</div>
            <div class="gallery-nav">
                <button class="gallery-btn" onclick="prevPhoto()"><i class="fas fa-chevron-left"></i></button>
                <button class="gallery-btn" onclick="nextPhoto()"><i class="fas fa-chevron-right"></i></button>
            </div>
        </div>
        <div class="gallery-thumbs" id="gallery-thumbs">
            <div class="gallery-thumb active" onclick="setPhoto(0)">${emoji}</div>
            <div class="gallery-thumb" onclick="setPhoto(1)">${emoji}</div>
            <div class="gallery-thumb" onclick="setPhoto(2)">${emoji}</div>
        </div>

        <!-- Info -->
        <div class="machine-info">
            <div class="info-badge">${m.type || 'Machine'}</div>
            <h1 class="machine-title">${m.name}</h1>
            <div class="machine-meta">
                <div class="meta-rating">
                    <i class="fas fa-star"></i>
                    <span>${m.ratings_avg || '—'}</span>
                    <small>(${m.ratings_count || 0} avis)</small>
                </div>
                <div class="meta-dispo">
                    <div class="dispo-dot" style="background:${dispo ? '#10B981' : '#EF4444'}"></div>
                    <span style="color:${dispo ? '#10B981' : '#EF4444'};font-size:12px;font-weight:700">${dispo ? 'Disponible' : 'Indisponible'}</span>
                </div>
                <div style="font-size:12px;color:var(--text-gray);display:flex;align-items:center;gap:4px">
                    <i class="fas fa-map-marker-alt" style="color:var(--orange);font-size:11px"></i>
                    ${m.location || '—'}
                </div>
            </div>
            <p class="machine-desc">${m.description || 'Aucune description disponible.'}</p>

            <!-- Specs -->
            <div style="font-size:12px;font-weight:800;color:var(--navy);letter-spacing:-.2px;margin-bottom:12px;padding:16px 18px;background:#F9FAFB;border-radius:var(--radius-md);border:1px solid #F0F0F0">
                <div style="font-size:11px;font-weight:800;color:var(--text-light);letter-spacing:1px;text-transform:uppercase;margin-bottom:12px">Spécifications techniques</div>
                <div class="specs-grid" style="margin-bottom:0">
                    <div class="spec-card"><div class="spec-icon"><i class="fas fa-bolt"></i></div><div class="spec-label">Puissance</div><div class="spec-value">92 ch</div></div>
                    <div class="spec-card"><div class="spec-icon"><i class="fas fa-gas-pump"></i></div><div class="spec-label">Carburant</div><div class="spec-value">Diesel</div></div>
                    <div class="spec-card"><div class="spec-icon"><i class="fas fa-weight-hanging"></i></div><div class="spec-label">Poids</div><div class="spec-value">8,2 tonnes</div></div>
                    <div class="spec-card"><div class="spec-icon"><i class="fas fa-calendar-alt"></i></div><div class="spec-label">Année</div><div class="spec-value">${m.annee || '—'}</div></div>
                </div>
            </div>

            <!-- Equipements -->
            <div style="background:#F9FAFB;border:1px solid #F0F0F0;border-radius:var(--radius-md);padding:16px 18px;margin-top:12px">
                <div style="font-size:11px;font-weight:800;color:var(--text-light);letter-spacing:1px;text-transform:uppercase;margin-bottom:12px">Équipements inclus</div>
                <div class="equip-grid">
                    <div class="equip-item"><i class="fas fa-check-circle"></i>GPS intégré</div>
                    <div class="equip-item"><i class="fas fa-check-circle"></i>Climatisation cabine</div>
                    <div class="equip-item"><i class="fas fa-check-circle"></i>Godet standard + godet curage</div>
                    <div class="equip-item"><i class="fas fa-check-circle"></i>Manuel d'utilisation FR</div>
                    <div class="equip-item"><i class="fas fa-check-circle"></i>Certifié CE</div>
                </div>
            </div>

            <!-- Availability calendar -->
            <div class="availability-cal">
                <div class="cal-header">
                    <div class="cal-title">Calendrier de disponibilité</div>
                    <div class="cal-nav">
                        <button class="cal-nav-btn"><i class="fas fa-chevron-left"></i></button>
                        <span style="font-size:12px;font-weight:700;color:var(--navy);align-self:center">Mai 2025</span>
                        <button class="cal-nav-btn"><i class="fas fa-chevron-right"></i></button>
                    </div>
                </div>
                <div class="cal-days-header">
                    ${['Dim','Lun','Mar','Mer','Jeu','Ven','Sam'].map(d=>`<div class="cal-day-hdr">${d}</div>`).join('')}
                </div>
                <div class="cal-days" id="calendar-grid"></div>
                <div class="cal-legend">
                    <div class="cal-legend-item"><div class="leg-dot" style="background:#D1FAE5"></div>Disponible</div>
                    <div class="cal-legend-item"><div class="leg-dot" style="background:#FEE2E2"></div>Réservé</div>
                </div>
            </div>
        </div>
    </div>

    <!-- RIGHT COLUMN — Booking sidebar -->
    <div class="booking-sidebar">
        <div class="booking-price-header">
            <div class="booking-price-main" id="sidebar-price">${parseInt(m.price_per_day).toLocaleString('fr')} <span>dh</span></div>
            <div class="booking-price-sub">par jour · ${parseInt(m.price_per_hour).toLocaleString('fr')} dh/heure · ${(parseInt(m.price_per_day)*7).toLocaleString('fr')} dh/semaine</div>
        </div>
        <div class="booking-body">

            <!-- Mode -->
            <div class="booking-label">Mode de location</div>
            <div class="mode-tabs">
                <div class="mode-tab" onclick="setMode('heure',this)">Heure</div>
                <div class="mode-tab active" onclick="setMode('jour',this)">Jour</div>
                <div class="mode-tab" onclick="setMode('semaine',this)">Semaine</div>
            </div>

            <!-- Date -->
            <div class="booking-label">Date de début</div>
            <input type="date" class="booking-date-input" id="booking-date"
                   min="${new Date().toISOString().split('T')[0]}"
                   onchange="updateTotal()">

            <!-- Duration stepper -->
            <div class="booking-label">Durée (<span id="mode-label">jour(s)</span>)</div>
            <div class="duration-stepper">
                <button class="step-btn" onclick="changeDur(-1)">−</button>
                <span class="step-val" id="dur-val">1</span>
                <button class="step-btn" onclick="changeDur(1)">+</button>
            </div>

            <!-- Price breakdown -->
            <div class="price-breakdown" id="price-breakdown">
                <div class="pb-row"><span id="pb-formula">${parseInt(m.price_per_day).toLocaleString('fr')} dh × 1 jour(s)</span><span id="pb-base">${parseInt(m.price_per_day).toLocaleString('fr')} dh</span></div>
                <div class="pb-row"><span>Frais de service (5%)</span><span id="pb-fee">${Math.round(m.price_per_day*.05).toLocaleString('fr')} dh</span></div>
                <div class="pb-row"><strong>Total estimé</strong><span id="pb-total">${Math.round(m.price_per_day*1.05).toLocaleString('fr')} dh</span></div>
            </div>

            <!-- CTA -->
            <button class="btn-orange w-100 mb-2" style="width:100%;justify-content:center;font-size:14px;padding:13px" onclick="sendDemande()">
                <i class="fas fa-paper-plane"></i> Envoyer une demande de location
            </button>
            <button class="btn-outline-orange" style="width:100%;justify-content:center;font-size:13px;padding:10px">
                ⚡ Devis rapide
            </button>
            <div style="text-align:center;margin-top:8px;font-size:11px;color:var(--text-light)">
                <i class="fas fa-lock" style="font-size:9px"></i> Paiement sécurisé · Annulation gratuite 48h avant
            </div>

            <!-- Owner -->
            <div style="border-top:1px solid #F0F0F0;margin:16px 0 14px"></div>
            <div class="booking-label">Propriétaire</div>
            <div class="owner-card">
                <div class="owner-avatar">${ownerInitial}</div>
                <div style="flex:1">
                    <div class="owner-name">${m.owner?.name || 'Propriétaire'}</div>
                    <div class="owner-meta">
                        <span class="owner-badge"><i class="fas fa-check-circle"></i> Vérifié</span>
                        &nbsp;·&nbsp; ⭐ ${m.owner?.rating || '—'}
                        &nbsp;·&nbsp; ${m.owner?.total_rentals || 0} loc.
                    </div>
                </div>
            </div>
            <div class="owner-actions">
                <a href="https://wa.me/${m.owner?.phone || '+212600000000'}?text=Bonjour, je suis intéressé par votre machine : ${encodeURIComponent(m.name)}"
                   target="_blank" class="btn-whatsapp">
                    <i class="fab fa-whatsapp"></i> WhatsApp
                </a>
                <a href="tel:${m.owner?.phone || '+212600000000'}" class="btn-call">
                    <i class="fas fa-phone"></i> Appeler
                </a>
            </div>

            <!-- Tracking -->
            <div class="tracking">
                <div class="tracking-title">Suivi de la réservation</div>
                <div class="track-item"><div class="track-dot track-done"><i class="fas fa-check" style="font-size:8px"></i></div><div><div class="track-label">Demande envoyée</div></div></div>
                <div class="track-item"><div class="track-dot track-active">2</div><div><div class="track-label">Confirmée par propriétaire</div><div class="track-sub">En cours...</div></div></div>
                <div class="track-item"><div class="track-dot track-pending">3</div><div><div class="track-label" style="color:var(--text-light)">Machine en route</div></div></div>
                <div class="track-item"><div class="track-dot track-pending">4</div><div><div class="track-label" style="color:var(--text-light)">Location démarrée</div></div></div>
            </div>
        </div>
    </div>`;

    buildCalendar();
    renderRatings(m.ratings || getDemoRatings());
}

/* ── Mode & duration ── */
const prices = { heure: 0, jour: 0, semaine: 0 };
function setMode(mode, btn) {
    currentMode = mode;
    document.querySelectorAll('.mode-tab').forEach(t => t.classList.remove('active'));
    btn.classList.add('active');
    const labels = { heure: 'heure(s)', jour: 'jour(s)', semaine: 'semaine(s)' };
    document.getElementById('mode-label').textContent = labels[mode];
    updateTotal();
}
function changeDur(delta) {
    duration = Math.max(1, duration + delta);
    document.getElementById('dur-val').textContent = duration;
    updateTotal();
}
function updateTotal() {
    if (!machineData) return;
    const rates = { heure: machineData.price_per_hour, jour: machineData.price_per_day, semaine: machineData.price_per_day * 7 };
    const rate = rates[currentMode] || machineData.price_per_day;
    const base = rate * duration;
    const fee  = Math.round(base * .05);
    const total = base + fee;
    const labels = { heure: 'heure(s)', jour: 'jour(s)', semaine: 'semaine(s)' };

    const fmt = n => parseInt(n).toLocaleString('fr');
    document.getElementById('pb-formula').textContent = `${fmt(rate)} dh × ${duration} ${labels[currentMode]}`;
    document.getElementById('pb-base').textContent    = fmt(base) + ' dh';
    document.getElementById('pb-fee').textContent     = fmt(fee) + ' dh';
    document.getElementById('pb-total').textContent   = fmt(total) + ' dh';
}

/* ── Calendar ── */
function buildCalendar() {
    const grid = document.getElementById('calendar-grid');
    if (!grid) return;
    const reserved = [3,4,8,15,16,17,25,26];
    const firstDay = 3; // May 2025 starts on Thursday
    let html = Array(firstDay).fill('<div class="cal-day empty"></div>').join('');
    for (let d = 1; d <= 31; d++) {
        const cls = reserved.includes(d) ? 'reserved' : 'available';
        html += `<div class="cal-day ${cls}">${d}</div>`;
    }
    grid.innerHTML = html;
}

/* ── Gallery ── */
let photoIdx = 0;
function setPhoto(i) {
    photoIdx = i;
    document.querySelectorAll('.gallery-thumb').forEach((t,j) => t.classList.toggle('active', j===i));
}
function nextPhoto() { setPhoto((photoIdx+1)%3); }
function prevPhoto() { setPhoto((photoIdx+2)%3); }

/* ── Send demande ── */
function sendDemande() {
    if (!getToken()) { window.location.href = '/login'; return; }
    const date = document.getElementById('booking-date').value;
    if (!date) { showFlash('Veuillez sélectionner une date de début', 'warning'); return; }
    showFlash('Demande envoyée au propriétaire ! Il vous répondra sous 24h.', 'success');
}

/* ── Ratings ── */
let starsOwner = 0, starsMachine = 0;
const avatarColors = ['#F59E0B','#3B82F6','#10B981','#8B5CF6','#EF4444','#EC4899'];

function getDemoRatings() {
    return [
        { name:'Karim Azzouzi',   date:'15 Avril 2026',   owner:5, machine:5, avg:5,   comment:"Matériel en parfait état, livraison ponctuelle. Propriétaire très professionnel. Je recommande vivement !", helpful:4 },
        { name:'Youssef El Amrani',date:'3 Mars 2026',    owner:3, machine:5, avg:4,   comment:"Bonne machine, bien entretenue. Quelques petits soucis de communication au début mais ça s'est arrangé rapidement.", helpful:2 },
        { name:'Hassan Tazi',      date:'18 Février 2026', owner:5, machine:4, avg:5,   comment:"Excellent rapport qualité/prix. La JCB était propre et performante. Opérateur compétent inclus dans le prix.", helpful:6 },
    ];
}

function renderRatings(ratings) {
    document.getElementById('ratings-section').style.display = 'block';

    // Update form title
    if (machineData) document.getElementById('review-form-title').textContent = `Votre avis sur ${machineData.name || 'cette machine'}`;

    // Scores
    const avgOwner   = ratings.reduce((s,r) => s + (r.owner||0), 0) / (ratings.length||1);
    const avgMachine = ratings.reduce((s,r) => s + (r.machine||0), 0) / (ratings.length||1);
    document.getElementById('ratings-count').textContent = `${ratings.length} avis vérifiés`;
    document.getElementById('ratings-scores').innerHTML = `
        <div class="score-block">
            <div class="score-num">${avgOwner.toFixed(1)}</div>
            <div class="score-stars">${starsHtml(avgOwner)}</div>
            <div class="score-label">Propriétaire</div>
        </div>
        <div class="score-block">
            <div class="score-num">${avgMachine.toFixed(1)}</div>
            <div class="score-stars">${starsHtml(avgMachine)}</div>
            <div class="score-label">Matériel</div>
        </div>`;

    // Reviews
    document.getElementById('reviews-list').innerHTML = ratings.map((r, i) => {
        const initial = (r.name || 'U').charAt(0).toUpperCase();
        const bgColor = avatarColors[i % avatarColors.length];
        return `
        <div class="review-item">
            <div class="review-item-header">
                <div class="review-user">
                    <div class="review-avatar" style="background:${bgColor}">${initial}</div>
                    <div>
                        <div class="review-name">${r.name}</div>
                        <div class="review-date">${r.date}</div>
                    </div>
                </div>
                <div class="review-global-stars">${starsHtml(r.avg)}</div>
            </div>
            <div class="review-sub-ratings">
                <div>
                    <div class="sub-rating-label">Propriétaire</div>
                    <div class="sub-rating-stars">${starsHtml(r.owner)}</div>
                </div>
                <div>
                    <div class="sub-rating-label">Matériel</div>
                    <div class="sub-rating-stars">${starsHtml(r.machine)}</div>
                </div>
            </div>
            <div class="review-comment">${r.comment}</div>
            <button class="review-helpful" onclick="markHelpful(this, ${i})">
                <i class="far fa-thumbs-up"></i> Utile (${r.helpful || 0})
            </button>
        </div>`;
    }).join('');
}

function starsHtml(rating) {
    let html = '';
    for (let i = 1; i <= 5; i++) {
        html += i <= Math.round(rating) ? '★' : '☆';
    }
    return html;
}

function toggleReviewForm() {
    const form = document.getElementById('review-form');
    form.classList.toggle('open');
    const isOpen = form.classList.contains('open');
    document.getElementById('btn-leave-review').style.display = isOpen ? 'none' : 'flex';
}

function setStars(type, val) {
    if (type === 'owner')   starsOwner   = val;
    if (type === 'machine') starsMachine = val;
    updateStarDisplay(type, val);
}
function hoverStars(type, val) { updateStarDisplay(type, val, true); }
function resetStarsHover(type) {
    const val = type === 'owner' ? starsOwner : starsMachine;
    updateStarDisplay(type, val);
}
function updateStarDisplay(type, val, isHover = false) {
    const id = type === 'owner' ? 'stars-owner' : 'stars-machine';
    document.querySelectorAll(`#${id} .star-pick`).forEach((s, i) => {
        s.classList.toggle('on', i < val);
    });
}

function markHelpful(btn, idx) {
    btn.innerHTML = `<i class="fas fa-thumbs-up"></i> Utile ✓`;
    btn.style.color = 'var(--orange)';
    btn.disabled = true;
}

async function submitReview() {
    const name    = document.getElementById('review-name').value.trim();
    const comment = document.getElementById('review-comment').value.trim();

    if (!name)    { showFlash('Veuillez entrer votre nom', 'warning'); return; }
    if (!starsOwner || !starsMachine) { showFlash('Veuillez noter le propriétaire et le matériel', 'warning'); return; }
    if (!comment) { showFlash('Veuillez écrire un commentaire', 'warning'); return; }

    try {
        await API.post(`/api/machines/${machineId}/ratings`, {
            rating: Math.round((starsOwner + starsMachine) / 2),
            comment
        });
    } catch(e) { /* demo mode */ }

    // Add locally
    const newReview = {
        name, date: new Date().toLocaleDateString('fr-FR', { day:'numeric', month:'long', year:'numeric' }),
        owner: starsOwner, machine: starsMachine,
        avg: Math.round((starsOwner + starsMachine) / 2),
        comment, helpful: 0
    };
    const existing = getDemoRatings();
    renderRatings([newReview, ...existing]);
    toggleReviewForm();
    starsOwner = 0; starsMachine = 0;
    document.getElementById('review-name').value = '';
    document.getElementById('review-comment').value = '';
    showFlash('Votre avis a été publié ! Merci 🙏', 'success');
}

loadMachine();
</script>
@endpush