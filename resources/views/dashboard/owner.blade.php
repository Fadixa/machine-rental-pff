
@extends('layouts.app')

@section('title', 'Dashboard Propriétaire — Rentify')

@push('styles')
<style>
/* ═══════════════════════════════════════════
   DASHBOARD OWNER — RENTIFY V10
   Gold / Crème Design System — ZÉRO NAVY BG
═══════════════════════════════════════════ */
:root {
  --gold:#D4AF37; --gold-dk:#9A7D20; --gold-lt:#F5E88A; --gold-pale:#FEF9E7;
  --gold-glow:rgba(212,175,55,.25);
  --navy:#0F1B2D; --navy2:#162540; --navy3:#1E3356;
  --cream:#FAF7F0; --cream2:#F0EBE0; --cream3:#E8DDD0;
  --txt-dark:#1a1a2e; --txt-mid:#5a5660; --txt-light:#9992a4;
  --green:#22c55e; --red:#ef4444;
  --radius:14px; --shadow:0 4px 24px rgba(15,27,45,.08);
}

body { background:var(--cream); font-family:'DM Sans',sans-serif; }

.owner-layout {
  display:grid;
  grid-template-columns:260px 1fr;
  min-height:calc(100vh - 64px);
  padding-top:0;
}

.owner-sidebar {
  background:var(--cream);
  padding:2rem 1.25rem;
  position:sticky;
  top:0;
  height:100vh;
  overflow-y:auto;
  border-right:1.5px solid rgba(212,175,55,.2);
}

.sidebar-profile {
  text-align:center;
  padding-bottom:1.5rem;
  border-bottom:1px solid rgba(212,175,55,.2);
  margin-bottom:1.5rem;
}
.sidebar-avatar {
  width:72px; height:72px; border-radius:50%;
  border:3px solid var(--gold);
  object-fit:cover; margin:0 auto 0.75rem;
  display:block;
  background:var(--cream2);
}
.sidebar-avatar-placeholder {
  width:72px; height:72px; border-radius:50%;
  border:3px solid var(--gold);
  background:var(--gold-pale);
  display:flex; align-items:center; justify-content:center;
  margin:0 auto 0.75rem;
  font-size:1.75rem; color:var(--gold);
}
.sidebar-name { color:var(--navy); font-weight:700; font-size:.95rem; }
.sidebar-role {
  display:inline-block; margin-top:.3rem;
  background:rgba(212,175,55,.15); color:var(--gold-dk);
  font-size:.72rem; font-weight:600; letter-spacing:.08em;
  padding:.2rem .7rem; border-radius:20px; text-transform:uppercase;
}

.sidebar-nav { list-style:none; padding:0; margin:0; }
.sidebar-nav li + li { margin-top:.25rem; }
.sidebar-nav a {
  display:flex; align-items:center; gap:.75rem;
  padding:.7rem 1rem; border-radius:10px;
  color:var(--txt-mid); font-size:.88rem; font-weight:500;
  text-decoration:none; transition:all .2s;
}
.sidebar-nav a:hover { background:rgba(212,175,55,.1); color:var(--navy); }
.sidebar-nav a.active {
  background:rgba(212,175,55,.18);
  color:var(--gold-dk);
  font-weight:700;
}
.sidebar-nav a .nav-icon {
  width:20px; text-align:center; font-size:1rem;
  flex-shrink:0;
}
.sidebar-section-label {
  color:var(--txt-light); font-size:.7rem; font-weight:700;
  letter-spacing:.1em; text-transform:uppercase;
  padding:.5rem 1rem .3rem; margin-top:.5rem;
}

.owner-main { padding:2rem 2.5rem; overflow-x:hidden; }

.page-header {
  display:flex; align-items:center; justify-content:space-between;
  margin-bottom:2rem;
}
.page-header h1 {
  font-family:'Playfair Display',serif;
  font-size:1.8rem; color:var(--navy);
  margin:0;
}
.page-header p { color:var(--txt-mid); font-size:.88rem; margin:.25rem 0 0; }

.stats-grid {
  display:grid;
  grid-template-columns:repeat(4,1fr);
  gap:1.25rem;
  margin-bottom:2rem;
}
.stat-card {
  background:#fff;
  border:1px solid rgba(212,175,55,.12);
  border-radius:var(--radius);
  padding:1.4rem 1.5rem;
  box-shadow:var(--shadow);
  transition:transform .2s, box-shadow .2s;
  position:relative; overflow:hidden;
}
.stat-card:hover { transform:translateY(-3px); box-shadow:0 8px 32px rgba(15,27,45,.12); }
.stat-card::before {
  content:''; position:absolute;
  top:0; left:0; right:0; height:3px;
  background:linear-gradient(90deg,var(--gold),var(--gold-dk));
}
.stat-icon {
  width:42px; height:42px; border-radius:10px;
  background:var(--gold-pale); display:flex;
  align-items:center; justify-content:center;
  font-size:1.2rem; margin-bottom:.9rem;
}
.stat-value {
  font-size:1.9rem; font-weight:800;
  color:var(--navy); line-height:1;
  font-family:'Playfair Display',serif;
}
.stat-label { color:var(--txt-mid); font-size:.8rem; margin-top:.3rem; }
.stat-delta { font-size:.75rem; font-weight:600; margin-top:.4rem; }
.stat-delta.up { color:var(--green); }
.stat-delta.down { color:var(--red); }

.tab-panel { display:none; }
.tab-panel.active { display:block; }

.toolbar {
  display:flex; align-items:center; gap:.75rem;
  margin-bottom:1.25rem; flex-wrap:wrap;
}
.toolbar-search {
  flex:1; min-width:200px;
  position:relative;
}
.toolbar-search input {
  width:100%; padding:.55rem .9rem .55rem 2.4rem;
  border:1px solid var(--cream3); border-radius:10px;
  background:#fff; font-family:'DM Sans',sans-serif; font-size:.88rem;
  color:var(--navy); outline:none; transition:border .2s;
}
.toolbar-search input:focus { border-color:var(--gold); }
.toolbar-search .search-icon {
  position:absolute; left:.8rem; top:50%; transform:translateY(-50%);
  color:var(--txt-light); font-size:.9rem;
}
.toolbar select {
  padding:.55rem .9rem; border:1px solid var(--cream3);
  border-radius:10px; background:#fff;
  font-family:'DM Sans',sans-serif; font-size:.85rem;
  color:var(--navy); outline:none; cursor:pointer;
  transition:border .2s;
}
.toolbar select:focus { border-color:var(--gold); }

.btn-navy {
  background:var(--gold); color:var(--txt-dark);
  border:2px solid var(--gold);
  padding:.55rem 1.25rem; border-radius:10px;
  font-family:'DM Sans',sans-serif; font-weight:700; font-size:.85rem;
  cursor:pointer; transition:all .2s; display:inline-flex;
  align-items:center; gap:.5rem; text-decoration:none; white-space:nowrap;
}
.btn-navy:hover { background:var(--gold-dk); border-color:var(--gold-dk); color:var(--txt-dark); }
.btn-gold {
  background:var(--gold); color:var(--txt-dark);
  border:2px solid var(--gold);
  padding:.55rem 1.25rem; border-radius:10px;
  font-family:'DM Sans',sans-serif; font-weight:700; font-size:.85rem;
  cursor:pointer; transition:all .2s; display:inline-flex;
  align-items:center; gap:.5rem; text-decoration:none; white-space:nowrap;
}
.btn-gold:hover { background:var(--gold-dk); border-color:var(--gold-dk); }
.btn-outline {
  background:transparent; color:var(--txt-dark);
  border:2px solid var(--cream3);
  padding:.55rem 1.25rem; border-radius:10px;
  font-family:'DM Sans',sans-serif; font-weight:600; font-size:.85rem;
  cursor:pointer; transition:all .2s; display:inline-flex;
  align-items:center; gap:.5rem; text-decoration:none; white-space:nowrap;
}
.btn-outline:hover { border-color:var(--gold); color:var(--gold-dk); }
.btn-sm { padding:.35rem .8rem; font-size:.78rem; }
.btn-icon {
  width:34px; height:34px; border-radius:8px;
  display:inline-flex; align-items:center; justify-content:center;
  cursor:pointer; border:none; transition:all .2s;
  background:transparent;
}
.btn-icon:hover { background:var(--cream2); }
.btn-icon.danger:hover { background:#fee2e2; color:var(--red); }
.btn-icon.success:hover { background:#dcfce7; color:var(--green); }

.machines-grid {
  display:grid;
  grid-template-columns:repeat(auto-fill,minmax(280px,1fr));
  gap:1.25rem;
}
.machine-card {
  background:#fff;
  border:1px solid rgba(212,175,55,.12);
  border-radius:var(--radius);
  overflow:hidden;
  box-shadow:var(--shadow);
  transition:transform .2s, box-shadow .2s;
}
.machine-card:hover { transform:translateY(-4px); box-shadow:0 12px 36px rgba(15,27,45,.13); }
.machine-card-img {
  height:160px; overflow:hidden; position:relative;
  background:var(--cream2);
}
.machine-card-img img {
  width:100%; height:100%; object-fit:cover;
  transition:transform .4s;
}
.machine-card:hover .machine-card-img img { transform:scale(1.06); }
.machine-card-status {
  position:absolute; top:.7rem; right:.7rem;
  padding:.25rem .65rem; border-radius:20px;
  font-size:.7rem; font-weight:700; letter-spacing:.05em;
}
.status-available   { background:#dcfce7; color:#16a34a; }
.status-unavailable { background:#fee2e2; color:#dc2626; }
.status-maintenance { background:#FEF9E7; color:var(--gold-dk); }

.machine-card-3d {
  position:absolute; top:.7rem; left:.7rem;
  width:28px; height:28px; border-radius:6px;
  background:rgba(250,247,240,.85); backdrop-filter:blur(4px);
  display:flex; align-items:center; justify-content:center;
  cursor:pointer; color:var(--gold-dk); font-size:.75rem;
  transition:all .2s; z-index:2;
  border:1px solid rgba(212,175,55,.3);
}
.machine-card-3d:hover { background:var(--gold); color:var(--txt-dark); }

.machine-card-body { padding:1.1rem 1.25rem; }
.machine-card-type {
  font-size:.7rem; font-weight:700; letter-spacing:.1em;
  text-transform:uppercase; color:var(--gold-dk);
  margin-bottom:.3rem;
}
.machine-card-name { font-weight:700; color:var(--navy); font-size:1rem; margin-bottom:.25rem; }
.machine-card-city { color:var(--txt-light); font-size:.8rem; }
.machine-card-price {
  display:flex; align-items:baseline; gap:.25rem;
  margin:.75rem 0 .5rem;
}
.machine-card-price strong { font-size:1.1rem; color:var(--navy); font-weight:800; }
.machine-card-price span { font-size:.78rem; color:var(--txt-mid); }
.machine-card-actions {
  display:flex; gap:.5rem; padding:.75rem 1.25rem;
  border-top:1px solid var(--cream2);
  background:var(--cream);
}

.mini-viewer-modal {
  position:fixed; inset:0; z-index:9999;
  background:rgba(10,16,28,.75); backdrop-filter:blur(8px);
  display:flex; align-items:center; justify-content:center;
  opacity:0; pointer-events:none; transition:opacity .3s;
}
.mini-viewer-modal.open { opacity:1; pointer-events:all; }
.mini-viewer-box {
  background:var(--cream);
  border-radius:18px;
  padding:1.5rem; width:440px; max-width:95vw;
  border:1.5px solid rgba(212,175,55,.3);
  transform:scale(.9); transition:transform .3s;
  box-shadow:0 24px 64px rgba(10,16,28,.2);
}
.mini-viewer-modal.open .mini-viewer-box { transform:scale(1); }
.mini-viewer-header {
  display:flex; align-items:center; justify-content:space-between;
  margin-bottom:1rem;
}
.mini-viewer-header h3 { color:var(--navy); font-size:1rem; margin:0; font-weight:700; }
.mini-viewer-close {
  background:var(--cream2); border:none;
  color:var(--txt-dark); width:32px; height:32px; border-radius:8px;
  cursor:pointer; font-size:1.1rem; transition:all .2s;
}
.mini-viewer-close:hover { background:var(--gold-pale); color:var(--gold-dk); border:1px solid var(--gold); }
#miniViewerCanvas {
  width:100%; border-radius:10px;
  height:280px; display:block;
  background:linear-gradient(135deg,#0d1a2e,#162540);
}

.table-wrapper {
  background:#fff;
  border:1px solid rgba(212,175,55,.12);
  border-radius:var(--radius);
  overflow:hidden;
  box-shadow:var(--shadow);
}
.rentify-table { width:100%; border-collapse:collapse; font-size:.85rem; }
.rentify-table thead tr {
  background:var(--cream);
  border-bottom:2px solid var(--cream3);
}
.rentify-table th {
  padding:.85rem 1.1rem;
  text-align:left; font-weight:700;
  color:var(--navy); font-size:.78rem;
  letter-spacing:.05em; text-transform:uppercase;
  white-space:nowrap;
}
.rentify-table td {
  padding:.85rem 1.1rem;
  color:var(--txt-mid); border-bottom:1px solid var(--cream2);
  vertical-align:middle;
}
.rentify-table tbody tr:last-child td { border-bottom:none; }
.rentify-table tbody tr:hover td { background:var(--cream); }

.badge {
  display:inline-flex; align-items:center; gap:.3rem;
  padding:.25rem .7rem; border-radius:20px;
  font-size:.72rem; font-weight:700; letter-spacing:.04em;
  white-space:nowrap;
}
.badge-pending   { background:#FEF9E7; color:var(--gold-dk); }
.badge-accepted  { background:#dcfce7; color:#15803d; }
.badge-rejected  { background:#fee2e2; color:#b91c1c; }
.badge-completed { background:rgba(212,175,55,.15); color:var(--gold-dk); }
.badge-cancelled { background:var(--cream3); color:var(--txt-mid); }

.client-info { display:flex; align-items:center; gap:.6rem; }
.client-avatar {
  width:30px; height:30px; border-radius:50%;
  background:var(--cream3); display:flex; align-items:center; justify-content:center;
  font-size:.75rem; font-weight:700; color:var(--navy); flex-shrink:0;
}

.res-actions { display:flex; gap:.4rem; }

.modal-overlay {
  position:fixed; inset:0; z-index:8888;
  background:rgba(10,16,28,.7); backdrop-filter:blur(6px);
  display:flex; align-items:center; justify-content:center;
  opacity:0; pointer-events:none; transition:opacity .3s;
}
.modal-overlay.open { opacity:1; pointer-events:all; }
.modal-box {
  background:#fff; border-radius:18px;
  width:680px; max-width:96vw; max-height:90vh;
  overflow-y:auto; box-shadow:0 24px 80px rgba(10,16,28,.25);
  transform:translateY(20px); transition:transform .3s;
}
.modal-overlay.open .modal-box { transform:translateY(0); }
.modal-header {
  display:flex; align-items:center; justify-content:space-between;
  padding:1.4rem 1.75rem; border-bottom:1px solid var(--cream3);
  position:sticky; top:0; background:#fff; z-index:1;
}
.modal-header h2 {
  font-family:'Playfair Display',serif;
  font-size:1.25rem; color:var(--navy); margin:0;
}
.modal-close {
  width:36px; height:36px; border-radius:8px;
  background:var(--cream2); border:none; cursor:pointer;
  font-size:1.1rem; transition:all .2s;
}
.modal-close:hover { background:var(--cream3); }
.modal-body { padding:1.75rem; }
.modal-footer {
  display:flex; justify-content:flex-end; gap:.75rem;
  padding:1.25rem 1.75rem; border-top:1px solid var(--cream3);
  background:var(--cream);
}

.form-grid { display:grid; grid-template-columns:1fr 1fr; gap:1.1rem; }
.form-group { display:flex; flex-direction:column; gap:.4rem; }
.form-group.full { grid-column:1/-1; }
.form-label { font-size:.8rem; font-weight:700; color:var(--navy); letter-spacing:.03em; }
.form-control {
  padding:.6rem .9rem;
  border:1.5px solid var(--cream3); border-radius:10px;
  font-family:'DM Sans',sans-serif; font-size:.88rem;
  color:var(--navy); outline:none; background:#fff;
  transition:border .2s;
}
.form-control:focus { border-color:var(--gold); }
.form-control::placeholder { color:var(--txt-light); }
textarea.form-control { resize:vertical; min-height:90px; }
select.form-control { cursor:pointer; }

.upload-zone {
  border:2px dashed var(--cream3); border-radius:12px;
  padding:2rem; text-align:center; cursor:pointer;
  transition:all .2s; position:relative;
}
.upload-zone:hover { border-color:var(--gold); background:var(--gold-pale); }
.upload-zone input { position:absolute; inset:0; opacity:0; cursor:pointer; }
.upload-zone-icon { font-size:2rem; margin-bottom:.5rem; }
.upload-zone p { color:var(--txt-mid); font-size:.85rem; margin:0; }
.upload-zone p strong { color:var(--navy); }

.img-preview-grid { display:flex; gap:.5rem; flex-wrap:wrap; margin-top:.75rem; }
.img-preview-item {
  position:relative; width:72px; height:72px;
  border-radius:8px; overflow:hidden; border:2px solid var(--cream3);
}
.img-preview-item img { width:100%; height:100%; object-fit:cover; }
.img-preview-remove {
  position:absolute; top:2px; right:2px;
  width:18px; height:18px; border-radius:4px;
  background:rgba(0,0,0,.6); color:#fff;
  font-size:.65rem; display:flex; align-items:center; justify-content:center;
  cursor:pointer; border:none; transition:background .2s;
}
.img-preview-remove:hover { background:var(--red); }

.dispo-group { display:flex; gap:.5rem; flex-wrap:wrap; }
.dispo-btn {
  padding:.45rem 1rem; border-radius:20px;
  font-size:.8rem; font-weight:700; cursor:pointer;
  border:2px solid var(--cream3);
  background:#fff; color:var(--txt-mid); transition:all .2s;
}
.dispo-btn[data-val="available"].active    { border-color:var(--green); background:#dcfce7; color:#15803d; }
.dispo-btn[data-val="unavailable"].active  { border-color:var(--red); background:#fee2e2; color:#dc2626; }
.dispo-btn[data-val="maintenance"].active  { border-color:var(--gold); background:#FEF9E7; color:var(--gold-dk); }

.confirm-overlay {
  position:fixed; inset:0; z-index:9999;
  background:rgba(10,16,28,.75); backdrop-filter:blur(6px);
  display:flex; align-items:center; justify-content:center;
  opacity:0; pointer-events:none; transition:opacity .25s;
}
.confirm-overlay.open { opacity:1; pointer-events:all; }
.confirm-box {
  background:#fff; border-radius:16px;
  padding:2rem 2.25rem; width:400px; max-width:92vw;
  text-align:center;
  border:1.5px solid rgba(212,175,55,.15);
}
.confirm-icon { font-size:2.5rem; margin-bottom:1rem; }
.confirm-box h3 { color:var(--navy); font-size:1.1rem; margin:0 0 .5rem; }
.confirm-box p  { color:var(--txt-mid); font-size:.88rem; margin:0 0 1.5rem; }
.confirm-btns   { display:flex; gap:.75rem; justify-content:center; }

.toast-container {
  position:fixed; bottom:2rem; right:2rem;
  z-index:99999; display:flex; flex-direction:column; gap:.6rem;
}
.toast {
  display:flex; align-items:center; gap:.75rem;
  background:#fff; border:1px solid var(--cream3);
  border-radius:12px; padding:.85rem 1.25rem;
  box-shadow:0 8px 32px rgba(10,16,28,.15);
  font-size:.85rem; font-weight:600; color:var(--txt-dark);
  transform:translateX(120%); transition:transform .35s cubic-bezier(.34,1.56,.64,1);
  min-width:240px; max-width:320px;
}
.toast.show { transform:translateX(0); }
.toast-icon  { font-size:1.1rem; flex-shrink:0; }
.toast.success { border-left:4px solid var(--green); }
.toast.error   { border-left:4px solid var(--red); }
.toast.info    { border-left:4px solid var(--gold); }

.chart-card {
  background:#fff; border:1px solid rgba(212,175,55,.12);
  border-radius:var(--radius); padding:1.5rem;
  box-shadow:var(--shadow);
}
.chart-card h3 {
  font-family:'Playfair Display',serif;
  font-size:1.05rem; color:var(--navy); margin:0 0 1.25rem;
  display:flex; align-items:center; gap:.5rem;
}

.empty-state { text-align:center; padding:4rem 2rem; }
.empty-state-icon { font-size:3rem; margin-bottom:1rem; opacity:.5; }
.empty-state h3 { color:var(--navy); font-size:1.1rem; margin:0 0 .5rem; }
.empty-state p  { color:var(--txt-mid); font-size:.88rem; }

.skeleton {
  background:linear-gradient(90deg,var(--cream2) 25%,var(--cream3) 50%,var(--cream2) 75%);
  background-size:200% 100%;
  animation:shimmer 1.5s infinite;
  border-radius:8px;
}
@keyframes shimmer { 0%{background-position:200% 0} 100%{background-position:-200% 0} }

.revenue-row { display:flex; align-items:center; gap:.75rem; padding:.6rem 0; border-bottom:1px solid var(--cream2); }
.revenue-row:last-child { border-bottom:none; }
.revenue-bar-wrap { flex:1; background:var(--cream2); border-radius:4px; height:6px; }
.revenue-bar { height:100%; border-radius:4px; background:linear-gradient(90deg,var(--gold),var(--gold-dk)); }
.revenue-amount { font-weight:700; color:var(--navy); font-size:.88rem; min-width:80px; text-align:right; }

/* ── Tab badge sidebar ── */
.tab-badge {
  background:var(--gold); color:var(--txt-dark);
  font-size:.68rem; font-weight:700;
  padding:.1rem .45rem; border-radius:20px;
  min-width:18px; text-align:center;
  margin-left:auto;
}

@media(max-width:1200px) { .stats-grid { grid-template-columns:repeat(2,1fr); } }
@media(max-width:900px) {
  .owner-layout { grid-template-columns:1fr; }
  .owner-sidebar {
    position:static; height:auto;
    display:flex; flex-wrap:wrap; gap:.5rem;
    padding:1rem; border-bottom:1.5px solid rgba(212,175,55,.2); border-right:none;
  }
  .sidebar-profile { display:none; }
  .sidebar-section-label { display:none; }
  .owner-main { padding:1.5rem 1.25rem; }
}
@media(max-width:600px) {
  .stats-grid { grid-template-columns:1fr 1fr; }
  .form-grid { grid-template-columns:1fr; }
  .machines-grid { grid-template-columns:1fr; }
}
</style>
@endpush

@section('content')
<div class="owner-layout" id="ownerLayout">

  <!-- SIDEBAR -->
  <aside class="owner-sidebar">
    <div class="sidebar-profile">
      <div id="sidebarAvatarWrap">
        <div class="sidebar-avatar-placeholder">👤</div>
      </div>
      <div class="sidebar-name" id="sidebarName">Chargement…</div>
      <span class="sidebar-role">Propriétaire</span>
    </div>

    <ul class="sidebar-nav">
      <li><span class="sidebar-section-label">Principal</span></li>
      <li>
        <a href="#" class="active" data-tab="overview" onclick="switchSideTab('overview',this);return false;">
          <span class="nav-icon">📊</span> Vue d'ensemble
        </a>
      </li>
      <li>
        <a href="#" data-tab="machines" onclick="switchSideTab('machines',this);return false;">
          <span class="nav-icon">🏗</span> Mes Machines
          <span class="tab-badge" id="sideNavMachinesBadge">0</span>
        </a>
      </li>
      <li>
        <a href="#" data-tab="reservations" onclick="switchSideTab('reservations',this);return false;">
          <span class="nav-icon">📋</span> Réservations reçues
          <span class="tab-badge" id="sideNavResBadge" style="background:#ef4444;color:#fff">0</span>
        </a>
      </li>
      {{-- FIX: Tab "Mes locations" — réservations faites kao client --}}
      <li>
        <a href="#" data-tab="my-bookings" onclick="switchSideTab('my-bookings',this);return false;">
          <span class="nav-icon">🛒</span> Mes locations
          <span class="tab-badge" id="sideNavBookingsBadge" style="background:var(--gold-dk);color:#fff">0</span>
        </a>
      </li>
      <li><span class="sidebar-section-label">Compte</span></li>
      <li>
        <a href="/profile">
          <span class="nav-icon">👤</span> Mon Profil
        </a>
      </li>
      <li>
        <a href="#" onclick="handleLogout();return false;" style="color:#ef4444!important">
          <span class="nav-icon">🚪</span> Déconnexion
        </a>
      </li>
    </ul>
  </aside>

  <!-- MAIN -->
  <main class="owner-main">

    <div class="page-header">
      <div>
        <h1>Dashboard Propriétaire</h1>
        <p id="headerGreeting">Bienvenue sur votre espace de gestion</p>
      </div>
      <button class="btn-gold" onclick="openMachineModal()">
        <span>➕</span> Ajouter une machine
      </button>
    </div>

    <!-- Tab Overview -->
    <div id="tab-overview" class="tab-panel active">
      <div class="stats-grid" id="statsGrid">
        <div class="stat-card skeleton" style="height:130px"></div>
        <div class="stat-card skeleton" style="height:130px"></div>
        <div class="stat-card skeleton" style="height:130px"></div>
        <div class="stat-card skeleton" style="height:130px"></div>
      </div>
      <div style="display:grid;grid-template-columns:1fr 1fr;gap:1.25rem;margin-bottom:2rem" id="chartsRow">
        <div class="chart-card">
          <h3>📈 Revenus mensuels</h3>
          <canvas id="revenueChart" height="180"></canvas>
        </div>
        <div class="chart-card">
          <h3>🗂 Répartition par machine</h3>
          <div id="machineRevenueList"></div>
        </div>
      </div>
      <div class="chart-card">
        <h3>🕐 Réservations récentes</h3>
        <div class="table-wrapper" style="box-shadow:none;border:none">
          <table class="rentify-table" id="recentResTable">
            <thead>
              <tr><th>#</th><th>Client</th><th>Machine</th><th>Période</th><th>Montant</th><th>Statut</th><th>Actions</th></tr>
            </thead>
            <tbody id="recentResTbody">
              <tr><td colspan="7" style="text-align:center;padding:2rem;color:var(--txt-light)">Chargement…</td></tr>
            </tbody>
          </table>
        </div>
      </div>
    </div>

    <!-- Tab Machines -->
    <div id="tab-machines" class="tab-panel">
      <div class="toolbar">
        <div class="toolbar-search">
          <span class="search-icon">🔍</span>
          <input type="text" placeholder="Rechercher une machine…" id="machineSearchInput" oninput="filterMachines()">
        </div>
        <select id="machineTypeFilter" onchange="filterMachines()">
          <option value="">Tous les types</option>
          <option value="excavatrice">Excavatrice</option>
          <option value="grue">Grue</option>
          <option value="bulldozer">Bulldozer</option>
          <option value="chargeuse">Chargeuse</option>
          <option value="compacteur">Compacteur</option>
          <option value="nacelle">Nacelle</option>
          <option value="tractopelle">Tractopelle</option>
          <option value="camion">Camion</option>
        </select>
        <select id="machineStatusFilter" onchange="filterMachines()">
          <option value="">Tous les statuts</option>
          <option value="available">Disponible</option>
          <option value="unavailable">Indisponible</option>
          <option value="maintenance">Maintenance</option>
        </select>
        <button class="btn-gold" onclick="openMachineModal()">
          <span>➕</span> Ajouter
        </button>
      </div>
      <div class="machines-grid" id="machinesGrid">
        @for($i=0;$i<4;$i++)
        <div style="background:#fff;border-radius:14px;overflow:hidden;border:1px solid rgba(212,175,55,.12)">
          <div class="skeleton" style="height:160px"></div>
          <div style="padding:1rem">
            <div class="skeleton" style="height:12px;margin-bottom:.5rem;width:60%"></div>
            <div class="skeleton" style="height:18px;margin-bottom:.5rem"></div>
            <div class="skeleton" style="height:12px;width:40%"></div>
          </div>
        </div>
        @endfor
      </div>
    </div>

    <!-- Tab Réservations reçues -->
    <div id="tab-reservations" class="tab-panel">
      <div class="toolbar">
        <div class="toolbar-search">
          <span class="search-icon">🔍</span>
          <input type="text" placeholder="Rechercher…" id="resSearchInput" oninput="filterReservations()">
        </div>
        <select id="resStatusFilter" onchange="filterReservations()">
          <option value="">Tous les statuts</option>
          <option value="pending">En attente</option>
          <option value="accepted">Acceptée</option>
          <option value="rejected">Refusée</option>
          <option value="completed">Complétée</option>
          <option value="cancelled">Annulée</option>
        </select>
        <select id="resMachineFilter" onchange="filterReservations()">
          <option value="">Toutes les machines</option>
        </select>
      </div>
      <div class="table-wrapper">
        <table class="rentify-table">
          <thead>
            <tr><th>#</th><th>Client</th><th>Machine</th><th>Dates</th><th>Prix total</th><th>Statut</th><th>Actions</th></tr>
          </thead>
          <tbody id="reservationsTbody">
            <tr><td colspan="7" style="text-align:center;padding:2.5rem;color:var(--txt-light)">Chargement…</td></tr>
          </tbody>
        </table>
      </div>
    </div>

    <!-- FIX: Tab "Mes locations" — réservations faites kao client -->
    <div id="tab-my-bookings" class="tab-panel">
      <div class="toolbar">
        <div class="toolbar-search">
          <span class="search-icon">🔍</span>
          <input type="text" placeholder="Rechercher…" id="bookingSearchInput" oninput="filterMyBookings()">
        </div>
        <select id="bookingStatusFilter" onchange="filterMyBookings()">
          <option value="">Tous les statuts</option>
          <option value="pending">En attente</option>
          <option value="accepted">Confirmée</option>
          <option value="rejected">Refusée</option>
          <option value="completed">Terminée</option>
          <option value="cancelled">Annulée</option>
        </select>
      </div>
      <div class="table-wrapper">
        <table class="rentify-table">
          <thead>
            <tr><th>#</th><th>Machine</th><th>Propriétaire</th><th>Dates</th><th>Prix total</th><th>Statut</th><th>Actions</th></tr>
          </thead>
          <tbody id="myBookingsTbody">
            <tr><td colspan="7" style="text-align:center;padding:2.5rem;color:var(--txt-light)">Chargement…</td></tr>
          </tbody>
        </table>
      </div>
    </div>

  </main>
</div>

<!-- MODAL MACHINE -->
<div class="modal-overlay" id="machineModal">
  <div class="modal-box">
    <div class="modal-header">
      <h2 id="machineModalTitle">Ajouter une machine</h2>
      <button class="modal-close" onclick="closeMachineModal()">✕</button>
    </div>
    <div class="modal-body">
      <div class="form-grid">
        <div class="form-group">
          <label class="form-label">Nom de la machine *</label>
          <input type="text" class="form-control" id="machineName" placeholder="Ex: Excavatrice JCB 3CX">
        </div>
        <div class="form-group">
          <label class="form-label">Type *</label>
          <select class="form-control" id="machineType">
            <option value="">Sélectionner…</option>
            <option value="excavatrice">Excavatrice</option>
            <option value="grue">Grue</option>
            <option value="bulldozer">Bulldozer</option>
            <option value="chargeuse">Chargeuse</option>
            <option value="compacteur">Compacteur</option>
            <option value="nacelle">Nacelle</option>
            <option value="tractopelle">Tractopelle</option>
            <option value="camion">Camion</option>
          </select>
        </div>
        <div class="form-group">
          <label class="form-label">Prix / jour (MAD) *</label>
          <input type="number" class="form-control" id="machinePriceDay" placeholder="500">
        </div>
        <div class="form-group">
          <label class="form-label">Prix / heure (MAD)</label>
          <input type="number" class="form-control" id="machinePriceHour" placeholder="75">
        </div>
        <div class="form-group">
          <label class="form-label">Ville *</label>
          <input type="text" class="form-control" id="machineCity" placeholder="Casablanca">
        </div>
        <div class="form-group">
          <label class="form-label">Localisation précise</label>
          <input type="text" class="form-control" id="machineLocation" placeholder="Zone industrielle Ain Sebaâ">
        </div>
        <div class="form-group full">
          <label class="form-label">Description</label>
          <textarea class="form-control" id="machineDescription" placeholder="Décrivez votre machine…"></textarea>
        </div>
        <div class="form-group full">
          <label class="form-label">Disponibilité</label>
          <div class="dispo-group">
            <button type="button" class="dispo-btn active" data-val="available"   onclick="setDispo(this)">✅ Disponible</button>
            <button type="button" class="dispo-btn"        data-val="unavailable" onclick="setDispo(this)">❌ Indisponible</button>
            <button type="button" class="dispo-btn"        data-val="maintenance" onclick="setDispo(this)">🔧 Maintenance</button>
          </div>
        </div>
        <div class="form-group full">
          <label class="form-label">Photos de la machine</label>
          <div class="upload-zone" id="uploadZone">
            <input type="file" id="machineImages" multiple accept="image/*" onchange="previewImages(event)">
            <div class="upload-zone-icon">📸</div>
            <p><strong>Cliquer</strong> ou glisser-déposer les images</p>
            <p style="font-size:.75rem;margin-top:.25rem;color:var(--txt-light)">PNG, JPG jusqu'à 5 Mo chacune</p>
          </div>
          <div class="img-preview-grid" id="imgPreviewGrid"></div>
        </div>
      </div>
    </div>
    <div class="modal-footer">
      <button class="btn-outline" onclick="closeMachineModal()">Annuler</button>
      <button class="btn-gold" id="machineModalSaveBtn" onclick="saveMachine()">
        <span>💾</span> Enregistrer
      </button>
    </div>
  </div>
</div>

<!-- MINI 3D VIEWER -->
<div class="mini-viewer-modal" id="miniViewerModal">
  <div class="mini-viewer-box">
    <div class="mini-viewer-header">
      <h3 id="miniViewerTitle">Vue 3D</h3>
      <button class="mini-viewer-close" onclick="closeMiniViewer()">✕</button>
    </div>
    <canvas id="miniViewerCanvas"></canvas>
    <p style="color:var(--txt-light);font-size:.75rem;text-align:center;margin-top:.75rem">
      🖱 Cliquer + glisser pour orbiter
    </p>
  </div>
</div>

<!-- CONFIRM DIALOG -->
<div class="confirm-overlay" id="confirmOverlay">
  <div class="confirm-box">
    <div class="confirm-icon" id="confirmIcon">⚠️</div>
    <h3 id="confirmTitle">Confirmation</h3>
    <p id="confirmMsg">Êtes-vous sûr de vouloir effectuer cette action ?</p>
    <div class="confirm-btns">
      <button class="btn-outline" onclick="closeConfirm()">Annuler</button>
      <button class="btn-gold" id="confirmOkBtn">Confirmer</button>
    </div>
  </div>
</div>

<div class="toast-container" id="toastContainer"></div>

@endsection

@push('scripts')
<script src="https://cdnjs.cloudflare.com/ajax/libs/three.js/r128/three.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/4.4.1/chart.umd.min.js"></script>
<script>
const TYPE_PHOTO = {
  excavatrice:'/images/img3.png', grue:'/images/img4.png',
  bulldozer:'/images/img1.png', chargeuse:'/images/img2.png',
  compacteur:'/images/img8.png', nacelle:'/images/img5.png',
  tractopelle:'/images/img7.png', camion:'/images/img9.png',
};

const STATUS_BADGE = {
  pending:  '<span class="badge badge-pending">⏳ En attente</span>',
  accepted: '<span class="badge badge-accepted">✅ Acceptée</span>',
  rejected: '<span class="badge badge-rejected">❌ Refusée</span>',
  completed:'<span class="badge badge-completed">🏆 Complétée</span>',
  cancelled:'<span class="badge badge-cancelled">🚫 Annulée</span>',
};

const STATUS_DISPO = {
  available:   '<span class="machine-card-status status-available">✅ Disponible</span>',
  unavailable: '<span class="machine-card-status status-unavailable">❌ Indisponible</span>',
  maintenance: '<span class="machine-card-status status-maintenance">🔧 Maintenance</span>',
};

let allMachines = [];
let allReservations = [];   // réservations REÇUES (machines dyalh)
let allMyBookings   = [];   // réservations FAITES kao client
let editMachineId = null;
let currentDispo = 'available';
let miniViewerAnim = null;
let miniViewerRenderer = null;
let confirmCallback = null;
let revenueChart = null;
let selectedFiles = [];

/* ══ INIT ══ */
document.addEventListener('DOMContentLoaded', async () => {
  const user = window.getUser ? window.getUser() : JSON.parse(localStorage.getItem('auth_user'));
  if (!user || user.role !== 'owner') { window.location.href = '/login'; return; }

  document.getElementById('sidebarName').textContent = user.name || 'Propriétaire';
  document.getElementById('headerGreeting').textContent =
    `Bonjour ${user.name?.split(' ')[0] || ''} 👋 — bienvenue sur votre espace`;
  const avatarWrap = document.getElementById('sidebarAvatarWrap');
  if (user.profile_photo_path) {
    avatarWrap.innerHTML = `<img src="/storage/${user.profile_photo_path}" class="sidebar-avatar" alt="Avatar">`;
  }

  await Promise.all([loadMachines(), loadReservations(), loadMyBookings()]);
  renderStats();
  renderRevenueChart();
  renderMachineRevenueList();
  renderRecentReservations();
  renderMachinesGrid();
  renderReservationsTable();
  renderMyBookingsTable();
});

/* ══ DATA LOADING ══ */
async function loadMachines() {
  try {
    const data = await window.API.get('/api/my-machines');
    allMachines = Array.isArray(data) ? data : (data.data || data.machines || []);
    document.getElementById('sideNavMachinesBadge').textContent = allMachines.length;
    populateMachineFilter();
  } catch(e) { allMachines = []; }
}

async function loadReservations() {
  try {
    const data = await window.API.get('/api/reservations');
    allReservations = Array.isArray(data) ? data : (data.data || data.reservations || []);
    const pending = allReservations.filter(r => r.status === 'pending').length;
    document.getElementById('sideNavResBadge').textContent = pending;
  } catch(e) { allReservations = []; }
}

/* FIX: charge les réservations FAITES par cet owner kao client */
async function loadMyBookings() {
  try {
    const data = await window.API.get('/api/my-bookings');
    allMyBookings = Array.isArray(data) ? data : (data.data || []);
    document.getElementById('sideNavBookingsBadge').textContent = allMyBookings.length;
  } catch(e) {
    /* Fallback: si /api/my-bookings n'existe pas encore, on essaie /api/reservations?as_client=1 */
    try {
      const data = await window.API.get('/api/reservations?as_client=1');
      allMyBookings = Array.isArray(data) ? data : (data.data || []);
      document.getElementById('sideNavBookingsBadge').textContent = allMyBookings.length;
    } catch(e2) { allMyBookings = []; }
  }
}

/* ══ STATS ══ */
function renderStats() {
  const totalMachines = allMachines.length;
  const available = allMachines.filter(m => m.status === 'available').length;
  const totalRes = allReservations.length;
  const pendingRes = allReservations.filter(r => r.status === 'pending').length;
  const revenue = allReservations
    .filter(r => ['accepted','completed'].includes(r.status))
    .reduce((s, r) => s + parseFloat(r.total_price || 0), 0);

  const cards = [
    { icon:'🏗', value: totalMachines, label:'Mes machines', delta:`${available} disponibles`, up: available > 0 },
    { icon:'📋', value: totalRes, label:'Réservations totales', delta:`${pendingRes} en attente`, up: pendingRes === 0 },
    { icon:'💰', value: revenue.toLocaleString('fr-MA') + ' MAD', label:'Revenus générés', delta:'Réservations acceptées', up: true },
    { icon:'⭐', value: calcAvgRating(), label:'Note moyenne', delta:'Sur vos machines', up: true },
  ];

  document.getElementById('statsGrid').innerHTML = cards.map(c => `
    <div class="stat-card">
      <div class="stat-icon">${c.icon}</div>
      <div class="stat-value">${c.value}</div>
      <div class="stat-label">${c.label}</div>
      <div class="stat-delta ${c.up ? 'up' : 'down'}">${c.delta}</div>
    </div>
  `).join('');
}

function calcAvgRating() {
  const ratings = allMachines.flatMap(m => m.ratings || []).map(r => parseFloat(r.rating));
  if (!ratings.length) return '—';
  return (ratings.reduce((a,b) => a+b, 0) / ratings.length).toFixed(1) + ' ⭐';
}

/* ══ CHARTS ══ */
function renderRevenueChart() {
  const months = ['Jan','Fév','Mar','Avr','Mai','Juin','Juil','Aoû','Sep','Oct','Nov','Déc'];
  const monthlyRev = new Array(12).fill(0);
  allReservations
    .filter(r => ['accepted','completed'].includes(r.status))
    .forEach(r => {
      const d = new Date(r.start_date || r.created_at);
      if (!isNaN(d)) monthlyRev[d.getMonth()] += parseFloat(r.total_price || 0);
    });
  const ctx = document.getElementById('revenueChart').getContext('2d');
  if (revenueChart) revenueChart.destroy();
  revenueChart = new Chart(ctx, {
    type: 'bar',
    data: {
      labels: months,
      datasets: [{
        label: 'Revenus (MAD)', data: monthlyRev,
        backgroundColor: 'rgba(212,175,55,.25)', borderColor: '#D4AF37',
        borderWidth: 2, borderRadius: 6, borderSkipped: false,
      }]
    },
    options: {
      responsive: true,
      plugins: { legend: { display: false } },
      scales: {
        y: { beginAtZero:true, grid:{ color:'rgba(212,175,55,.08)' }, ticks:{ color:'#9992a4', font:{size:11} } },
        x: { grid:{ display:false }, ticks:{ color:'#9992a4', font:{size:11} } }
      }
    }
  });
}

function renderMachineRevenueList() {
  const revenueByMachine = {};
  allReservations
    .filter(r => ['accepted','completed'].includes(r.status))
    .forEach(r => {
      const name = r.machine?.name || `Machine #${r.machine_id}`;
      revenueByMachine[name] = (revenueByMachine[name] || 0) + parseFloat(r.total_price || 0);
    });
  const sorted = Object.entries(revenueByMachine).sort((a,b) => b[1]-a[1]).slice(0,6);
  const max = sorted[0]?.[1] || 1;
  const container = document.getElementById('machineRevenueList');
  if (!sorted.length) {
    container.innerHTML = `<div class="empty-state"><div class="empty-state-icon">📊</div><p>Aucun revenu enregistré</p></div>`;
    return;
  }
  container.innerHTML = sorted.map(([name, rev]) => `
    <div class="revenue-row">
      <div style="font-size:.82rem;color:var(--navy);font-weight:600;min-width:120px;max-width:140px;
        overflow:hidden;text-overflow:ellipsis;white-space:nowrap" title="${name}">${name}</div>
      <div class="revenue-bar-wrap"><div class="revenue-bar" style="width:${(rev/max*100).toFixed(1)}%"></div></div>
      <div class="revenue-amount">${rev.toLocaleString('fr-MA')} MAD</div>
    </div>
  `).join('');
}

function renderRecentReservations() {
  const recent = [...allReservations].sort((a,b) => new Date(b.created_at)-new Date(a.created_at)).slice(0,6);
  document.getElementById('recentResTbody').innerHTML = recent.length
    ? recent.map(r => buildResRow(r)).join('')
    : `<tr><td colspan="7"><div class="empty-state"><div class="empty-state-icon">📭</div><p>Aucune réservation</p></div></td></tr>`;
}

/* ══ MACHINES GRID ══ */
function renderMachinesGrid(machines) {
  const list = machines || allMachines;
  const grid = document.getElementById('machinesGrid');
  if (!list.length) {
    grid.innerHTML = `
      <div class="empty-state" style="grid-column:1/-1">
        <div class="empty-state-icon">🏗</div>
        <h3>Aucune machine trouvée</h3>
        <p>Ajoutez votre première machine pour commencer</p>
        <button class="btn-gold" style="margin-top:1rem" onclick="openMachineModal()">➕ Ajouter</button>
      </div>`;
    return;
  }
  grid.innerHTML = list.map(m => buildMachineCard(m)).join('');
}

function buildMachineCard(m) {
  const img = m.images?.[0]?.path
    ? `/storage/${m.images[0].path}`
    : (TYPE_PHOTO[m.type?.toLowerCase()] || '/images/img1.png');
  return `
  <div class="machine-card" id="mc-${m.id}">
    <div class="machine-card-img">
      <img src="${img}" alt="${m.name}" loading="lazy"
        onerror="this.src='${TYPE_PHOTO[m.type?.toLowerCase()]||'/images/img1.png'}'">
      ${STATUS_DISPO[m.status] || ''}
      <button class="machine-card-3d" onclick="openMiniViewer(${m.id},'${escHtml(m.name)}','${m.type?.toLowerCase()}')" title="Vue 3D">🎲</button>
    </div>
    <div class="machine-card-body">
      <div class="machine-card-type">${m.type || 'Machine'}</div>
      <div class="machine-card-name">${escHtml(m.name)}</div>
      <div class="machine-card-city">📍 ${escHtml(m.city || '—')}</div>
      <div class="machine-card-price">
        <strong>${parseFloat(m.price_per_day||0).toLocaleString('fr-MA')}</strong>
        <span>MAD/jour</span>
        ${m.price_per_hour ? `<span style="color:var(--txt-light);margin-left:.5rem">· ${parseFloat(m.price_per_hour).toLocaleString('fr-MA')} MAD/h</span>` : ''}
      </div>
    </div>
    <div class="machine-card-actions">
      <button class="btn-outline btn-sm" onclick="openMachineModal(${m.id})">✏️ Modifier</button>
      <button class="btn-outline btn-sm" onclick="changeStatus(${m.id})">🔄 Statut</button>
      <button class="btn-icon danger" title="Supprimer" onclick="deleteMachine(${m.id},'${escHtml(m.name)}')">🗑</button>
      <a href="https://wa.me/?text=Ma machine ${encodeURIComponent(m.name)} est disponible ! ${encodeURIComponent(window.location.origin+'/machines/'+m.id)}"
         target="_blank" class="btn-icon success" title="Partager WhatsApp">💬</a>
    </div>
  </div>`;
}

function filterMachines() {
  const q = document.getElementById('machineSearchInput').value.toLowerCase();
  const type = document.getElementById('machineTypeFilter').value;
  const status = document.getElementById('machineStatusFilter').value;
  const filtered = allMachines.filter(m =>
    (!q || m.name?.toLowerCase().includes(q) || m.city?.toLowerCase().includes(q)) &&
    (!type || m.type?.toLowerCase() === type) &&
    (!status || m.status === status)
  );
  renderMachinesGrid(filtered);
}

/* ══ RESERVATIONS TABLE (reçues) ══ */
function renderReservationsTable(reservations) {
  const list = reservations || allReservations;
  const tbody = document.getElementById('reservationsTbody');
  if (!list.length) {
    tbody.innerHTML = `<tr><td colspan="7"><div class="empty-state">
      <div class="empty-state-icon">📭</div>
      <h3>Aucune réservation</h3>
      <p>Les demandes de location apparaîtront ici</p>
    </div></td></tr>`;
    return;
  }
  tbody.innerHTML = list.map(r => buildResRow(r, true)).join('');
}

function buildResRow(r, withActions = false) {
  const clientInitial = (r.client?.name || 'C')[0].toUpperCase();
  const start = r.start_date ? new Date(r.start_date).toLocaleDateString('fr-FR',{day:'2-digit',month:'short'}) : '—';
  const end   = r.end_date   ? new Date(r.end_date).toLocaleDateString('fr-FR',{day:'2-digit',month:'short'}) : '—';
  const price = parseFloat(r.total_price||0).toLocaleString('fr-MA');
  const machineName = r.machine?.name || `Machine #${r.machine_id}`;
  let actions = '';
  if (withActions) {
    if (r.status === 'pending') {
      actions = `<div class="res-actions">
        <button class="btn-gold btn-sm" onclick="respondReservation(${r.id},'accept')">✅ Accepter</button>
        <button class="btn-outline btn-sm" onclick="respondReservation(${r.id},'reject')" style="border-color:var(--red);color:var(--red)">❌ Refuser</button>
      </div>`;
    } else if (r.status === 'accepted') {
      actions = `<div class="res-actions">
        <button class="btn-outline btn-sm" onclick="respondReservation(${r.id},'complete')">🏆 Terminer</button>
        <a href="https://wa.me/${r.client?.phone || ''}?text=Bonjour ${encodeURIComponent(r.client?.name||'')} !"
           target="_blank" class="btn-outline btn-sm">💬 WhatsApp</a>
      </div>`;
    } else {
      actions = `<span style="color:var(--txt-light);font-size:.8rem">—</span>`;
    }
  }
  return `<tr>
    <td style="color:var(--txt-light);font-size:.8rem">#${r.id}</td>
    <td><div class="client-info">
      <div class="client-avatar">${clientInitial}</div>
      <div>
        <div style="font-weight:600;color:var(--navy);font-size:.85rem">${escHtml(r.client?.name||'—')}</div>
        <div style="font-size:.75rem;color:var(--txt-light)">${escHtml(r.client?.phone||'')}</div>
      </div>
    </div></td>
    <td style="font-weight:600;color:var(--navy)">${escHtml(machineName)}</td>
    <td style="font-size:.82rem">${start} → ${end}</td>
    <td><strong style="color:var(--navy)">${price} MAD</strong></td>
    <td>${STATUS_BADGE[r.status] || r.status}</td>
    <td>${actions}</td>
  </tr>`;
}

function filterReservations() {
  const q      = document.getElementById('resSearchInput').value.toLowerCase();
  const status = document.getElementById('resStatusFilter').value;
  const machId = document.getElementById('resMachineFilter').value;
  const filtered = allReservations.filter(r =>
    (!q || (r.client?.name||'').toLowerCase().includes(q) || (r.machine?.name||'').toLowerCase().includes(q)) &&
    (!status || r.status === status) &&
    (!machId || String(r.machine_id) === machId)
  );
  renderReservationsTable(filtered);
}

function populateMachineFilter() {
  const sel = document.getElementById('resMachineFilter');
  allMachines.forEach(m => {
    const opt = document.createElement('option');
    opt.value = m.id; opt.textContent = m.name;
    sel.appendChild(opt);
  });
}

/* ══ FIX: MES LOCATIONS TABLE (faites kao client) ══ */
function renderMyBookingsTable(bookings) {
  const list = bookings || allMyBookings;
  const tbody = document.getElementById('myBookingsTbody');
  if (!list.length) {
    tbody.innerHTML = `<tr><td colspan="7"><div class="empty-state">
      <div class="empty-state-icon">🛒</div>
      <h3>Aucune location effectuée</h3>
      <p>Vos réservations chez d'autres propriétaires apparaîtront ici</p>
      <a href="/machines" class="btn-gold" style="margin-top:1rem;display:inline-flex">🔍 Parcourir les machines</a>
    </div></td></tr>`;
    return;
  }
  tbody.innerHTML = list.map(r => {
    const start = r.start_date ? new Date(r.start_date).toLocaleDateString('fr-FR',{day:'2-digit',month:'short'}) : '—';
    const end   = r.end_date   ? new Date(r.end_date).toLocaleDateString('fr-FR',{day:'2-digit',month:'short'}) : '—';
    const price = parseFloat(r.total_price||0).toLocaleString('fr-MA');
    const machineName  = r.machine?.name  || `Machine #${r.machine_id}`;
    const ownerName    = r.machine?.owner?.name  || 'Propriétaire';
    const ownerPhone   = r.machine?.owner?.phone || '';
    const ownerInitial = ownerName[0].toUpperCase();

    let actions = '';
    if (r.status === 'accepted') {
      const waUrl = ownerPhone
        ? `https://wa.me/${ownerPhone.replace(/\D/g,'').replace(/^0/,'212')}?text=${encodeURIComponent('Bonjour, ma réservation "'+machineName+'" sur Rentify a été acceptée.')}`
        : null;
      actions = `<div class="res-actions">
        <button class="btn-outline btn-sm" onclick="downloadContrat(${r.id},this)">📄 Contrat</button>
        ${waUrl ? `<a href="${waUrl}" target="_blank" class="btn-outline btn-sm" style="background:#25D366;color:#fff;border-color:#25D366">💬 WhatsApp</a>` : ''}
      </div>`;
    } else if (r.status === 'pending') {
      actions = `<button class="btn-outline btn-sm" style="border-color:var(--red);color:var(--red)"
        onclick="cancelMyBooking(${r.id})">✕ Annuler</button>`;
    } else {
      actions = `<span style="color:var(--txt-light);font-size:.8rem">—</span>`;
    }

    return `<tr>
      <td style="color:var(--txt-light);font-size:.8rem">#${r.id}</td>
      <td>
        <div style="font-weight:600;color:var(--navy);font-size:.85rem">${escHtml(machineName)}</div>
        <div style="font-size:.75rem;color:var(--txt-light)">${escHtml(r.machine?.type||'')} · ${escHtml(r.machine?.city||'')}</div>
      </td>
      <td><div class="client-info">
        <div class="client-avatar">${ownerInitial}</div>
        <div style="font-weight:600;color:var(--navy);font-size:.85rem">${escHtml(ownerName)}</div>
      </div></td>
      <td style="font-size:.82rem">${start} → ${end}</td>
      <td><strong style="color:var(--navy)">${price} MAD</strong></td>
      <td>${STATUS_BADGE[r.status] || r.status}</td>
      <td>${actions}</td>
    </tr>`;
  }).join('');
}

function filterMyBookings() {
  const q      = document.getElementById('bookingSearchInput').value.toLowerCase();
  const status = document.getElementById('bookingStatusFilter').value;
  const filtered = allMyBookings.filter(r =>
    (!q || (r.machine?.name||'').toLowerCase().includes(q)) &&
    (!status || r.status === status)
  );
  renderMyBookingsTable(filtered);
}

async function cancelMyBooking(id) {
  if (!confirm('Annuler cette réservation ?')) return;
  const r = await window.API.patch(`/api/reservations/${id}/cancel`);
  if (r.ok) {
    showToast('success', '✅ Réservation annulée');
    await loadMyBookings();
    renderMyBookingsTable();
  } else {
    showToast('error', '❌ Erreur');
  }
}

async function downloadContrat(id, btn) {
  const orig = btn.innerHTML;
  btn.disabled = true; btn.innerHTML = '⏳';
  try {
    const r = await fetch(`/api/reservations/${id}/contrat`, {
      headers:{'Authorization':'Bearer '+window.getToken(),'Accept':'application/pdf'}
    });
    if (!r.ok) throw new Error('Erreur');
    const blob = await r.blob(), url = URL.createObjectURL(blob);
    const a = document.createElement('a'); a.href = url; a.download = `contrat-rentify-${id}.pdf`;
    document.body.appendChild(a); a.click();
    setTimeout(()=>{ URL.revokeObjectURL(url); a.remove(); }, 200);
    showToast('success', '✅ Contrat téléchargé');
  } catch(e) { showToast('error', '❌ Erreur'); }
  finally { btn.disabled = false; btn.innerHTML = orig; }
}

/* ══ RESPOND RESERVATION ══ */
async function respondReservation(id, action) {
  const labels = {
    accept:  { title:'Accepter la réservation', msg:'Confirmer l\'acceptation ?', icon:'✅' },
    reject:  { title:'Refuser la réservation',  msg:'Confirmer le refus ?',       icon:'❌' },
    complete:{ title:'Marquer comme terminée',  msg:'Confirmer la fin ?',         icon:'🏆' },
  };
  const l = labels[action];
  openConfirm(l.icon, l.title, l.msg, async () => {
    try {
      const res = await window.API.patch(`/api/reservations/${id}/${action}`);
      if (res.ok || res.status === 200) {
        await loadReservations();
        renderReservationsTable();
        renderRecentReservations();
        renderStats();
        showToast('success', '✅ Réservation mise à jour');
      } else {
        showToast('error', '❌ ' + (res.data?.message || 'Réessayez'));
      }
    } catch(e) { showToast('error', '❌ Erreur réseau'); }
  });
}

/* ══ MACHINE MODAL ══ */
function openMachineModal(id = null) {
  editMachineId = id;
  selectedFiles = [];
  document.getElementById('imgPreviewGrid').innerHTML = '';
  document.getElementById('machineImages').value = '';
  if (id) {
    const m = allMachines.find(x => x.id == id);
    if (!m) return;
    document.getElementById('machineModalTitle').textContent = 'Modifier la machine';
    document.getElementById('machineModalSaveBtn').innerHTML = '<span>💾</span> Mettre à jour';
    document.getElementById('machineName').value        = m.name || '';
    document.getElementById('machineType').value        = m.type?.toLowerCase() || '';
    document.getElementById('machinePriceDay').value    = m.price_per_day || '';
    document.getElementById('machinePriceHour').value   = m.price_per_hour || '';
    document.getElementById('machineCity').value        = m.city || '';
    document.getElementById('machineLocation').value    = m.location || '';
    document.getElementById('machineDescription').value = m.description || '';
    setDispoVal(m.status || 'available');
    (m.images || []).forEach(img => {
      const div = document.createElement('div');
      div.className = 'img-preview-item';
      div.innerHTML = `<img src="/storage/${img.path}" alt=""><button class="img-preview-remove" onclick="this.parentNode.remove()">✕</button>`;
      document.getElementById('imgPreviewGrid').appendChild(div);
    });
  } else {
    document.getElementById('machineModalTitle').textContent = 'Ajouter une machine';
    document.getElementById('machineModalSaveBtn').innerHTML = '<span>💾</span> Enregistrer';
    ['machineName','machinePriceDay','machinePriceHour','machineCity','machineLocation','machineDescription']
      .forEach(id => document.getElementById(id).value = '');
    document.getElementById('machineType').value = '';
    setDispoVal('available');
  }
  document.getElementById('machineModal').classList.add('open');
}

function closeMachineModal() {
  document.getElementById('machineModal').classList.remove('open');
  editMachineId = null; selectedFiles = [];
}

function setDispo(btn) {
  document.querySelectorAll('.dispo-btn').forEach(b => b.classList.remove('active'));
  btn.classList.add('active');
  currentDispo = btn.dataset.val;
}

function setDispoVal(val) {
  currentDispo = val;
  document.querySelectorAll('.dispo-btn').forEach(b => b.classList.toggle('active', b.dataset.val === val));
}

function previewImages(event) {
  const files = Array.from(event.target.files);
  selectedFiles = [...selectedFiles, ...files];
  const grid = document.getElementById('imgPreviewGrid');
  files.forEach((file, i) => {
    const reader = new FileReader();
    reader.onload = e => {
      const div = document.createElement('div');
      div.className = 'img-preview-item';
      div.dataset.fileIdx = selectedFiles.length - files.length + i;
      div.innerHTML = `<img src="${e.target.result}" alt="">
        <button class="img-preview-remove" onclick="removePreview(this,${selectedFiles.length - files.length + i})">✕</button>`;
      grid.appendChild(div);
    };
    reader.readAsDataURL(file);
  });
}

function removePreview(btn, idx) { selectedFiles[idx] = null; btn.parentNode.remove(); }

async function saveMachine() {
  const name     = document.getElementById('machineName').value.trim();
  const type     = document.getElementById('machineType').value;
  const priceDay = document.getElementById('machinePriceDay').value;
  const city     = document.getElementById('machineCity').value.trim();
  if (!name || !type || !priceDay || !city) {
    showToast('error', '⚠️ Veuillez remplir tous les champs obligatoires'); return;
  }
  const btn = document.getElementById('machineModalSaveBtn');
  btn.disabled = true; btn.innerHTML = '<span>⏳</span> Enregistrement…';
  try {
    const formData = new FormData();
    formData.append('name', name); formData.append('type', type);
    formData.append('price_per_day', priceDay);
    formData.append('price_per_hour', document.getElementById('machinePriceHour').value || 0);
    formData.append('city', city);
    formData.append('location', document.getElementById('machineLocation').value.trim());
    formData.append('description', document.getElementById('machineDescription').value.trim());
    formData.append('status', currentDispo);
    selectedFiles.filter(Boolean).forEach((f, i) => formData.append(`images[${i}]`, f));
    const token  = window.getToken ? window.getToken() : localStorage.getItem('auth_token');
    const url    = editMachineId ? `/api/machines/${editMachineId}` : '/api/machines';
    if (editMachineId) formData.append('_method', 'PUT');
    const response = await fetch(url, {
      method: 'POST',
      headers: { Authorization: `Bearer ${token}`, Accept: 'application/json' },
      body: formData,
    });
    const data = await response.json();
    if (response.ok) {
      showToast('success', editMachineId ? '✅ Machine mise à jour !' : '✅ Machine ajoutée !');
      closeMachineModal();
      await loadMachines();
      renderMachinesGrid(); renderStats();
    } else {
      const errs = data.errors ? Object.values(data.errors).flat().join(' | ') : (data.message || 'Erreur');
      showToast('error', '❌ ' + errs);
    }
  } catch(e) { showToast('error', '❌ Erreur réseau'); }
  finally {
    btn.disabled = false;
    btn.innerHTML = editMachineId ? '<span>💾</span> Mettre à jour' : '<span>💾</span> Enregistrer';
  }
}

/* ══ CHANGE STATUS ══ */
function changeStatus(id) {
  const m = allMachines.find(x => x.id == id);
  if (!m) return;
  const nextStatus = { available:'unavailable', unavailable:'maintenance', maintenance:'available' };
  const next = nextStatus[m.status] || 'available';
  const labels = { available:'Disponible ✅', unavailable:'Indisponible ❌', maintenance:'Maintenance 🔧' };
  openConfirm('🔄', 'Changer le statut', `Passer "${m.name}" en : ${labels[next]} ?`, async () => {
    try {
      const res = await window.API.put(`/api/machines/${id}`, { status: next });
      if (res.ok || res.status === 200) {
        const idx = allMachines.findIndex(x => x.id == id);
        if (idx !== -1) allMachines[idx].status = next;
        renderMachinesGrid(); renderStats();
        showToast('success', `✅ Statut : ${labels[next]}`);
      } else { showToast('error', '❌ Erreur'); }
    } catch(e) { showToast('error', '❌ Erreur réseau'); }
  });
}

/* ══ DELETE MACHINE ══ */
function deleteMachine(id, name) {
  openConfirm('🗑', 'Supprimer la machine',
    `Supprimer "${name}" ? Action irréversible.`,
    async () => {
      try {
        const res = await window.API.delete(`/api/machines/${id}`);
        if (res.ok || res.status === 200 || res.status === 204) {
          allMachines = allMachines.filter(m => m.id != id);
          allReservations = allReservations.filter(r => r.machine_id != id);
          renderMachinesGrid(); renderStats(); renderRecentReservations(); renderReservationsTable();
          showToast('success', '✅ Machine supprimée');
        } else { showToast('error', '❌ Impossible de supprimer'); }
      } catch(e) { showToast('error', '❌ Erreur réseau'); }
    }
  );
}

/* ══ MINI 3D VIEWER ══ */
const PALETTE_3D = {
  excavatrice:{ body:0xD4AF37, arm:0x9A7D20, cabin:0x0F1B2D },
  grue:       { body:0xD4AF37, arm:0x5a5660, cabin:0x162540 },
  bulldozer:  { body:0xD4AF37, arm:0x9A7D20, cabin:0x0F1B2D },
  chargeuse:  { body:0x22c55e, arm:0x9A7D20, cabin:0x0F1B2D },
  compacteur: { body:0xef4444, arm:0x5a5660, cabin:0x162540 },
  nacelle:    { body:0x3b82f6, arm:0x9A7D20, cabin:0x0F1B2D },
  tractopelle:{ body:0xD4AF37, arm:0x9A7D20, cabin:0x0F1B2D },
  camion:     { body:0xD4AF37, arm:0x5a5660, cabin:0x162540 },
};

function openMiniViewer(machineId, name, type) {
  document.getElementById('miniViewerTitle').textContent = name;
  document.getElementById('miniViewerModal').classList.add('open');
  setTimeout(() => initMiniViewer(type), 50);
}
function closeMiniViewer() {
  document.getElementById('miniViewerModal').classList.remove('open');
  destroyMiniViewer();
}
function destroyMiniViewer() {
  if (miniViewerAnim)     { cancelAnimationFrame(miniViewerAnim); miniViewerAnim = null; }
  if (miniViewerRenderer) { miniViewerRenderer.dispose(); miniViewerRenderer = null; }
}
function initMiniViewer(type) {
  destroyMiniViewer();
  const canvas = document.getElementById('miniViewerCanvas');
  const W = canvas.clientWidth || 380, H = canvas.clientHeight || 280;
  const pal = PALETTE_3D[type?.toLowerCase()] || PALETTE_3D.excavatrice;
  const renderer = new THREE.WebGLRenderer({ canvas, antialias:true, alpha:true });
  renderer.setSize(W, H); renderer.setPixelRatio(Math.min(window.devicePixelRatio,2));
  renderer.shadowMap.enabled = true; miniViewerRenderer = renderer;
  const scene = new THREE.Scene(); scene.background = new THREE.Color(0x0d1a2e);
  const camera = new THREE.PerspectiveCamera(45, W/H, 0.1, 100);
  camera.position.set(4,3,5); camera.lookAt(0,0.5,0);
  scene.add(new THREE.AmbientLight(0xffffff, 0.6));
  const dir = new THREE.DirectionalLight(0xD4AF37, 1.2);
  dir.position.set(5,8,5); dir.castShadow = true; scene.add(dir);
  scene.add(new THREE.PointLight(0x162540, 0.8, 20));
  const ground = new THREE.Mesh(new THREE.CylinderGeometry(3,3,.1,32), new THREE.MeshStandardMaterial({color:0x162540}));
  ground.position.y = -0.3; ground.receiveShadow = true; scene.add(ground);
  const group = new THREE.Group();
  buildMachine3D(group, type?.toLowerCase() || 'excavatrice', pal);
  scene.add(group);
  scene.add(Object.assign(new THREE.GridHelper(6,6,0x1a2f4a,0x1a2f4a), {position:{y:-0.25,x:0,z:0}}));
  let isDragging=false, prevX=0, prevY=0, rotY=0, rotX=0.2;
  canvas.addEventListener('mousedown',  e => { isDragging=true; prevX=e.clientX; prevY=e.clientY; });
  canvas.addEventListener('mousemove',  e => { if (!isDragging) return; rotY+=(e.clientX-prevX)*.01; rotX+=(e.clientY-prevY)*.008; rotX=Math.max(-0.5,Math.min(0.8,rotX)); prevX=e.clientX; prevY=e.clientY; });
  canvas.addEventListener('mouseup',    () => isDragging=false);
  canvas.addEventListener('mouseleave', () => isDragging=false);
  function animate() {
    miniViewerAnim = requestAnimationFrame(animate);
    if (!isDragging) rotY += 0.007;
    group.rotation.y = rotY;
    const r = 6;
    camera.position.x = r*Math.sin(rotY)*Math.cos(rotX);
    camera.position.z = r*Math.cos(rotY)*Math.cos(rotX);
    camera.position.y = r*Math.sin(rotX)+1;
    camera.lookAt(0,0.5,0);
    renderer.render(scene, camera);
  }
  animate();
}
function buildMachine3D(group, type, pal) {
  const matBody  = new THREE.MeshStandardMaterial({color:pal.body, metalness:.4, roughness:.5});
  const matArm   = new THREE.MeshStandardMaterial({color:pal.arm,  metalness:.3, roughness:.6});
  const matCabin = new THREE.MeshStandardMaterial({color:pal.cabin,metalness:.6, roughness:.3});
  const matWheel = new THREE.MeshStandardMaterial({color:0x222222, metalness:.2, roughness:.8});
  const matGlass = new THREE.MeshStandardMaterial({color:0x88bbff, transparent:true, opacity:.6});
  const add = (geo,mat,x=0,y=0,z=0,rx=0,ry=0,rz=0) => {
    const m = new THREE.Mesh(geo,mat); m.position.set(x,y,z); m.rotation.set(rx,ry,rz); m.castShadow=true; group.add(m); return m;
  };
  if (type==='excavatrice'||type==='tractopelle') {
    add(new THREE.BoxGeometry(2,.8,1.2),matBody,0,.4,0);
    add(new THREE.BoxGeometry(1,.8,1),matCabin,-.4,1.2,0);
    add(new THREE.BoxGeometry(.9,.6,.9),matGlass,-.4,1.6,0);
    add(new THREE.BoxGeometry(2.2,.35,1.6),matArm,0,.05,0);
    [-1,1].forEach(s=>{add(new THREE.CylinderGeometry(.35,.35,.35,12),matWheel,s*.85,-.1,.7);add(new THREE.CylinderGeometry(.35,.35,.35,12),matWheel,s*.85,-.1,-.7);});
    add(new THREE.BoxGeometry(.25,1.2,.2),matArm,.6,1.4,0,0,0,-.4);
    add(new THREE.BoxGeometry(.2,.9,.18),matArm,1.25,.9,0,0,0,.35);
    add(new THREE.BoxGeometry(.5,.25,.15),matArm,1.6,.3,0,0,0,.5);
  } else if (type==='grue') {
    add(new THREE.CylinderGeometry(.6,.8,.4,8),matBody,0,.2,0);
    add(new THREE.BoxGeometry(.35,4,.35),matBody,0,2,0);
    add(new THREE.BoxGeometry(3.5,.2,.2),matArm,.5,4,0);
    add(new THREE.BoxGeometry(.7,.7,.6),matCabin,.1,3.3,0);
    add(new THREE.CylinderGeometry(.03,.03,2.5,6),matArm,1.8,2.7,0);
    [-1,1].forEach(s=>add(new THREE.CylinderGeometry(.4,.4,.25,8),matWheel,s*.8,0,0,0,0,Math.PI/2));
  } else if (type==='bulldozer') {
    add(new THREE.BoxGeometry(2.2,1,1.6),matBody,0,.5,0);
    add(new THREE.BoxGeometry(1,1,1.3),matCabin,-.3,1.2,0);
    add(new THREE.BoxGeometry(.2,1.1,1.8),matArm,1.3,.7,0,0,0,-.15);
    [-1,1].forEach(s=>{add(new THREE.BoxGeometry(2.4,.35,.4),matArm,0,.05,s*.7);add(new THREE.CylinderGeometry(.35,.35,.35,12),matWheel,.85,.05,s*.7);add(new THREE.CylinderGeometry(.35,.35,.35,12),matWheel,-.85,.05,s*.7);});
  } else {
    add(new THREE.BoxGeometry(2.5,.8,1.4),matBody,.3,.4,0);
    add(new THREE.BoxGeometry(1.1,1.1,1.3),matCabin,-1,1,0);
    add(new THREE.BoxGeometry(1.8,.15,1.4),matArm,.5,.8,0);
    [-1,1].forEach(s=>{add(new THREE.CylinderGeometry(.4,.4,.4,12),matWheel,-1.2,0,s*.55,0,0,Math.PI/2);add(new THREE.CylinderGeometry(.4,.4,.4,12),matWheel,.8,0,s*.55,0,0,Math.PI/2);});
  }
}

/* ══ TABS ══ */
function switchSideTab(tabId, link) {
  document.querySelectorAll('.tab-panel').forEach(p => p.classList.remove('active'));
  const panel = document.getElementById(`tab-${tabId}`);
  if (panel) panel.classList.add('active');
  document.querySelectorAll('.sidebar-nav a').forEach(a => a.classList.remove('active'));
  if (link) link.classList.add('active');
  destroyMiniViewer();
}

/* ══ CONFIRM ══ */
function openConfirm(icon, title, msg, callback) {
  document.getElementById('confirmIcon').textContent  = icon;
  document.getElementById('confirmTitle').textContent = title;
  document.getElementById('confirmMsg').textContent   = msg;
  confirmCallback = callback;
  document.getElementById('confirmOverlay').classList.add('open');
  document.getElementById('confirmOkBtn').onclick = () => { closeConfirm(); callback(); };
}
function closeConfirm() { document.getElementById('confirmOverlay').classList.remove('open'); }

/* ══ TOAST ══ */
function showToast(type, message) {
  const container = document.getElementById('toastContainer');
  const toast = document.createElement('div');
  toast.className = `toast ${type}`;
  const icons = { success:'✅', error:'❌', info:'ℹ️' };
  toast.innerHTML = `<span class="toast-icon">${icons[type]||'ℹ️'}</span><span>${message}</span>`;
  container.appendChild(toast);
  requestAnimationFrame(() => toast.classList.add('show'));
  setTimeout(() => { toast.classList.remove('show'); setTimeout(()=>toast.remove(),400); }, 3500);
}

/* ══ LOGOUT ══ */
async function handleLogout() {
  try { await window.API.post('/api/logout'); } catch(e) {}
  localStorage.removeItem('auth_token');
  localStorage.removeItem('auth_user');
  window.location.href = '/login';
}

/* ══ UTILS ══ */
function escHtml(str) {
  if (!str) return '';
  return String(str).replace(/&/g,'&amp;').replace(/</g,'&lt;').replace(/>/g,'&gt;').replace(/"/g,'&quot;');
}

document.getElementById('machineModal').addEventListener('click', e => { if (e.target===e.currentTarget) closeMachineModal(); });
document.getElementById('miniViewerModal').addEventListener('click', e => { if (e.target===e.currentTarget) closeMiniViewer(); });
document.getElementById('confirmOverlay').addEventListener('click', e => { if (e.target===e.currentTarget) closeConfirm(); });
document.addEventListener('keydown', e => { if (e.key==='Escape') { closeMachineModal(); closeMiniViewer(); closeConfirm(); } });
</script>
@endpush
