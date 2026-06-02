@extends('layouts.app')

@push('styles')
{{-- Cropper.js CSS --}}
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.6.2/cropper.min.css">
<style>
:root {
  --gold:#D4AF37; --gold-dk:#9A7D20; --gold-pale:#FEF9E7;
  --gold-glow:rgba(212,175,55,.25);
  --cream:#FAF7F0; --cream2:#F0EBE0; --cream3:#E8DDD0;
  --txt-dark:#1a1a2e; --txt-mid:#5a5660; --txt-light:#9992a4;
  --green:#22c55e; --red:#ef4444;
  --radius:14px; --shadow:0 4px 24px rgba(15,27,45,.08);
}

.profile-body {
  max-width: 900px; margin: 2rem auto; padding: 0 1rem;
}

/* ── Header profil ── */
.profile-header {
  background: var(--gold-pale);
  border: 1.5px solid rgba(212,175,55,.3);
  border-radius: 16px;
  padding: 2rem;
  display: flex; align-items: center; gap: 1.5rem;
  margin-bottom: 2rem;
  box-shadow: var(--shadow);
}
.avatar-wrap { position: relative; width: 90px; height: 90px; flex-shrink: 0; }
.avatar-wrap img {
  width: 90px; height: 90px; border-radius: 50%;
  object-fit: cover; border: 3px solid var(--gold);
  background: var(--cream2);
}
.avatar-edit {
  position: absolute; bottom: 0; right: 0;
  background: var(--gold); border: none; border-radius: 50%;
  width: 28px; height: 28px; cursor: pointer;
  color: var(--txt-dark); font-size: .8rem;
  display: flex; align-items: center; justify-content: center;
  transition: background .2s;
}
.avatar-edit:hover { background: var(--gold-dk); color: #fff; }

.profile-header-info h4 {
  color: var(--txt-dark); font-weight: 700; margin: 0; font-size: 1.2rem;
}
.profile-header-info .role-badge {
  display: inline-block; margin-top: .3rem;
  background: rgba(212,175,55,.2); color: var(--gold-dk);
  font-size: .72rem; font-weight: 700; letter-spacing: .08em;
  padding: .2rem .75rem; border-radius: 20px; text-transform: uppercase;
  border: 1px solid rgba(212,175,55,.35);
}
.profile-header-info .city {
  color: var(--txt-mid); font-size: .85rem; margin-top: .35rem;
}

/* ── Tabs ── */
.profile-tabs {
  display: flex; gap: .25rem;
  border-bottom: 2px solid var(--cream3);
  margin-bottom: 1.5rem;
}
.profile-tab-btn {
  padding: .65rem 1.25rem; background: none; border: none;
  border-bottom: 3px solid transparent; margin-bottom: -2px;
  font-family: 'DM Sans', sans-serif; font-size: .88rem; font-weight: 600;
  color: var(--txt-mid); cursor: pointer; transition: all .2s;
  border-radius: 8px 8px 0 0;
}
.profile-tab-btn:hover { color: var(--txt-dark); background: var(--cream2); }
.profile-tab-btn.active {
  color: var(--gold-dk);
  border-bottom-color: var(--gold);
  background: var(--gold-pale);
}

/* ── Cards ── */
.card-profile {
  background: #fff;
  border: 1px solid rgba(212,175,55,.15);
  border-radius: var(--radius);
  padding: 2rem;
  box-shadow: var(--shadow);
}
.card-profile h5 {
  font-family: 'Playfair Display', serif;
  color: var(--txt-dark); font-size: 1.1rem; margin-bottom: 1.25rem;
  padding-bottom: .75rem;
  border-bottom: 1.5px solid var(--cream3);
}

/* ── Form controls ── */
.form-label {
  display: block; font-size: .8rem; font-weight: 700;
  color: var(--txt-dark); margin-bottom: .4rem; letter-spacing: .03em;
}
.form-control {
  width: 100%; box-sizing: border-box;
  border: 1.5px solid var(--cream3); border-radius: 10px;
  padding: .6rem .9rem; font-family: 'DM Sans', sans-serif;
  font-size: .88rem; color: var(--txt-dark); background: #fff;
  transition: border .2s; outline: none;
}
.form-control:focus { border-color: var(--gold); box-shadow: 0 0 0 3px var(--gold-glow); }
.form-control[readonly] { background: var(--cream2); color: var(--txt-mid); cursor: default; }
.form-control::placeholder { color: var(--txt-light); }
textarea.form-control { resize: vertical; min-height: 90px; }

/* ── Grid form ── */
.form-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 1rem; }
.form-grid .col-full { grid-column: 1 / -1; }
@media (max-width: 600px) { .form-grid { grid-template-columns: 1fr; } }

/* ── Boutons ── */
.btn-gold {
  background: var(--gold); color: var(--txt-dark);
  border: 2px solid var(--gold); border-radius: 10px;
  padding: .55rem 1.4rem; font-family: 'DM Sans', sans-serif;
  font-weight: 700; font-size: .88rem; cursor: pointer;
  transition: all .2s; display: inline-flex; align-items: center; gap: .5rem;
}
.btn-gold:hover { background: var(--gold-dk); border-color: var(--gold-dk); color: #fff; }
.btn-outline {
  background: #fff; color: var(--gold-dk);
  border: 2px solid var(--gold); border-radius: 10px;
  padding: .55rem 1.4rem; font-family: 'DM Sans', sans-serif;
  font-weight: 700; font-size: .88rem; cursor: pointer;
  transition: all .2s; display: inline-flex; align-items: center; gap: .5rem;
}
.btn-outline:hover { background: var(--gold-pale); }
.btn-danger-outline {
  background: #fff; color: var(--red);
  border: 2px solid var(--red); border-radius: 10px;
  padding: .55rem 1.4rem; font-family: 'DM Sans', sans-serif;
  font-weight: 700; font-size: .88rem; cursor: pointer;
  transition: all .2s; display: inline-flex; align-items: center; gap: .5rem;
}
.btn-danger-outline:hover { background: #fee2e2; }

/* ── Historique table ── */
.history-table { width: 100%; border-collapse: collapse; font-size: .85rem; }
.history-table thead tr {
  background: var(--cream);
  border-bottom: 2px solid var(--cream3);
}
.history-table th {
  padding: .75rem 1rem; text-align: left; font-weight: 700;
  color: var(--txt-dark); font-size: .78rem; letter-spacing: .05em;
  text-transform: uppercase;
}
.history-table td {
  padding: .75rem 1rem; color: var(--txt-mid);
  border-bottom: 1px solid var(--cream2); vertical-align: middle;
}
.history-table tbody tr:hover td { background: var(--cream); }

.badge-status {
  padding: .25rem .7rem; border-radius: 20px;
  font-size: .72rem; font-weight: 700; letter-spacing: .04em;
}
.badge-pending     { background: #FEF9E7; color: var(--gold-dk); }
.badge-accepted    { background: #dcfce7; color: #15803d; }
.badge-completed   { background: #dcfce7; color: #15803d; }
.badge-rejected    { background: #fee2e2; color: #b91c1c; }
.badge-cancelled   { background: var(--cream3); color: var(--txt-mid); }

/* ── Loading spinner ── */
.spin {
  display: inline-block; width: 18px; height: 18px;
  border: 2px solid var(--cream3); border-top-color: var(--gold);
  border-radius: 50%; animation: spin .7s linear infinite;
}
@keyframes spin { to { transform: rotate(360deg); } }

/* ── Toast ── */
.toast-container {
  position: fixed; bottom: 2rem; right: 2rem; z-index: 99999;
  display: flex; flex-direction: column; gap: .6rem;
}
.toast-v13 {
  display: flex; align-items: center; gap: .75rem;
  background: #fff; border: 1px solid var(--cream3); border-radius: 12px;
  padding: .85rem 1.25rem; font-size: .85rem; font-weight: 600;
  color: var(--txt-dark);
  box-shadow: 0 8px 32px rgba(10,16,28,.15);
  transform: translateX(120%); transition: transform .35s cubic-bezier(.34,1.56,.64,1);
  min-width: 240px; max-width: 320px;
}
.toast-v13.show    { transform: translateX(0); }
.toast-v13.success { border-left: 4px solid var(--green); }
.toast-v13.error   { border-left: 4px solid var(--red); }
.toast-v13.info    { border-left: 4px solid var(--gold); }

/* ════════════════════════════════════
   CROP MODAL
════════════════════════════════════ */
#cropModal {
  display: none;
  position: fixed; inset: 0; z-index: 9999;
  background: rgba(15,27,45,.65);
  backdrop-filter: blur(4px);
  align-items: center; justify-content: center;
}
#cropModal.open { display: flex; }

.crop-dialog {
  background: #fff;
  border-radius: 20px;
  box-shadow: 0 24px 80px rgba(15,27,45,.25);
  width: min(520px, 94vw);
  overflow: hidden;
  animation: cropIn .28s cubic-bezier(.34,1.4,.64,1);
}
@keyframes cropIn {
  from { transform: scale(.88); opacity: 0; }
  to   { transform: scale(1);   opacity: 1; }
}

.crop-dialog-header {
  display: flex; align-items: center; justify-content: space-between;
  padding: 1.1rem 1.5rem;
  background: var(--gold-pale);
  border-bottom: 1.5px solid rgba(212,175,55,.3);
}
.crop-dialog-header h6 {
  margin: 0; font-family: 'Playfair Display', serif;
  font-size: 1rem; color: var(--txt-dark); font-weight: 700;
}
.crop-close {
  background: none; border: none; cursor: pointer;
  color: var(--txt-mid); font-size: 1.1rem; padding: .2rem .4rem;
  border-radius: 6px; transition: background .15s;
}
.crop-close:hover { background: var(--cream2); }

.crop-dialog-body {
  padding: 1.25rem 1.5rem;
  background: var(--cream);
}
.crop-preview-wrap {
  width: 100%; max-height: 300px; overflow: hidden;
  border-radius: 12px; background: var(--cream2);
}
.crop-preview-wrap img {
  display: block; max-width: 100%;
}

.crop-hint {
  margin-top: .75rem; text-align: center;
  font-size: .78rem; color: var(--txt-light);
}

.crop-dialog-footer {
  display: flex; gap: .75rem; justify-content: flex-end;
  padding: 1rem 1.5rem;
  border-top: 1.5px solid var(--cream3);
}
</style>
@endpush

@section('content')
<div class="profile-body" id="profileApp" style="display:none">

  {{-- ── Header profil ── --}}
  <div class="profile-header">
    <div class="avatar-wrap">
      <img id="avatarImg" src="" alt="avatar">
      <button class="avatar-edit"
              onclick="document.getElementById('avatarInput').click()"
              title="Changer la photo">
        <i class="fas fa-camera"></i>
      </button>
      <input type="file" id="avatarInput" accept="image/jpeg,image/png,image/webp" hidden>
    </div>
    <div class="profile-header-info">
      <h4 id="headerName">—</h4>
      <span class="role-badge" id="headerRole"></span>
      <div class="city" id="headerCity"></div>
    </div>
  </div>

  {{-- ── Tabs ── --}}
  <div class="profile-tabs">
    <button class="profile-tab-btn active" onclick="showTab('infos', this)">
      <i class="fas fa-user"></i> Informations
    </button>
    <button class="profile-tab-btn" onclick="showTab('security', this)">
      <i class="fas fa-lock"></i> Sécurité
    </button>
    <button class="profile-tab-btn" onclick="showTab('history', this)">
      <i class="fas fa-history"></i> Historique
    </button>
  </div>

  {{-- ── Tab Infos ── --}}
  <div id="tab-infos" class="card-profile">
    <h5><i class="fas fa-user-circle me-2" style="color:var(--gold)"></i>Mes informations</h5>
    <div class="form-grid">
      <div>
        <label class="form-label">Nom complet</label>
        <input type="text" id="inp_name" class="form-control" placeholder="Votre nom">
      </div>
      <div>
        <label class="form-label">Email <small style="color:var(--txt-light);font-weight:400">(non modifiable)</small></label>
        <input type="email" id="inp_email" class="form-control" readonly>
      </div>
      <div>
        <label class="form-label">Téléphone</label>
        <input type="text" id="inp_phone" class="form-control" placeholder="+212...">
      </div>
      <div>
        <label class="form-label">Ville</label>
        <input type="text" id="inp_city" class="form-control" placeholder="Casablanca">
      </div>
      <div class="col-full">
        <label class="form-label">Bio</label>
        <textarea id="inp_bio" class="form-control"
                  placeholder="Quelques mots sur vous…"></textarea>
      </div>
    </div>
    <button class="btn-gold mt-3" onclick="saveProfile()" id="btnSave">
      <i class="fas fa-save"></i> Enregistrer
    </button>
  </div>

  {{-- ── Tab Sécurité ── --}}
  <div id="tab-security" class="card-profile" style="display:none">
    <h5><i class="fas fa-lock me-2" style="color:var(--gold)"></i>Changer le mot de passe</h5>
    <div style="max-width:420px">
      <div style="margin-bottom:1rem">
        <label class="form-label">Mot de passe actuel</label>
        <input type="password" id="inp_current" class="form-control" placeholder="••••••••">
      </div>
      <div style="margin-bottom:1rem">
        <label class="form-label">Nouveau mot de passe <small style="color:var(--txt-light);font-weight:400">(min. 8 caractères)</small></label>
        <input type="password" id="inp_new" class="form-control" placeholder="••••••••">
      </div>
      <div style="margin-bottom:1.25rem">
        <label class="form-label">Confirmer le nouveau mot de passe</label>
        <input type="password" id="inp_confirm" class="form-control" placeholder="••••••••">
      </div>
      <button class="btn-danger-outline" onclick="changePassword()" id="btnPwd">
        <i class="fas fa-key"></i> Modifier le mot de passe
      </button>
    </div>
  </div>

  {{-- ── Tab Historique ── --}}
  <div id="tab-history" class="card-profile" style="display:none">
    <h5><i class="fas fa-history me-2" style="color:var(--gold)"></i>Historique des réservations</h5>
    <div id="historyList">
      <div style="text-align:center;padding:2rem;color:var(--txt-light)">
        <div class="spin"></div>
      </div>
    </div>
  </div>

</div>

{{-- ════════════════════════════════════
     CROP MODAL
════════════════════════════════════ --}}
<div id="cropModal" role="dialog" aria-modal="true" aria-labelledby="cropModalTitle">
  <div class="crop-dialog">
    <div class="crop-dialog-header">
      <h6 id="cropModalTitle">
        <i class="fas fa-crop-alt" style="color:var(--gold-dk);margin-right:.5rem"></i>
        Ajuster la photo de profil
      </h6>
      <button class="crop-close" onclick="closeCropModal()" title="Annuler">
        <i class="fas fa-times"></i>
      </button>
    </div>
    <div class="crop-dialog-body">
      <div class="crop-preview-wrap">
        <img id="cropImage" src="" alt="Image à recadrer">
      </div>
      <p class="crop-hint">
        <i class="fas fa-arrows-alt" style="margin-right:.3rem"></i>
        Faites glisser pour repositionner · Pincez ou molette pour zoomer
      </p>
    </div>
    <div class="crop-dialog-footer">
      <button class="btn-outline" onclick="closeCropModal()">
        <i class="fas fa-times"></i> Annuler
      </button>
      <button class="btn-gold" onclick="confirmCrop()" id="btnConfirmCrop">
        <i class="fas fa-check"></i> Confirmer
      </button>
    </div>
  </div>
</div>

<div class="toast-container" id="toastContainer"></div>
@endsection

@push('scripts')
{{-- Cropper.js --}}
<script src="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.6.2/cropper.min.js"></script>
<script>
(function() {
/* ══════════════════════════════════════════
    PROFILE — RENTIFY V17
    ✅ FIX password : bouton disabled pendant envoi + clear fields après succès
    ✅ FIX avatar   : Cropper.js modal avant upload + resize canvas 400×400
    ✅ FIX crop     : closeCropModal() destroy() proprement
══════════════════════════════════════════ */

/* ── Helpers localStorage ── */
function _readUser()  { try { return JSON.parse(localStorage.getItem('auth_user') || 'null'); } catch(e) { return null; } }
function _saveUser(u) { localStorage.setItem('auth_user', JSON.stringify(u)); }
function _token()     { return localStorage.getItem('auth_token') || ''; }
function _photo(u)    { return (u && (u.profile_photo_path || u.avatar)) || null; }

/* ── Affiche l'avatar ── */
function _renderAvatar(photoPath, name) {
  var img = document.getElementById('avatarImg');
  if (photoPath) {
    img.src = '/storage/' + photoPath + '?t=' + Date.now();
    img.onerror = function() {
      img.src = _avatarFallback(name);
      img.onerror = null;
    };
  } else {
    img.src = _avatarFallback(name);
  }
}

function _avatarFallback(name) {
  return 'https://ui-avatars.com/api/?name=' + encodeURIComponent(name || 'U')
       + '&background=D4AF37&color=1a1a2e&size=90&bold=true';
}

/* ── Met à jour le header ── */
function _renderHeader(u) {
  document.getElementById('headerName').textContent = u.name  || '—';
  document.getElementById('headerRole').textContent = u.role  || '';
  document.getElementById('headerCity').textContent = u.city  ? '📍 ' + u.city : '';
}

/* ══ GUARD ══ */
var _user = _readUser();
if (!_user) {
  window.location.replace('/login');
} else {
  document.getElementById('profileApp').style.display = 'block';
  loadProfile();
}

/* ══ LOAD PROFILE ══ */
async function loadProfile() {
  _renderHeader(_user);
  _renderAvatar(_photo(_user), _user.name);

  try {
    var r = await fetch('/api/profile', {
      headers: {
        'Authorization': 'Bearer ' + _token(),
        'Accept': 'application/json'
      }
    });

    if (r.status === 401) {
      localStorage.removeItem('auth_token');
      localStorage.removeItem('auth_user');
      window.location.replace('/login');
      return;
    }

    var json  = await r.json();
    var fresh = (json && json.data) ? json.data : (json && json.id ? json : null);
    if (!fresh) throw new Error('Réponse API invalide');

    if (!fresh.profile_photo_path && _photo(_user)) {
      fresh.profile_photo_path = _photo(_user);
    }

    document.getElementById('inp_name').value  = fresh.name  || '';
    document.getElementById('inp_email').value = fresh.email || '';
    document.getElementById('inp_phone').value = fresh.phone || '';
    document.getElementById('inp_city').value  = fresh.city  || '';
    document.getElementById('inp_bio').value   = fresh.bio   || '';

    _renderHeader(fresh);
    _renderAvatar(_photo(fresh), fresh.name);
    _saveUser(fresh);
    _user = fresh;

  } catch(e) {
    console.error('loadProfile error:', e);
    document.getElementById('inp_name').value  = _user.name  || '';
    document.getElementById('inp_email').value = _user.email || '';
    document.getElementById('inp_phone').value = _user.phone || '';
    document.getElementById('inp_city').value  = _user.city  || '';
    document.getElementById('inp_bio').value   = _user.bio   || '';
  }

  loadHistory();

  /* ✅ addEventListener UNE SEULE FOIS */
  var avatarInput = document.getElementById('avatarInput');
  if (avatarInput && !avatarInput._bound) {
    avatarInput._bound = true;
    avatarInput.addEventListener('change', function() {
      onAvatarFileSelected(this);
    });
  }
}

/* ══ SAVE PROFILE ══ */
async function saveProfile() {
  var name  = document.getElementById('inp_name').value.trim();
  var phone = document.getElementById('inp_phone').value.trim();
  var city  = document.getElementById('inp_city').value.trim();
  var bio   = document.getElementById('inp_bio').value.trim();

  if (!name) { showToast('Le nom est obligatoire', 'error'); return; }

  var btn = document.getElementById('btnSave');
  btn.disabled = true;
  btn.innerHTML = '<span class="spin"></span> Enregistrement…';

  try {
    var r = await fetch('/api/profile', {
      method: 'PUT',
      headers: {
        'Content-Type':  'application/json',
        'Authorization': 'Bearer ' + _token(),
        'Accept':        'application/json'
      },
      body: JSON.stringify({ name: name, phone: phone, city: city, bio: bio })
    });
    var json = await r.json();

    if (r.ok) {
      var updated = Object.assign({}, _user, { name: name, phone: phone, city: city, bio: bio });
      _saveUser(updated);
      _user = updated;
      _renderHeader(_user);
      showToast('Profil mis à jour avec succès ✓', 'success');
    } else {
      var errMsg = (json.errors && Object.values(json.errors)[0])
                 ? Object.values(json.errors)[0][0]
                 : (json.message || 'Erreur lors de la sauvegarde');
      showToast(errMsg, 'error');
    }
  } catch(e) {
    showToast('Erreur réseau', 'error');
  } finally {
    btn.disabled = false;
    btn.innerHTML = '<i class="fas fa-save"></i> Enregistrer';
  }
}

/* ══════════════════════════════════════════
   CROP MODAL — LOGIQUE COMPLÈTE
══════════════════════════════════════════ */
var _cropper = null;

/* Étape 1 : l'utilisateur choisit un fichier → on ouvre le modal */
function onAvatarFileSelected(input) {
  var file = (input.files && input.files[0]) ? input.files[0] : null;
  /* ✅ Reset l'input immédiatement pour éviter re-trigger */
  input.value = '';

  if (!file) return;

  /* Validation type */
  var allowed = ['image/jpeg', 'image/jpg', 'image/png', 'image/webp'];
  if (!allowed.includes(file.type)) {
    showToast('Format non supporté — JPG, PNG ou WebP uniquement', 'error');
    return;
  }

  /* Validation taille (max 5 Mo avant crop, sera réduit après) */
  if (file.size > 5 * 1024 * 1024) {
    showToast('Image trop grande — max 5 Mo', 'error');
    return;
  }

  /* Lire le fichier et ouvrir le modal */
  var reader = new FileReader();
  reader.onload = function(e) {
    openCropModal(e.target.result);
  };
  reader.readAsDataURL(file);
}

/* Étape 2 : Ouvrir le modal avec Cropper.js */
function openCropModal(dataUrl) {
  var modal = document.getElementById('cropModal');
  var img   = document.getElementById('cropImage');

  /* Détruire ancien cropper si existe */
  if (_cropper) {
    _cropper.destroy();
    _cropper = null;
  }

  img.src = dataUrl;
  modal.classList.add('open');
  document.body.style.overflow = 'hidden';

  /* Init Cropper.js après que l'image soit chargée */
  img.onload = function() {
    _cropper = new Cropper(img, {
      aspectRatio: 1,          /* ✅ Carré obligatoire pour avatar rond */
      viewMode: 1,              /* Pas de dépassement hors image */
      dragMode: 'move',
      autoCropArea: 0.85,
      restore: false,
      guides: true,
      center: true,
      highlight: false,
      cropBoxMovable: true,
      cropBoxResizable: true,
      toggleDragModeOnDblclick: false,
    });
  };
}

/* Étape 3 : L'utilisateur clique "Confirmer" */
async function confirmCrop() {
  if (!_cropper) return;

  var btn = document.getElementById('btnConfirmCrop');
  btn.disabled = true;
  btn.innerHTML = '<span class="spin"></span> Upload…';

  try {
    /* ✅ Générer canvas 400×400 (taille optimale pour avatar) */
    var canvas = _cropper.getCroppedCanvas({
      width:  400,
      height: 400,
      imageSmoothingEnabled: true,
      imageSmoothingQuality: 'high',
    });

    /* ✅ Convertir canvas → Blob (JPEG qualité 85%) */
    canvas.toBlob(async function(blob) {
      if (!blob) {
        showToast('Erreur lors du traitement de l\'image', 'error');
        btn.disabled = false;
        btn.innerHTML = '<i class="fas fa-check"></i> Confirmer';
        return;
      }

      /* Vérification taille finale (max 2 Mo) */
      if (blob.size > 2 * 1024 * 1024) {
        showToast('Image trop grande après recadrage — max 2 Mo', 'error');
        btn.disabled = false;
        btn.innerHTML = '<i class="fas fa-check"></i> Confirmer';
        return;
      }

      /* Preview immédiat dans le header */
      var previewUrl = URL.createObjectURL(blob);
      document.getElementById('avatarImg').src = previewUrl;

      /* Fermer le modal */
      closeCropModal();

      /* Upload vers l'API */
      await uploadAvatar(blob);

      btn.disabled = false;
      btn.innerHTML = '<i class="fas fa-check"></i> Confirmer';
    }, 'image/jpeg', 0.85);

  } catch(e) {
    console.error('Crop error:', e);
    showToast('Erreur lors du recadrage', 'error');
    btn.disabled = false;
    btn.innerHTML = '<i class="fas fa-check"></i> Confirmer';
  }
}

/* Fermer le modal proprement */
function closeCropModal() {
  var modal = document.getElementById('cropModal');
  modal.classList.remove('open');
  document.body.style.overflow = '';
  if (_cropper) {
    _cropper.destroy();
    _cropper = null;
  }
}

/* Rendre closeCropModal accessible globalement */
window.closeCropModal = closeCropModal;

/* ══ UPLOAD AVATAR (prend un Blob) ══ */
async function uploadAvatar(blob) {
  var formData = new FormData();
  formData.append('avatar', blob, 'avatar.jpg');

  try {
    var r = await fetch('/api/profile/avatar', {
      method: 'POST',
      headers: {
        'Authorization': 'Bearer ' + _token(),
        'Accept': 'application/json'
        /* ✅ PAS de Content-Type — browser le set auto avec boundary */
      },
      body: formData
    });

    var json = await r.json();

    if (r.ok) {
      var newPath = json.data ? json.data.profile_photo_path : null;

      if (newPath) {
        _user = Object.assign({}, _user, {
          profile_photo_path: newPath,
          avatar: newPath
        });
        _saveUser(_user);
        document.getElementById('avatarImg').src = '/storage/' + newPath + '?t=' + Date.now();
      }

      if (typeof window.refreshNavAvatar === 'function') {
        window.refreshNavAvatar(_user);
      }

      showToast('Photo de profil mise à jour !', 'success');
    } else {
      var msg = '';
      if (json.errors && json.errors.avatar) {
        msg = json.errors.avatar[0];
      } else {
        msg = json.message || 'Erreur lors de l\'upload';
      }
      showToast(msg, 'error');
      _renderAvatar(_photo(_user), _user.name);
    }
  } catch(e) {
    console.error(e);
    showToast('Erreur réseau lors de l\'upload', 'error');
    _renderAvatar(_photo(_user), _user.name);
  }
}

/* ══════════════════════════════════════════
   CHANGE PASSWORD — ✅ FIX COMPLET
   - current_password / password / password_confirmation
   - Bouton disabled pendant l'appel
   - Clear les champs après succès
   - Affiche le message d'erreur précis du serveur
══════════════════════════════════════════ */
async function changePassword() {
  var curr = document.getElementById('inp_current').value;
  var nw   = document.getElementById('inp_new').value;
  var conf = document.getElementById('inp_confirm').value;

  /* Validations côté JS */
  if (!curr || !nw || !conf) {
    showToast('Tous les champs sont obligatoires', 'error');
    return;
  }
  if (nw !== conf) {
    showToast('Les mots de passe ne correspondent pas', 'error');
    return;
  }
  if (nw.length < 8) {
    showToast('Le nouveau mot de passe doit avoir au moins 8 caractères', 'error');
    return;
  }
  if (nw === curr) {
    showToast('Le nouveau mot de passe doit être différent de l\'actuel', 'error');
    return;
  }

  var btn = document.getElementById('btnPwd');
  btn.disabled = true;
  btn.innerHTML = '<span class="spin"></span> Modification…';

  try {
    var r = await fetch('/api/profile/password', {
      method: 'PUT',
      headers: {
        'Content-Type':  'application/json',
        'Authorization': 'Bearer ' + _token(),
        'Accept':        'application/json'
      },
      /* ✅ Clés exactes attendues par Laravel (confirmed rule) */
      body: JSON.stringify({
        current_password:      curr,
        password:              nw,
        password_confirmation: conf
      })
    });

    var json = await r.json();

    if (r.ok) {
      showToast('Mot de passe modifié avec succès ✓', 'success');
      /* ✅ Clear les champs après succès */
      document.getElementById('inp_current').value = '';
      document.getElementById('inp_new').value     = '';
      document.getElementById('inp_confirm').value = '';
    } else {
      /* ✅ Afficher l'erreur précise du serveur (Laravel validation) */
      var errMsg = '';
      if (json.errors) {
        var firstKey = Object.keys(json.errors)[0];
        errMsg = json.errors[firstKey][0];
      } else {
        errMsg = json.message || 'Erreur lors du changement de mot de passe';
      }
      showToast(errMsg, 'error');
    }
  } catch(e) {
    console.error('changePassword error:', e);
    showToast('Erreur réseau — réessayez', 'error');
  } finally {
    btn.disabled = false;
    btn.innerHTML = '<i class="fas fa-key"></i> Modifier le mot de passe';
  }
}

/* ══ HISTORY ══ */
async function loadHistory() {
  var el = document.getElementById('historyList');
  try {
    var r = await fetch('/api/reservations', {
      headers: {
        'Authorization': 'Bearer ' + _token(),
        'Accept': 'application/json'
      }
    });
    var json = await r.json();
    var list = Array.isArray(json)      ? json
             : Array.isArray(json.data) ? json.data
             : [];

    if (!list.length) {
      el.innerHTML = '<div style="text-align:center;padding:3rem;color:var(--txt-light)">'
                   + '<i class="fas fa-calendar-times" style="font-size:2rem;margin-bottom:.75rem;display:block;color:var(--cream3)"></i>'
                   + 'Aucune réservation pour l\'instant</div>';
      return;
    }

    var rows = list.map(function(res) {
      var mname  = (res.machine && res.machine.name) ? res.machine.name : '—';
      var period = (res.start_date || '—') + ' → ' + (res.end_date || '—');
      var price  = res.total_price
                 ? parseFloat(res.total_price).toLocaleString('fr-MA') + ' MAD'
                 : '—';
      var st = res.status || 'pending';
      return '<tr>'
        + '<td><strong style="color:var(--txt-dark)">' + _esc(mname) + '</strong></td>'
        + '<td style="font-size:.82rem;color:var(--txt-mid)">' + period + '</td>'
        + '<td><strong style="color:var(--txt-dark)">' + price + '</strong></td>'
        + '<td><span class="badge-status badge-' + st + '">' + st + '</span></td>'
        + '</tr>';
    }).join('');

    el.innerHTML =
      '<div style="overflow-x:auto">'
    + '<table class="history-table">'
    + '<thead><tr><th>Machine</th><th>Période</th><th>Montant</th><th>Statut</th></tr></thead>'
    + '<tbody>' + rows + '</tbody>'
    + '</table></div>';

  } catch(e) {
    el.innerHTML = '<div style="text-align:center;padding:2rem;color:var(--txt-light)">Erreur de chargement</div>';
  }
}

/* ══ TABS ══ */
window.showTab = function(name, btn) {
  ['infos', 'security', 'history'].forEach(function(t) {
    document.getElementById('tab-' + t).style.display = (t === name) ? 'block' : 'none';
  });
  document.querySelectorAll('.profile-tab-btn').forEach(function(b) {
    b.classList.remove('active');
  });
  if (btn) btn.classList.add('active');
};

/* ══ TOAST ══ */
function showToast(msg, type) {
  type = type || 'info';
  var icons = { success: '✅', error: '❌', info: 'ℹ️' };
  var c = document.getElementById('toastContainer');
  var t = document.createElement('div');
  t.className = 'toast-v13 ' + type;
  t.innerHTML = '<span>' + (icons[type] || 'ℹ️') + '</span><span>' + _esc(msg) + '</span>';
  c.appendChild(t);
  requestAnimationFrame(function() { t.classList.add('show'); });
  setTimeout(function() {
    t.classList.remove('show');
    setTimeout(function() { t.remove(); }, 400);
  }, 3500);
}

/* ══ ESC key pour fermer le modal ══ */
document.addEventListener('keydown', function(e) {
  if (e.key === 'Escape') closeCropModal();
});

/* ══ Click outside pour fermer ══ */
document.getElementById('cropModal').addEventListener('click', function(e) {
  if (e.target === this) closeCropModal();
});

/* ══ Utils ══ */
function _esc(s) {
  return String(s || '').replace(/&/g,'&amp;').replace(/</g,'&lt;').replace(/>/g,'&gt;');
}

})(); // fin IIFE
</script>
@endpush