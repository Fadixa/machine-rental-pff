@extends('layouts.app')
@section('title', 'Inscription — Rentify')

@push('styles')
<style>
body { overflow: hidden; }
.auth-wrap {
    display: grid; grid-template-columns: 1fr 1fr;
    height: calc(100vh - 64px);
}

/* Left (same as login) */
.auth-left {
    background: var(--navy); position: relative; overflow: hidden;
    display: flex; flex-direction: column; justify-content: space-between; padding: 48px 52px;
}
.auth-left-grid {
    position: absolute; inset: -64px;
    background-image:
        linear-gradient(rgba(245,158,11,.05) 1px,transparent 1px),
        linear-gradient(90deg,rgba(245,158,11,.05) 1px,transparent 1px);
    background-size: 48px 48px; animation: gridDrift 14s linear infinite; pointer-events: none;
}
@keyframes gridDrift { 0%{transform:translate(0,0)}100%{transform:translate(48px,48px)} }

/* Progress dots */
.reg-progress {
    display: flex; align-items: center; gap: 0; position: relative; z-index: 2; margin-bottom: 28px;
}
.prog-dot {
    width: 28px; height: 28px; border-radius: 50%;
    display: flex; align-items: center; justify-content: center;
    font-size: 12px; font-weight: 800; transition: all .3s;
}
.prog-dot.done { background: var(--orange); color: #111; }
.prog-dot.active { background: var(--orange); color: #111; animation: progPulse 1.5s ease infinite; }
.prog-dot.pending { background: rgba(255,255,255,.1); color: rgba(255,255,255,.3); }
@keyframes progPulse { 0%,100%{box-shadow:0 0 0 0 rgba(245,158,11,.4)} 50%{box-shadow:0 0 0 8px rgba(245,158,11,0)} }
.prog-line { flex: 1; height: 2px; background: rgba(255,255,255,.08); margin: 0 6px; max-width: 60px; }
.prog-line.done { background: var(--orange); }
.auth-left-title { font-size: 28px; font-weight: 900; color: #fff; letter-spacing: -.8px; line-height: 1.2; margin-bottom: 10px; position: relative; z-index: 2; }
.auth-left-title em { color: var(--orange); font-style: normal; }
.auth-left-sub { color: rgba(255,255,255,.4); font-size: 13px; line-height: 1.65; max-width: 300px; position: relative; z-index: 2; }
.auth-trust { display: flex; flex-direction: column; gap: 8px; position: relative; z-index: 2; }
.auth-trust-item { display: flex; align-items: center; gap: 9px; color: rgba(255,255,255,.5); font-size: 12px; font-weight: 500; }
.auth-trust-item i { color: var(--orange); font-size: 11px; width: 14px; }

/* Right */
.auth-right {
    background: #F8F9FA; display: flex; align-items: center; justify-content: center;
    padding: 32px 48px; overflow-y: auto;
}
.auth-form-wrap { width: 100%; max-width: 420px; }

/* Step 1 — role selection */
.role-title { font-size: 22px; font-weight: 900; color: var(--navy); letter-spacing: -.4px; margin-bottom: 4px; }
.role-sub { font-size: 13px; color: var(--text-gray); margin-bottom: 24px; }
.role-cards { display: flex; flex-direction: column; gap: 12px; margin-bottom: 24px; }
.role-card {
    display: flex; align-items: center; gap: 16px;
    background: #fff; border: 2px solid #E5E7EB; border-radius: var(--radius-lg);
    padding: 18px 18px; cursor: pointer; transition: all .2s; position: relative;
}
.role-card:hover { border-color: var(--orange); box-shadow: 0 4px 20px rgba(245,158,11,.1); }
.role-card.selected { border-color: var(--orange); background: rgba(245,158,11,.04); }
.role-card.selected .role-radio { background: var(--orange); border-color: var(--orange); }
.role-card.selected .role-radio::after { display: block; }
.role-icon { font-size: 28px; flex-shrink: 0; }
.role-info { flex: 1; }
.role-name { font-size: 15px; font-weight: 800; color: var(--navy); margin-bottom: 2px; }
.role-desc { font-size: 12px; color: var(--text-gray); }
.role-radio {
    width: 20px; height: 20px; border-radius: 50%;
    border: 2px solid #D1D5DB; transition: all .15s;
    flex-shrink: 0; position: relative;
}
.role-radio::after {
    content: ''; display: none;
    position: absolute; inset: 3px; background: #fff;
    border-radius: 50%;
}

/* Step 2 — form */
.form-title { font-size: 22px; font-weight: 900; color: var(--navy); letter-spacing: -.4px; margin-bottom: 2px; }
.form-account-type { font-size: 12px; color: var(--text-gray); margin-bottom: 20px; }
.form-account-type span { color: var(--orange); font-weight: 700; }
.back-btn {
    display: inline-flex; align-items: center; gap: 5px;
    font-size: 12px; font-weight: 600; color: var(--text-gray);
    cursor: pointer; border: none; background: none; padding: 0; margin-bottom: 16px;
    transition: color .15s;
}
.back-btn:hover { color: var(--navy); }
.form-row { display: grid; grid-template-columns: 1fr 1fr; gap: 12px; }
.form-group { margin-bottom: 14px; }
.form-label { display: block; font-size: 10px; font-weight: 800; color: var(--text-gray); letter-spacing: 1px; text-transform: uppercase; margin-bottom: 6px; }
.form-input-wrap { position: relative; }
.form-icon { position: absolute; left: 13px; top: 50%; transform: translateY(-50%); color: var(--text-light); font-size: 13px; pointer-events: none; }
.form-input {
    width: 100%; padding: 11px 13px 11px 38px;
    background: #fff; border: 1.5px solid #E5E7EB;
    border-radius: var(--radius-md); font-size: 13px; color: var(--navy);
    outline: none; transition: border-color .2s, box-shadow .2s;
    font-family: 'Inter', sans-serif;
}
.form-input:focus { border-color: var(--orange); box-shadow: 0 0 0 3px rgba(245,158,11,.12); }
.show-pass { position: absolute; right: 12px; top: 50%; transform: translateY(-50%); color: var(--text-light); cursor: pointer; font-size: 13px; background: none; border: none; transition: color .2s; }
.show-pass:hover { color: var(--navy); }

.btn-submit {
    width: 100%; padding: 13px; background: var(--navy); color: #fff;
    font-size: 14px; font-weight: 800; border: none; border-radius: var(--radius-md);
    cursor: pointer; display: flex; align-items: center; justify-content: center; gap: 8px;
    transition: transform .15s, box-shadow .15s, background .15s; letter-spacing: .2px; margin-bottom: 12px;
}
.btn-submit:hover { background: #0a1421; transform: translateY(-1px); box-shadow: 0 5px 20px rgba(0,0,0,.2); }
.btn-submit:disabled { opacity: .6; cursor: not-allowed; transform: none; }
.error-box { background: #FEF2F2; border: 1px solid #FECACA; border-radius: var(--radius-md); padding: 10px 14px; font-size: 12px; color: #B91C1C; margin-bottom: 12px; display: flex; align-items: flex-start; gap: 8px; }
.terms-check { display: flex; align-items: flex-start; gap: 8px; font-size: 12px; color: var(--text-gray); margin-bottom: 14px; cursor: pointer; }
.terms-check input { accent-color: var(--orange); margin-top: 2px; width: 15px; height: 15px; flex-shrink: 0; }
.terms-check a { color: var(--orange); font-weight: 600; text-decoration: none; }
.auth-bottom-link { text-align: center; font-size: 13px; color: var(--text-gray); margin-top: 10px; }
.auth-bottom-link a { color: var(--orange); font-weight: 700; text-decoration: none; }

/* Transitions */
.step { transition: opacity .3s, transform .3s; }
.step.out { opacity: 0; transform: translateX(-20px); pointer-events: none; }

@media (max-width: 900px) {
    body { overflow: auto; }
    .auth-wrap { grid-template-columns: 1fr; height: auto; }
    .auth-left { display: none; }
    .auth-right { padding: 28px 20px; min-height: calc(100vh - 64px); }
    .form-row { grid-template-columns: 1fr; }
}
</style>
@endpush

@section('content')
<div class="auth-wrap">

    {{-- LEFT --}}
    <div class="auth-left">
        <div class="auth-left-grid"></div>

        <div>
            <div class="reg-progress" id="reg-progress">
                <div class="prog-dot active" id="dot1">1</div>
                <div class="prog-line" id="line1"></div>
                <div class="prog-dot pending" id="dot2">2</div>
            </div>
            <div class="auth-left-title" id="left-title">Choisissez<br>votre <em>profil</em></div>
            <p class="auth-left-sub" id="left-sub">Rejoignez la plateforme N°1 de location d'engins au Maroc.</p>
        </div>

        <div class="auth-trust">
            <div class="auth-trust-item"><i class="fas fa-check-circle"></i>Inscription 100% gratuite</div>
            <div class="auth-trust-item"><i class="fas fa-check-circle"></i>Mise en ligne en 5 minutes</div>
            <div class="auth-trust-item"><i class="fas fa-check-circle"></i>Support WhatsApp disponible</div>
        </div>
    </div>

    {{-- RIGHT --}}
    <div class="auth-right">
        <div class="auth-form-wrap">

            {{-- STEP 1 : Role --}}
            <div class="step" id="step1">
                <div class="role-title">Qui êtes-vous ?</div>
                <div class="role-sub">Choisissez votre type de compte</div>

                <div class="role-cards">
                    <div class="role-card" id="role-owner" onclick="selectRole('owner')">
                        <div class="role-icon">🏗</div>
                        <div class="role-info">
                            <div class="role-name">Propriétaire</div>
                            <div class="role-desc">Je possède des machines et je veux les louer</div>
                        </div>
                        <div class="role-radio" id="radio-owner"></div>
                    </div>
                    <div class="role-card" id="role-client" onclick="selectRole('client')">
                        <div class="role-icon">🏢</div>
                        <div class="role-info">
                            <div class="role-name">Entreprise / Client</div>
                            <div class="role-desc">Je cherche des machines pour mes chantiers</div>
                        </div>
                        <div class="role-radio" id="radio-client"></div>
                    </div>
                </div>

                <button class="btn-submit" id="continue-btn" disabled onclick="goStep2()">
                    Continuer <i class="fas fa-arrow-right"></i>
                </button>
                <div class="auth-bottom-link">
                    Déjà un compte ? <a href="/login">Se connecter</a>
                </div>
            </div>

            {{-- STEP 2 : Form --}}
            <div class="step" id="step2" style="display:none">
                <button type="button" class="back-btn" onclick="goStep1()">
                    <i class="fas fa-arrow-left"></i> Retour
                </button>
                <div class="form-title">Créez votre compte</div>
                <div class="form-account-type">Compte <span id="role-label">—</span></div>

                <div id="reg-error" class="error-box" style="display:none">
                    <i class="fas fa-exclamation-circle" style="margin-top:1px"></i>
                    <span id="reg-error-msg"></span>
                </div>

                <div class="form-row">
                    <div class="form-group">
                        <label class="form-label">Nom complet</label>
                        <div class="form-input-wrap">
                            <i class="fas fa-user form-icon"></i>
                            <input type="text" id="reg-name" class="form-input" placeholder="Votre nom">
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Entreprise</label>
                        <div class="form-input-wrap">
                            <i class="fas fa-building form-icon"></i>
                            <input type="text" id="reg-company" class="form-input" placeholder="Optionnel">
                        </div>
                    </div>
                </div>

                <div class="form-group">
                    <label class="form-label">Email</label>
                    <div class="form-input-wrap">
                        <i class="fas fa-envelope form-icon"></i>
                        <input type="email" id="reg-email" class="form-input" placeholder="votre@email.com">
                    </div>
                </div>

                <div class="form-group">
                    <label class="form-label">Téléphone / WhatsApp</label>
                    <div class="form-input-wrap">
                        <i class="fas fa-mobile-alt form-icon"></i>
                        <input type="tel" id="reg-phone" class="form-input" placeholder="+212 6 00 00 00 00">
                    </div>
                </div>

                <div class="form-row">
                    <div class="form-group">
                        <label class="form-label">Mot de passe</label>
                        <div class="form-input-wrap">
                            <i class="fas fa-lock form-icon"></i>
                            <input type="password" id="reg-password" class="form-input" placeholder="••••••••">
                            <button type="button" class="show-pass" onclick="togglePass('reg-password',this)"><i class="fas fa-eye"></i></button>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Confirmer</label>
                        <div class="form-input-wrap">
                            <i class="fas fa-lock form-icon"></i>
                            <input type="password" id="reg-confirm" class="form-input" placeholder="••••••••">
                        </div>
                    </div>
                </div>

                <label class="terms-check">
                    <input type="checkbox" id="reg-terms">
                    J'accepte les <a href="#">conditions d'utilisation</a> et la <a href="#">politique de confidentialité</a>
                </label>

                <button class="btn-submit" id="reg-btn" onclick="doRegister()">
                    Créer mon compte <i class="fas fa-arrow-right"></i>
                </button>

                <div style="display:flex;align-items:center;gap:12px;margin-bottom:12px">
                    <div style="flex:1;height:1px;background:#E5E7EB"></div>
                    <span style="font-size:11px;color:var(--text-light);font-weight:600;white-space:nowrap">ou inscrivez-vous via</span>
                    <div style="flex:1;height:1px;background:#E5E7EB"></div>
                </div>

                <div style="display:grid;grid-template-columns:1fr 1fr;gap:10px;margin-bottom:14px">
                    <button onclick="showFlash('Google — à connecter avec votre OAuth App','warning')"
                        style="display:flex;align-items:center;justify-content:center;gap:8px;padding:10px;border:1.5px solid #E5E7EB;border-radius:var(--radius-md);background:#fff;font-size:13px;font-weight:600;color:var(--navy);cursor:pointer;transition:all .15s"
                        onmouseover="this.style.borderColor='#4285F4';this.style.boxShadow='0 0 0 3px rgba(66,133,244,.1)'"
                        onmouseout="this.style.borderColor='#E5E7EB';this.style.boxShadow='none'">
                        <svg width="17" height="17" viewBox="0 0 48 48"><path fill="#EA4335" d="M24 9.5c3.54 0 6.71 1.22 9.21 3.6l6.85-6.85C35.9 2.38 30.47 0 24 0 14.62 0 6.51 5.38 2.56 13.22l7.98 6.19C12.43 13.72 17.74 9.5 24 9.5z"/><path fill="#4285F4" d="M46.98 24.55c0-1.57-.15-3.09-.38-4.55H24v9.02h12.94c-.58 2.96-2.26 5.48-4.78 7.18l7.73 6c4.51-4.18 7.09-10.36 7.09-17.65z"/><path fill="#FBBC05" d="M10.53 28.59c-.48-1.45-.76-2.99-.76-4.59s.27-3.14.76-4.59l-7.98-6.19C.92 16.46 0 20.12 0 24c0 3.88.92 7.54 2.56 10.78l7.97-6.19z"/><path fill="#34A853" d="M24 48c6.48 0 11.93-2.13 15.89-5.81l-7.73-6c-2.18 1.48-4.97 2.31-8.16 2.31-6.26 0-11.57-4.22-13.47-9.91l-7.98 6.19C6.51 42.62 14.62 48 24 48z"/></svg>
                        Google
                    </button>
                    <button onclick="showFlash('Facebook — à connecter avec votre App ID','warning')"
                        style="display:flex;align-items:center;justify-content:center;gap:8px;padding:10px;border:1.5px solid #1877F2;border-radius:var(--radius-md);background:#1877F2;font-size:13px;font-weight:600;color:#fff;cursor:pointer;transition:all .15s"
                        onmouseover="this.style.background='#1466d8'" onmouseout="this.style.background='#1877F2'">
                        <i class="fab fa-facebook-f" style="font-size:15px"></i>
                        Facebook
                    </button>
                </div>

                <div class="auth-bottom-link">
                    Déjà un compte ? <a href="/login">Se connecter</a>
                </div>
            </div>

        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
let selectedRole = null;

function selectRole(role) {
    selectedRole = role;
    ['owner','client'].forEach(r => {
        document.getElementById('role-' + r).classList.toggle('selected', r === role);
        document.getElementById('radio-' + r).querySelector ? null : null;
        document.getElementById('radio-' + r).style.background = r === role ? 'var(--orange)' : '';
        document.getElementById('radio-' + r).style.borderColor = r === role ? 'var(--orange)' : '';
    });
    document.getElementById('continue-btn').disabled = false;
    document.getElementById('continue-btn').style.opacity = '1';
}

function goStep2() {
    if (!selectedRole) return;
    document.getElementById('step1').style.display = 'none';
    document.getElementById('step2').style.display = 'block';
    document.getElementById('role-label').textContent = selectedRole === 'owner' ? 'Propriétaire' : 'Entreprise / Client';
    // Update left panel
    document.getElementById('left-title').innerHTML = 'Créez<br>votre <em>compte</em>';
    document.getElementById('left-sub').textContent = 'Quelques informations pour finaliser votre inscription.';
    document.getElementById('dot1').classList.remove('active');
    document.getElementById('dot1').classList.add('done');
    document.getElementById('dot1').innerHTML = '<i class="fas fa-check" style="font-size:10px"></i>';
    document.getElementById('line1').classList.add('done');
    document.getElementById('dot2').classList.remove('pending');
    document.getElementById('dot2').classList.add('active');
}

function goStep1() {
    document.getElementById('step2').style.display = 'none';
    document.getElementById('step1').style.display = 'block';
    document.getElementById('left-title').innerHTML = 'Choisissez<br>votre <em>profil</em>';
    document.getElementById('left-sub').textContent = 'Rejoignez la plateforme N°1 de location d\'engins au Maroc.';
    document.getElementById('dot1').classList.add('active');
    document.getElementById('dot1').classList.remove('done');
    document.getElementById('dot1').innerHTML = '1';
    document.getElementById('line1').classList.remove('done');
    document.getElementById('dot2').classList.add('pending');
    document.getElementById('dot2').classList.remove('active');
}

function togglePass(id, btn) {
    const inp = document.getElementById(id);
    const isPass = inp.type === 'password';
    inp.type = isPass ? 'text' : 'password';
    btn.querySelector('i').className = isPass ? 'fas fa-eye-slash' : 'fas fa-eye';
}

async function doRegister() {
    const btn    = document.getElementById('reg-btn');
    const errBox = document.getElementById('reg-error');
    const errMsg = document.getElementById('reg-error-msg');

    const name     = document.getElementById('reg-name').value.trim();
    const email    = document.getElementById('reg-email').value.trim();
    const phone    = document.getElementById('reg-phone').value.trim();
    const password = document.getElementById('reg-password').value;
    const confirm  = document.getElementById('reg-confirm').value;
    const terms    = document.getElementById('reg-terms').checked;

    errBox.style.display = 'none';

    if (!name || !email || !password) {
        errMsg.textContent = 'Veuillez remplir tous les champs obligatoires.';
        errBox.style.display = 'flex'; return;
    }
    if (password !== confirm) {
        errMsg.textContent = 'Les mots de passe ne correspondent pas.';
        errBox.style.display = 'flex'; return;
    }
    if (password.length < 8) {
        errMsg.textContent = 'Le mot de passe doit contenir au moins 8 caractères.';
        errBox.style.display = 'flex'; return;
    }
    if (!terms) {
        errMsg.textContent = 'Veuillez accepter les conditions d\'utilisation.';
        errBox.style.display = 'flex'; return;
    }

    btn.disabled = true;
    btn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Création du compte...';

    try {
        const res = await API.post('/api/register', {
            name, email, phone,
            password, password_confirmation: confirm,
            role: selectedRole
        });
        if (!res.ok) {
            const errors = res.data?.errors;
            const firstErr = errors ? Object.values(errors)[0][0] : (res.data?.message || 'Erreur lors de l\'inscription');
            throw new Error(firstErr);
        }

        localStorage.setItem('auth_token', res.data.token);
        localStorage.setItem('auth_user', JSON.stringify(res.data.user));

        showFlash('Compte créé avec succès ! Bienvenue ' + res.data.user.name.split(' ')[0] + ' 🎉', 'success');
        setTimeout(() => {
            window.location.href = selectedRole === 'owner' ? '/dashboard/owner' : '/dashboard/client';
        }, 1000);
    } catch(e) {
        errMsg.textContent = e.message;
        errBox.style.display = 'flex';
        btn.disabled = false;
        btn.innerHTML = 'Créer mon compte <i class="fas fa-arrow-right"></i>';
    }
}

if (getToken()) window.location.href = '/';
</script>
@endpush