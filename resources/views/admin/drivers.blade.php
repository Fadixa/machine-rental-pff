{{-- resources/views/admin/drivers.blade.php --}}
@extends('layouts.app')

@section('title', 'Gestion Chauffeurs — Admin Rentify')

@push('styles')
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css"/>
<style>
  :root { --navy: #0F1B2D; --orange: #F59E0B; }

  .page-header {
    background: linear-gradient(135deg, var(--navy) 0%, #1a3050 100%);
    color: #fff; padding: 1.8rem 0; margin-bottom: 2rem;
  }
  .kpi-card {
    background:#fff; border-radius:12px; padding:1.2rem 1.5rem;
    box-shadow:0 2px 10px rgba(0,0,0,.07);
    display:flex; align-items:center; gap:1rem;
  }
  .kpi-icon { font-size:2rem; width:46px; text-align:center; }
  .kpi-val  { font-size:1.7rem; font-weight:700; color:var(--navy); line-height:1; }
  .kpi-lbl  { font-size:.75rem; color:#64748b; text-transform:uppercase; letter-spacing:.05em; }

  /* Tabs */
  .nav-tabs .nav-link { color:#64748b; font-weight:500; border:none; padding:.6rem 1.2rem; }
  .nav-tabs .nav-link.active {
    color:var(--navy); font-weight:700;
    border-bottom:3px solid var(--orange); background:transparent;
  }

  /* Table */
  .driver-avatar {
    width:38px; height:38px; border-radius:50%; background:var(--orange);
    display:flex; align-items:center; justify-content:center;
    color:#fff; font-weight:700; font-size:.9rem; flex-shrink:0;
  }
  .statut-badge {
    display:inline-flex; align-items:center; gap:.3rem;
    font-size:.75rem; font-weight:600; padding:.25rem .65rem;
    border-radius:20px; border:1.5px solid;
  }
  .sb-disponible   { color:#16a34a; border-color:#16a34a; background:#f0fdf4; }
  .sb-en_mission   { color:#d97706; border-color:#d97706; background:#fffbeb; }
  .sb-indisponible { color:#6b7280; border-color:#6b7280; background:#f9fafb; }
  .sb-conge        { color:#0891b2; border-color:#0891b2; background:#ecfeff; }

  .sb-assignee  { color:#7c3aed; border-color:#7c3aed; background:#f5f3ff; }
  .sb-en_route  { color:#d97706; border-color:#d97706; background:#fffbeb; }
  .sb-sur_place { color:#059669; border-color:#059669; background:#ecfdf5; }
  .sb-en_cours  { color:#2563eb; border-color:#2563eb; background:#eff6ff; }
  .sb-terminee  { color:#16a34a; border-color:#16a34a; background:#f0fdf4; }
  .sb-annulee   { color:#dc2626; border-color:#dc2626; background:#fef2f2; }

  /* Map */
  #map-global { height:460px; border-radius:12px; overflow:hidden; }

  /* Planning */
  .planning-grid {
    display:grid; grid-template-columns:80px repeat(7,1fr);
    gap:2px; font-size:.78rem;
  }
  .pg-header { background:var(--navy); color:#fff; padding:.4rem; text-align:center; border-radius:4px; font-weight:600; }
  .pg-time   { background:#f8fafc; padding:.4rem; text-align:center; color:#64748b; font-weight:500; border-radius:4px; }
  .pg-cell   { background:#f8fafc; border-radius:4px; min-height:36px; padding:.2rem; }
  .pg-mission{
    background:var(--orange); color:#fff; border-radius:4px;
    padding:.2rem .4rem; font-size:.7rem; font-weight:600;
    white-space:nowrap; overflow:hidden; text-overflow:ellipsis;
    margin-bottom:2px; cursor:pointer;
  }
  .pg-mission.terminee { background:#22c55e; }
  .pg-mission.annulee  { background:#ef4444; }
  .pg-mission.assignee { background:#7c3aed; }

  /* Formulaire */
  .form-section { background:#f8fafc; border-radius:10px; padding:1.2rem; margin-bottom:1rem; }
  .form-section h6 { color:var(--navy); font-weight:700; margin-bottom:.8rem; }

  .btn-orange { background:var(--orange); color:#fff; border:none; font-weight:600; }
  .btn-orange:hover { background:#d97706; color:#fff; }
  .btn-navy   { background:var(--navy);  color:#fff; border:none; font-weight:600; }
  .btn-navy:hover   { background:#1a3050; color:#fff; }

  tr.clickable { cursor:pointer; }
  tr.clickable:hover td { background:#f8fafc; }
</style>
@endpush

@section('content')

<!-- ════ HEADER ══════════════════════════════════════════════════════ -->
<div class="page-header">
  <div class="container d-flex justify-content-between align-items-center flex-wrap gap-3">
    <div>
      <h3 class="mb-0 fw-bold">🚗 Gestion des Chauffeurs</h3>
      <small class="opacity-75">Missions · Planning · Géolocalisation temps réel</small>
    </div>
    <button class="btn btn-action btn-orange px-4" onclick="openModalAddDriver()">
      <i class="fas fa-plus me-2"></i>Ajouter un chauffeur
    </button>
  </div>
</div>

<div class="container pb-5">

  <!-- ════ KPI ════════════════════════════════════════════════════ -->
  <div class="row g-3 mb-4">
    <div class="col-6 col-md-3">
      <div class="kpi-card">
        <div class="kpi-icon">🚗</div>
        <div><div class="kpi-val" id="kpi-total">—</div><div class="kpi-lbl">Chauffeurs actifs</div></div>
      </div>
    </div>
    <div class="col-6 col-md-3">
      <div class="kpi-card">
        <div class="kpi-icon">✅</div>
        <div><div class="kpi-val" id="kpi-dispo">—</div><div class="kpi-lbl">Disponibles</div></div>
      </div>
    </div>
    <div class="col-6 col-md-3">
      <div class="kpi-card">
        <div class="kpi-icon">⚡</div>
        <div><div class="kpi-val" id="kpi-mission">—</div><div class="kpi-lbl">En mission</div></div>
      </div>
    </div>
    <div class="col-6 col-md-3">
      <div class="kpi-card">
        <div class="kpi-icon">📋</div>
        <div><div class="kpi-val" id="kpi-today">—</div><div class="kpi-lbl">Missions aujourd'hui</div></div>
      </div>
    </div>
  </div>

  <!-- ════ TABS ════════════════════════════════════════════════════ -->
  <ul class="nav nav-tabs mb-4" id="adminTabs">
    <li class="nav-item"><a class="nav-link active" data-bs-toggle="tab" href="#tab-chauffeurs">👥 Chauffeurs</a></li>
    <li class="nav-item"><a class="nav-link" data-bs-toggle="tab" href="#tab-missions" onclick="loadMissions()">📋 Missions</a></li>
    <li class="nav-item"><a class="nav-link" data-bs-toggle="tab" href="#tab-planning" onclick="loadPlanning()">📅 Planning</a></li>
    <li class="nav-item"><a class="nav-link" data-bs-toggle="tab" href="#tab-carte" onclick="setTimeout(initMap,200)">🗺️ Carte temps réel</a></li>
  </ul>

  <div class="tab-content">

    <!-- ── TAB CHAUFFEURS ─────────────────────────────────────── -->
    <div class="tab-pane fade show active" id="tab-chauffeurs">
      <div class="card border-0 shadow-sm">
        <div class="card-header bg-white border-0 py-3 d-flex justify-content-between align-items-center">
          <h6 class="mb-0 fw-bold">Liste des chauffeurs</h6>
          <div class="d-flex gap-2">
            <select class="form-select form-select-sm" id="filter-statut-d" onchange="loadDrivers()" style="width:150px">
              <option value="">Tous</option>
              <option value="disponible">Disponible</option>
              <option value="en_mission">En mission</option>
              <option value="indisponible">Indisponible</option>
              <option value="conge">Congé</option>
            </select>
          </div>
        </div>
        <div class="card-body p-0">
          <div class="table-responsive">
            <table class="table align-middle mb-0">
              <thead class="table-light">
                <tr>
                  <th class="ps-3">Chauffeur</th>
                  <th>Permis</th>
                  <th>Statut</th>
                  <th>Missions</th>
                  <th>Note</th>
                  <th>Dernière position</th>
                  <th>Actions</th>
                </tr>
              </thead>
              <tbody id="drivers-tbody">
                <tr><td colspan="7" class="text-center py-4 text-muted">
                  <div class="spinner-border spinner-border-sm me-2"></div>Chargement…
                </td></tr>
              </tbody>
            </table>
          </div>
        </div>
      </div>
    </div>

    <!-- ── TAB MISSIONS ───────────────────────────────────────── -->
    <div class="tab-pane fade" id="tab-missions">
      <div class="d-flex justify-content-between align-items-center mb-3 flex-wrap gap-2">
        <h6 class="mb-0 fw-bold">Toutes les missions</h6>
        <div class="d-flex gap-2 flex-wrap">
          <select class="form-select form-select-sm" id="filter-statut-m" onchange="loadMissions()" style="width:150px">
            <option value="">Tous statuts</option>
            <option value="assignee">Assignée</option>
            <option value="en_route">En route</option>
            <option value="sur_place">Sur place</option>
            <option value="en_cours">En cours</option>
            <option value="terminee">Terminée</option>
            <option value="annulee">Annulée</option>
          </select>
          <select class="form-select form-select-sm" id="filter-today-m" onchange="loadMissions()" style="width:150px">
            <option value="">Toutes dates</option>
            <option value="1">Aujourd'hui</option>
          </select>
          <button class="btn btn-sm btn-orange" onclick="openModalAssign()">
            <i class="fas fa-plus me-1"></i>Assigner mission
          </button>
        </div>
      </div>
      <div class="card border-0 shadow-sm">
        <div class="card-body p-0">
          <div class="table-responsive">
            <table class="table align-middle mb-0">
              <thead class="table-light">
                <tr>
                  <th class="ps-3">#</th>
                  <th>Chauffeur</th>
                  <th>Machine / Client</th>
                  <th>Départ prévu</th>
                  <th>Destination</th>
                  <th>Statut</th>
                  <th>Note</th>
                  <th></th>
                </tr>
              </thead>
              <tbody id="missions-tbody">
                <tr><td colspan="8" class="text-center py-4 text-muted">Cliquez sur l'onglet pour charger</td></tr>
              </tbody>
            </table>
          </div>
        </div>
      </div>
    </div>

    <!-- ── TAB PLANNING ───────────────────────────────────────── -->
    <div class="tab-pane fade" id="tab-planning">
      <div class="d-flex justify-content-between align-items-center mb-3 flex-wrap gap-2">
        <h6 class="mb-0 fw-bold">Planning hebdomadaire</h6>
        <div class="d-flex gap-2 align-items-center">
          <button class="btn btn-sm btn-outline-secondary" onclick="shiftWeek(-1)">◀ Préc.</button>
          <span class="fw-semibold small" id="week-label">Semaine en cours</span>
          <button class="btn btn-sm btn-outline-secondary" onclick="shiftWeek(1)">Suiv. ▶</button>
        </div>
      </div>
      <div class="card border-0 shadow-sm">
        <div class="card-body p-0 p-md-3" id="planning-container">
          <div class="text-center py-5 text-muted">
            <div class="spinner-border spinner-border-sm me-2"></div>Chargement du planning…
          </div>
        </div>
      </div>
    </div>

    <!-- ── TAB CARTE ──────────────────────────────────────────── -->
    <div class="tab-pane fade" id="tab-carte">
      <div class="row g-3">
        <div class="col-lg-8">
          <div class="card border-0 shadow-sm">
            <div class="card-header bg-white border-0 py-3 d-flex justify-content-between">
              <h6 class="mb-0 fw-bold">🗺️ Positions en temps réel</h6>
              <button class="btn btn-sm btn-outline-secondary" onclick="refreshMap()">
                <i class="fas fa-sync-alt me-1"></i>Actualiser
              </button>
            </div>
            <div class="card-body p-2">
              <div id="map-global"></div>
              <div class="d-flex gap-3 mt-2 flex-wrap" style="font-size:.8rem">
                <span>🟢 Disponible</span>
                <span>🟠 En mission</span>
                <span>⚫ Indisponible</span>
                <span>🔵 Destination mission</span>
              </div>
            </div>
          </div>
        </div>
        <div class="col-lg-4">
          <div class="card border-0 shadow-sm h-100">
            <div class="card-header bg-white border-0 py-3">
              <h6 class="mb-0 fw-bold">Missions actives</h6>
            </div>
            <div class="card-body p-0" id="active-missions-list" style="overflow-y:auto;max-height:420px">
              <div class="text-center py-5 text-muted small">Actualisez la carte pour voir les missions</div>
            </div>
          </div>
        </div>
      </div>
    </div>

  </div><!-- /tab-content -->
</div>

<!-- ══ MODAL AJOUTER CHAUFFEUR ════════════════════════════════════ -->
<div class="modal fade" id="modalAddDriver" tabindex="-1">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header" style="background:var(--navy);color:#fff">
        <h6 class="modal-title fw-bold">➕ Ajouter un chauffeur</h6>
        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
      </div>
      <div class="modal-body">
        <div class="form-section">
          <h6>👤 Informations du compte</h6>
          <div class="row g-3">
            <div class="col-md-6">
              <label class="form-label small">Utilisateur existant (ID)</label>
              <input type="number" class="form-control form-control-sm" id="d-user-id" placeholder="ID de l'utilisateur">
            </div>
            <div class="col-md-6">
              <label class="form-label small">Téléphone</label>
              <input type="text" class="form-control form-control-sm" id="d-telephone" placeholder="+212 6XX XXX XXX">
            </div>
          </div>
        </div>
        <div class="form-section">
          <h6>🪪 Permis & Compétences</h6>
          <div class="row g-3">
            <div class="col-md-4">
              <label class="form-label small">Numéro de permis</label>
              <input type="text" class="form-control form-control-sm" id="d-permis" placeholder="Ex: MA-123456">
            </div>
            <div class="col-md-4">
              <label class="form-label small">Catégorie</label>
              <select class="form-select form-select-sm" id="d-categorie">
                <option value="C">C — Poids lourd</option>
                <option value="B">B — Voiture</option>
                <option value="D">D — Bus</option>
                <option value="E">E — Remorque</option>
                <option value="F">F — Engins spéciaux</option>
              </select>
            </div>
            <div class="col-md-4">
              <label class="form-label small">Compétences machines</label>
              <select class="form-select form-select-sm" id="d-competences" multiple>
                <option value="pelle">Pelle hydraulique</option>
                <option value="bulldozer">Bulldozer</option>
                <option value="grue">Grue</option>
                <option value="camion">Camion benne</option>
                <option value="compacteur">Compacteur</option>
                <option value="chargeuse">Chargeuse</option>
              </select>
            </div>
          </div>
        </div>
      </div>
      <div class="modal-footer">
        <button class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
        <button class="btn btn-orange" onclick="saveDriver()">💾 Enregistrer</button>
      </div>
    </div>
  </div>
</div>

<!-- ══ MODAL ASSIGNER MISSION ══════════════════════════════════════ -->
<div class="modal fade" id="modalAssign" tabindex="-1">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header" style="background:var(--navy);color:#fff">
        <h6 class="modal-title fw-bold">🚗 Assigner une mission</h6>
        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
      </div>
      <div class="modal-body">
        <div class="form-section">
          <h6>🔗 Liaison</h6>
          <div class="row g-3">
            <div class="col-md-6">
              <label class="form-label small">Chauffeur *</label>
              <select class="form-select form-select-sm" id="m-driver-id">
                <option value="">Sélectionner un chauffeur disponible…</option>
              </select>
            </div>
            <div class="col-md-6">
              <label class="form-label small">Réservation *</label>
              <input type="number" class="form-control form-control-sm" id="m-reservation-id" placeholder="ID de la réservation">
            </div>
          </div>
        </div>
        <div class="form-section">
          <h6>📍 Localisation</h6>
          <div class="row g-3">
            <div class="col-md-6">
              <label class="form-label small">Adresse de départ</label>
              <input type="text" class="form-control form-control-sm" id="m-adr-dep" placeholder="Dépôt / Entrepôt">
            </div>
            <div class="col-md-3">
              <label class="form-label small">Lat départ</label>
              <input type="number" step="0.000001" class="form-control form-control-sm" id="m-lat-dep">
            </div>
            <div class="col-md-3">
              <label class="form-label small">Lng départ</label>
              <input type="number" step="0.000001" class="form-control form-control-sm" id="m-lng-dep">
            </div>
            <div class="col-md-6">
              <label class="form-label small">Adresse destination *</label>
              <input type="text" class="form-control form-control-sm" id="m-adr-dst" placeholder="Chantier client">
            </div>
            <div class="col-md-3">
              <label class="form-label small">Lat destination</label>
              <input type="number" step="0.000001" class="form-control form-control-sm" id="m-lat-dst">
            </div>
            <div class="col-md-3">
              <label class="form-label small">Lng destination</label>
              <input type="number" step="0.000001" class="form-control form-control-sm" id="m-lng-dst">
            </div>
          </div>
        </div>
        <div class="form-section">
          <h6>📅 Planning</h6>
          <div class="row g-3">
            <div class="col-md-4">
              <label class="form-label small">Départ prévu *</label>
              <input type="datetime-local" class="form-control form-control-sm" id="m-depart-prevu">
            </div>
            <div class="col-md-4">
              <label class="form-label small">Arrivée prévue</label>
              <input type="datetime-local" class="form-control form-control-sm" id="m-arrivee-prevue">
            </div>
            <div class="col-md-2">
              <label class="form-label small">Distance (km)</label>
              <input type="number" step="0.1" class="form-control form-control-sm" id="m-distance">
            </div>
            <div class="col-md-2">
              <label class="form-label small">Durée (min)</label>
              <input type="number" class="form-control form-control-sm" id="m-duree">
            </div>
          </div>
        </div>
        <div class="form-section">
          <h6>📝 Instructions</h6>
          <textarea class="form-control form-control-sm" id="m-instructions" rows="3"
            placeholder="Consignes spécifiques pour le chauffeur…"></textarea>
        </div>
      </div>
      <div class="modal-footer">
        <button class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
        <button class="btn btn-orange" onclick="saveMission()">🚀 Assigner la mission</button>
      </div>
    </div>
  </div>
</div>

<!-- ══ MODAL DÉTAIL CHAUFFEUR ═════════════════════════════════════ -->
<div class="modal fade" id="modalDriverDetail" tabindex="-1">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header" style="background:var(--navy);color:#fff">
        <h6 class="modal-title fw-bold" id="detail-title">Fiche chauffeur</h6>
        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
      </div>
      <div class="modal-body" id="detail-body">
        <div class="text-center py-4"><div class="spinner-border"></div></div>
      </div>
    </div>
  </div>
</div>

@endsection

@push('scripts')
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
<script>
/* ══════════════════════════════════════════════════════════════════
   ADMIN — Gestion Chauffeurs & Missions — Rentify
══════════════════════════════════════════════════════════════════ */
let mapGlobal    = null;
let allDrivers   = [];
let weekOffset   = 0;

document.addEventListener('DOMContentLoaded', () => {
  loadDrivers();
  loadKpis();
});

// ── KPIs ──────────────────────────────────────────────────────────
async function loadKpis() {
  try {
    const [drivers, missions] = await Promise.all([
      API.get('/drivers'),
      API.get('/missions?today=1'),
    ]);
    const dList = Array.isArray(drivers) ? drivers : [];
    const mList = (missions?.data || (Array.isArray(missions) ? missions : []));

    document.getElementById('kpi-total').textContent   = dList.filter(d => d.actif).length;
    document.getElementById('kpi-dispo').textContent   = dList.filter(d => d.statut === 'disponible').length;
    document.getElementById('kpi-mission').textContent = dList.filter(d => d.statut === 'en_mission').length;
    document.getElementById('kpi-today').textContent   = mList.length;
  } catch(e) { console.error(e); }
}

// ── Chauffeurs ────────────────────────────────────────────────────
async function loadDrivers() {
  const statut = document.getElementById('filter-statut-d').value;
  const tbody  = document.getElementById('drivers-tbody');
  tbody.innerHTML = '<tr><td colspan="7" class="text-center py-4"><div class="spinner-border spinner-border-sm"></div></td></tr>';

  try {
    const data = await API.get(`/drivers${statut ? '?statut=' + statut : ''}`);
    allDrivers = Array.isArray(data) ? data : [];

    if (!allDrivers.length) {
      tbody.innerHTML = '<tr><td colspan="7" class="text-center py-5 text-muted">Aucun chauffeur trouvé</td></tr>';
      return;
    }

    tbody.innerHTML = allDrivers.map(d => `
      <tr class="clickable" onclick="showDriverDetail(${d.id})">
        <td class="ps-3">
          <div class="d-flex align-items-center gap-2">
            <div class="driver-avatar">${d.nom?.charAt(0)?.toUpperCase() || '?'}</div>
            <div>
              <div class="fw-semibold small">${d.nom}</div>
              <div class="text-muted" style="font-size:.75rem">${d.email}</div>
            </div>
          </div>
        </td>
        <td>
          <div class="small fw-semibold">${d.categorie_permis}</div>
          <div class="text-muted" style="font-size:.72rem">${d.numero_permis}</div>
        </td>
        <td><span class="statut-badge sb-${d.statut}">${iconStatut(d.statut)} ${d.statut.replace('_',' ')}</span></td>
        <td class="text-center fw-bold">${d.missions_completees}</td>
        <td>${d.note_moyenne > 0 ? '⭐ ' + d.note_moyenne : '<span class="text-muted">—</span>'}</td>
        <td class="text-muted small">${d.derniere_position_at || '—'}</td>
        <td onclick="event.stopPropagation()">
          <div class="d-flex gap-1">
            <button class="btn btn-sm btn-outline-secondary btn-sm py-0 px-2" title="Modifier statut"
              onclick="changeDriverStatut(${d.id}, '${d.statut}')">✏️</button>
            <button class="btn btn-sm btn-outline-danger py-0 px-2" title="Désactiver"
              onclick="desactiverDriver(${d.id})">🗑</button>
          </div>
        </td>
      </tr>
    `).join('');
  } catch(e) {
    tbody.innerHTML = '<tr><td colspan="7" class="text-center text-danger py-3">Erreur de chargement</td></tr>';
  }
}

function iconStatut(s) {
  return { disponible:'🟢', en_mission:'🟠', indisponible:'⚫', conge:'🔵' }[s] || '❓';
}

// ── Missions ──────────────────────────────────────────────────────
async function loadMissions() {
  const statut = document.getElementById('filter-statut-m')?.value || '';
  const today  = document.getElementById('filter-today-m')?.value  || '';
  const tbody  = document.getElementById('missions-tbody');
  tbody.innerHTML = '<tr><td colspan="8" class="text-center py-4"><div class="spinner-border spinner-border-sm"></div></td></tr>';

  let qs = [];
  if (statut) qs.push('statut=' + statut);
  if (today)  qs.push('today=1');

  try {
    const data = await API.get('/missions' + (qs.length ? '?' + qs.join('&') : ''));
    const list = data?.data || (Array.isArray(data) ? data : []);

    if (!list.length) {
      tbody.innerHTML = '<tr><td colspan="8" class="text-center py-5 text-muted">Aucune mission</td></tr>';
      return;
    }

    tbody.innerHTML = list.map(m => `
      <tr class="clickable">
        <td class="ps-3 fw-bold">#${m.id}</td>
        <td>
          <div class="fw-semibold small">${m.driver?.nom || '—'}</div>
          <div class="text-muted" style="font-size:.72rem">${m.driver?.tel || ''}</div>
        </td>
        <td>
          <div class="small fw-semibold">${m.reservation?.machine || '—'}</div>
          <div class="text-muted" style="font-size:.72rem">${m.reservation?.client || ''}</div>
        </td>
        <td>
          <div class="small">${m.planning?.depart_prevu || '—'}</div>
          ${m.planning?.en_retard ? '<span class="badge bg-danger-subtle text-danger" style="font-size:.68rem">⚠️ Retard</span>' : ''}
        </td>
        <td class="small text-muted">${m.destination?.adresse || '—'}</td>
        <td><span class="statut-badge sb-${m.statut}" style="font-size:.72rem">${m.statut_icon} ${m.statut}</span></td>
        <td>${m.note_client ? '⭐'.repeat(m.note_client) : '<span class="text-muted small">—</span>'}</td>
        <td>
          ${['assignee','en_route','sur_place','en_cours'].includes(m.statut)
            ? `<button class="btn btn-sm btn-outline-danger py-0 px-2" onclick="annulerMission(${m.id})">Annuler</button>` : ''}
        </td>
      </tr>
    `).join('');
  } catch(e) {
    tbody.innerHTML = '<tr><td colspan="8" class="text-center text-danger py-3">Erreur</td></tr>';
  }
}

// ── Planning hebdomadaire ────────────────────────────────────────
async function loadPlanning() {
  const container = document.getElementById('planning-container');
  container.innerHTML = '<div class="text-center py-5"><div class="spinner-border"></div></div>';

  const debut = new Date();
  debut.setDate(debut.getDate() - debut.getDay() + 1 + weekOffset * 7);
  const fin = new Date(debut);
  fin.setDate(fin.getDate() + 6);

  const fmt = d => d.toISOString().split('T')[0];
  document.getElementById('week-label').textContent =
    `${debut.toLocaleDateString('fr-FR', {day:'2-digit',month:'short'})} — ${fin.toLocaleDateString('fr-FR', {day:'2-digit',month:'short', year:'numeric'})}`;

  try {
    const data = await API.get(`/missions/planning?debut=${fmt(debut)}&fin=${fmt(fin)}`);
    const missions = Array.isArray(data) ? data : [];

    // Construire grille par chauffeur
    const days = Array.from({length:7}, (_, i) => {
      const d = new Date(debut); d.setDate(d.getDate() + i);
      return d;
    });

    const chauffeurs = [...new Set(missions.map(m => m.driver?.nom).filter(Boolean))];

    if (!chauffeurs.length) {
      container.innerHTML = '<div class="text-center py-5 text-muted">Aucune mission cette semaine</div>';
      return;
    }

    const dayNames = ['Lun','Mar','Mer','Jeu','Ven','Sam','Dim'];

    let html = `
      <div class="planning-grid">
        <div class="pg-header">Chauffeur</div>
        ${days.map((d,i) => `<div class="pg-header">${dayNames[i]}<br><small style="font-weight:400">${d.getDate()}/${d.getMonth()+1}</small></div>`).join('')}
    `;

    chauffeurs.forEach(nom => {
      html += `<div class="pg-time">${nom}</div>`;
      days.forEach(d => {
        const dayStr = fmt(d);
        const dayMissions = missions.filter(m =>
          m.driver?.nom === nom &&
          m.planning?.depart_prevu?.includes(d.toLocaleDateString('fr-FR').split('/').reverse().join('-'))
        );
        html += `<div class="pg-cell">`;
        dayMissions.forEach(m => {
          html += `<div class="pg-mission ${m.statut}" title="${m.reservation?.machine || ''}">${m.statut_icon} ${m.reservation?.machine || 'Mission #'+m.id}</div>`;
        });
        html += `</div>`;
      });
    });

    html += '</div>';
    container.innerHTML = html;
  } catch(e) {
    container.innerHTML = '<div class="text-center text-danger py-4">Erreur de chargement du planning</div>';
  }
}

function shiftWeek(dir) { weekOffset += dir; loadPlanning(); }

// ── Carte globale ─────────────────────────────────────────────────
function initMap() {
  if (mapGlobal) { refreshMap(); return; }
  mapGlobal = L.map('map-global').setView([31.7917, -7.0926], 5);
  L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png',
    { attribution:'© OpenStreetMap', maxZoom:19 }).addTo(mapGlobal);
  refreshMap();
}

async function refreshMap() {
  if (!mapGlobal) return;
  mapGlobal.eachLayer(l => { if (l instanceof L.Marker || l instanceof L.Polyline) mapGlobal.removeLayer(l); });

  const listEl = document.getElementById('active-missions-list');

  try {
    const [driversData, missionsData] = await Promise.all([
      API.get('/drivers/map'),
      API.get('/missions?statut=en_route'),
    ]);

    const drivers  = Array.isArray(driversData) ? driversData : [];
    const missions = missionsData?.data || (Array.isArray(missionsData) ? missionsData : []);

    // Markers chauffeurs
    drivers.forEach(d => {
      const color = d.statut === 'disponible' ? '#22c55e' : d.statut === 'en_mission' ? '#F59E0B' : '#6b7280';
      const icon  = L.divIcon({
        html:`<div style="background:${color};width:16px;height:16px;border-radius:50%;border:3px solid #fff;box-shadow:0 2px 8px rgba(0,0,0,.4)"></div>`,
        iconSize:[16,16], iconAnchor:[8,8], className:''
      });
      L.marker([d.lat,d.lng], {icon}).addTo(mapGlobal)
        .bindPopup(`🚗 <b>${d.nom}</b><br>${d.statut} · ${d.derniere_position}`);
    });

    // Lignes mission en route (chauffeur → destination)
    missions.forEach(m => {
      if (m.position_actuelle?.lat && m.destination?.lat) {
        L.polyline([
          [m.position_actuelle.lat, m.position_actuelle.lng],
          [m.destination.lat,       m.destination.lng],
        ], { color:'#F59E0B', weight:2, dashArray:'6 4' }).addTo(mapGlobal);

        // Marker destination
        const iconDst = L.divIcon({
          html:`<div style="background:#2563eb;width:12px;height:12px;border-radius:50%;border:2px solid #fff;box-shadow:0 2px 6px rgba(0,0,0,.3)"></div>`,
          iconSize:[12,12], iconAnchor:[6,6], className:''
        });
        L.marker([m.destination.lat, m.destination.lng], {icon:iconDst})
          .addTo(mapGlobal).bindPopup(`📍 Destination : ${m.destination.adresse || ''}`);
      }
    });

    // Liste missions actives
    const active = missions.filter(m => ['en_route','sur_place','en_cours'].includes(m.statut));
    if (!active.length) {
      listEl.innerHTML = '<div class="text-center py-4 text-muted small">Aucune mission en cours</div>';
    } else {
      listEl.innerHTML = active.map(m => `
        <div class="border-bottom p-3">
          <div class="d-flex justify-content-between align-items-start">
            <div>
              <div class="fw-semibold small">${m.driver?.nom || '—'}</div>
              <div class="text-muted" style="font-size:.75rem">${m.reservation?.machine || ''}</div>
            </div>
            <span class="statut-badge sb-${m.statut}" style="font-size:.68rem">${m.statut_icon}</span>
          </div>
          <div class="text-muted mt-1" style="font-size:.75rem">
            📍 ${m.destination?.adresse || '—'}<br>
            🕐 Prévu : ${m.planning?.depart_prevu || '—'}
          </div>
        </div>
      `).join('');
    }

  } catch(e) { console.error(e); }
}

// ── Modal add driver ──────────────────────────────────────────────
function openModalAddDriver() {
  new bootstrap.Modal(document.getElementById('modalAddDriver')).show();
}

async function saveDriver() {
  const competences = Array.from(document.getElementById('d-competences').selectedOptions).map(o => o.value);
  const payload = {
    user_id         : document.getElementById('d-user-id').value,
    numero_permis   : document.getElementById('d-permis').value,
    categorie_permis: document.getElementById('d-categorie').value,
    telephone       : document.getElementById('d-telephone').value,
    competences,
  };
  try {
    const res = await API.post('/drivers', payload);
    if (!res.ok) return showToast('Erreur : ' + JSON.stringify(res.data?.errors || res.data), true);
    bootstrap.Modal.getInstance(document.getElementById('modalAddDriver')).hide();
    await loadDrivers(); await loadKpis();
    showToast('Chauffeur ajouté ✅');
  } catch(e) { showToast('Erreur serveur', true); }
}

// ── Modal assign mission ──────────────────────────────────────────
async function openModalAssign() {
  // Charger chauffeurs disponibles
  const select = document.getElementById('m-driver-id');
  select.innerHTML = '<option value="">Chargement…</option>';
  const drivers = await API.get('/drivers?statut=disponible').catch(() => []);
  const list = Array.isArray(drivers) ? drivers : [];
  select.innerHTML = '<option value="">Sélectionner…</option>' +
    list.map(d => `<option value="${d.id}">${d.nom} — Permis ${d.categorie_permis}</option>`).join('');

  // Valeur par défaut heure départ
  const now = new Date(); now.setMinutes(0,0,0);
  document.getElementById('m-depart-prevu').value = now.toISOString().slice(0,16);

  new bootstrap.Modal(document.getElementById('modalAssign')).show();
}

async function saveMission() {
  const payload = {
    driver_id           : document.getElementById('m-driver-id').value,
    reservation_id      : document.getElementById('m-reservation-id').value,
    heure_depart_prevue : document.getElementById('m-depart-prevu').value,
    heure_arrivee_prevue: document.getElementById('m-arrivee-prevue').value || null,
    adresse_depart      : document.getElementById('m-adr-dep').value || null,
    lat_depart          : document.getElementById('m-lat-dep').value || null,
    lng_depart          : document.getElementById('m-lng-dep').value || null,
    adresse_destination : document.getElementById('m-adr-dst').value,
    lat_destination     : document.getElementById('m-lat-dst').value || null,
    lng_destination     : document.getElementById('m-lng-dst').value || null,
    distance_km         : document.getElementById('m-distance').value || null,
    duree_estimee_min   : document.getElementById('m-duree').value || null,
    instructions        : document.getElementById('m-instructions').value || null,
  };

  if (!payload.driver_id || !payload.reservation_id || !payload.heure_depart_prevue) {
    return showToast('Chauffeur, réservation et heure de départ sont requis', true);
  }

  try {
    const res = await API.post('/missions', payload);
    if (!res.ok) return showToast('Erreur : ' + JSON.stringify(res.data?.errors || res.data), true);
    bootstrap.Modal.getInstance(document.getElementById('modalAssign')).hide();
    await loadMissions(); await loadKpis();
    showToast('Mission assignée ✅');
  } catch(e) { showToast('Erreur serveur', true); }
}

// ── Détail chauffeur ──────────────────────────────────────────────
async function showDriverDetail(id) {
  document.getElementById('detail-body').innerHTML = '<div class="text-center py-4"><div class="spinner-border"></div></div>';
  new bootstrap.Modal(document.getElementById('modalDriverDetail')).show();

  try {
    const data = await API.get('/drivers/' + id);
    const d = data.driver;
    const missions = data.missions || [];

    document.getElementById('detail-title').textContent = `🚗 ${d.user?.name}`;
    document.getElementById('detail-body').innerHTML = `
      <div class="row g-3 mb-3">
        <div class="col-md-4">
          <div class="form-section">
            <h6>👤 Profil</h6>
            <div class="small"><b>Nom :</b> ${d.user?.name}</div>
            <div class="small"><b>Email :</b> ${d.user?.email}</div>
            <div class="small"><b>Tél :</b> ${d.telephone || '—'}</div>
            <div class="small"><b>Permis :</b> ${d.categorie_permis} — ${d.numero_permis}</div>
            <div class="small"><b>Missions :</b> ${d.missions_completees} complétées</div>
            <div class="small"><b>Note :</b> ${d.note_moyenne > 0 ? '⭐ ' + d.note_moyenne : '—'}</div>
            <div class="mt-2">
              <label class="form-label small fw-semibold">Changer statut</label>
              <select class="form-select form-select-sm" id="inline-statut-${d.id}" onchange="updateStatutInline(${d.id})">
                ${['disponible','en_mission','indisponible','conge'].map(s =>
                  `<option value="${s}" ${d.statut===s?'selected':''}>${s.replace('_',' ')}</option>`).join('')}
              </select>
            </div>
          </div>
        </div>
        <div class="col-md-8">
          <div class="form-section">
            <h6>📋 10 dernières missions</h6>
            <div class="table-responsive">
              <table class="table table-sm mb-0 align-middle">
                <thead class="table-light"><tr><th>#</th><th>Machine</th><th>Statut</th><th>Date</th><th>Note</th></tr></thead>
                <tbody>
                  ${missions.length ? missions.map(m => `
                    <tr>
                      <td class="fw-bold">#${m.id}</td>
                      <td class="small">${m.reservation?.machine?.name || '—'}</td>
                      <td><span class="statut-badge sb-${m.statut}" style="font-size:.7rem">${m.statut}</span></td>
                      <td class="small">${m.created_at || '—'}</td>
                      <td>${m.note_client ? '⭐'.repeat(m.note_client) : '—'}</td>
                    </tr>
                  `).join('') : '<tr><td colspan="5" class="text-center text-muted">Aucune mission</td></tr>'}
                </tbody>
              </table>
            </div>
          </div>
        </div>
      </div>
    `;
  } catch(e) {
    document.getElementById('detail-body').innerHTML = '<div class="text-danger text-center py-4">Erreur de chargement</div>';
  }
}

async function updateStatutInline(id) {
  const statut = document.getElementById('inline-statut-' + id).value;
  try {
    await API.put('/drivers/' + id, { statut });
    await loadDrivers(); await loadKpis();
    showToast('Statut mis à jour');
  } catch(e) { showToast('Erreur', true); }
}

async function changeDriverStatut(id, current) {
  const next = { disponible:'indisponible', indisponible:'disponible', conge:'disponible', en_mission:'indisponible' };
  const newS = next[current] || 'disponible';
  await API.put('/drivers/' + id, { statut: newS });
  await loadDrivers(); await loadKpis();
  showToast('Statut → ' + newS);
}

async function desactiverDriver(id) {
  if (!confirm('Désactiver ce chauffeur ?')) return;
  await API.delete('/drivers/' + id);
  await loadDrivers(); await loadKpis();
  showToast('Chauffeur désactivé');
}

async function annulerMission(id) {
  if (!confirm('Annuler cette mission ?')) return;
  await API.post('/missions/' + id + '/statut', { statut: 'annulee' });
  await loadMissions(); await loadKpis();
  showToast('Mission annulée');
}

// ── Toast ────────────────────────────────────────────────────────
function showToast(msg, isError = false) {
  const t = document.createElement('div');
  t.className = `position-fixed bottom-0 end-0 m-3 alert ${isError ? 'alert-danger' : 'alert-success'} shadow`;
  t.style.cssText = 'z-index:9999;min-width:260px;';
  t.textContent = msg;
  document.body.appendChild(t);
  setTimeout(() => t.remove(), 3500);
}
</script>
@endpush