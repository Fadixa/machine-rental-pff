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
.profile-body { max-width:900px; margin:2rem auto; padding:0 1rem; }
.profile-header {
  background:var(--gold-pale); border:1.5px solid rgba(212,175,55,.3);
  border-radius:16px; padding:2rem; display:flex; align-items:center; gap:1.5rem;
  margin-bottom:2rem; box-shadow:var(--shadow);
}
.avatar-wrap { position:relative; width:90px; height:90px; flex-shrink:0; }
.avatar-wrap img { width:90px; height:90px; border-radius:50%; object-fit:cover; border:3px solid var(--gold); background:var(--cream2); }
.avatar-edit {
  position:absolute; bottom:0; right:0; background:var(--gold); border:none; border-radius:50%;
  width:28px; height:28px; cursor:pointer; color:var(--txt-dark); font-size:.8rem;
  display:flex; align-items:center; justify-content:center; transition:background .2s;
}
.avatar-edit:hover { background:var(--gold-dk); color:#fff; }
.profile-header-info h4 { color:var(--txt-dark); font-weight:700; margin:0; font-size:1.2rem; }
.profile-header-info .role-badge {
  display:inline-block; margin-top:.3rem; background:rgba(212,175,55,.2); color:var(--gold-dk);
  font-size:.72rem; font-weight:700; letter-spacing:.08em; padding:.2rem .75rem;
  border-radius:20px; text-transform:uppercase; border:1px solid rgba(212,175,55,.35);
}
.profile-header-info .city { color:var(--txt-mid); font-size:.85rem; margin-top:.35rem; }
.profile-tabs { display:flex; gap:.25rem; border-bottom:2px solid var(--cream3); margin-bottom:1.5rem; }
.profile-tab-btn {
  padding:.65rem 1.25rem; background:none; border:none;
  border-bottom:3px solid transparent; margin-bottom:-2px;
  font-family:'DM Sans',sans-serif; font-size:.88rem; font-weight:600;
  color:var(--txt-mid); cursor:pointer; transition:all .2s; border-radius:8px 8px 0 0;
}
.profile-tab-btn:hover { color:var(--txt-dark); background:var(--cream2); }
.profile-tab-btn.active { color:var(--gold-dk); border-bottom-color:var(--gold); background:var(--gold-pale); }
.card-profile { background:#fff; border:1px solid rgba(212,175,55,.15); border-radius:var(--radius); padding:2rem; box-shadow:var(--shadow); }
.card-profile h5 { font-family:'Playfair Display',serif; color:var(--txt-dark); font-size:1.1rem; margin-bottom:1.25rem; padding-bottom:.75rem; border-bottom:1.5px solid var(--cream3); }
.form-label { display:block; font-size:.8rem; font-weight:700; color:var(--txt-dark); margin-bottom:.4rem; letter-spacing:.03em; }
.form-control {
  width:100%; box-sizing:border-box; border:1.5px solid var(--cream3); border-radius:10px;
  padding:.6rem .9rem; font-family:'DM Sans',sans-serif; font-size:.88rem; color:var(--txt-dark);
  background:#fff; transition:border .2s; outline:none;
}
.form-control:focus { border-color:var(--gold); box-shadow:0 0 0 3px var(--gold-glow); }
.form-control[readonly] { background:var(--cream2); color:var(--txt-mid); cursor:default; }
.form-control::placeholder { color:var(--txt-light); }
textarea.form-control { resize:vertical; min-height:90px; }
.form-grid { display:grid; grid-template-columns:1fr 1fr; gap:1rem; }
.form-grid .col-full { grid-column:1/-1; }
@media (max-width:600px) { .form-grid { grid-template-columns:1fr; } }
.btn-gold {
  background:var(--gold); color:var(--txt-dark); border:2px solid var(--gold); border-radius:10px;
  padding:.55rem 1.4rem; font-family:'DM Sans',sans-serif; font-weight:700; font-size:.88rem;
  cursor:pointer; transition:all .2s; display:inline-flex; align-items:center; gap:.5rem;
}
.btn-gold:hover { background:var(--gold-dk); border-color:var(--gold-dk); color:#fff; }
.btn-gold:disabled { opacity:.6; cursor:not-allowed; }
.btn-outline {
  background:#fff; color:var(--gold-dk); border:2px solid var(--gold); border-radius:10px;
  padding:.55rem 1.4rem; font-family:'DM Sans',sans-serif; font-weight:700; font-size:.88rem;
  cursor:pointer; transition:all .2s; display:inline-flex; align-items:center; gap:.5rem;
}
.btn-outline:hover { background:var(--gold-pale); }
.btn-danger-outline {
  background:#fff; color:var(--red); border:2px solid var(--red); border-radius:10px;
  padding:.55rem 1.4rem; font-family:'DM Sans',sans-serif; font-weight:700; font-size:.88rem;
  cursor:pointer; transition:all .2s; display:inline-flex; align-items:center; gap:.5rem;
}
.btn-danger-outline:hover { background:#fee2e2; }
.btn-danger-outline:disabled { opacity:.6; cursor:not-allowed; }
.history-table { width:100%; border-collapse:collapse; font-size:.85rem; }
.history-table thead tr { background:var(--cream); border-bottom:2px solid var(--cream3); }
.history-table th { padding:.75rem 1rem; text-align:left; font-weight:700; color:var(--txt-dark); font-size:.78rem; letter-spacing:.05em; text-transform:uppercase; }
.history-table td { padding:.75rem 1rem; color:var(--txt-mid); border-bottom:1px solid var(--cream2); vertical-align:middle; }
.history-table tbody tr:hover td { background:var(--cream); }
.badge-status { padding:.25rem .7rem; border-radius:20px; font-size:.72rem; font-weight:700; letter-spacing:.04em; }
.badge-pending   { background:#FEF9E7; color:var(--gold-dk); }
.badge-accepted  { background:#dcfce7; color:#15803d; }
.badge-completed { background:#dcfce7; color:#15803d; }
.badge-rejected  { background:#fee2e2; color:#b91c1c; }
.badge-cancelled { background:var(--cream3); color:var(--txt-mid); }
.spin { display:inline-block; width:18px; height:18px; border:2px solid var(--cream3); border-top-color:var(--gold); border-radius:50%; animation:spin .7s linear infinite; }
@keyframes spin { to { transform:rotate(360deg); } }
.toast-container { position:fixed; bottom:2rem; right:2rem; z-index:99999; display:flex; flex-direction:column; gap:.6rem; }
.toast-v13 {
  display:flex; align-items:center; gap:.75rem; background:#fff; border:1px solid var(--cream3);
  border-radius:12px; padding:.85rem 1.25rem; font-size:.85rem; font-weight:600; color:var(--txt-dark);
  box-shadow:0 8px 32px rgba(10,16,28,.15); transform:translateX(120%);
  transition:transform .35s cubic-bezier(.34,1.56,.64,1); min-width:240px; max-width:320px;
}
.toast-v13.show    { transform:translateX(0); }
.toast-v13.success { border-left:4px solid var(--green); }
.toast-v13.error   { border-left:4px solid var(--red); }
.toast-v13.info    { border-left:4px solid var(--gold); }

/* ── CROP MODAL ── */
#cropModal {
  display:none; position:fixed; inset:0; z-index:9999;
  background:rgba(15,27,45,.72); backdrop-filter:blur(4px);
  align-items:center; justify-content:center;
}
#cropModal.open { display:flex; }
.crop-dialog {
  background:#fff; border-radius:20px; box-shadow:0 24px 80px rgba(15,27,45,.3);
  width:min(500px, 95vw); overflow:hidden;
  animation:cropIn .25s cubic-bezier(.34,1.4,.64,1);
}
@keyframes cropIn { from{transform:scale(.88);opacity:0} to{transform:scale(1);opacity:1} }
.crop-dialog-header {
  display:flex; align-items:center; justify-content:space-between;
  padding:1rem 1.5rem; background:var(--gold-pale);
  border-bottom:1.5px solid rgba(212,175,55,.3);
}
.crop-dialog-header h6 { margin:0; font-family:'Playfair Display',serif; font-size:1rem; color:var(--txt-dark); font-weight:700; }
.crop-close { background:none; border:none; cursor:pointer; color:var(--txt-mid); font-size:1.1rem; padding:.25rem .45rem; border-radius:6px; transition:background .15s; }
.crop-close:hover { background:var(--cream2); }
.crop-canvas-wrap {
  position:relative; width:100%; height:320px;
  background:#111; overflow:hidden; cursor:grab;
  user-select:none; -webkit-user-select:none;
}
.crop-canvas-wrap:active { cursor:grabbing; }
#cropCanvas { display:block; position:absolute; top:0; left:0; }
.crop-overlay { position:absolute; top:0; left:0; width:100%; height:100%; pointer-events:none; display:block; }
.crop-hint { padding:.5rem 1.5rem; text-align:center; font-size:.75rem; color:var(--txt-light); background:var(--cream); border-top:1px solid var(--cream3); margin:0; }
.crop-dialog-footer { display:flex; gap:.75rem; justify-content:flex-end; padding:.9rem 1.5rem; border-top:1.5px solid var(--cream3); }
</style>
@endpush

@section('content')
<div class="profile-body" id="profileApp" style="display:none">

  <div class="profile-header">
    <div class="avatar-wrap">
      <img id="avatarImg" src="" alt="avatar">
      <button class="avatar-edit" onclick="document.getElementById('avatarInput').click()" title="Changer la photo">
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

  <div class="profile-tabs">
    <button class="profile-tab-btn active" onclick="showTab('infos',this)"><i class="fas fa-user"></i> Informations</button>
    <button class="profile-tab-btn" onclick="showTab('security',this)"><i class="fas fa-lock"></i> Sécurité</button>
    <button class="profile-tab-btn" onclick="showTab('history',this)"><i class="fas fa-history"></i> Historique</button>
  </div>

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
        <textarea id="inp_bio" class="form-control" placeholder="Quelques mots sur vous…"></textarea>
      </div>
    </div>
    <button class="btn-gold mt-3" onclick="saveProfile()" id="btnSave">
      <i class="fas fa-save"></i> Enregistrer
    </button>
  </div>

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
      <button class="btn-danger-outline" id="btnPwd" onclick="changePassword()">
        <i class="fas fa-key"></i> Modifier le mot de passe
      </button>
    </div>
  </div>

  <div id="tab-history" class="card-profile" style="display:none">
    <h5><i class="fas fa-history me-2" style="color:var(--gold)"></i>Historique des réservations</h5>
    <div id="historyList"><div style="text-align:center;padding:2rem;color:var(--txt-light)"><div class="spin"></div></div></div>
  </div>

</div>

{{-- CROP MODAL --}}
<div id="cropModal" role="dialog" aria-modal="true">
  <div class="crop-dialog">
    <div class="crop-dialog-header">
      <h6><i class="fas fa-crop-alt" style="color:var(--gold-dk);margin-right:.5rem"></i>Ajuster la photo de profil</h6>
      <button class="crop-close" id="btnCropClose"><i class="fas fa-times"></i></button>
    </div>
    <div class="crop-canvas-wrap" id="cropWrap">
      <canvas id="cropCanvas"></canvas>
      <svg class="crop-overlay" id="cropSvg" xmlns="http://www.w3.org/2000/svg">
        <defs>
          <mask id="cm"><rect width="100%" height="100%" fill="white"/><circle id="mcircle" fill="black"/></mask>
        </defs>
        <rect width="100%" height="100%" fill="rgba(0,0,0,0.55)" mask="url(#cm)"/>
        <circle id="ocircle" fill="none" stroke="#D4AF37" stroke-width="2.5" stroke-dasharray="6 3"/>
      </svg>
    </div>
    <p class="crop-hint">🖱 Glisser pour déplacer &nbsp;·&nbsp; Molette / pincer pour zoomer</p>
    <div class="crop-dialog-footer">
      <button class="btn-outline" id="btnCropCancel"><i class="fas fa-times"></i> Annuler</button>
      <button class="btn-gold" id="btnConfirmCrop"><i class="fas fa-check"></i> Confirmer</button>
    </div>
  </div>
</div>

<div class="toast-container" id="toastContainer"></div>
@endsection

@push('scripts')
<script>
(function(){
'use strict';
/* RENTIFY PROFILE V18 — crop Canvas natif, zero CDN */

function _ru()  { try{return JSON.parse(localStorage.getItem('auth_user')||'null');}catch(e){return null;} }
function _su(u) { localStorage.setItem('auth_user',JSON.stringify(u)); }
function _tok() { return localStorage.getItem('auth_token')||''; }
function _ph(u) { return (u&&(u.profile_photo_path||u.avatar))||null; }

function _renderAvatar(p,n){
  var img=document.getElementById('avatarImg');
  if(p){ img.src='/storage/'+p+'?t='+Date.now(); img.onerror=function(){img.src=_fb(n);img.onerror=null;}; }
  else img.src=_fb(n);
}
function _fb(n){ return 'https://ui-avatars.com/api/?name='+encodeURIComponent(n||'U')+'&background=D4AF37&color=1a1a2e&size=90&bold=true'; }
function _rh(u){
  document.getElementById('headerName').textContent=u.name||'—';
  document.getElementById('headerRole').textContent=u.role||'';
  document.getElementById('headerCity').textContent=u.city?'📍 '+u.city:'';
}
function _esc(s){ return String(s||'').replace(/&/g,'&amp;').replace(/</g,'&lt;').replace(/>/g,'&gt;'); }

var _user=_ru();
if(!_user){window.location.replace('/login');return;}
document.getElementById('profileApp').style.display='block';
loadProfile();

async function loadProfile(){
  _rh(_user); _renderAvatar(_ph(_user),_user.name);
  try{
    var r=await fetch('/api/profile',{headers:{'Authorization':'Bearer '+_tok(),'Accept':'application/json'}});
    if(r.status===401){localStorage.removeItem('auth_token');localStorage.removeItem('auth_user');window.location.replace('/login');return;}
    var j=await r.json();
    var f=(j&&j.data)?j.data:(j&&j.id?j:null);
    if(!f)throw new Error('invalid');
    if(!f.profile_photo_path&&_ph(_user))f.profile_photo_path=_ph(_user);
    document.getElementById('inp_name').value =f.name ||'';
    document.getElementById('inp_email').value=f.email||'';
    document.getElementById('inp_phone').value=f.phone||'';
    document.getElementById('inp_city').value =f.city ||'';
    document.getElementById('inp_bio').value  =f.bio  ||'';
    _rh(f);_renderAvatar(_ph(f),f.name);_su(f);_user=f;
  }catch(e){
    document.getElementById('inp_name').value =_user.name ||'';
    document.getElementById('inp_email').value=_user.email||'';
    document.getElementById('inp_phone').value=_user.phone||'';
    document.getElementById('inp_city').value =_user.city ||'';
    document.getElementById('inp_bio').value  =_user.bio  ||'';
  }
  loadHistory();
  var inp=document.getElementById('avatarInput');
  if(!inp._b){inp._b=true;inp.addEventListener('change',function(){onFileSel(this);});}
}

async function saveProfile(){
  var name=document.getElementById('inp_name').value.trim();
  if(!name){showToast('Le nom est obligatoire','error');return;}
  var btn=document.getElementById('btnSave');
  btn.disabled=true;btn.innerHTML='<span class="spin"></span> Enregistrement\u2026';
  try{
    var r=await fetch('/api/profile',{method:'PUT',
      headers:{'Content-Type':'application/json','Authorization':'Bearer '+_tok(),'Accept':'application/json'},
      body:JSON.stringify({name:name,phone:document.getElementById('inp_phone').value.trim(),city:document.getElementById('inp_city').value.trim(),bio:document.getElementById('inp_bio').value.trim()})
    });
    var j=await r.json();
    if(r.ok){
      var u2=Object.assign({},_user,{name:name});_su(u2);_user=u2;_rh(_user);
      showToast('Profil mis à jour \u2713','success');
    }else{
      showToast((j.errors?Object.values(j.errors)[0][0]:null)||j.message||'Erreur','error');
    }
  }catch(e){showToast('Erreur réseau','error');}
  finally{btn.disabled=false;btn.innerHTML='<i class="fas fa-save"></i> Enregistrer';}
}
window.saveProfile=saveProfile;

/* ═══════════════════════════════════════
   CROP ENGINE — Canvas natif pur
═══════════════════════════════════════ */
var _ci=null,_cc=null,_cx=null,_cw=null;
var _wW=0,_wH=0,_cr=0;
var _tx=0,_ty=0,_sc=1;
var _drag=false,_dsx=0,_dsy=0,_dtx=0,_dty=0;
var _ltd=0;

function _initEngine(){
  _cc=document.getElementById('cropCanvas');
  _cx=_cc.getContext('2d');
  _cw=document.getElementById('cropWrap');

  /* getBoundingClientRect est plus fiable qu'offsetWidth apres display:flex */
  var rect=_cw.getBoundingClientRect();
  _wW=Math.round(rect.width)||Math.min(500,window.innerWidth*0.95-32)||460;
  _wH=320;
  _cr=Math.min(_wW,_wH)*0.43;

  /* canvas logique (CSS pixels) — pas de scaling DPR pour eviter decalage */
  _cc.width =_wW;
  _cc.height=_wH;
  _cc.style.width =_wW+'px';
  _cc.style.height=_wH+'px';

  /* SVG overlay : forcer width/height/viewBox pour qu'il couvre exactement le wrap */
  var svgEl=document.getElementById('cropSvg');
  svgEl.setAttribute('width', _wW);
  svgEl.setAttribute('height',_wH);
  svgEl.setAttribute('viewBox','0 0 '+_wW+' '+_wH);

  /* Cercle centre au milieu */
  var ox=_wW/2, oy=_wH/2;
  ['mcircle','ocircle'].forEach(function(id){
    var el=document.getElementById(id);
    el.setAttribute('cx',ox);
    el.setAttribute('cy',oy);
    el.setAttribute('r', _cr);
  });

  /* Scale initial : image couvre le cercle */
  var fw=(_cr*2)/_ci.naturalWidth, fh=(_cr*2)/_ci.naturalHeight;
  _sc=Math.max(fw,fh)*1.05;
  _tx=(_wW-_ci.naturalWidth *_sc)/2;
  _ty=(_wH-_ci.naturalHeight*_sc)/2;
  _draw();_bindEvt();
}

function _draw(){
  _cx.clearRect(0,0,_wW,_wH);
  _cx.fillStyle='#111';_cx.fillRect(0,0,_wW,_wH);
  _cx.drawImage(_ci,_tx,_ty,_ci.naturalWidth*_sc,_ci.naturalHeight*_sc);
}

function _bindEvt(){
  var el=_cc;
  el.addEventListener('mousedown',_mdn);
  el.addEventListener('mousemove',_mmv);
  el.addEventListener('mouseup',_mup);
  el.addEventListener('mouseleave',_mup);
  el.addEventListener('touchstart',_tst,{passive:true});
  el.addEventListener('touchmove',_tmv,{passive:false});
  el.addEventListener('touchend',_mup);
  el.addEventListener('wheel',_whl,{passive:false});
}
function _unbindEvt(){
  if(!_cc)return;
  var el=_cc;
  el.removeEventListener('mousedown',_mdn);
  el.removeEventListener('mousemove',_mmv);
  el.removeEventListener('mouseup',_mup);
  el.removeEventListener('mouseleave',_mup);
  el.removeEventListener('touchstart',_tst);
  el.removeEventListener('touchmove',_tmv);
  el.removeEventListener('touchend',_mup);
  el.removeEventListener('wheel',_whl);
}

function _mdn(e){_drag=true;_dsx=e.clientX;_dsy=e.clientY;_dtx=_tx;_dty=_ty;}
function _mmv(e){if(!_drag)return;_tx=_dtx+(e.clientX-_dsx);_ty=_dty+(e.clientY-_dsy);_draw();}
function _mup(){_drag=false;}
function _tst(e){
  if(e.touches.length===1){_drag=true;_dsx=e.touches[0].clientX;_dsy=e.touches[0].clientY;_dtx=_tx;_dty=_ty;}
  else if(e.touches.length===2){_drag=false;_ltd=Math.hypot(e.touches[0].clientX-e.touches[1].clientX,e.touches[0].clientY-e.touches[1].clientY);}
}
function _tmv(e){
  e.preventDefault();
  if(e.touches.length===1&&_drag){_tx=_dtx+(e.touches[0].clientX-_dsx);_ty=_dty+(e.touches[0].clientY-_dsy);_draw();}
  else if(e.touches.length===2){
    var d=Math.hypot(e.touches[0].clientX-e.touches[1].clientX,e.touches[0].clientY-e.touches[1].clientY);
    _zoom(_wW/2,_wH/2,d/(_ltd||d));_ltd=d;
  }
}
function _whl(e){e.preventDefault();var f=e.deltaY<0?1.08:0.93;var rc=_cc.getBoundingClientRect();_zoom(e.clientX-rc.left,e.clientY-rc.top,f);}
function _zoom(px,py,f){var ns=Math.max(0.05,Math.min(20,_sc*f));var r=ns/_sc;_tx=px-r*(px-_tx);_ty=py-r*(py-_ty);_sc=ns;_draw();}

function openCropModal(dataUrl){
  document.getElementById('cropModal').classList.add('open');
  document.body.style.overflow='hidden';
  var img=new Image();
  img.onload=function(){
    _ci=img;
    /* double rAF pour que le DOM soit rendu et _cw.offsetWidth soit correct */
    requestAnimationFrame(function(){requestAnimationFrame(function(){_initEngine();});});
  };
  img.src=dataUrl;
}

function closeCropModal(){
  document.getElementById('cropModal').classList.remove('open');
  document.body.style.overflow='';
  _unbindEvt();
  _ci=null;
  var btn=document.getElementById('btnConfirmCrop');
  btn.disabled=false;btn.innerHTML='<i class="fas fa-check"></i> Confirmer';
}
window.closeCropModal=closeCropModal;

function confirmCrop(){
  if(!_ci||!_cc){showToast('Image non prête — réessayez','error');return;}
  var btn=document.getElementById('btnConfirmCrop');
  btn.disabled=true;btn.innerHTML='<span class="spin"></span> Upload\u2026';

  /* Canvas de sortie 400x400 */
  var out=document.createElement('canvas');
  out.width=400;out.height=400;
  var octx=out.getContext('2d');
  octx.fillStyle='#fff';octx.fillRect(0,0,400,400);

  /* Zone source : cercle centré dans le wrap */
  var srcX=(_wW/2-_cr-_tx)/_sc;
  var srcY=(_wH/2-_cr-_ty)/_sc;
  var srcS=(_cr*2)/_sc;

  octx.drawImage(_ci,srcX,srcY,srcS,srcS,0,0,400,400);

  out.toBlob(function(blob){
    if(!blob){
      showToast('Erreur recadrage','error');
      btn.disabled=false;btn.innerHTML='<i class="fas fa-check"></i> Confirmer';
      return;
    }
    /* Preview immédiat */
    document.getElementById('avatarImg').src=URL.createObjectURL(blob);
    closeCropModal();
    uploadAvatar(blob);
  },'image/jpeg',0.88);
}

/* Bind boutons modal via addEventListener (fiable vs onclick inline) */
document.getElementById('btnConfirmCrop').addEventListener('click',confirmCrop);
document.getElementById('btnCropCancel').addEventListener('click',closeCropModal);
document.getElementById('btnCropClose').addEventListener('click',closeCropModal);
document.getElementById('cropModal').addEventListener('click',function(e){if(e.target===this)closeCropModal();});
document.addEventListener('keydown',function(e){if(e.key==='Escape')closeCropModal();});

function onFileSel(input){
  var file=input.files&&input.files[0];input.value='';
  if(!file)return;
  if(!['image/jpeg','image/jpg','image/png','image/webp'].includes(file.type)){showToast('Format non supporté — JPG, PNG ou WebP','error');return;}
  if(file.size>8*1024*1024){showToast('Image trop grande — max 8 Mo','error');return;}
  var rd=new FileReader();
  rd.onload=function(e){openCropModal(e.target.result);};
  rd.readAsDataURL(file);
}

async function uploadAvatar(blob){
  var fd=new FormData();fd.append('avatar',blob,'avatar.jpg');
  try{
    var r=await fetch('/api/profile/avatar',{method:'POST',
      headers:{'Authorization':'Bearer '+_tok(),'Accept':'application/json'},body:fd});
    var j=await r.json();
    if(r.ok){
      var p=j.data?j.data.profile_photo_path:null;
      if(p){_user=Object.assign({},_user,{profile_photo_path:p,avatar:p});_su(_user);
        document.getElementById('avatarImg').src='/storage/'+p+'?t='+Date.now();}
      if(typeof window.refreshNavAvatar==='function')window.refreshNavAvatar(_user);
      showToast('Photo de profil mise à jour !','success');
    }else{
      var msg=(j.errors&&j.errors.avatar)?j.errors.avatar[0]:(j.message||'Erreur upload');
      showToast(msg,'error');_renderAvatar(_ph(_user),_user.name);
    }
  }catch(e){showToast("Erreur réseau lors de l'upload",'error');_renderAvatar(_ph(_user),_user.name);}
}

async function changePassword(){
  var curr=document.getElementById('inp_current').value;
  var nw=document.getElementById('inp_new').value;
  var conf=document.getElementById('inp_confirm').value;
  if(!curr||!nw||!conf){showToast('Tous les champs sont obligatoires','error');return;}
  if(nw!==conf){showToast('Les mots de passe ne correspondent pas','error');return;}
  if(nw.length<8){showToast('Minimum 8 caractères','error');return;}
  if(nw===curr){showToast('Le nouveau doit être différent de l\'actuel','error');return;}
  var btn=document.getElementById('btnPwd');
  btn.disabled=true;btn.innerHTML='<span class="spin"></span> Modification\u2026';
  try{
    var r=await fetch('/api/profile/password',{method:'PUT',
      headers:{'Content-Type':'application/json','Authorization':'Bearer '+_tok(),'Accept':'application/json'},
      body:JSON.stringify({current_password:curr,password:nw,password_confirmation:conf})
    });
    var j=await r.json();
    if(r.ok){
      showToast('Mot de passe modifié \u2713','success');
      document.getElementById('inp_current').value='';
      document.getElementById('inp_new').value='';
      document.getElementById('inp_confirm').value='';
    }else{
      var em=j.errors?j.errors[Object.keys(j.errors)[0]][0]:(j.message||'Erreur');
      showToast(em,'error');
    }
  }catch(e){showToast('Erreur réseau','error');}
  finally{btn.disabled=false;btn.innerHTML='<i class="fas fa-key"></i> Modifier le mot de passe';}
}
window.changePassword=changePassword;

async function loadHistory(){
  var el=document.getElementById('historyList');
  try{
    var r=await fetch('/api/reservations',{headers:{'Authorization':'Bearer '+_tok(),'Accept':'application/json'}});
    var j=await r.json();
    var list=Array.isArray(j)?j:(Array.isArray(j.data)?j.data:[]);
    if(!list.length){el.innerHTML='<div style="text-align:center;padding:3rem;color:var(--txt-light)"><i class="fas fa-calendar-times" style="font-size:2rem;display:block;margin-bottom:.75rem;color:var(--cream3)"></i>Aucune réservation</div>';return;}
    var rows=list.map(function(res){var st=res.status||'pending';return '<tr><td><strong style="color:var(--txt-dark)">'+_esc((res.machine&&res.machine.name)||'—')+'</strong></td><td style="font-size:.82rem">'+(res.start_date||'—')+' \u2192 '+(res.end_date||'—')+'</td><td><strong>'+(res.total_price?parseFloat(res.total_price).toLocaleString('fr-MA')+' MAD':'—')+'</strong></td><td><span class="badge-status badge-'+st+'">'+st+'</span></td></tr>';}).join('');
    el.innerHTML='<div style="overflow-x:auto"><table class="history-table"><thead><tr><th>Machine</th><th>Période</th><th>Montant</th><th>Statut</th></tr></thead><tbody>'+rows+'</tbody></table></div>';
  }catch(e){el.innerHTML='<div style="text-align:center;padding:2rem;color:var(--txt-light)">Erreur de chargement</div>';}
}

window.showTab=function(name,btn){
  ['infos','security','history'].forEach(function(t){document.getElementById('tab-'+t).style.display=(t===name)?'block':'none';});
  document.querySelectorAll('.profile-tab-btn').forEach(function(b){b.classList.remove('active');});
  if(btn)btn.classList.add('active');
};

function showToast(msg,type){
  var icons={success:'✅',error:'❌',info:'ℹ️'};
  var t=document.createElement('div');
  t.className='toast-v13 '+(type||'info');
  t.innerHTML='<span>'+(icons[type]||'ℹ️')+'</span><span>'+_esc(msg)+'</span>';
  document.getElementById('toastContainer').appendChild(t);
  requestAnimationFrame(function(){t.classList.add('show');});
  setTimeout(function(){t.classList.remove('show');setTimeout(function(){t.remove();},400);},3500);
}

})();
</script>
@endpush