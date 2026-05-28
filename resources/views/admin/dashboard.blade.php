@extends('layouts.app')

@section('title', 'Administration — Rentify')

@push('styles')
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
<style>
/* ══════════════════════════════════════════════
   ADMIN DASHBOARD — RENTIFY V15
   Gold / Crème — 100% clair, zéro navy background
══════════════════════════════════════════════ */
:root {
  --gold:#D4AF37;--gold-dk:#9A7D20;--gold-pale:#FEF9E7;--gold-glow:rgba(212,175,55,.25);
  --navy:#0F1B2D;--navy2:#162540;
  --cream:#FAF7F0;--cream2:#F0EBE0;--cream3:#E8DDD0;
  --txt-dark:#1a1a2e;--txt-mid:#5a5660;--txt-light:#9992a4;
  --green:#22c55e;--red:#ef4444;--blue:#3b82f6;--purple:#8b5cf6;
  --radius:14px;--shadow:0 4px 24px rgba(15,27,45,.08);
}
* { box-sizing:border-box; }
body { background:var(--cream); font-family:'DM Sans',sans-serif; margin:0; }

/* ══════════════════════════════════════════════
   ✅ V15 FIX — MASQUER NAVBAR GLOBALE (layouts/app)
   Sélecteurs exacts : .rentify-nav / #rentify-nav
   + supprime le padding-top:64px du <main>
══════════════════════════════════════════════ */
.rentify-nav,
#rentify-nav {
  display: none !important;
}
/* Annule le padding-top:64px injecté par layouts/app sur <main> */
main {
  padding-top: 0 !important;
}

/* ════════════════════════
   ✅ V15 — ADMIN TOPBAR
   Dashboard | Utilisateurs | Machines | Réservations | [avatar]
════════════════════════ */
#adminTopBar {
  position: sticky;
  top: 0;
  z-index: 500;
  width: 100%;
  background: #fff;
  border-bottom: 1.5px solid rgba(212,175,55,.2);
  box-shadow: 0 2px 12px rgba(15,27,45,.06);
  display: flex;
  align-items: center;
  height: 58px;
  padding: 0 1.5rem;
  gap: 0;
}

.atb-brand {
  font-family: 'Playfair Display', serif;
  font-size: 1.3rem;
  font-weight: 700;
  color: var(--txt-dark);
  text-decoration: none;
  margin-right: 2rem;
  flex-shrink: 0;
}
.atb-brand span { color: var(--gold); }

.atb-nav {
  display: flex;
  align-items: center;
  gap: .15rem;
  flex: 1;
}

.atb-link {
  display: flex;
  align-items: center;
  gap: .4rem;
  padding: .45rem .9rem;
  border-radius: 9px;
  font-size: .85rem;
  font-weight: 600;
  color: var(--txt-mid);
  cursor: pointer;
  border: none;
  background: transparent;
  transition: all .18s;
  white-space: nowrap;
  text-decoration: none;
  position: relative;
}
.atb-link:hover {
  color: var(--txt-dark);
  background: rgba(212,175,55,.07);
}
.atb-link.active {
  color: var(--gold-dk);
  background: var(--gold-pale);
  font-weight: 700;
}
.atb-link.active::after {
  content: '';
  position: absolute;
  bottom: -11px;
  left: 50%;
  transform: translateX(-50%);
  width: 28px;
  height: 3px;
  background: var(--gold);
  border-radius: 2px 2px 0 0;
}
.atb-badge {
  background: var(--red);
  color: #fff;
  font-size: .62rem;
  font-weight: 800;
  padding: .08rem .38rem;
  border-radius: 20px;
  min-width: 16px;
  text-align: center;
}
.atb-badge.gold { background: var(--gold); color: var(--txt-dark); }

/* Avatar dropdown */
.atb-avatar-wrap {
  margin-left: auto;
  position: relative;
  flex-shrink: 0;
}
.atb-avatar-btn {
  display: flex;
  align-items: center;
  gap: .5rem;
  background: transparent;
  border: none;
  cursor: pointer;
  padding: .3rem .5rem;
  border-radius: 10px;
  transition: background .18s;
}
.atb-avatar-btn:hover { background: var(--cream); }
.atb-avatar-circle {
  width: 34px;
  height: 34px;
  border-radius: 50%;
  background: var(--gold);
  color: var(--txt-dark);
  font-weight: 800;
  font-size: .85rem;
  display: flex;
  align-items: center;
  justify-content: center;
  overflow: hidden;
  border: 2px solid rgba(212,175,55,.4);
  flex-shrink: 0;
}
.atb-avatar-circle img { width: 100%; height: 100%; object-fit: cover; }
.atb-avatar-name {
  font-size: .82rem;
  font-weight: 700;
  color: var(--txt-dark);
  max-width: 110px;
  overflow: hidden;
  text-overflow: ellipsis;
  white-space: nowrap;
}
.atb-avatar-role {
  font-size: .65rem;
  color: var(--txt-light);
  display: block;
  line-height: 1;
  text-align: left;
}
.atb-caret { font-size: .7rem; color: var(--txt-light); }

.atb-dropdown {
  position: absolute;
  top: calc(100% + 6px);
  right: 0;
  background: #fff;
  border: 1.5px solid rgba(212,175,55,.18);
  border-radius: 12px;
  box-shadow: 0 8px 32px rgba(15,27,45,.13);
  min-width: 180px;
  overflow: hidden;
  opacity: 0;
  pointer-events: none;
  transform: translateY(-6px);
  transition: all .2s;
  z-index: 9999;
}
.atb-dropdown.open {
  opacity: 1;
  pointer-events: all;
  transform: translateY(0);
}
.atb-dd-header {
  padding: .75rem 1rem .6rem;
  border-bottom: 1px solid var(--cream3);
  background: var(--cream);
}
.atb-dd-name  { font-weight: 700; font-size: .84rem; color: var(--txt-dark); }
.atb-dd-email { font-size: .72rem; color: var(--txt-light); margin-top: .1rem; }
.atb-dd-item {
  display: flex;
  align-items: center;
  gap: .5rem;
  padding: .6rem 1rem;
  font-size: .83rem;
  font-weight: 600;
  color: var(--txt-mid);
  text-decoration: none;
  cursor: pointer;
  transition: background .15s;
  background: transparent;
  border: none;
  width: 100%;
  text-align: left;
}
.atb-dd-item:hover { background: var(--cream); color: var(--txt-dark); }
.atb-dd-item.danger { color: var(--red); }
.atb-dd-item.danger:hover { background: #fee2e2; }
.atb-dd-divider { height: 1px; background: var(--cream3); margin: .2rem 0; }

/* ─ Responsive topbar ─ */
@media(max-width:700px) {
  .atb-brand { margin-right: .75rem; }
  .atb-link-label { display: none; }
  .atb-link { padding: .45rem .6rem; }
  .atb-avatar-name, .atb-avatar-role, .atb-caret { display: none; }
}

/* ════════════════════════
   LAYOUT
════════════════════════ */
.admin-layout { display:flex; min-height:calc(100vh - 58px); }

/* ── Sidebar (navigation secondaire, masquée sur petits écrans) ── */
.admin-sidebar {
  width:220px; flex-shrink:0;
  background:var(--cream);
  border-right:1.5px solid rgba(212,175,55,.2);
  display:flex; flex-direction:column;
  position:sticky; top:58px; height:calc(100vh - 58px);
  overflow-y:auto; z-index:100;
}
.sidebar-brand { padding:1.1rem 1.25rem .8rem; border-bottom:1px solid rgba(212,175,55,.15); }
.sidebar-brand-name { font-family:'Playfair Display',serif; font-size:1.25rem; color:var(--txt-dark); font-weight:700; }
.sidebar-brand-name span { color:var(--gold); }
.sidebar-brand-sub { font-size:.67rem; color:var(--txt-light); letter-spacing:.1em; text-transform:uppercase; margin-top:.1rem; }
.sidebar-admin-badge {
  display:inline-flex; align-items:center; gap:.3rem;
  background:var(--gold-pale); border:1px solid rgba(212,175,55,.35);
  color:var(--gold-dk); font-size:.68rem; font-weight:800;
  padding:.2rem .55rem; border-radius:20px; margin-top:.5rem; letter-spacing:.06em;
}
.sidebar-nav { flex:1; padding:.75rem 0; }
.nav-section-label {
  font-size:.63rem; font-weight:800; letter-spacing:.12em;
  text-transform:uppercase; color:var(--txt-light);
  padding:.4rem 1.25rem .25rem; margin-top:.4rem;
}
.nav-item {
  display:flex; align-items:center; gap:.65rem;
  padding:.58rem 1.25rem; cursor:pointer;
  color:var(--txt-mid); font-size:.83rem; font-weight:600;
  border:none; background:transparent; width:100%;
  transition:all .18s; text-align:left; position:relative;
  text-decoration:none;
}
.nav-item:hover { color:var(--txt-dark); background:rgba(212,175,55,.06); }
.nav-item.active { color:var(--gold-dk); background:rgba(212,175,55,.1); border-right:3px solid var(--gold); }
.nav-item-icon { font-size:.95rem; width:18px; text-align:center; flex-shrink:0; }
.nav-badge { margin-left:auto; background:var(--red); color:#fff; font-size:.63rem; font-weight:800; padding:.08rem .4rem; border-radius:20px; min-width:17px; text-align:center; }
.nav-badge.gold { background:var(--gold); color:var(--txt-dark); }

.sidebar-footer { padding:.85rem 1.25rem; border-top:1px solid rgba(212,175,55,.15); }
.sidebar-admin-info { display:flex; align-items:center; gap:.55rem; }
.admin-avatar { width:30px; height:30px; border-radius:50%; background:var(--gold); color:var(--txt-dark); font-weight:800; font-size:.8rem; display:flex; align-items:center; justify-content:center; flex-shrink:0; overflow:hidden; border:2px solid rgba(212,175,55,.35); }
.admin-avatar img { width:100%; height:100%; object-fit:cover; }
.admin-name { font-size:.8rem; font-weight:700; color:var(--txt-dark); }
.admin-role { font-size:.66rem; color:var(--txt-light); }
.btn-logout-sm { margin-left:auto; background:rgba(239,68,68,.1); border:1px solid rgba(239,68,68,.2); border-radius:7px; color:#ef4444; font-size:.72rem; padding:.28rem .48rem; cursor:pointer; transition:all .2s; }
.btn-logout-sm:hover { background:rgba(239,68,68,.2); }

/* ════════════════════════
   MAIN CONTENT
════════════════════════ */
.admin-main { flex:1; overflow:hidden; display:flex; flex-direction:column; }

.tab-panel { display:none; padding:2rem; animation:fadeIn .3s ease; }
.tab-panel.active { display:block; }
@keyframes fadeIn { from{opacity:0;transform:translateY(8px)} to{opacity:1;transform:translateY(0)} }

.panel-header { display:flex; align-items:center; justify-content:space-between; margin-bottom:1.75rem; flex-wrap:wrap; gap:1rem; }
.panel-title { font-family:'Playfair Display',serif; font-size:1.6rem; color:var(--txt-dark); margin:0; }
.panel-title span { color:var(--gold); }
.panel-sub { color:var(--txt-light); font-size:.82rem; margin:.2rem 0 0; }

/* ═ Stat Cards ═ */
.stats-grid { display:grid; grid-template-columns:repeat(auto-fill,minmax(210px,1fr)); gap:1rem; margin-bottom:1.75rem; }
.stat-card { background:#fff; border:1px solid rgba(212,175,55,.12); border-radius:var(--radius); padding:1.25rem 1.4rem; box-shadow:var(--shadow); display:flex; align-items:flex-start; gap:1rem; position:relative; overflow:hidden; transition:transform .2s; }
.stat-card:hover { transform:translateY(-3px); }
.stat-card::before { content:''; position:absolute; top:0; left:0; width:4px; height:100%; }
.stat-card.gold::before   { background:var(--gold); }
.stat-card.navy::before   { background:var(--gold-dk); }
.stat-card.green::before  { background:var(--green); }
.stat-card.red::before    { background:var(--red); }
.stat-card.blue::before   { background:var(--blue); }
.stat-card.purple::before { background:var(--purple); }
.stat-icon { width:44px; height:44px; border-radius:11px; font-size:1.25rem; display:flex; align-items:center; justify-content:center; flex-shrink:0; }
.stat-card.gold .stat-icon   { background:var(--gold-pale); }
.stat-card.navy .stat-icon   { background:var(--gold-pale); }
.stat-card.green .stat-icon  { background:#dcfce7; }
.stat-card.red .stat-icon    { background:#fee2e2; }
.stat-card.blue .stat-icon   { background:#dbeafe; }
.stat-card.purple .stat-icon { background:#ede9fe; }
.stat-body { flex:1; min-width:0; }
.stat-label { font-size:.72rem; font-weight:700; color:var(--txt-light); text-transform:uppercase; letter-spacing:.07em; }
.stat-value { font-family:'Playfair Display',serif; font-size:1.8rem; color:var(--txt-dark); font-weight:700; line-height:1.1; margin:.15rem 0 .25rem; }
.stat-meta  { font-size:.72rem; color:var(--txt-light); }
.stat-meta strong { color:var(--green); }
.stat-meta strong.down { color:var(--red); }

/* ═ Charts ═ */
.charts-row { display:grid; grid-template-columns:2fr 1fr; gap:1.25rem; margin-bottom:1.75rem; }
.chart-card { background:#fff; border:1px solid rgba(212,175,55,.12); border-radius:var(--radius); box-shadow:var(--shadow); overflow:hidden; }
.chart-card-header { padding:1.1rem 1.5rem .75rem; display:flex; align-items:center; justify-content:space-between; border-bottom:1px solid var(--cream3); }
.chart-card-title { font-weight:800; color:var(--txt-dark); font-size:.9rem; }
.chart-card-body { padding:1.25rem; }

/* ═ Tables ═ */
.data-card { background:#fff; border:1px solid rgba(212,175,55,.12); border-radius:var(--radius); box-shadow:var(--shadow); overflow:hidden; margin-bottom:1.5rem; }
.data-card-header { padding:1.1rem 1.5rem; background:var(--cream); display:flex; align-items:center; justify-content:space-between; border-bottom:1px solid var(--cream3); flex-wrap:wrap; gap:.75rem; }
.data-card-title { font-weight:800; color:var(--txt-dark); font-size:.9rem; display:flex; align-items:center; gap:.4rem; }
.data-card-actions { display:flex; gap:.5rem; align-items:center; flex-wrap:wrap; }
.tbl-wrap { overflow-x:auto; }
table { width:100%; border-collapse:collapse; font-size:.83rem; }
th { background:var(--cream); color:var(--txt-mid); font-weight:800; font-size:.72rem; letter-spacing:.06em; text-transform:uppercase; padding:.7rem 1.1rem; border-bottom:1px solid var(--cream3); white-space:nowrap; text-align:left; }
td { padding:.75rem 1.1rem; color:var(--txt-mid); border-bottom:1px solid var(--cream3); vertical-align:middle; }
tr:last-child td { border-bottom:none; }
tr:hover td { background:rgba(212,175,55,.02); }

/* ═ Badges ═ */
.badge { display:inline-flex; align-items:center; gap:.25rem; padding:.2rem .65rem; border-radius:20px; font-size:.7rem; font-weight:700; white-space:nowrap; }
.badge-green  { background:#dcfce7; color:#15803d; }
.badge-red    { background:#fee2e2; color:#dc2626; }
.badge-yellow { background:#FEF9E7; color:var(--gold-dk); }
.badge-blue   { background:#dbeafe; color:#1d4ed8; }
.badge-purple { background:#ede9fe; color:#6d28d9; }
.badge-gray   { background:var(--cream2); color:var(--txt-mid); }
.badge-navy   { background:var(--gold-pale); color:var(--txt-dark); }
.badge-gold   { background:var(--gold-pale); color:var(--gold-dk); }
.role-admin  { background:rgba(239,68,68,.12); color:#dc2626; }
.role-owner  { background:var(--gold-pale); color:var(--gold-dk); }
.role-client { background:#dbeafe; color:#1d4ed8; }

.user-cell { display:flex; align-items:center; gap:.6rem; }
.tbl-avatar { width:30px; height:30px; border-radius:50%; background:var(--gold-pale); color:var(--gold-dk); font-weight:800; font-size:.72rem; display:flex; align-items:center; justify-content:center; flex-shrink:0; overflow:hidden; border:2px solid rgba(212,175,55,.25); }
.tbl-avatar img { width:100%; height:100%; object-fit:cover; }
.tbl-user-name  { font-weight:700; color:var(--txt-dark); font-size:.83rem; }
.tbl-user-email { font-size:.72rem; color:var(--txt-light); }
.machine-cell { display:flex; align-items:center; gap:.6rem; }
.tbl-machine-img { width:36px; height:36px; border-radius:7px; object-fit:cover; border:1.5px solid var(--cream3); flex-shrink:0; }

/* ═ Controls ═ */
.search-ctrl { padding:.42rem .8rem; border:1.5px solid var(--cream3); border-radius:8px; font-family:'DM Sans',sans-serif; font-size:.82rem; color:var(--txt-dark); outline:none; background:#fff; transition:border .2s; }
.search-ctrl:focus { border-color:var(--gold); }
.select-ctrl { padding:.42rem .7rem; border:1.5px solid var(--cream3); border-radius:8px; font-family:'DM Sans',sans-serif; font-size:.82rem; color:var(--txt-dark); outline:none; background:#fff; cursor:pointer; }
.btn-sm { padding:.3rem .65rem; border-radius:7px; font-family:'DM Sans',sans-serif; font-size:.75rem; font-weight:700; cursor:pointer; border:none; transition:all .18s; display:inline-flex; align-items:center; gap:.3rem; }
.btn-sm-navy  { background:var(--gold); color:var(--txt-dark); }
.btn-sm-navy:hover { background:var(--gold-dk); color:#fff; }
.btn-sm-gold  { background:var(--gold); color:var(--txt-dark); }
.btn-sm-gold:hover { background:var(--gold-dk); color:#fff; }
.btn-sm-green { background:#dcfce7; color:#15803d; border:1px solid #bbf7d0; }
.btn-sm-green:hover { background:#bbf7d0; }
.btn-sm-red   { background:#fee2e2; color:#dc2626; border:1px solid #fecaca; }
.btn-sm-red:hover { background:#fecaca; }
.btn-sm-blue  { background:#dbeafe; color:#1d4ed8; border:1px solid #bfdbfe; }
.btn-sm-blue:hover { background:#bfdbfe; }
.btn-sm-outline { background:transparent; color:var(--txt-dark); border:1.5px solid var(--cream3); }
.btn-sm-outline:hover { border-color:var(--gold); color:var(--gold-dk); }
.btn-navy { background:var(--gold); color:var(--txt-dark); border:none; border-radius:10px; padding:.6rem 1.25rem; font-family:'DM Sans',sans-serif; font-weight:700; font-size:.85rem; cursor:pointer; transition:all .2s; display:inline-flex; align-items:center; gap:.4rem; text-decoration:none; }
.btn-navy:hover { background:var(--gold-dk); color:#fff; transform:translateY(-1px); }

/* ═ Pagination ═ */
.tbl-pagination { display:flex; align-items:center; justify-content:space-between; padding:.75rem 1.25rem; border-top:1px solid var(--cream3); font-size:.78rem; color:var(--txt-light); flex-wrap:wrap; gap:.5rem; }
.pag-btns { display:flex; gap:.3rem; }
.pag-btn { width:30px; height:30px; border-radius:7px; display:flex; align-items:center; justify-content:center; border:1.5px solid var(--cream3); background:#fff; color:var(--txt-mid); cursor:pointer; font-size:.8rem; transition:all .18s; font-weight:700; }
.pag-btn:hover,.pag-btn.active { background:var(--gold); border-color:var(--gold); color:var(--txt-dark); }
.pag-btn:disabled { opacity:.3; cursor:not-allowed; }

/* ═ Modal ═ */
.modal-overlay { position:fixed; inset:0; z-index:9000; background:rgba(10,16,28,.7); backdrop-filter:blur(6px); display:flex; align-items:center; justify-content:center; opacity:0; pointer-events:none; transition:opacity .3s; }
.modal-overlay.open { opacity:1; pointer-events:all; }
.modal-box { background:#fff; border-radius:18px; width:540px; max-width:96vw; box-shadow:0 24px 80px rgba(10,16,28,.2); transform:translateY(20px); transition:transform .3s; overflow:hidden; }
.modal-overlay.open .modal-box { transform:translateY(0); }
.modal-header { padding:1.25rem 1.5rem; background:var(--cream); border-bottom:1px solid var(--cream3); display:flex; align-items:center; justify-content:space-between; }
.modal-header h2 { font-family:'Playfair Display',serif; font-size:1.1rem; color:var(--txt-dark); margin:0; }
.modal-close { width:32px; height:32px; border-radius:8px; background:var(--cream3); border:none; cursor:pointer; font-size:.9rem; transition:all .2s; }
.modal-close:hover { background:var(--cream2); }
.modal-body { padding:1.5rem; max-height:70vh; overflow-y:auto; }
.modal-footer { padding:1rem 1.5rem; border-top:1px solid var(--cream3); background:var(--cream); display:flex; gap:.6rem; }
.form-group { margin-bottom:1.1rem; }
.form-label { display:block; font-size:.78rem; font-weight:800; color:var(--txt-dark); margin-bottom:.4rem; }
.form-control { width:100%; padding:.58rem .85rem; border:1.5px solid var(--cream3); border-radius:9px; font-family:'DM Sans',sans-serif; font-size:.86rem; color:var(--txt-dark); outline:none; transition:border .2s; }
.form-control:focus { border-color:var(--gold); }
.form-row-2 { display:grid; grid-template-columns:1fr 1fr; gap:.9rem; }

/* ═ Confirm dialog ═ */
.confirm-overlay { position:fixed; inset:0; z-index:9999; background:rgba(10,16,28,.65); backdrop-filter:blur(4px); display:flex; align-items:center; justify-content:center; opacity:0; pointer-events:none; transition:opacity .25s; }
.confirm-overlay.open { opacity:1; pointer-events:all; }
.confirm-box { background:#fff; border-radius:16px; padding:1.75rem; width:380px; max-width:94vw; box-shadow:0 20px 60px rgba(10,16,28,.2); text-align:center; transform:scale(.95); transition:transform .25s; }
.confirm-overlay.open .confirm-box { transform:scale(1); }
.confirm-icon  { font-size:2.5rem; margin-bottom:.75rem; }
.confirm-title { font-family:'Playfair Display',serif; font-size:1.1rem; color:var(--txt-dark); margin:0 0 .4rem; }
.confirm-msg   { color:var(--txt-mid); font-size:.84rem; margin:0 0 1.25rem; }
.confirm-btns  { display:flex; gap:.6rem; justify-content:center; }

/* ═ Skeleton ═ */
.skeleton { background:linear-gradient(90deg,var(--cream2) 25%,var(--cream3) 50%,var(--cream2) 75%); background-size:200% 100%; animation:shimmer 1.5s infinite; border-radius:6px; }
@keyframes shimmer{0%{background-position:200% 0}100%{background-position:-200% 0}}

/* ═ Toast ═ */
.toast-ctr { position:fixed; bottom:2rem; right:2rem; z-index:99999; display:flex; flex-direction:column; gap:.5rem; }
.toast { background:#fff; border-radius:12px; border:1px solid var(--cream3); padding:.8rem 1.2rem; box-shadow:0 8px 32px rgba(10,16,28,.12); font-size:.84rem; font-weight:600; color:var(--txt-dark); display:flex; align-items:center; gap:.6rem; transform:translateX(120%); transition:transform .35s cubic-bezier(.34,1.56,.64,1); min-width:220px; }
.toast.show    { transform:translateX(0); }
.toast.success { border-left:4px solid var(--green); }
.toast.error   { border-left:4px solid var(--red); }
.toast.info    { border-left:4px solid var(--gold); }

/* ═ Suspended row ═ */
tr.suspended td { opacity:.5; }
tr.suspended td:first-child { border-left:3px solid var(--red); }

/* ═ Responsive ═ */
@media(max-width:1100px){ .charts-row { grid-template-columns:1fr; } }
@media(max-width:860px){
  .admin-sidebar { display:none; }
  .tab-panel { padding:1.25rem; }
  .stats-grid { grid-template-columns:repeat(2,1fr); }
}
@media(max-width:540px){ .stats-grid { grid-template-columns:1fr; } }
</style>
@endpush

@section('content')

{{-- ✅ V15 — ADMIN TOPBAR (remplace la navbar globale) --}}
<nav id="adminTopBar">
  <a class="atb-brand" href="/dashboard/admin">Rent<span>ify</span></a>

  <div class="atb-nav">
    <button class="atb-link active" id="atbVue" onclick="switchTab('vue')">
      <span>📊</span>
      <span class="atb-link-label">Dashboard</span>
    </button>
    <button class="atb-link" id="atbUsers" onclick="switchTab('users')">
      <span>👥</span>
      <span class="atb-link-label">Utilisateurs</span>
      <span class="atb-badge" id="topBadgeSuspended" style="display:none">!</span>
    </button>
    <button class="atb-link" id="atbMachines" onclick="switchTab('machines')">
      <span>🏗</span>
      <span class="atb-link-label">Machines</span>
    </button>
    <button class="atb-link" id="atbReservations" onclick="switchTab('reservations')">
      <span>📋</span>
      <span class="atb-link-label">Réservations</span>
      <span class="atb-badge" id="topBadgePending" style="display:none">0</span>
    </button>
  </div>

  {{-- Avatar dropdown --}}
  <div class="atb-avatar-wrap">
    <button class="atb-avatar-btn" id="atbAvatarBtn" onclick="toggleAvatarDropdown()">
      <div class="atb-avatar-circle" id="atbAvatarCircle">A</div>
      <div>
        <div class="atb-avatar-name" id="atbAvatarName">Admin</div>
        <span class="atb-avatar-role">Administrateur</span>
      </div>
      <span class="atb-caret">▾</span>
    </button>
    <div class="atb-dropdown" id="atbDropdown">
      <div class="atb-dd-header">
        <div class="atb-dd-name" id="atbDdName">Admin</div>
        <div class="atb-dd-email" id="atbDdEmail">admin@rentify.ma</div>
      </div>
      <a href="/profile" class="atb-dd-item">👤 Mon profil</a>
      <div class="atb-dd-divider"></div>
      <button class="atb-dd-item danger" onclick="logout()">⏻ Déconnexion</button>
    </div>
  </div>
</nav>

<div class="admin-layout">

  {{-- ══ SIDEBAR (navigation secondaire) ══ --}}
  <aside class="admin-sidebar">
    <div class="sidebar-brand">
      <div class="sidebar-brand-name">Rent<span>ify</span></div>
      <div class="sidebar-brand-sub">Panneau Admin</div>
      <div class="sidebar-admin-badge">👑 ADMINISTRATEUR</div>
    </div>
    <nav class="sidebar-nav">
      <div class="nav-section-label">Tableau de bord</div>
      <button class="nav-item active" id="navVue" onclick="switchTab('vue')">
        <span class="nav-item-icon">📊</span> Vue d'ensemble
      </button>
      <div class="nav-section-label">Gestion</div>
      <button class="nav-item" id="navUsers" onclick="switchTab('users')">
        <span class="nav-item-icon">👥</span> Utilisateurs
        <span class="nav-badge" id="badgeSuspended" style="display:none">!</span>
      </button>
      <button class="nav-item" id="navMachines" onclick="switchTab('machines')">
        <span class="nav-item-icon">🏗</span> Machines
      </button>
      <button class="nav-item" id="navReservations" onclick="switchTab('reservations')">
        <span class="nav-item-icon">📋</span> Réservations
        <span class="nav-badge" id="badgePending" style="display:none">0</span>
      </button>
      <button class="nav-item" id="navRatings" onclick="switchTab('ratings')">
        <span class="nav-item-icon">⭐</span> Avis
      </button>
      <div class="nav-section-label">Compte</div>
      <a href="/profile" class="nav-item">
        <span class="nav-item-icon">👤</span> Mon profil
      </a>
    </nav>
    <div class="sidebar-footer">
      <div class="sidebar-admin-info">
        <div class="admin-avatar" id="sidebarAvatar">A</div>
        <div>
          <div class="admin-name" id="sidebarName">Admin</div>
          <div class="admin-role">admin@rentify.ma</div>
        </div>
        <button class="btn-logout-sm" onclick="logout()" title="Déconnexion">⏻</button>
      </div>
    </div>
  </aside>

  {{-- ══ MAIN ══ --}}
  <main class="admin-main">

    {{-- TAB 1 : VUE D'ENSEMBLE --}}
    <div class="tab-panel active" id="tabVue">
      <div class="panel-header">
        <div>
          <h1 class="panel-title">Vue <span>d'ensemble</span></h1>
          <p class="panel-sub" id="overviewDate">—</p>
        </div>
        <button class="btn-navy" onclick="refreshAll()">🔄 Actualiser</button>
      </div>
      <div class="stats-grid">
        <div class="stat-card gold">
          <div class="stat-icon">👥</div>
          <div class="stat-body">
            <div class="stat-label">Utilisateurs</div>
            <div class="stat-value" id="statUsers"><div class="skeleton" style="width:60px;height:36px"></div></div>
            <div class="stat-meta" id="statUsersM">—</div>
          </div>
        </div>
        <div class="stat-card green">
          <div class="stat-icon">🏗</div>
          <div class="stat-body">
            <div class="stat-label">Machines</div>
            <div class="stat-value" id="statMachines"><div class="skeleton" style="width:60px;height:36px"></div></div>
            <div class="stat-meta" id="statMachinesM">—</div>
          </div>
        </div>
        <div class="stat-card blue">
          <div class="stat-icon">📋</div>
          <div class="stat-body">
            <div class="stat-label">Réservations</div>
            <div class="stat-value" id="statReservations"><div class="skeleton" style="width:60px;height:36px"></div></div>
            <div class="stat-meta" id="statReservationsM">—</div>
          </div>
        </div>
        <div class="stat-card navy">
          <div class="stat-icon">💰</div>
          <div class="stat-body">
            <div class="stat-label">Revenus totaux</div>
            <div class="stat-value" id="statRevenue"><div class="skeleton" style="width:80px;height:36px"></div></div>
            <div class="stat-meta" id="statRevenueM">—</div>
          </div>
        </div>
        <div class="stat-card purple">
          <div class="stat-icon">⏳</div>
          <div class="stat-body">
            <div class="stat-label">En attente</div>
            <div class="stat-value" id="statPending"><div class="skeleton" style="width:40px;height:36px"></div></div>
            <div class="stat-meta">Demandes à traiter</div>
          </div>
        </div>
        <div class="stat-card red">
          <div class="stat-icon">🚫</div>
          <div class="stat-body">
            <div class="stat-label">Suspendus</div>
            <div class="stat-value" id="statSuspended"><div class="skeleton" style="width:40px;height:36px"></div></div>
            <div class="stat-meta">Comptes bloqués</div>
          </div>
        </div>
      </div>
      <div class="charts-row">
        <div class="chart-card">
          <div class="chart-card-header">
            <span class="chart-card-title">📈 Réservations & Revenus mensuels</span>
            <select class="select-ctrl" id="chartYearSel" onchange="renderCharts()">
              <option value="2025">2025</option>
              <option value="2024">2024</option>
            </select>
          </div>
          <div class="chart-card-body"><canvas id="chartMain" height="100"></canvas></div>
        </div>
        <div class="chart-card">
          <div class="chart-card-header"><span class="chart-card-title">🏗 Machines par type</span></div>
          <div class="chart-card-body" style="display:flex;align-items:center;justify-content:center">
            <canvas id="chartTypes" style="max-height:200px"></canvas>
          </div>
        </div>
      </div>
      <div class="data-card">
        <div class="data-card-header">
          <span class="data-card-title">🕐 Activité récente</span>
          <button class="btn-sm btn-sm-outline" onclick="switchTab('reservations')">Voir tout →</button>
        </div>
        <div class="tbl-wrap">
          <table id="tblActivity">
            <thead><tr><th>#</th><th>Client</th><th>Machine</th><th>Dates</th><th>Montant</th><th>Statut</th><th>Actions</th></tr></thead>
            <tbody id="tbodyActivity"><tr><td colspan="7" style="text-align:center;padding:2rem;color:var(--txt-light)">Chargement…</td></tr></tbody>
          </table>
        </div>
      </div>
    </div>

    {{-- TAB 2 : UTILISATEURS --}}
    <div class="tab-panel" id="tabUsers">
      <div class="panel-header">
        <div>
          <h1 class="panel-title">Gestion des <span>Utilisateurs</span></h1>
          <p class="panel-sub">Gérez les comptes, rôles et accès</p>
        </div>
        <button class="btn-navy" onclick="openCreateUserModal()">➕ Nouvel utilisateur</button>
      </div>
      <div class="data-card">
        <div class="data-card-header">
          <span class="data-card-title">👥 Tous les utilisateurs</span>
          <div class="data-card-actions">
            <input class="search-ctrl" id="userSearch" placeholder="🔍 Nom, email…" oninput="filterUsers()">
            <select class="select-ctrl" id="userRoleFilter" onchange="filterUsers()">
              <option value="">Tous les rôles</option>
              <option value="admin">Admin</option>
              <option value="owner">Owner</option>
              <option value="client">Client</option>
            </select>
            <select class="select-ctrl" id="userStatusFilter" onchange="filterUsers()">
              <option value="">Tous statuts</option>
              <option value="active">Actifs</option>
              <option value="suspended">Suspendus</option>
            </select>
          </div>
        </div>
        <div class="tbl-wrap">
          <table>
            <thead><tr><th>#</th><th>Utilisateur</th><th>Rôle</th><th>Ville</th><th>Téléphone</th><th>Statut</th><th>Actions</th></tr></thead>
            <tbody id="tbodyUsers"><tr><td colspan="7" style="text-align:center;padding:2rem;color:var(--txt-light)">Chargement…</td></tr></tbody>
          </table>
        </div>
        <div class="tbl-pagination" id="usersPagination">
          <span id="usersCount">—</span>
          <div class="pag-btns" id="usersPagBtns"></div>
        </div>
      </div>
    </div>

    {{-- TAB 3 : MACHINES --}}
    <div class="tab-panel" id="tabMachines">
      <div class="panel-header">
        <div>
          <h1 class="panel-title">Gestion des <span>Machines</span></h1>
          <p class="panel-sub">Toutes les machines publiées sur Rentify</p>
        </div>
        <a href="/machines/create" class="btn-navy">➕ Ajouter machine</a>
      </div>
      <div class="data-card">
        <div class="data-card-header">
          <span class="data-card-title">🏗 Catalogue complet</span>
          <div class="data-card-actions">
            <input class="search-ctrl" id="machineSearch" placeholder="🔍 Nom, ville…" oninput="filterMachines()">
            <select class="select-ctrl" id="machineTypeFilter" onchange="filterMachines()">
              <option value="">Tous types</option>
              <option value="excavatrice">Excavatrice</option>
              <option value="grue">Grue</option>
              <option value="bulldozer">Bulldozer</option>
              <option value="chargeuse">Chargeuse</option>
              <option value="compacteur">Compacteur</option>
              <option value="nacelle">Nacelle</option>
              <option value="tractopelle">Tractopelle</option>
              <option value="camion">Camion</option>
            </select>
            <select class="select-ctrl" id="machineStatusFilter" onchange="filterMachines()">
              <option value="">Tous statuts</option>
              <option value="available">Disponible</option>
              <option value="unavailable">Indisponible</option>
              <option value="maintenance">Maintenance</option>
            </select>
          </div>
        </div>
        <div class="tbl-wrap">
          <table>
            <thead><tr><th>#</th><th>Machine</th><th>Propriétaire</th><th>Type</th><th>Prix/jour</th><th>Ville</th><th>Statut</th><th>Actions</th></tr></thead>
            <tbody id="tbodyMachines"><tr><td colspan="8" style="text-align:center;padding:2rem;color:var(--txt-light)">Chargement…</td></tr></tbody>
          </table>
        </div>
        <div class="tbl-pagination" id="machinesPagination">
          <span id="machinesCount">—</span>
          <div class="pag-btns" id="machinesPagBtns"></div>
        </div>
      </div>
    </div>

    {{-- TAB 4 : RÉSERVATIONS --}}
    <div class="tab-panel" id="tabReservations">
      <div class="panel-header">
        <div>
          <h1 class="panel-title">Gestion des <span>Réservations</span></h1>
          <p class="panel-sub">Suivi complet de toutes les demandes</p>
        </div>
      </div>
      <div class="data-card">
        <div class="data-card-header">
          <span class="data-card-title">📋 Toutes les réservations</span>
          <div class="data-card-actions">
            <input class="search-ctrl" id="resSearch" placeholder="🔍 Client, machine…" oninput="filterReservations()">
            <select class="select-ctrl" id="resStatusFilter" onchange="filterReservations()">
              <option value="">Tous statuts</option>
              <option value="pending">En attente</option>
              <option value="accepted">Acceptée</option>
              <option value="rejected">Refusée</option>
              <option value="completed">Terminée</option>
              <option value="cancelled">Annulée</option>
            </select>
          </div>
        </div>
        <div class="tbl-wrap">
          <table>
            <thead><tr><th>#</th><th>Client</th><th>Machine</th><th>Dates</th><th>Durée</th><th>Montant</th><th>Statut</th><th>Actions</th></tr></thead>
            <tbody id="tbodyReservations"><tr><td colspan="8" style="text-align:center;padding:2rem;color:var(--txt-light)">Chargement…</td></tr></tbody>
          </table>
        </div>
        <div class="tbl-pagination" id="resPagination">
          <span id="resCount">—</span>
          <div class="pag-btns" id="resPagBtns"></div>
        </div>
      </div>
    </div>

    {{-- TAB 5 : AVIS --}}
    <div class="tab-panel" id="tabRatings">
      <div class="panel-header">
        <div>
          <h1 class="panel-title">Gestion des <span>Avis</span></h1>
          <p class="panel-sub">Modérez les évaluations des clients</p>
        </div>
      </div>
      <div class="data-card">
        <div class="data-card-header">
          <span class="data-card-title">⭐ Tous les avis</span>
          <div class="data-card-actions">
            <input class="search-ctrl" id="ratingSearch" placeholder="🔍 Machine, client…" oninput="filterRatings()">
            <select class="select-ctrl" id="ratingStarFilter" onchange="filterRatings()">
              <option value="">Toutes les notes</option>
              <option value="5">⭐⭐⭐⭐⭐ 5 étoiles</option>
              <option value="4">⭐⭐⭐⭐ 4 étoiles</option>
              <option value="3">⭐⭐⭐ 3 étoiles</option>
              <option value="2">⭐⭐ 2 étoiles</option>
              <option value="1">⭐ 1 étoile</option>
            </select>
          </div>
        </div>
        <div class="tbl-wrap">
          <table>
            <thead><tr><th>#</th><th>Machine</th><th>Client</th><th>Note</th><th>Commentaire</th><th>Date</th><th>Actions</th></tr></thead>
            <tbody id="tbodyRatings"><tr><td colspan="7" style="text-align:center;padding:2rem;color:var(--txt-light)">Chargement…</td></tr></tbody>
          </table>
        </div>
        <div class="tbl-pagination" id="ratingsPagination">
          <span id="ratingsCount">—</span>
          <div class="pag-btns" id="ratingsPagBtns"></div>
        </div>
      </div>
    </div>

  </main>
</div>

{{-- ══ MODAL UTILISATEUR ══ --}}
<div class="modal-overlay" id="userModal">
  <div class="modal-box">
    <div class="modal-header">
      <h2 id="userModalTitle">👤 Modifier l'utilisateur</h2>
      <button class="modal-close" onclick="closeModal('userModal')">✕</button>
    </div>
    <div class="modal-body">
      <input type="hidden" id="uId">
      <div class="form-row-2">
        <div class="form-group"><label class="form-label">Nom complet</label><input type="text" id="uName" class="form-control" placeholder="Nom de l'utilisateur"></div>
        <div class="form-group"><label class="form-label">Email</label><input type="email" id="uEmail" class="form-control" placeholder="email@exemple.ma"></div>
      </div>
      <div class="form-row-2">
        <div class="form-group"><label class="form-label">Rôle</label><select id="uRole" class="form-control"><option value="client">Client</option><option value="owner">Owner</option><option value="admin">Admin</option></select></div>
        <div class="form-group"><label class="form-label">Téléphone</label><input type="text" id="uPhone" class="form-control" placeholder="06XXXXXXXX"></div>
      </div>
      <div class="form-group"><label class="form-label">Ville</label><input type="text" id="uCity" class="form-control" placeholder="Casablanca"></div>
      <div class="form-group"><label class="form-label">Statut du compte</label><select id="uSuspended" class="form-control"><option value="0">✅ Actif</option><option value="1">🚫 Suspendu</option></select></div>
    </div>
    <div class="modal-footer">
      <button class="btn-sm btn-sm-outline" onclick="closeModal('userModal')" style="flex:1;justify-content:center">Annuler</button>
      <button class="btn-navy" onclick="saveUser()" style="flex:2">💾 Sauvegarder</button>
    </div>
  </div>
</div>

{{-- ══ CONFIRM DIALOG ══ --}}
<div class="confirm-overlay" id="confirmDialog">
  <div class="confirm-box">
    <div class="confirm-icon" id="confirmIcon">⚠️</div>
    <div class="confirm-title" id="confirmTitle">Confirmer l'action</div>
    <div class="confirm-msg" id="confirmMsg">Cette action est irréversible.</div>
    <div class="confirm-btns">
      <button class="btn-sm btn-sm-outline" onclick="closeConfirm()" style="padding:.5rem 1.25rem">Annuler</button>
      <button class="btn-sm btn-sm-red" id="confirmOkBtn" onclick="confirmAction()" style="padding:.5rem 1.25rem">Confirmer</button>
    </div>
  </div>
</div>

<div class="toast-ctr" id="toastCtr"></div>
@endsection

@push('scripts')
<script>
/* ════════════════════════════════════════════════
   ADMIN DASHBOARD — RENTIFY V15
   Fix navbar : topbar admin + guard role admin
════════════════════════════════════════════════ */

let allUsers=[], allMachines=[], allReservations=[], allRatings=[], statsData={};
let filteredUsers=[], filteredMachines=[], filteredReservations=[], filteredRatings=[];
let usersPage=1, machinesPage=1, resPage=1, ratingsPage=1;
const PAGE=12;
let confirmCallback=null;
let chartMain=null, chartTypes=null;

const TYPE_PHOTO = {
  excavatrice:'/images/img3.png', grue:'/images/img4.png',
  bulldozer:'/images/img1.png',   chargeuse:'/images/img2.png',
  compacteur:'/images/img8.png',  nacelle:'/images/img5.png',
  tractopelle:'/images/img7.png', camion:'/images/img9.png',
};

/* ════════════════════════
   INIT — guard admin
════════════════════════ */
document.addEventListener('DOMContentLoaded', async () => {
  const user = window.getUser ? window.getUser() : JSON.parse(localStorage.getItem('auth_user')||'null');

  // ✅ Guard : rôle admin obligatoire
  if (!user) { window.location.replace('/login'); return; }
  if (user.role !== 'admin') {
    const redirects = { owner: '/dashboard/owner', client: '/dashboard/client' };
    window.location.replace(redirects[user.role] || '/login');
    return;
  }

  // ✅ Sync topbar avatar + sidebar
  const name  = user.name || 'Admin';
  const email = user.email || 'admin@rentify.ma';
  const init  = name[0].toUpperCase();
  const photoHtml = user.profile_photo_path
    ? `<img src="/storage/${user.profile_photo_path}" alt="">`
    : init;

  document.getElementById('atbAvatarCircle').innerHTML = photoHtml;
  document.getElementById('atbAvatarName').textContent  = name;
  document.getElementById('atbDdName').textContent      = name;
  document.getElementById('atbDdEmail').textContent     = email;
  document.getElementById('sidebarName').textContent    = name;
  document.getElementById('sidebarAvatar').innerHTML    = photoHtml;
  document.getElementById('overviewDate').textContent   =
    new Date().toLocaleDateString('fr-MA', {weekday:'long',year:'numeric',month:'long',day:'numeric'});

  await refreshAll();
});

/* ════════════════════════
   TOPBAR — Avatar dropdown
════════════════════════ */
function toggleAvatarDropdown() {
  document.getElementById('atbDropdown').classList.toggle('open');
}
document.addEventListener('click', e => {
  if (!document.getElementById('atbAvatarBtn')?.contains(e.target)) {
    document.getElementById('atbDropdown')?.classList.remove('open');
  }
});

/* ════════════════════════
   TAB SWITCHING
   Sync topbar + sidebar
════════════════════════ */
function switchTab(tab) {
  const tabs = { vue:'tabVue', users:'tabUsers', machines:'tabMachines', reservations:'tabReservations', ratings:'tabRatings' };
  const sideNavs = { vue:'navVue', users:'navUsers', machines:'navMachines', reservations:'navReservations', ratings:'navRatings' };
  const topNavs  = { vue:'atbVue', users:'atbUsers', machines:'atbMachines', reservations:'atbReservations' };

  Object.values(tabs).forEach(id     => document.getElementById(id)?.classList.remove('active'));
  Object.values(sideNavs).forEach(id => document.getElementById(id)?.classList.remove('active'));
  Object.values(topNavs).forEach(id  => document.getElementById(id)?.classList.remove('active'));

  document.getElementById(tabs[tab])?.classList.add('active');
  document.getElementById(sideNavs[tab])?.classList.add('active');
  if (topNavs[tab]) document.getElementById(topNavs[tab])?.classList.add('active');

  if (tab === 'vue') renderCharts();
}

/* ════════════════════════
   DATA LOADING
════════════════════════ */
async function refreshAll() {
  await Promise.all([loadStats(), loadUsers(), loadMachines(), loadReservations(), loadRatings()]);
}

async function loadStats() {
  try {
    const data = await window.API.get('/api/admin/stats');
    statsData = data;
    document.getElementById('statUsers').textContent        = data.total_users || 0;
    document.getElementById('statMachines').textContent     = data.total_machines || 0;
    document.getElementById('statReservations').textContent = data.total_reservations || 0;
    document.getElementById('statPending').textContent      = data.pending_reservations || 0;
    document.getElementById('statSuspended').textContent    = data.suspended_users || 0;
    document.getElementById('statRevenue').textContent      = parseFloat(data.total_revenue||0).toLocaleString('fr-MA') + ' MAD';
    document.getElementById('statUsersM').innerHTML         = `<strong>${data.owners||0}</strong> owners · <strong>${data.clients||0}</strong> clients`;
    document.getElementById('statMachinesM').innerHTML      = `<strong>${data.available_machines||0}</strong> disponibles`;
    document.getElementById('statReservationsM').innerHTML  = `<strong>${data.completed_reservations||0}</strong> terminées`;
    document.getElementById('statRevenueM').innerHTML       = `<strong>${data.monthly_revenue ? parseFloat(data.monthly_revenue).toLocaleString('fr-MA')+' MAD' : '—'}</strong> ce mois`;

    // ✅ Badges topbar + sidebar sync
    const pending   = data.pending_reservations || 0;
    const suspended = data.suspended_users || 0;
    ['badgePending','topBadgePending'].forEach(id => {
      const el = document.getElementById(id);
      if (!el) return;
      if (pending > 0) { el.textContent = pending; el.style.display = ''; }
      else el.style.display = 'none';
    });
    ['badgeSuspended','topBadgeSuspended'].forEach(id => {
      const el = document.getElementById(id);
      if (!el) return;
      el.style.display = suspended > 0 ? '' : 'none';
    });

    renderCharts();
  } catch(e) { console.error('loadStats', e); }
}

function renderCharts() { renderMainChart(); renderTypeChart(); }

function renderMainChart() {
  const ctx = document.getElementById('chartMain').getContext('2d');
  if (chartMain) chartMain.destroy();
  const months = ['Jan','Fév','Mar','Avr','Mai','Jun','Jul','Aoû','Sep','Oct','Nov','Déc'];
  const resData = statsData.monthly_reservations || Array(12).fill(0).map(()=>Math.floor(Math.random()*20+5));
  const revData = statsData.monthly_revenue_data || Array(12).fill(0).map(()=>Math.floor(Math.random()*50000+5000));
  chartMain = new Chart(ctx, {
    type:'bar',
    data:{
      labels:months,
      datasets:[
        { label:'Réservations', data:resData, backgroundColor:'rgba(212,175,55,.55)', borderRadius:6, yAxisID:'y' },
        { label:'Revenus (MAD)', data:revData, type:'line', borderColor:'#9A7D20', backgroundColor:'rgba(212,175,55,.08)', borderWidth:2.5, pointBackgroundColor:'#D4AF37', pointRadius:4, tension:.4, fill:true, yAxisID:'y1' },
      ],
    },
    options:{
      responsive:true, maintainAspectRatio:false,
      interaction:{ mode:'index', intersect:false },
      plugins:{ legend:{ labels:{ font:{family:'DM Sans',size:11}, color:'#5a5660' } } },
      scales:{
        y:  { position:'left',  ticks:{ color:'#9992a4', font:{size:10} }, grid:{ color:'rgba(212,175,55,.08)' } },
        y1: { position:'right', ticks:{ color:'#D4AF37',  font:{size:10} }, grid:{ drawOnChartArea:false } },
        x:  { ticks:{ color:'#9992a4', font:{size:11} }, grid:{ display:false } },
      },
    },
  });
}

function renderTypeChart() {
  const ctx = document.getElementById('chartTypes').getContext('2d');
  if (chartTypes) chartTypes.destroy();
  const byType = {};
  allMachines.forEach(m => { const t=m.type?.toLowerCase()||'autre'; byType[t]=(byType[t]||0)+1; });
  const labels=Object.keys(byType), vals=Object.values(byType);
  const COLORS=['#D4AF37','#9A7D20','#22c55e','#3b82f6','#8b5cf6','#ef4444','#F0EBE0','#06b6d4'];
  chartTypes = new Chart(ctx, {
    type:'doughnut',
    data:{ labels, datasets:[{ data:vals, backgroundColor:COLORS, borderWidth:2, borderColor:'#fff' }] },
    options:{ responsive:true, maintainAspectRatio:false, plugins:{ legend:{ position:'bottom', labels:{ font:{family:'DM Sans',size:11}, color:'#5a5660', padding:10 } } } },
  });
}

/* ════════════════════════
   USERS
════════════════════════ */
async function loadUsers() {
  try {
    const data = await window.API.get('/api/admin/users');
    allUsers = Array.isArray(data) ? data : (data.data||data.users||[]);
    filteredUsers=[...allUsers]; usersPage=1; renderUsers();
  } catch(e) { console.error('loadUsers',e); }
}
function filterUsers() {
  const q=document.getElementById('userSearch').value.toLowerCase();
  const role=document.getElementById('userRoleFilter').value;
  const status=document.getElementById('userStatusFilter').value;
  filteredUsers=allUsers.filter(u=>{
    const matchQ=!q||u.name?.toLowerCase().includes(q)||u.email?.toLowerCase().includes(q);
    const matchR=!role||u.role===role;
    const matchS=!status||(status==='active'&&!u.is_suspended)||(status==='suspended'&&u.is_suspended);
    return matchQ&&matchR&&matchS;
  });
  usersPage=1; renderUsers();
}
function renderUsers() {
  const page=filteredUsers.slice((usersPage-1)*PAGE,usersPage*PAGE);
  const tbody=document.getElementById('tbodyUsers');
  if(!page.length){ tbody.innerHTML=`<tr><td colspan="7" style="text-align:center;padding:2rem;color:var(--txt-light)">Aucun utilisateur trouvé</td></tr>`; document.getElementById('usersCount').textContent='0 utilisateur'; document.getElementById('usersPagBtns').innerHTML=''; return; }
  tbody.innerHTML=page.map(u=>{
    const init=(u.name||'?')[0].toUpperCase();
    const photo=u.profile_photo_path?`<img src="/storage/${u.profile_photo_path}" alt="">`:init;
    const roleClass={admin:'role-admin',owner:'role-owner',client:'role-client'}[u.role]||'';
    const statusBadge=u.is_suspended?'<span class="badge badge-red">🚫 Suspendu</span>':'<span class="badge badge-green">✅ Actif</span>';
    return `<tr class="${u.is_suspended?'suspended':''}">
      <td style="color:var(--txt-light);font-size:.75rem">#${u.id}</td>
      <td><div class="user-cell"><div class="tbl-avatar">${photo}</div><div><div class="tbl-user-name">${escH(u.name)}</div><div class="tbl-user-email">${escH(u.email)}</div></div></div></td>
      <td><span class="badge ${roleClass}">${u.role}</span></td>
      <td>${escH(u.city||'—')}</td>
      <td>${escH(u.phone||'—')}</td>
      <td>${statusBadge}</td>
      <td><div style="display:flex;gap:.3rem;flex-wrap:wrap">
        <button class="btn-sm btn-sm-navy" onclick="openEditUserModal(${u.id})">✏️</button>
        ${u.is_suspended
          ?`<button class="btn-sm btn-sm-green" onclick="toggleSuspend(${u.id},false)">✅ Activer</button>`
          :`<button class="btn-sm btn-sm-red" onclick="toggleSuspend(${u.id},true)">🚫 Suspendre</button>`}
        <button class="btn-sm btn-sm-red" onclick="askDelete('user',${u.id},'${escH(u.name)}')">🗑</button>
      </div></td>
    </tr>`;
  }).join('');
  document.getElementById('usersCount').textContent=`${filteredUsers.length} utilisateur${filteredUsers.length>1?'s':''}`;
  renderPagination('usersPagBtns',usersPage,Math.ceil(filteredUsers.length/PAGE),p=>{usersPage=p;renderUsers();});
}
function openCreateUserModal() {
  document.getElementById('userModalTitle').textContent='👤 Nouvel utilisateur';
  document.getElementById('uId').value='';
  ['uName','uEmail','uPhone','uCity'].forEach(id=>document.getElementById(id).value='');
  document.getElementById('uRole').value='client'; document.getElementById('uSuspended').value='0';
  openModal('userModal');
}
function openEditUserModal(id) {
  const u=allUsers.find(x=>x.id===id); if(!u) return;
  document.getElementById('userModalTitle').textContent='✏️ Modifier l\'utilisateur';
  document.getElementById('uId').value=u.id; document.getElementById('uName').value=u.name||'';
  document.getElementById('uEmail').value=u.email||''; document.getElementById('uRole').value=u.role||'client';
  document.getElementById('uPhone').value=u.phone||''; document.getElementById('uCity').value=u.city||'';
  document.getElementById('uSuspended').value=u.is_suspended?'1':'0';
  openModal('userModal');
}
async function saveUser() {
  const id=document.getElementById('uId').value;
  const payload={name:document.getElementById('uName').value.trim(),email:document.getElementById('uEmail').value.trim(),role:document.getElementById('uRole').value,phone:document.getElementById('uPhone').value.trim(),city:document.getElementById('uCity').value.trim(),is_suspended:document.getElementById('uSuspended').value==='1'};
  try {
    let res=id?await window.API.put(`/api/admin/users/${id}`,payload):(payload.password='password',await window.API.post('/api/admin/users',payload));
    if(res.ok){ showToast('success',id?'✅ Utilisateur mis à jour':'✅ Utilisateur créé'); closeModal('userModal'); await loadUsers(); if(!id) await loadStats(); }
    else showToast('error','❌ '+(res.data?.message||'Erreur'));
  } catch(e){ showToast('error','❌ Erreur réseau'); }
}
async function toggleSuspend(id,suspend) {
  try {
    const res=await window.API.patch(suspend?`/api/admin/users/${id}/suspend`:`/api/admin/users/${id}/unsuspend`,{});
    if(res.ok){ showToast('success',suspend?'🚫 Compte suspendu':'✅ Compte réactivé'); await loadUsers(); await loadStats(); }
    else showToast('error','❌ '+(res.data?.message||'Erreur'));
  } catch(e){ showToast('error','❌ Erreur réseau'); }
}

/* ════════════════════════
   MACHINES
════════════════════════ */
async function loadMachines() {
  try {
    const data=await window.API.get('/api/machines');
    allMachines=Array.isArray(data)?data:(data.data||data.machines||[]);
    filteredMachines=[...allMachines]; machinesPage=1; renderMachines(); renderTypeChart();
  } catch(e){ console.error('loadMachines',e); }
}
function filterMachines() {
  const q=document.getElementById('machineSearch').value.toLowerCase();
  const type=document.getElementById('machineTypeFilter').value;
  const status=document.getElementById('machineStatusFilter').value;
  filteredMachines=allMachines.filter(m=>{
    const matchQ=!q||m.name?.toLowerCase().includes(q)||m.city?.toLowerCase().includes(q);
    const matchT=!type||m.type?.toLowerCase()===type;
    const matchS=!status||m.status===status;
    return matchQ&&matchT&&matchS;
  });
  machinesPage=1; renderMachines();
}
function renderMachines() {
  const page=filteredMachines.slice((machinesPage-1)*PAGE,machinesPage*PAGE);
  const tbody=document.getElementById('tbodyMachines');
  if(!page.length){ tbody.innerHTML=`<tr><td colspan="8" style="text-align:center;padding:2rem;color:var(--txt-light)">Aucune machine trouvée</td></tr>`; document.getElementById('machinesCount').textContent='0 machine'; document.getElementById('machinesPagBtns').innerHTML=''; return; }
  const statusBadge={available:'<span class="badge badge-green">✅ Disponible</span>',unavailable:'<span class="badge badge-red">❌ Indisponible</span>',maintenance:'<span class="badge badge-yellow">🔧 Maintenance</span>'};
  tbody.innerHTML=page.map(m=>{
    const img=m.images?.[0]?.path?`/storage/${m.images[0].path}`:(TYPE_PHOTO[m.type?.toLowerCase()]||'/images/img1.png');
    return `<tr>
      <td style="color:var(--txt-light);font-size:.75rem">#${m.id}</td>
      <td><div class="machine-cell"><img class="tbl-machine-img" src="${img}" alt="" onerror="this.src='${TYPE_PHOTO[m.type?.toLowerCase()]||'/images/img1.png'}'"><div><div style="font-weight:700;color:var(--txt-dark);font-size:.83rem">${escH(m.name)}</div></div></div></td>
      <td>${escH(m.owner?.name||'—')}</td>
      <td><span class="badge badge-navy">${m.type||'—'}</span></td>
      <td style="font-weight:700;color:var(--txt-dark)">${parseFloat(m.price_per_day||0).toLocaleString('fr-MA')} MAD</td>
      <td>📍 ${escH(m.city||'—')}</td>
      <td>${statusBadge[m.status]||'—'}</td>
      <td><div style="display:flex;gap:.3rem">
        <a href="/machines/${m.id}" class="btn-sm btn-sm-blue" target="_blank">👁</a>
        <a href="/machines/${m.id}/edit" class="btn-sm btn-sm-navy">✏️</a>
        <button class="btn-sm btn-sm-red" onclick="askDelete('machine',${m.id},'${escH(m.name)}')">🗑</button>
      </div></td>
    </tr>`;
  }).join('');
  document.getElementById('machinesCount').textContent=`${filteredMachines.length} machine${filteredMachines.length>1?'s':''}`;
  renderPagination('machinesPagBtns',machinesPage,Math.ceil(filteredMachines.length/PAGE),p=>{machinesPage=p;renderMachines();});
}

/* ════════════════════════
   RÉSERVATIONS
════════════════════════ */
async function loadReservations() {
  try {
    const data=await window.API.get('/api/admin/reservations');
    allReservations=Array.isArray(data)?data:(data.data||data.reservations||[]);
    filteredReservations=[...allReservations]; resPage=1; renderReservations(); renderActivityTable();
  } catch(e){ console.error('loadReservations',e); }
}
function filterReservations() {
  const q=document.getElementById('resSearch').value.toLowerCase();
  const status=document.getElementById('resStatusFilter').value;
  filteredReservations=allReservations.filter(r=>{
    const matchQ=!q||r.client?.name?.toLowerCase().includes(q)||r.machine?.name?.toLowerCase().includes(q);
    const matchS=!status||r.status===status;
    return matchQ&&matchS;
  });
  resPage=1; renderReservations();
}
function renderReservations() {
  renderReservationTable('tbodyReservations',filteredReservations,resPage);
  document.getElementById('resCount').textContent=`${filteredReservations.length} réservation${filteredReservations.length>1?'s':''}`;
  renderPagination('resPagBtns',resPage,Math.ceil(filteredReservations.length/PAGE),p=>{resPage=p;renderReservations();});
}
function renderActivityTable() {
  const recent=[...allReservations].sort((a,b)=>new Date(b.created_at)-new Date(a.created_at)).slice(0,6);
  renderReservationTable('tbodyActivity',recent,1,true);
}
function renderReservationTable(tbodyId,list,page,noPage=false) {
  const data=noPage?list:list.slice((page-1)*PAGE,page*PAGE);
  const tbody=document.getElementById(tbodyId);
  if(!data.length){ tbody.innerHTML=`<tr><td colspan="8" style="text-align:center;padding:2rem;color:var(--txt-light)">Aucune réservation</td></tr>`; return; }
  const statusBadge={pending:'<span class="badge badge-yellow">⏳ En attente</span>',accepted:'<span class="badge badge-green">✅ Acceptée</span>',rejected:'<span class="badge badge-red">❌ Refusée</span>',completed:'<span class="badge badge-blue">🏁 Terminée</span>',cancelled:'<span class="badge badge-gray">🚫 Annulée</span>'};
  tbody.innerHTML=data.map(r=>{
    const start=r.start_date?new Date(r.start_date).toLocaleDateString('fr-MA'):'—';
    const end=r.end_date?new Date(r.end_date).toLocaleDateString('fr-MA'):'—';
    const days=r.start_date&&r.end_date?Math.ceil((new Date(r.end_date)-new Date(r.start_date))/86400000):'—';
    return `<tr>
      <td style="color:var(--txt-light);font-size:.75rem">#${r.id}</td>
      <td><div style="font-weight:700;color:var(--txt-dark);font-size:.83rem">${escH(r.client?.name||'—')}</div><div style="font-size:.72rem;color:var(--txt-light)">${escH(r.client?.email||'')}</div></td>
      <td style="font-weight:600;color:var(--txt-dark)">${escH(r.machine?.name||'—')}</td>
      <td style="font-size:.78rem">${start} → ${end}</td>
      <td><span class="badge badge-navy">${days} j</span></td>
      <td style="font-weight:700;color:var(--txt-dark)">${parseFloat(r.total_price||0).toLocaleString('fr-MA')} MAD</td>
      <td>${statusBadge[r.status]||r.status}</td>
      <td><div style="display:flex;gap:.3rem">
        ${r.status==='pending'?`<button class="btn-sm btn-sm-green" onclick="changeResStatus(${r.id},'accept')">✅</button><button class="btn-sm btn-sm-red" onclick="changeResStatus(${r.id},'reject')">❌</button>`:''}
        <a href="/api/reservations/${r.id}/contrat" target="_blank" class="btn-sm btn-sm-blue" title="Contrat PDF">📄</a>
        <button class="btn-sm btn-sm-red" onclick="askDelete('reservation',${r.id},'#${r.id}')">🗑</button>
      </div></td>
    </tr>`;
  }).join('');
}
async function changeResStatus(id,action) {
  try {
    const res=await window.API.patch(`/api/reservations/${id}/${action}`,{});
    if(res.ok){ showToast('success',action==='accept'?'✅ Réservation acceptée':'❌ Réservation rejetée'); await loadReservations(); await loadStats(); }
    else showToast('error','❌ '+(res.data?.message||'Erreur'));
  } catch(e){ showToast('error','❌ Erreur réseau'); }
}

/* ════════════════════════
   AVIS
════════════════════════ */
async function loadRatings() {
  try {
    const data=await window.API.get('/api/admin/ratings');
    allRatings=Array.isArray(data)?data:(data.data||data.ratings||[]);
    filteredRatings=[...allRatings]; ratingsPage=1; renderRatings();
  } catch(e){ console.error('loadRatings',e); }
}
function filterRatings() {
  const q=document.getElementById('ratingSearch').value.toLowerCase();
  const star=document.getElementById('ratingStarFilter').value;
  filteredRatings=allRatings.filter(r=>{
    const matchQ=!q||r.machine?.name?.toLowerCase().includes(q)||r.user?.name?.toLowerCase().includes(q)||r.comment?.toLowerCase().includes(q);
    const matchS=!star||Math.round(r.rating)===parseInt(star);
    return matchQ&&matchS;
  });
  ratingsPage=1; renderRatings();
}
function renderRatings() {
  const page=filteredRatings.slice((ratingsPage-1)*PAGE,ratingsPage*PAGE);
  const tbody=document.getElementById('tbodyRatings');
  if(!page.length){ tbody.innerHTML=`<tr><td colspan="7" style="text-align:center;padding:2rem;color:var(--txt-light)">Aucun avis trouvé</td></tr>`; document.getElementById('ratingsCount').textContent='0 avis'; document.getElementById('ratingsPagBtns').innerHTML=''; return; }
  tbody.innerHTML=page.map(r=>`<tr>
    <td style="color:var(--txt-light);font-size:.75rem">#${r.id}</td>
    <td style="font-weight:600;color:var(--txt-dark)">${escH(r.machine?.name||'—')}</td>
    <td><div style="font-weight:600;color:var(--txt-dark);font-size:.83rem">${escH(r.user?.name||'—')}</div><div style="font-size:.72rem;color:var(--txt-light)">${escH(r.user?.email||'')}</div></td>
    <td><div style="font-size:.9rem">${'⭐'.repeat(Math.round(r.rating))}</div><div style="font-size:.72rem;color:var(--txt-light)">${r.rating}/5</div></td>
    <td style="max-width:240px;font-size:.8rem;color:var(--txt-mid)">${escH(r.comment||'—')}</td>
    <td style="font-size:.78rem;color:var(--txt-light)">${r.created_at?new Date(r.created_at).toLocaleDateString('fr-MA'):'—'}</td>
    <td><button class="btn-sm btn-sm-red" onclick="askDelete('rating',${r.id},'avis #${r.id}')">🗑 Supprimer</button></td>
  </tr>`).join('');
  document.getElementById('ratingsCount').textContent=`${filteredRatings.length} avis`;
  renderPagination('ratingsPagBtns',ratingsPage,Math.ceil(filteredRatings.length/PAGE),p=>{ratingsPage=p;renderRatings();});
}

/* ════════════════════════
   DELETE / CONFIRM
════════════════════════ */
function askDelete(type,id,label) {
  const msgs={
    user:{icon:'👤',title:'Supprimer l\'utilisateur ?',msg:`L'utilisateur "${label}" sera supprimé définitivement.`},
    machine:{icon:'🏗',title:'Supprimer la machine ?',msg:`La machine "${label}" et ses données seront supprimées.`},
    reservation:{icon:'📋',title:'Supprimer la réservation ?',msg:`La réservation ${label} sera supprimée.`},
    rating:{icon:'⭐',title:'Supprimer cet avis ?',msg:`L'${label} sera supprimé définitivement.`}
  };
  const cfg=msgs[type];
  document.getElementById('confirmIcon').textContent=cfg.icon;
  document.getElementById('confirmTitle').textContent=cfg.title;
  document.getElementById('confirmMsg').textContent=cfg.msg;
  confirmCallback=()=>doDelete(type,id);
  document.getElementById('confirmDialog').classList.add('open');
}
async function doDelete(type,id) {
  const endpoints={user:`/api/admin/users/${id}`,machine:`/api/machines/${id}`,reservation:`/api/admin/reservations/${id}`,rating:`/api/admin/ratings/${id}`};
  try {
    const res=await window.API.delete(endpoints[type]);
    if(res.ok){ showToast('success','🗑 Supprimé avec succès'); refreshAll(); }
    else showToast('error','❌ '+(res.data?.message|'Erreur'));
  } catch(e){ showToast('error','❌ Erreur réseau'); }
}
function confirmAction(){ closeConfirm(); if(typeof confirmCallback==='function') confirmCallback(); }
function closeConfirm(){ document.getElementById('confirmDialog').classList.remove('open'); confirmCallback=null; }

/* ════════════════════════
   HELPERS
════════════════════════ */
function renderPagination(containerId,current,total,cb) {
  const el=document.getElementById(containerId); if(!el) return;
  if(total<=1){ el.innerHTML=''; return; }
  let html=`<button class="pag-btn" onclick="(${cb.toString()})(${current-1})" ${current===1?'disabled':''}>‹</button>`;
  for(let p=1;p<=total;p++){
    if(total>7&&p>2&&p<total-1&&Math.abs(p-current)>1){ if(p===3||p===total-2) html+=`<span style="padding:0 .3rem;color:var(--txt-light)">…</span>`; continue; }
    html+=`<button class="pag-btn ${p===current?'active':''}" onclick="(${cb.toString()})(${p})">${p}</button>`;
  }
  html+=`<button class="pag-btn" onclick="(${cb.toString()})(${current+1})" ${current===total?'disabled':''}>›</button>`;
  el.innerHTML=html;
}
function openModal(id){ document.getElementById(id)?.classList.add('open'); }
function closeModal(id){ document.getElementById(id)?.classList.remove('open'); }
document.querySelectorAll('.modal-overlay').forEach(m=>{
  m.addEventListener('click',e=>{ if(e.target===m) m.classList.remove('open'); });
});
document.addEventListener('keydown',e=>{
  if(e.key==='Escape'){
    document.querySelectorAll('.modal-overlay.open').forEach(m=>m.classList.remove('open'));
    closeConfirm();
  }
});
function logout(){
  localStorage.removeItem('auth_token');
  localStorage.removeItem('auth_user');
  window.location.replace('/login');
}
function showToast(type,msg) {
  const ctr=document.getElementById('toastCtr');
  const el=document.createElement('div'); el.className=`toast ${type}`; el.innerHTML=msg;
  ctr.appendChild(el); requestAnimationFrame(()=>el.classList.add('show'));
  setTimeout(()=>{ el.classList.remove('show'); setTimeout(()=>el.remove(),400); },3500);
}
function escH(s){ if(!s) return ''; return String(s).replace(/&/g,'&amp;').replace(/</g,'&lt;').replace(/>/g,'&gt;').replace(/"/g,'&quot;'); }
</script>
@endpush