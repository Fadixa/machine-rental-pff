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
   VIEWER 3D (Three.js)
═══════════════════════════════════════════════ */
.viewer-section {
  background: #071018; border-radius: var(--radius);
  box-shadow: var(--shadow); overflow: hidden;
}
.v3d-wrap {
  position: relative; height: 420px;
  background: #071018; overflow: hidden;
  user-select: none;
}
@keyframes v3dspin { to { transform: rotate(360deg); } }
.v3d-hs {
  position: absolute; width: 22px; height: 22px; border-radius: 50%;
  background: #F59E0B; border: 2px solid #fff; cursor: pointer;
  display: flex; align-items: center; justify-content: center;
  font-size: 10px; font-weight: 800; color: #071018;
  pointer-events: all; animation: v3dpulse 2s ease-in-out infinite;
  transition: transform .15s;
}
.v3d-hs:hover { transform: scale(1.35); }
@keyframes v3dpulse {
  0%,100% { box-shadow: 0 0 0 0 rgba(245,158,11,.5); }
  50%     { box-shadow: 0 0 0 7px rgba(245,158,11,0); }
}

/* Status badge */
.status-badge-3d {
  position: absolute; top: 16px; left: 16px;
  padding: 5px 14px; border-radius: 100px;
  font-size: 11px; font-weight: 800; letter-spacing: 1px;
  text-transform: uppercase; z-index: 4;
}
.status-available   { background: rgba(16,185,129,.2);  color: #10b981; border: 1px solid rgba(16,185,129,.35); }
.status-rented      { background: rgba(245,158,11,.2);  color: #F59E0B; border: 1px solid rgba(245,158,11,.35); }
.status-maintenance { background: rgba(239,68,68,.2);   color: #ef4444; border: 1px solid rgba(239,68,68,.35); }

/* ═══════════════════════════════════════════════
   INFOS MACHINE
═══════════════════════════════════════════════ */
.machine-info { padding: 24px; background:#fff; border-radius:0 0 var(--radius) var(--radius); }
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

/* Specs */
.specs-grid {
  display: grid; grid-template-columns: repeat(4, 1fr);
  gap: 12px; margin-bottom: 20px;
}
.spec-card { background: #f8fafc; border-radius: 12px; padding: 14px 12px; text-align: center; }
.spec-icon { font-size: 20px; margin-bottom: 6px; }
.spec-val  { font-size: 13px; font-weight: 800; color: var(--navy); }
.spec-label { font-size: 10px; color: var(--gray); text-transform: uppercase; letter-spacing: .5px; margin-top: 2px; }

/* Prix section */
.price-section {
  display: flex; align-items: center; gap: 20px;
  padding: 16px; background: var(--navy); border-radius: 14px; margin-bottom: 20px;
}
.price-item { text-align: center; flex: 1; }
.price-label { font-size: 10px; color: rgba(255,255,255,.5); text-transform: uppercase; letter-spacing: .8px; margin-bottom: 4px; }
.price-val   { font-size: 22px; font-weight: 900; color: var(--orange); }
.price-unit  { font-size: 11px; color: rgba(255,255,255,.4); margin-top: 2px; }
.price-sep   { width: 1px; height: 40px; background: rgba(255,255,255,.1); }

/* ═══════════════════════════════════════════════
   BOOKING SIDEBAR
═══════════════════════════════════════════════ */
.booking-card {
  background: #fff; border-radius: var(--radius);
  box-shadow: var(--shadow); overflow: hidden;
  position: sticky; top: 90px;
}
.booking-header { background: linear-gradient(135deg, var(--navy), var(--navy2)); padding: 20px 22px; }
.booking-price-main { font-size: 28px; font-weight: 900; color: var(--orange); }
.booking-price-sub  { font-size: 12px; color: rgba(255,255,255,.5); margin-top: 2px; }
.booking-body { padding: 20px 22px; }
.form-group { margin-bottom: 16px; }
.form-label-custom {
  display: block; font-size: 11px; font-weight: 700;
  color: var(--gray); text-transform: uppercase; letter-spacing: .8px; margin-bottom: 6px;
}
.form-control-custom {
  width: 100%; padding: 10px 14px; border-radius: 10px;
  border: 1.5px solid var(--border); font-size: 14px;
  color: var(--navy); background: #fff; outline: none; transition: border-color .2s;
}
.form-control-custom:focus { border-color: var(--orange); }
.mode-toggle {
  display: grid; grid-template-columns: 1fr 1fr;
  gap: 6px; background: #f1f5f9; border-radius: 10px; padding: 4px; margin-bottom: 16px;
}
.mode-btn {
  padding: 8px; border: none; border-radius: 8px;
  font-size: 12px; font-weight: 700; cursor: pointer;
  background: transparent; color: var(--gray); transition: all .2s;
}
.mode-btn.active { background: #fff; color: var(--navy); box-shadow: 0 1px 4px rgba(0,0,0,.1); }
.duration-ctrl {
  display: flex; align-items: center;
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
  font-size: 16px; font-weight: 800; color: var(--navy); background: white;
}
.price-recap { background: #f8fafc; border-radius: 12px; padding: 14px; margin-bottom: 16px; }
.price-line {
  display: flex; justify-content: space-between;
  font-size: 13px; color: var(--gray); margin-bottom: 8px;
}
.price-line.total {
  font-size: 15px; font-weight: 800; color: var(--navy);
  padding-top: 8px; border-top: 1px solid var(--border); margin-bottom: 0;
}
.price-line.total span:last-child { color: var(--orange); }
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
.owner-section { border-top: 1px solid var(--border); padding: 16px 22px; }
.owner-row { display: flex; align-items: center; gap: 12px; margin-bottom: 12px; }
.owner-avatar {
  width: 44px; height: 44px; border-radius: 50%;
  background: var(--orange); display: flex; align-items: center;
  justify-content: center; font-size: 16px; font-weight: 800; color: white; flex-shrink: 0;
}
.owner-name  { font-size: 14px; font-weight: 800; color: var(--navy); }
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
   SECTION CARDS (calendrier, avis)
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
.rating-summary {
  display: flex; gap: 20px; align-items: center;
  padding: 20px; background: var(--navy); border-radius: 14px; margin-bottom: 20px;
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
.review-stars  { color: var(--orange); font-size: 12px; margin-bottom: 6px; }
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
}
@media (max-width: 600px) {
  .show-wrap { padding: 12px; }
  .v3d-wrap  { height: 300px; }
}
</style>
@endpush

@section('content')

{{-- BREADCRUMB --}}
<div style="background:#f8fafc;border-bottom:1px solid #e2e8f0;padding:12px 24px">
  <div style="max-width:1280px;margin:0 auto;font-size:13px;color:#6b7280">
    <a href="/" style="color:inherit;text-decoration:none">Accueil</a>
    <span style="margin:0 8px">›</span>
    <a href="/machines" style="color:inherit;text-decoration:none">Catalogue</a>
    <span style="margin:0 8px">›</span>
    <span id="breadcrumb-name" style="color:#0F1B2D;font-weight:600">Chargement...</span>
  </div>
</div>

<div class="show-wrap" id="showWrap" style="opacity:0;transition:opacity .4s">

  {{-- ══════════════════════════════════════
       COL GAUCHE
  ══════════════════════════════════════ --}}
  <div>

    {{-- ─── VIEWER 3D THREE.JS ─── --}}
    <div class="viewer-section">
      <div class="v3d-wrap" id="v3d-wrap">

        <canvas id="v3d-canvas"
                style="display:block;width:100%;height:100%;cursor:grab;touch-action:none;"></canvas>

        {{-- Hint drag --}}
        <div style="position:absolute;top:16px;right:16px;z-index:4;
                    background:rgba(7,16,24,.80);border:.5px solid rgba(245,158,11,.35);
                    border-radius:8px;padding:5px 12px;pointer-events:none;">
          <small style="font-size:11px;color:#6a8ea8;letter-spacing:.04em;">
            ⟳ Glisser · Molette zoom
          </small>
        </div>

        {{-- Statut (mis à jour par JS) --}}
        <div id="v3d-badge" class="status-badge-3d status-available">Disponible</div>

        {{-- Panel hotspot --}}
        <div id="v3d-hotspot-panel"
             style="display:none;position:absolute;bottom:64px;left:16px;right:16px;
                    background:rgba(7,16,24,.93);border:.5px solid rgba(245,158,11,.4);
                    border-radius:12px;padding:14px 16px;pointer-events:none;z-index:8;">
          <p id="v3d-hs-title"
             style="margin:0 0 5px;font-size:13px;font-weight:700;color:#F59E0B;letter-spacing:.04em;"></p>
          <p id="v3d-hs-desc"
             style="margin:0;font-size:12px;color:#94afc7;line-height:1.55;"></p>
        </div>

        {{-- Hotspot buttons (positionnés par JS) --}}
        <div id="v3d-hotspots"
             style="position:absolute;inset:0;pointer-events:none;z-index:7;"></div>

        {{-- Boutons bas --}}
        <div style="position:absolute;bottom:14px;left:50%;transform:translateX(-50%);
                    display:flex;gap:8px;z-index:9;white-space:nowrap;">
          <button id="v3d-anim-btn"
                  style="background:#F59E0B;color:#071018;border:none;border-radius:9px;
                         padding:8px 18px;font-size:12px;font-weight:700;cursor:pointer;">
            ▶ Animer le bras
          </button>
          <button id="v3d-hs-toggle"
                  style="background:rgba(245,158,11,.12);color:#F59E0B;
                         border:.5px solid rgba(245,158,11,.4);border-radius:9px;
                         padding:8px 13px;font-size:12px;font-weight:600;cursor:pointer;">
            🔍 Points clés
          </button>
          <button id="v3d-reset-btn"
                  style="background:rgba(255,255,255,.07);color:#94afc7;
                         border:.5px solid rgba(255,255,255,.14);border-radius:9px;
                         padding:8px 13px;font-size:13px;cursor:pointer;">↺</button>
        </div>

        {{-- Loader --}}
        <div id="v3d-loader"
             style="position:absolute;inset:0;display:flex;flex-direction:column;
                    align-items:center;justify-content:center;
                    background:#071018;gap:12px;z-index:20;pointer-events:none;">
          <div style="width:36px;height:36px;border:3px solid #162333;
                      border-top-color:#F59E0B;border-radius:50%;
                      animation:v3dspin .75s linear infinite;"></div>
          <span style="font-size:12px;color:#3a5a75;letter-spacing:.06em;">Chargement 3D…</span>
        </div>

      </div>{{-- fin v3d-wrap --}}
    </div>{{-- fin viewer-section --}}

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
        <div style="text-align:center;padding:30px;color:#6b7280">
          <div class="spinner-border text-warning spinner-border-sm me-2"></div>
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
        <div style="text-align:center;padding:20px;color:#6b7280">Aucun avis pour le moment</div>
      </div>
      <button class="btn-reserver mt-3"
              style="background:transparent;border:1.5px solid var(--orange);color:var(--orange)"
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
       SIDEBAR BOOKING
  ══════════════════════════════════════ --}}
  <div>
    <div class="booking-card">
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

        <button onclick="reserver()"
                style="width:100%;padding:14px;border-radius:12px;background:#0F1B2D;
                       color:#F59E0B;border:1.5px solid #F59E0B;font-size:15px;font-weight:800;
                       cursor:pointer;margin-bottom:10px;display:flex;align-items:center;
                       justify-content:center;gap:8px;transition:all .2s;"
                onmouseover="this.style.background='#F59E0B';this.style.color='#0F1B2D'"
                onmouseout="this.style.background='#0F1B2D';this.style.color='#F59E0B'">
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
  </div>

</div>{{-- fin show-wrap --}}

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

/* ═══════════════════════════════════════════════
   INIT
═══════════════════════════════════════════════ */
document.addEventListener('DOMContentLoaded', function() {
  loadMachine();
  document.getElementById('startDate').value = new Date().toISOString().split('T')[0];
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
    init3DViewer();   // ← lance le viewer après avoir la data
    loadRatings();
    loadCalendar();
    document.getElementById('showWrap').style.opacity = '1';

  } catch(e) {
    console.error('loadMachine:', e);
    showToast('Erreur de chargement', 'danger');
  }
}

/* ═══════════════════════════════════════════════
   RENDER MACHINE (texte / badges / prix)
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

  var statusLabels = { available:'Disponible', rented:'Loué', maintenance:'Maintenance' };
  var statusColors = { available:'#10b981',    rented:'#F59E0B', maintenance:'#ef4444'  };
  var st = m.status || 'available';
  document.getElementById('priceStatus').textContent   = statusLabels[st] || st;
  document.getElementById('priceStatus').style.color   = statusColors[st] || '#fff';

  // ← badge du viewer 3D
  var badge = document.getElementById('v3d-badge');
  badge.textContent = (statusLabels[st] || st).toUpperCase();
  badge.className   = 'status-badge-3d status-' + st;

  document.getElementById('bookingPrice').textContent     = fmt(m.price_per_day)  + ' DH';
  document.getElementById('bookingPriceHour').textContent = fmt(m.price_per_hour);

  var ownerName = m.owner ? (m.owner.name || 'Propriétaire') : 'Propriétaire';
  document.getElementById('ownerName').textContent   = ownerName;
  document.getElementById('ownerAvatar').textContent = ownerName.charAt(0).toUpperCase();

  calcTotal();
}

/* ═══════════════════════════════════════════════
   VIEWER 3D THREE.JS
═══════════════════════════════════════════════ */
function init3DViewer() {
  var TYPE = ((machine && machine.type) || 'excavatrice').toLowerCase();

  /* ── Palettes par type ── */
  var PALETTES = {
    excavatrice : { main:0xF0900C, dark:0xBB6800 },
    grue        : { main:0xf5e230, dark:0xb8a800 },
    chargeuse   : { main:0xF0900C, dark:0xBB6800 },
    bulldozer   : { main:0xf5a623, dark:0xc07a00 },
    compacteur  : { main:0xff6b35, dark:0xc84b1c },
    nacelle     : { main:0x42a5f5, dark:0x1565c0 },
  };
  var PAL = PALETTES[TYPE] || PALETTES.excavatrice;

  /* ── Hotspots par type ── */
  var HS_DATA = {
    excavatrice:[
      {label:'Cabine opérateur',   desc:'ROPS/FOPS, AC, siège suspendu. Visibilité 360°.',         w:new THREE.Vector3(-2.1,3.2,0)},
      {label:'Flèche principale',  desc:'Portée 5.9 m · Force arrachement 62 kN.',                 w:new THREE.Vector3(2.2,3.0,0)},
      {label:'Godet fouille',      desc:'Capacité 0.28 m³ · Dents Hardox remplaçables.',           w:new THREE.Vector3(4.0,0.8,0)},
      {label:'Moteur JCB EcoMAX',  desc:'4 cyl · 74 kW · Conso nominale 5.5 L/h.',                w:new THREE.Vector3(1.5,2.1,0)},
      {label:'Stabilisateurs',     desc:'Vérin ø80 mm · Course 650 mm · Déploiement auto.',        w:new THREE.Vector3(2.8,-0.2,1.1)},
    ],
    grue:[
      {label:'Cabine panoramique', desc:'Siège rotatif 180° · Chauffage · Intercoms.',             w:new THREE.Vector3(-2.1,3.2,0)},
      {label:'Flèche grue',        desc:'Portée max 22 m · Capacité 3.2 T à 10 m.',               w:new THREE.Vector3(2.2,3.0,0)},
      {label:'Motorisation',       desc:'Diesel 120 kW · Transmission hydrostatique.',             w:new THREE.Vector3(1.5,2.1,0)},
      {label:'Stabilisateurs',     desc:'4 vérins H · Assiette auto < 0.5° · Capteurs charge.',   w:new THREE.Vector3(2.8,-0.2,1.1)},
    ],
    chargeuse:[
      {label:'Cabine FOPS II',     desc:'AC · Écran 7" multifonction · Rétroviseurs caméra.',      w:new THREE.Vector3(-2.1,3.2,0)},
      {label:'Godet chargeur',     desc:'1.1 m³ · Force 82 kN · Tranchant boulonné.',             w:new THREE.Vector3(-3.2,1.6,0)},
      {label:'Moteur Tier 4',      desc:'4 cyl turbo 74 kW · FAP intégré.',                       w:new THREE.Vector3(1.5,2.1,0)},
      {label:'Bras levage',        desc:'Hauteur déversement 2.8 m · Géométrie Z-bar.',            w:new THREE.Vector3(0,2.5,0)},
      {label:'Attache rapide',     desc:'S-Type · godets, fourches, balayeuse.',                   w:new THREE.Vector3(2.8,-0.2,1.1)},
    ],
    bulldozer:[
      {label:'Structure ROPS',     desc:'AC · Radio Bluetooth · Siège air suspendu.',              w:new THREE.Vector3(-2.1,3.2,0)},
      {label:'Lame SU',            desc:'3.5 m × 1.1 m · Vérins correction 2 axes.',              w:new THREE.Vector3(-3.2,1.6,0)},
      {label:'Moteur 6 cyl',       desc:'180 kW · Couple 840 Nm · Démarrage froid -25°.',         w:new THREE.Vector3(1.5,2.1,0)},
      {label:'Ripper hydraulique', desc:'Mono-dent · Force 140 kN · 3 positions.',                w:new THREE.Vector3(2.8,-0.2,1.1)},
    ],
    compacteur:[
      {label:'Poste conduite',     desc:'Siège bidirectionnel · ROPS ouvert.',                    w:new THREE.Vector3(-2.1,3.2,0)},
      {label:'Tambour avant',      desc:'ø 1.5 m · Largeur 2.13 m · Amplitude vib. réglable.',   w:new THREE.Vector3(-3.2,1.6,0)},
      {label:'Moteur',             desc:'4 cyl 97 kW · Pompe tandem haute pression.',             w:new THREE.Vector3(1.5,2.1,0)},
      {label:'Tambour arrière',    desc:'Statique ø 1.5 m · Scraper acier.',                     w:new THREE.Vector3(2.8,-0.2,1.1)},
    ],
    nacelle:[
      {label:'Plateforme travail', desc:'Charge 230 kg · 0.8×1.6 m · Garde-corps 1.1 m.',       w:new THREE.Vector3(-2.1,3.2,0)},
      {label:'Flèche télescopique',desc:'H 22 m · Portée 13.5 m · Rotation 360°.',              w:new THREE.Vector3(2.2,3.0,0)},
      {label:'Motorisation hybride',desc:'Diesel/élec · Autonomie 4h élec.',                    w:new THREE.Vector3(1.5,2.1,0)},
      {label:'Stabilisateurs',    desc:'4 vérins oscillants · Nivellement auto 5°.',            w:new THREE.Vector3(2.8,-0.2,1.1)},
    ],
  };
  var HOTSPOTS = HS_DATA[TYPE] || HS_DATA.excavatrice;

  /* ── Renderer ── */
  var wrap   = document.getElementById('v3d-wrap');
  var canvas = document.getElementById('v3d-canvas');
  var W = wrap.clientWidth || 680, H = 420;

  var renderer = new THREE.WebGLRenderer({ canvas: canvas, antialias: true });
  renderer.setPixelRatio(Math.min(devicePixelRatio, 2));
  renderer.setSize(W, H);
  renderer.shadowMap.enabled = true;
  renderer.shadowMap.type    = THREE.PCFSoftShadowMap;

  var scene  = new THREE.Scene();
  scene.background = new THREE.Color(0x071018);
  var camera = new THREE.PerspectiveCamera(42, W / H, 0.1, 100);

  /* ── Lumières ── */
  scene.add(new THREE.AmbientLight(0xffffff, 0.55));
  var sun = new THREE.DirectionalLight(0xfff5dd, 1.7);
  sun.position.set(8, 13, 9); sun.castShadow = true;
  sun.shadow.mapSize.set(1024, 1024);
  scene.add(sun);
  var fill = new THREE.DirectionalLight(0x3355bb, 0.28);
  fill.position.set(-6, 4, -6); scene.add(fill);
  var rim = new THREE.DirectionalLight(0xffaa33, 0.2);
  rim.position.set(0, 2, -8); scene.add(rim);

  /* ── Sol ── */
  var gnd = new THREE.Mesh(
    new THREE.PlaneGeometry(40, 40),
    new THREE.MeshLambertMaterial({ color: 0x0c1c2d })
  );
  gnd.rotation.x = -Math.PI / 2; gnd.receiveShadow = true; scene.add(gnd);
  scene.add(new THREE.GridHelper(20, 20, 0x1b3246, 0x111f2d));
  var patch = new THREE.Mesh(new THREE.PlaneGeometry(6, 4),
    new THREE.MeshLambertMaterial({ color: 0x0e2030 }));
  patch.rotation.x = -Math.PI / 2; patch.position.y = 0.008; scene.add(patch);

  /* ── Helpers matériaux ── */
  var M  = function(c) { return new THREE.MeshLambertMaterial({ color: c }); };
  var MT = function(c,o) { return new THREE.MeshLambertMaterial({ color:c, transparent:true, opacity:o }); };
  var Y=M(PAL.main), YD=M(PAL.dark), BK=M(0x0d0d0d), SL=M(0x7a8898), DK=M(0x060c12);
  var GL=MT(0x88c4ea,.45), RD=MT(0xff2200,.9), AM=MT(0xffbb00,.85);

  /* ── Helpers géométrie ── */
  var B = function(w,h,d,mat,x,y,z,rx,ry,rz) {
    x=x||0;y=y||0;z=z||0;rx=rx||0;ry=ry||0;rz=rz||0;
    var o = new THREE.Mesh(new THREE.BoxGeometry(w,h,d), mat);
    o.position.set(x,y,z); o.rotation.set(rx,ry,rz);
    o.castShadow = o.receiveShadow = true; return o;
  };
  var C = function(rt,rb,h,s,mat,x,y,z,rx,ry,rz) {
    x=x||0;y=y||0;z=z||0;rx=rx||0;ry=ry||0;rz=rz||0;
    var o = new THREE.Mesh(new THREE.CylinderGeometry(rt,rb,h,s), mat);
    o.position.set(x,y,z); o.rotation.set(rx,ry,rz);
    o.castShadow = true; return o;
  };

  /* ══════════════════════════════════════
     MODÈLE JCB 3CX
  ══════════════════════════════════════ */
  var JCB = new THREE.Group();
  JCB.add(B(4.1,.21,1.9,YD,0,.51,0));
  JCB.add(B(3.75,.39,1.68,Y,0,.78,0));

  /* Cab */
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
  cab.add(B(.07,.15,.22,GL,-.645,.9,.63)); cab.add(B(.07,.15,.22,GL,-.645,.9,-.63));
  cab.add(B(.07,.1,.15,AM,-.645,.72,.65)); cab.add(B(.07,.1,.15,AM,-.645,.72,-.65));
  JCB.add(cab);

  /* Capot */
  JCB.add(B(1.58,.87,1.62,Y,.93,1.21,0));
  [0,.53,-.53].forEach(function(z){ JCB.add(B(1.59,.025,.46,YD,.93,1.6,z)); });
  JCB.add(B(.06,.55,1.42,DK,1.73,1.15,0));
  for(var i=0;i<5;i++) JCB.add(B(.08,.04,1.38,DK,1.72,.92+i*.1,0));
  JCB.add(C(.055,.055,.6,10,SL,.72,1.91,.52));
  JCB.add(C(.082,.065,.09,10,DK,.72,2.22,.52));
  JCB.add(B(.07,.16,.22,RD,1.73,1.06,.63));  JCB.add(B(.07,.16,.22,RD,1.73,1.06,-.63));
  JCB.add(B(.07,.1,.15,AM,1.73,.88,.63));    JCB.add(B(.07,.1,.15,AM,1.73,.88,-.63));
  JCB.add(B(.42,.07,.42,YD,-.88,.65,.9));

  /* Roues */
  var addWheel = function(x,y,z,r,ww) {
    JCB.add(C(r,r,ww,28,BK,x,y,z,Math.PI/2,0,0));
    JCB.add(C(r*.5,r*.5,ww+.02,18,SL,x,y,z,Math.PI/2,0,0));
    JCB.add(C(r*.16,r*.16,ww+.05,8,DK,x,y,z,Math.PI/2,0,0));
    for(var n=0;n<5;n++){
      var a=n/5*Math.PI*2;
      JCB.add(C(.035,.035,ww+.06,6,SL,x,y+Math.cos(a)*r*.36,z+Math.sin(a)*r*.36,Math.PI/2,0,0));
    }
  };
  addWheel(-1.32,.46,.94,.46,.38); addWheel(-1.32,.46,-.94,.46,.38);
  addWheel(1.32,.50,.97,.50,.42);  addWheel(1.32,.50,-.97,.50,.42);
  JCB.add(C(.07,.07,1.98,8,SL,-1.32,.46,0,Math.PI/2,0,0));
  JCB.add(C(.07,.07,2.05,8,SL,1.32,.50,0,Math.PI/2,0,0));

  /* Loader avant */
  var loaderArm = new THREE.Group(); loaderArm.position.set(-1.9,.78,0);
  loaderArm.add(B(.12,1.28,.12,YD,0,.56,.67,-.22,0,0));
  loaderArm.add(B(.12,1.28,.12,YD,0,.56,-.67,-.22,0,0));
  loaderArm.add(B(.1,.1,1.46,YD,0,.98,0));
  loaderArm.add(C(.048,.048,.85,8,SL,0,.42,.37,-.4,0,0));
  loaderArm.add(C(.048,.048,.85,8,SL,0,.42,-.37,-.4,0,0));
  var ldBkt = new THREE.Group(); ldBkt.position.set(-.13,1.22,0);
  ldBkt.add(B(.5,.28,1.72,Y)); ldBkt.add(B(.36,.07,1.72,YD,-.16,-.13,0,.35,0,0));
  for(var t=-3;t<=3;t++) ldBkt.add(B(.12,.17,.09,SL,-.2,-.22,t*.22));
  loaderArm.add(ldBkt); JCB.add(loaderArm);

  /* Backhoe arrière */
  var bh = new THREE.Group(); bh.position.set(1.9,.78,0);
  var addStab = function(zs){
    bh.add(B(.12,.07,.12,Y,.12,-.06,zs));
    bh.add(B(.065,.68,.065,Y,.12,-.4,zs,.18*Math.sign(zs),0,0));
    bh.add(B(.38,.065,.22,YD,.14,-.74,zs));
  };
  addStab(1.06); addStab(-1.06);
  bh.add(B(.38,.64,.46,Y,0,.32,0));

  var boom = new THREE.Group(); boom.position.set(0,.64,0); boom.rotation.z=-.45;
  boom.add(B(.18,2.02,.18,Y,0,1.01,0));
  boom.add(C(.055,.055,1.1,8,SL,.2,.4,.11,0,0,-.52));

  var dipper = new THREE.Group(); dipper.position.set(0,2.02,0); dipper.rotation.z=.55;
  dipper.add(B(.14,1.32,.14,Y,0,.66,0));
  dipper.add(C(.05,.05,.72,8,SL,.12,.34,.1,0,0,.38));

  var bktGrp = new THREE.Group(); bktGrp.position.set(0,1.32,0); bktGrp.rotation.z=-.45;
  bktGrp.add(B(.43,.34,.56,YD)); bktGrp.add(B(.36,.07,.56,Y,-.16,-.14,0,.3,0,0));
  for(var t=-1;t<=1;t++) bktGrp.add(B(.14,.19,.09,SL,-.18,-.17,t*.19));

  dipper.add(bktGrp); boom.add(dipper); bh.add(boom); JCB.add(bh);
  scene.add(JCB);

  /* ── Orbit controls (manuel) ── */
  var theta=.95, phi=.38, radius=11.5;
  var dragging=false, autoRot=true, px=0, py=0;

  canvas.addEventListener('mousedown', function(e){ dragging=true; autoRot=false; px=e.clientX; py=e.clientY; canvas.style.cursor='grabbing'; });
  document.addEventListener('mouseup',  function(){ dragging=false; canvas.style.cursor='grab'; });
  document.addEventListener('mousemove',function(e){
    if(!dragging) return;
    theta -= (e.clientX-px)*.008;
    phi = Math.max(.06, Math.min(1.28, phi+(e.clientY-py)*.005));
    px=e.clientX; py=e.clientY;
  });
  canvas.addEventListener('wheel', function(e){
    radius = Math.max(5.5, Math.min(18, radius+e.deltaY*.013));
    e.preventDefault();
  }, { passive: false });
  var t0=null;
  canvas.addEventListener('touchstart', function(e){ autoRot=false; dragging=true; t0={x:e.touches[0].clientX,y:e.touches[0].clientY}; e.preventDefault(); },{ passive:false });
  canvas.addEventListener('touchmove',  function(e){
    if(!t0) return;
    theta -= (e.touches[0].clientX-t0.x)*.008;
    phi = Math.max(.06, Math.min(1.28, phi+(e.touches[0].clientY-t0.y)*.005));
    t0={x:e.touches[0].clientX,y:e.touches[0].clientY}; e.preventDefault();
  },{ passive:false });
  canvas.addEventListener('touchend', function(){ dragging=false; t0=null; });

  /* ── Animation bras ── */
  var animOn=false, aT=0;
  var BOOM0=-.45, DIP0=.55, BKT0=-.45;

  document.getElementById('v3d-anim-btn').addEventListener('click', function(){
    animOn=!animOn;
    this.textContent = animOn ? '⏸ Pause' : '▶ Animer le bras';
    if(animOn) autoRot=false;
  });
  document.getElementById('v3d-reset-btn').addEventListener('click', function(){
    theta=.95; phi=.38; radius=11.5; autoRot=true;
    boom.rotation.z=BOOM0; dipper.rotation.z=DIP0; bktGrp.rotation.z=BKT0;
    loaderArm.rotation.z=0; aT=0; animOn=false;
    document.getElementById('v3d-anim-btn').textContent='▶ Animer le bras';
  });

  /* ── Hotspots ── */
  var hsOn=false;
  var hsContainer = document.getElementById('v3d-hotspots');
  var hsPanel     = document.getElementById('v3d-hotspot-panel');
  var hsBtns=[];

  HOTSPOTS.forEach(function(hs, i){
    var btn = document.createElement('div');
    btn.className='v3d-hs'; btn.textContent=i+1; btn.style.display='none';
    btn.addEventListener('click', function(){
      document.getElementById('v3d-hs-title').textContent = hs.label;
      document.getElementById('v3d-hs-desc').textContent  = hs.desc;
      hsPanel.style.display='block';
      clearTimeout(btn._t);
      btn._t = setTimeout(function(){ hsPanel.style.display='none'; }, 4500);
    });
    hsContainer.appendChild(btn);
    hsBtns.push({ btn:btn, w:hs.w });
  });

  document.getElementById('v3d-hs-toggle').addEventListener('click', function(){
    hsOn=!hsOn;
    this.style.background = hsOn ? 'rgba(245,158,11,.3)' : 'rgba(245,158,11,.12)';
    if(!hsOn){ hsBtns.forEach(function(b){ b.btn.style.display='none'; }); hsPanel.style.display='none'; }
  });

  var _v = new THREE.Vector3();
  function updateHotspots(){
    if(!hsOn) return;
    hsBtns.forEach(function(obj){
      _v.copy(obj.w); _v.project(camera);
      var x=(_v.x*.5+.5)*W, y=(-.5*_v.y+.5)*H;
      if(_v.z<1 && x>10 && x<W-10 && y>10 && y<H-10){
        obj.btn.style.display='flex';
        obj.btn.style.left=(x-11)+'px';
        obj.btn.style.top =(y-11)+'px';
      } else obj.btn.style.display='none';
    });
  }

  /* ── Caméra ── */
  function camUpdate(){
    camera.position.set(
      radius*Math.sin(theta)*Math.cos(phi),
      radius*Math.sin(phi)+1.4,
      radius*Math.cos(theta)*Math.cos(phi)
    );
    camera.lookAt(0, 1.35, 0);
  }

  /* ── Remove loader ── */
  setTimeout(function(){
    var ld = document.getElementById('v3d-loader');
    if(ld) ld.style.display='none';
  }, 600);

  /* ── Boucle rendu ── */
  function loop(){
    requestAnimationFrame(loop);
    if(autoRot && !dragging) theta += .0035;
    camUpdate();
    if(animOn){
      aT += .021;
      boom.rotation.z     = BOOM0 + Math.sin(aT)*.74;
      dipper.rotation.z   = DIP0  + Math.sin(aT*1.35+.9)*.52;
      bktGrp.rotation.z   = BKT0  + Math.sin(aT*1.8+1.4)*.52;
      loaderArm.rotation.z= Math.sin(aT*.55)*.18;
    }
    renderer.render(scene, camera);
    updateHotspots();
  }
  camUpdate(); loop();

  window.addEventListener('resize', function(){
    W = wrap.clientWidth;
    renderer.setSize(W, H);
    camera.aspect = W/H;
    camera.updateProjectionMatrix();
  });
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
      '<span style="font-size:11px;color:rgba(255,255,255,.5);width:12px">'+s+'</span>'+
      '<div style="flex:1;height:6px;background:rgba(255,255,255,.1);border-radius:3px;overflow:hidden">'+
        '<div style="width:'+pct+'%;height:100%;background:#F59E0B;border-radius:3px"></div>'+
      '</div>'+
      '<span style="font-size:11px;color:rgba(255,255,255,.4);width:28px">'+pct+'%</span>'+
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
  document.getElementById('reviewsList').innerHTML = html || '<p style="color:#6b7280;text-align:center;padding:16px">Aucun avis</p>';
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
    s.style.color = parseInt(s.dataset.v)<=val ? '#F59E0B' : '#d1d5db';
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
    '<button onclick="changeMonth(-1)" style="border:none;background:#f1f5f9;border-radius:8px;width:32px;height:32px;cursor:pointer">‹</button>'+
    '<strong style="color:#0F1B2D">'+mnames[month]+' '+year+'</strong>'+
    '<button onclick="changeMonth(1)"  style="border:none;background:#f1f5f9;border-radius:8px;width:32px;height:32px;cursor:pointer">›</button>'+
    '</div><div style="display:grid;grid-template-columns:repeat(7,1fr);gap:4px;text-align:center">';
  names.forEach(function(n){ html+='<div style="font-size:10px;font-weight:700;color:#6b7280;padding:6px 0">'+n+'</div>'; });
  for(var i=0;i<first;i++) html+='<div></div>';
  for(var d=1;d<=days;d++){
    var isToday   =(d===today && month===curM && year===curY);
    var isPast    =(new Date(year,month,d)<new Date(curY,curM,today));
    var isReserved=reserved.includes(year+'-'+pad(month+1)+'-'+pad(d));
    var bg =isReserved?'#FEE2E2':(isToday?'#F59E0B':(isPast?'#f9fafb':'#f0fdf4'));
    var col=isReserved?'#991B1B':(isToday?'white':(isPast?'#d1d5db':'#0F1B2D'));
    var fw =isToday?'900':'500';
    html+='<div style="background:'+bg+';color:'+col+';border-radius:8px;padding:8px 4px;font-size:12px;font-weight:'+fw+'">'+d+'</div>';
  }
  html+='</div><div style="display:flex;gap:16px;margin-top:12px;font-size:11px;color:#6b7280">'+
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
  var colors={success:'#10b981',danger:'#ef4444',warning:'#F59E0B',info:'#3b82f6'};
  el.style.background=colors[type]||colors.info;
  el.textContent=msg; el.classList.add('visible');
  clearTimeout(_toastT);
  _toastT=setTimeout(function(){ el.classList.remove('visible'); },3500);
}
</script>
@endpush