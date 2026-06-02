@extends('layouts.app')

@push('styles')
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
      {{-- ✅ FIX : pas de onchange ici — géré via addEventListener pour éviter double-trigger --}}
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
        <input type="password" id="inp_current" class="form-control">
      </div>
      <div style="margin-bottom:1rem">
        <label class="form-label">Nouveau mot de passe</label>
        <input type="password" id="inp_new" class="form-control">
      </div>
      <div style="margin-bottom:1.25rem">
        <label class="form-label">Confirmer le nouveau mot de passe</label>
        <input type="password" id="inp_confirm" class="form-control">
      </div>
      <button class="btn-danger-outline" onclick="changePassword()">
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

<div class="toast-container" id="toastContainer"></div>
@endsection

@push('scripts')
<script>
(function() {
/* ══════════════════════════════════════════
    PROFILE — RENTIFY V17
    ✅ FIX : uploadAvatar via addEventListener (évite double-trigger)
    ✅ FIX : input.value='' AU DÉBUT avant tout traitement
    ✅ FIX : accept="image/jpeg,image/png,image/webp" sur l'input
    ✅ FIX : validation taille côté JS avant envoi
    ✅ API show() retourne data{} — cohérent avec ProfileController V17
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
  /* Affichage immédiat depuis localStorage */
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
    /* ✅ ProfileController V17 retourne { data: {...} } */
    var fresh = (json && json.data) ? json.data : (json && json.id ? json : null);

    if (!fresh) throw new Error('Réponse API invalide');

    /* ✅ Préserver photo si API renvoie null */
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

  /* ✅ Historique chargé séparément — pas de risque de conflit */
  loadHistory();

  /* ✅ FIX PRINCIPAL : addEventListener UNE SEULE FOIS sur l'input file
     Evite le double-trigger du onchange inline + l'auto-trigger au load */
  var avatarInput = document.getElementById('avatarInput');
  if (avatarInput && !avatarInput._bound) {
    avatarInput._bound = true;
    avatarInput.addEventListener('change', function() {
      uploadAvatar(this);
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

/* ══ UPLOAD AVATAR ══ */
async function uploadAvatar(input) {
  /* ✅ FIX : reset l'input EN PREMIER pour éviter re-trigger */
  var file = (input.files && input.files[0]) ? input.files[0] : null;
  input.value = '';

  if (!file) return;

  /* ✅ Validation taille côté JS (évite l'erreur Laravel "max") */
  if (file.size > 2 * 1024 * 1024) {
    showToast('Image trop grande — max 2 Mo', 'error');
    return;
  }

  /* ✅ Validation type côté JS */
  var allowed = ['image/jpeg', 'image/jpg', 'image/png', 'image/webp'];
  if (!allowed.includes(file.type)) {
    showToast('Format non supporté — JPG, PNG ou WebP uniquement', 'error');
    return;
  }

  /* Preview immédiat */
  var reader = new FileReader();
  reader.onload = function(e) {
    document.getElementById('avatarImg').src = e.target.result;
  };
  reader.readAsDataURL(file);

  var formData = new FormData();
  formData.append('avatar', file);

  try {
    var r = await fetch('/api/profile/avatar', {
      method: 'POST',
      headers: {
        'Authorization': 'Bearer ' + _token(),
        'Accept': 'application/json'
        /* ✅ PAS de Content-Type ici — le browser le set auto avec boundary pour FormData */
      },
      body: formData
    });

    var json = await r.json();

    if (r.ok) {
      /* ✅ ProfileController V17 retourne { data: { profile_photo_path } } */
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
      /* ✅ Afficher l'erreur Laravel précise */
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

/* ══ CHANGE PASSWORD ══ */
async function changePassword() {
  var curr = document.getElementById('inp_current').value;
  var nw   = document.getElementById('inp_new').value;
  var conf = document.getElementById('inp_confirm').value;

  if (!curr || !nw || !conf) {
    showToast('Tous les champs sont obligatoires', 'error'); return;
  }
  if (nw !== conf) {
    showToast('Les mots de passe ne correspondent pas', 'error'); return;
  }
  if (nw.length < 8) {
    showToast('Minimum 8 caractères', 'error'); return;
  }

  try {
    var r = await fetch('/api/profile/password', {
      method: 'PUT',
      headers: {
        'Content-Type':  'application/json',
        'Authorization': 'Bearer ' + _token(),
        'Accept':        'application/json'
      },
      body: JSON.stringify({
        current_password:      curr,
        password:              nw,
        password_confirmation: conf
      })
    });
    var json = await r.json();

    if (r.ok) {
      showToast('Mot de passe modifié avec succès', 'success');
      document.getElementById('inp_current').value = '';
      document.getElementById('inp_new').value     = '';
      document.getElementById('inp_confirm').value = '';
    } else {
      showToast(json.message || 'Erreur', 'error');
    }
  } catch(e) {
    showToast('Erreur réseau', 'error');
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

/* ══ Utils ══ */
function _esc(s) {
  return String(s || '').replace(/&/g,'&amp;').replace(/</g,'&lt;').replace(/>/g,'&gt;');
}

})(); // fin IIFE
</script>
@endpush