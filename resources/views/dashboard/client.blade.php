@extends('layouts.app')
@section('title', 'Mon espace — Rentify')

@push('styles')
<style>
/* ══════════════════════════════════════
   DASHBOARD CLIENT V9 — Gold / Crème (100% clair)
   ══════════════════════════════════════ */
:root {
    --gold:#D4AF37; --gold-dk:#9A7D20; --gold-lt:#F5E88A;
    --gold-pale:#FEF9E7; --gold-glow:rgba(212,175,55,.25);
    --navy:#0F1B2D; --navy2:#162540; --navy3:#1E3356;
    --cream:#FAF7F0; --cream2:#F0EBE0; --cream3:#E8DDD0;
    --txt-dark:#1a1a2e; --txt:#5a5660; --txt-light:#9992a4;
    --green:#10b981; --red:#ef4444; --blue:#3b82f6;
}
body { background: var(--cream); }

.dash-wrap {
    max-width:1280px; margin:0 auto; padding:28px 32px;
    display:grid; grid-template-columns:240px 1fr; gap:24px; align-items:start;
}

/* ── Sidebar ── */
.dash-sidebar {
    background:#fff; border:1px solid rgba(212,175,55,.18);
    border-radius:14px; overflow:hidden; position:sticky; top:90px;
    box-shadow:0 4px 20px rgba(15,27,45,.04);
}
.dash-user-block {
    background:var(--gold-pale);
    border-bottom:1px solid rgba(212,175,55,.2);
    padding:20px 18px;
    display:flex; align-items:center; gap:12px;
}
.dash-avatar {
    width:44px; height:44px; background:var(--gold);
    border-radius:50%; display:flex; align-items:center; justify-content:center;
    font-size:18px; font-weight:800; color:var(--txt-dark); flex-shrink:0;
    border:2px solid rgba(212,175,55,.35);
}
.dash-user-name { color:var(--txt-dark); font-size:14px; font-weight:700; }
.dash-user-role {
    display:inline-flex; align-items:center; gap:4px;
    background:rgba(212,175,55,.15); color:var(--gold-dk);
    font-size:10px; font-weight:700; padding:2px 8px;
    border-radius:100px; margin-top:3px;
}
.dash-nav { padding:10px 0; }
.dash-nav-item {
    display:flex; align-items:center; gap:10px;
    padding:10px 18px; font-size:13px; font-weight:500;
    color:var(--txt); cursor:pointer; transition:all .15s;
    border-left:3px solid transparent; text-decoration:none;
    background:none; border-top:none; border-right:none; border-bottom:none;
    width:100%; font-family:inherit;
}
.dash-nav-item:hover { background:rgba(212,175,55,.05); color:var(--txt-dark); }
.dash-nav-item.active {
    background:rgba(212,175,55,.08); color:var(--gold-dk);
    border-left-color:var(--gold); font-weight:700;
}
.dash-nav-item i { width:16px; text-align:center; font-size:13px; color:var(--gold); }
.dash-nav-sep { height:1px; background:rgba(212,175,55,.1); margin:6px 0; }
.btn-logout {
    display:flex; align-items:center; gap:10px;
    padding:10px 18px; font-size:13px; font-weight:500;
    color:var(--red); cursor:pointer; width:100%; border:none;
    background:none; text-align:left; transition:background .15s; font-family:inherit;
}
.btn-logout:hover { background:rgba(239,68,68,.05); }

/* ── KPI Cards ── */
.kpi-grid { display:grid; grid-template-columns:repeat(3,1fr); gap:14px; margin-bottom:22px; }
.kpi-card {
    background:#fff; border:1px solid rgba(212,175,55,.12);
    border-radius:14px; padding:18px; transition:box-shadow .2s,transform .2s;
    border-left:3px solid var(--gold);
}
.kpi-card:hover { box-shadow:0 6px 24px rgba(15,27,45,.08); transform:translateY(-2px); }
.kpi-card.blue  { border-left-color:var(--blue); }
.kpi-card.green { border-left-color:var(--green); }
.kpi-label {
    font-size:11px; font-weight:700; color:var(--txt-light);
    text-transform:uppercase; letter-spacing:.8px; margin-bottom:8px;
    display:flex; align-items:center; gap:6px;
}
.kpi-label i { color:var(--gold); }
.kpi-value { font-size:28px; font-weight:900; color:var(--txt-dark); letter-spacing:-1px; }
.kpi-sub { font-size:11px; color:var(--txt-light); margin-top:2px; }

/* ── Panel ── */
.dash-panel {
    background:#fff; border:1px solid rgba(212,175,55,.12);
    border-radius:14px; overflow:hidden; margin-bottom:20px;
    box-shadow:0 2px 12px rgba(15,27,45,.04);
}
.panel-header {
    display:flex; align-items:center; justify-content:space-between;
    padding:16px 20px; border-bottom:1px solid rgba(212,175,55,.1);
    flex-wrap:wrap; gap:10px;
}
.panel-title { font-size:15px; font-weight:800; color:var(--txt-dark); display:flex; align-items:center; gap:8px; }
.panel-title i { color:var(--gold); }
.panel-badge {
    font-size:11px; font-weight:700; padding:3px 10px;
    border-radius:100px; background:rgba(212,175,55,.1); color:var(--gold-dk);
}

/* ── Reservations Table ── */
.res-table { width:100%; border-collapse:collapse; }
.res-table th {
    text-align:left; padding:10px 20px;
    font-size:10px; font-weight:800; color:var(--txt-light);
    letter-spacing:1px; text-transform:uppercase;
    background:var(--cream); border-bottom:1px solid rgba(212,175,55,.1);
}
.res-table td {
    padding:14px 20px; border-bottom:1px solid rgba(212,175,55,.06);
    font-size:13px; color:var(--txt-dark); vertical-align:middle;
}
.res-table tr:last-child td { border-bottom:none; }
.res-table tr:hover td { background:rgba(212,175,55,.02); }

/* ── Status badges ── */
.stat-badge {
    display:inline-flex; align-items:center; gap:5px;
    font-size:11px; font-weight:700; padding:3px 10px; border-radius:100px;
}
.sb-pending   { background:#FEF9E7; color:var(--gold-dk); }
.sb-accepted  { background:rgba(16,185,129,.1);  color:#065f46; }
.sb-rejected  { background:rgba(239,68,68,.1);   color:#991b1b; }
.sb-completed { background:rgba(99,102,241,.1);  color:#3730a3; }
.sb-cancelled { background:rgba(107,114,128,.1); color:#374151; }
.sb-default   { background:rgba(212,175,55,.08); color:var(--txt); }

/* ── Action buttons ── */
.btn-action {
    display:inline-flex; align-items:center; gap:4px;
    border:none; border-radius:7px; padding:5px 10px;
    font-size:.78rem; font-weight:600; cursor:pointer;
    transition:all .15s; font-family:inherit; text-decoration:none;
}
.btn-contrat  { background:rgba(212,175,55,.12); color:var(--gold-dk); }
.btn-contrat:hover { background:rgba(212,175,55,.22); }
.btn-voir     { background:var(--cream); color:var(--txt-dark); border:1px solid var(--cream3); }
.btn-voir:hover { background:var(--cream2); color:var(--txt-dark); }
.btn-wa       { background:#25D366; color:#fff; }
.btn-wa:hover { background:#1ebe5d; color:#fff; }
.btn-cancel   { background:rgba(239,68,68,.08); color:var(--red); }
.btn-cancel:hover { background:rgba(239,68,68,.15); }

/* ── Favorites Grid ── */
.favs-grid { display:grid; grid-template-columns:repeat(auto-fill,minmax(240px,1fr)); gap:16px; padding:20px; }
.fav-card {
    background:#fff; border:1px solid rgba(212,175,55,.12);
    border-radius:12px; overflow:hidden; position:relative;
    transition:transform .2s, box-shadow .2s, border-color .2s;
}
.fav-card:hover { transform:translateY(-3px); box-shadow:0 8px 24px rgba(15,27,45,.08); border-color:rgba(212,175,55,.3); }
.fav-photo { height:120px; overflow:hidden; background:var(--cream2); position:relative; }
.fav-photo img { width:100%; height:100%; object-fit:cover; }
.fav-remove {
    position:absolute; top:8px; right:8px; width:28px; height:28px;
    background:rgba(239,68,68,.85); border:none; border-radius:50%;
    color:#fff; cursor:pointer; display:flex; align-items:center; justify-content:center;
    font-size:.8rem; transition:background .15s;
}
.fav-remove:hover { background:#ef4444; }
.fav-body { padding:12px 14px; }
.fav-type { font-size:.65rem; font-weight:800; color:var(--gold-dk); letter-spacing:.08em; text-transform:uppercase; margin-bottom:3px; }
.fav-name { font-size:.88rem; font-weight:700; color:var(--txt-dark); margin-bottom:4px; }
.fav-city { font-size:.75rem; color:var(--txt-light); margin-bottom:8px; }
.fav-city i { color:var(--gold); margin-right:3px; }
.fav-price { font-size:.95rem; font-weight:800; color:var(--gold-dk); }
.fav-price small { font-size:.7rem; font-weight:400; color:var(--txt-light); }
.fav-actions { display:flex; gap:6px; margin-top:10px; }

/* ── Filter bar ── */
.filter-bar {
    display:flex; gap:8px; flex-wrap:wrap; align-items:center;
    padding:14px 20px; background:var(--cream); border-bottom:1px solid rgba(212,175,55,.1);
}
.filter-bar select, .filter-bar input {
    border:1px solid rgba(212,175,55,.2); border-radius:8px;
    padding:7px 12px; font-size:.82rem; color:var(--txt-dark);
    background:#fff; outline:none; transition:border-color .2s; font-family:inherit;
}
.filter-bar select:focus, .filter-bar input:focus { border-color:var(--gold); }
.filter-clear {
    font-size:.78rem; color:var(--txt-light); cursor:pointer;
    background:none; border:none; font-family:inherit; padding:7px 8px;
    text-decoration:underline; transition:color .15s;
}
.filter-clear:hover { color:var(--red); }

/* ── Profile Form ── */
.profile-wrap { padding:24px; }
.profile-avatar-section {
    display:flex; align-items:center; gap:20px; margin-bottom:28px;
    padding-bottom:24px; border-bottom:1px solid rgba(212,175,55,.1);
}
.profile-avatar-big {
    width:72px; height:72px; background:var(--gold);
    border-radius:50%; display:flex; align-items:center; justify-content:center;
    font-size:28px; font-weight:800; color:var(--txt-dark);
    border:3px solid rgba(212,175,55,.3);
}
.form-group { margin-bottom:18px; }
.form-label {
    display:block; font-size:.78rem; font-weight:700; color:var(--txt-light);
    text-transform:uppercase; letter-spacing:.8px; margin-bottom:6px;
}
.form-input {
    width:100%; padding:10px 14px; border-radius:9px;
    border:1.5px solid rgba(212,175,55,.2); font-size:.88rem;
    color:var(--txt-dark); background:#fff; outline:none; transition:border-color .2s;
    font-family:inherit;
}
.form-input:focus { border-color:var(--gold); box-shadow:0 0 0 3px rgba(212,175,55,.1); }
.form-grid { display:grid; grid-template-columns:1fr 1fr; gap:16px; }
.btn-save {
    background:var(--gold); color:var(--txt-dark); border:none;
    border-radius:9px; padding:11px 28px; font-size:.88rem; font-weight:700;
    cursor:pointer; transition:all .15s; font-family:inherit;
    display:inline-flex; align-items:center; gap:8px;
}
.btn-save:hover { background:var(--gold-dk); color:#fff; transform:translateY(-1px); }

/* ── Empty state ── */
.empty-state { padding:48px 20px; text-align:center; color:var(--txt-light); }
.empty-ico { font-size:40px; margin-bottom:10px; opacity:.4; display:block; }
.empty-title { font-size:15px; font-weight:700; color:var(--txt-dark); margin-bottom:5px; }
.empty-sub { font-size:13px; }

.cta-banner {
    background:var(--gold-pale);
    border:1.5px solid var(--gold);
    border-radius:14px; padding:22px 26px;
    display:flex; align-items:center; justify-content:space-between;
    flex-wrap:wrap; gap:16px; margin-bottom:20px;
}
.cta-banner-title { font-size:15px; font-weight:800; color:var(--txt-dark); margin-bottom:4px; }
.cta-banner-sub   { font-size:12px; color:var(--txt); }
.btn-cta {
    background:var(--gold); color:var(--txt-dark)!important;
    font-size:.82rem; font-weight:700; border:none; border-radius:8px;
    padding:9px 20px; cursor:pointer; transition:all .15s;
    text-decoration:none; display:inline-flex; align-items:center; gap:6px; white-space:nowrap;
}
.btn-cta:hover { background:var(--gold-dk); color:#fff!important; transform:translateY(-1px); }

#dash-toast {
    position:fixed; bottom:24px; right:24px; z-index:9999;
    background:#fff; color:var(--txt-dark);
    padding:12px 20px; border-radius:10px; font-size:.88rem; font-weight:600;
    box-shadow:0 4px 20px rgba(15,27,45,.15);
    display:none; align-items:center; gap:10px;
    border-left:3px solid var(--gold);
}
#dash-toast.show { display:flex; animation:slideUp .3s ease; }
#dash-toast.err  { border-left-color:var(--red); }
@keyframes slideUp { from{transform:translateY(14px);opacity:0} to{transform:translateY(0);opacity:1} }

@media(max-width:900px) {
    .dash-wrap { grid-template-columns:1fr; }
    .dash-sidebar { position:static; }
    .kpi-grid { grid-template-columns:1fr 1fr; }
    .form-grid { grid-template-columns:1fr; }
}
@media(max-width:600px) {
    .dash-wrap { padding:16px; }
    .kpi-grid { grid-template-columns:1fr; }
}
</style>
@endpush

@section('content')
<div class="dash-wrap" id="dash-root">
    <div style="grid-column:1/-1;text-align:center;padding:60px;color:var(--txt-light)">
        <i class="fas fa-spinner fa-spin" style="font-size:2rem;color:var(--gold)"></i>
        <p style="margin-top:12px">Chargement...</p>
    </div>
</div>
<div id="dash-toast"><i class="fas fa-check-circle" style="color:var(--gold)"></i><span id="toast-msg"></span></div>
@endsection

@push('scripts')
<script>
(function () {

/* ── Fallbacks ── */
if (typeof getFavorites   === 'undefined') window.getFavorites   = () => JSON.parse(localStorage.getItem('rentify_favorites') || '[]');
if (typeof isFavorite     === 'undefined') window.isFavorite     = id => getFavorites().includes(Number(id));
if (typeof toggleFavorite === 'undefined') window.toggleFavorite = id => {
    const favs = getFavorites(), idx = favs.indexOf(Number(id));
    if (idx >= 0) favs.splice(idx,1); else favs.push(Number(id));
    localStorage.setItem('rentify_favorites', JSON.stringify(favs));
};

/* ── Guard ── */
const _token = getToken(), _user = getUser();
if (!_token || !_user) { window.location.replace('/login'); return; }
if (_user.role === 'admin')  { window.location.replace('/dashboard/admin');  return; }
if (_user.role === 'owner')  { window.location.replace('/dashboard/owner');  return; }
if (_user.role === 'driver') { window.location.replace('/dashboard/driver'); return; }

/* ══════════════════════════════════════
   STATE
   ══════════════════════════════════════ */
const user = _user;
let allReservations = null; /* FIX: null = pas encore chargé, [] = chargé mais vide */
let activeTab = 'reservations';

let filterStatus = '', filterType = '', filterCity = '', filterSearch = '';

/* ══════════════════════════════════════
   TOAST
   ══════════════════════════════════════ */
function toast(msg, isErr=false) {
    const t = document.getElementById('dash-toast');
    document.getElementById('toast-msg').textContent = msg;
    t.className = 'show' + (isErr?' err':'');
    setTimeout(() => t.className = '', 3500);
}

/* ══════════════════════════════════════
   HELPERS
   ══════════════════════════════════════ */
const fDate  = d => d ? new Date(d).toLocaleDateString('fr-MA',{day:'2-digit',month:'short',year:'numeric'}) : '—';
const fMoney = n => Number(n||0).toLocaleString('fr-MA') + ' DH';
const initial  = (user.name||'C')[0].toUpperCase();
const firstName = (user.name||'Client').split(' ')[0];

const TYPE_PHOTO = {
    excavatrice:'/images/img3.png', grue:'/images/img4.png',
    bulldozer:'/images/img1.png',   chargeuse:'/images/img2.png',
    compacteur:'/images/img8.png',  nacelle:'/images/img5.png',
    tractopelle:'/images/img7.png', camion:'/images/img9.png',
};
const getPhoto = m => TYPE_PHOTO[(m.type||'').toLowerCase()] || '/images/img7.png';

/* ══════════════════════════════════════
   SIDEBAR
   ══════════════════════════════════════ */
function sidebarHTML() {
    const navItems = [
        { id:'reservations', icon:'fa-calendar-check', label:'Mes réservations' },
        { id:'favorites',    icon:'fa-heart',           label:'Mes favoris' },
        { id:'profile',      icon:'fa-user',            label:'Mon profil' },
    ];
    return `
    <aside class="dash-sidebar">
        <div class="dash-user-block">
            <div class="dash-avatar">${initial}</div>
            <div>
                <div class="dash-user-name">${user.name||'Client'}</div>
                <div class="dash-user-role"><i class="fas fa-circle" style="font-size:5px"></i> Client</div>
            </div>
        </div>
        <nav class="dash-nav">
            ${navItems.map(n=>`
            <button class="dash-nav-item${activeTab===n.id?' active':''}" onclick="switchTab('${n.id}')">
                <i class="fas ${n.icon}"></i> ${n.label}
            </button>`).join('')}
            <div class="dash-nav-sep"></div>
            <a href="/machines" class="dash-nav-item"><i class="fas fa-search"></i> Chercher un engin</a>
            <button class="btn-logout" onclick="doLogout()"><i class="fas fa-sign-out-alt"></i> Déconnexion</button>
        </nav>
    </aside>`;
}

/* ══════════════════════════════════════
   SWITCH TAB
   ══════════════════════════════════════ */
window.switchTab = function(tab) {
    activeTab = tab;
    render();
};

/* ══════════════════════════════════════
   MAIN RENDER
   ══════════════════════════════════════ */
async function render() {
    const root = document.getElementById('dash-root');
    let mainHTML = '';

    if (activeTab === 'reservations') mainHTML = await renderReservationsTab();
    else if (activeTab === 'favorites') mainHTML = renderFavoritesTab();
    else if (activeTab === 'profile')   mainHTML = renderProfileTab();

    root.innerHTML = sidebarHTML() + `<main class="dash-main">${mainHTML}</main>`;
    afterRender();
}

/* ══════════════════════════════════════
   TAB: RÉSERVATIONS
   ══════════════════════════════════════ */
async function renderReservationsTab() {

    /* FIX: charge uniquement si pas encore chargé (null) */
    if (allReservations === null) {
        try {
            const d = await API.get('/api/reservations');
            /* FIX: accepte array direct ou {data:[]} — jamais fallback demo */
            allReservations = Array.isArray(d) ? d : (Array.isArray(d?.data) ? d.data : []);
        } catch(e) {
            allReservations = [];
        }
    }

    const total   = allReservations.length;
    const active  = allReservations.filter(r=>r.status==='accepted').length;
    const pending = allReservations.filter(r=>r.status==='pending').length;

    /* Apply filters */
    let filtered = allReservations;
    if (filterStatus) filtered = filtered.filter(r=>r.status===filterStatus);
    if (filterType)   filtered = filtered.filter(r=>(r.machine?.type||'').toLowerCase()===filterType.toLowerCase());
    if (filterCity)   filtered = filtered.filter(r=>(r.machine?.city||'').toLowerCase().includes(filterCity.toLowerCase()));
    if (filterSearch) filtered = filtered.filter(r=>(r.machine?.name||'').toLowerCase().includes(filterSearch.toLowerCase()));

    return `
    <div class="dash-header">
        <div style="font-size:22px;font-weight:900;color:var(--txt-dark);letter-spacing:-.4px">Bonjour, ${firstName} 👋</div>
        <div style="font-size:13px;color:var(--txt);margin-top:3px">Voici un résumé de votre activité sur Rentify</div>
    </div>

    <div class="kpi-grid">
        <div class="kpi-card">
            <div class="kpi-label"><i class="fas fa-calendar-check"></i> Réservations</div>
            <div class="kpi-value">${total}</div>
            <div class="kpi-sub">au total</div>
        </div>
        <div class="kpi-card green">
            <div class="kpi-label"><i class="fas fa-check-circle"></i> En cours</div>
            <div class="kpi-value">${active}</div>
            <div class="kpi-sub">locations actives</div>
        </div>
        <div class="kpi-card blue">
            <div class="kpi-label"><i class="fas fa-hourglass-half"></i> En attente</div>
            <div class="kpi-value">${pending}</div>
            <div class="kpi-sub">réponse propriétaire</div>
        </div>
    </div>

    <div class="dash-panel">
        <div class="panel-header">
            <div class="panel-title"><i class="fas fa-calendar-check"></i> Mes réservations</div>
            <div class="panel-badge">${total} au total</div>
        </div>

        <div class="filter-bar">
            <input type="text" placeholder="🔍 Rechercher machine..." id="f-search"
                value="${filterSearch}" oninput="applyFilter('search',this.value)"
                style="min-width:180px">
            <select id="f-status" onchange="applyFilter('status',this.value)">
                <option value="">Tous statuts</option>
                <option value="pending"   ${filterStatus==='pending'?'selected':''}>En attente</option>
                <option value="accepted"  ${filterStatus==='accepted'?'selected':''}>Acceptées</option>
                <option value="rejected"  ${filterStatus==='rejected'?'selected':''}>Refusées</option>
                <option value="completed" ${filterStatus==='completed'?'selected':''}>Terminées</option>
                <option value="cancelled" ${filterStatus==='cancelled'?'selected':''}>Annulées</option>
            </select>
            <select id="f-type" onchange="applyFilter('type',this.value)">
                <option value="">Tous types</option>
                ${['Excavatrice','Grue','Bulldozer','Chargeuse','Compacteur','Tractopelle','Camion']
                    .map(t=>`<option value="${t}" ${filterType===t?'selected':''}>${t}</option>`).join('')}
            </select>
            <input type="text" placeholder="Ville..." id="f-city"
                value="${filterCity}" oninput="applyFilter('city',this.value)"
                style="width:110px">
            <button class="filter-clear" onclick="clearFilters()">✕ Réinitialiser</button>
        </div>

        ${renderReservationsTable(filtered)}
    </div>

    <div class="cta-banner">
        <div>
            <div class="cta-banner-title">Besoin d'un engin ?</div>
            <div class="cta-banner-sub">Parcourez 500+ machines disponibles partout au Maroc</div>
        </div>
        <a href="/machines" class="btn-cta"><i class="fas fa-search"></i> Chercher un engin</a>
    </div>`;
}

/* ── Table ── */
function renderReservationsTable(list) {
    if (!list.length) return `
        <div class="empty-state">
            <span class="empty-ico">📋</span>
            <div class="empty-title">Aucune réservation</div>
            <div class="empty-sub">Vous n'avez pas encore effectué de réservation</div>
            <a href="/machines" class="btn-cta" style="display:inline-flex;margin-top:14px">
                <i class="fas fa-search"></i> Parcourir les machines
            </a>
        </div>`;

    const sMap = {
        pending:   {label:'En attente', cls:'sb-pending'},
        accepted:  {label:'Confirmée',  cls:'sb-accepted'},
        rejected:  {label:'Refusée',    cls:'sb-rejected'},
        completed: {label:'Terminée',   cls:'sb-completed'},
        cancelled: {label:'Annulée',    cls:'sb-cancelled'},
    };

    return `<div style="overflow-x:auto">
    <table class="res-table">
        <thead>
            <tr>
                <th>Machine</th><th>Dates</th><th>Total</th><th>Statut</th><th>Actions</th>
            </tr>
        </thead>
        <tbody>
        ${list.map(r => {
            const st = sMap[r.status] || {label:r.status, cls:'sb-default'};
            const phone = r.machine?.owner?.phone;
            const waUrl = phone
                ? `https://wa.me/${phone.replace(/\D/g,'').replace(/^0/,'212')}?text=${encodeURIComponent('Bonjour, ma réservation "'+(r.machine?.name||'')+'" sur Rentify a été acceptée. Comment procéder ?')}`
                : null;
            return `<tr>
                <td>
                    <div style="font-weight:700;color:var(--txt-dark)">${r.machine?.name||'—'}</div>
                    <div style="font-size:11px;color:var(--txt-light)">${r.machine?.type||''} · ${r.machine?.city||''}</div>
                </td>
                <td>
                    <div style="font-size:.82rem">${fDate(r.start_date)}</div>
                    <div style="font-size:.75rem;color:var(--txt-light)">→ ${fDate(r.end_date)}</div>
                </td>
                <td style="font-weight:800;color:var(--gold-dk)">${fMoney(r.total_price)}</td>
                <td><span class="stat-badge ${st.cls}">${st.label}</span></td>
                <td>
                    <div style="display:flex;gap:5px;flex-wrap:wrap">
                        <a href="/machines/${r.machine_id}" class="btn-action btn-voir" title="Voir">
                            <i class="fas fa-eye"></i>
                        </a>
                        ${r.status==='accepted' ? `
                        <button class="btn-action btn-contrat" onclick="downloadContrat(${r.id},this)" title="Contrat PDF">
                            <i class="fas fa-file-pdf"></i> Contrat
                        </button>
                        ${waUrl?`<a href="${waUrl}" target="_blank" class="btn-action btn-wa" title="WhatsApp"><i class="fab fa-whatsapp"></i></a>`:''}
                        ` : ''}
                        ${r.status==='pending' ? `
                        <button class="btn-action btn-cancel" onclick="cancelRes(${r.id})" title="Annuler">
                            <i class="fas fa-times"></i>
                        </button>` : ''}
                    </div>
                </td>
            </tr>`;
        }).join('')}
        </tbody>
    </table></div>`;
}

/* ══════════════════════════════════════
   TAB: FAVORIS
   ══════════════════════════════════════ */
function renderFavoritesTab() {
    const favIds = getFavorites();
    return `
    <div class="dash-header">
        <div style="font-size:22px;font-weight:900;color:var(--txt-dark);letter-spacing:-.4px">❤️ Mes favoris</div>
        <div style="font-size:13px;color:var(--txt);margin-top:3px">
            ${favIds.length} machine${favIds.length>1?'s':''} sauvegardée${favIds.length>1?'s':''}
        </div>
    </div>
    <div class="dash-panel">
        <div class="panel-header">
            <div class="panel-title"><i class="fas fa-heart"></i> Machines sauvegardées</div>
            <div class="panel-badge">${favIds.length} favoris</div>
        </div>
        <div id="favs-content">
            ${favIds.length === 0 ? `
            <div class="empty-state">
                <span class="empty-ico">❤️</span>
                <div class="empty-title">Aucun favori</div>
                <div class="empty-sub">Ajoutez des machines à vos favoris depuis le catalogue</div>
                <a href="/machines" class="btn-cta" style="display:inline-flex;margin-top:14px">
                    <i class="fas fa-search"></i> Parcourir le catalogue
                </a>
            </div>` : `<div class="favs-grid" id="favs-grid"><div style="grid-column:1/-1;text-align:center;padding:24px;color:var(--txt-light)"><i class="fas fa-spinner fa-spin" style="color:var(--gold)"></i> Chargement...</div></div>`}
        </div>
    </div>`;
}

async function loadFavoriteMachines() {
    const favIds = getFavorites();
    if (!favIds.length) return;
    const grid = document.getElementById('favs-grid');
    if (!grid) return;

    const machines = [];
    for (const id of favIds) {
        try {
            const d = await API.get(`/api/machines/${id}`);
            if (d?.id || d?.data?.id) machines.push(d?.data || d);
        } catch(e) {}
    }

    if (!machines.length) {
        grid.innerHTML = `<div style="grid-column:1/-1;text-align:center;padding:24px;color:var(--txt-light)">Impossible de charger les machines</div>`;
        return;
    }

    grid.innerHTML = machines.map(m => `
    <div class="fav-card" id="fav-${m.id}">
        <div class="fav-photo">
            <img src="${getPhoto(m)}" alt="${m.name}" onerror="this.src='/images/img7.png'">
            <button class="fav-remove" onclick="removeFav(${m.id})" title="Retirer des favoris">
                <i class="fas fa-heart-broken"></i>
            </button>
        </div>
        <div class="fav-body">
            <div class="fav-type">${m.type||'—'}</div>
            <div class="fav-name">${m.name}</div>
            <div class="fav-city"><i class="fas fa-map-marker-alt"></i>${m.city||m.location||'—'}</div>
            <div class="fav-price">${Number(m.price_per_day||0).toLocaleString('fr-MA')} <small>DH/jour</small></div>
            <div class="fav-actions">
                <a href="/machines/${m.id}" class="btn-action btn-voir" style="flex:1;justify-content:center">
                    <i class="fas fa-eye"></i> Voir
                </a>
                <a href="/machines/${m.id}" class="btn-action btn-cta" style="flex:1;justify-content:center;font-size:.75rem">
                    <i class="fas fa-calendar-check"></i> Réserver
                </a>
            </div>
        </div>
    </div>`).join('');
}

window.removeFav = function(id) {
    toggleFavorite(id);
    const card = document.getElementById('fav-'+id);
    if (card) { card.style.opacity='0'; card.style.transform='scale(.9)'; setTimeout(()=>card.remove(), 300); }
    toast('Retiré des favoris');
    window.refreshFavsBadge?.();
    const badge = document.querySelector('.panel-badge');
    const count = document.querySelector('.dash-header div:last-child');
    const n = getFavorites().length;
    if (badge) badge.textContent = n + ' favoris';
    if (count) count.textContent = n + ' machine'+(n>1?'s':'')+' sauvegardée'+(n>1?'s':'');
};

/* ══════════════════════════════════════
   TAB: PROFIL
   ══════════════════════════════════════ */
function renderProfileTab() {
    return `
    <div class="dash-header">
        <div style="font-size:22px;font-weight:900;color:var(--txt-dark);letter-spacing:-.4px">👤 Mon profil</div>
        <div style="font-size:13px;color:var(--txt);margin-top:3px">Gérez vos informations personnelles</div>
    </div>
    <div class="dash-panel">
        <div class="panel-header">
            <div class="panel-title"><i class="fas fa-user"></i> Informations personnelles</div>
        </div>
        <div class="profile-wrap">
            <div class="profile-avatar-section">
                <div class="profile-avatar-big">${initial}</div>
                <div>
                    <div style="font-size:1.1rem;font-weight:800;color:var(--txt-dark)">${user.name||'—'}</div>
                    <div style="font-size:.85rem;color:var(--txt-light);margin-top:3px">${user.email||'—'}</div>
                    <div style="margin-top:8px">
                        <span style="background:#FEF9E7;color:var(--gold-dk);font-size:.72rem;font-weight:700;padding:3px 12px;border-radius:100px;">Client Rentify</span>
                    </div>
                </div>
            </div>
            <div class="form-grid">
                <div class="form-group">
                    <label class="form-label">Nom complet</label>
                    <input type="text" class="form-input" id="p-name" value="${user.name||''}">
                </div>
                <div class="form-group">
                    <label class="form-label">Email</label>
                    <input type="email" class="form-input" id="p-email" value="${user.email||''}" disabled
                        style="background:var(--cream);cursor:not-allowed;opacity:.7">
                </div>
                <div class="form-group">
                    <label class="form-label">Téléphone</label>
                    <input type="tel" class="form-input" id="p-phone" value="${user.phone||''}" placeholder="+212 6XX XXX XXX">
                </div>
                <div class="form-group">
                    <label class="form-label">Ville</label>
                    <input type="text" class="form-input" id="p-city" value="${user.city||''}" placeholder="Casablanca">
                </div>
            </div>
            <div class="form-group">
                <label class="form-label">Bio</label>
                <textarea class="form-input" id="p-bio" rows="3" placeholder="Quelques mots sur vous...">${user.bio||''}</textarea>
            </div>
            <button class="btn-save" onclick="saveProfile()">
                <i class="fas fa-save"></i> Sauvegarder les modifications
            </button>
        </div>
    </div>

    <div class="dash-panel" style="margin-top:16px">
        <div class="panel-header">
            <div class="panel-title"><i class="fas fa-lock"></i> Changer le mot de passe</div>
        </div>
        <div class="profile-wrap" style="padding-top:16px">
            <div class="form-grid">
                <div class="form-group">
                    <label class="form-label">Mot de passe actuel</label>
                    <input type="password" class="form-input" id="p-pwd-current" placeholder="••••••••">
                </div>
                <div class="form-group">
                    <label class="form-label">Nouveau mot de passe</label>
                    <input type="password" class="form-input" id="p-pwd-new" placeholder="••••••••">
                </div>
            </div>
            <button class="btn-save" onclick="changePwd()">
                <i class="fas fa-key"></i> Mettre à jour
            </button>
        </div>
    </div>`;
}

/* ══════════════════════════════════════
   ACTIONS
   ══════════════════════════════════════ */
window.applyFilter = function(key, val) {
    if (key==='status') filterStatus=val;
    if (key==='type')   filterType=val;
    if (key==='city')   filterCity=val;
    if (key==='search') filterSearch=val;
    render();
};
window.clearFilters = function() {
    filterStatus=filterType=filterCity=filterSearch='';
    render();
};

window.downloadContrat = async function(id, btn) {
    const orig = btn.innerHTML;
    btn.disabled=true; btn.innerHTML='<i class="fas fa-spinner fa-spin"></i>';
    try {
        const r = await fetch(`/api/reservations/${id}/contrat`, {
            headers:{'Authorization':'Bearer '+getToken(),'Accept':'application/pdf'}
        });
        if (!r.ok) throw new Error((await r.json()).message||'Erreur');
        const blob=await r.blob(), url=URL.createObjectURL(blob);
        const a=document.createElement('a'); a.href=url; a.download=`contrat-rentify-${id}.pdf`;
        document.body.appendChild(a); a.click(); setTimeout(()=>{URL.revokeObjectURL(url);a.remove();},200);
        toast('Contrat téléchargé ✅');
    } catch(e) { toast(e.message||'Erreur', true); }
    finally { btn.disabled=false; btn.innerHTML=orig; }
};

window.cancelRes = async function(id) {
    if (!confirm('Annuler cette réservation ?')) return;
    const r = await API.patch(`/api/reservations/${id}/cancel`);
    if (r.ok) {
        toast('Réservation annulée');
        allReservations = null; /* FIX: force reload */
        render();
    } else toast('Erreur', true);
};

window.saveProfile = async function() {
    const body = {
        name:  document.getElementById('p-name').value,
        phone: document.getElementById('p-phone').value,
        city:  document.getElementById('p-city').value,
        bio:   document.getElementById('p-bio').value,
    };
    const r = await API.put('/api/profile', body);
    if (r.ok) {
        const updated = {...user, ...body};
        localStorage.setItem('auth_user', JSON.stringify(updated));
        toast('Profil mis à jour ✅');
    } else toast(r.data?.message||'Erreur', true);
};

window.changePwd = async function() {
    const body = {
        current_password: document.getElementById('p-pwd-current').value,
        password:         document.getElementById('p-pwd-new').value,
        password_confirmation: document.getElementById('p-pwd-new').value,
    };
    if (!body.current_password || !body.password) { toast('Remplissez les champs', true); return; }
    const r = await API.put('/api/profile/password', body);
    if (r.ok) {
        toast('Mot de passe mis à jour ✅');
        document.getElementById('p-pwd-current').value='';
        document.getElementById('p-pwd-new').value='';
    } else toast(r.data?.message||'Erreur', true);
};

window.doLogout = function() {
    localStorage.removeItem('auth_token');
    localStorage.removeItem('auth_user');
    API.post('/api/logout',{}).finally(()=>window.location.replace('/'));
};

/* ══════════════════════════════════════
   AFTER RENDER
   ══════════════════════════════════════ */
function afterRender() {
    if (activeTab==='favorites') loadFavoriteMachines();
}

/* ══════════════════════════════════════
   INIT
   ══════════════════════════════════════ */
render();

})();
</script>
@endpush