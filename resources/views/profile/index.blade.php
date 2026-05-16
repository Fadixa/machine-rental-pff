@extends('layouts.app')

@push('styles')
<style>
.profile-body { max-width: 900px; margin: 2rem auto; padding: 0 1rem; }
.profile-header {
  background: linear-gradient(135deg, #0F1B2D 0%, #1a2d45 100%);
  border-radius: 16px; padding: 2rem; color: white;
  display: flex; align-items: center; gap: 1.5rem; margin-bottom: 2rem;
}
.avatar-wrap { position: relative; width: 90px; height: 90px; }
.avatar-wrap img {
  width: 90px; height: 90px; border-radius: 50%;
  object-fit: cover; border: 3px solid #F59E0B;
}
.avatar-edit {
  position: absolute; bottom: 0; right: 0;
  background: #F59E0B; border: none; border-radius: 50%;
  width: 28px; height: 28px; cursor: pointer; color: white; font-size: .8rem;
}
.nav-tabs .nav-link { color: #0F1B2D; font-weight: 500; border: none; padding: .75rem 1.25rem; }
.nav-tabs .nav-link.active { color: #F59E0B; border-bottom: 2px solid #F59E0B; background: transparent; }
.card-profile { border: none; border-radius: 16px; box-shadow: 0 2px 12px rgba(15,27,45,.08); }
</style>
@endpush

@section('content')
<div class="profile-body" id="profileApp" style="display:none">

  <div class="profile-header">
    <div class="avatar-wrap">
      <img id="avatarImg" src="https://ui-avatars.com/api/?name=U&background=F59E0B&color=fff" alt="avatar">
      <button class="avatar-edit" onclick="document.getElementById('avatarInput').click()">
        <i class="fas fa-camera"></i>
      </button>
      <input type="file" id="avatarInput" accept="image/*" hidden onchange="uploadAvatar(this)">
    </div>
    <div>
      <h4 class="mb-0" id="headerName">—</h4>
      <small id="headerRole" class="opacity-75 text-capitalize"></small>
      <p class="mb-0 mt-1 opacity-75" id="headerCity"></p>
    </div>
  </div>

  <ul class="nav nav-tabs mb-4" id="profileTabs">
    <li class="nav-item">
      <button class="nav-link active" onclick="showTab('infos')">
        <i class="fas fa-user me-1"></i>Informations
      </button>
    </li>
    <li class="nav-item">
      <button class="nav-link" onclick="showTab('security')">
        <i class="fas fa-lock me-1"></i>Securite
      </button>
    </li>
    <li class="nav-item">
      <button class="nav-link" onclick="showTab('history')">
        <i class="fas fa-history me-1"></i>Historique
      </button>
    </li>
  </ul>

  <div id="tab-infos" class="card card-profile p-4">
    <h5 class="mb-3 fw-bold">Mes informations</h5>
    <div class="row g-3">
      <div class="col-md-6">
        <label class="form-label">Nom complet</label>
        <input type="text" id="inp_name" class="form-control">
      </div>
      <div class="col-md-6">
        <label class="form-label">Email</label>
        <input type="email" id="inp_email" class="form-control" disabled>
      </div>
      <div class="col-md-6">
        <label class="form-label">Telephone</label>
        <input type="text" id="inp_phone" class="form-control" placeholder="+212...">
      </div>
      <div class="col-md-6">
        <label class="form-label">Ville</label>
        <input type="text" id="inp_city" class="form-control">
      </div>
      <div class="col-12">
        <label class="form-label">Bio</label>
        <textarea id="inp_bio" class="form-control" rows="3"></textarea>
      </div>
    </div>
    <button class="btn mt-3 text-white"
      style="background:#F59E0B;border:none;width:fit-content"
      onclick="saveProfile()">
      <i class="fas fa-save me-1"></i>Enregistrer
    </button>
  </div>

  <div id="tab-security" class="card card-profile p-4" style="display:none">
    <h5 class="mb-3 fw-bold">Changer le mot de passe</h5>
    <div style="max-width:420px">
      <div class="mb-3">
        <label class="form-label">Mot de passe actuel</label>
        <input type="password" id="inp_current" class="form-control">
      </div>
      <div class="mb-3">
        <label class="form-label">Nouveau mot de passe</label>
        <input type="password" id="inp_new" class="form-control">
      </div>
      <div class="mb-3">
        <label class="form-label">Confirmer</label>
        <input type="password" id="inp_confirm" class="form-control">
      </div>
      <button class="btn btn-danger" onclick="changePassword()">
        <i class="fas fa-key me-1"></i>Modifier
      </button>
    </div>
  </div>

  <div id="tab-history" class="card card-profile p-4" style="display:none">
    <h5 class="mb-3 fw-bold">Historique des reservations</h5>
    <div id="historyList">
      <div class="text-center py-4">
        <div class="spinner-border text-warning"></div>
      </div>
    </div>
  </div>

</div>

<div class="position-fixed bottom-0 end-0 m-3" style="z-index:9999">
  <div id="toast" class="toast align-items-center text-white border-0" role="alert">
    <div class="d-flex">
      <div class="toast-body fw-bold" id="toastMsg"></div>
      <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast"></button>
    </div>
  </div>
</div>
@endsection

@push('scripts')
<script>
// ══════════════════════════════════════════
//  HELPERS localStorage
// ══════════════════════════════════════════
function readUser() {
  try {
    var u = localStorage.getItem('auth_user') || localStorage.getItem('user');
    return u ? JSON.parse(u) : null;
  } catch(e) { return null; }
}
function saveUser(obj) {
  var str = JSON.stringify(obj);
  localStorage.setItem('auth_user', str);
  localStorage.setItem('user', str);
}
function readToken() {
  return localStorage.getItem('auth_token') || localStorage.getItem('token') || '';
}

// ══════════════════════════════════════════
//  فونكسيون مستقلة — فقط النص، مالمساتش الصورة
// ══════════════════════════════════════════
function updateHeaderText(name, role, city) {
  document.getElementById('headerName').textContent = name || '—';
  document.getElementById('headerRole').textContent = role || '';
  document.getElementById('headerCity').textContent = city || '';
}

// ══════════════════════════════════════════
//  فونكسيون مستقلة — فقط الصورة
// ══════════════════════════════════════════
function updateAvatar(avatarPath, name) {
  if (avatarPath) {
    document.getElementById('avatarImg').src = '/storage/' + avatarPath + '?t=' + Date.now();
  } else {
    var n = encodeURIComponent(name || 'U');
    document.getElementById('avatarImg').src =
      'https://ui-avatars.com/api/?name=' + n + '&background=F59E0B&color=fff&size=90';
  }
}

// ══════════════════════════════════════════
//  GUARD
// ══════════════════════════════════════════
var currentUser = readUser();
if (!currentUser) {
  window.location.replace('/login');
} else {
  document.getElementById('profileApp').style.display = 'block';
  loadProfile();
}

// ══════════════════════════════════════════
//  LOAD PROFILE — يشغل مرة واحدة فالبداية
// ══════════════════════════════════════════
async function loadProfile() {
  // عرض فوري من localStorage
  updateHeaderText(currentUser.name, currentUser.role, currentUser.city);
  updateAvatar(currentUser.avatar, currentUser.name);

  try {
    var r = await fetch('/api/profile', {
      headers: {
        'Authorization': 'Bearer ' + readToken(),
        'Accept': 'application/json'
      }
    });
    var json = await r.json();
    var u = (json && json.data && json.data.id) ? json.data
           : (json && json.id)                   ? json
           : currentUser;

    // ملأ الفورم
    document.getElementById('inp_name').value  = u.name  || '';
    document.getElementById('inp_email').value = u.email || '';
    document.getElementById('inp_phone').value = u.phone || '';
    document.getElementById('inp_city').value  = u.city  || '';
    document.getElementById('inp_bio').value   = u.bio   || '';

    // تحديث الهيدر والأفاتار من API
    updateHeaderText(u.name, u.role, u.city);
    updateAvatar(u.avatar, u.name);

    // حفظ كامل فـ localStorage
    saveUser(u);

  } catch(e) {
    console.error('loadProfile:', e);
  }

  loadHistory();
}

// ══════════════════════════════════════════
//  SAVE PROFILE — لا يمس الصورة إطلاقاً
// ══════════════════════════════════════════
async function saveProfile() {
  var name  = document.getElementById('inp_name').value.trim();
  var phone = document.getElementById('inp_phone').value.trim();
  var city  = document.getElementById('inp_city').value.trim();
  var bio   = document.getElementById('inp_bio').value.trim();

  try {
    var r = await fetch('/api/profile', {
      method: 'PUT',
      headers: {
        'Content-Type':  'application/json',
        'Authorization': 'Bearer ' + readToken()
      },
      body: JSON.stringify({ name: name, phone: phone, city: city, bio: bio })
    });
    var json = await r.json();

    if (r.ok) {
      // ✅ حفظ مع الاحتفاظ بالـ avatar الموجود
      var old = readUser() || {};
      old.name  = name  || old.name;
      old.phone = phone || old.phone;
      old.city  = city  || old.city;
      old.bio   = bio   || old.bio;
      // avatar ماتغيرش — نبقيه كما هو
      saveUser(old);

      // ✅ فقط نص الهيدر — الصورة ماتتمساش
      updateHeaderText(old.name, old.role, old.city);

      showToast('Profil mis a jour', 'success');
    } else {
      showToast((json.message || 'Erreur'), 'danger');
    }
  } catch(e) {
    showToast('Erreur reseau', 'danger');
  }
}

// ══════════════════════════════════════════
//  UPLOAD AVATAR — يحدث الصورة فقط
// ══════════════════════════════════════════
async function uploadAvatar(input) {
  if (!input.files || !input.files[0]) return;
  var file = input.files[0];

  if (file.size > 2 * 1024 * 1024) {
    showToast('Image trop grande (max 2MB)', 'danger');
    return;
  }

  // Preview فوري بـ FileReader
  var reader = new FileReader();
  reader.onload = function(e) {
    document.getElementById('avatarImg').src = e.target.result;
  };
  reader.readAsDataURL(file);

  var form = new FormData();
  form.append('avatar', file);

  try {
    var r = await fetch('/api/profile/avatar', {
      method: 'POST',
      headers: { 'Authorization': 'Bearer ' + readToken() },
      body: form
    });
    var json = await r.json();

    if (r.ok) {
      
      var u = readUser() || {};
      if (json.data && json.data.avatar) {
        u.avatar = json.data.avatar;
        saveUser(u);
        
        document.getElementById('avatarImg').src =
          (json.data.avatar_url || '/storage/' + json.data.avatar) + '?t=' + Date.now();
      }
      showToast('Photo de profil mise a jour', 'success');
    } else {
      showToast((json.message || 'Erreur upload'), 'danger');
      // رجع الصورة القديمة
      var old2 = readUser() || {};
      updateAvatar(old2.avatar, old2.name);
    }
  } catch(e) {
    showToast('Erreur reseau', 'danger');
    var old3 = readUser() || {};
    updateAvatar(old3.avatar, old3.name);
  }
}

// ══════════════════════════════════════════
//  CHANGE PASSWORD
// ══════════════════════════════════════════
async function changePassword() {
  var body = {
    current_password:      document.getElementById('inp_current').value,
    password:              document.getElementById('inp_new').value,
    password_confirmation: document.getElementById('inp_confirm').value
  };
  try {
    var r = await fetch('/api/profile/password', {
      method: 'PUT',
      headers: { 'Content-Type': 'application/json', 'Authorization': 'Bearer ' + readToken() },
      body: JSON.stringify(body)
    });
    var json = await r.json();
    showToast(r.ok ? 'Mot de passe modifie' : (json.message || 'Erreur'), r.ok ? 'success' : 'danger');
    if (r.ok) {
      document.getElementById('inp_current').value = '';
      document.getElementById('inp_new').value     = '';
      document.getElementById('inp_confirm').value = '';
    }
  } catch(e) {
    showToast('Erreur reseau', 'danger');
  }
}

// ══════════════════════════════════════════
//  LOAD HISTORY
// ══════════════════════════════════════════
async function loadHistory() {
  var el = document.getElementById('historyList');
  try {
    var r = await fetch('/api/reservations', {
      headers: { 'Authorization': 'Bearer ' + readToken(), 'Accept': 'application/json' }
    });
    var json = await r.json();
    var list = (json && json.data) ? json.data : (Array.isArray(json) ? json : []);

    if (!list.length) {
      el.innerHTML = '<p class="text-muted text-center py-3">Aucune reservation</p>';
      return;
    }

    var colors = { pending:'warning', accepted:'success', completed:'secondary', rejected:'danger' };
    var rows = '';
    for (var i = 0; i < list.length; i++) {
      var res   = list[i];
      var mname = res.machine ? res.machine.name : '—';
      var col   = colors[res.status] || 'secondary';
      rows += '<tr>' +
        '<td><strong>' + mname + '</strong></td>' +
        '<td class="text-muted small">' + (res.start_date||'—') + ' → ' + (res.end_date||'—') + '</td>' +
        '<td><span class="badge bg-' + col + '">' + res.status + '</span></td>' +
        '</tr>';
    }
    el.innerHTML =
      '<div class="table-responsive"><table class="table table-hover align-middle">' +
      '<thead style="background:#f8f9fa"><tr><th>Machine</th><th>Periode</th><th>Statut</th></tr></thead>' +
      '<tbody>' + rows + '</tbody></table></div>';
  } catch(e) {
    el.innerHTML = '<p class="text-muted text-center py-3">Erreur de chargement</p>';
  }
}

// ══════════════════════════════════════════
//  TABS
// ══════════════════════════════════════════
function showTab(name) {
  var tabs = ['infos', 'security', 'history'];
  for (var i = 0; i < tabs.length; i++) {
    document.getElementById('tab-' + tabs[i]).style.display = tabs[i] === name ? 'block' : 'none';
  }
  var btns = document.querySelectorAll('#profileTabs .nav-link');
  for (var j = 0; j < btns.length; j++) {
    btns[j].classList.toggle('active', tabs[j] === name);
  }
}

// ══════════════════════════════════════════
//  TOAST
// ══════════════════════════════════════════
function showToast(msg, type) {
  type = type || 'success';
  var el = document.getElementById('toast');
  el.className = 'toast align-items-center text-white bg-' + type + ' border-0';
  document.getElementById('toastMsg').textContent = msg;
  new bootstrap.Toast(el, { delay: 3500 }).show();
}
</script>
@endpush