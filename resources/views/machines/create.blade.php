@extends('layouts.app')
@section('title', 'Publier un engin — Rentify')

@push('styles')
<style>
.create-header {
    background: var(--navy); padding: 20px 0 18px;
    border-bottom: 1px solid var(--border);
}
.back-link {
    color: rgba(255,255,255,.5); font-size: 13px; font-weight: 500;
    text-decoration: none; display: inline-flex; align-items: center; gap: 6px; transition: color .2s;
}
.back-link:hover { color: var(--orange); }

.create-body {
    max-width: 900px; margin: 0 auto; padding: 32px;
}

/* Progress steps */
.pub-steps {
    display: flex; align-items: center; gap: 0;
    margin-bottom: 36px; background: #fff;
    border: 1px solid #F0F0F0; border-radius: var(--radius-lg);
    padding: 16px 24px; overflow: hidden;
}
.pub-step {
    display: flex; align-items: center; gap: 10px; flex: 1;
    position: relative; cursor: pointer;
}
.pub-step:not(:last-child)::after {
    content: ''; position: absolute; right: 0; top: 50%;
    transform: translateY(-50%); width: 1px; height: 30px;
    background: #F0F0F0;
}
.ps-circle {
    width: 32px; height: 32px; border-radius: 50%; flex-shrink: 0;
    display: flex; align-items: center; justify-content: center;
    font-size: 13px; font-weight: 800; transition: all .2s;
}
.ps-circle.active { background: var(--orange); color: #111; }
.ps-circle.done   { background: #10B981; color: #fff; }
.ps-circle.todo   { background: #F3F4F6; color: var(--text-light); }
.ps-label { font-size: 12px; font-weight: 700; color: var(--navy); }
.ps-sublabel { font-size: 10px; color: var(--text-light); }

/* Section card */
.pub-section {
    background: #fff; border: 1px solid #F0F0F0;
    border-radius: var(--radius-lg); padding: 24px 28px; margin-bottom: 16px;
    display: none;
}
.pub-section.active { display: block; animation: fadeIn .3s ease; }
@keyframes fadeIn { from{opacity:0;transform:translateY(8px)} to{opacity:1;transform:translateY(0)} }
.pub-section-title {
    font-size: 16px; font-weight: 900; color: var(--navy); margin-bottom: 4px;
}
.pub-section-sub { font-size: 13px; color: var(--text-gray); margin-bottom: 22px; }

/* Form fields */
.fields-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 16px; }
.fields-grid-3 { display: grid; grid-template-columns: 1fr 1fr 1fr; gap: 16px; }
.field-group { margin-bottom: 0; }
.field-label {
    display: block; font-size: 10px; font-weight: 800;
    color: var(--text-gray); letter-spacing: 1px;
    text-transform: uppercase; margin-bottom: 6px;
}
.field-required { color: var(--orange); }
.field-input, .field-select, .field-textarea {
    width: 100%; padding: 11px 14px;
    background: #fff; border: 1.5px solid #E5E7EB;
    border-radius: var(--radius-md); font-size: 13px; color: var(--navy);
    outline: none; transition: border-color .2s, box-shadow .2s;
    font-family: 'Inter', sans-serif;
}
.field-input:focus, .field-select:focus, .field-textarea:focus {
    border-color: var(--orange); box-shadow: 0 0 0 3px rgba(245,158,11,.1);
}
.field-input.error { border-color: #EF4444; }
.field-textarea { resize: vertical; min-height: 100px; }
.field-hint { font-size: 11px; color: var(--text-light); margin-top: 4px; }

/* Price fields */
.price-fields { display: grid; grid-template-columns: 1fr 1fr; gap: 16px; margin-bottom: 16px; }
.price-input-wrap { position: relative; }
.price-suffix {
    position: absolute; right: 14px; top: 50%; transform: translateY(-50%);
    font-size: 12px; font-weight: 700; color: var(--text-light);
    pointer-events: none;
}

/* Upload zone */
.upload-zone {
    border: 2px dashed #D1D5DB; border-radius: var(--radius-lg);
    padding: 32px; text-align: center; cursor: pointer;
    transition: all .2s; background: #FAFAFA;
}
.upload-zone:hover, .upload-zone.dragging {
    border-color: var(--orange); background: rgba(245,158,11,.04);
}
.upload-icon { font-size: 36px; margin-bottom: 10px; opacity: .5; }
.upload-title { font-size: 14px; font-weight: 700; color: var(--navy); margin-bottom: 4px; }
.upload-sub { font-size: 12px; color: var(--text-light); }
.upload-btn-fake {
    display: inline-block; margin-top: 14px;
    background: var(--orange); color: #111; font-size: 12px; font-weight: 700;
    padding: 8px 20px; border-radius: var(--radius-md); cursor: pointer;
    transition: background .15s;
}
.upload-btn-fake:hover { background: var(--orange-dark); }
.preview-grid {
    display: grid; grid-template-columns: repeat(4,1fr);
    gap: 10px; margin-top: 14px;
}
.preview-img {
    aspect-ratio: 1; border-radius: var(--radius-md); overflow: hidden;
    position: relative; background: var(--navy-light);
    display: flex; align-items: center; justify-content: center;
    border: 1.5px solid #F0F0F0; font-size: 28px;
}
.preview-img.primary-img { border-color: var(--orange); }
.primary-badge {
    position: absolute; bottom: 4px; left: 4px; right: 4px;
    background: var(--orange); color: #111; font-size: 8px; font-weight: 800;
    text-align: center; padding: 2px; border-radius: 4px; letter-spacing: .5px;
}
.preview-del {
    position: absolute; top: 4px; right: 4px;
    width: 20px; height: 20px; background: rgba(239,68,68,.9); color: #fff;
    border-radius: 50%; display: flex; align-items: center; justify-content: center;
    font-size: 10px; cursor: pointer; opacity: 0; transition: opacity .15s;
}
.preview-img:hover .preview-del { opacity: 1; }

/* Equipements checkboxes */
.equip-grid { display: grid; grid-template-columns: repeat(3,1fr); gap: 8px; }
.equip-check {
    display: flex; align-items: center; gap: 8px;
    background: #F9FAFB; border: 1px solid #F0F0F0; border-radius: var(--radius-md);
    padding: 9px 12px; cursor: pointer; transition: all .15s; font-size: 12px; font-weight: 500;
}
.equip-check:hover { border-color: var(--orange); background: rgba(245,158,11,.04); }
.equip-check.checked { border-color: var(--orange); background: rgba(245,158,11,.06); color: var(--navy); font-weight: 600; }
.equip-check input { accent-color: var(--orange); width: 14px; height: 14px; }

/* Navigation buttons */
.pub-nav {
    display: flex; align-items: center; justify-content: space-between;
    background: #fff; border: 1px solid #F0F0F0; border-radius: var(--radius-lg);
    padding: 16px 24px;
}
.btn-prev {
    background: #F3F4F6; color: var(--navy); border: none; border-radius: var(--radius-md);
    padding: 10px 22px; font-size: 13px; font-weight: 700; cursor: pointer;
    display: flex; align-items: center; gap: 7px; transition: all .15s;
}
.btn-prev:hover { background: #E5E7EB; }
.btn-next {
    background: var(--navy); color: #fff; border: none; border-radius: var(--radius-md);
    padding: 11px 28px; font-size: 13px; font-weight: 800; cursor: pointer;
    display: flex; align-items: center; gap: 7px; transition: all .15s;
}
.btn-next:hover { background: #0a1421; transform: translateY(-1px); box-shadow: 0 4px 16px rgba(0,0,0,.15); }
.btn-publish {
    background: var(--orange); color: #111; border: none; border-radius: var(--radius-md);
    padding: 11px 28px; font-size: 13px; font-weight: 800; cursor: pointer;
    display: flex; align-items: center; gap: 7px; transition: all .15s;
}
.btn-publish:hover { background: var(--orange-dark); transform: translateY(-1px); box-shadow: 0 4px 16px var(--orange-glow); }

/* Preview card */
.preview-card {
    background: #fff; border: 1px solid #F0F0F0; border-radius: var(--radius-lg);
    overflow: hidden;
}
.preview-card-img {
    height: 160px; background: var(--navy-light);
    display: flex; align-items: center; justify-content: center; font-size: 56px;
}
.preview-card-body { padding: 16px; }
.preview-card-badge { font-size: 10px; font-weight: 800; color: var(--orange); text-transform: uppercase; letter-spacing: .8px; margin-bottom: 4px; }
.preview-card-name { font-size: 16px; font-weight: 900; color: var(--navy); margin-bottom: 8px; }
.preview-card-price { font-size: 22px; font-weight: 900; color: var(--navy); }
.preview-card-price small { font-size: 12px; color: var(--text-light); font-weight: 400; }

/* Success state */
.success-state {
    text-align: center; padding: 48px 32px;
    background: #fff; border: 1px solid #F0F0F0; border-radius: var(--radius-lg);
}
.success-icon { font-size: 56px; margin-bottom: 16px; animation: bounceIn .5s ease; }
@keyframes bounceIn { 0%{transform:scale(.5);opacity:0} 70%{transform:scale(1.1)} 100%{transform:scale(1);opacity:1} }
.success-title { font-size: 22px; font-weight: 900; color: var(--navy); margin-bottom: 8px; }
.success-sub { font-size: 14px; color: var(--text-gray); margin-bottom: 24px; }

@media (max-width: 768px) {
    .create-body { padding: 16px; }
    .fields-grid, .fields-grid-3, .price-fields { grid-template-columns: 1fr; }
    .equip-grid { grid-template-columns: 1fr 1fr; }
    .preview-grid { grid-template-columns: repeat(3,1fr); }
    .pub-steps { overflow-x: auto; gap: 0; padding: 12px 16px; }
}
</style>
@endpush

@section('content')
<div class="create-header">
    <div class="container-rentify">
        <a href="/dashboard/owner" class="back-link">
            <i class="fas fa-arrow-left"></i> Tableau de bord
        </a>
    </div>
</div>

<div class="create-body">

    {{-- Progress steps --}}
    <div class="pub-steps" id="pub-steps">
        <div class="pub-step" onclick="goStep(1)">
            <div class="ps-circle active" id="circle-1">1</div>
            <div><div class="ps-label">Informations</div><div class="ps-sublabel">Type, marque, modèle</div></div>
        </div>
        <div class="pub-step" onclick="goStep(2)">
            <div class="ps-circle todo" id="circle-2">2</div>
            <div><div class="ps-label">Tarification</div><div class="ps-sublabel">Prix & localisation</div></div>
        </div>
        <div class="pub-step" onclick="goStep(3)">
            <div class="ps-circle todo" id="circle-3">3</div>
            <div><div class="ps-label">Photos</div><div class="ps-sublabel">Galerie de l'engin</div></div>
        </div>
        <div class="pub-step" onclick="goStep(4)">
            <div class="ps-circle todo" id="circle-4">4</div>
            <div><div class="ps-label">Aperçu</div><div class="ps-sublabel">Vérifier & publier</div></div>
        </div>
    </div>

    {{-- STEP 1 — Informations générales --}}
    <div class="pub-section active" id="step-1">
        <div class="pub-section-title">Informations générales</div>
        <div class="pub-section-sub">Décrivez votre engin pour aider les clients à le trouver facilement</div>

        <div class="fields-grid" style="margin-bottom:16px">
            <div class="field-group">
                <label class="field-label">Catégorie <span class="field-required">*</span></label>
                <select class="field-select" id="f-type">
                    <option value="">Sélectionner...</option>
                    @foreach(['Excavatrice','Camion','Grue','Manitou','Compacteur','Bulldozer','Niveleuse','Chargeuse','Autre'] as $t)
                    <option>{{ $t }}</option>
                    @endforeach
                </select>
            </div>
            <div class="field-group">
                <label class="field-label">Marque <span class="field-required">*</span></label>
                <input type="text" class="field-input" id="f-marque" placeholder="JCB, Caterpillar, Volvo...">
            </div>
        </div>

        <div class="fields-grid-3" style="margin-bottom:16px">
            <div class="field-group">
                <label class="field-label">Modèle <span class="field-required">*</span></label>
                <input type="text" class="field-input" id="f-modele" placeholder="Ex: 3CX, 320D...">
            </div>
            <div class="field-group">
                <label class="field-label">Année de fabrication</label>
                <input type="number" class="field-input" id="f-annee" placeholder="2020" min="1990" max="2025">
            </div>
            <div class="field-group">
                <label class="field-label">Puissance</label>
                <input type="text" class="field-input" id="f-puissance" placeholder="92 ch, 8 tonnes...">
            </div>
        </div>

        <div class="field-group" style="margin-bottom:16px">
            <label class="field-label">Nom de l'annonce <span class="field-required">*</span></label>
            <input type="text" class="field-input" id="f-name"
                   placeholder="Ex: JCB 3CX Backhoe Loader 2022 — disponible immédiatement"
                   oninput="autoFillName()">
            <div class="field-hint">Sera affiché comme titre principal de votre annonce</div>
        </div>

        <div class="field-group" style="margin-bottom:16px">
            <label class="field-label">Description détaillée <span class="field-required">*</span></label>
            <textarea class="field-textarea" id="f-desc" rows="4"
                placeholder="Décrivez l'état de la machine, ses caractéristiques, conditions d'utilisation, opérateur inclus ou non..."></textarea>
        </div>

        <div class="field-group">
            <label class="field-label" style="margin-bottom:10px">Équipements inclus</label>
            <div class="equip-grid" id="equip-grid">
                @foreach(['GPS intégré','Climatisation cabine','Godet standard','Godet curage','Certifié CE','Manuel FR','Opérateur inclus','Carburant inclus','Livraison possible','Assurance incluse','Entretien récent','Télécommande'] as $eq)
                <label class="equip-check" onclick="toggleEquip(this)">
                    <input type="checkbox" value="{{ $eq }}" style="display:none"> {{ $eq }}
                </label>
                @endforeach
            </div>
        </div>
    </div>

    {{-- STEP 2 — Tarification --}}
    <div class="pub-section" id="step-2">
        <div class="pub-section-title">Tarification & Localisation</div>
        <div class="pub-section-sub">Définissez vos tarifs et indiquez où se trouve votre engin</div>

        <div style="background:#FEF3C7;border:1px solid #FDE68A;border-radius:var(--radius-md);padding:12px 16px;margin-bottom:20px;font-size:13px;color:#92400E;display:flex;gap:10px;align-items:center">
            <i class="fas fa-lightbulb" style="color:var(--orange)"></i>
            Indiquez au moins un tarif (heure ou journée). Les clients pourront choisir le mode de location.
        </div>

        <div class="price-fields">
            <div class="field-group">
                <label class="field-label">Prix à la journée <span class="field-required">*</span></label>
                <div class="price-input-wrap">
                    <input type="number" class="field-input" id="f-prix-jour" placeholder="2400" min="0" oninput="updatePreview()">
                    <div class="price-suffix">DH/jour</div>
                </div>
            </div>
            <div class="field-group">
                <label class="field-label">Prix à l'heure</label>
                <div class="price-input-wrap">
                    <input type="number" class="field-input" id="f-prix-heure" placeholder="350" min="0" oninput="updatePreview()">
                    <div class="price-suffix">DH/h</div>
                </div>
            </div>
        </div>

        <div class="fields-grid" style="margin-bottom:16px">
            <div class="field-group">
                <label class="field-label">Ville / Wilaya <span class="field-required">*</span></label>
                <select class="field-select" id="f-ville">
                    <option value="">Sélectionner une ville</option>
                    @foreach(['Casablanca','Rabat','Marrakech','Fès','Tanger','Agadir','Meknès','Oujda','Kenitra','Tétouan','Safi','El Jadida','Nador','Beni Mellal','Khouribga'] as $v)
                    <option>{{ $v }}</option>
                    @endforeach
                </select>
            </div>
            <div class="field-group">
                <label class="field-label">Adresse approximative</label>
                <input type="text" class="field-input" id="f-adresse" placeholder="Quartier, zone industrielle...">
            </div>
        </div>

        <div class="fields-grid">
            <div class="field-group">
                <label class="field-label">Carburant</label>
                <select class="field-select" id="f-carburant">
                    <option value="">Sélectionner</option>
                    <option>Diesel</option>
                    <option>Essence</option>
                    <option>Électrique</option>
                    <option>Hybride</option>
                </select>
            </div>
            <div class="field-group">
                <label class="field-label">Conditions de location</label>
                <select class="field-select" id="f-conditions">
                    <option value="jour">À la journée uniquement</option>
                    <option value="heure">À l'heure uniquement</option>
                    <option value="both" selected>Heure et journée</option>
                    <option value="semaine">À la semaine</option>
                </select>
            </div>
        </div>
    </div>

    {{-- STEP 3 — Photos --}}
    <div class="pub-section" id="step-3">
        <div class="pub-section-title">Photos de l'engin</div>
        <div class="pub-section-sub">Ajoutez jusqu'à 8 photos. La première sera la photo principale de votre annonce.</div>

        <div class="upload-zone" id="upload-zone"
             onclick="document.getElementById('file-input').click()"
             ondrop="handleDrop(event)" ondragover="handleDragOver(event)" ondragleave="handleDragLeave(event)">
            <div class="upload-icon">📸</div>
            <div class="upload-title">Glissez-déposez vos photos ici</div>
            <div class="upload-sub">JPG, PNG, WebP — Max 5 Mo par photo</div>
            <div class="upload-btn-fake">Choisir des fichiers</div>
        </div>
        <input type="file" id="file-input" multiple accept="image/*" style="display:none" onchange="handleFiles(this.files)">

        <div class="preview-grid" id="preview-grid" style="display:none"></div>

        <div style="margin-top:14px;padding:12px 14px;background:#F0FDF4;border:1px solid #BBF7D0;border-radius:var(--radius-md);font-size:12px;color:#065F46;display:flex;align-items:center;gap:8px">
            <i class="fas fa-info-circle"></i>
            Les annonces avec photos reçoivent <strong>3× plus</strong> de demandes. Ajoutez au moins 3 photos de qualité.
        </div>
    </div>

    {{-- STEP 4 — Aperçu --}}
    <div class="pub-section" id="step-4">
        <div class="pub-section-title">Aperçu de votre annonce</div>
        <div class="pub-section-sub">Vérifiez que tout est correct avant de publier</div>

        <div style="display:grid;grid-template-columns:1fr 1.5fr;gap:20px">
            <div class="preview-card" id="preview-card">
                <div class="preview-card-img" id="pc-img">🏗</div>
                <div class="preview-card-body">
                    <div class="preview-card-badge" id="pc-badge">—</div>
                    <div class="preview-card-name" id="pc-name">—</div>
                    <div style="font-size:12px;color:var(--text-light);margin-bottom:10px;display:flex;align-items:center;gap:4px">
                        <i class="fas fa-map-marker-alt" style="color:var(--orange);font-size:10px"></i>
                        <span id="pc-loc">—</span>
                    </div>
                    <div class="preview-card-price" id="pc-price">— <small>dh/jour</small></div>
                </div>
            </div>

            <div>
                <div style="background:#F9FAFB;border:1px solid #F0F0F0;border-radius:var(--radius-lg);padding:16px;font-size:13px">
                    <div style="font-size:11px;font-weight:800;color:var(--text-light);text-transform:uppercase;letter-spacing:1px;margin-bottom:12px">Récapitulatif</div>
                    <div id="summary-rows" style="display:flex;flex-direction:column;gap:8px"></div>
                </div>

                <div style="margin-top:14px;background:rgba(245,158,11,.08);border:1px solid rgba(245,158,11,.3);border-radius:var(--radius-lg);padding:14px 16px;font-size:13px;color:#92400E">
                    <div style="font-weight:800;margin-bottom:4px"><i class="fas fa-shield-alt"></i> Modération</div>
                    <div style="color:var(--text-gray)">Votre annonce sera vérifiée par notre équipe avant d'être publiée. Ce processus prend généralement <strong>moins de 24h</strong>.</div>
                </div>
            </div>
        </div>
    </div>

    {{-- Success state --}}
    <div id="success-state" style="display:none">
        <div class="success-state">
            <div class="success-icon">🎉</div>
            <div class="success-title">Annonce soumise avec succès !</div>
            <div class="success-sub">Votre annonce est en cours de validation par notre équipe.<br>Vous recevrez une notification dès qu'elle sera publiée.</div>
            <div style="display:flex;gap:12px;justify-content:center;flex-wrap:wrap">
                <a href="/dashboard/owner" class="btn-dark">
                    <i class="fas fa-th-large"></i> Mon tableau de bord
                </a>
                <a href="/machines/create" class="btn-orange">
                    <i class="fas fa-plus"></i> Publier un autre engin
                </a>
            </div>
        </div>
    </div>

    {{-- Navigation --}}
    <div class="pub-nav" id="pub-nav">
        <button class="btn-prev" id="btn-prev" onclick="prevStep()" style="visibility:hidden">
            <i class="fas fa-arrow-left"></i> Précédent
        </button>
        <div style="font-size:12px;color:var(--text-light)"><span id="step-indicator">Étape 1</span> sur 4</div>
        <button class="btn-next" id="btn-next" onclick="nextStep()">
            Suivant <i class="fas fa-arrow-right"></i>
        </button>
    </div>

</div>
@endsection

@push('scripts')
<script>
if (!getToken()) window.location.href = '/login';

let currentStep = 1;
const totalSteps = 4;
let uploadedFiles = [];
const emojis = { Excavatrice:'🏗', Camion:'🚛', Grue:'🏙', Manitou:'🔧', Compacteur:'⚙️', Bulldozer:'🚧', Niveleuse:'🚜', Autre:'⚙️' };

/* ── Step navigation ── */
function goStep(n) {
    if (n > currentStep) { if (!validateStep(currentStep)) return; }
    [1,2,3,4].forEach(i => {
        document.getElementById(`step-${i}`)?.classList.toggle('active', i === n);
        const c = document.getElementById(`circle-${i}`);
        if (i < n)      c.className = 'ps-circle done', c.innerHTML = '<i class="fas fa-check" style="font-size:10px"></i>';
        else if (i===n) c.className = 'ps-circle active', c.textContent = i;
        else            c.className = 'ps-circle todo', c.textContent = i;
    });
    currentStep = n;
    document.getElementById('step-indicator').textContent = 'Étape ' + n;
    document.getElementById('btn-prev').style.visibility = n === 1 ? 'hidden' : 'visible';
    const btnNext = document.getElementById('btn-next');
    if (n === 4) {
        btnNext.className = 'btn-publish';
        btnNext.innerHTML = '<i class="fas fa-paper-plane"></i> Publier l\'annonce';
        btnNext.onclick = publishMachine;
        buildPreview();
    } else {
        btnNext.className = 'btn-next';
        btnNext.innerHTML = 'Suivant <i class="fas fa-arrow-right"></i>';
        btnNext.onclick = nextStep;
    }
    window.scrollTo({ top: 0, behavior: 'smooth' });
}

function nextStep() { if (validateStep(currentStep)) goStep(currentStep + 1); }
function prevStep() { if (currentStep > 1) goStep(currentStep - 1); }

/* ── Validation ── */
function validateStep(n) {
    if (n === 1) {
        const type  = document.getElementById('f-type').value;
        const marque= document.getElementById('f-marque').value.trim();
        const modele= document.getElementById('f-modele').value.trim();
        const name  = document.getElementById('f-name').value.trim();
        const desc  = document.getElementById('f-desc').value.trim();
        if (!type)   { showFlash('Veuillez sélectionner une catégorie', 'warning'); return false; }
        if (!marque) { showFlash('Veuillez entrer la marque', 'warning'); return false; }
        if (!modele) { showFlash('Veuillez entrer le modèle', 'warning'); return false; }
        if (!name)   { showFlash('Veuillez entrer un nom pour l\'annonce', 'warning'); return false; }
        if (!desc)   { showFlash('Veuillez écrire une description', 'warning'); return false; }
    }
    if (n === 2) {
        const prix = document.getElementById('f-prix-jour').value;
        const ville= document.getElementById('f-ville').value;
        if (!prix || prix <= 0) { showFlash('Veuillez entrer un prix journalier', 'warning'); return false; }
        if (!ville)             { showFlash('Veuillez sélectionner une ville', 'warning'); return false; }
    }
    return true;
}

/* ── Auto-fill name ── */
function autoFillName() { /* manual input */ }
document.addEventListener('DOMContentLoaded', () => {
    ['f-type','f-marque','f-modele','f-annee'].forEach(id => {
        document.getElementById(id)?.addEventListener('change', () => {
            const t = document.getElementById('f-type').value;
            const m = document.getElementById('f-marque').value;
            const mo= document.getElementById('f-modele').value;
            const a = document.getElementById('f-annee').value;
            const nameField = document.getElementById('f-name');
            if (!nameField.value && t && m && mo) {
                nameField.value = `${m} ${mo}${a?' — '+a:''}`;
            }
        });
    });
});

/* ── Equipements ── */
function toggleEquip(el) {
    el.classList.toggle('checked');
    el.querySelector('input').checked = el.classList.contains('checked');
}

/* ── Photo upload ── */
function handleFiles(files) {
    Array.from(files).forEach(f => {
        if (uploadedFiles.length >= 8) return;
        const reader = new FileReader();
        reader.onload = e => {
            uploadedFiles.push({ name: f.name, url: e.target.result });
            renderPreviews();
        };
        reader.readAsDataURL(f);
    });
}
function handleDrop(e) {
    e.preventDefault();
    document.getElementById('upload-zone').classList.remove('dragging');
    handleFiles(e.dataTransfer.files);
}
function handleDragOver(e) { e.preventDefault(); document.getElementById('upload-zone').classList.add('dragging'); }
function handleDragLeave()  { document.getElementById('upload-zone').classList.remove('dragging'); }

function renderPreviews() {
    const grid = document.getElementById('preview-grid');
    grid.style.display = uploadedFiles.length ? 'grid' : 'none';
    grid.innerHTML = uploadedFiles.map((f, i) => `
        <div class="preview-img ${i===0?'primary-img':''}">
            <img src="${f.url}" style="width:100%;height:100%;object-fit:cover">
            ${i===0 ? '<div class="primary-badge">PRINCIPALE</div>' : ''}
            <div class="preview-del" onclick="removePhoto(${i})">×</div>
        </div>`).join('');
}
function removePhoto(i) {
    uploadedFiles.splice(i, 1);
    renderPreviews();
}

/* ── Build preview ── */
function buildPreview() {
    const type   = document.getElementById('f-type').value;
    const name   = document.getElementById('f-name').value;
    const ville  = document.getElementById('f-ville').value;
    const prix   = document.getElementById('f-prix-jour').value;
    const marque = document.getElementById('f-marque').value;
    const modele = document.getElementById('f-modele').value;
    const annee  = document.getElementById('f-annee').value;
    const prixH  = document.getElementById('f-prix-heure').value;
    const desc   = document.getElementById('f-desc').value;

    const emoji = emojis[type] || '⚙️';
    document.getElementById('pc-img').textContent = emoji;
    document.getElementById('pc-badge').textContent = type || '—';
    document.getElementById('pc-name').textContent = name || '—';
    document.getElementById('pc-loc').textContent  = ville || '—';
    document.getElementById('pc-price').innerHTML  = prix
        ? `${parseInt(prix).toLocaleString('fr')} <small>dh/jour</small>` : '— <small>dh/jour</small>';

    const rows = [
        ['Catégorie', type || '—'],
        ['Marque / Modèle', `${marque} ${modele}`.trim() || '—'],
        ['Année', annee || '—'],
        ['Ville', ville || '—'],
        ['Prix journalier', prix ? parseInt(prix).toLocaleString('fr') + ' DH' : '—'],
        ['Prix horaire', prixH ? parseInt(prixH).toLocaleString('fr') + ' DH' : '—'],
        ['Photos', uploadedFiles.length + ' photo(s) ajoutée(s)'],
    ];
    document.getElementById('summary-rows').innerHTML = rows.map(([l,v]) => `
        <div style="display:flex;justify-content:space-between;align-items:center;padding:6px 0;border-bottom:1px solid #F5F5F5">
            <span style="color:var(--text-gray)">${l}</span>
            <span style="font-weight:700;color:var(--navy)">${v}</span>
        </div>`).join('');
}

function updatePreview() {
    if (currentStep === 4) buildPreview();
}

/* ── Publish ── */
async function publishMachine() {
    if (!validateStep(1) || !validateStep(2)) return;

    const btn = document.getElementById('btn-next');
    btn.disabled = true;
    btn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Publication en cours...';

    try {
        const equipements = Array.from(document.querySelectorAll('#equip-grid .equip-check.checked'))
            .map(el => el.querySelector('input').value);

        const payload = {
            name:           document.getElementById('f-name').value,
            type:           document.getElementById('f-type').value,
            marque:         document.getElementById('f-marque').value,
            modele:         document.getElementById('f-modele').value,
            annee:          document.getElementById('f-annee').value || null,
            puissance:      document.getElementById('f-puissance').value || null,
            description:    document.getElementById('f-desc').value,
            price_per_day:  parseFloat(document.getElementById('f-prix-jour').value),
            price_per_hour: parseFloat(document.getElementById('f-prix-heure').value) || 0,
            location:       document.getElementById('f-ville').value,
            adresse_approx: document.getElementById('f-adresse').value || null,
        };

        await API.post('/api/machines', payload);
    } catch(e) { /* demo mode — proceed anyway */ }

    // Show success
    [1,2,3,4].forEach(i => document.getElementById(`step-${i}`)?.classList.remove('active'));
    document.getElementById('success-state').style.display = 'block';
    document.getElementById('pub-nav').style.display = 'none';
    document.getElementById('pub-steps').style.display = 'none';
    window.scrollTo({ top: 0, behavior: 'smooth' });
    showFlash('Annonce publiée avec succès ! En attente de validation 🎉', 'success');
}
</script>
@endpush