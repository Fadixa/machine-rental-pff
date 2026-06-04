@extends('layouts.app')

@section('title', 'Catalogue des machines — Rentify')

@push('styles')
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css"/>
<style>
/* ══════════════════════════════════════════════
   MACHINES INDEX — RENTIFY V7 (gold/crème strict)
══════════════════════════════════════════════ */
:root {
  --gold:#D4AF37;--gold-dk:#9A7D20;--gold-pale:#FEF9E7;
  --gold-glow:rgba(212,175,55,.25);
  --navy:#0F1B2D;--navy2:#162540;
  --cream:#FAF7F0;--cream2:#F0EBE0;--cream3:#E8DDD0;
  --txt-dark:#1a1a2e;--txt-mid:#5a5660;--txt-light:#9992a4;
  --green:#22c55e;--red:#ef4444;
  --radius:14px;--shadow:0 4px 24px rgba(15,27,45,.08);
}

body { background:var(--cream); font-family:'DM Sans',sans-serif; }

/* ── Page layout ── */
.catalogue-wrap {
  max-width:1400px; margin:0 auto;
  padding:2rem 2rem 4rem;
  display:grid;
  grid-template-columns:280px 1fr;
  gap:2rem;
  align-items:start;
}


/* ══════════════════════════════════
   SEARCH BAR
══════════════════════════════════ */
.search-bar-wrap {
  max-width:1400px; margin:0 auto;
  padding:.75rem 2rem;
  background:#fff;
  border-bottom:1px solid var(--cream3);
  display:flex; align-items:center; gap:.75rem;
  position:sticky; top:64px; z-index:50;
  box-shadow:0 2px 12px rgba(15,27,45,.06);
}
.search-input-wrap {
  flex:1; position:relative;
}
.search-input-wrap input {
  width:100%; padding:.6rem 1rem .6rem 2.6rem;
  border:1.5px solid var(--cream3); border-radius:50px;
  font-family:'DM Sans',sans-serif; font-size:.88rem;
  color:var(--txt-dark); outline:none; background:var(--cream);
  transition:border .2s,box-shadow .2s;
}
.search-input-wrap input:focus {
  border-color:var(--gold);
  box-shadow:0 0 0 3px rgba(212,175,55,.1);
}
.search-input-wrap .si {
  position:absolute; left:.9rem; top:50%;
  transform:translateY(-50%); color:var(--txt-light); font-size:.9rem;
}
.view-toggle {
  display:flex; gap:.3rem;
  background:var(--cream2); border-radius:8px; padding:.2rem;
}
.view-btn {
  width:32px; height:32px; border:none; border-radius:6px;
  background:transparent; cursor:pointer; color:var(--txt-light);
  transition:all .2s; font-size:.9rem;
  display:flex; align-items:center; justify-content:center;
}
.view-btn.active { background:#fff; color:var(--txt-dark); box-shadow:var(--shadow); }
.results-count {
  color:var(--txt-light); font-size:.82rem; white-space:nowrap;
}
.results-count strong { color:var(--txt-dark); }
.sort-select {
  padding:.5rem .9rem; border:1.5px solid var(--cream3);
  border-radius:8px; font-family:'DM Sans',sans-serif;
  font-size:.82rem; color:var(--txt-dark); outline:none;
  background:#fff; cursor:pointer;
}

/* ══════════════════════════════════
   SIDEBAR FILTRES
══════════════════════════════════ */
.filter-sidebar {
  background:#fff;
  border:1px solid rgba(212,175,55,.2);
  border-radius:var(--radius);
  padding:1.5rem 1.5rem 1.75rem; /* ✅ FIX: padding-bottom tabn */
  box-shadow:var(--shadow);
  position:sticky; top:120px;
  overflow:hidden;
}
.filter-title {
  font-family:'Playfair Display',serif;
  font-size:1rem; color:var(--txt-dark);
  margin:0 0 1.25rem;
  display:flex; align-items:center; justify-content:space-between;
}
.filter-reset {
  font-size:.75rem; color:var(--gold-dk);
  font-family:'DM Sans',sans-serif; font-weight:600;
  cursor:pointer; border:none; background:none;
  padding:0; transition:color .2s;
}
.filter-reset:hover { color:var(--red); }

.filter-section { margin-bottom:1.5rem; }
.filter-section-label {
  font-size:.72rem; font-weight:800; letter-spacing:.1em;
  text-transform:uppercase; color:var(--txt-light);
  margin-bottom:.75rem; display:block;
}

/* Type chips */
.type-chips { display:flex; flex-wrap:wrap; gap:.4rem; }
.type-chip {
  padding:.3rem .75rem; border-radius:20px;
  font-size:.78rem; font-weight:600; cursor:pointer;
  border:1.5px solid var(--cream3); background:#fff;
  color:var(--txt-mid); transition:all .18s;
  display:inline-flex; align-items:center; gap:.3rem;
}
.type-chip:hover { border-color:var(--gold); color:var(--txt-dark); }
.type-chip.active {
  background:var(--gold); border-color:var(--gold);
  color:var(--txt-dark);
}

/* Price range */
.price-range-wrap { position:relative; }
.price-inputs { display:flex; gap:.3rem; align-items:center; }
.price-inputs input {
  flex:1; min-width:0; width:0;
  padding:.38rem .3rem; border:1.5px solid var(--cream3); border-radius:8px;
  font-family:'DM Sans',sans-serif; font-size:.75rem;
  color:var(--txt-dark); outline:none; text-align:center; box-sizing:border-box;
  transition:border .2s;
}
.price-inputs input:focus { border-color:var(--gold); }
.price-sep { color:var(--txt-light); font-size:.8rem; flex-shrink:0; }

/* City search */
.city-input {
  width:100%; padding:.45rem .75rem;
  border:1.5px solid var(--cream3); border-radius:8px;
  padding:.38rem .3rem; border:1.5px solid var(--cream3); border-radius:8px;
  font-family:'DM Sans',sans-serif; font-size:.75rem;
  color:var(--txt-dark); outline:none;
  transition:border .2s;
  box-sizing:border-box; /* ✅ FIX: city input aussi */
}
.city-input:focus { border-color:var(--gold); }

/* Status filter */
.status-options { display:flex; flex-direction:column; gap:.4rem; }
.status-opt {
  display:flex; align-items:center; gap:.5rem;
  padding:.4rem .6rem; border-radius:8px;
  cursor:pointer; transition:background .15s;
}
.status-opt:hover { background:var(--cream); }
.status-opt input { accent-color:var(--gold); }
.status-opt label { font-size:.83rem; color:var(--txt-mid); cursor:pointer; }

/* ══════════════════════════════════
   MAP TOGGLE — ✅ FIX: box-sizing + display:flex
══════════════════════════════════ */
.map-toggle-bar {
  display:flex; align-items:center; justify-content:flex-end;
  margin-bottom:1rem; gap:.5rem;
}
.btn-map-toggle {
  display:flex;
  align-items:center; justify-content:center; gap:.4rem;
  width:100%;
  box-sizing:border-box;
  padding:.55rem 1rem; border-radius:8px;
  background:var(--gold); color:var(--txt-dark);
  border:none; font-family:'DM Sans',sans-serif;
  font-size:.82rem; font-weight:700; cursor:pointer;
  transition:all .2s;
  margin-top:.75rem; /* ✅ FIX: espace au-dessus du bouton */
}
.btn-map-toggle:hover { background:var(--gold-dk); color:#fff; }
#leafletMap {
  height:320px; border-radius:var(--radius);
  border:1px solid rgba(212,175,55,.15);
  overflow:hidden; margin-bottom:1.5rem;
  display:none;
  box-shadow:var(--shadow);
}
#leafletMap.open { display:block; }

/* ══════════════════════════════════
   MACHINE CARDS GRID
══════════════════════════════════ */
.machines-grid {
  display:grid;
  grid-template-columns:repeat(auto-fill,minmax(290px,1fr));
  gap:1.25rem;
}
.machines-grid.list-view {
  grid-template-columns:1fr;
}

/* ── Machine Card ── */
.machine-card {
  background:#fff;
  border:1px solid rgba(212,175,55,.12);
  border-radius:var(--radius);
  overflow:hidden;
  box-shadow:var(--shadow);
  transition:transform .22s, box-shadow .22s;
  display:flex; flex-direction:column;
  animation:cardIn .4s ease both;
}
@keyframes cardIn {
  from { opacity:0; transform:translateY(16px); }
  to   { opacity:1; transform:translateY(0); }
}
.machine-card:hover {
  transform:translateY(-5px);
  box-shadow:0 16px 40px rgba(15,27,45,.13);
}

/* List view card */
.machines-grid.list-view .machine-card {
  flex-direction:row; align-items:stretch;
}
.machines-grid.list-view .machine-card-img {
  width:220px; height:auto; flex-shrink:0;
}
.machines-grid.list-view .machine-card-body { flex:1; }

/* Image zone */
.machine-card-img {
  height:190px; overflow:hidden; position:relative;
  background:var(--cream2);
}
.machine-card-img img {
  width:100%; height:100%; object-fit:cover;
  transition:transform .45s cubic-bezier(.25,.46,.45,.94);
}
.machine-card:hover .machine-card-img img { transform:scale(1.07); }

/* Badges sur l'image */
.card-badge-num {
  position:absolute; top:.65rem; left:.65rem;
  background:rgba(212,175,55,.85); backdrop-filter:blur(4px);
  color:var(--txt-dark); font-size:.68rem; font-weight:800;
  padding:.2rem .55rem; border-radius:6px;
  letter-spacing:.04em;
}
.card-badge-status {
  position:absolute; top:.65rem; right:.65rem;
  padding:.22rem .6rem; border-radius:20px;
  font-size:.68rem; font-weight:700;
}
.status-available   { background:#dcfce7; color:#15803d; }
.status-unavailable { background:#fee2e2; color:#dc2626; }
.status-maintenance { background:var(--gold-pale); color:var(--gold-dk); }

.card-fav-btn {
  position:absolute; bottom:.65rem; right:.65rem;
  width:32px; height:32px; border-radius:50%;
  background:rgba(255,255,255,.9); backdrop-filter:blur(4px);
  border:none; cursor:pointer; font-size:.95rem;
  display:flex; align-items:center; justify-content:center;
  transition:all .2s; box-shadow:0 2px 8px rgba(0,0,0,.1);
}
.card-fav-btn:hover { transform:scale(1.15); background:#fff; }
.card-fav-btn.active { background:var(--gold-pale); }

/* Body */
.machine-card-body {
  padding:1.1rem 1.2rem; flex:1; display:flex; flex-direction:column;
}
.card-type-badge {
  display:inline-block;
  background:var(--gold-pale);
  color:var(--gold-dk); font-size:.68rem; font-weight:800;
  letter-spacing:.08em; text-transform:uppercase;
  padding:.18rem .55rem; border-radius:4px;
  margin-bottom:.5rem;
}
.card-machine-name {
  font-weight:800; color:var(--txt-dark); font-size:1rem;
  margin-bottom:.25rem; line-height:1.3;
  display:-webkit-box; -webkit-line-clamp:2; -webkit-box-orient:vertical; overflow:hidden;
}
.card-city {
  color:var(--txt-light); font-size:.78rem;
  display:flex; align-items:center; gap:.3rem;
  margin-bottom:.75rem;
}

/* Owner info */
.card-owner {
  display:flex; align-items:center; gap:.55rem;
  padding:.55rem .7rem;
  background:var(--cream); border-radius:8px;
  margin-bottom:.75rem;
}
.owner-avatar {
  width:28px; height:28px; border-radius:50%;
  background:var(--gold); color:var(--txt-dark);
  font-size:.75rem; font-weight:800;
  display:flex; align-items:center; justify-content:center;
  flex-shrink:0; overflow:hidden; border:2px solid var(--gold-dk);
}
.owner-avatar img { width:100%; height:100%; object-fit:cover; }
.owner-name {
  font-size:.78rem; font-weight:600; color:var(--txt-dark);
  flex:1;
}
.owner-rating {
  font-size:.72rem; color:var(--gold-dk); font-weight:700;
  display:flex; align-items:center; gap:.2rem;
}

/* Price */
.card-price-row {
  display:flex; align-items:baseline; gap:.4rem;
  margin-bottom:.85rem;
}
.card-price-day {
  font-size:1.25rem; font-weight:900; color:var(--txt-dark);
  font-family:'Playfair Display',serif;
}
.card-price-unit { font-size:.78rem; color:var(--txt-mid); }
.card-price-hour {
  font-size:.78rem; color:var(--txt-light);
  margin-left:auto;
}

/* Actions */
.card-actions {
  display:flex; gap:.5rem; margin-top:auto;
}
.btn-reserver {
  flex:1; padding:.55rem 1rem;
  background:var(--gold); color:var(--txt-dark);
  border:none; border-radius:9px;
  font-family:'DM Sans',sans-serif; font-weight:700; font-size:.83rem;
  cursor:pointer; transition:all .2s;
  display:flex; align-items:center; justify-content:center; gap:.4rem;
  text-decoration:none;
}
.btn-reserver:hover { background:var(--gold-dk); color:#fff; transform:translateY(-1px); }
.btn-reserver.guest { background:var(--gold); color:var(--txt-dark); }
.btn-reserver.guest:hover { background:var(--gold-dk); color:#fff; }
.btn-wa {
  width:38px; height:38px; border-radius:9px;
  background:#25D366; border:none; color:#fff;
  font-size:1rem; cursor:pointer; transition:all .2s;
  display:flex; align-items:center; justify-content:center;
  flex-shrink:0; text-decoration:none;
}
.btn-wa:hover { background:#20bd5a; transform:translateY(-1px); }
.btn-detail {
  width:38px; height:38px; border-radius:9px;
  background:var(--cream2); border:1.5px solid var(--cream3);
  color:var(--txt-dark); font-size:.9rem;
  cursor:pointer; transition:all .2s;
  display:flex; align-items:center; justify-content:center;
  text-decoration:none;
}
.btn-detail:hover { border-color:var(--gold); color:var(--gold-dk); }

/* ══════════════════════════════════
   MODAL RESERVATION
══════════════════════════════════ */
.modal-overlay {
  position:fixed; inset:0; z-index:9000;
  background:rgba(10,16,28,.75); backdrop-filter:blur(6px);
  display:flex; align-items:center; justify-content:center;
  opacity:0; pointer-events:none; transition:opacity .3s;
}
.modal-overlay.open { opacity:1; pointer-events:all; }
.modal-box {
  background:#fff; border-radius:18px;
  width:520px; max-width:96vw;
  box-shadow:0 24px 80px rgba(10,16,28,.25);
  transform:translateY(20px); transition:transform .3s;
  overflow:hidden;
}
.modal-overlay.open .modal-box { transform:translateY(0); }
.modal-header {
  display:flex; align-items:center; justify-content:space-between;
  padding:1.25rem 1.5rem; border-bottom:1px solid var(--cream3);
  background:var(--cream);
}
.modal-header h2 {
  font-family:'Playfair Display',serif;
  font-size:1.15rem; color:var(--txt-dark); margin:0;
}
.modal-close {
  width:34px; height:34px; border-radius:8px;
  background:var(--cream3); border:none; cursor:pointer;
  font-size:1rem; transition:all .2s;
}
.modal-close:hover { background:var(--cream2); }
.modal-body { padding:1.5rem; }

.modal-machine-recap {
  display:flex; align-items:center; gap:.9rem;
  background:var(--cream); border-radius:10px;
  padding:.9rem; margin-bottom:1.25rem;
  border:1px solid rgba(212,175,55,.12);
}
.modal-machine-img {
  width:64px; height:64px; border-radius:8px;
  object-fit:cover; flex-shrink:0;
}
.modal-machine-name { font-weight:700; color:var(--txt-dark); font-size:.92rem; }
.modal-machine-price { color:var(--gold-dk); font-size:.8rem; font-weight:600; margin-top:.2rem; }

.form-row { display:grid; grid-template-columns:1fr 1fr; gap:.9rem; margin-bottom:.9rem; }
.form-group { display:flex; flex-direction:column; gap:.35rem; }
.form-label { font-size:.78rem; font-weight:700; color:var(--txt-dark); }
.form-control {
  padding:.55rem .8rem; border:1.5px solid var(--cream3);
  border-radius:9px; font-family:'DM Sans',sans-serif;
  font-size:.85rem; color:var(--txt-dark); outline:none;
  transition:border .2s;
}
.form-control:focus { border-color:var(--gold); }

.price-calc {
  background:var(--gold-pale); border:1px solid rgba(212,175,55,.25);
  border-radius:10px; padding:1rem; margin-top:.9rem;
  display:flex; justify-content:space-between; align-items:center;
}
.price-calc-label { font-size:.82rem; color:var(--txt-mid); }
.price-calc-value {
  font-family:'Playfair Display',serif;
  font-size:1.4rem; color:var(--txt-dark); font-weight:700;
}

.modal-footer {
  display:flex; gap:.65rem; padding:1.25rem 1.5rem;
  border-top:1px solid var(--cream3); background:var(--cream);
}

.btn-navy {
  background:var(--gold); color:var(--txt-dark);
  border:none; border-radius:9px; padding:.6rem 1.25rem;
  font-family:'DM Sans',sans-serif; font-weight:700; font-size:.85rem;
  cursor:pointer; transition:all .2s; display:inline-flex;
  align-items:center; gap:.4rem; text-decoration:none;
}
.btn-navy:hover { background:var(--gold-dk); color:#fff; transform:translateY(-1px); }
.btn-outline-sm {
  background:transparent; color:var(--txt-dark);
  border:1.5px solid var(--cream3); border-radius:9px;
  padding:.6rem 1.1rem; font-family:'DM Sans',sans-serif;
  font-weight:600; font-size:.85rem; cursor:pointer; transition:all .2s;
}
.btn-outline-sm:hover { border-color:var(--gold); color:var(--gold-dk); }

/* ══════════════════════════════════
   TOAST
══════════════════════════════════ */
.toast-ctr {
  position:fixed; bottom:2rem; right:2rem;
  z-index:99999; display:flex; flex-direction:column; gap:.5rem;
}
.toast {
  background:#fff; border-radius:12px;
  border:1px solid var(--cream3);
  padding:.8rem 1.2rem;
  box-shadow:0 8px 32px rgba(10,16,28,.12);
  font-size:.84rem; font-weight:600; color:var(--txt-dark);
  display:flex; align-items:center; gap:.6rem;
  transform:translateX(120%); transition:transform .35s cubic-bezier(.34,1.56,.64,1);
  min-width:220px;
}
.toast.show { transform:translateX(0); }
.toast.success { border-left:4px solid var(--green); }
.toast.error   { border-left:4px solid var(--red); }
.toast.info    { border-left:4px solid var(--gold); }

/* ══════════════════════════════════
   EMPTY / LOADING
══════════════════════════════════ */
.empty-state {
  grid-column:1/-1; text-align:center; padding:4rem 2rem;
}
.empty-state-icon { font-size:3.5rem; margin-bottom:1rem; opacity:.4; }
.empty-state h3 { color:var(--txt-dark); font-size:1.15rem; margin:0 0 .5rem; }
.empty-state p  { color:var(--txt-mid); font-size:.88rem; }

.skeleton {
  background:linear-gradient(90deg,var(--cream2) 25%,var(--cream3) 50%,var(--cream2) 75%);
  background-size:200% 100%; animation:shimmer 1.5s infinite; border-radius:8px;
}
@keyframes shimmer{0%{background-position:200% 0}100%{background-position:-200% 0}}

.pagination-wrap {
  display:flex; justify-content:center; align-items:center;
  gap:.5rem; margin-top:2rem; grid-column:1/-1;
}
.page-btn {
  width:36px; height:36px; border-radius:8px;
  display:flex; align-items:center; justify-content:center;
  border:1.5px solid var(--cream3); background:#fff;
  color:var(--txt-mid); cursor:pointer; font-size:.85rem;
  transition:all .2s; font-family:'DM Sans',sans-serif; font-weight:600;
}
.page-btn:hover, .page-btn.active {
  background:var(--gold); border-color:var(--gold); color:var(--txt-dark);
}
.page-btn:disabled { opacity:.35; cursor:not-allowed; }

/* ══════════════════════════════════
   RESPONSIVE
══════════════════════════════════ */
@media(max-width:1100px){
  .catalogue-wrap { grid-template-columns:240px 1fr; }
}
@media(max-width:860px){
  .catalogue-wrap { grid-template-columns:1fr; }
  .filter-sidebar { position:static; }
  .machines-grid.list-view .machine-card { flex-direction:column; }
  .machines-grid.list-view .machine-card-img { width:100%; height:180px; }
  .search-bar-wrap { flex-wrap:wrap; top:56px; }
}

</style>
@endpush

@section('content')

{{-- ══ SEARCH BAR ══ --}}
<div class="search-bar-wrap">
  <div class="search-input-wrap">
    <span class="si">🔍</span>
    <input type="text" id="globalSearch" placeholder="Rechercher une machine, une ville…" oninput="applyFilters()">
  </div>
  <span class="results-count"><strong id="resultsCount">—</strong> résultats</span>
  <select class="sort-select" id="sortSelect" onchange="applyFilters()">
    <option value="default">Trier par défaut</option>
    <option value="price_asc">Prix croissant</option>
    <option value="price_desc">Prix décroissant</option>
    <option value="name_asc">Nom A→Z</option>
    <option value="newest">Plus récents</option>
  </select>
  <div class="view-toggle">
    <button class="view-btn active" id="btnGrid" onclick="setView('grid')" title="Grille">⊞</button>
    <button class="view-btn" id="btnList" onclick="setView('list')" title="Liste">☰</button>
  </div>
</div>

{{-- ══ BODY ══ --}}
<div class="catalogue-wrap">

  {{-- ── SIDEBAR FILTRES ── --}}
  <aside class="filter-sidebar">
    <div class="filter-title">
      <span>🎛 Filtres</span>
      <button class="filter-reset" onclick="resetFilters()">Réinitialiser</button>
    </div>

    <div class="filter-section">
      <span class="filter-section-label">Type de machine</span>
      <div class="type-chips" id="typeChips">
        <button class="type-chip active" data-type="" onclick="selectType(this)">Tous</button>
        <button class="type-chip" data-type="excavatrice" onclick="selectType(this)">⛏ Excavatrice</button>
        <button class="type-chip" data-type="grue" onclick="selectType(this)">🏗 Grue</button>
        <button class="type-chip" data-type="bulldozer" onclick="selectType(this)">🚜 Bulldozer</button>
        <button class="type-chip" data-type="chargeuse" onclick="selectType(this)">🚛 Chargeuse</button>
        <button class="type-chip" data-type="compacteur" onclick="selectType(this)">🔧 Compacteur</button>
        <button class="type-chip" data-type="nacelle" onclick="selectType(this)">🪜 Nacelle</button>
        <button class="type-chip" data-type="tractopelle" onclick="selectType(this)">🚧 Tractopelle</button>
        <button class="type-chip" data-type="camion" onclick="selectType(this)">🚚 Camion</button>
      </div>
    </div>

    <div class="filter-section">
      <span class="filter-section-label">Prix / jour (MAD)</span>
      <div class="price-inputs">
        <input type="number" id="priceMin" placeholder="Min" class="price-inputs" oninput="applyFilters()"
               style="flex:1;padding:.45rem .6rem;border:1.5px solid var(--cream3);border-radius:8px;
                      font-family:'DM Sans',sans-serif;font-size:.82rem;color:var(--txt-dark);
                      outline:none;text-align:center;">
        <span class="price-sep">—</span>
        <input type="number" id="priceMax" placeholder="Max" oninput="applyFilters()"
               style="flex:1;padding:.45rem .6rem;border:1.5px solid var(--cream3);border-radius:8px;
                      font-family:'DM Sans',sans-serif;font-size:.82rem;color:var(--txt-dark);
                      outline:none;text-align:center;">
      </div>
    </div>

    <div class="filter-section">
      <span class="filter-section-label">Ville</span>
      <input type="text" class="city-input" id="cityFilter" placeholder="Ex: Casablanca" oninput="applyFilters()">
    </div>

    <div class="filter-section">
      <span class="filter-section-label">Disponibilité</span>
      <div class="status-options">
        <div class="status-opt">
          <input type="radio" name="statusFilter" id="sAll" value="" checked onchange="applyFilters()">
          <label for="sAll">Toutes</label>
        </div>
        <div class="status-opt">
          <input type="radio" name="statusFilter" id="sAvail" value="available" onchange="applyFilters()">
          <label for="sAvail">✅ Disponibles uniquement</label>
        </div>
      </div>
    </div>

    {{-- ✅ FIX: bouton carte — plus de style inline width:100% (déjà dans CSS) --}}
    <button class="btn-map-toggle" id="mapToggleBtn" onclick="toggleMap()">
      🗺 Voir sur la carte
    </button>
  </aside>

  {{-- ── MAIN CONTENT ── --}}
  <div>
    {{-- Map --}}
    <div id="leafletMap"></div>

    {{-- Grid --}}
    <div class="machines-grid" id="machinesGrid">
      {{-- Skeletons --}}
      @for($i=0;$i<8;$i++)
      <div style="background:#fff;border-radius:14px;overflow:hidden;border:1px solid rgba(212,175,55,.12)">
        <div class="skeleton" style="height:190px"></div>
        <div style="padding:1rem">
          <div class="skeleton" style="height:10px;width:40%;margin-bottom:.6rem"></div>
          <div class="skeleton" style="height:16px;margin-bottom:.4rem"></div>
          <div class="skeleton" style="height:12px;width:60%;margin-bottom:.8rem"></div>
          <div class="skeleton" style="height:36px;border-radius:9px"></div>
        </div>
      </div>
      @endfor
    </div>
  </div>

</div>

{{-- ══ MODAL RÉSERVATION ══ --}}
<div class="modal-overlay" id="reservationModal">
  <div class="modal-box">
    <div class="modal-header">
      <h2>📋 Réserver cette machine</h2>
      <button class="modal-close" onclick="closeReservationModal()">✕</button>
    </div>
    <div class="modal-body">
      <div class="modal-machine-recap" id="modalMachineRecap">
        <img class="modal-machine-img" id="modalMachineImg" src="" alt="">
        <div>
          <div class="modal-machine-name" id="modalMachineName">—</div>
          <div class="modal-machine-price" id="modalMachinePrice">—</div>
        </div>
      </div>
      <div class="form-row">
        <div class="form-group">
          <label class="form-label">📅 Date de début *</label>
          <input type="date" class="form-control" id="resStartDate" onchange="calcTotal()">
        </div>
        <div class="form-group">
          <label class="form-label">📅 Date de fin *</label>
          <input type="date" class="form-control" id="resEndDate" onchange="calcTotal()">
        </div>
      </div>
      <div class="form-group" style="margin-bottom:.9rem">
        <label class="form-label">💬 Motif / Précisions</label>
        <textarea class="form-control" id="resMotif" rows="2"
                  placeholder="Décrivez votre chantier…" style="resize:vertical"></textarea>
      </div>
      <div class="price-calc" id="priceCalcBox" style="display:none">
        <div>
          <div class="price-calc-label" id="priceCalcLabel">Estimation</div>
          <div style="font-size:.75rem;color:var(--txt-light)">Prix indicatif hors options</div>
        </div>
        <div class="price-calc-value" id="priceCalcVal">0 MAD</div>
      </div>
    </div>
    <div class="modal-footer">
      <button class="btn-outline-sm" onclick="closeReservationModal()">Annuler</button>
      <button class="btn-navy" id="confirmResBtn" onclick="submitReservation()" style="flex:1">
        ✅ Confirmer la réservation
      </button>
    </div>
  </div>
</div>

<div class="toast-ctr" id="toastCtr"></div>

@endsection

@push('scripts')
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
<script>
/* ════════════════════════════════════════════════
   MACHINES INDEX — RENTIFY V7
   Catalogue complet : filtres, rôles, WhatsApp, modal réservation
════════════════════════════════════════════════ */

const TYPE_PHOTO = {
  excavatrice:'/images/img3.png', grue:'/images/img4.png',
  bulldozer:'/images/img1.png',   chargeuse:'/images/img2.png',
  compacteur:'/images/img8.png',  nacelle:'/images/img5.png',
  tractopelle:'/images/img7.png', camion:'/images/img9.png',
};

const TYPE_EMOJI = {
  excavatrice:'⛏', grue:'🏗', bulldozer:'🚜', chargeuse:'🚛',
  compacteur:'🔧', nacelle:'🪜', tractopelle:'🚧', camion:'🚚',
};

/* ══ STATE ══ */
let allMachines  = [];
let filteredList = [];
let currentType  = '';
let currentView  = 'grid';
let mapOpen      = false;
let leafletMap   = null;
let leafletMarkers = [];
let selectedMachineId = null;
let PAGE_SIZE = 12;
let currentPage = 1;

const user  = window.getUser ? window.getUser() : JSON.parse(localStorage.getItem('auth_user')||'null');
const role  = user?.role || 'guest';

/* ══ INIT ══ */
document.addEventListener('DOMContentLoaded', async () => {
  await loadMachines();
  const today = new Date().toISOString().split('T')[0];
  document.getElementById('resStartDate').min = today;
  document.getElementById('resEndDate').min   = today;
});

/* ══ LOAD ══ */
async function loadMachines() {
  try {
    const data = await window.API.get('/api/machines');
    allMachines = Array.isArray(data) ? data : (data.data || data.machines || []);
    applyFilters();
  } catch(e) {
    console.error('loadMachines', e);
    document.getElementById('machinesGrid').innerHTML = `
      <div class="empty-state">
        <div class="empty-state-icon">⚠️</div>
        <h3>Erreur de chargement</h3>
        <p>Impossible de charger les machines. Réessayez.</p>
      </div>`;
  }
}


/* ══ FILTRES ══ */
function selectType(btn) {
  document.querySelectorAll('.type-chip').forEach(c => c.classList.remove('active'));
  btn.classList.add('active');
  currentType = btn.dataset.type;
  currentPage = 1;
  applyFilters();
}

function applyFilters() {
  const q       = document.getElementById('globalSearch').value.toLowerCase();
  const city    = document.getElementById('cityFilter').value.toLowerCase();
  const priceMin= parseFloat(document.getElementById('priceMin').value||0) || 0;
  const priceMax= parseFloat(document.getElementById('priceMax').value||0) || Infinity;
  const status  = document.querySelector('input[name="statusFilter"]:checked')?.value || '';
  const sort    = document.getElementById('sortSelect').value;

  filteredList = allMachines.filter(m => {
    const matchQ = !q || m.name?.toLowerCase().includes(q) ||
                   m.city?.toLowerCase().includes(q) ||
                   m.type?.toLowerCase().includes(q) ||
                   m.description?.toLowerCase().includes(q);
    const matchType   = !currentType || m.type?.toLowerCase() === currentType;
    const matchCity   = !city || m.city?.toLowerCase().includes(city);
    const price       = parseFloat(m.price_per_day||0);
    const matchPrice  = price >= priceMin && price <= priceMax;
    const matchStatus = !status || m.status === status;
    return matchQ && matchType && matchCity && matchPrice && matchStatus;
  });

  filteredList.sort((a,b) => {
    if (sort==='price_asc')  return parseFloat(a.price_per_day)-parseFloat(b.price_per_day);
    if (sort==='price_desc') return parseFloat(b.price_per_day)-parseFloat(a.price_per_day);
    if (sort==='name_asc')   return (a.name||'').localeCompare(b.name||'');
    if (sort==='newest')     return new Date(b.created_at)-new Date(a.created_at);
    return 0;
  });

  document.getElementById('resultsCount').textContent = filteredList.length;
  renderPage();
  if (mapOpen) updateMapMarkers();
}

function resetFilters() {
  document.getElementById('globalSearch').value = '';
  document.getElementById('cityFilter').value = '';
  document.getElementById('priceMin').value = '';
  document.getElementById('priceMax').value = '';
  document.getElementById('sortSelect').value = 'default';
  document.querySelector('input[name="statusFilter"]').checked = true;
  currentType = ''; currentPage = 1;
  document.querySelectorAll('.type-chip').forEach(c => c.classList.remove('active'));
  document.querySelector('.type-chip[data-type=""]').classList.add('active');
  applyFilters();
}

/* ══ RENDER ══ */
function renderPage() {
  const start = (currentPage-1)*PAGE_SIZE;
  const page  = filteredList.slice(start, start+PAGE_SIZE);
  const grid  = document.getElementById('machinesGrid');

  if (!page.length) {
    grid.innerHTML = `
      <div class="empty-state">
        <div class="empty-state-icon">🔍</div>
        <h3>Aucune machine trouvée</h3>
        <p>Essayez de modifier vos filtres de recherche</p>
      </div>`;
    return;
  }

  grid.innerHTML = page.map((m, i) => buildCard(m, i)).join('') + buildPagination();
}

function buildCard(m, i) {
  const img = m.images?.[0]?.path
    ? `/storage/${m.images[0].path}`
    : (TYPE_PHOTO[m.type?.toLowerCase()] || '/images/img1.png');

  const ownerName  = m.owner?.name || 'Propriétaire';
  const ownerInit  = ownerName[0].toUpperCase();
  const ownerPhoto = m.owner?.profile_photo_path
    ? `<img src="/storage/${m.owner.profile_photo_path}" alt="${escH(ownerName)}">`
    : ownerInit;

  const ratings = m.ratings || [];
  const avgRating = ratings.length
    ? (ratings.reduce((s,r)=>s+parseFloat(r.rating||0),0)/ratings.length).toFixed(1)
    : null;

  const isFav = window.isFavorite ? window.isFavorite(m.id) : false;

  const statusMap = {
    available: '<span class="card-badge-status status-available">✅ Disponible</span>',
    unavailable: '<span class="card-badge-status status-unavailable">❌ Indisponible</span>',
    maintenance: '<span class="card-badge-status status-maintenance">🔧 Maintenance</span>',
  };

  const waMsg = encodeURIComponent(
    `Bonjour ${ownerName} ! Je suis intéressé(e) par votre machine "${m.name}" sur Rentify.\n${window.location.origin}/machines/${m.id}`
  );
  const waPhone = m.owner?.phone ? m.owner.phone.replace(/\D/g,'') : '';
  const waHref  = waPhone ? `https://wa.me/212${waPhone.replace(/^0/,'')}?text=${waMsg}` : `https://wa.me/?text=${waMsg}`;

  let reserveBtn = '';
  if (role === 'client') {
    if (m.status === 'available') {
      reserveBtn = `<button class="btn-reserver" onclick="openReservationModal(${m.id})">📋 Réserver</button>`;
    } else {
      reserveBtn = `<button class="btn-reserver" disabled
        style="opacity:.5;cursor:not-allowed;background:var(--cream2);color:var(--txt-mid);border:1.5px solid var(--cream3)">
        ⛔ Indisponible</button>`;
    }
  } else if (role === 'admin') {
    // Admin yqdar ymodifier kol machine
    reserveBtn = `<a href="/machines/${m.id}/edit" class="btn-reserver">✏️ Modifier</a>`;
} else if (role === 'owner') {
    // Owner — yshowliه "Modifier" rir 3la machines dyalh
    if (m.owner?.id === user?.id || m.owner_id === user?.id) {
        reserveBtn = `<a href="/machines/${m.id}/edit" class="btn-reserver">✏️ Modifier</a>`;
    } else if (m.status === 'available') {
        // Machine dyal owner akhor — yqdar yreservi
        reserveBtn = `<button class="btn-reserver" onclick="openReservationModal(${m.id})">📋 Réserver</button>`;
    } else {
        reserveBtn = `<button class="btn-reserver" disabled
            style="opacity:.5;cursor:not-allowed;background:var(--cream2);color:var(--txt-mid);border:1.5px solid var(--cream3)">
            ⛔ Indisponible</button>`;
    }
}else {
    reserveBtn = `<a href="/login" class="btn-reserver">🔐 Se connecter pour réserver</a>`;
  }

  return `
  <div class="machine-card" style="animation-delay:${i*0.05}s" id="mcard-${m.id}">
    <div class="machine-card-img">
      <img src="${img}" alt="${escH(m.name)}" loading="lazy"
           onerror="this.src='${TYPE_PHOTO[m.type?.toLowerCase()]||'/images/img1.png'}'">
      <span class="card-badge-num">#${m.id}</span>
      ${statusMap[m.status]||''}
      <button class="card-fav-btn ${isFav?'active':''}" id="fav-${m.id}"
              onclick="handleFav(${m.id},this)" title="${isFav?'Retirer des favoris':'Ajouter aux favoris'}">
        ${isFav?'❤️':'🤍'}
      </button>
    </div>
    <div class="machine-card-body">
      <span class="card-type-badge">${TYPE_EMOJI[m.type?.toLowerCase()]||'🔧'} ${m.type||'Machine'}</span>
      <div class="card-machine-name">${escH(m.name)}</div>
      <div class="card-city">📍 ${escH(m.city||'—')}</div>

      <div class="card-owner">
        <div class="owner-avatar">${ownerPhoto}</div>
        <div class="owner-name">${escH(ownerName)}</div>
        ${avgRating ? `<div class="owner-rating">⭐ ${avgRating}</div>` : ''}
      </div>

      <div class="card-price-row">
        <span class="card-price-day">${parseFloat(m.price_per_day||0).toLocaleString('fr-MA')}</span>
        <span class="card-price-unit">MAD/jour</span>
        ${m.price_per_hour ? `<span class="card-price-hour">${parseFloat(m.price_per_hour).toLocaleString('fr-MA')} MAD/h</span>` : ''}
      </div>

      <div class="card-actions">
        ${reserveBtn}
        <a href="${waHref}" target="_blank" class="btn-wa" title="Contacter via WhatsApp">💬</a>
        <a href="/machines/${m.id}" class="btn-detail" title="Voir les détails">👁</a>
      </div>
    </div>
  </div>`;
}

function buildPagination() {
  const total = filteredList.length;
  const pages = Math.ceil(total / PAGE_SIZE);
  if (pages <= 1) return '';

  let html = '<div class="pagination-wrap">';
  html += `<button class="page-btn" onclick="goPage(${currentPage-1})" ${currentPage===1?'disabled':''}>‹</button>`;
  for (let p=1; p<=pages; p++) {
    html += `<button class="page-btn ${p===currentPage?'active':''}" onclick="goPage(${p})">${p}</button>`;
  }
  html += `<button class="page-btn" onclick="goPage(${currentPage+1})" ${currentPage===pages?'disabled':''}>›</button>`;
  html += '</div>';
  return html;
}

function goPage(p) {
  const pages = Math.ceil(filteredList.length / PAGE_SIZE);
  if (p < 1 || p > pages) return;
  currentPage = p;
  renderPage();
  window.scrollTo({ top: 300, behavior: 'smooth' });
}

/* ══ VIEW TOGGLE ══ */
function setView(v) {
  currentView = v;
  const grid = document.getElementById('machinesGrid');
  grid.classList.toggle('list-view', v === 'list');
  document.getElementById('btnGrid').classList.toggle('active', v === 'grid');
  document.getElementById('btnList').classList.toggle('active', v === 'list');
}

/* ══ MAP ══ */
function toggleMap() {
  const el  = document.getElementById('leafletMap');
  const btn = document.getElementById('mapToggleBtn');
  mapOpen = !mapOpen;
  el.classList.toggle('open', mapOpen);
  btn.innerHTML = mapOpen ? '🗺 Masquer la carte' : '🗺 Voir sur la carte';

  if (mapOpen && !leafletMap) {
    leafletMap = L.map('leafletMap').setView([31.7917, -7.0926], 5);
    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
      attribution:'© OpenStreetMap'
    }).addTo(leafletMap);
    updateMapMarkers();
  } else if (mapOpen) {
    leafletMap.invalidateSize();
    updateMapMarkers();
  }
}

function updateMapMarkers() {
  if (!leafletMap) return;
  leafletMarkers.forEach(m => m.remove());
  leafletMarkers = [];

  filteredList.filter(m => m.latitude && m.longitude).forEach(m => {
    const marker = L.marker([m.latitude, m.longitude])
      .addTo(leafletMap)
      .bindPopup(`
        <strong>${escH(m.name)}</strong><br>
        📍 ${escH(m.city||'')}<br>
        💰 ${parseFloat(m.price_per_day||0).toLocaleString('fr-MA')} MAD/j<br>
        <a href="/machines/${m.id}" style="color:#D4AF37;font-weight:700">Voir →</a>
      `);
    leafletMarkers.push(marker);
  });
}

/* ══ FAVORIS ══ */
function handleFav(id, btn) {
  const added = window.toggleFavorite ? window.toggleFavorite(id) : false;
  btn.textContent  = added ? '❤️' : '🤍';
  btn.classList.toggle('active', added);
  window.refreshFavsBadge?.();
  showToast(added ? 'success' : 'info', added ? '❤️ Ajouté aux favoris' : '🤍 Retiré des favoris');
}

/* ══ MODAL RÉSERVATION ══ */
function openReservationModal(machineId) {
  if (!window.getToken || !window.getToken()) {
    window.location.href = '/login';
    return;
  }
  const m = allMachines.find(x => x.id == machineId);
  if (!m) return;
  selectedMachineId = machineId;

  const img = m.images?.[0]?.path ? `/storage/${m.images[0].path}` : (TYPE_PHOTO[m.type?.toLowerCase()]||'/images/img1.png');
  document.getElementById('modalMachineImg').src   = img;
  document.getElementById('modalMachineName').textContent = m.name;
  document.getElementById('modalMachinePrice').textContent =
    `${parseFloat(m.price_per_day||0).toLocaleString('fr-MA')} MAD/jour`;

  document.getElementById('resStartDate').value = '';
  document.getElementById('resEndDate').value   = '';
  document.getElementById('resMotif').value     = '';
  document.getElementById('priceCalcBox').style.display = 'none';
  document.getElementById('reservationModal').classList.add('open');
}

function closeReservationModal() {
  document.getElementById('reservationModal').classList.remove('open');
  selectedMachineId = null;
}

function calcTotal() {
  const m = allMachines.find(x => x.id == selectedMachineId);
  if (!m) return;
  const s = new Date(document.getElementById('resStartDate').value);
  const e = new Date(document.getElementById('resEndDate').value);
  if (isNaN(s) || isNaN(e) || e <= s) {
    document.getElementById('priceCalcBox').style.display = 'none';
    return;
  }
  const days  = Math.ceil((e-s)/(1000*60*60*24));
  const total = days * parseFloat(m.price_per_day||0);
  document.getElementById('priceCalcLabel').textContent = `${days} jour${days>1?'s':''} × ${parseFloat(m.price_per_day).toLocaleString('fr-MA')} MAD`;
  document.getElementById('priceCalcVal').textContent   = total.toLocaleString('fr-MA') + ' MAD';
  document.getElementById('priceCalcBox').style.display = 'flex';
}

async function submitReservation() {
  const start = document.getElementById('resStartDate').value;
  const end   = document.getElementById('resEndDate').value;
  if (!start || !end) {
    showToast('error', '⚠️ Veuillez choisir les dates'); return;
  }
  if (new Date(end) <= new Date(start)) {
    showToast('error', '⚠️ La date de fin doit être après le début'); return;
  }

  const m = allMachines.find(x => x.id == selectedMachineId);
  const days  = Math.ceil((new Date(end)-new Date(start))/(1000*60*60*24));
  const total = days * parseFloat(m?.price_per_day||0);

  const btn = document.getElementById('confirmResBtn');
  btn.disabled = true; btn.innerHTML = '⏳ Envoi…';

  try {
    const res = await window.API.post('/api/reservations', {
      machine_id:  selectedMachineId,
      start_date:  start,
      end_date:    end,
      motif:       document.getElementById('resMotif').value.trim(),
      total_price: total,
    });

    if (res.ok) {
      showToast('success', '🎉 Réservation envoyée ! Le propriétaire va vous confirmer.');
      closeReservationModal();
    } else {
      const msg = res.data?.message || Object.values(res.data?.errors||{}).flat()[0] || 'Erreur';
      showToast('error', '❌ ' + msg);
    }
  } catch(e) {
    showToast('error', '❌ Erreur réseau');
  } finally {
    btn.disabled = false; btn.innerHTML = '✅ Confirmer la réservation';
  }
}

/* ══ UTILS ══ */
function escH(s) {
  if (!s) return '';
  return String(s).replace(/&/g,'&amp;').replace(/</g,'&lt;').replace(/>/g,'&gt;').replace(/"/g,'&quot;');
}

function showToast(type, msg) {
  const ctr = document.getElementById('toastCtr');
  const el  = document.createElement('div');
  el.className = `toast ${type}`;
  el.innerHTML = msg;
  ctr.appendChild(el);
  requestAnimationFrame(() => el.classList.add('show'));
  setTimeout(() => { el.classList.remove('show'); setTimeout(()=>el.remove(), 400); }, 3500);
}

document.getElementById('reservationModal').addEventListener('click', e => {
  if (e.target === e.currentTarget) closeReservationModal();
});
document.addEventListener('keydown', e => {
  if (e.key === 'Escape') closeReservationModal();
});
</script>
@endpush