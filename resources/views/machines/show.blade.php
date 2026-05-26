@extends('layouts.app')
@section('title', 'Détail machine — Rentify')

@push('styles')
<style>
/* ═══════════════════════════════════════════════
   VARIABLES & BASE
═══════════════════════════════════════════════ */
:root {
  --gold:#D4AF37; --gold-dk:#9A7D20; --gold-lt:#F5E88A;
  --gold-pale:#FEF9E7; --gold-glow:rgba(212,175,55,.25);
  --navy:#0F1B2D; --navy2:#162540; --navy3:#1E3356;
  --cream:#FAF7F0; --cream2:#F0EBE0; --cream3:#E8DDD0;
  --txt-dark:#1a1a2e; --txt-mid:#5a5660; --txt-light:#9992a4;
  --green:#10b981; --red:#ef4444;
  --gray:#6b7280; --border:#e2e8f0;
  --radius:16px; --shadow:0 4px 24px rgba(15,27,45,.10);
}
* { box-sizing: border-box; margin: 0; padding: 0; }

.show-wrap {
  max-width: 1280px; margin: 0 auto;
  padding: 28px 24px; display: grid;
  grid-template-columns: 1fr 380px;
  gap: 28px; align-items: start;
}

/* ═══════════════════════════════════════════════
   GALERIE IMAGES — PRINCIPALE
═══════════════════════════════════════════════ */
.gallery-section {
  background: #fff;
  border-radius: var(--radius);
  box-shadow: var(--shadow);
  overflow: hidden;
}

.gallery-main {
  position: relative;
  height: 420px;
  background: var(--cream2);
  overflow: hidden;
  cursor: zoom-in;
}
.gallery-main-img {
  width: 100%; height: 100%;
  object-fit: cover;
  transition: transform .4s ease;
  display: block;
}
.gallery-main:hover .gallery-main-img {
  transform: scale(1.03);
}

.gallery-status-badge {
  position: absolute; top: 16px; left: 16px;
  padding: 5px 14px; border-radius: 100px;
  font-size: 11px; font-weight: 800; letter-spacing: 1px;
  text-transform: uppercase; z-index: 4;
  backdrop-filter: blur(8px);
}
.status-available   { background: rgba(16,185,129,.18);  color: #10b981; border: 1px solid rgba(16,185,129,.4); }
.status-rented      { background: rgba(212,175,55,.18);  color: var(--gold); border: 1px solid rgba(212,175,55,.4); }
.status-maintenance { background: rgba(239,68,68,.18);   color: #ef4444; border: 1px solid rgba(239,68,68,.4); }
.status-unavailable { background: rgba(107,114,128,.18); color: #6b7280; border: 1px solid rgba(107,114,128,.4); }

.gallery-nav {
  position: absolute; top: 50%; transform: translateY(-50%);
  width: 38px; height: 38px; border-radius: 50%;
  background: rgba(15,27,45,.65); border: 1px solid rgba(212,175,55,.3);
  color: var(--gold); font-size: 16px; cursor: pointer;
  display: flex; align-items: center; justify-content: center;
  transition: all .2s; z-index: 5; backdrop-filter: blur(6px);
}
.gallery-nav:hover { background: var(--gold); color: var(--navy); }
.gallery-nav.prev { left: 14px; }
.gallery-nav.next { right: 14px; }

.gallery-counter {
  position: absolute; bottom: 14px; right: 14px;
  background: rgba(15,27,45,.72); backdrop-filter: blur(6px);
  border: .5px solid rgba(212,175,55,.25); border-radius: 8px;
  padding: 4px 10px; font-size: 11px; color: rgba(255,255,255,.75);
  font-weight: 600; z-index: 4;
}

.gallery-3d-btn {
  position: absolute; bottom: 14px; left: 14px;
  background: rgba(212,175,55,.15); backdrop-filter: blur(8px);
  border: 1px solid rgba(212,175,55,.4); border-radius: 9px;
  color: var(--gold); font-size: 12px; font-weight: 700;
  padding: 6px 14px; cursor: pointer; z-index: 5;
  transition: all .2s; display: flex; align-items: center; gap: 6px;
}
.gallery-3d-btn:hover { background: rgba(212,175,55,.3); }

.gallery-thumbs {
  display: flex; gap: 8px; padding: 12px;
  overflow-x: auto; background: var(--cream);
  scrollbar-width: thin; scrollbar-color: var(--gold-glow) transparent;
}
.gallery-thumbs::-webkit-scrollbar { height: 4px; }
.gallery-thumbs::-webkit-scrollbar-thumb { background: var(--gold-glow); border-radius: 2px; }

.gallery-thumb {
  flex-shrink: 0; width: 72px; height: 56px;
  border-radius: 8px; overflow: hidden;
  cursor: pointer; border: 2px solid transparent;
  transition: all .2s; opacity: .65;
}
.gallery-thumb.active { border-color: var(--gold); opacity: 1; }
.gallery-thumb:hover  { opacity: .9; }
.gallery-thumb img {
  width: 100%; height: 100%; object-fit: cover; display: block;
}

.lightbox-overlay {
  display: none; position: fixed; inset: 0; z-index: 9998;
  background: rgba(7,16,24,.96); backdrop-filter: blur(4px);
  align-items: center; justify-content: center;
}
.lightbox-overlay.active { display: flex; }
.lightbox-img {
  max-width: 90vw; max-height: 88vh;
  border-radius: 12px; object-fit: contain;
  box-shadow: 0 20px 60px rgba(0,0,0,.5);
}
.lightbox-close {
  position: absolute; top: 20px; right: 24px;
  background: rgba(255,255,255,.1); border: none; color: #fff;
  width: 40px; height: 40px; border-radius: 50%;
  font-size: 20px; cursor: pointer; display: flex; align-items: center; justify-content: center;
}
.lightbox-nav {
  position: absolute; top: 50%; transform: translateY(-50%);
  width: 44px; height: 44px; border-radius: 50%;
  background: rgba(212,175,55,.2); border: 1px solid rgba(212,175,55,.4);
  color: var(--gold); font-size: 18px; cursor: pointer;
  display: flex; align-items: center; justify-content: center;
  transition: all .2s;
}
.lightbox-nav:hover { background: var(--gold); color: var(--navy); }
.lightbox-nav.prev { left: 24px; }
.lightbox-nav.next { right: 24px; }

/* ═══════════════════════════════════════════════
   MINI 3D VIEWER
═══════════════════════════════════════════════ */
.mini3d-card {
  background: #071018; border-radius: var(--radius);
  box-shadow: var(--shadow); overflow: hidden;
  margin-top: 16px;
}
.mini3d-header {
  padding: 10px 14px;
  background: rgba(212,175,55,.08);
  border-bottom: 1px solid rgba(212,175,55,.15);
  display: flex; align-items: center; justify-content: space-between;
}
.mini3d-title {
  font-size: 11px; font-weight: 700; color: var(--gold);
  letter-spacing: .06em; text-transform: uppercase;
  display: flex; align-items: center; gap: 6px;
}
.mini3d-wrap {
  position: relative; height: 200px;
  overflow: hidden; user-select: none;
}
@keyframes v3dspin { to { transform: rotate(360deg); } }
.mini3d-controls {
  display: flex; gap: 6px; padding: 8px 10px;
  background: rgba(0,0,0,.3); border-top: 1px solid rgba(212,175,55,.1);
}
.mini3d-btn {
  flex: 1; padding: 6px 8px; border-radius: 7px;
  font-size: 11px; font-weight: 700; cursor: pointer;
  border: none; transition: all .2s;
}
.mini3d-btn.primary { background: var(--gold); color: var(--navy); }
.mini3d-btn.primary:hover { background: var(--gold-dk); color: #fff; }
.mini3d-btn.secondary {
  background: rgba(212,175,55,.1); color: var(--gold);
  border: .5px solid rgba(212,175,55,.3);
}
.mini3d-btn.secondary:hover { background: rgba(212,175,55,.2); }
.mini3d-hint {
  font-size: 9px; color: rgba(255,255,255,.3);
  text-align: center; padding: 4px 0 6px;
  letter-spacing: .04em;
}

/* ═══════════════════════════════════════════════
   INFOS MACHINE
═══════════════════════════════════════════════ */
.machine-info { padding: 24px; background:#fff; border-radius:0 0 var(--radius) var(--radius); }
.machine-badges { display: flex; gap: 8px; flex-wrap: wrap; margin-bottom: 12px; }
.badge-type {
  background: var(--gold-pale); color: var(--gold-dk);
  font-size: 10px; font-weight: 800; padding: 4px 12px;
  border-radius: 100px; letter-spacing: 1px; text-transform: uppercase;
}
.badge-city {
  background: #f1f5f9; color: var(--gray);
  font-size: 11px; font-weight: 600; padding: 4px 12px;
  border-radius: 100px; display: flex; align-items: center; gap: 4px;
}
.machine-title {
  font-size: 26px; font-weight: 900; color: var(--txt-dark);
  letter-spacing: -.5px; line-height: 1.2; margin-bottom: 8px;
}
.machine-rating { display: flex; align-items: center; gap: 8px; margin-bottom: 16px; }
.stars { color: var(--gold); font-size: 14px; }
.rating-val { font-weight: 800; color: var(--txt-dark); font-size: 14px; }
.rating-count { color: var(--gray); font-size: 13px; }
.machine-desc {
  color: var(--gray); font-size: 14px; line-height: 1.7;
  margin-bottom: 20px; padding-bottom: 20px;
  border-bottom: 1px solid var(--border);
}

/* Specs */
.specs-grid {
  display: grid; grid-template-columns: repeat(4, 1fr);
  gap: 12px; margin-bottom: 20px;
}
.spec-card { background: #f8fafc; border-radius: 12px; padding: 14px 12px; text-align: center; }
.spec-icon { font-size: 20px; margin-bottom: 6px; }
.spec-val  { font-size: 13px; font-weight: 800; color: var(--txt-dark); }
.spec-label { font-size: 10px; color: var(--gray); text-transform: uppercase; letter-spacing: .5px; margin-top: 2px; }

/* ✅ FIX: price-section = gold-pale (plus navy background) */
.price-section {
  display: flex; align-items: center; gap: 20px;
  padding: 16px;
  background: var(--gold-pale);
  border: 1.5px solid var(--gold);
  border-radius: 14px; margin-bottom: 20px;
}
.price-item { text-align: center; flex: 1; }
.price-label { font-size: 10px; color: var(--txt-mid); text-transform: uppercase; letter-spacing: .8px; margin-bottom: 4px; }
.price-val   { font-size: 22px; font-weight: 900; color: var(--gold-dk); }
.price-unit  { font-size: 11px; color: var(--txt-light); margin-top: 2px; }
.price-sep   { width: 1px; height: 40px; background: rgba(212,175,55,.3); }

/* ═══════════════════════════════════════════════
   BOOKING SIDEBAR
═══════════════════════════════════════════════ */
.booking-card {
  background: #fff; border-radius: var(--radius);
  box-shadow: var(--shadow); overflow: hidden;
  position: sticky; top: 90px;
}
/* ✅ FIX: booking-header = gold-pale (plus navy gradient) */
.booking-header {
  background: var(--gold-pale);
  border-bottom: 2px solid var(--gold);
  padding: 20px 22px;
}
.booking-price-main { font-size: 28px; font-weight: 900; color: var(--txt-dark); }
.booking-price-sub  { font-size: 12px; color: var(--txt-mid); margin-top: 2px; }
.booking-body { padding: 20px 22px; }
.form-group { margin-bottom: 16px; }
.form-label-custom {
  display: block; font-size: 11px; font-weight: 700;
  color: var(--gray); text-transform: uppercase; letter-spacing: .8px; margin-bottom: 6px;
}
.form-control-custom {
  width: 100%; padding: 10px 14px; border-radius: 10px;
  border: 1.5px solid var(--border); font-size: 14px;
  color: var(--txt-dark); background: #fff; outline: none; transition: border-color .2s;
}
.form-control-custom:focus { border-color: var(--gold); }
.mode-toggle {
  display: grid; grid-template-columns: 1fr 1fr;
  gap: 6px; background: #f1f5f9; border-radius: 10px; padding: 4px; margin-bottom: 16px;
}
.mode-btn {
  padding: 8px; border: none; border-radius: 8px;
  font-size: 12px; font-weight: 700; cursor: pointer;
  background: transparent; color: var(--gray); transition: all .2s;
}
.mode-btn.active { background: #fff; color: var(--txt-dark); box-shadow: 0 1px 4px rgba(0,0,0,.1); }
.duration-ctrl {
  display: flex; align-items: center;
  border: 1.5px solid var(--border); border-radius: 10px; overflow: hidden;
}
.duration-btn {
  width: 42px; height: 42px; border: none; background: #f8fafc;
  font-size: 18px; cursor: pointer; color: var(--txt-dark);
  transition: background .2s; flex-shrink: 0;
}
.duration-btn:hover { background: var(--gold); color: var(--navy); }
.duration-input {
  flex: 1; text-align: center; border: none; outline: none;
  font-size: 16px; font-weight: 800; color: var(--txt-dark); background: white;
}
.price-recap { background: #f8fafc; border-radius: 12px; padding: 14px; margin-bottom: 16px; }
.price-line {
  display: flex; justify-content: space-between;
  font-size: 13px; color: var(--gray); margin-bottom: 8px;
}
.price-line.total {
  font-size: 15px; font-weight: 800; color: var(--txt-dark);
  padding-top: 8px; border-top: 1px solid var(--border); margin-bottom: 0;
}
.price-line.total span:last-child { color: var(--gold-dk); }

/* ✅ FIX: btn-reserver = gold */
.btn-reserver {
  width: 100%; padding: 14px; border-radius: 12px;
  background: var(--gold); border: none; color: var(--txt-dark);
  font-size: 15px; font-weight: 800; cursor: pointer;
  transition: all .2s; margin-bottom: 10px;
  display: flex; align-items: center; justify-content: center; gap: 8px;
}
.btn-reserver:hover { background: var(--gold-dk); color: #fff; transform: translateY(-1px); box-shadow: 0 6px 20px rgba(212,175,55,.4); }
.btn-devis {
  width: 100%; padding: 12px; border-radius: 12px;
  background: transparent; border: 1.5px solid var(--border);
  color: var(--txt-dark); font-size: 14px; font-weight: 700;
  cursor: pointer; transition: all .2s;
  display: flex; align-items: center; justify-content: center; gap: 8px;
}
.btn-devis:hover { border-color: var(--gold); background: var(--gold-pale); }
.owner-section { border-top: 1px solid var(--border); padding: 16px 22px; }
.owner-row { display: flex; align-items: center; gap: 12px; margin-bottom: 12px; }
.owner-avatar {
  width: 44px; height: 44px; border-radius: 50%;
  background: var(--gold); display: flex; align-items: center;
  justify-content: center; font-size: 16px; font-weight: 800; color: var(--txt-dark); flex-shrink: 0;
}
.owner-name  { font-size: 14px; font-weight: 800; color: var(--txt-dark); }
.owner-badge { font-size: 11px; color: var(--green); font-weight: 600; }
.owner-actions { display: grid; grid-template-columns: 1fr 1fr; gap: 8px; }
.btn-whatsapp {
  padding: 10px; border-radius: 10px; border: none;
  background: #25D366; color: white; font-size: 13px; font-weight: 700;
  cursor: pointer; display: flex; align-items: center; justify-content: center; gap: 6px;
  transition: opacity .2s;
}
.btn-whatsapp:hover { opacity: .85; }
.btn-appeler {
  padding: 10px; border-radius: 10px;
  border: 1.5px solid var(--border); background: white;
  color: var(--txt-dark); font-size: 13px; font-weight: 700;
  cursor: pointer; display: flex; align-items: center; justify-content: center; gap: 6px;
  transition: all .2s;
}
.btn-appeler:hover { border-color: var(--gold); }

/* ═══════════════════════════════════════════════
   SECTION CARDS (calendrier, avis)
═══════════════════════════════════════════════ */
.section-card {
  background: #fff; border-radius: var(--radius);
  box-shadow: var(--shadow); padding: 24px; margin-top: 20px;
}
.section-title {
  font-size: 18px; font-weight: 900; color: var(--txt-dark);
  margin-bottom: 18px; display: flex; align-items: center; gap: 8px;
}
.section-title i { color: var(--gold); }

/* ✅ FIX: rating-summary = gold-pale (plus navy background) */
.rating-summary {
  display: flex; gap: 20px; align-items: center;
  padding: 20px;
  background: var(--gold-pale);
  border: 1.5px solid var(--gold);
  border-radius: 14px; margin-bottom: 20px;
}
.rating-big { font-size: 48px; font-weight: 900; color: var(--gold-dk); line-height: 1; }
.rating-stars-big { color: var(--gold); font-size: 18px; letter-spacing: 2px; }
.rating-total { font-size: 13px; color: var(--txt-mid); margin-top: 4px; }
.review-card {
  padding: 16px; border: 1px solid var(--border); border-radius: 12px;
  margin-bottom: 12px; transition: box-shadow .2s;
}
.review-card:hover { box-shadow: 0 2px 12px rgba(0,0,0,.06); }
.review-header { display: flex; justify-content: space-between; align-items: flex-start; margin-bottom: 8px; }
.reviewer-name { font-weight: 700; color: var(--txt-dark); font-size: 14px; }
.reviewer-date { font-size: 11px; color: var(--gray); }
.review-stars  { color: var(--gold); font-size: 12px; margin-bottom: 6px; }
.review-text   { font-size: 13px; color: var(--gray); line-height: 1.6; }

/* Toast */
#showToast {
  position: fixed; bottom: 24px; right: 24px; z-index: 9999;
  padding: 14px 20px; border-radius: 14px;
  color: white; font-weight: 700; font-size: 14px;
  box-shadow: 0 8px 30px rgba(0,0,0,.2);
  transform: translateY(20px); opacity: 0;
  transition: all .3s ease; pointer-events: none;
}
#showToast.visible { transform: translateY(0); opacity: 1; }

/* Responsive */
@media (max-width: 1024px) {
  .show-wrap { grid-template-columns: 1fr; }
  .booking-card { position: static; }
  .specs-grid { grid-template-columns: repeat(2,1fr); }
  .mini3d-card { margin-top: 0; }
}
@media (max-width: 600px) {
  .show-wrap { padding: 12px; }
  .gallery-main { height: 260px; }
  .mini3d-wrap  { height: 160px; }
}
</style>
@endpush

@section('content')

{{-- BREADCRUMB --}}
<div style="background:#f8fafc;border-bottom:1px solid var(--cream3);padding:12px 24px">
  <div style="max-width:1280px;margin:0 auto;font-size:13px;color:var(--txt-mid)">
    <a href="/" style="color:inherit;text-decoration:none">Accueil</a>
    <span style="margin:0 8px">›</span>
    <a href="/machines" style="color:inherit;text-decoration:none">Catalogue</a>
    <span style="margin:0 8px">›</span>
    <span id="breadcrumb-name" style="color:var(--txt-dark);font-weight:600">Chargement...</span>
  </div>
</div>

<div class="show-wrap" id="showWrap" style="opacity:0;transition:opacity .4s">

  {{-- ══════════════════════════════════════
       COL GAUCHE
  ══════════════════════════════════════ --}}
  <div>

    {{-- ─── GALERIE IMAGES ─── --}}
    <div class="gallery-section">

      <div class="gallery-main" id="galleryMain" onclick="openLightbox(galleryIndex)">

        <img id="galleryMainImg" class="gallery-main-img" src="" alt="Machine"
             onerror="this.src=galleryFallback()">

        <div id="gallery-status-badge" class="gallery-status-badge status-available">Disponible</div>

        <button class="gallery-nav prev" id="galleryPrev"
                onclick="event.stopPropagation();galleryNav(-1)" style="display:none">‹</button>
        <button class="gallery-nav next" id="galleryNext"
                onclick="event.stopPropagation();galleryNav(1)" style="display:none">›</button>

        <div class="gallery-counter" id="galleryCounter" style="display:none">1 / 1</div>

        <button class="gallery-3d-btn" onclick="event.stopPropagation();toggle3DPanel()">
          <span>🔲</span> Vue 3D
        </button>

      </div>

      <div class="gallery-thumbs" id="galleryThumbs"></div>

    </div>{{-- fin gallery-section --}}

    {{-- ─── INFOS MACHINE ─── --}}
    <div class="machine-info" id="machineInfo">
      <div class="machine-badges">
        <span class="badge-type" id="machineType">—</span>
        <span class="badge-city">
          <i class="fas fa-map-marker-alt"></i>
          <span id="machineCity">—</span>
        </span>
      </div>
      <h1 class="machine-title" id="machineName">Chargement...</h1>
      <div class="machine-rating">
        <div class="stars" id="machineStars">★★★★★</div>
        <span class="rating-val" id="machineRatingVal">—</span>
        <span class="rating-count" id="machineRatingCount">(0 avis)</span>
      </div>
      <p class="machine-desc" id="machineDesc">—</p>

      <div class="specs-grid">
        <div class="spec-card">
          <div class="spec-icon">🏗️</div>
          <div class="spec-val" id="specType">—</div>
          <div class="spec-label">Type</div>
        </div>
        <div class="spec-card">
          <div class="spec-icon">📍</div>
          <div class="spec-val" id="specCity">—</div>
          <div class="spec-label">Ville</div>
        </div>
        <div class="spec-card">
          <div class="spec-icon">📅</div>
          <div class="spec-val" id="specDay">—</div>
          <div class="spec-label">DH/Jour</div>
        </div>
        <div class="spec-card">
          <div class="spec-icon">⏱️</div>
          <div class="spec-val" id="specHour">—</div>
          <div class="spec-label">DH/Heure</div>
        </div>
      </div>

      {{-- ✅ FIX: price-section gold-pale --}}
      <div class="price-section">
        <div class="price-item">
          <div class="price-label">Par jour</div>
          <div class="price-val" id="priceDay">— DH</div>
        </div>
        <div class="price-sep"></div>
        <div class="price-item">
          <div class="price-label">Par heure</div>
          <div class="price-val" id="priceHour">— DH</div>
        </div>
        <div class="price-sep"></div>
        <div class="price-item">
          <div class="price-label">Statut</div>
          <div class="price-val" id="priceStatus" style="font-size:14px">—</div>
        </div>
      </div>
    </div>

    {{-- ─── CALENDRIER ─── --}}
    <div class="section-card">
      <div class="section-title">
        <i class="fas fa-calendar-alt"></i> Calendrier de disponibilité
      </div>
      <div id="calendarWrap">
        <div style="text-align:center;padding:30px;color:var(--txt-mid)">
          Chargement du calendrier...
        </div>
      </div>
    </div>

    {{-- ─── AVIS ─── --}}
    <div class="section-card">
      <div class="section-title">
        <i class="fas fa-star"></i> Avis & Évaluations
      </div>
      <div class="rating-summary">
        <div>
          <div class="rating-big" id="avgRating">—</div>
          <div class="rating-stars-big" id="avgStars">★★★★★</div>
          <div class="rating-total" id="ratingTotal">0 avis</div>
        </div>
        <div style="flex:1;padding-left:20px" id="ratingBars"></div>
      </div>
      <div id="reviewsList">
        <div style="text-align:center;padding:20px;color:var(--txt-mid)">Aucun avis pour le moment</div>
      </div>
      <button class="btn-reserver mt-3"
              style="background:transparent;border:1.5px solid var(--gold);color:var(--gold-dk);width:auto;padding:10px 24px"
              onclick="showReviewForm()">
        <i class="fas fa-star"></i> Laisser un avis
      </button>
      <div id="reviewFormWrap" style="display:none;margin-top:16px">
        <div style="display:flex;gap:8px;margin-bottom:12px" id="starPicker">
          <span style="font-size:11px;color:var(--txt-mid);align-self:center">Note :</span>
          <span class="star-pick" data-v="1" onclick="pickStar(1)" style="font-size:24px;cursor:pointer;color:#d1d5db">★</span>
          <span class="star-pick" data-v="2" onclick="pickStar(2)" style="font-size:24px;cursor:pointer;color:#d1d5db">★</span>
          <span class="star-pick" data-v="3" onclick="pickStar(3)" style="font-size:24px;cursor:pointer;color:#d1d5db">★</span>
          <span class="star-pick" data-v="4" onclick="pickStar(4)" style="font-size:24px;cursor:pointer;color:#d1d5db">★</span>
          <span class="star-pick" data-v="5" onclick="pickStar(5)" style="font-size:24px;cursor:pointer;color:#d1d5db">★</span>
        </div>
        <textarea id="reviewText" class="form-control-custom" rows="3"
                  placeholder="Partagez votre expérience..."></textarea>
        <button class="btn-reserver mt-2" onclick="submitReview()"
                style="width:auto;padding:10px 24px">
          <i class="fas fa-paper-plane"></i> Envoyer
        </button>
      </div>
    </div>

  </div>{{-- fin col gauche --}}

  {{-- ══════════════════════════════════════
       SIDEBAR DROITE
  ══════════════════════════════════════ --}}
  <div>

    {{-- Booking card --}}
    <div class="booking-card">
      {{-- ✅ FIX: header gold-pale --}}
      <div class="booking-header">
        <div class="booking-price-main" id="bookingPrice">— DH</div>
        <div class="booking-price-sub">
          par jour · <span id="bookingPriceHour">—</span> DH/heure
        </div>
      </div>

      <div class="booking-body">
        <div class="form-group">
          <label class="form-label-custom">Mode de location</label>
          <div class="mode-toggle">
            <button class="mode-btn active" onclick="setMode('jour',this)">
              <i class="fas fa-sun me-1"></i>Jour
            </button>
            <button class="mode-btn" onclick="setMode('heure',this)">
              <i class="fas fa-clock me-1"></i>Heure
            </button>
          </div>
        </div>

        <div class="form-group">
          <label class="form-label-custom">Date de début</label>
          <input type="date" id="startDate" class="form-control-custom"
                 onchange="calcTotal()" min="{{ date('Y-m-d') }}">
        </div>

        <div class="form-group">
          <label class="form-label-custom" id="durationLabel">Durée (jours)</label>
          <div class="duration-ctrl">
            <button class="duration-btn" onclick="changeDuration(-1)">−</button>
            <input type="number" id="durationVal" class="duration-input"
                   value="1" min="1" onchange="calcTotal()">
            <button class="duration-btn" onclick="changeDuration(1)">+</button>
          </div>
        </div>

        <div class="price-recap">
          <div class="price-line">
            <span id="recapLabel">— DH × 1 jour</span>
            <span id="recapSub">— DH</span>
          </div>
          <div class="price-line">
            <span>Frais de service (5%)</span>
            <span id="recapFee">— DH</span>
          </div>
          <div class="price-line total">
            <span>Total estimé</span>
            <span id="recapTotal">— DH</span>
          </div>
        </div>

        {{-- ✅ FIX: bouton "Envoyer une demande" = gold --}}
        <button onclick="reserver()" class="btn-reserver">
          <i class="fas fa-paper-plane"></i> Envoyer une demande
        </button>
        <button class="btn-devis" onclick="devis()">
          <i class="fas fa-file-alt"></i> Devis rapide
        </button>
      </div>

      <div class="owner-section">
        <div class="owner-row">
          <div class="owner-avatar" id="ownerAvatar">P</div>
          <div>
            <div class="owner-name" id="ownerName">Propriétaire</div>
            <div class="owner-badge"><i class="fas fa-check-circle me-1"></i>Vérifié</div>
          </div>
        </div>
        <div class="owner-actions">
          <button class="btn-whatsapp" onclick="openWhatsApp()">
            <i class="fab fa-whatsapp"></i> WhatsApp
          </button>
          <button class="btn-appeler" onclick="appeler()">
            <i class="fas fa-phone"></i> Appeler
          </button>
        </div>
      </div>
    </div>

    {{-- ─── MINI 3D VIEWER ─── --}}
    <div class="mini3d-card" id="mini3dCard" style="display:none">
      <div class="mini3d-header">
        <div class="mini3d-title">
          <span>🔲</span> Modèle 3D interactif
        </div>
        <button onclick="toggle3DPanel()"
                style="background:none;border:none;color:rgba(212,175,55,.5);
                       cursor:pointer;font-size:16px;line-height:1;">✕</button>
      </div>
      <div class="mini3d-wrap" id="mini3d-wrap">
        <canvas id="mini3d-canvas"
                style="display:block;width:100%;height:100%;cursor:grab;touch-action:none;"></canvas>
        <div id="mini3d-loader"
             style="position:absolute;inset:0;display:flex;flex-direction:column;
                    align-items:center;justify-content:center;
                    background:#071018;gap:10px;z-index:10;pointer-events:none;">
          <div style="width:28px;height:28px;border:2px solid #162333;
                      border-top-color:#D4AF37;border-radius:50%;
                      animation:v3dspin .75s linear infinite;"></div>
          <span style="font-size:10px;color:#3a5a75;">Chargement 3D…</span>
        </div>
      </div>
      <div class="mini3d-controls">
        <button class="mini3d-btn primary" id="mini3d-anim-btn" onclick="toggleMini3DAnim()">
          ▶ Animer
        </button>
        <button class="mini3d-btn secondary" onclick="resetMini3D()">↺ Reset</button>
      </div>
      <div class="mini3d-hint">⟳ Glisser · Molette zoom</div>
    </div>

  </div>{{-- fin sidebar --}}

</div>{{-- fin show-wrap --}}

{{-- LIGHTBOX --}}
<div class="lightbox-overlay" id="lightboxOverlay" onclick="closeLightbox()">
  <button class="lightbox-close" onclick="closeLightbox()">✕</button>
  <button class="lightbox-nav prev" onclick="event.stopPropagation();lightboxNav(-1)">‹</button>
  <img id="lightboxImg" class="lightbox-img" src="" alt=""
       onclick="event.stopPropagation()"
       onerror="this.src=galleryFallback()">
  <button class="lightbox-nav next" onclick="event.stopPropagation();lightboxNav(1)">›</button>
</div>

<div id="showToast"></div>
@endsection

@push('scripts')
{{-- Three.js r128 --}}
<script src="https://cdnjs.cloudflare.com/ajax/libs/three.js/r128/three.min.js"></script>

<script>
/* ═══════════════════════════════════════════════
   STATE GLOBAL
═══════════════════════════════════════════════ */
var machineId    = window.location.pathname.split('/').pop();
var machine      = null;
var currentMode  = 'jour';
var selectedStar = 0;

var galleryImages = [];
var galleryIndex  = 0;
var mini3dReady   = false;
var mini3dAnimOn  = false;
var mini3dTheta   = .95, mini3dPhi = .38, mini3dRadius = 7.5;
var mini3dRenderer= null, mini3dScene = null, mini3dCamera = null;
var mini3dBoom = null, mini3dDipper = null, mini3dBktGrp = null, mini3dLoaderArm = null;
var mini3dAT  = 0;
var MINI3D_BOOM0=-.45, MINI3D_DIP0=.55, MINI3D_BKT0=-.45;

var TYPE_PHOTO = {
  excavatrice: '/images/img3.png',
  grue:        '/images/img4.png',
  bulldozer:   '/images/img1.png',
  chargeuse:   '/images/img2.png',
  compacteur:  '/images/img8.png',
  nacelle:     '/images/img5.png',
  tractopelle: '/images/img7.png',
  camion:      '/images/img9.png',
};
function galleryFallback() {
  if (!machine) return '/images/img1.png';
  var t = (machine.type||'').toLowerCase();
  return TYPE_PHOTO[t] || '/images/img1.png';
}

/* ═══════════════════════════════════════════════
   INIT
═══════════════════════════════════════════════ */
document.addEventListener('DOMContentLoaded', function() {
  loadMachine();
  document.getElementById('startDate').value = new Date().toISOString().split('T')[0];
  document.addEventListener('keydown', function(e) {
    if (e.key === 'Escape') closeLightbox();
    if (e.key === 'ArrowLeft')  { lightboxNav(-1); galleryNav(-1); }
    if (e.key === 'ArrowRight') { lightboxNav(1);  galleryNav(1);  }
  });
});

/* ═══════════════════════════════════════════════
   LOAD MACHINE
═══════════════════════════════════════════════ */
async function loadMachine() {
  try {
    var token = localStorage.getItem('auth_token') || localStorage.getItem('token') || '';
    var headers = { 'Accept': 'application/json' };
    if (token) headers['Authorization'] = 'Bearer ' + token;

    var r    = await fetch('/api/machines/' + machineId, { headers: headers });
    var json = await r.json();
    machine  = (json && json.data) ? json.data : json;

    if (!machine || !machine.id) {
      document.getElementById('machineName').textContent = 'Machine introuvable';
      return;
    }
    renderMachine();
    buildGallery();
    loadRatings();
    loadCalendar();
    document.getElementById('showWrap').style.opacity = '1';

  } catch(e) {
    console.error('loadMachine:', e);
    showToast('Erreur de chargement', 'danger');
  }
}

/* ═══════════════════════════════════════════════
   RENDER MACHINE
═══════════════════════════════════════════════ */
function renderMachine() {
  var m = machine;

  document.getElementById('breadcrumb-name').textContent = m.name || '—';
  document.getElementById('machineType').textContent     = m.type || 'Machine';
  document.getElementById('machineCity').textContent     = m.city || '—';
  document.getElementById('machineName').textContent     = m.name || '—';
  document.getElementById('machineDesc').textContent     = m.description || 'Aucune description disponible.';
  document.getElementById('specType').textContent  = m.type               || '—';
  document.getElementById('specCity').textContent  = m.city               || '—';
  document.getElementById('specDay').textContent   = fmt(m.price_per_day) + ' DH';
  document.getElementById('specHour').textContent  = fmt(m.price_per_hour)+ ' DH';
  document.getElementById('priceDay').textContent  = fmt(m.price_per_day) + ' DH';
  document.getElementById('priceHour').textContent = fmt(m.price_per_hour)+ ' DH';

  var statusLabels = { available:'Disponible', rented:'Loué', maintenance:'Maintenance', unavailable:'Indisponible' };
  var st = m.status || 'available';
  var stColor = st==='available' ? '#15803d' : (st==='rented' ? '#9A7D20' : (st==='maintenance' ? '#b91c1c' : '#6b7280'));
  document.getElementById('priceStatus').textContent   = statusLabels[st] || st;
  document.getElementById('priceStatus').style.color   = stColor;

  var badge = document.getElementById('gallery-status-badge');
  badge.textContent = (statusLabels[st] || st).toUpperCase();
  badge.className   = 'gallery-status-badge status-' + st;

  document.getElementById('bookingPrice').textContent     = fmt(m.price_per_day)  + ' DH';
  document.getElementById('bookingPriceHour').textContent = fmt(m.price_per_hour);

  var ownerName = m.owner ? (m.owner.name || 'Propriétaire') : 'Propriétaire';
  document.getElementById('ownerName').textContent   = ownerName;
  document.getElementById('ownerAvatar').textContent = ownerName.charAt(0).toUpperCase();

  calcTotal();
}

/* ═══════════════════════════════════════════════
   GALERIE — BUILD
═══════════════════════════════════════════════ */
function buildGallery() {
  var m = machine;
  galleryImages = [];

  if (m.machine_images && m.machine_images.length) {
    m.machine_images.forEach(function(img) {
      var src = img.path
        ? (img.path.startsWith('http') ? img.path : '/storage/' + img.path)
        : null;
      if (src) galleryImages.push({ src: src, alt: m.name || 'Machine' });
    });
  }

  if (!galleryImages.length && m.images && m.images.length) {
    m.images.forEach(function(img) {
      var src = typeof img === 'string'
        ? (img.startsWith('http') ? img : '/storage/' + img)
        : (img.path ? '/storage/' + img.path : null);
      if (src) galleryImages.push({ src: src, alt: m.name || 'Machine' });
    });
  }

  if (!galleryImages.length) {
    galleryImages.push({ src: galleryFallback(), alt: m.name || 'Machine' });
  }

  galleryIndex = 0;
  renderGalleryMain();
  renderGalleryThumbs();
}

function renderGalleryMain() {
  var img = galleryImages[galleryIndex];
  document.getElementById('galleryMainImg').src = img.src;
  document.getElementById('galleryMainImg').alt = img.alt;

  var multi = galleryImages.length > 1;
  document.getElementById('galleryPrev').style.display    = multi ? 'flex' : 'none';
  document.getElementById('galleryNext').style.display    = multi ? 'flex' : 'none';
  document.getElementById('galleryCounter').style.display = multi ? 'block' : 'none';
  if (multi) {
    document.getElementById('galleryCounter').textContent =
      (galleryIndex + 1) + ' / ' + galleryImages.length;
  }
}

function renderGalleryThumbs() {
  var container = document.getElementById('galleryThumbs');
  if (galleryImages.length <= 1) { container.style.display = 'none'; return; }
  container.style.display = 'flex';
  container.innerHTML = '';
  galleryImages.forEach(function(img, i) {
    var div = document.createElement('div');
    div.className = 'gallery-thumb' + (i === 0 ? ' active' : '');
    div.onclick   = function() { setGalleryIndex(i); };
    var el  = document.createElement('img');
    el.src  = img.src;
    el.alt  = img.alt;
    el.onerror = function() { this.src = galleryFallback(); };
    div.appendChild(el);
    container.appendChild(div);
  });
}

function setGalleryIndex(i) {
  galleryIndex = (i + galleryImages.length) % galleryImages.length;
  renderGalleryMain();
  document.querySelectorAll('.gallery-thumb').forEach(function(el, idx) {
    el.classList.toggle('active', idx === galleryIndex);
  });
  var thumbs = document.querySelectorAll('.gallery-thumb');
  if (thumbs[galleryIndex]) {
    thumbs[galleryIndex].scrollIntoView({ behavior: 'smooth', block: 'nearest', inline: 'center' });
  }
}

function galleryNav(dir) {
  setGalleryIndex(galleryIndex + dir);
}

/* ═══════════════════════════════════════════════
   LIGHTBOX
═══════════════════════════════════════════════ */
function openLightbox(idx) {
  galleryIndex = idx;
  document.getElementById('lightboxImg').src = galleryImages[idx].src;
  document.getElementById('lightboxOverlay').classList.add('active');
  document.body.style.overflow = 'hidden';
}
function closeLightbox() {
  document.getElementById('lightboxOverlay').classList.remove('active');
  document.body.style.overflow = '';
}
function lightboxNav(dir) {
  var newIdx = (galleryIndex + dir + galleryImages.length) % galleryImages.length;
  galleryIndex = newIdx;
  document.getElementById('lightboxImg').src = galleryImages[newIdx].src;
}

/* ═══════════════════════════════════════════════
   TOGGLE 3D PANEL
═══════════════════════════════════════════════ */
function toggle3DPanel() {
  var card = document.getElementById('mini3dCard');
  var visible = card.style.display !== 'none';
  card.style.display = visible ? 'none' : 'block';
  if (!visible && !mini3dReady) {
    setTimeout(function() { initMini3DViewer(); }, 50);
  }
}

/* ═══════════════════════════════════════════════
   MINI 3D VIEWER
═══════════════════════════════════════════════ */
function initMini3DViewer() {
  if (mini3dReady) return;
  mini3dReady = true;

  var TYPE = ((machine && machine.type) || 'excavatrice').toLowerCase();
  var PALETTES = {
    excavatrice : { main:0xD4AF37, dark:0x9A7D20 },
    grue        : { main:0xF5E88A, dark:0xD4AF37 },
    chargeuse   : { main:0xD4AF37, dark:0x9A7D20 },
    bulldozer   : { main:0xD4AF37, dark:0x9A7D20 },
    compacteur  : { main:0xD4AF37, dark:0x9A7D20 },
    nacelle     : { main:0x42a5f5, dark:0x1565c0 },
  };
  var PAL = PALETTES[TYPE] || PALETTES.excavatrice;

  var wrap   = document.getElementById('mini3d-wrap');
  var canvas = document.getElementById('mini3d-canvas');
  var W = wrap.clientWidth || 340, H = 200;

  mini3dRenderer = new THREE.WebGLRenderer({ canvas: canvas, antialias: true });
  mini3dRenderer.setPixelRatio(Math.min(devicePixelRatio, 2));
  mini3dRenderer.setSize(W, H);
  mini3dRenderer.shadowMap.enabled = true;

  mini3dScene = new THREE.Scene();
  mini3dScene.background = new THREE.Color(0x071018);
  mini3dCamera = new THREE.PerspectiveCamera(48, W / H, 0.1, 100);

  mini3dScene.add(new THREE.AmbientLight(0xffffff, 0.55));
  var sun = new THREE.DirectionalLight(0xfff5dd, 1.7);
  sun.position.set(8, 13, 9); sun.castShadow = true;
  mini3dScene.add(sun);
  var fill = new THREE.DirectionalLight(0x3355bb, 0.28);
  fill.position.set(-6, 4, -6); mini3dScene.add(fill);

  var gnd = new THREE.Mesh(
    new THREE.PlaneGeometry(30, 30),
    new THREE.MeshLambertMaterial({ color: 0x0c1c2d })
  );
  gnd.rotation.x = -Math.PI / 2; gnd.receiveShadow = true; mini3dScene.add(gnd);
  mini3dScene.add(new THREE.GridHelper(16, 16, 0x1b3246, 0x111f2d));

  var M  = function(c) { return new THREE.MeshLambertMaterial({ color: c }); };
  var MT = function(c,o) { return new THREE.MeshLambertMaterial({ color:c, transparent:true, opacity:o }); };
  var Y=M(PAL.main), YD=M(PAL.dark), BK=M(0x0d0d0d), SL=M(0x7a8898), DK=M(0x060c12);
  var GL=MT(0x88c4ea,.45), RD=MT(0xff2200,.9), AM=MT(0xD4AF37,.85);

  var B = function(w,h,d,mat,x,y,z,rx,ry,rz) {
    x=x||0;y=y||0;z=z||0;rx=rx||0;ry=ry||0;rz=rz||0;
    var o = new THREE.Mesh(new THREE.BoxGeometry(w,h,d), mat);
    o.position.set(x,y,z); o.rotation.set(rx,ry,rz);
    o.castShadow = o.receiveShadow = true; return o;
  };
  var C = function(rt,rb,h,s,mat,x,y,z,rx,ry,rz) {
    x=x||0;y=y||0;z=z||0;rx=rx||0;ry=ry||0;rz=rz||0;
    var o = new THREE.Mesh(new THREE.CylinderGeometry(rt,rb,h,s), mat);
    o.position.set(x,y,z); o.rotation.set(rx,ry,rz); o.castShadow = true; return o;
  };

  var JCB = new THREE.Group();
  JCB.add(B(4.1,.21,1.9,YD,0,.51,0));
  JCB.add(B(3.75,.39,1.68,Y,0,.78,0));
  var cab = new THREE.Group(); cab.position.set(-.52,.975,0);
  cab.add(B(1.28,1.42,1.52,Y,0,.71,0));
  cab.add(B(.07,.78,1.12,GL,-.645,.8,0));
  cab.add(B(.07,.78,1.12,GL,.645,.8,0));
  cab.add(B(1.15,.07,1.12,GL,0,1.4,0));
  cab.add(B(1.28,.07,1.52,YD,0,1.41,0));
  [.76,-.76].forEach(function(z){
    cab.add(B(.07,.78,.07,DK,-.645,.8,z));
    cab.add(B(.07,.78,.07,DK,.645,.8,z));
  });
  cab.add(B(.07,.1,.15,AM,-.645,.72,.65)); cab.add(B(.07,.1,.15,AM,-.645,.72,-.65));
  JCB.add(cab);
  JCB.add(B(1.58,.87,1.62,Y,.93,1.21,0));
  JCB.add(B(.06,.55,1.42,DK,1.73,1.15,0));
  JCB.add(C(.055,.055,.6,10,SL,.72,1.91,.52));
  JCB.add(B(.07,.16,.22,RD,1.73,1.06,.63)); JCB.add(B(.07,.16,.22,RD,1.73,1.06,-.63));
  JCB.add(B(.07,.1,.15,AM,1.73,.88,.63));   JCB.add(B(.07,.1,.15,AM,1.73,.88,-.63));
  var addWheel = function(x,y,z,r,ww) {
    JCB.add(C(r,r,ww,28,BK,x,y,z,Math.PI/2,0,0));
    JCB.add(C(r*.5,r*.5,ww+.02,18,SL,x,y,z,Math.PI/2,0,0));
    JCB.add(C(r*.16,r*.16,ww+.05,8,DK,x,y,z,Math.PI/2,0,0));
  };
  addWheel(-1.32,.46,.94,.46,.38); addWheel(-1.32,.46,-.94,.46,.38);
  addWheel(1.32,.50,.97,.50,.42);  addWheel(1.32,.50,-.97,.50,.42);
  mini3dLoaderArm = new THREE.Group(); mini3dLoaderArm.position.set(-1.9,.78,0);
  mini3dLoaderArm.add(B(.12,1.28,.12,YD,0,.56,.67,-.22,0,0));
  mini3dLoaderArm.add(B(.12,1.28,.12,YD,0,.56,-.67,-.22,0,0));
  mini3dLoaderArm.add(B(.1,.1,1.46,YD,0,.98,0));
  var ldBkt = new THREE.Group(); ldBkt.position.set(-.13,1.22,0);
  ldBkt.add(B(.5,.28,1.72,Y)); ldBkt.add(B(.36,.07,1.72,YD,-.16,-.13,0,.35,0,0));
  mini3dLoaderArm.add(ldBkt); JCB.add(mini3dLoaderArm);
  var bh = new THREE.Group(); bh.position.set(1.9,.78,0);
  bh.add(B(.38,.64,.46,Y,0,.32,0));
  mini3dBoom = new THREE.Group(); mini3dBoom.position.set(0,.64,0); mini3dBoom.rotation.z=MINI3D_BOOM0;
  mini3dBoom.add(B(.18,2.02,.18,Y,0,1.01,0));
  mini3dDipper = new THREE.Group(); mini3dDipper.position.set(0,2.02,0); mini3dDipper.rotation.z=MINI3D_DIP0;
  mini3dDipper.add(B(.14,1.32,.14,Y,0,.66,0));
  mini3dBktGrp = new THREE.Group(); mini3dBktGrp.position.set(0,1.32,0); mini3dBktGrp.rotation.z=MINI3D_BKT0;
  mini3dBktGrp.add(B(.43,.34,.56,YD)); mini3dBktGrp.add(B(.36,.07,.56,Y,-.16,-.14,0,.3,0,0));
  mini3dDipper.add(mini3dBktGrp); mini3dBoom.add(mini3dDipper); bh.add(mini3dBoom); JCB.add(bh);
  mini3dScene.add(JCB);

  var dragging=false, autoRot=true, px=0, py=0;
  canvas.addEventListener('mousedown', function(e){ dragging=true; autoRot=false; px=e.clientX; py=e.clientY; canvas.style.cursor='grabbing'; });
  document.addEventListener('mouseup',  function(){ dragging=false; canvas.style.cursor='grab'; });
  document.addEventListener('mousemove',function(e){
    if(!dragging) return;
    mini3dTheta -= (e.clientX-px)*.009;
    mini3dPhi = Math.max(.06, Math.min(1.28, mini3dPhi+(e.clientY-py)*.006));
    px=e.clientX; py=e.clientY;
  });
  canvas.addEventListener('wheel', function(e){
    mini3dRadius = Math.max(4, Math.min(13, mini3dRadius+e.deltaY*.012));
    e.preventDefault();
  }, { passive: false });

  setTimeout(function(){
    var ld = document.getElementById('mini3d-loader');
    if(ld) ld.style.display='none';
  }, 500);

  function loop(){
    requestAnimationFrame(loop);
    if(autoRot && !dragging) mini3dTheta += .004;
    mini3dCamera.position.set(
      mini3dRadius*Math.sin(mini3dTheta)*Math.cos(mini3dPhi),
      mini3dRadius*Math.sin(mini3dPhi)+1.4,
      mini3dRadius*Math.cos(mini3dTheta)*Math.cos(mini3dPhi)
    );
    mini3dCamera.lookAt(0, 1.35, 0);
    if(mini3dAnimOn){
      mini3dAT += .022;
      mini3dBoom.rotation.z     = MINI3D_BOOM0 + Math.sin(mini3dAT)*.74;
      mini3dDipper.rotation.z   = MINI3D_DIP0  + Math.sin(mini3dAT*1.35+.9)*.52;
      mini3dBktGrp.rotation.z   = MINI3D_BKT0  + Math.sin(mini3dAT*1.8+1.4)*.52;
      mini3dLoaderArm.rotation.z= Math.sin(mini3dAT*.55)*.18;
    }
    mini3dRenderer.render(mini3dScene, mini3dCamera);
  }
  loop();

  window.addEventListener('resize', function(){
    if (!document.getElementById('mini3dCard') || document.getElementById('mini3dCard').style.display==='none') return;
    W = wrap.clientWidth;
    mini3dRenderer.setSize(W, H);
    mini3dCamera.aspect = W/H;
    mini3dCamera.updateProjectionMatrix();
  });
}

function toggleMini3DAnim() {
  mini3dAnimOn = !mini3dAnimOn;
  document.getElementById('mini3d-anim-btn').textContent = mini3dAnimOn ? '⏸ Pause' : '▶ Animer';
}
function resetMini3D() {
  mini3dTheta=.95; mini3dPhi=.38; mini3dRadius=7.5; mini3dAnimOn=false;
  if(mini3dBoom)   { mini3dBoom.rotation.z=MINI3D_BOOM0; }
  if(mini3dDipper) { mini3dDipper.rotation.z=MINI3D_DIP0; }
  if(mini3dBktGrp) { mini3dBktGrp.rotation.z=MINI3D_BKT0; }
  if(mini3dLoaderArm) { mini3dLoaderArm.rotation.z=0; }
  mini3dAT=0;
  document.getElementById('mini3d-anim-btn').textContent='▶ Animer';
}

/* ═══════════════════════════════════════════════
   MODE & CALCUL
═══════════════════════════════════════════════ */
function setMode(mode, btn) {
  currentMode = mode;
  document.querySelectorAll('.mode-btn').forEach(function(b){ b.classList.remove('active'); });
  btn.classList.add('active');
  document.getElementById('durationLabel').textContent = mode === 'jour' ? 'Durée (jours)' : 'Durée (heures)';
  calcTotal();
}
function changeDuration(d) {
  var inp = document.getElementById('durationVal');
  inp.value = Math.max(1, parseInt(inp.value||1)+d);
  calcTotal();
}
function calcTotal() {
  if (!machine) return;
  var dur  = parseInt(document.getElementById('durationVal').value) || 1;
  var rate = currentMode === 'jour' ? (machine.price_per_day||0) : (machine.price_per_hour||0);
  var base  = rate * dur;
  var fee   = Math.round(base * .05);
  var total = base + fee;
  var unit  = currentMode === 'jour' ? 'jour' : 'heure';
  document.getElementById('recapLabel').textContent = fmt(rate)+' DH × '+dur+' '+unit+(dur>1?'s':'');
  document.getElementById('recapSub').textContent   = fmt(base)+' DH';
  document.getElementById('recapFee').textContent   = fmt(fee) +' DH';
  document.getElementById('recapTotal').textContent = fmt(total)+' DH';
}

/* ═══════════════════════════════════════════════
   RÉSERVER
═══════════════════════════════════════════════ */
async function reserver() {
  var token = localStorage.getItem('auth_token') || localStorage.getItem('token');
  if (!token) { window.location.href = '/login'; return; }
  var start = document.getElementById('startDate').value;
  var dur   = parseInt(document.getElementById('durationVal').value) || 1;
  if (!start) { showToast('Choisissez une date de début', 'warning'); return; }
  var end = new Date(start);
  if (currentMode === 'jour') end.setDate(end.getDate()+dur);
  var endStr = end.toISOString().split('T')[0];
  try {
    var r = await fetch('/api/reservations', {
      method: 'POST',
      headers: { 'Content-Type':'application/json', 'Authorization':'Bearer '+token },
      body: JSON.stringify({ machine_id:machineId, start_date:start, end_date:endStr })
    });
    var json = await r.json();
    if (r.ok) showToast('✅ Demande envoyée avec succès !', 'success');
    else      showToast('❌ '+(json.message||'Erreur'), 'danger');
  } catch(e) { showToast('❌ Erreur réseau', 'danger'); }
}
function devis() { showToast('📄 Fonctionnalité devis bientôt disponible', 'info'); }

/* ═══════════════════════════════════════════════
   OWNER ACTIONS
═══════════════════════════════════════════════ */
function openWhatsApp() {
  var phone = machine && machine.owner ? (machine.owner.phone||'') : '';
  phone = phone.replace(/\s/g,'').replace(/^\+/,'');
  if (!phone) { showToast('Numéro non disponible', 'warning'); return; }
  var msg = encodeURIComponent('Bonjour, je suis intéressé par "'+( machine.name||'')+'" sur Rentify.');
  window.open('https://wa.me/'+phone+'?text='+msg, '_blank');
}
function appeler() {
  var phone = machine && machine.owner ? (machine.owner.phone||'') : '';
  if (!phone) { showToast('Numéro non disponible', 'warning'); return; }
  window.location.href = 'tel:'+phone;
}

/* ═══════════════════════════════════════════════
   RATINGS
═══════════════════════════════════════════════ */
async function loadRatings() {
  try {
    var r = await fetch('/api/machines/'+machineId+'/ratings');
    if (!r.ok) return;
    var json = await r.json();
    var ratings = (json && json.data) ? json.data : (Array.isArray(json) ? json : []);
    renderRatings(ratings);
  } catch(e) {}
}
function renderRatings(ratings) {
  if (!ratings.length) return;
  var avg = ratings.reduce(function(s,r){ return s+(r.rating||r.note||0); },0) / ratings.length;
  avg = Math.round(avg*10)/10;
  document.getElementById('avgRating').textContent   = avg;
  document.getElementById('avgStars').textContent    = starsStr(avg);
  document.getElementById('ratingTotal').textContent = ratings.length+' avis vérifiés';
  var bars='';
  for(var s=5;s>=1;s--){
    var count = ratings.filter(function(r){ return Math.round(r.rating||r.note||0)===s; }).length;
    var pct   = Math.round(count/ratings.length*100);
    bars += '<div style="display:flex;align-items:center;gap:8px;margin-bottom:6px">'+
      '<span style="font-size:11px;color:var(--txt-mid);width:12px">'+s+'</span>'+
      '<div style="flex:1;height:6px;background:var(--cream3);border-radius:3px;overflow:hidden">'+
        '<div style="width:'+pct+'%;height:100%;background:var(--gold);border-radius:3px"></div>'+
      '</div>'+
      '<span style="font-size:11px;color:var(--txt-mid);width:28px">'+pct+'%</span>'+
      '</div>';
  }
  document.getElementById('ratingBars').innerHTML = bars;
  var html='';
  ratings.slice(0,5).forEach(function(r){
    var name = r.user ? (r.user.name||'Anonyme') : 'Client Rentify';
    var note = r.rating||r.note||5;
    var text = r.comment||r.commentaire||'';
    var date = r.created_at ? new Date(r.created_at).toLocaleDateString('fr-FR') : '';
    html += '<div class="review-card">'+
      '<div class="review-header">'+
        '<div class="reviewer-name">'+name+'</div>'+
        '<div class="reviewer-date">'+date+'</div>'+
      '</div>'+
      '<div class="review-stars">'+starsStr(note)+'</div>'+
      (text ? '<div class="review-text">'+text+'</div>' : '')+
      '</div>';
  });
  document.getElementById('reviewsList').innerHTML = html || '<p style="color:var(--txt-mid);text-align:center;padding:16px">Aucun avis</p>';
  document.getElementById('machineRatingVal').textContent   = avg;
  document.getElementById('machineStars').textContent       = starsStr(avg);
  document.getElementById('machineRatingCount').textContent = '('+ratings.length+' avis)';
}
function showReviewForm() {
  var token = localStorage.getItem('auth_token') || localStorage.getItem('token');
  if (!token) { window.location.href = '/login'; return; }
  var wrap = document.getElementById('reviewFormWrap');
  wrap.style.display = wrap.style.display==='none' ? 'block' : 'none';
}
function pickStar(val) {
  selectedStar=val;
  document.querySelectorAll('.star-pick').forEach(function(s){
    s.style.color = parseInt(s.dataset.v)<=val ? '#D4AF37' : '#d1d5db';
  });
}
async function submitReview() {
  var token = localStorage.getItem('auth_token') || localStorage.getItem('token');
  if (!selectedStar) { showToast('Choisissez une note','warning'); return; }
  var text = document.getElementById('reviewText').value.trim();
  try {
    var r = await fetch('/api/machines/'+machineId+'/ratings', {
      method:'POST',
      headers:{ 'Content-Type':'application/json','Authorization':'Bearer '+token },
      body: JSON.stringify({ machine_id:machineId, rating:selectedStar, comment:text })
    });
    var json = await r.json();
    if(r.ok){ showToast('✅ Avis envoyé, merci !','success'); document.getElementById('reviewFormWrap').style.display='none'; loadRatings(); }
    else     showToast('❌ '+(json.message||'Erreur'),'danger');
  } catch(e){ showToast('Erreur réseau','danger'); }
}

/* ═══════════════════════════════════════════════
   CALENDRIER
═══════════════════════════════════════════════ */
async function loadCalendar() {
  var now=new Date(); renderCalendar(now.getFullYear(), now.getMonth(), []);
  try {
    var r = await fetch('/api/machines/'+machineId+'/availability');
    if(r.ok){ var json=await r.json(); renderCalendar(now.getFullYear(),now.getMonth(),json.data||json||[]); }
  } catch(e){}
}
function renderCalendar(year, month, reserved) {
  var names  =['Dim','Lun','Mar','Mer','Jeu','Ven','Sam'];
  var mnames =['Janvier','Février','Mars','Avril','Mai','Juin','Juillet','Août','Septembre','Octobre','Novembre','Décembre'];
  var first  = new Date(year,month,1).getDay();
  var days   = new Date(year,month+1,0).getDate();
  var today  = new Date().getDate();
  var curM   = new Date().getMonth();
  var curY   = new Date().getFullYear();
  var html='<div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:16px">'+
    '<button onclick="changeMonth(-1)" style="border:none;background:var(--cream2);border-radius:8px;width:32px;height:32px;cursor:pointer">‹</button>'+
    '<strong style="color:var(--txt-dark)">'+mnames[month]+' '+year+'</strong>'+
    '<button onclick="changeMonth(1)"  style="border:none;background:var(--cream2);border-radius:8px;width:32px;height:32px;cursor:pointer">›</button>'+
    '</div><div style="display:grid;grid-template-columns:repeat(7,1fr);gap:4px;text-align:center">';
  names.forEach(function(n){ html+='<div style="font-size:10px;font-weight:700;color:var(--txt-light);padding:6px 0">'+n+'</div>'; });
  for(var i=0;i<first;i++) html+='<div></div>';
  for(var d=1;d<=days;d++){
    var isToday   =(d===today && month===curM && year===curY);
    var isPast    =(new Date(year,month,d)<new Date(curY,curM,today));
    var isReserved=reserved.includes(year+'-'+pad(month+1)+'-'+pad(d));
    var bg =isReserved?'#FEE2E2':(isToday?'#D4AF37':(isPast?'#f9fafb':'#f0fdf4'));
    var col=isReserved?'#991B1B':(isToday?'#1a1a2e':(isPast?'#d1d5db':'#1a1a2e'));
    var fw =isToday?'900':'500';
    html+='<div style="background:'+bg+';color:'+col+';border-radius:8px;padding:8px 4px;font-size:12px;font-weight:'+fw+'">'+d+'</div>';
  }
  html+='</div><div style="display:flex;gap:16px;margin-top:12px;font-size:11px;color:var(--txt-mid)">'+
    '<span><span style="display:inline-block;width:12px;height:12px;background:#f0fdf4;border-radius:3px;margin-right:4px"></span>Disponible</span>'+
    '<span><span style="display:inline-block;width:12px;height:12px;background:#FEE2E2;border-radius:3px;margin-right:4px"></span>Réservé</span>'+
    '</div>';
  document.getElementById('calendarWrap').innerHTML=html;
  window._calYear=year; window._calMonth=month; window._calResv=reserved;
}
function changeMonth(d){
  var m=window._calMonth+d, y=window._calYear;
  if(m<0){m=11;y--;} if(m>11){m=0;y++;}
  renderCalendar(y,m,window._calResv||[]);
}

/* ═══════════════════════════════════════════════
   UTILS
═══════════════════════════════════════════════ */
function fmt(n) { return n ? parseInt(n).toLocaleString('fr') : '0'; }
function pad(n) { return n<10?'0'+n:''+n; }
function starsStr(n){ var f=Math.round(n),s=''; for(var i=1;i<=5;i++) s+=(i<=f?'★':'☆'); return s; }
var _toastT;
function showToast(msg, type) {
  var el=document.getElementById('showToast');
  var colors={success:'#10b981',danger:'#ef4444',warning:'#D4AF37',info:'#3b82f6'};
  el.style.background=colors[type]||colors.info;
  el.textContent=msg; el.classList.add('visible');
  clearTimeout(_toastT);
  _toastT=setTimeout(function(){ el.classList.remove('visible'); },3500);
}
</script>
@endpush