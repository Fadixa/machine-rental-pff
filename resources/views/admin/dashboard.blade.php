@extends('layouts.app')

@section('title', 'Dashboard Admin — Rentify')

@push('styles')
<style>
/* ═══ VARIABLES ═══════════════════════════════════════════════ */
:root {
    --navy:   #0F1B2D;
    --navy2:  #1a2d45;
    --orange: #F59E0B;
    --orange2:#d97706;
    --green:  #10b981;
    --red:    #ef4444;
    --yellow: #f59e0b;
    --blue:   #3b82f6;
    --gray:   #6b7280;
    --light:  #f8fafc;
    --card-bg:#fff;
    --border: #e2e8f0;
    --shadow: 0 2px 12px rgba(15,27,45,.08);
}

body { background: #f1f5f9; }

/* ═══ HEADER ══════════════════════════════════════════════════ */
.admin-header {
    background: linear-gradient(135deg, var(--navy) 0%, var(--navy2) 100%);
    padding: 28px 32px;
    border-bottom: 3px solid var(--orange);
    display: flex; align-items: center; justify-content: space-between;
}
.admin-header h1 { color: #fff; font-size: 1.5rem; font-weight: 700; margin: 0; }
.admin-header h1 span { color: var(--orange); }
.admin-badge {
    background: var(--orange); color: #fff;
    padding: 6px 14px; border-radius: 20px;
    font-size: .8rem; font-weight: 600; letter-spacing: .5px;
}
.admin-meta { color: #94a3b8; font-size: .85rem; margin-top: 4px; }

/* ═══ TABS ════════════════════════════════════════════════════ */
.admin-tabs {
    background: var(--navy);
    padding: 0 24px;
    display: flex; gap: 4px;
    border-bottom: 2px solid var(--navy2);
}
.tab-btn {
    background: none; border: none;
    color: #94a3b8; padding: 16px 20px;
    font-size: .9rem; font-weight: 500; cursor: pointer;
    border-bottom: 3px solid transparent;
    transition: all .2s; white-space: nowrap;
    display: flex; align-items: center; gap: 8px;
}
.tab-btn:hover { color: #fff; }
.tab-btn.active { color: var(--orange); border-bottom-color: var(--orange); }
.tab-btn .tab-count {
    background: var(--orange); color: #fff;
    border-radius: 10px; padding: 1px 7px; font-size: .75rem;
}

/* ═══ CONTAINER ═══════════════════════════════════════════════ */
.admin-body { padding: 28px 32px; max-width: 1400px; margin: 0 auto; }

/* ═══ KPI CARDS ═══════════════════════════════════════════════ */
.kpi-grid { display: grid; grid-template-columns: repeat(4, 1fr); gap: 20px; margin-bottom: 28px; }
.kpi-card {
    background: var(--card-bg); border-radius: 14px;
    padding: 20px 24px; box-shadow: var(--shadow);
    display: flex; align-items: center; gap: 16px;
    border-left: 4px solid var(--orange);
    transition: transform .2s;
}
.kpi-card:hover { transform: translateY(-2px); }
.kpi-card.blue  { border-left-color: var(--blue); }
.kpi-card.green { border-left-color: var(--green); }
.kpi-card.red   { border-left-color: var(--red); }
.kpi-icon {
    width: 52px; height: 52px; border-radius: 12px;
    display: flex; align-items: center; justify-content: center;
    font-size: 1.4rem; flex-shrink: 0;
}
.kpi-icon.orange { background: #fef3c7; color: var(--orange); }
.kpi-icon.blue   { background: #dbeafe; color: var(--blue); }
.kpi-icon.green  { background: #d1fae5; color: var(--green); }
.kpi-icon.red    { background: #fee2e2; color: var(--red); }
.kpi-val { font-size: 2rem; font-weight: 800; color: var(--navy); line-height: 1; }
.kpi-label { font-size: .8rem; color: var(--gray); margin-top: 4px; }
.kpi-sub { font-size: .75rem; color: var(--gray); margin-top: 2px; }

/* ═══ CHARTS ══════════════════════════════════════════════════ */
.charts-grid { display: grid; grid-template-columns: 2fr 1fr; gap: 20px; margin-bottom: 28px; }
.chart-card {
    background: var(--card-bg); border-radius: 14px;
    padding: 20px 24px; box-shadow: var(--shadow);
}
.chart-title { font-weight: 700; color: var(--navy); margin-bottom: 16px; font-size: 1rem; }

/* ═══ TABLE CARD ══════════════════════════════════════════════ */
.table-card {
    background: var(--card-bg); border-radius: 14px;
    box-shadow: var(--shadow); overflow: hidden;
}
.table-header {
    padding: 18px 24px;
    border-bottom: 1px solid var(--border);
    display: flex; align-items: center; justify-content: space-between; gap: 12px;
    flex-wrap: wrap;
}
.table-header h3 { font-weight: 700; color: var(--navy); margin: 0; font-size: 1rem; }
.filter-group { display: flex; gap: 8px; flex-wrap: wrap; }
.filter-group input, .filter-group select {
    border: 1px solid var(--border); border-radius: 8px;
    padding: 7px 12px; font-size: .85rem; color: var(--navy);
    background: var(--light); outline: none;
    transition: border-color .2s;
}
.filter-group input:focus, .filter-group select:focus { border-color: var(--orange); }
.filter-group input { min-width: 200px; }

.data-table { width: 100%; border-collapse: collapse; }
.data-table th {
    background: #f8fafc; color: var(--gray);
    font-size: .75rem; font-weight: 600; text-transform: uppercase;
    letter-spacing: .5px; padding: 10px 16px; text-align: left;
    border-bottom: 1px solid var(--border);
}
.data-table td { padding: 12px 16px; border-bottom: 1px solid #f1f5f9; font-size: .875rem; color: #374151; }
.data-table tr:last-child td { border-bottom: none; }
.data-table tr:hover td { background: #fafafa; }

/* ═══ BADGES ══════════════════════════════════════════════════ */
.badge {
    display: inline-flex; align-items: center; gap: 4px;
    padding: 3px 10px; border-radius: 20px; font-size: .75rem; font-weight: 600;
}
.badge-owner     { background: #dbeafe; color: #1d4ed8; }
.badge-client    { background: #d1fae5; color: #065f46; }
.badge-pending   { background: #fef3c7; color: #92400e; }
.badge-accepted  { background: #d1fae5; color: #065f46; }
.badge-completed { background: #e0e7ff; color: #3730a3; }
.badge-rejected  { background: #fee2e2; color: #991b1b; }
.badge-available { background: #d1fae5; color: #065f46; }
.badge-rented    { background: #fef3c7; color: #92400e; }
.badge-suspended { background: #fee2e2; color: #991b1b; }
.badge-active    { background: #d1fae5; color: #065f46; }

/* ═══ ACTION BTNS ════════════════════════════════════════════ */
.btn-action {
    border: none; border-radius: 7px; padding: 5px 10px;
    cursor: pointer; font-size: .8rem; font-weight: 500;
    transition: opacity .2s; display: inline-flex; align-items: center; gap: 4px;
}
.btn-suspend { background: #fef3c7; color: #92400e; }
.btn-activate { background: #d1fae5; color: #065f46; }
.btn-delete  { background: #fee2e2; color: #991b1b; }
.btn-action:hover { opacity: .8; }

/* ═══ PAGINATION ══════════════════════════════════════════════ */
.pagination-bar {
    padding: 14px 24px;
    border-top: 1px solid var(--border);
    display: flex; align-items: center; justify-content: space-between;
    font-size: .85rem; color: var(--gray);
}
.pagination-btns { display: flex; gap: 6px; }
.page-btn {
    border: 1px solid var(--border); background: #fff; color: var(--navy);
    width: 32px; height: 32px; border-radius: 7px;
    cursor: pointer; font-size: .85rem; font-weight: 500;
    display: flex; align-items: center; justify-content: center;
    transition: all .2s;
}
.page-btn:hover, .page-btn.active { background: var(--orange); color: #fff; border-color: var(--orange); }
.page-btn:disabled { opacity: .4; cursor: not-allowed; }

/* ═══ ACTIVITY FEED ═══════════════════════════════════════════ */
.activity-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 20px; margin-bottom: 28px; }
.activity-item {
    display: flex; align-items: flex-start; gap: 12px;
    padding: 12px 0; border-bottom: 1px solid #f1f5f9;
}
.activity-item:last-child { border-bottom: none; }
.activity-dot {
    width: 10px; height: 10px; border-radius: 50%;
    flex-shrink: 0; margin-top: 5px;
}
.activity-text { font-size: .875rem; color: #374151; }
.activity-time { font-size: .75rem; color: var(--gray); margin-top: 2px; }

/* ═══ EMPTY STATE ═════════════════════════════════════════════ */
.empty-state { padding: 48px; text-align: center; color: var(--gray); }
.empty-state i { font-size: 2.5rem; margin-bottom: 12px; opacity: .3; }
.empty-state p { margin: 0; font-size: .9rem; }

/* ═══ LOADING ════════════════════════════════════════════════ */
.skeleton-row td { background: linear-gradient(90deg, #f0f0f0 25%, #e8e8e8 50%, #f0f0f0 75%); background-size: 200% 100%; animation: shimmer 1.5s infinite; height: 44px; }
@keyframes shimmer { 0%{background-position:200% 0} 100%{background-position:-200% 0} }

/* ═══ TOAST ══════════════════════════════════════════════════ */
#toast {
    position: fixed; bottom: 28px; right: 28px;
    background: var(--navy); color: #fff;
    padding: 12px 20px; border-radius: 10px; font-size: .9rem;
    box-shadow: 0 4px 20px rgba(0,0,0,.3);
    display: none; align-items: center; gap: 10px;
    z-index: 9999; max-width: 320px;
    border-left: 4px solid var(--orange);
}
#toast.show { display: flex; animation: slideUp .3s ease; }
#toast.error { border-left-color: var(--red); }
@keyframes slideUp { from{transform:translateY(20px);opacity:0} to{transform:translateY(0);opacity:1} }

/* ═══ CONFIRM MODAL ══════════════════════════════════════════ */
.modal-overlay {
    position: fixed; inset: 0; background: rgba(0,0,0,.5);
    z-index: 1000; display: none; align-items: center; justify-content: center;
}
.modal-overlay.show { display: flex; }
.modal-box {
    background: #fff; border-radius: 16px; padding: 28px;
    max-width: 400px; width: 90%; box-shadow: 0 20px 60px rgba(0,0,0,.2);
}
.modal-box h4 { margin: 0 0 8px; font-size: 1.1rem; color: var(--navy); }
.modal-box p { margin: 0 0 20px; color: var(--gray); font-size: .9rem; }
.modal-btns { display: flex; gap: 10px; justify-content: flex-end; }
.btn-cancel { border: 1px solid var(--border); background: #fff; color: var(--navy); padding: 8px 18px; border-radius: 8px; cursor: pointer; font-size: .9rem; }
.btn-confirm { background: var(--red); color: #fff; border: none; padding: 8px 18px; border-radius: 8px; cursor: pointer; font-size: .9rem; font-weight: 600; }

/* ═══ RESPONSIVE ══════════════════════════════════════════════ */
@media (max-width: 1024px) {
    .kpi-grid { grid-template-columns: repeat(2, 1fr); }
    .charts-grid { grid-template-columns: 1fr; }
    .activity-grid { grid-template-columns: 1fr; }
}
@media (max-width: 640px) {
    .admin-body { padding: 16px; }
    .kpi-grid { grid-template-columns: 1fr 1fr; gap: 12px; }
    .admin-header { padding: 16px; }
    .admin-tabs { overflow-x: auto; }
}
</style>
@endpush

@section('content')

{{-- ═══ HEADER ═══════════════════════════════════════════════════════ --}}
<div class="admin-header">
    <div>
        <h1><i class="fas fa-shield-alt" style="color:var(--orange)"></i> Dashboard <span>Admin</span></h1>
        <div class="admin-meta">Rentify — Panneau d'administration</div>
    </div>
    <div style="display:flex;align-items:center;gap:12px">
        <span class="admin-badge"><i class="fas fa-circle" style="font-size:.6rem;margin-right:4px"></i> Admin</span>
        <span id="adminName" style="color:#94a3b8;font-size:.9rem"></span>
    </div>
</div>

{{-- ═══ TABS ══════════════════════════════════════════════════════════ --}}
<div class="admin-tabs">
    <button class="tab-btn active" onclick="switchTab('overview')">
        <i class="fas fa-chart-pie"></i> Vue générale
    </button>
    <button class="tab-btn" onclick="switchTab('users')">
        <i class="fas fa-users"></i> Utilisateurs
        <span class="tab-count" id="tabCountUsers">–</span>
    </button>
    <button class="tab-btn" onclick="switchTab('machines')">
        <i class="fas fa-truck"></i> Machines
        <span class="tab-count" id="tabCountMachines">–</span>
    </button>
    <button class="tab-btn" onclick="switchTab('reservations')">
        <i class="fas fa-calendar-check"></i> Réservations
        <span class="tab-count" id="tabCountReservations">–</span>
    </button>
</div>

{{-- ═══ BODY ═══════════════════════════════════════════════════════════ --}}
<div class="admin-body">

    {{-- ─── TAB OVERVIEW ─────────────────────────────────────────── --}}
    <div id="tab-overview">

        {{-- KPIs --}}
        <div class="kpi-grid">
            <div class="kpi-card">
                <div class="kpi-icon orange"><i class="fas fa-users"></i></div>
                <div>
                    <div class="kpi-val" id="kpiUsers">–</div>
                    <div class="kpi-label">Utilisateurs</div>
                    <div class="kpi-sub" id="kpiUsersSub">–</div>
                </div>
            </div>
            <div class="kpi-card blue">
                <div class="kpi-icon blue"><i class="fas fa-truck"></i></div>
                <div>
                    <div class="kpi-val" id="kpiMachines">–</div>
                    <div class="kpi-label">Machines</div>
                    <div class="kpi-sub" id="kpiMachinesSub">–</div>
                </div>
            </div>
            <div class="kpi-card green">
                <div class="kpi-icon green"><i class="fas fa-calendar-check"></i></div>
                <div>
                    <div class="kpi-val" id="kpiReservations">–</div>
                    <div class="kpi-label">Réservations</div>
                    <div class="kpi-sub" id="kpiReservationsSub">–</div>
                </div>
            </div>
            <div class="kpi-card red">
                <div class="kpi-icon red"><i class="fas fa-coins"></i></div>
                <div>
                    <div class="kpi-val" id="kpiRevenue">–</div>
                    <div class="kpi-label">Revenus totaux</div>
                    <div class="kpi-sub">réservations complétées</div>
                </div>
            </div>
        </div>

        {{-- Charts --}}
        <div class="charts-grid">
            <div class="chart-card">
                <div class="chart-title"><i class="fas fa-chart-line" style="color:var(--orange)"></i> Réservations — 12 derniers mois</div>
                <canvas id="chartReservations" height="100"></canvas>
            </div>
            <div class="chart-card">
                <div class="chart-title"><i class="fas fa-chart-donut" style="color:var(--blue)"></i> Statuts réservations</div>
                <canvas id="chartDonut" height="160"></canvas>
            </div>
        </div>

        {{-- Activity + Top Machines --}}
        <div class="activity-grid">
            <div class="table-card">
                <div class="table-header"><h3><i class="fas fa-bolt" style="color:var(--orange)"></i> Dernières réservations</h3></div>
                <div style="padding:0 24px" id="activityFeed"></div>
            </div>
            <div class="table-card">
                <div class="table-header"><h3><i class="fas fa-trophy" style="color:var(--orange)"></i> Top 5 Machines</h3></div>
                <div style="padding:0 24px" id="topMachinesList"></div>
            </div>
        </div>

    </div>{{-- /tab-overview --}}

    {{-- ─── TAB USERS ──────────────────────────────────────────────── --}}
    <div id="tab-users" style="display:none">
        <div class="table-card">
            <div class="table-header">
                <h3><i class="fas fa-users"></i> Gestion des utilisateurs</h3>
                <div class="filter-group">
                    <input type="text" id="searchUsers" placeholder="🔍 Rechercher nom / email…" oninput="debounceUsers()">
                    <select id="filterRole" onchange="loadUsers()">
                        <option value="">Tous les rôles</option>
                        <option value="owner">Propriétaires</option>
                        <option value="client">Clients</option>
                    </select>
                    <select id="filterStatus" onchange="loadUsers()">
                        <option value="">Tous statuts</option>
                        <option value="active">Actifs</option>
                        <option value="suspended">Suspendus</option>
                    </select>
                </div>
            </div>
            <div style="overflow-x:auto">
                <table class="data-table">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Utilisateur</th>
                            <th>Rôle</th>
                            <th>Statut</th>
                            <th>Réservations</th>
                            <th>Machines</th>
                            <th>Inscrit le</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody id="usersTableBody"></tbody>
                </table>
            </div>
            <div class="pagination-bar">
                <span id="usersPaginInfo">–</span>
                <div class="pagination-btns" id="usersPaginBtns"></div>
            </div>
        </div>
    </div>

    {{-- ─── TAB MACHINES ───────────────────────────────────────────── --}}
    <div id="tab-machines" style="display:none">
        <div class="table-card">
            <div class="table-header">
                <h3><i class="fas fa-truck"></i> Gestion des machines</h3>
                <div class="filter-group">
                    <input type="text" id="searchMachines" placeholder="🔍 Machine / ville / catégorie…" oninput="debounceMachines()">
                    <select id="filterMachineStatus" onchange="loadMachines()">
                        <option value="">Tous statuts</option>
                        <option value="available">Disponible</option>
                        <option value="rented">Louée</option>
                        <option value="maintenance">Maintenance</option>
                    </select>
                </div>
            </div>
            <div style="overflow-x:auto">
                <table class="data-table">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Machine</th>
                            <th>Propriétaire</th>
                            <th>Ville</th>
                            <th>Prix/jour</th>
                            <th>Statut</th>
                            <th>Réservations</th>
                            <th>Ajoutée le</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody id="machinesTableBody"></tbody>
                </table>
            </div>
            <div class="pagination-bar">
                <span id="machinesPaginInfo">–</span>
                <div class="pagination-btns" id="machinesPaginBtns"></div>
            </div>
        </div>
    </div>

    {{-- ─── TAB RESERVATIONS ───────────────────────────────────────── --}}
    <div id="tab-reservations" style="display:none">
        <div class="table-card">
            <div class="table-header">
                <h3><i class="fas fa-calendar-check"></i> Toutes les réservations</h3>
                <div class="filter-group">
                    <input type="text" id="searchReservations" placeholder="🔍 Client / machine…" oninput="debounceReservations()">
                    <select id="filterReservStatus" onchange="loadReservations()">
                        <option value="">Tous statuts</option>
                        <option value="pending">En attente</option>
                        <option value="accepted">Acceptées</option>
                        <option value="completed">Complétées</option>
                        <option value="rejected">Refusées</option>
                    </select>
                </div>
            </div>
            <div style="overflow-x:auto">
                <table class="data-table">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Client</th>
                            <th>Machine</th>
                            <th>Ville</th>
                            <th>Du</th>
                            <th>Au</th>
                            <th>Montant</th>
                            <th>Statut</th>
                            <th>Créée le</th>
                        </tr>
                    </thead>
                    <tbody id="reservationsTableBody"></tbody>
                </table>
            </div>
            <div class="pagination-bar">
                <span id="reservPaginInfo">–</span>
                <div class="pagination-btns" id="reservPaginBtns"></div>
            </div>
        </div>
    </div>

</div>{{-- /admin-body --}}

{{-- ═══ TOAST ══════════════════════════════════════════════════════════ --}}
<div id="toast"><i class="fas fa-check-circle"></i> <span id="toastMsg"></span></div>

{{-- ═══ CONFIRM MODAL ══════════════════════════════════════════════════ --}}
<div class="modal-overlay" id="confirmModal">
    <div class="modal-box">
        <h4 id="modalTitle">Confirmer</h4>
        <p id="modalBody">Cette action est irréversible.</p>
        <div class="modal-btns">
            <button class="btn-cancel" onclick="closeModal()">Annuler</button>
            <button class="btn-confirm" id="modalConfirmBtn">Confirmer</button>
        </div>
    </div>
</div>

@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
// ════════════════════════════════════════════════════════
// GUARD : admin seulement
// ════════════════════════════════════════════════════════
const user = getUser();
if (!user || user.role !== 'admin') {
    window.location.replace('/login');
}
document.getElementById('adminName').textContent = user?.name ?? '';

// ════════════════════════════════════════════════════════
// TABS
// ════════════════════════════════════════════════════════
let activeTab = 'overview';
function switchTab(tab) {
    document.querySelectorAll('.tab-btn').forEach((b, i) => {
        const tabs = ['overview','users','machines','reservations'];
        b.classList.toggle('active', tabs[i] === tab);
    });
    ['overview','users','machines','reservations'].forEach(t => {
        document.getElementById('tab-' + t).style.display = (t === tab) ? '' : 'none';
    });
    activeTab = tab;
    if (tab === 'users')        loadUsers();
    if (tab === 'machines')     loadMachines();
    if (tab === 'reservations') loadReservations();
}

// ════════════════════════════════════════════════════════
// TOAST
// ════════════════════════════════════════════════════════
function showToast(msg, isError = false) {
    const t = document.getElementById('toast');
    document.getElementById('toastMsg').textContent = msg;
    t.className = 'show' + (isError ? ' error' : '');
    setTimeout(() => t.className = '', 3500);
}

// ════════════════════════════════════════════════════════
// MODAL
// ════════════════════════════════════════════════════════
let modalCallback = null;
function openModal(title, body, cb) {
    document.getElementById('modalTitle').textContent = title;
    document.getElementById('modalBody').textContent = body;
    document.getElementById('confirmModal').classList.add('show');
    modalCallback = cb;
}
function closeModal() {
    document.getElementById('confirmModal').classList.remove('show');
    modalCallback = null;
}
document.getElementById('modalConfirmBtn').onclick = () => {
    if (modalCallback) modalCallback();
    closeModal();
};

// ════════════════════════════════════════════════════════
// HELPERS
// ════════════════════════════════════════════════════════
function formatDate(d) {
    if (!d) return '–';
    return new Date(d).toLocaleDateString('fr-MA', { day:'2-digit', month:'short', year:'numeric' });
}
function formatMoney(n) {
    return Number(n || 0).toLocaleString('fr-MA') + ' MAD';
}
function calcDays(start, end) {
    if (!start || !end) return 0;
    return Math.max(1, Math.round((new Date(end) - new Date(start)) / 86400000));
}
function skeleton(cols) {
    return Array(5).fill('').map(() =>
        `<tr class="skeleton-row">${Array(cols).fill('<td>&nbsp;</td>').join('')}</tr>`
    ).join('');
}
function paginationHTML(meta, loadFn, paginBtnsId, paginInfoId) {
    const { current_page, last_page, from, to, total } = meta;
    document.getElementById(paginInfoId).textContent =
        `Affichage ${from ?? 0}–${to ?? 0} sur ${total ?? 0}`;
    const btns = document.getElementById(paginBtnsId);
    btns.innerHTML = '';
    const prev = document.createElement('button');
    prev.className = 'page-btn'; prev.innerHTML = '‹'; prev.disabled = current_page <= 1;
    prev.onclick = () => loadFn(current_page - 1); btns.appendChild(prev);
    const pages = [...new Set([1, current_page - 1, current_page, current_page + 1, last_page])]
        .filter(p => p >= 1 && p <= last_page).sort((a,b) => a-b);
    let prev2 = null;
    pages.forEach(p => {
        if (prev2 !== null && p - prev2 > 1) {
            const dots = document.createElement('button');
            dots.className = 'page-btn'; dots.textContent = '…'; dots.disabled = true;
            btns.appendChild(dots);
        }
        const btn = document.createElement('button');
        btn.className = 'page-btn' + (p === current_page ? ' active' : '');
        btn.textContent = p; btn.onclick = () => loadFn(p);
        btns.appendChild(btn); prev2 = p;
    });
    const next = document.createElement('button');
    next.className = 'page-btn'; next.innerHTML = '›'; next.disabled = current_page >= last_page;
    next.onclick = () => loadFn(current_page + 1); btns.appendChild(next);
}

// ════════════════════════════════════════════════════════
// OVERVIEW — Stats + Charts
// ════════════════════════════════════════════════════════
let chartLine = null, chartDonut = null;

async function loadStats() {
    const stats = await API.get('/api/admin/stats');
    if (!stats) return showToast('Erreur chargement stats', true);

    // KPIs
    document.getElementById('kpiUsers').textContent = stats.users.total;
    document.getElementById('kpiUsersSub').textContent =
        `${stats.users.owners} propriétaires · ${stats.users.clients} clients`;
    document.getElementById('tabCountUsers').textContent = stats.users.total;

    document.getElementById('kpiMachines').textContent = stats.machines.total;
    document.getElementById('kpiMachinesSub').textContent =
        `${stats.machines.available} dispo · ${stats.machines.rented} louées`;
    document.getElementById('tabCountMachines').textContent = stats.machines.total;

    document.getElementById('kpiReservations').textContent = stats.reservations.total;
    document.getElementById('kpiReservationsSub').textContent =
        `${stats.reservations.pending} en attente · ${stats.reservations.completed} complétées`;
    document.getElementById('tabCountReservations').textContent = stats.reservations.total;

    document.getElementById('kpiRevenue').textContent = formatMoney(stats.revenue);

    // Chart Line
    const months = ['Jan','Fév','Mar','Avr','Mai','Jun','Jul','Aoû','Sep','Oct','Nov','Déc'];
    const lineData = stats.charts.reservByMonth;
    const labels = lineData.map(r => months[r.month - 1] + ' ' + r.year);
    const vals   = lineData.map(r => r.count);
    if (chartLine) chartLine.destroy();
    chartLine = new Chart(document.getElementById('chartReservations'), {
        type: 'line',
        data: {
            labels,
            datasets: [{
                label: 'Réservations',
                data: vals,
                borderColor: '#F59E0B',
                backgroundColor: 'rgba(245,158,11,.12)',
                borderWidth: 2.5,
                pointBackgroundColor: '#F59E0B',
                fill: true, tension: .4,
            }]
        },
        options: { plugins: { legend: { display: false } }, scales: { y: { beginAtZero: true, grid: { color: '#f1f5f9' } }, x: { grid: { display: false } } } }
    });

    // Chart Donut
    const r = stats.reservations;
    if (chartDonut) chartDonut.destroy();
    chartDonut = new Chart(document.getElementById('chartDonut'), {
        type: 'doughnut',
        data: {
            labels: ['En attente','Acceptées','Complétées','Refusées'],
            datasets: [{
                data: [r.pending, r.accepted, r.completed, r.rejected],
                backgroundColor: ['#fef3c7','#d1fae5','#e0e7ff','#fee2e2'],
                borderColor:     ['#f59e0b','#10b981','#6366f1','#ef4444'],
                borderWidth: 2,
            }]
        },
        options: { cutout: '65%', plugins: { legend: { position: 'bottom', labels: { padding: 12, font: { size: 12 } } } } }
    });

    // Top Machines
    const top = document.getElementById('topMachinesList');
    top.innerHTML = stats.charts.topMachines.map((m, i) => `
        <div class="activity-item">
            <div style="width:24px;height:24px;border-radius:50%;background:${['#fef3c7','#dbeafe','#d1fae5','#f3e8ff','#fee2e2'][i]};color:${['#92400e','#1d4ed8','#065f46','#6b21a8','#991b1b'][i]};display:flex;align-items:center;justify-content:center;font-size:.75rem;font-weight:700;flex-shrink:0">${i+1}</div>
            <div>
                <div class="activity-text">${m.name}</div>
                <div class="activity-time">${m.city} · ${m.reservations_count} réservation(s) · ${formatMoney(m.daily_price)}/j</div>
            </div>
        </div>`).join('') || '<div class="empty-state"><i class="fas fa-truck"></i><p>Aucune machine</p></div>';

    // Activity Feed
    const act = await API.get('/api/admin/activity');
    const feed = document.getElementById('activityFeed');
    feed.innerHTML = (act?.reservations || []).map(r => `
        <div class="activity-item">
            <div class="activity-dot" style="background:${{pending:'#f59e0b',accepted:'#10b981',completed:'#6366f1',rejected:'#ef4444'}[r.status]||'#94a3b8'}"></div>
            <div>
                <div class="activity-text">${r.client?.name ?? '–'} → <strong>${r.machine?.name ?? '–'}</strong></div>
                <div class="activity-time">${formatDate(r.created_at)} · <span class="badge badge-${r.status}">${r.status}</span></div>
            </div>
        </div>`).join('') || '<div class="empty-state"><i class="fas fa-calendar"></i><p>Aucune activité</p></div>';
}

// ════════════════════════════════════════════════════════
// USERS
// ════════════════════════════════════════════════════════
let usersPage = 1;
let usersDebounce;
function debounceUsers() { clearTimeout(usersDebounce); usersDebounce = setTimeout(loadUsers, 400); }

async function loadUsers(page = 1) {
    usersPage = page;
    const tb = document.getElementById('usersTableBody');
    tb.innerHTML = skeleton(8);
    const search = document.getElementById('searchUsers').value;
    const role   = document.getElementById('filterRole').value;
    const status = document.getElementById('filterStatus').value;
    const params = new URLSearchParams({ page, ...(search && {search}), ...(role && {role}), ...(status && {status}) });
    const data = await API.get('/api/admin/users?' + params);
    if (!data?.data) { tb.innerHTML = `<tr><td colspan="8"><div class="empty-state"><i class="fas fa-exclamation"></i><p>Erreur de chargement</p></div></td></tr>`; return; }

    tb.innerHTML = data.data.length ? data.data.map(u => `
        <tr>
            <td style="color:var(--gray);font-size:.8rem">#${u.id}</td>
            <td>
                <div style="font-weight:600;color:var(--navy)">${u.name}</div>
                <div style="font-size:.78rem;color:var(--gray)">${u.email}</div>
            </td>
            <td><span class="badge badge-${u.role}">${u.role === 'owner' ? 'Propriétaire' : 'Client'}</span></td>
            <td><span class="badge ${u.is_suspended ? 'badge-suspended' : 'badge-active'}">${u.is_suspended ? 'Suspendu' : 'Actif'}</span></td>
            <td style="text-align:center">${u.reservations_count ?? 0}</td>
            <td style="text-align:center">${u.machines_count ?? 0}</td>
            <td style="font-size:.82rem;color:var(--gray)">${formatDate(u.created_at)}</td>
            <td>
                <div style="display:flex;gap:6px">
                    ${u.is_suspended
                        ? `<button class="btn-action btn-activate" onclick="activateUser(${u.id},'${u.name}')"><i class="fas fa-check"></i></button>`
                        : `<button class="btn-action btn-suspend" onclick="suspendUser(${u.id},'${u.name}')"><i class="fas fa-ban"></i></button>`
                    }
                    <button class="btn-action btn-delete" onclick="deleteUser(${u.id},'${u.name}')"><i class="fas fa-trash"></i></button>
                </div>
            </td>
        </tr>`).join('')
    : `<tr><td colspan="8"><div class="empty-state"><i class="fas fa-users"></i><p>Aucun utilisateur trouvé</p></div></td></tr>`;

    paginationHTML(data, loadUsers, 'usersPaginBtns', 'usersPaginInfo');
}

async function suspendUser(id, name) {
    openModal('Suspendre l\'utilisateur', `Voulez-vous vraiment suspendre ${name} ?`, async () => {
        const r = await API.post(`/api/admin/users/${id}/suspend`, {}, 'PUT');
        if (r.ok) { showToast(r.data.message); loadUsers(usersPage); }
        else showToast('Erreur', true);
    });
}
async function activateUser(id, name) {
    const res = await fetch(`/api/admin/users/${id}/activate`, { method:'PUT', headers:{'Authorization':'Bearer '+getToken(),'Accept':'application/json'} });
    const d = await res.json();
    showToast(d.message || (res.ok ? 'Réactivé' : 'Erreur'), !res.ok);
    loadUsers(usersPage);
}
async function deleteUser(id, name) {
    openModal('Supprimer l\'utilisateur', `Supprimer définitivement ${name} ? Cette action est irréversible.`, async () => {
        const res = await fetch(`/api/admin/users/${id}`, { method:'DELETE', headers:{'Authorization':'Bearer '+getToken(),'Accept':'application/json'} });
        const d = await res.json();
        showToast(d.message || (res.ok ? 'Supprimé' : 'Erreur'), !res.ok);
        loadUsers(usersPage);
    });
}

// ════════════════════════════════════════════════════════
// MACHINES
// ════════════════════════════════════════════════════════
let machinesPage = 1;
let machinesDebounce;
function debounceMachines() { clearTimeout(machinesDebounce); machinesDebounce = setTimeout(loadMachines, 400); }

async function loadMachines(page = 1) {
    machinesPage = page;
    const tb = document.getElementById('machinesTableBody');
    tb.innerHTML = skeleton(9);
    const search = document.getElementById('searchMachines').value;
    const status = document.getElementById('filterMachineStatus').value;
    const params = new URLSearchParams({ page, ...(search && {search}), ...(status && {status}) });
    const data = await API.get('/api/admin/machines?' + params);
    if (!data?.data) { tb.innerHTML = `<tr><td colspan="9"><div class="empty-state"><p>Erreur de chargement</p></div></td></tr>`; return; }

    tb.innerHTML = data.data.length ? data.data.map(m => `
        <tr>
            <td style="color:var(--gray);font-size:.8rem">#${m.id}</td>
            <td>
                <div style="font-weight:600;color:var(--navy)">${m.name}</div>
                <div style="font-size:.78rem;color:var(--gray)">${m.category ?? '–'}</div>
            </td>
            <td>
                <div style="font-size:.85rem">${m.owner?.name ?? '–'}</div>
                <div style="font-size:.75rem;color:var(--gray)">${m.owner?.email ?? ''}</div>
            </td>
            <td>${m.city ?? '–'}</td>
            <td style="font-weight:600;color:var(--orange)">${formatMoney(m.daily_price)}</td>
            <td><span class="badge badge-${m.status ?? 'available'}">${m.status ?? '–'}</span></td>
            <td style="text-align:center">${m.reservations_count ?? 0}</td>
            <td style="font-size:.82rem;color:var(--gray)">${formatDate(m.created_at)}</td>
            <td>
                <button class="btn-action btn-delete" onclick="deleteMachine(${m.id},'${m.name}')">
                    <i class="fas fa-trash"></i> Supprimer
                </button>
            </td>
        </tr>`).join('')
    : `<tr><td colspan="9"><div class="empty-state"><i class="fas fa-truck"></i><p>Aucune machine trouvée</p></div></td></tr>`;

    paginationHTML(data, loadMachines, 'machinesPaginBtns', 'machinesPaginInfo');
}

async function deleteMachine(id, name) {
    openModal('Supprimer la machine', `Supprimer définitivement "${name}" ? Cette action est irréversible.`, async () => {
        const res = await fetch(`/api/admin/machines/${id}`, { method:'DELETE', headers:{'Authorization':'Bearer '+getToken(),'Accept':'application/json'} });
        const d = await res.json();
        showToast(d.message || (res.ok ? 'Supprimé' : 'Erreur'), !res.ok);
        loadMachines(machinesPage);
    });
}

// ════════════════════════════════════════════════════════
// RESERVATIONS
// ════════════════════════════════════════════════════════
let reservPage = 1;
let reservDebounce;
function debounceReservations() { clearTimeout(reservDebounce); reservDebounce = setTimeout(loadReservations, 400); }

async function loadReservations(page = 1) {
    reservPage = page;
    const tb = document.getElementById('reservationsTableBody');
    tb.innerHTML = skeleton(9);
    const search = document.getElementById('searchReservations').value;
    const status = document.getElementById('filterReservStatus').value;
    const params = new URLSearchParams({ page, ...(search && {search}), ...(status && {status}) });
    const data = await API.get('/api/admin/reservations?' + params);
    if (!data?.data) { tb.innerHTML = `<tr><td colspan="9"><div class="empty-state"><p>Erreur</p></div></td></tr>`; return; }

    tb.innerHTML = data.data.length ? data.data.map(r => {
        const days = calcDays(r.start_date, r.end_date);
        const amount = days * (r.machine?.daily_price || 0);
        return `
        <tr>
            <td style="color:var(--gray);font-size:.8rem">#${r.id}</td>
            <td>
                <div style="font-weight:600">${r.client?.name ?? '–'}</div>
                <div style="font-size:.75rem;color:var(--gray)">${r.client?.email ?? ''}</div>
            </td>
            <td>
                <div style="font-weight:600;color:var(--navy)">${r.machine?.name ?? '–'}</div>
            </td>
            <td style="font-size:.85rem">${r.machine?.city ?? '–'}</td>
            <td style="font-size:.85rem">${formatDate(r.start_date)}</td>
            <td style="font-size:.85rem">${formatDate(r.end_date)}</td>
            <td style="font-weight:600;color:var(--orange)">${formatMoney(amount)}</td>
            <td><span class="badge badge-${r.status}">${{pending:'En attente',accepted:'Acceptée',completed:'Complétée',rejected:'Refusée'}[r.status]||r.status}</span></td>
            <td style="font-size:.82rem;color:var(--gray)">${formatDate(r.created_at)}</td>
        </tr>`;
    }).join('')
    : `<tr><td colspan="9"><div class="empty-state"><i class="fas fa-calendar"></i><p>Aucune réservation trouvée</p></div></td></tr>`;

    paginationHTML(data, loadReservations, 'reservPaginBtns', 'reservPaginInfo');
}

// ════════════════════════════════════════════════════════
// INIT
// ════════════════════════════════════════════════════════
loadStats();
</script>
@endpush