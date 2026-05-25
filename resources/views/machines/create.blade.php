@extends('layouts.app')

@section('title', isset($editMode) && $editMode ? 'Modifier la machine — Rentify' : 'Ajouter une machine — Rentify')

@push('styles')
<style>
/* ══════════════════════════════════════════════
   MACHINES CREATE / EDIT — RENTIFY V13
   ✅ Zéro navy bg — Zéro couleurs interdites
   ✅ btn-navy → alias btn-gold (V13)
   ✅ 3D palette gold uniquement
══════════════════════════════════════════════ */
:root {
  --gold:#D4AF37; --gold-dk:#9A7D20; --gold-pale:#FEF9E7;
  --gold-glow:rgba(212,175,55,.25);
  --cream:#FAF7F0; --cream2:#F0EBE0; --cream3:#E8DDD0;
  --txt-dark:#1a1a2e; --txt-mid:#5a5660; --txt-light:#9992a4;
  --green:#22c55e; --red:#ef4444;
  --radius:14px; --shadow:0 4px 24px rgba(15,27,45,.08);
}
body { background:var(--cream); font-family:'DM Sans',sans-serif; }

/* ── Page layout ── */
.create-wrap {
  max-width:1080px; margin:0 auto;
  padding:2.5rem 2rem 5rem;
}

/* ── Page header ── */
.page-header {
  display:flex; align-items:center; justify-content:space-between;
  margin-bottom:2rem; flex-wrap:wrap; gap:1rem;
}
.page-header-left { display:flex; align-items:center; gap:1rem; }
.back-btn {
  display:inline-flex; align-items:center; gap:.4rem;
  padding:.5rem .9rem; border-radius:9px;
  background:#fff; border:1.5px solid var(--cream3);
  color:var(--txt-mid); font-size:.82rem; font-weight:600;
  cursor:pointer; text-decoration:none; transition:all .2s;
}
.back-btn:hover { border-color:var(--gold); color:var(--txt-dark); }
.page-title {
  font-family:'Playfair Display',serif;
  font-size:1.7rem; color:var(--txt-dark); margin:0;
}
.page-title span { color:var(--gold); }
.page-sub { color:var(--txt-light); font-size:.85rem; margin:.2rem 0 0; }

/* ── Steps bar ── */
.steps-bar {
  display:flex; align-items:center;
  background:#fff; border:1px solid rgba(212,175,55,.12);
  border-radius:12px; padding:.6rem 1.5rem;
  margin-bottom:2rem; overflow-x:auto; gap:0;
}
.step-item {
  display:flex; align-items:center; gap:.5rem;
  padding:.5rem 1.2rem; border-radius:8px;
  cursor:pointer; transition:all .2s; flex-shrink:0;
}
/* ✅ active = gold-pale + border gold (plus navy bg) */
.step-item.active {
  background:var(--gold-pale);
  border:1.5px solid rgba(212,175,55,.4);
}
.step-item.done { background:var(--gold-pale); }
.step-dot {
  width:24px; height:24px; border-radius:50%;
  display:flex; align-items:center; justify-content:center;
  font-size:.72rem; font-weight:800; flex-shrink:0;
  background:var(--cream2); color:var(--txt-mid);
  border:2px solid var(--cream3);
}
.step-item.active .step-dot {
  background:var(--gold); color:var(--txt-dark); border-color:var(--gold);
}
.step-item.done .step-dot  { background:var(--green); color:#fff; border-color:var(--green); }
.step-label { font-size:.8rem; font-weight:700; color:var(--txt-mid); }
.step-item.active .step-label { color:var(--gold-dk); }
.step-item.done .step-label   { color:var(--gold-dk); }
.step-arrow { color:var(--cream3); font-size:.8rem; padding:0 .3rem; }

/* ── Grid layout ── */
.create-grid {
  display:grid;
  grid-template-columns:1fr 360px;
  gap:1.5rem; align-items:start;
}

/* ── Cards ── */
.form-card {
  background:#fff;
  border:1px solid rgba(212,175,55,.12);
  border-radius:var(--radius);
  box-shadow:var(--shadow);
  overflow:hidden;
}
.form-card-header {
  padding:1.1rem 1.5rem;
  border-bottom:1px solid var(--cream3);
  background:var(--cream);
  display:flex; align-items:center; gap:.6rem;
}
.form-card-header h3 {
  font-family:'Playfair Display',serif;
  font-size:1rem; color:var(--txt-dark); margin:0;
}
/* ✅ icon gold bg (plus navy bg) */
.form-card-icon {
  width:32px; height:32px; border-radius:8px;
  background:var(--gold); color:var(--txt-dark);
  font-size:.9rem; display:flex; align-items:center; justify-content:center;
}
.form-card-body { padding:1.5rem; }

/* ── Form elements ── */
.form-group { margin-bottom:1.2rem; }
.form-label {
  display:block; font-size:.78rem; font-weight:800;
  color:var(--txt-dark); margin-bottom:.45rem; letter-spacing:.03em;
}
.form-label span { color:var(--red); margin-left:.2rem; }
.form-control {
  width:100%; padding:.62rem .9rem;
  border:1.5px solid var(--cream3); border-radius:10px;
  font-family:'DM Sans',sans-serif; font-size:.88rem;
  color:var(--txt-dark); outline:none; background:#fff;
  transition:border .2s, box-shadow .2s; box-sizing:border-box;
}
.form-control:focus {
  border-color:var(--gold);
  box-shadow:0 0 0 3px var(--gold-glow);
}
.form-control.error { border-color:var(--red); }
.field-error { color:var(--red); font-size:.75rem; margin-top:.3rem; display:none; }
.field-error.show { display:block; }
.form-hint { color:var(--txt-light); font-size:.75rem; margin-top:.3rem; }
textarea.form-control { resize:vertical; min-height:100px; }

.form-row-2 { display:grid; grid-template-columns:1fr 1fr; gap:1rem; }
.form-row-3 { display:grid; grid-template-columns:1fr 1fr 1fr; gap:.75rem; }

/* ── Type selector chips ── */
.type-selector {
  display:grid; grid-template-columns:repeat(4,1fr); gap:.5rem;
}
.type-opt {
  display:flex; flex-direction:column; align-items:center;
  justify-content:center; gap:.3rem;
  padding:.7rem .4rem; border-radius:10px;
  border:1.5px solid var(--cream3); background:#fff;
  cursor:pointer; transition:all .2s; text-align:center;
}
.type-opt:hover { border-color:var(--gold); background:var(--gold-pale); }
/* ✅ selected = gold-pale + border gold (plus navy bg) */
.type-opt.selected {
  background:var(--gold-pale);
  border-color:var(--gold);
  box-shadow:0 4px 12px var(--gold-glow);
}
.type-opt-icon  { font-size:1.4rem; line-height:1; }
.type-opt-label { font-size:.7rem; font-weight:700; color:var(--txt-mid); text-transform:capitalize; }
.type-opt.selected .type-opt-label { color:var(--gold-dk); }

/* ── Status toggle ── */
.status-toggle { display:flex; gap:.5rem; flex-wrap:wrap; }
.status-btn {
  flex:1; min-width:100px;
  display:flex; flex-direction:column; align-items:center; gap:.3rem;
  padding:.75rem .5rem; border-radius:10px;
  border:1.5px solid var(--cream3); background:#fff;
  cursor:pointer; transition:all .2s; text-align:center;
}
.status-btn:hover { border-color:var(--gold); }
.status-btn.active-available   { background:#dcfce7; border-color:#22c55e; }
.status-btn.active-unavailable { background:#fee2e2; border-color:var(--red); }
/* ✅ maintenance = gold-pale + gold border (plus #fef9c3 + #ca8a04 interdits) */
.status-btn.active-maintenance { background:#FEF9E7; border-color:var(--gold); }
.status-btn-icon  { font-size:1.2rem; }
.status-btn-label { font-size:.72rem; font-weight:700; color:var(--txt-mid); }
.status-btn.active-available .status-btn-label   { color:#15803d; }
.status-btn.active-unavailable .status-btn-label { color:#dc2626; }
/* ✅ maintenance label = gold-dk (plus #ca8a04 interdit) */
.status-btn.active-maintenance .status-btn-label { color:var(--gold-dk); }

/* ── Image upload zone ── */
.upload-zone {
  border:2px dashed var(--cream3); border-radius:12px;
  padding:2rem; text-align:center; cursor:pointer;
  transition:all .2s; background:var(--cream);
  position:relative; overflow:hidden;
}
.upload-zone:hover, .upload-zone.drag-over {
  border-color:var(--gold); background:var(--gold-pale);
}
.upload-zone input[type="file"] { position:absolute; inset:0; opacity:0; cursor:pointer; }
.upload-icon  { font-size:2.2rem; margin-bottom:.5rem; opacity:.6; }
.upload-label { font-weight:700; color:var(--txt-dark); font-size:.9rem; margin-bottom:.25rem; }
.upload-sub   { color:var(--txt-light); font-size:.78rem; }

.images-preview {
  display:grid; grid-template-columns:repeat(auto-fill,minmax(100px,1fr));
  gap:.6rem; margin-top:1rem;
}
.preview-item {
  position:relative; border-radius:8px; overflow:hidden;
  aspect-ratio:1; background:var(--cream2); border:1.5px solid var(--cream3);
}
.preview-item img { width:100%; height:100%; object-fit:cover; }
.preview-remove {
  position:absolute; top:.3rem; right:.3rem;
  width:22px; height:22px; border-radius:50%;
  background:rgba(239,68,68,.9); border:none;
  color:#fff; font-size:.7rem; cursor:pointer;
  display:flex; align-items:center; justify-content:center;
  transition:transform .2s;
}
.preview-remove:hover { transform:scale(1.15); }
.preview-main-badge {
  position:absolute; bottom:.3rem; left:.3rem;
  background:var(--gold); color:var(--txt-dark);
  font-size:.6rem; font-weight:800;
  padding:.1rem .35rem; border-radius:3px;
}

/* ── Map picker ── */
#mapPicker {
  height:240px; border-radius:10px;
  border:1.5px solid var(--cream3);
  overflow:hidden; margin-top:.75rem;
}
.map-coords-row {
  display:grid; grid-template-columns:1fr 1fr; gap:.75rem; margin-top:.75rem;
}

/* ── Preview card (sidebar) ── */
.preview-card {
  background:#fff; border:1px solid rgba(212,175,55,.12);
  border-radius:var(--radius); overflow:hidden; box-shadow:var(--shadow);
}
.preview-card-img {
  height:190px; background:var(--cream2); position:relative; overflow:hidden;
}
.preview-card-img img {
  width:100%; height:100%; object-fit:cover; transition:transform .45s;
}
.preview-card-img:hover img { transform:scale(1.05); }
.preview-status-badge {
  position:absolute; top:.65rem; right:.65rem;
  padding:.22rem .6rem; border-radius:20px; font-size:.68rem; font-weight:700;
}
.pv-body { padding:1.2rem; }
.pv-type {
  display:inline-block; background:var(--gold-pale); color:var(--gold-dk);
  font-size:.65rem; font-weight:800; letter-spacing:.08em; text-transform:uppercase;
  padding:.15rem .5rem; border-radius:4px; margin-bottom:.5rem;
}
.pv-name  { font-weight:800; color:var(--txt-dark); font-size:1rem; margin-bottom:.25rem; }
.pv-city  { color:var(--txt-light); font-size:.78rem; margin-bottom:.75rem; }
.pv-price {
  font-family:'Playfair Display',serif; font-size:1.3rem;
  color:var(--txt-dark); font-weight:700;
}
.pv-price span { font-family:'DM Sans',sans-serif; font-size:.78rem; color:var(--txt-mid); font-weight:400; }

/* ── Mini 3D viewer ── */
.viewer-wrap {
  position:relative; height:200px;
  /* ✅ txt-dark au lieu de navy bg */
  background:var(--txt-dark);
  border-radius:10px; overflow:hidden; margin-top:1rem;
}
#miniCanvas3d { width:100%; height:100%; display:block; }
.viewer-label {
  position:absolute; bottom:.6rem; left:.7rem;
  background:rgba(0,0,0,.5); backdrop-filter:blur(4px);
  color:var(--gold); font-size:.68rem; font-weight:700;
  padding:.2rem .5rem; border-radius:4px;
}

/* ── Char counter ── */
.char-counter { font-size:.72rem; color:var(--txt-light); text-align:right; margin-top:.25rem; }
.char-counter.warn { color:var(--gold-dk); }

/* ── Submit area ── */
.submit-card {
  background:#fff; border:1px solid rgba(212,175,55,.12);
  border-radius:var(--radius); padding:1.5rem; box-shadow:var(--shadow);
  margin-top:1.5rem; display:flex; align-items:center;
  justify-content:space-between; gap:1rem; flex-wrap:wrap;
}
.submit-note { font-size:.8rem; color:var(--txt-light); max-width:400px; }
.submit-note strong { color:var(--txt-dark); display:block; margin-bottom:.2rem; font-size:.85rem; }
.submit-btns { display:flex; gap:.65rem; }

/* ✅ btn-navy = alias btn-gold en V13 (plus de navy bg) */
.btn-navy, .btn-gold {
  background:var(--gold); color:var(--txt-dark); border:none;
  border-radius:10px; padding:.65rem 1.5rem;
  font-family:'DM Sans',sans-serif; font-weight:700; font-size:.88rem;
  cursor:pointer; transition:all .2s;
  display:inline-flex; align-items:center; gap:.4rem;
}
.btn-navy:hover, .btn-gold:hover { background:var(--gold-dk); color:#fff; transform:translateY(-1px); }
.btn-navy:disabled, .btn-gold:disabled { opacity:.5; cursor:not-allowed; transform:none; }

.btn-outline {
  background:transparent; color:var(--txt-dark);
  border:1.5px solid var(--cream3); border-radius:10px;
  padding:.65rem 1.25rem; font-family:'DM Sans',sans-serif;
  font-weight:600; font-size:.88rem; cursor:pointer; transition:all .2s;
  display:inline-flex; align-items:center; gap:.4rem; text-decoration:none;
}
.btn-outline:hover { border-color:var(--gold); color:var(--gold-dk); }

/* ── Toast V13 ── */
.toast-ctr {
  position:fixed; bottom:2rem; right:2rem;
  z-index:99999; display:flex; flex-direction:column; gap:.5rem;
}
.toast {
  background:#fff; border-radius:12px; border:1px solid var(--cream3);
  padding:.8rem 1.2rem; box-shadow:0 8px 32px rgba(10,16,28,.12);
  font-size:.84rem; font-weight:600; color:var(--txt-dark);
  display:flex; align-items:center; gap:.6rem;
  transform:translateX(120%); transition:transform .35s cubic-bezier(.34,1.56,.64,1);
  min-width:240px;
}
.toast.show    { transform:translateX(0); }
.toast.success { border-left:4px solid var(--green); }
.toast.error   { border-left:4px solid var(--red); }
.toast.info    { border-left:4px solid var(--gold); }

/* ── Responsive ── */
@media(max-width:940px) {
  .create-grid { grid-template-columns:1fr; }
}
@media(max-width:560px) {
  .form-row-2, .form-row-3 { grid-template-columns:1fr; }
  .create-wrap { padding:1.5rem 1rem 4rem; }
}
</style>
@endpush

@section('content')

<div class="create-wrap">

  {{-- ── PAGE HEADER ── --}}
  <div class="page-header">
    <div class="page-header-left">
      <a href="/dashboard/owner" class="back-btn">← Retour</a>
      <div>
        <h1 class="page-title">
          @if(isset($editMode) && $editMode)
            Modifier la <span>Machine</span>
          @else
            Ajouter une <span>Machine</span>
          @endif
        </h1>
        <p class="page-sub">
          @if(isset($editMode) && $editMode)
            Mettez à jour les informations de votre machine
          @else
            Publiez votre engin sur Rentify et commencez à recevoir des demandes
          @endif
        </p>
      </div>
    </div>
    <div id="formProgressLabel"
         style="background:var(--gold-pale);border:1px solid rgba(212,175,55,.3);
                border-radius:9px;padding:.5rem 1rem;font-size:.8rem;font-weight:700;
                color:var(--gold-dk);display:flex;align-items:center;gap:.4rem;">
      <span id="progressPct">0%</span> complété
    </div>
  </div>

  {{-- ── STEPS BAR ── --}}
  <div class="steps-bar">
    <div class="step-item active" id="step1Indicator">
      <div class="step-dot">1</div>
      <span class="step-label">Informations</span>
    </div>
    <span class="step-arrow">›</span>
    <div class="step-item" id="step2Indicator">
      <div class="step-dot">2</div>
      <span class="step-label">Prix &amp; Statut</span>
    </div>
    <span class="step-arrow">›</span>
    <div class="step-item" id="step3Indicator">
      <div class="step-dot">3</div>
      <span class="step-label">Photos</span>
    </div>
    <span class="step-arrow">›</span>
    <div class="step-item" id="step4Indicator">
      <div class="step-dot">4</div>
      <span class="step-label">Localisation</span>
    </div>
  </div>

  {{-- ── MAIN GRID ── --}}
  <div class="create-grid">

    {{-- ══ LEFT COLUMN ══ --}}
    <div>

      {{-- SECTION 1 — INFOS --}}
      <div class="form-card" id="section1">
        <div class="form-card-header">
          <div class="form-card-icon">📋</div>
          <h3>Informations générales</h3>
        </div>
        <div class="form-card-body">

          <div class="form-group">
            <label class="form-label" for="fName">Nom de la machine <span>*</span></label>
            <input type="text" id="fName" class="form-control"
                   placeholder="Ex: Caterpillar 320 GX — Excavatrice hydraulique"
                   maxlength="120"
                   oninput="updatePreview(); updateProgress(); countChars('fName','nameCounter',120)">
            <div class="char-counter" id="nameCounter">0 / 120</div>
            <div class="field-error" id="errName">Ce champ est requis.</div>
          </div>

          <div class="form-group">
            <label class="form-label">Type de machine <span>*</span></label>
            <div class="type-selector" id="typeSelector">
              <div class="type-opt" data-type="excavatrice" onclick="selectType(this)">
                <span class="type-opt-icon">⛏</span>
                <span class="type-opt-label">Excavatrice</span>
              </div>
              <div class="type-opt" data-type="grue" onclick="selectType(this)">
                <span class="type-opt-icon">🏗</span>
                <span class="type-opt-label">Grue</span>
              </div>
              <div class="type-opt" data-type="bulldozer" onclick="selectType(this)">
                <span class="type-opt-icon">🚜</span>
                <span class="type-opt-label">Bulldozer</span>
              </div>
              <div class="type-opt" data-type="chargeuse" onclick="selectType(this)">
                <span class="type-opt-icon">🚛</span>
                <span class="type-opt-label">Chargeuse</span>
              </div>
              <div class="type-opt" data-type="compacteur" onclick="selectType(this)">
                <span class="type-opt-icon">🔧</span>
                <span class="type-opt-label">Compacteur</span>
              </div>
              <div class="type-opt" data-type="nacelle" onclick="selectType(this)">
                <span class="type-opt-icon">🪜</span>
                <span class="type-opt-label">Nacelle</span>
              </div>
              <div class="type-opt" data-type="tractopelle" onclick="selectType(this)">
                <span class="type-opt-icon">🚧</span>
                <span class="type-opt-label">Tractopelle</span>
              </div>
              <div class="type-opt" data-type="camion" onclick="selectType(this)">
                <span class="type-opt-icon">🚚</span>
                <span class="type-opt-label">Camion</span>
              </div>
            </div>
            <input type="hidden" id="fType">
            <div class="field-error" id="errType">Choisissez un type.</div>
          </div>

          <div class="form-group">
            <label class="form-label" for="fDescription">Description <span>*</span></label>
            <textarea id="fDescription" class="form-control" rows="4"
                      placeholder="Décrivez votre machine : marque, modèle, année, capacités, état, équipements inclus…"
                      maxlength="1500"
                      oninput="updateProgress(); countChars('fDescription','descCounter',1500)"></textarea>
            <div class="char-counter" id="descCounter">0 / 1500</div>
            <div class="field-error" id="errDescription">Description requise (min 20 caractères).</div>
          </div>

        </div>
      </div>

      {{-- SECTION 2 — PRIX & STATUT --}}
      <div class="form-card" id="section2" style="margin-top:1.25rem">
        <div class="form-card-header">
          <div class="form-card-icon">💰</div>
          <h3>Prix &amp; Disponibilité</h3>
        </div>
        <div class="form-card-body">

          <div class="form-row-2">
            <div class="form-group">
              <label class="form-label" for="fPriceDay">Prix / jour (MAD) <span>*</span></label>
              <input type="number" id="fPriceDay" class="form-control"
                     placeholder="Ex: 2500" min="0" step="50"
                     oninput="updatePreview(); updateProgress()">
              <div class="form-hint">Prix HT — sans frais de transport</div>
              <div class="field-error" id="errPriceDay">Prix journalier requis.</div>
            </div>
            <div class="form-group">
              <label class="form-label" for="fPriceHour">Prix / heure (MAD)</label>
              <input type="number" id="fPriceHour" class="form-control"
                     placeholder="Optionnel — Ex: 350" min="0" step="10"
                     oninput="updatePreview()">
              <div class="form-hint">Laisser vide si pas de tarif horaire</div>
            </div>
          </div>

          <div class="form-group" style="margin-top:.25rem">
            <label class="form-label">Statut <span>*</span></label>
            <div class="status-toggle" id="statusToggle">
              <button type="button" class="status-btn" data-status="available" onclick="selectStatus(this)">
                <span class="status-btn-icon">✅</span>
                <span class="status-btn-label">Disponible</span>
              </button>
              <button type="button" class="status-btn" data-status="unavailable" onclick="selectStatus(this)">
                <span class="status-btn-icon">❌</span>
                <span class="status-btn-label">Indisponible</span>
              </button>
              <button type="button" class="status-btn" data-status="maintenance" onclick="selectStatus(this)">
                <span class="status-btn-icon">🔧</span>
                <span class="status-btn-label">Maintenance</span>
              </button>
            </div>
            <input type="hidden" id="fStatus" value="available">
            <div class="field-error" id="errStatus">Choisissez un statut.</div>
          </div>

        </div>
      </div>

      {{-- SECTION 3 — PHOTOS --}}
      <div class="form-card" id="section3" style="margin-top:1.25rem">
        <div class="form-card-header">
          <div class="form-card-icon">📸</div>
          <h3>Photos de la machine</h3>
        </div>
        <div class="form-card-body">

          <div class="upload-zone" id="uploadZone"
               ondragover="handleDragOver(event)" ondragleave="handleDragLeave(event)"
               ondrop="handleDrop(event)">
            <input type="file" id="fImages" accept="image/*" multiple onchange="handleFiles(this.files)">
            <div class="upload-icon">📷</div>
            <div class="upload-label">Glissez vos photos ici ou cliquez pour parcourir</div>
            <div class="upload-sub">JPG, PNG, WebP — max 5 Mo par image — max 8 photos</div>
          </div>

          <div class="images-preview" id="imagesPreview"></div>

          <div id="existingImagesWrap" style="display:none;margin-top:1rem">
            <div style="font-size:.75rem;font-weight:800;color:var(--txt-light);
                        letter-spacing:.08em;text-transform:uppercase;margin-bottom:.6rem">
              Photos actuelles
            </div>
            <div class="images-preview" id="existingImagesGrid"></div>
          </div>

        </div>
      </div>

      {{-- SECTION 4 — LOCALISATION --}}
      <div class="form-card" id="section4" style="margin-top:1.25rem">
        <div class="form-card-header">
          <div class="form-card-icon">📍</div>
          <h3>Localisation</h3>
        </div>
        <div class="form-card-body">

          <div class="form-row-2">
            <div class="form-group">
              <label class="form-label" for="fCity">Ville <span>*</span></label>
              <input type="text" id="fCity" class="form-control"
                     placeholder="Ex: Casablanca"
                     oninput="updatePreview(); updateProgress()">
              <div class="field-error" id="errCity">Ville requise.</div>
            </div>
            <div class="form-group">
              <label class="form-label" for="fLocation">Adresse / Zone</label>
              <input type="text" id="fLocation" class="form-control"
                     placeholder="Ex: Zone industrielle Ain Sebaâ">
            </div>
          </div>

          <div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:.5rem">
            <label class="form-label" style="margin:0">Coordonnées GPS</label>
            <button type="button" onclick="locateMe()"
                    style="font-size:.75rem;font-weight:700;color:var(--gold-dk);
                           background:none;border:none;cursor:pointer;padding:0">
              📡 Ma position
            </button>
          </div>

          <div id="mapPicker"></div>
          <p style="font-size:.75rem;color:var(--txt-light);margin:.4rem 0 .75rem">
            Cliquez sur la carte pour placer le marqueur, ou entrez manuellement.
          </p>

          <div class="map-coords-row">
            <div class="form-group" style="margin:0">
              <label class="form-label" for="fLat">Latitude</label>
              <input type="number" id="fLat" class="form-control"
                     placeholder="Ex: 33.5731" step="0.0001" oninput="syncMapFromInputs()">
            </div>
            <div class="form-group" style="margin:0">
              <label class="form-label" for="fLng">Longitude</label>
              <input type="number" id="fLng" class="form-control"
                     placeholder="Ex: -7.5898" step="0.0001" oninput="syncMapFromInputs()">
            </div>
          </div>

        </div>
      </div>

    </div>

    {{-- ══ RIGHT COLUMN ══ --}}
    <div>

      {{-- PREVIEW CARD --}}
      <div class="form-card" style="position:sticky;top:80px">
        <div class="form-card-header">
          {{-- ✅ icon gold sur gold-pale = txt-dark text --}}
          <div class="form-card-icon">👁</div>
          <h3>Aperçu de la fiche</h3>
        </div>
        <div class="form-card-body" style="padding:0">

          <div class="preview-card">
            <div class="preview-card-img">
              <img id="pvImg" src="/images/img1.png" alt="preview">
              <span class="preview-status-badge status-available" id="pvStatus">✅ Disponible</span>
            </div>
            <div class="pv-body">
              <span class="pv-type" id="pvType">Type</span>
              <div class="pv-name" id="pvName">Nom de la machine</div>
              <div class="pv-city" id="pvCity">📍 Ville</div>
              <div class="pv-price" id="pvPrice">— <span>MAD/jour</span></div>
            </div>
          </div>

          {{-- Mini viewer 3D --}}
          <div style="padding:0 1.2rem 1.2rem">
            <div class="viewer-wrap" id="miniViewerWrap">
              <canvas id="miniCanvas3d"></canvas>
              <div class="viewer-label">⬡ Vue 3D — <span id="viewerTypeName">—</span></div>
            </div>
          </div>

        </div>
      </div>

      {{-- AIDE --}}
      <div style="background:var(--gold-pale);border:1px solid rgba(212,175,55,.25);
                  border-radius:var(--radius);padding:1.2rem;margin-top:1rem">
        <div style="font-size:.8rem;font-weight:800;color:var(--txt-dark);margin-bottom:.6rem">
          💡 Conseils pour une bonne annonce
        </div>
        <ul style="font-size:.78rem;color:var(--txt-mid);margin:0;padding-left:1.1rem;line-height:1.8">
          <li>Ajoutez au moins <strong>3 photos</strong> de qualité</li>
          <li>Précisez la <strong>marque et le modèle</strong> exact</li>
          <li>Indiquez l'<strong>année</strong> et les heures de service</li>
          <li>Mentionnez les <strong>équipements inclus</strong></li>
          <li>Un prix <strong>compétitif</strong> attire plus de demandes</li>
        </ul>
      </div>

    </div>

  </div>

  {{-- ── SUBMIT CARD ── --}}
  <div class="submit-card">
    <div class="submit-note">
      <strong>Prêt à publier ?</strong>
      Vérifiez que toutes les informations sont correctes avant de soumettre.
      Votre machine sera visible sur le catalogue dès validation.
    </div>
    <div class="submit-btns">
      <a href="/dashboard/owner" class="btn-outline">✕ Annuler</a>
      {{-- ✅ btn-navy = même style que btn-gold en V13 --}}
      <button type="button" class="btn-navy" id="submitBtn" onclick="submitForm()">
        <span id="submitBtnText">
          @if(isset($editMode) && $editMode)
            💾 Sauvegarder les modifications
          @else
            🚀 Publier la machine
          @endif
        </span>
      </button>
    </div>
  </div>

</div>

<div class="toast-ctr" id="toastCtr"></div>

@endsection

@push('scripts')
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css"/>
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/three.js/r128/three.min.js"></script>
<script>
/* ════════════════════════════════════════════════
   MACHINES CREATE / EDIT — RENTIFY V13
   ✅ Zéro navy — Palette 3D gold uniquement
════════════════════════════════════════════════ */

const EDIT_MODE  = {{ isset($editMode) && $editMode ? 'true' : 'false' }};
const MACHINE_ID = {{ isset($id) ? $id : 'null' }};

const TYPE_PHOTO = {
  excavatrice:'/images/img3.png', grue:'/images/img4.png',
  bulldozer:'/images/img1.png',   chargeuse:'/images/img2.png',
  compacteur:'/images/img8.png',  nacelle:'/images/img5.png',
  tractopelle:'/images/img7.png', camion:'/images/img9.png',
};

/* ✅ Palette 3D — gold uniquement, zéro orange/amber interdit */
const TYPE_COLORS = {
  excavatrice: 0xD4AF37,
  grue:        0xD4AF37,
  bulldozer:   0xD4AF37,
  chargeuse:   0x9A7D20,
  compacteur:  0xD4AF37,
  nacelle:     0x9A7D20,
  tractopelle: 0xD4AF37,
  camion:      0x9A7D20,
};

/* ── State ── */
let currentType     = '';
let currentStatus   = 'available';
let newImageFiles   = [];
let deletedImageIds = [];
let mapInstance     = null;
let mapMarker       = null;

// Three.js
let renderer3d, scene3d, camera3d, mesh3d, animId3d;
let isDragging3d = false, prevMouse3d = {x:0, y:0};

/* ══ INIT ══ */
document.addEventListener('DOMContentLoaded', async () => {
  const user = window.getUser ? window.getUser() : JSON.parse(localStorage.getItem('auth_user')||'null');
  if (!user || user.role !== 'owner') { window.location.href = '/login'; return; }

  initMap();
  initMiniViewer();
  selectStatus(document.querySelector('.status-btn[data-status="available"]'));

  if (EDIT_MODE && MACHINE_ID) await loadMachineData();

  updateProgress();
  observeSections();
});

/* ══ LOAD DATA (edit mode) ══ */
async function loadMachineData() {
  try {
    const data = await window.API.get(`/api/machines/${MACHINE_ID}`);
    const m    = data.machine || data;

    document.getElementById('fName').value        = m.name         || '';
    document.getElementById('fDescription').value = m.description  || '';
    document.getElementById('fPriceDay').value    = m.price_per_day  || '';
    document.getElementById('fPriceHour').value   = m.price_per_hour || '';
    document.getElementById('fCity').value        = m.city         || '';
    document.getElementById('fLocation').value    = m.location     || '';
    document.getElementById('fLat').value         = m.latitude     || '';
    document.getElementById('fLng').value         = m.longitude    || '';

    const typeEl = document.querySelector(`.type-opt[data-type="${(m.type||'').toLowerCase()}"]`);
    if (typeEl) selectType(typeEl);

    const statusEl = document.querySelector(`.status-btn[data-status="${m.status}"]`);
    if (statusEl) selectStatus(statusEl);

    if (m.images?.length) {
      document.getElementById('existingImagesWrap').style.display = 'block';
      document.getElementById('existingImagesGrid').innerHTML = m.images.map((img, i) => `
        <div class="preview-item" id="eimg-${img.id}">
          <img src="/storage/${img.path}" alt="photo ${i+1}">
          ${i===0 ? '<div class="preview-main-badge">Principal</div>' : ''}
          <button class="preview-remove" onclick="deleteExistingImage(${img.id})" title="Supprimer">✕</button>
        </div>
      `).join('');
      document.getElementById('pvImg').src = `/storage/${m.images[0].path}`;
    }

    if (m.latitude && m.longitude) syncMapToCoords(parseFloat(m.latitude), parseFloat(m.longitude));

    countChars('fName', 'nameCounter', 120);
    countChars('fDescription', 'descCounter', 1500);
    updatePreview();
    updateProgress();
  } catch(e) {
    console.error('loadMachineData', e);
    showToast('error', '❌ Erreur lors du chargement');
  }
}

/* ══ TYPE SELECTOR ══ */
function selectType(el) {
  document.querySelectorAll('.type-opt').forEach(o => o.classList.remove('selected'));
  el.classList.add('selected');
  currentType = el.dataset.type;
  document.getElementById('fType').value = currentType;
  updatePreview();
  update3dMesh();
  updateProgress();
  updateStepIndicator();
}

/* ══ STATUS TOGGLE ══ */
function selectStatus(el) {
  document.querySelectorAll('.status-btn').forEach(b => b.className = 'status-btn');
  const s = el.dataset.status;
  currentStatus = s;
  el.classList.add(`active-${s}`);
  document.getElementById('fStatus').value = s;

  const pvBadge = document.getElementById('pvStatus');
  if (pvBadge) {
    pvBadge.className = `preview-status-badge status-${s}`;
    const labels = { available:'✅ Disponible', unavailable:'❌ Indisponible', maintenance:'🔧 Maintenance' };
    pvBadge.textContent = labels[s];
  }
}

/* ══ PREVIEW UPDATE ══ */
function updatePreview() {
  const name  = document.getElementById('fName').value     || 'Nom de la machine';
  const city  = document.getElementById('fCity').value     || 'Ville';
  const price = document.getElementById('fPriceDay').value;

  document.getElementById('pvName').textContent = name;
  document.getElementById('pvCity').textContent = '📍 ' + city;
  document.getElementById('pvType').textContent = currentType || 'Type';
  document.getElementById('pvPrice').innerHTML  = price
    ? `${parseFloat(price).toLocaleString('fr-MA')} <span>MAD/jour</span>`
    : `— <span>MAD/jour</span>`;

  if (currentType && !newImageFiles.length) {
    document.getElementById('pvImg').src = TYPE_PHOTO[currentType] || '/images/img1.png';
  }
  if (newImageFiles.length) {
    const reader = new FileReader();
    reader.onload = e => document.getElementById('pvImg').src = e.target.result;
    reader.readAsDataURL(newImageFiles[0]);
  }
}

/* ══ CHAR COUNTER ══ */
function countChars(inputId, counterId, max) {
  const len = document.getElementById(inputId).value.length;
  const el  = document.getElementById(counterId);
  el.textContent = `${len} / ${max}`;
  el.classList.toggle('warn', len > max * 0.85);
}

/* ══ PROGRESS ══ */
function updateProgress() {
  const fields = [
    !!document.getElementById('fName').value.trim(),
    !!currentType,
    !!document.getElementById('fDescription').value.trim(),
    !!document.getElementById('fPriceDay').value,
    !!currentStatus,
    !!document.getElementById('fCity').value.trim(),
  ];
  const pct = Math.round(fields.filter(Boolean).length / fields.length * 100);
  document.getElementById('progressPct').textContent = pct + '%';
  updateStepIndicator();
}

function updateStepIndicator() {
  const s1 = !!(document.getElementById('fName').value && currentType && document.getElementById('fDescription').value);
  const s2 = !!(document.getElementById('fPriceDay').value && currentStatus);
  const s3 = newImageFiles.length > 0;
  const s4 = !!(document.getElementById('fCity').value);
  [1,2,3,4].forEach((n, i) => setStep(n, [s1,s2,s3,s4][i]));
}

function setStep(n, done) {
  const el = document.getElementById(`step${n}Indicator`);
  if (el) el.classList.toggle('done', done);
}

/* ══ SECTION OBSERVER ══ */
function observeSections() {
  const obs = new IntersectionObserver(entries => {
    entries.forEach(e => {
      if (e.isIntersecting) {
        const n = e.target.id.replace('section','');
        document.querySelectorAll('.step-item').forEach(s => s.classList.remove('active'));
        const si = document.getElementById(`step${n}Indicator`);
        if (si) si.classList.add('active');
      }
    });
  }, { threshold: 0.4 });
  [1,2,3,4].forEach(n => {
    const el = document.getElementById(`section${n}`);
    if (el) obs.observe(el);
  });
}

/* ══ IMAGE UPLOAD ══ */
function handleDragOver(e) {
  e.preventDefault();
  document.getElementById('uploadZone').classList.add('drag-over');
}
function handleDragLeave() {
  document.getElementById('uploadZone').classList.remove('drag-over');
}
function handleDrop(e) {
  e.preventDefault();
  document.getElementById('uploadZone').classList.remove('drag-over');
  handleFiles(e.dataTransfer.files);
}

function handleFiles(files) {
  const arr = Array.from(files).filter(f => f.type.startsWith('image/'));
  const remaining = 8 - newImageFiles.length;
  if (!remaining) { showToast('info', 'ℹ️ Maximum 8 photos atteint'); return; }

  arr.slice(0, remaining).forEach(file => {
    if (file.size > 5*1024*1024) {
      showToast('error', `❌ ${file.name} dépasse 5 Mo`); return;
    }
    newImageFiles.push(file);
    addPreviewItem(file, newImageFiles.length - 1);
    updateProgress();
    updateStepIndicator();
  });

  if (newImageFiles.length === 1) updatePreview();
}

function addPreviewItem(file, idx) {
  const grid = document.getElementById('imagesPreview');
  const item = document.createElement('div');
  item.className = 'preview-item';
  item.id = `nimg-${idx}`;

  const reader = new FileReader();
  reader.onload = e => {
    item.innerHTML = `
      <img src="${e.target.result}" alt="">
      ${idx===0 && !EDIT_MODE ? '<div class="preview-main-badge">Principal</div>' : ''}
      <button class="preview-remove" onclick="removeNewImage(${idx})" title="Supprimer">✕</button>
    `;
    if (idx===0) document.getElementById('pvImg').src = e.target.result;
  };
  reader.readAsDataURL(file);
  grid.appendChild(item);
}

function removeNewImage(idx) {
  newImageFiles.splice(idx, 1);
  const el = document.getElementById(`nimg-${idx}`);
  if (el) el.remove();
  const grid = document.getElementById('imagesPreview');
  [...grid.children].forEach((c, i) => {
    c.id = `nimg-${i}`;
    const btn = c.querySelector('.preview-remove');
    if (btn) btn.setAttribute('onclick', `removeNewImage(${i})`);
  });
  updateProgress();
  updateStepIndicator();
}

function deleteExistingImage(imageId) {
  deletedImageIds.push(imageId);
  const el = document.getElementById(`eimg-${imageId}`);
  if (el) { el.style.opacity = '.3'; el.style.pointerEvents = 'none'; }
  showToast('info', '🗑 Photo marquée pour suppression');
}

/* ══ MAP ══ */
function initMap() {
  mapInstance = L.map('mapPicker').setView([33.5731, -7.5898], 8);
  L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
    attribution: '© OpenStreetMap'
  }).addTo(mapInstance);

  mapInstance.on('click', e => {
    const { lat, lng } = e.latlng;
    placeMapMarker(lat, lng);
    document.getElementById('fLat').value = lat.toFixed(6);
    document.getElementById('fLng').value = lng.toFixed(6);
  });
}

function placeMapMarker(lat, lng) {
  if (mapMarker) mapMarker.remove();
  mapMarker = L.marker([lat, lng], {
    icon: L.divIcon({
      className: '',
      /* ✅ border txt-dark au lieu de #0F1B2D navy */
      html: '<div style="background:#D4AF37;width:16px;height:16px;border-radius:50%;border:3px solid #1a1a2e;box-shadow:0 2px 8px rgba(0,0,0,.3)"></div>',
      iconAnchor: [8, 8],
    })
  }).addTo(mapInstance);
}

function syncMapFromInputs() {
  const lat = parseFloat(document.getElementById('fLat').value);
  const lng = parseFloat(document.getElementById('fLng').value);
  if (!isNaN(lat) && !isNaN(lng)) syncMapToCoords(lat, lng);
}

function syncMapToCoords(lat, lng) {
  mapInstance.setView([lat, lng], 12);
  placeMapMarker(lat, lng);
}

function locateMe() {
  if (!navigator.geolocation) { showToast('error', '❌ Géolocalisation non supportée'); return; }
  navigator.geolocation.getCurrentPosition(pos => {
    const { latitude: lat, longitude: lng } = pos.coords;
    document.getElementById('fLat').value = lat.toFixed(6);
    document.getElementById('fLng').value = lng.toFixed(6);
    syncMapToCoords(lat, lng);
    showToast('success', '📡 Position détectée !');
  }, () => { showToast('error', '❌ Impossible d\'accéder à votre position'); });
}

/* ══ MINI 3D VIEWER ══ */
function initMiniViewer() {
  const canvas = document.getElementById('miniCanvas3d');
  const wrap   = document.getElementById('miniViewerWrap');

  renderer3d = new THREE.WebGLRenderer({ canvas, antialias: true, alpha: true });
  renderer3d.setPixelRatio(Math.min(window.devicePixelRatio, 2));
  renderer3d.setSize(wrap.clientWidth || 320, 200);
  /* ✅ txt-dark (#1a1a2e) au lieu de navy (#0F1B2D) */
  renderer3d.setClearColor(0x1a1a2e, 1);

  scene3d  = new THREE.Scene();
  camera3d = new THREE.PerspectiveCamera(55, (wrap.clientWidth||320)/200, 0.1, 100);
  camera3d.position.set(0, 1.2, 3.5);

  const ambLight = new THREE.AmbientLight(0xffffff, 0.6);
  const dirLight = new THREE.DirectionalLight(0xD4AF37, 1.2);
  dirLight.position.set(3, 4, 3);
  scene3d.add(ambLight, dirLight);

  buildMesh3d('bulldozer');

  canvas.addEventListener('mousedown', e => {
    isDragging3d = true; prevMouse3d = {x:e.clientX, y:e.clientY};
  });
  window.addEventListener('mouseup',   () => isDragging3d = false);
  window.addEventListener('mousemove', e => {
    if (!isDragging3d || !mesh3d) return;
    mesh3d.rotation.y += (e.clientX - prevMouse3d.x) * 0.012;
    mesh3d.rotation.x += (e.clientY - prevMouse3d.y) * 0.008;
    prevMouse3d = {x:e.clientX, y:e.clientY};
  });

  (function animate3d() {
    animId3d = requestAnimationFrame(animate3d);
    if (mesh3d && !isDragging3d) mesh3d.rotation.y += 0.008;
    renderer3d.render(scene3d, camera3d);
  })();
}

function buildMesh3d(type) {
  if (mesh3d) {
    scene3d.remove(mesh3d);
    mesh3d.geometry?.dispose();
    mesh3d.material?.dispose();
  }

  /* ✅ Palette gold uniquement */
  const color = TYPE_COLORS[type] || 0xD4AF37;
  const mat   = new THREE.MeshStandardMaterial({ color, roughness:.4, metalness:.7 });
  let geo;

  switch(type) {
    case 'excavatrice':
    case 'tractopelle': geo = new THREE.BoxGeometry(1.8, 0.8, 1.1); break;
    case 'grue':        geo = new THREE.CylinderGeometry(0.2, 0.2, 3, 8); break;
    case 'bulldozer':
    case 'chargeuse':   geo = new THREE.BoxGeometry(2, 1, 1.3); break;
    case 'compacteur':  geo = new THREE.CylinderGeometry(0.7, 0.7, 1.4, 12); break;
    case 'nacelle':     geo = new THREE.BoxGeometry(0.9, 2.5, 0.9); break;
    case 'camion':      geo = new THREE.BoxGeometry(2.4, 1, 1.2); break;
    default:            geo = new THREE.BoxGeometry(1.5, 1, 1);
  }

  mesh3d = new THREE.Mesh(geo, mat);
  scene3d.add(mesh3d);

  const grid = new THREE.GridHelper(6, 10, 0x2a2a3a, 0x2a2a3a);
  grid.position.y = -0.9; grid.name = 'grid';
  scene3d.children.filter(c => c.name==='grid').forEach(c => scene3d.remove(c));
  scene3d.add(grid);
}

function update3dMesh() {
  if (!scene3d) return;
  buildMesh3d(currentType || 'bulldozer');
  document.getElementById('viewerTypeName').textContent =
    currentType ? currentType.charAt(0).toUpperCase() + currentType.slice(1) : '—';
}

/* ══ VALIDATION ══ */
function validateForm() {
  let ok = true;
  const checks = [
    { id:'fName',        errId:'errName',       test: v => v.trim().length >= 3,  msg:'Nom requis (min 3 caractères).' },
    { id:'fType',        errId:'errType',        test: v => !!v,                   msg:'Choisissez un type.' },
    { id:'fDescription', errId:'errDescription', test: v => v.trim().length >= 20, msg:'Description trop courte (min 20 caractères).' },
    { id:'fPriceDay',    errId:'errPriceDay',    test: v => parseFloat(v) > 0,     msg:'Prix journalier requis.' },
    { id:'fCity',        errId:'errCity',        test: v => v.trim().length >= 2,  msg:'Ville requise.' },
  ];
  checks.forEach(c => {
    const val   = document.getElementById(c.id)?.value || '';
    const errEl = document.getElementById(c.errId);
    const ctrl  = document.getElementById(c.id);
    if (!c.test(val)) {
      ok = false;
      if (errEl) { errEl.textContent = c.msg; errEl.classList.add('show'); }
      if (ctrl)  ctrl.classList.add('error');
    } else {
      if (errEl) errEl.classList.remove('show');
      if (ctrl)  ctrl.classList.remove('error');
    }
  });
  return ok;
}

/* ══ SUBMIT ══ */
async function submitForm() {
  if (!validateForm()) {
    showToast('error', '⚠️ Veuillez corriger les erreurs');
    const first = document.querySelector('.form-control.error');
    if (first) first.scrollIntoView({ behavior:'smooth', block:'center' });
    return;
  }

  const btn    = document.getElementById('submitBtn');
  const btnTxt = document.getElementById('submitBtnText');
  btn.disabled = true;
  btnTxt.textContent = '⏳ Envoi en cours…';

  try {
    const payload = {
      name:          document.getElementById('fName').value.trim(),
      type:          document.getElementById('fType').value,
      description:   document.getElementById('fDescription').value.trim(),
      price_per_day: parseFloat(document.getElementById('fPriceDay').value),
      price_per_hour: document.getElementById('fPriceHour').value
                     ? parseFloat(document.getElementById('fPriceHour').value) : null,
      status:    document.getElementById('fStatus').value,
      city:      document.getElementById('fCity').value.trim(),
      location:  document.getElementById('fLocation').value.trim(),
      latitude:  document.getElementById('fLat').value  || null,
      longitude: document.getElementById('fLng').value  || null,
    };

    let machineId = MACHINE_ID;
    let res;

    if (EDIT_MODE && machineId) {
      res = await window.API.put(`/api/machines/${machineId}`, payload);
    } else {
      res = await window.API.post('/api/machines', payload);
      if (res.ok) machineId = res.data?.machine?.id || res.data?.id;
    }

    if (!res.ok) {
      const msg = res.data?.message || Object.values(res.data?.errors||{}).flat()[0] || 'Erreur';
      showToast('error', '❌ ' + msg);
      btn.disabled = false;
      btnTxt.textContent = EDIT_MODE ? '💾 Sauvegarder les modifications' : '🚀 Publier la machine';
      return;
    }

    if (newImageFiles.length && machineId) {
      const fd = new FormData();
      newImageFiles.forEach((f, i) => fd.append(`images[${i}]`, f));
      await fetch(`/api/machines/${machineId}/images`, {
        method: 'POST',
        headers: { 'Authorization': `Bearer ${window.getToken()}` },
        body: fd,
      });
    }

    if (deletedImageIds.length && EDIT_MODE) {
      await Promise.all(deletedImageIds.map(imgId =>
        window.API.delete(`/api/machines/${machineId}/images/${imgId}`)
      ));
    }

    showToast('success', EDIT_MODE ? '✅ Machine mise à jour !' : '🎉 Machine publiée avec succès !');
    setTimeout(() => { window.location.href = '/dashboard/owner'; }, 1800);

  } catch(e) {
    console.error('submitForm', e);
    showToast('error', '❌ Erreur réseau. Réessayez.');
    btn.disabled = false;
    btnTxt.textContent = EDIT_MODE ? '💾 Sauvegarder les modifications' : '🚀 Publier la machine';
  }
}

/* ══ TOAST ══ */
function showToast(type, msg) {
  const ctr = document.getElementById('toastCtr');
  const el  = document.createElement('div');
  el.className = `toast ${type}`;
  el.textContent = msg;
  ctr.appendChild(el);
  requestAnimationFrame(() => el.classList.add('show'));
  setTimeout(() => { el.classList.remove('show'); setTimeout(() => el.remove(), 400); }, 3800);
}

window.addEventListener('beforeunload', () => {
  if (animId3d) cancelAnimationFrame(animId3d);
  renderer3d?.dispose();
});
</script>
@endpush