{{-- resources/views/dashboard/driver.blade.php --}}
@extends('layouts.app')

@section('title', 'Mon Espace Chauffeur — Rentify')

@push('styles')
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css"/>
<style>
  :root { --navy: #0F1B2D; --orange: #F59E0B; }

  .driver-hero {
    background: linear-gradient(135deg, var(--navy) 0%, #1a2f4a 100%);
    color: #fff; padding: 2rem 0; margin-bottom: 2rem;
  }
  .driver-hero .badge-statut {
    font-size: .85rem; padding: .4rem .9rem; border-radius: 20px;
    font-weight: 600;
  }
  .statut-disponible  { background: #22c55e; color: #fff; }
  .statut-en_mission  { background: var(--orange); color: #fff; }
  .statut-indisponible{ background: #6b7280; color: #fff; }
  .statut-conge       { background: #06b6d4; color: #fff; }

  /* KPI Cards */
  .kpi-card {
    background: #fff; border-radius: 12px; padding: 1.4rem 1.8rem;
    box-shadow: 0 2px 10px rgba(0,0,0,.07);
    display: flex; align-items: center; gap: 1rem; height: 100%;
  }
  .kpi-icon { font-size: 2rem; width: 50px; text-align: center; }
  .kpi-val  { font-size: 1.8rem; font-weight: 700; color: var(--navy); line-height: 1; }
  .kpi-lbl  { font-size: .8rem; color: #64748b; text-transform: uppercase; letter-spacing: .05em; }

  /* Mission Card */
  .mission-card {
    border: none; border-radius: 14px;
    box-shadow: 0 3px 15px rgba(0,0,0,.09);
    overflow: hidden; transition: transform .2s;
  }
  .mission-card:hover { transform: translateY(-3px); }
  .mission-card .card-header {
    background: var(--navy); color: #fff; padding: .9rem 1.3rem;
    display: flex; justify-content: space-between; align-items: center;
  }
  .statut-badge {
    display: inline-flex; align-items: center; gap: .35rem;
    font-size: .78rem; font-weight: 600; padding: .3rem .75rem;
    border-radius: 20px; border: 1.5px solid;
  }
  .sb-assignee   { color: #7c3aed; border-color: #7c3aed; background: #f5f3ff; }
  .sb-en_route   { color: #d97706; border-color: #d97706; background: #fffbeb; }
  .sb-sur_place  { color: #059669; border-color: #059669; background: #ecfdf5; }
  .sb-en_cours   { color: #2563eb; border-color: #2563eb; background: #eff6ff; }
  .sb-terminee   { color: #16a34a; border-color: #16a34a; background: #f0fdf4; }
  .sb-annulee    { color: #dc2626; border-color: #dc2626; background: #fef2f2; }

  /* Timeline */
  .timeline { position: relative; padding-left: 2rem; }
  .timeline::before {
    content: ''; position: absolute; left: .65rem; top: 0; bottom: 0;
    width: 2px; background: #e2e8f0;
  }
  .tl-item { position: relative; margin-bottom: 1.1rem; }
  .tl-dot {
    position: absolute; left: -1.65rem; top: .2rem;
    width: 12px; height: 12px; border-radius: 50%;
    background: #e2e8f0; border: 2px solid #fff;
  }
  .tl-dot.done  { background: #22c55e; }
  .tl-dot.active{ background: var(--orange); box-shadow: 0 0 0 3px rgba(245,158,11,.25); }
  .tl-dot.late  { background: #ef4444; }
  .tl-label { font-size: .8rem; color: #94a3b8; margin-bottom: .15rem; }
  .tl-val   { font-size: .9rem; font-weight: 600; color: var(--navy); }

  /* Map */
  #map-mission { height: 340px; border-radius: 12px; overflow: hidden; }
  #map-drivers  { height: 400px; border-radius: 12px; overflow: hidden; }

  /* Planning */
  .planning-row { cursor: pointer; transition: background .15s; }
  .planning-row:hover { background: #f8fafc; }
  .progress-statut { height: 6px; border-radius: 3px; }

  /* Boutons action */
  .btn-action {
    font-size: .82rem; font-weight: 600; padding: .45rem 1rem;
    border-radius: 8px; border: none; cursor: pointer; transition: all .2s;
  }
  .btn-orange  { background: var(--orange); color: #fff; }
  .btn-orange:hover { background: #d97706; color: #fff; }
  .btn-navy    { background: var(--navy);  color: #fff; }
  .btn-navy:hover  { background: #1a3050; color: #fff; }

  @media (max-width: 768px) {
    .kpi-card { padding: 1rem; }
    .kpi-val  { font-size: 1.4rem; }
  }
</style>
@endpush

@section('content')
<!-- ════ HERO ════════════════════════════════════════════════════════════ -->
<div class="driver-hero">
  <div class="container">
    <div class="d-flex flex-wrap align-items-center justify-content-between gap-3">
      <div>
        <div class="d-flex align-items-center gap-3 mb-1">
          <div style="width:52px;height:52px;background:var(--orange);border-radius:50%;
               display:flex;align-items:center;justify-content:center;font-size:1.5rem;">🚗</div>
          <div>
            <h2 class="mb-0 fw-bold" id="driver-name">Chargement…</h2>
            <small class="opacity-75">Permis : <span id="driver-permis">—</span></small>
          </div>
        </div>
      </div>
      <div class="d-flex align-items-center gap-3">
        <span class="badge-statut statut-disponible" id="badge-statut">Disponible</span>
        <button class="btn btn-sm btn-outline-light" id="btn-gps-update" onclick="updateMyPosition()">
          <i class="fas fa-location-crosshairs me-1"></i>Ma position
        </button>
      </div>
    </div>
  </div>
</div>

<div class="container pb-5">

  <!-- ════ KPI ══════════════════════════════════════════════════════════ -->
  <div class="row g-3 mb-4" id="kpi-row">
    <div class="col-6 col-md-3">
      <div class="kpi-card">
        <div class="kpi-icon">✅</div>
        <div>
          <div class="kpi-val" id="kpi-completees">—</div>
          <div class="kpi-lbl">Missions complétées</div>
        </div>
      </div>
    </div>
    <div class="col-6 col-md-3">
      <div class="kpi-card">
        <div class="kpi-icon">⭐</div>
        <div>
          <div class="kpi-val" id="kpi-note">—</div>
          <div class="kpi-lbl">Note moyenne</div>
        </div>
      </div>
    </div>
    <div class="col-6 col-md-3">
      <div class="kpi-card">
        <div class="kpi-icon">📋</div>
        <div>
          <div class="kpi-val" id="kpi-today">—</div>
          <div class="kpi-lbl">Missions aujourd'hui</div>
        </div>
      </div>
    </div>
    <div class="col-6 col-md-3">
      <div class="kpi-card">
        <div class="kpi-icon">🕐</div>
        <div>
          <div class="kpi-val" id="kpi-heures">—</div>
          <div class="kpi-lbl">Heures ce mois</div>
        </div>
      </div>
    </div>
  </div>

  <!-- ════ MISSION EN COURS ════════════════════════════════════════════ -->
  <div id="mission-active-block" class="d-none mb-4">
    <h5 class="fw-bold mb-3"><span class="text-warning me-2">⚡</span>Mission en cours</h5>
    <div class="mission-card card" id="active-mission-card">
      <div class="card-header">
        <span id="mc-title">Mission #—</span>
        <span class="statut-badge sb-en_route" id="mc-badge">En route</span>
      </div>
      <div class="card-body">
        <div class="row g-4">

          <!-- Infos mission -->
          <div class="col-md-5">
            <div class="mb-3">
              <div class="text-muted small mb-1">🏗️ Machine</div>
              <div class="fw-semibold" id="mc-machine">—</div>
            </div>
            <div class="mb-3">
              <div class="text-muted small mb-1">👤 Client</div>
              <div class="fw-semibold" id="mc-client">—</div>
            </div>
            <div class="mb-3">
              <div class="text-muted small mb-1">📍 Destination</div>
              <div class="fw-semibold" id="mc-adresse">—</div>
            </div>
            <div class="mb-3" id="mc-instructions-block">
              <div class="text-muted small mb-1">📝 Instructions</div>
              <div class="bg-light p-2 rounded small" id="mc-instructions">—</div>
            </div>

            <!-- Actions statut -->
            <div class="d-flex flex-wrap gap-2 mt-3" id="mc-actions">
              <!-- dynamique -->
            </div>
          </div>

          <!-- Timeline + Carte -->
          <div class="col-md-7">
            <div class="timeline mb-3" id="mc-timeline">
              <!-- injecté JS -->
            </div>
            <div id="map-mission"></div>
          </div>

        </div>
      </div>
    </div>
  </div>

  <!-- ════ PLANNING / PROCHAINES MISSIONS ════════════════════════════ -->
  <div class="row g-4 mb-4">
    <div class="col-lg-8">
      <div class="card border-0 shadow-sm">
        <div class="card-header bg-white border-0 py-3 d-flex justify-content-between align-items-center">
          <h6 class="mb-0 fw-bold">📅 Planning — Mes missions</h6>
          <div class="d-flex gap-2">
            <select class="form-select form-select-sm" id="filter-statut" onchange="loadMissions()" style="width:150px">
              <option value="">Tous les statuts</option>
              <option value="assignee">Assignée</option>
              <option value="en_route">En route</option>
              <option value="terminee">Terminée</option>
              <option value="annulee">Annulée</option>
            </select>
          </div>
        </div>
        <div class="card-body p-0">
          <div class="table-responsive">
            <table class="table table-hover mb-0 align-middle">
              <thead class="table-light">
                <tr>
                  <th class="ps-3">Date/Heure</th>
                  <th>Machine</th>
                  <th>Destination</th>
                  <th>Statut</th>
                  <th>Note</th>
                </tr>
              </thead>
              <tbody id="missions-tbody">
                <tr><td colspan="5" class="text-center py-4 text-muted">
                  <div class="spinner-border spinner-border-sm me-2"></div>Chargement…
                </td></tr>
              </tbody>
            </table>
          </div>
        </div>
      </div>
    </div>

    <!-- Carte flotte -->
    <div class="col-lg-4">
      <div class="card border-0 shadow-sm h-100">
        <div class="card-header bg-white border-0 py-3">
          <h6 class="mb-0 fw-bold">🗺️ Carte des chauffeurs</h6>
        </div>
        <div class="card-body p-2">
          <div id="map-drivers"></div>
          <div class="mt-2 d-flex flex-wrap gap-2">
            <span class="badge bg-success">🟢 Disponible</span>
            <span class="badge" style="background:var(--orange)">🟠 En mission</span>
            <span class="badge bg-secondary">⚫ Indisponible</span>
          </div>
        </div>
      </div>
    </div>
  </div>

</div><!-- /container -->

<!-- ═══ MODAL NOTER CLIENT ══════════════════════════════════════════════ -->
<div class="modal fade" id="modalNotes" tabindex="-1">
  <div class="modal-dialog modal-sm">
    <div class="modal-content">
      <div class="modal-header">
        <h6 class="modal-title fw-bold">📝 Notes de fin de mission</h6>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
      </div>
      <div class="modal-body">
        <div class="mb-3">
          <label class="form-label small fw-semibold">Remarques / observations</label>
          <textarea class="form-control form-control-sm" id="textarea-notes" rows="4"
            placeholder="Ex : accès difficile, machine nettoyée, heures supplémentaires…"></textarea>
        </div>
      </div>
      <div class="modal-footer">
        <button class="btn btn-sm btn-secondary" data-bs-dismiss="modal">Annuler</button>
        <button class="btn btn-sm btn-action btn-orange" id="btn-confirm-action">Confirmer</button>
      </div>
    </div>
  </div>
</div>
@endsection

@push('scripts')
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
<script>
/* ══════════════════════════════════════════════════════════════════════
   DASHBOARD CHAUFFEUR — Rentify
══════════════════════════════════════════════════════════════════════ */
let currentDriverId = null;
let activeMission   = null;
let mapMission      = null;
let mapDrivers      = null;
let markerMe        = null;
let markerDest      = null;
let pendingAction   = null;

// ── Init ────────────────────────────────────────────────────────────
document.addEventListener('DOMContentLoaded', () => {
  initMaps();
  loadDriverProfile();
  loadMissions();
  // Refresh toutes les 30 secondes
  setInterval(() => { loadActiveMission(); refreshDriversMap(); }, 30000);
});

// ── Profil chauffeur ────────────────────────────────────────────────
async function loadDriverProfile() {
  const user = getUser();
  if (!user) return window.location.replace('/login');

  document.getElementById('driver-name').textContent = user.name;

  try {
    const drivers = await API.get('/drivers');
    const me = Array.isArray(drivers) ? drivers.find(d => d.user_id === user.id) : null;
    if (!me) return;

    currentDriverId = me.id;
    document.getElementById('driver-permis').textContent =
      `${me.categorie_permis} — ${me.numero_permis}`;

    // Badge statut
    const badge = document.getElementById('badge-statut');
    badge.textContent = me.statut.replace('_', ' ').charAt(0).toUpperCase() +
                        me.statut.replace('_', ' ').slice(1);
    badge.className = `badge-statut statut-${me.statut}`;

    // KPIs
    document.getElementById('kpi-completees').textContent = me.missions_completees;
    document.getElementById('kpi-note').textContent =
      me.note_moyenne > 0 ? `${me.note_moyenne} ⭐` : '—';

    loadActiveMission();
  } catch(e) { console.error(e); }
}

// ── Mission active ──────────────────────────────────────────────────
async function loadActiveMission() {
  if (!currentDriverId) return;
  try {
    const missions = await API.get(`/missions?driver_id=${currentDriverId}`);
    const list = missions?.data || (Array.isArray(missions) ? missions : []);
    const active = list.find(m =>
      ['assignee','en_route','sur_place','en_cours'].includes(m.statut));

    const block = document.getElementById('mission-active-block');
    if (!active) { block.classList.add('d-none'); return; }

    activeMission = active;
    block.classList.remove('d-none');
    renderActiveMission(active);
  } catch(e) { console.error(e); }
}

function renderActiveMission(m) {
  document.getElementById('mc-title').textContent   = `Mission #${m.id}`;
  document.getElementById('mc-machine').textContent = m.reservation?.machine || '—';
  document.getElementById('mc-client').textContent  = m.reservation?.client  || '—';
  document.getElementById('mc-adresse').textContent = m.destination?.adresse  || 'Non renseignée';

  const instr = m.instructions;
  if (instr) {
    document.getElementById('mc-instructions').textContent = instr;
    document.getElementById('mc-instructions-block').classList.remove('d-none');
  }

  // Badge statut
  const badge = document.getElementById('mc-badge');
  const labels = { assignee:'Assignée', en_route:'En route',
                   sur_place:'Sur place', en_cours:'En cours' };
  badge.textContent  = labels[m.statut] || m.statut;
  badge.className    = `statut-badge sb-${m.statut}`;

  // Timeline
  renderTimeline(m);

  // Carte
  updateMissionMap(m);

  // Actions
  renderActions(m.statut);
}

function renderTimeline(m) {
  const steps = [
    { key:'assignee',  label:'Mission assignée',  time: m.planning.depart_prevu   },
    { key:'en_route',  label:'En route',           time: m.planning.depart_reel    },
    { key:'sur_place', label:'Arrivé sur place',   time: m.planning.arrivee_reelle },
    { key:'en_cours',  label:'Mission démarrée',   time: m.planning.arrivee_prevue },
    { key:'terminee',  label:'Mission terminée',   time: m.planning.fin_reelle     },
  ];
  const order = ['assignee','en_route','sur_place','en_cours','terminee'];
  const cur   = order.indexOf(m.statut);

  const html = steps.map((s, i) => {
    const done = i < cur;
    const act  = i === cur;
    const late = m.planning.en_retard && act;
    return `
      <div class="tl-item">
        <div class="tl-dot ${done?'done':act?late?'late':'active':''}"></div>
        <div class="tl-label">${s.label}</div>
        <div class="tl-val">${s.time || (done?'Complété':'À venir')}</div>
      </div>`;
  }).join('');
  document.getElementById('mc-timeline').innerHTML = html;
}

function renderActions(statut) {
  const next = {
    assignee : { label:'🚗 Démarrer trajet',   newStatut:'en_route',  cls:'btn-orange'},
    en_route : { label:'📍 Je suis sur place', newStatut:'sur_place', cls:'btn-navy'  },
    sur_place: { label:'⚙️ Démarrer mission',  newStatut:'en_cours',  cls:'btn-orange'},
    en_cours : { label:'✅ Terminer mission',   newStatut:'terminee',  cls:'btn-navy'  },
  };
  const a = next[statut];
  if (!a) { document.getElementById('mc-actions').innerHTML = ''; return; }

  document.getElementById('mc-actions').innerHTML = `
    <button class="btn btn-action ${a.cls}" onclick="changeStatut('${a.newStatut}')">
      ${a.label}
    </button>
    ${statut === 'en_cours' ? `
    <button class="btn btn-action btn-outline-secondary"
      onclick="updateMyPosition()">📡 Mettre à jour GPS</button>` : ''}
  `;
}

// ── Changer statut mission ─────────────────────────────────────────
function changeStatut(newStatut) {
  pendingAction = newStatut;
  if (newStatut === 'terminee') {
    new bootstrap.Modal(document.getElementById('modalNotes')).show();
    document.getElementById('btn-confirm-action').onclick = confirmAction;
  } else {
    confirmAction();
  }
}

async function confirmAction() {
  if (!activeMission || !pendingAction) return;
  const notes = document.getElementById('textarea-notes').value;
  const pos   = await getCurrentPosition().catch(() => null);

  try {
    const res = await API.post(`/missions/${activeMission.id}/statut`, {
      statut         : pendingAction,
      notes_chauffeur: notes || null,
      lat: pos?.lat || null,
      lng: pos?.lng || null,
    });
    bootstrap.Modal.getInstance(document.getElementById('modalNotes'))?.hide();
    await loadActiveMission();
    await loadMissions();
    pendingAction = null;
    showToast('Statut mis à jour ✅');
  } catch(e) { console.error(e); showToast('Erreur mise à jour', true); }
}

// ── GPS helpers ─────────────────────────────────────────────────────
function getCurrentPosition() {
  return new Promise((res, rej) => {
    if (!navigator.geolocation) return rej('GPS non disponible');
    navigator.geolocation.getCurrentPosition(
      p  => res({ lat: p.coords.latitude, lng: p.coords.longitude }),
      err => rej(err)
    );
  });
}

async function updateMyPosition() {
  try {
    const pos = await getCurrentPosition();
    if (currentDriverId) {
      await API.post(`/drivers/${currentDriverId}/position`, pos);
    }
    if (activeMission) {
      await API.post(`/missions/${activeMission.id}/track`, pos);
    }
    if (mapMission && markerMe) {
      markerMe.setLatLng([pos.lat, pos.lng]);
      mapMission.setView([pos.lat, pos.lng], 13);
    }
    showToast('Position mise à jour 📡');
  } catch(e) { showToast('Impossible de récupérer la position GPS', true); }
}

// ── Carte mission ────────────────────────────────────────────────────
function initMaps() {
  mapMission = L.map('map-mission').setView([33.5731, -7.5898], 7);
  L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png',
    { attribution: '© OpenStreetMap', maxZoom: 19 }).addTo(mapMission);

  mapDrivers = L.map('map-drivers').setView([31.7917, -7.0926], 5);
  L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png',
    { attribution: '© OpenStreetMap', maxZoom: 19 }).addTo(mapDrivers);

  refreshDriversMap();
}

function updateMissionMap(m) {
  if (!mapMission) return;

  // Marker départ
  if (m.depart?.lat && m.depart?.lng) {
    const iconDep = L.divIcon({ html: '<div style="background:#0F1B2D;width:14px;height:14px;border-radius:50%;border:3px solid #fff;box-shadow:0 2px 6px rgba(0,0,0,.4)"></div>', iconSize:[14,14], iconAnchor:[7,7], className:'' });
    L.marker([m.depart.lat, m.depart.lng], { icon: iconDep })
      .addTo(mapMission).bindPopup(`🏁 Départ : ${m.depart.adresse || 'Dépôt'}`);
  }

  // Marker destination
  if (m.destination?.lat && m.destination?.lng) {
    const iconDst = L.divIcon({ html: '<div style="background:#F59E0B;width:18px;height:18px;border-radius:50% 50% 50% 0;transform:rotate(-45deg);border:3px solid #fff;box-shadow:0 2px 6px rgba(0,0,0,.4)"></div>', iconSize:[18,18], iconAnchor:[9,18], className:'' });
    markerDest = L.marker([m.destination.lat, m.destination.lng], { icon: iconDst })
      .addTo(mapMission).bindPopup(`📍 Destination : ${m.destination.adresse || 'Chantier'}`);

    mapMission.setView([m.destination.lat, m.destination.lng], 12);
  }

  // Marker position actuelle
  if (m.position_actuelle?.lat && m.position_actuelle?.lng) {
    const iconMe = L.divIcon({ html: '<div style="background:#22c55e;width:16px;height:16px;border-radius:50%;border:3px solid #fff;box-shadow:0 0 0 4px rgba(34,197,94,.25)"></div>', iconSize:[16,16], iconAnchor:[8,8], className:'' });
    if (markerMe) {
      markerMe.setLatLng([m.position_actuelle.lat, m.position_actuelle.lng]);
    } else {
      markerMe = L.marker([m.position_actuelle.lat, m.position_actuelle.lng], { icon: iconMe })
        .addTo(mapMission).bindPopup('🚗 Ma position');
    }
  }
}

async function refreshDriversMap() {
  if (!mapDrivers) return;
  try {
    const drivers = await API.get('/drivers/map');
    const list = Array.isArray(drivers) ? drivers : [];
    mapDrivers.eachLayer(l => { if (l instanceof L.Marker) mapDrivers.removeLayer(l); });

    list.forEach(d => {
      const color = d.statut === 'disponible' ? '#22c55e' :
                    d.statut === 'en_mission'  ? '#F59E0B' : '#6b7280';
      const icon  = L.divIcon({
        html: `<div style="background:${color};width:14px;height:14px;border-radius:50%;border:3px solid #fff;box-shadow:0 2px 8px rgba(0,0,0,.4)"></div>`,
        iconSize:[14,14], iconAnchor:[7,7], className:''
      });
      L.marker([d.lat, d.lng], { icon })
        .addTo(mapDrivers)
        .bindPopup(`🚗 ${d.nom}<br><small>${d.statut} · ${d.derniere_position}</small>`);
    });
  } catch(e) { /* silencieux */ }
}

// ── Liste missions ────────────────────────────────────────────────
async function loadMissions() {
  if (!currentDriverId) {
    // Essaie de retrouver l'ID
    await loadDriverProfile();
    if (!currentDriverId) { setTimeout(loadMissions, 1000); return; }
  }
  const statut = document.getElementById('filter-statut').value;
  const qs     = `driver_id=${currentDriverId}${statut ? '&statut=' + statut : ''}`;
  const tbody  = document.getElementById('missions-tbody');

  try {
    const res  = await API.get(`/missions?${qs}`);
    const list = res?.data || (Array.isArray(res) ? res : []);

    document.getElementById('kpi-today').textContent =
      list.filter(m => m.planning?.depart_prevu?.includes(new Date().toLocaleDateString('fr-FR'))).length;

    if (!list.length) {
      tbody.innerHTML = '<tr><td colspan="5" class="text-center py-5 text-muted">Aucune mission trouvée</td></tr>';
      return;
    }

    tbody.innerHTML = list.map(m => `
      <tr class="planning-row" onclick="showMissionDetail(${m.id})">
        <td class="ps-3">
          <div class="fw-semibold small">${m.planning?.depart_prevu || '—'}</div>
          ${m.planning?.en_retard ? '<span class="badge bg-danger-subtle text-danger" style="font-size:.7rem">En retard</span>' : ''}
        </td>
        <td><span class="fw-semibold small">${m.reservation?.machine || '—'}</span></td>
        <td><span class="text-muted small">${m.destination?.adresse || '—'}</span></td>
        <td><span class="statut-badge sb-${m.statut}" style="font-size:.72rem">${m.statut_icon} ${m.statut}</span></td>
        <td>${m.note_client ? '⭐'.repeat(m.note_client) : '<span class="text-muted small">—</span>'}</td>
      </tr>
    `).join('');
  } catch(e) {
    tbody.innerHTML = '<tr><td colspan="5" class="text-center text-danger py-3">Erreur de chargement</td></tr>';
  }
}

// ── Toast ────────────────────────────────────────────────────────
function showToast(msg, isError = false) {
  const t = document.createElement('div');
  t.className = `position-fixed bottom-0 end-0 m-3 alert ${isError ? 'alert-danger' : 'alert-success'} shadow`;
  t.style.cssText = 'z-index:9999;min-width:260px;animation:fadeIn .3s';
  t.textContent = msg;
  document.body.appendChild(t);
  setTimeout(() => t.remove(), 3000);
}
</script>
@endpush