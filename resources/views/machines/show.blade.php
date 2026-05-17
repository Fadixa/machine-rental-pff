@extends('layouts.app')
@section('title', 'Détail machine — Rentify')

@push('styles')
<style>
/* ═══════════════════════════════════════════════
   VARIABLES & BASE
═══════════════════════════════════════════════ */
:root {
  --navy:   #0F1B2D;
  --navy2:  #1a2d45;
  --navy3:  #243752;
  --orange: #F59E0B;
  --orange2:#d97706;
  --green:  #10b981;
  --red:    #ef4444;
  --gray:   #6b7280;
  --border: #e2e8f0;
  --radius: 16px;
  --shadow: 0 4px 24px rgba(15,27,45,.10);
}

* { box-sizing: border-box; margin: 0; padding: 0; }

.show-wrap {
  max-width: 1280px; margin: 0 auto;
  padding: 28px 24px; display: grid;
  grid-template-columns: 1fr 380px;
  gap: 28px; align-items: start;
}

/* ═══════════════════════════════════════════════
   3D IMAGE VIEWER
═══════════════════════════════════════════════ */
.viewer-section {
  background: #fff; border-radius: var(--radius);
  box-shadow: var(--shadow); overflow: hidden;
}

.viewer-3d-wrap {
  position: relative; height: 420px;
  background: linear-gradient(135deg, var(--navy) 0%, var(--navy2) 60%, var(--navy3) 100%);
  overflow: hidden; perspective: 1000px;
  cursor: grab;
}
.viewer-3d-wrap:active { cursor: grabbing; }

/* Grille décorative */
.viewer-grid {
  position: absolute; inset: 0;
  background-image:
    linear-gradient(rgba(245,158,11,.08) 1px, transparent 1px),
    linear-gradient(90deg, rgba(245,158,11,.08) 1px, transparent 1px);
  background-size: 40px 40px;
  animation: gridMove 8s linear infinite;
}
@keyframes gridMove {
  0%   { background-position: 0 0; }
  100% { background-position: 40px 40px; }
}

/* Halo orange */
.viewer-halo {
  position: absolute; width: 300px; height: 300px;
  background: radial-gradient(circle, rgba(245,158,11,.15) 0%, transparent 70%);
  top: 50%; left: 50%; transform: translate(-50%,-50%);
  animation: pulse 3s ease-in-out infinite;
}
@keyframes pulse {
  0%,100% { transform: translate(-50%,-50%) scale(1); opacity: .6; }
  50%      { transform: translate(-50%,-50%) scale(1.3); opacity: 1; }
}

/* Stage 3D */
.stage-3d {
  position: absolute; inset: 0;
  display: flex; align-items: center; justify-content: center;
  transform-style: preserve-3d;
  transition: transform .05s linear;
}

/* Image principale */
.main-img {
  width: 340px; height: 280px;
  object-fit: contain; border-radius: 12px;
  filter: drop-shadow(0 20px 40px rgba(0,0,0,.5));
  transform: translateZ(40px);
  transition: opacity .4s ease;
  user-select: none; pointer-events: none;
}
.main-img.loading { opacity: 0; }

/* Badge status */
.status-badge-3d {
  position: absolute; top: 20px; left: 20px;
  padding: 6px 16px; border-radius: 100px;
  font-size: 11px; font-weight: 800; letter-spacing: 1px;
  text-transform: uppercase; backdrop-filter: blur(8px);
}
.status-available { background: rgba(16,185,129,.2); color: #10b981; border: 1px solid rgba(16,185,129,.3); }
.status-rented    { background: rgba(245,158,11,.2);  color: #F59E0B;  border: 1px solid rgba(245,158,11,.3); }
.status-maintenance { background: rgba(239,68,68,.2); color: #ef4444; border: 1px solid rgba(239,68,68,.3); }

/* Compteur rotation */
.rotation-hint {
  position: absolute; bottom: 16px; right: 16px;
  background: rgba(255,255,255,.1); backdrop-filter: blur(8px);
  border: 1px solid rgba(255,255,255,.15);
  color: rgba(255,255,255,.7); font-size: 11px;
  padding: 6px 12px; border-radius: 100px;
  display: flex; align-items: center; gap: 6px;
}

/* Flèches nav */
.viewer-arrow {
  position: absolute; top: 50%; transform: translateY(-50%);
  width: 40px; height: 40px; border-radius: 50%;
  background: rgba(255,255,255,.12); backdrop-filter: blur(8px);
  border: 1px solid rgba(255,255,255,.2);
  color: white; font-size: 16px; cursor: pointer;
  display: flex; align-items: center; justify-content: center;
  transition: all .2s; z-index: 5;
}
.viewer-arrow:hover { background: var(--orange); border-color: var(--orange); }
.viewer-arrow.prev { left: 16px; }
.viewer-arrow.next { right: 16px; }

/* Thumbnails */
.viewer-thumbs {
  display: flex; gap: 10px; padding: 14px 16px;
  overflow-x: auto; background: #fafafa;
  border-top: 1px solid var(--border);
}
.thumb {
  width: 72px; height: 56px; border-radius: 10px;
  object-fit: cover; cursor: pointer; flex-shrink: 0;
  border: 2px solid transparent;
  transition: all .2s; opacity: .6;
}
.thumb.active { border-color: var(--orange); opacity: 1; }
.thumb-placeholder {
  width: 72px; height: 56px; border-radius: 10px;
  background: var(--navy); border: 2px solid transparent;
  cursor: pointer; flex-shrink: 0; opacity: .6;
  display: flex; align-items: center; justify-content: center;
  color: rgba(255,255,255,.3); font-size: 20px;
  transition: all .2s;
}
.thumb-placeholder.active { border-color: var(--orange); opacity: 1; }

/* ═══════════════════════════════════════════════
   INFOS MACHINE
═══════════════════════════════════════════════ */
.machine-info { padding: 24px; }

.machine-badges { display: flex; gap: 8px; flex-wrap: wrap; margin-bottom: 12px; }
.badge-type {
  background: rgba(245,158,11,.1); color: var(--orange);
  font-size: 10px; font-weight: 800; padding: 4px 12px;
  border-radius: 100px; letter-spacing: 1px; text-transform: uppercase;
}
.badge-city {
  background: #f1f5f9; color: var(--gray);
  font-size: 11px; font-weight: 600; padding: 4px 12px;
  border-radius: 100px; display: flex; align-items: center; gap: 4px;
}

.machine-title {
  font-size: 26px; font-weight: 900; color: var(--navy);
  letter-spacing: -.5px; line-height: 1.2; margin-bottom: 8px;
}

.machine-rating { display: flex; align-items: center; gap: 8px; margin-bottom: 16px; }
.stars { color: var(--orange); font-size: 14px; }
.rating-val { font-weight: 800; color: var(--navy); font-size: 14px; }
.rating-count { color: var(--gray); font-size: 13px; }

.machine-desc {
  color: var(--gray); font-size: 14px; line-height: 1.7;
  margin-bottom: 20px; padding-bottom: 20px;
  border-bottom: 1px solid var(--border);
}

/* Specs grid */
.specs-grid {
  display: grid; grid-template-columns: repeat(4, 1fr);
  gap: 12px; margin-bottom: 20px;
}
.spec-card {
  background: #f8fafc; border-radius: 12px; padding: 14px 12px;
  text-align: center;
}
.spec-icon { font-size: 20px; margin-bottom: 6px; }
.spec-val { font-size: 13px; font-weight: 800; color: var(--navy); }
.spec-label { font-size: 10px; color: var(--gray); text-transform: uppercase; letter-spacing: .5px; margin-top: 2px; }

/* Prix section */
.price-section {
  display: flex; align-items: center; gap: 20px;
  padding: 16px; background: var(--navy); border-radius: 14px;
  margin-bottom: 20px;
}
.price-item { text-align: center; flex: 1; }
.price-label { font-size: 10px; color: rgba(255,255,255,.5); text-transform: uppercase; letter-spacing: .8px; margin-bottom: 4px; }
.price-val { font-size: 22px; font-weight: 900; color: var(--orange); }
.price-unit { font-size: 11px; color: rgba(255,255,255,.4); margin-top: 2px; }
.price-sep { width: 1px; height: 40px; background: rgba(255,255,255,.1); }

/* ═══════════════════════════════════════════════
   BOOKING SIDEBAR
═══════════════════════════════════════════════ */
.booking-card {
  background: #fff; border-radius: var(--radius);
  box-shadow: var(--shadow); overflow: hidden;
  position: sticky; top: 90px;
}

.booking-header {
  background: linear-gradient(135deg, var(--navy), var(--navy2));
  padding: 20px 22px;
}
.booking-price-main { font-size: 28px; font-weight: 900; color: var(--orange); }
.booking-price-sub  { font-size: 12px; color: rgba(255,255,255,.5); margin-top: 2px; }

.booking-body { padding: 20px 22px; }

.form-group { margin-bottom: 16px; }
.form-label-custom {
  display: block; font-size: 11px; font-weight: 700;
  color: var(--gray); text-transform: uppercase;
  letter-spacing: .8px; margin-bottom: 6px;
}
.form-control-custom {
  width: 100%; padding: 10px 14px; border-radius: 10px;
  border: 1.5px solid var(--border); font-size: 14px;
  color: var(--navy); background: #fff; outline: none;
  transition: border-color .2s;
}
.form-control-custom:focus { border-color: var(--orange); }

/* Mode location toggle */
.mode-toggle {
  display: grid; grid-template-columns: 1fr 1fr;
  gap: 6px; background: #f1f5f9; border-radius: 10px; padding: 4px;
  margin-bottom: 16px;
}
.mode-btn {
  padding: 8px; border: none; border-radius: 8px;
  font-size: 12px; font-weight: 700; cursor: pointer;
  background: transparent; color: var(--gray); transition: all .2s;
}
.mode-btn.active { background: #fff; color: var(--navy); box-shadow: 0 1px 4px rgba(0,0,0,.1); }

/* Counter durée */
.duration-ctrl {
  display: flex; align-items: center; gap: 0;
  border: 1.5px solid var(--border); border-radius: 10px; overflow: hidden;
}
.duration-btn {
  width: 42px; height: 42px; border: none; background: #f8fafc;
  font-size: 18px; cursor: pointer; color: var(--navy);
  transition: background .2s; flex-shrink: 0;
}
.duration-btn:hover { background: var(--orange); color: white; }
.duration-input {
  flex: 1; text-align: center; border: none; outline: none;
  font-size: 16px; font-weight: 800; color: var(--navy);
  background: white;
}

/* Récap prix */
.price-recap {
  background: #f8fafc; border-radius: 12px; padding: 14px;
  margin-bottom: 16px;
}
.price-line {
  display: flex; justify-content: space-between;
  font-size: 13px; color: var(--gray); margin-bottom: 8px;
}
.price-line.total {
  font-size: 15px; font-weight: 800; color: var(--navy);
  padding-top: 8px; border-top: 1px solid var(--border);
  margin-bottom: 0;
}
.price-line.total span:last-child { color: var(--orange); }

/* CTA buttons */
.btn-reserver {
  width: 100%; padding: 14px; border-radius: 12px;
  background: var(--orange); border: none; color: white;
  font-size: 15px; font-weight: 800; cursor: pointer;
  transition: all .2s; margin-bottom: 10px;
  display: flex; align-items: center; justify-content: center; gap: 8px;
}
.btn-reserver:hover { background: var(--orange2); transform: translateY(-1px); box-shadow: 0 6px 20px rgba(245,158,11,.4); }
.btn-devis {
  width: 100%; padding: 12px; border-radius: 12px;
  background: transparent; border: 1.5px solid var(--border);
  color: var(--navy); font-size: 14px; font-weight: 700;
  cursor: pointer; transition: all .2s;
  display: flex; align-items: center; justify-content: center; gap: 8px;
}
.btn-devis:hover { border-color: var(--navy); background: #f8fafc; }

/* Owner card */
.owner-section {
  border-top: 1px solid var(--border); padding: 16px 22px;
}
.owner-row { display: flex; align-items: center; gap: 12px; margin-bottom: 12px; }
.owner-avatar {
  width: 44px; height: 44px; border-radius: 50%;
  background: var(--orange); display: flex; align-items: center;
  justify-content: center; font-size: 16px; font-weight: 800; color: white;
  flex-shrink: 0;
}
.owner-name { font-size: 14px; font-weight: 800; color: var(--navy); }
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
  color: var(--navy); font-size: 13px; font-weight: 700;
  cursor: pointer; display: flex; align-items: center; justify-content: center; gap: 6px;
  transition: all .2s;
}
.btn-appeler:hover { border-color: var(--navy); }

/* ═══════════════════════════════════════════════
   CALENDRIER DISPO
═══════════════════════════════════════════════ */
.section-card {
  background: #fff; border-radius: var(--radius);
  box-shadow: var(--shadow); padding: 24px; margin-top: 20px;
}
.section-title {
  font-size: 18px; font-weight: 900; color: var(--navy);
  margin-bottom: 18px; display: flex; align-items: center; gap: 8px;
}
.section-title i { color: var(--orange); }

/* ═══════════════════════════════════════════════
   RATINGS
═══════════════════════════════════════════════ */
.rating-summary {
  display: flex; gap: 20px; align-items: center;
  padding: 20px; background: var(--navy); border-radius: 14px;
  margin-bottom: 20px;
}
.rating-big { font-size: 48px; font-weight: 900; color: var(--orange); line-height: 1; }
.rating-stars-big { color: var(--orange); font-size: 18px; letter-spacing: 2px; }
.rating-total { font-size: 13px; color: rgba(255,255,255,.5); margin-top: 4px; }

.review-card {
  padding: 16px; border: 1px solid var(--border); border-radius: 12px;
  margin-bottom: 12px; transition: box-shadow .2s;
}
.review-card:hover { box-shadow: 0 2px 12px rgba(0,0,0,.06); }
.review-header { display: flex; justify-content: space-between; align-items: flex-start; margin-bottom: 8px; }
.reviewer-name { font-weight: 700; color: var(--navy); font-size: 14px; }
.reviewer-date { font-size: 11px; color: var(--gray); }
.review-stars { color: var(--orange); font-size: 12px; margin-bottom: 6px; }
.review-text { font-size: 13px; color: var(--gray); line-height: 1.6; }

/* ═══════════════════════════════════════════════
   TOAST
═══════════════════════════════════════════════ */
#showToast {
  position: fixed; bottom: 24px; right: 24px; z-index: 9999;
  padding: 14px 20px; border-radius: 14px;
  color: white; font-weight: 700; font-size: 14px;
  box-shadow: 0 8px 30px rgba(0,0,0,.2);
  transform: translateY(20px); opacity: 0;
  transition: all .3s ease; pointer-events: none;
}
#showToast.visible { transform: translateY(0); opacity: 1; }

/* ═══════════════════════════════════════════════
   RESPONSIVE
═══════════════════════════════════════════════ */
@media (max-width: 1024px) {
  .show-wrap { grid-template-columns: 1fr; }
  .booking-card { position: static; }
  .specs-grid { grid-template-columns: repeat(2,1fr); }
}
@media (max-width: 600px) {
  .show-wrap { padding: 12px; }
  .viewer-3d-wrap { height: 280px; }
  .main-img { width: 220px; height: 180px; }
}


.btn-reserver {
  width: 100%; padding: 14px; border-radius: 12px;
  background: var(--orange); border: none; color: white;
  ...
}
.btn-reserver:hover { background: var(--orange2); transform: translateY(-1px); box-shadow: 0 6px 20px rgba(245,158,11,.4); }

</style>
@endpush

@section('content')

{{-- BREADCRUMB --}}
<div style="background:#f8fafc;border-bottom:1px solid var(--border,#e2e8f0);padding:12px 24px">
  <div style="max-width:1280px;margin:0 auto;font-size:13px;color:#6b7280">
    <a href="/" style="color:inherit;text-decoration:none">Accueil</a>
    <span style="margin:0 8px">›</span>
    <a href="/machines" style="color:inherit;text-decoration:none">Catalogue</a>
    <span style="margin:0 8px">›</span>
    <span id="breadcrumb-name" style="color:#0F1B2D;font-weight:600">Chargement...</span>
  </div>
</div>

<div class="show-wrap" id="showWrap" style="opacity:0;transition:opacity .4s">

  {{-- COL GAUCHE --}}
  <div>

    {{-- VIEWER 3D --}}
    <div class="viewer-section">
      <div class="viewer-3d-wrap" id="viewer3d">
        <div class="viewer-grid"></div>
        <div class="viewer-halo"></div>

        <div class="stage-3d" id="stage3d">
          <img id="mainImg" class="main-img loading"
               src="/img/machines/default.png"
               onerror="this.src='https://placehold.co/340x280/0F1B2D/F59E0B?text=Machine'"
               alt="machine">
        </div>

        <div class="status-badge-3d status-available" id="statusBadge">Disponible</div>

        <button class="viewer-arrow prev" onclick="prevImg()">‹</button>
        <button class="viewer-arrow next" onclick="nextImg()">›</button>

        <div class="rotation-hint">
          <i class="fas fa-arrows-alt"></i> Glisser pour tourner
        </div>
      </div>

      <div class="viewer-thumbs" id="thumbsRow">
        {{-- Thumbnails injectés par JS --}}
      </div>
    </div>

    {{-- INFOS --}}
    <div class="machine-info" id="machineInfo">
      <div class="machine-badges">
        <span class="badge-type" id="machineType">—</span>
        <span class="badge-city"><i class="fas fa-map-marker-alt"></i> <span id="machineCity">—</span></span>
      </div>
      <h1 class="machine-title" id="machineName">Chargement...</h1>
      <div class="machine-rating">
        <div class="stars" id="machineStars">★★★★★</div>
        <span class="rating-val" id="machineRatingVal">—</span>
        <span class="rating-count" id="machineRatingCount">(0 avis)</span>
      </div>
      <p class="machine-desc" id="machineDesc">—</p>

      {{-- SPECS --}}
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

      {{-- PRIX --}}
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

    {{-- CALENDRIER --}}
    <div class="section-card">
      <div class="section-title">
        <i class="fas fa-calendar-alt"></i> Calendrier de disponibilité
      </div>
      <div id="calendarWrap">
        <div style="text-align:center;padding:30px;color:#6b7280">
          <div class="spinner-border text-warning spinner-border-sm me-2"></div>
          Chargement du calendrier...
        </div>
      </div>
    </div>

    {{-- AVIS --}}
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
        <div style="flex:1;padding-left:20px" id="ratingBars">
          {{-- Barres générées par JS --}}
        </div>
      </div>
      <div id="reviewsList">
        <div style="text-align:center;padding:20px;color:#6b7280">Aucun avis pour le moment</div>
      </div>
      <button class="btn-reserver mt-3" style="background:transparent;border:1.5px solid var(--orange);color:var(--orange)"
              onclick="showReviewForm()">
        <i class="fas fa-star"></i> Laisser un avis
      </button>
      <div id="reviewFormWrap" style="display:none;margin-top:16px">
        <div style="display:flex;gap:8px;margin-bottom:12px" id="starPicker">
          <span style="font-size:11px;color:#6b7280;align-self:center">Note :</span>
          <span class="star-pick" data-v="1" onclick="pickStar(1)" style="font-size:24px;cursor:pointer;color:#d1d5db">★</span>
          <span class="star-pick" data-v="2" onclick="pickStar(2)" style="font-size:24px;cursor:pointer;color:#d1d5db">★</span>
          <span class="star-pick" data-v="3" onclick="pickStar(3)" style="font-size:24px;cursor:pointer;color:#d1d5db">★</span>
          <span class="star-pick" data-v="4" onclick="pickStar(4)" style="font-size:24px;cursor:pointer;color:#d1d5db">★</span>
          <span class="star-pick" data-v="5" onclick="pickStar(5)" style="font-size:24px;cursor:pointer;color:#d1d5db">★</span>
        </div>
        <textarea id="reviewText" class="form-control-custom" rows="3" placeholder="Partagez votre expérience..."></textarea>
        <button class="btn-reserver mt-2" onclick="submitReview()" style="width:auto;padding:10px 24px">
          <i class="fas fa-paper-plane"></i> Envoyer
        </button>
      </div>
    </div>

  </div>{{-- fin col gauche --}}

  {{-- SIDEBAR BOOKING --}}
  <div>
    <div class="booking-card">
      <div class="booking-header">
        <div class="booking-price-main" id="bookingPrice">— DH</div>
        <div class="booking-price-sub">par jour · <span id="bookingPriceHour">—</span> DH/heure</div>
      </div>

      <div class="booking-body">
        {{-- Mode --}}
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

        {{-- Date début --}}
        <div class="form-group">
          <label class="form-label-custom">Date de début</label>
          <input type="date" id="startDate" class="form-control-custom"
                 onchange="calcTotal()" min="{{ date('Y-m-d') }}">
        </div>

        {{-- Durée --}}
        <div class="form-group">
          <label class="form-label-custom" id="durationLabel">Durée (jours)</label>
          <div class="duration-ctrl">
            <button class="duration-btn" onclick="changeDuration(-1)">−</button>
            <input type="number" id="durationVal" class="duration-input" value="1" min="1" onchange="calcTotal()">
            <button class="duration-btn" onclick="changeDuration(1)">+</button>
          </div>
        </div>

        {{-- Récap --}}
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

        <button onclick="reserver()"
  style="width:100%;padding:14px;border-radius:12px;background:#0F1B2D;color:#F59E0B;border:1.5px solid #F59E0B;font-size:15px;font-weight:800;cursor:pointer;margin-bottom:10px;display:flex;align-items:center;justify-content:center;gap:8px;transition:all .2s;"
  onmouseover="this.style.background='#F59E0B';this.style.color='#0F1B2D'"
  onmouseout="this.style.background='#0F1B2D';this.style.color='#F59E0B'">
  <i class="fas fa-paper-plane"></i> Envoyer une demande
</button>
        <button class="btn-devis" onclick="devis()">
          <i class="fas fa-file-alt"></i> Devis rapide
        </button>
      </div>

      {{-- Owner --}}
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
  </div>

</div>

<div id="showToast"></div>
@endsection

@push('scripts')
<script>
// ═══════════════════════════════════════════════
//  STATE
// ═══════════════════════════════════════════════
var machineId   = window.location.pathname.split('/').pop();
var machine     = null;
var currentMode = 'jour';
var currentImg  = 0;
var selectedStar= 0;
var images      = [];

// ═══════════════════════════════════════════════
//  INIT
// ═══════════════════════════════════════════════
document.addEventListener('DOMContentLoaded', function() {
  loadMachine();
  init3DViewer();
  document.getElementById('startDate').value = new Date().toISOString().split('T')[0];
});

// ═══════════════════════════════════════════════
//  LOAD MACHINE
// ═══════════════════════════════════════════════
async function loadMachine() {
  try {
    var token = localStorage.getItem('auth_token') || localStorage.getItem('token') || '';
    var headers = { 'Accept': 'application/json' };
    if (token) headers['Authorization'] = 'Bearer ' + token;

    var r = await fetch('/api/machines/' + machineId, { headers: headers });
    var json = await r.json();
    machine = (json && json.data) ? json.data : json;

    if (!machine || !machine.id) {
      document.getElementById('machineName').textContent = 'Machine introuvable';
      return;
    }

    renderMachine();
    loadRatings();
    loadCalendar();
    document.getElementById('showWrap').style.opacity = '1';

  } catch(e) {
    console.error('loadMachine:', e);
    showToast('Erreur de chargement', 'danger');
  }
}

// ═══════════════════════════════════════════════
//  RENDER MACHINE
// ═══════════════════════════════════════════════
function renderMachine() {
  var m = machine;

  // Breadcrumb
  document.getElementById('breadcrumb-name').textContent = m.name || '—';

  // Badges & titre
  document.getElementById('machineType').textContent    = m.type     || 'Machine';
  document.getElementById('machineCity').textContent    = m.city     || '—';
  document.getElementById('machineName').textContent    = m.name     || '—';
  document.getElementById('machineDesc').textContent    = m.description || 'Aucune description disponible.';

  // Specs
  document.getElementById('specType').textContent  = m.type             || '—';
  document.getElementById('specCity').textContent  = m.city             || '—';
  document.getElementById('specDay').textContent   = fmt(m.price_per_day)  + ' DH';
  document.getElementById('specHour').textContent  = fmt(m.price_per_hour) + ' DH';

  // Prix section
  document.getElementById('priceDay').textContent   = fmt(m.price_per_day)  + ' DH';
  document.getElementById('priceHour').textContent  = fmt(m.price_per_hour) + ' DH';

  // Status
  var statusLabels = { available:'Disponible', rented:'Loué', maintenance:'Maintenance' };
  var statusColors = { available:'#10b981',    rented:'#F59E0B', maintenance:'#ef4444' };
  var st = m.status || 'available';
  document.getElementById('priceStatus').textContent      = statusLabels[st] || st;
  document.getElementById('priceStatus').style.color      = statusColors[st] || '#fff';
  document.getElementById('statusBadge').textContent      = statusLabels[st] || st;
  document.getElementById('statusBadge').className        = 'status-badge-3d status-' + st;

  // Booking sidebar
  document.getElementById('bookingPrice').textContent     = fmt(m.price_per_day)  + ' DH';
  document.getElementById('bookingPriceHour').textContent = fmt(m.price_per_hour);

  // Owner
  var ownerName = m.owner ? (m.owner.name || 'Propriétaire') : 'Propriétaire';
  document.getElementById('ownerName').textContent = ownerName;
  document.getElementById('ownerAvatar').textContent = ownerName.charAt(0).toUpperCase();

  // Images
  images = [];
  if (m.images && m.images.length) {
    m.images.forEach(function(img) {
      images.push('/storage/' + (img.path || img.image || img));
    });
  }
  if (!images.length) {
    images.push('https://placehold.co/340x280/0F1B2D/F59E0B?text=' + encodeURIComponent(m.name || 'Machine'));
  }
  renderImages();
  calcTotal();
}

// ═══════════════════════════════════════════════
//  IMAGES
// ═══════════════════════════════════════════════
function renderImages() {
  var img   = document.getElementById('mainImg');
  var thumbs = document.getElementById('thumbsRow');

  img.src = images[currentImg];
  img.classList.remove('loading');

  thumbs.innerHTML = '';
  images.forEach(function(src, i) {
    var el = document.createElement('img');
    el.src = src;
    el.className = 'thumb' + (i === currentImg ? ' active' : '');
    el.onerror  = function() { this.src = 'https://placehold.co/72x56/0F1B2D/F59E0B?text=img'; };
    el.onclick  = function() { goImg(i); };
    thumbs.appendChild(el);
  });
}

function goImg(i) {
  currentImg = i;
  var img = document.getElementById('mainImg');
  img.classList.add('loading');
  setTimeout(function() {
    img.src = images[i];
    img.classList.remove('loading');
  }, 200);
  document.querySelectorAll('.thumb').forEach(function(t, j) {
    t.classList.toggle('active', j === i);
  });
}
function prevImg() { goImg((currentImg - 1 + images.length) % images.length); }
function nextImg() { goImg((currentImg + 1) % images.length); }

// ═══════════════════════════════════════════════
//  3D DRAG EFFECT
// ═══════════════════════════════════════════════
function init3DViewer() {
  var viewer = document.getElementById('viewer3d');
  var stage  = document.getElementById('stage3d');
  var isDragging = false, startX = 0, startY = 0, rotX = 0, rotY = 0;

  viewer.addEventListener('mousedown', function(e) {
    isDragging = true; startX = e.clientX; startY = e.clientY;
  });
  document.addEventListener('mousemove', function(e) {
    if (!isDragging) return;
    var dx = (e.clientX - startX) * 0.3;
    var dy = (e.clientY - startY) * 0.15;
    rotY = Math.max(-25, Math.min(25, dx));
    rotX = Math.max(-15, Math.min(15, -dy));
    stage.style.transform = 'rotateX(' + rotX + 'deg) rotateY(' + rotY + 'deg)';
  });
  document.addEventListener('mouseup', function() {
    if (!isDragging) return;
    isDragging = false;
    var int = setInterval(function() {
      rotX *= 0.85; rotY *= 0.85;
      stage.style.transform = 'rotateX(' + rotX + 'deg) rotateY(' + rotY + 'deg)';
      if (Math.abs(rotX) < 0.1 && Math.abs(rotY) < 0.1) clearInterval(int);
    }, 16);
  });

  // Touch support
  viewer.addEventListener('touchstart', function(e) {
    startX = e.touches[0].clientX; startY = e.touches[0].clientY; isDragging = true;
  }, { passive: true });
  viewer.addEventListener('touchmove', function(e) {
    if (!isDragging) return;
    var dx = (e.touches[0].clientX - startX) * 0.3;
    rotY = Math.max(-25, Math.min(25, dx));
    stage.style.transform = 'rotateY(' + rotY + 'deg)';
  }, { passive: true });
  viewer.addEventListener('touchend', function() { isDragging = false; });
}

// ═══════════════════════════════════════════════
//  MODE & CALCUL
// ═══════════════════════════════════════════════
function setMode(mode, btn) {
  currentMode = mode;
  document.querySelectorAll('.mode-btn').forEach(function(b) { b.classList.remove('active'); });
  btn.classList.add('active');
  document.getElementById('durationLabel').textContent = mode === 'jour' ? 'Durée (jours)' : 'Durée (heures)';
  calcTotal();
}

function changeDuration(d) {
  var inp = document.getElementById('durationVal');
  inp.value = Math.max(1, parseInt(inp.value || 1) + d);
  calcTotal();
}

function calcTotal() {
  if (!machine) return;
  var dur  = parseInt(document.getElementById('durationVal').value) || 1;
  var rate = currentMode === 'jour'
    ? (machine.price_per_day  || 0)
    : (machine.price_per_hour || 0);

  var base  = rate * dur;
  var fee   = Math.round(base * 0.05);
  var total = base + fee;
  var unit  = currentMode === 'jour' ? 'jour' : 'heure';

  document.getElementById('recapLabel').textContent = fmt(rate) + ' DH × ' + dur + ' ' + unit + (dur > 1 ? 's' : '');
  document.getElementById('recapSub').textContent   = fmt(base)  + ' DH';
  document.getElementById('recapFee').textContent   = fmt(fee)   + ' DH';
  document.getElementById('recapTotal').textContent = fmt(total) + ' DH';
}

// ═══════════════════════════════════════════════
//  RÉSERVER
// ═══════════════════════════════════════════════
async function reserver() {
  var token = localStorage.getItem('auth_token') || localStorage.getItem('token');
  if (!token) { window.location.href = '/login'; return; }

  var start = document.getElementById('startDate').value;
  var dur   = parseInt(document.getElementById('durationVal').value) || 1;
  if (!start) { showToast('Choisissez une date de début', 'warning'); return; }

  var end = new Date(start);
  if (currentMode === 'jour') {
    end.setDate(end.getDate() + dur);
  } else {
    end = new Date(start);
  }
  var endStr = end.toISOString().split('T')[0];

  try {
    var r = await fetch('/api/reservations', {
      method: 'POST',
      headers: {
        'Content-Type': 'application/json',
        'Authorization': 'Bearer ' + token
      },
      body: JSON.stringify({
        machine_id: machineId,
        start_date: start,
        end_date:   endStr
      })
    });
    var json = await r.json();
    if (r.ok) {
      showToast('✅ Demande envoyée avec succès !', 'success');
    } else {
      showToast('❌ ' + (json.message || 'Erreur'), 'danger');
    }
  } catch(e) {
    showToast('❌ Erreur réseau', 'danger');
  }
}

function devis() {
  showToast('📄 Fonctionnalité devis bientôt disponible', 'info');
}

// ═══════════════════════════════════════════════
//  OWNER ACTIONS
// ═══════════════════════════════════════════════
function openWhatsApp() {
  var phone = machine && machine.owner ? (machine.owner.phone || '') : '';
  phone = phone.replace(/\s/g,'').replace(/^\+/, '');
  if (!phone) { showToast('Numéro non disponible', 'warning'); return; }
  var msg = encodeURIComponent('Bonjour, je suis intéressé par la machine "' + (machine.name||'') + '" sur Rentify.');
  window.open('https://wa.me/' + phone + '?text=' + msg, '_blank');
}

function appeler() {
  var phone = machine && machine.owner ? (machine.owner.phone || '') : '';
  if (!phone) { showToast('Numéro non disponible', 'warning'); return; }
  window.location.href = 'tel:' + phone;
}

// ═══════════════════════════════════════════════
//  RATINGS
// ═══════════════════════════════════════════════
async function loadRatings() {
  try {
    var r = await fetch('/api/machines/' + machineId + '/ratings');
    if (!r.ok) return;
    var json = await r.json();
    var ratings = (json && json.data) ? json.data : (Array.isArray(json) ? json : []);
    renderRatings(ratings);
  } catch(e) {}
}

function renderRatings(ratings) {
  if (!ratings.length) return;

  var avg = ratings.reduce(function(s, r) { return s + (r.rating || r.note || 0); }, 0) / ratings.length;
  avg = Math.round(avg * 10) / 10;

  document.getElementById('avgRating').textContent   = avg;
  document.getElementById('avgStars').textContent    = starsStr(avg);
  document.getElementById('ratingTotal').textContent = ratings.length + ' avis vérifiés';

  // Barres
  var bars = '';
  for (var s = 5; s >= 1; s--) {
    var count = ratings.filter(function(r) { return Math.round(r.rating||r.note||0) === s; }).length;
    var pct   = Math.round(count / ratings.length * 100);
    bars += '<div style="display:flex;align-items:center;gap:8px;margin-bottom:6px">' +
      '<span style="font-size:11px;color:rgba(255,255,255,.5);width:12px">' + s + '</span>' +
      '<div style="flex:1;height:6px;background:rgba(255,255,255,.1);border-radius:3px;overflow:hidden">' +
        '<div style="width:' + pct + '%;height:100%;background:#F59E0B;border-radius:3px"></div>' +
      '</div>' +
      '<span style="font-size:11px;color:rgba(255,255,255,.4);width:28px">' + pct + '%</span>' +
      '</div>';
  }
  document.getElementById('ratingBars').innerHTML = bars;

  // Reviews
  var html = '';
  ratings.slice(0, 5).forEach(function(r) {
    var name  = r.user ? (r.user.name || 'Anonyme') : 'Client Rentify';
    var note  = r.rating || r.note || 5;
    var text  = r.comment || r.commentaire || '';
    var date  = r.created_at ? new Date(r.created_at).toLocaleDateString('fr-FR') : '';
    html += '<div class="review-card">' +
      '<div class="review-header">' +
        '<div class="reviewer-name">' + name + '</div>' +
        '<div class="reviewer-date">' + date + '</div>' +
      '</div>' +
      '<div class="review-stars">' + starsStr(note) + '</div>' +
      (text ? '<div class="review-text">' + text + '</div>' : '') +
      '</div>';
  });
  document.getElementById('reviewsList').innerHTML = html || '<p style="color:#6b7280;text-align:center;padding:16px">Aucun avis</p>';

  // Mise à jour header machine
  document.getElementById('machineRatingVal').textContent   = avg;
  document.getElementById('machineStars').textContent       = starsStr(avg);
  document.getElementById('machineRatingCount').textContent = '(' + ratings.length + ' avis)';
}

// ═══════════════════════════════════════════════
//  REVIEW FORM
// ═══════════════════════════════════════════════
function showReviewForm() {
  var token = localStorage.getItem('auth_token') || localStorage.getItem('token');
  if (!token) { window.location.href = '/login'; return; }
  var wrap = document.getElementById('reviewFormWrap');
  wrap.style.display = wrap.style.display === 'none' ? 'block' : 'none';
}

function pickStar(val) {
  selectedStar = val;
  document.querySelectorAll('.star-pick').forEach(function(s) {
    s.style.color = parseInt(s.dataset.v) <= val ? '#F59E0B' : '#d1d5db';
  });
}

async function submitReview() {
  var token = localStorage.getItem('auth_token') || localStorage.getItem('token');
  if (!selectedStar) { showToast('Choisissez une note', 'warning'); return; }
  var text = document.getElementById('reviewText').value.trim();
  try {
    var r = await fetch('/api/machines/' + machineId + '/ratings', {
      method: 'POST',
      headers: { 'Content-Type': 'application/json', 'Authorization': 'Bearer ' + token },
      body: JSON.stringify({ machine_id: machineId, rating: selectedStar, comment: text })
    });
    var json = await r.json();
    if (r.ok) {
      showToast('✅ Avis envoyé, merci !', 'success');
      document.getElementById('reviewFormWrap').style.display = 'none';
      loadRatings();
    } else {
      showToast('❌ ' + (json.message || 'Erreur'), 'danger');
    }
  } catch(e) { showToast('Erreur réseau', 'danger'); }
}

// ═══════════════════════════════════════════════
//  CALENDRIER
// ═══════════════════════════════════════════════
async function loadCalendar() {
  // Calendrier simple — mois actuel
  var now      = new Date();
  var year     = now.getFullYear();
  var month    = now.getMonth();
  renderCalendar(year, month, []);

  // Essayer de charger les réservations
  try {
    var r = await fetch('/api/machines/' + machineId + '/availability');
    if (r.ok) {
      var json = await r.json();
      var reserved = json.data || json || [];
      renderCalendar(year, month, reserved);
    }
  } catch(e) {}
}

function renderCalendar(year, month, reserved) {
  var names   = ['Dim','Lun','Mar','Mer','Jeu','Ven','Sam'];
  var mnames  = ['Janvier','Février','Mars','Avril','Mai','Juin','Juillet','Août','Septembre','Octobre','Novembre','Décembre'];
  var first   = new Date(year, month, 1).getDay();
  var days    = new Date(year, month + 1, 0).getDate();
  var today   = new Date().getDate();
  var curMonth= new Date().getMonth();
  var curYear = new Date().getFullYear();

  var html = '<div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:16px">' +
    '<button onclick="changeMonth(-1)" style="border:none;background:#f1f5f9;border-radius:8px;width:32px;height:32px;cursor:pointer">‹</button>' +
    '<strong style="color:#0F1B2D">' + mnames[month] + ' ' + year + '</strong>' +
    '<button onclick="changeMonth(1)"  style="border:none;background:#f1f5f9;border-radius:8px;width:32px;height:32px;cursor:pointer">›</button>' +
    '</div>' +
    '<div style="display:grid;grid-template-columns:repeat(7,1fr);gap:4px;text-align:center">';

  names.forEach(function(n) {
    html += '<div style="font-size:10px;font-weight:700;color:#6b7280;padding:6px 0">' + n + '</div>';
  });

  for (var i = 0; i < first; i++) html += '<div></div>';

  for (var d = 1; d <= days; d++) {
    var isToday   = (d === today && month === curMonth && year === curYear);
    var isPast    = (new Date(year, month, d) < new Date(curYear, curMonth, today));
    var isReserved= reserved.includes(year + '-' + pad(month+1) + '-' + pad(d));

    var bg  = isReserved ? '#FEE2E2' : (isToday ? '#F59E0B' : (isPast ? '#f9fafb' : '#f0fdf4'));
    var col = isReserved ? '#991B1B' : (isToday ? 'white'   : (isPast ? '#d1d5db' : '#0F1B2D'));
    var fw  = isToday ? '900' : '500';

    html += '<div style="background:' + bg + ';color:' + col + ';border-radius:8px;padding:8px 4px;font-size:12px;font-weight:' + fw + '">' + d + '</div>';
  }
  html += '</div>';
  html += '<div style="display:flex;gap:16px;margin-top:12px;font-size:11px;color:#6b7280">' +
    '<span><span style="display:inline-block;width:12px;height:12px;background:#f0fdf4;border-radius:3px;margin-right:4px"></span>Disponible</span>' +
    '<span><span style="display:inline-block;width:12px;height:12px;background:#FEE2E2;border-radius:3px;margin-right:4px"></span>Réservé</span>' +
    '</div>';

  document.getElementById('calendarWrap').innerHTML = html;
  window._calYear  = year;
  window._calMonth = month;
  window._calResv  = reserved;
}

function changeMonth(d) {
  var m = window._calMonth + d;
  var y = window._calYear;
  if (m < 0)  { m = 11; y--; }
  if (m > 11) { m = 0;  y++; }
  renderCalendar(y, m, window._calResv || []);
}

// ═══════════════════════════════════════════════
//  UTILS
// ═══════════════════════════════════════════════
function fmt(n) { return n ? parseInt(n).toLocaleString('fr') : '0'; }
function pad(n) { return n < 10 ? '0' + n : '' + n; }
function starsStr(n) {
  var full = Math.round(n); var s = '';
  for (var i = 1; i <= 5; i++) s += (i <= full ? '★' : '☆');
  return s;
}

var _toastT;
function showToast(msg, type) {
  var el = document.getElementById('showToast');
  var colors = { success:'#10b981', danger:'#ef4444', warning:'#F59E0B', info:'#3b82f6' };
  el.style.background = colors[type] || colors.info;
  el.textContent = msg;
  el.classList.add('visible');
  clearTimeout(_toastT);
  _toastT = setTimeout(function() { el.classList.remove('visible'); }, 3500);
}
</script>
@endpush