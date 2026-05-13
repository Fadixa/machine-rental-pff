@extends('layouts.app')
@section('title', 'Connexion — Rentify')

@push('styles')
<style>
body { overflow: hidden; }
.auth-wrap {
    display: grid;
    grid-template-columns: 1fr 1fr;
    height: calc(100vh - 64px);
}

/* ── Left panel ── */
.auth-left {
    background: var(--navy);
    position: relative;
    overflow: hidden;
    display: flex;
    flex-direction: column;
    justify-content: space-between;
    padding: 48px 52px;
}
.auth-left-grid {
    position: absolute; inset: -64px;
    background-image:
        linear-gradient(rgba(245,158,11,.05) 1px, transparent 1px),
        linear-gradient(90deg, rgba(245,158,11,.05) 1px, transparent 1px);
    background-size: 48px 48px;
    animation: gridDrift 14s linear infinite;
    pointer-events: none;
}
@keyframes gridDrift {
    0%   { transform: translate(0,0); }
    100% { transform: translate(48px,48px); }
}
/* Concentric circles (login left) */
.circles-wrap {
    position: absolute;
    right: -80px; top: 50%; transform: translateY(-50%);
    pointer-events: none;
}
.ring {
    position: absolute;
    border-radius: 50%;
    border: 1px solid rgba(245,158,11,0.1);
    top: 50%; left: 50%;
    transform: translate(-50%,-50%);
    animation: ringPulse var(--d,6s) ease-in-out infinite;
}
@keyframes ringPulse {
    0%,100% { transform: translate(-50%,-50%) scale(1); opacity: .6; }
    50%      { transform: translate(-50%,-50%) scale(1.05); opacity: 1; }
}
.auth-left-content { position: relative; z-index: 2; }
.auth-badge {
    display: inline-flex; align-items: center; gap: 7px;
    background: rgba(245,158,11,.15); border: 1px solid rgba(245,158,11,.35);
    color: var(--orange); font-size: 10px; font-weight: 700;
    padding: 5px 13px; border-radius: 100px; letter-spacing: .5px;
    margin-bottom: 20px;
}
.auth-badge-dot { width:6px;height:6px;background:var(--orange);border-radius:50%;animation:dotP 1.5s ease infinite; }
@keyframes dotP { 0%,100%{opacity:1}50%{opacity:.3} }
.auth-left-title {
    font-size: 32px; font-weight: 900; color: #fff;
    letter-spacing: -1px; line-height: 1.15; margin-bottom: 12px;
}
.auth-left-title em { color: var(--orange); font-style: normal; }
.auth-left-sub { color: rgba(255,255,255,.4); font-size: 14px; line-height: 1.65; max-width: 340px; }
.auth-stats {
    display: flex; gap: 28px; position: relative; z-index: 2;
}
.auth-stat-n { color: var(--orange); font-size: 22px; font-weight: 900; letter-spacing:-1px; }
.auth-stat-l { color: rgba(255,255,255,.35); font-size: 10px; font-weight: 600; text-transform: uppercase; letter-spacing: .5px; }
.auth-trust {
    display: flex; flex-direction: column; gap: 8px;
    position: relative; z-index: 2;
}
.auth-trust-item {
    display: flex; align-items: center; gap: 9px;
    color: rgba(255,255,255,.5); font-size: 12px; font-weight: 500;
}
.auth-trust-item i { color: var(--orange); font-size: 11px; width: 14px; }

/* ── Right panel ── */
.auth-right {
    background: #F8F9FA;
    display: flex; align-items: center; justify-content: center;
    padding: 40px 48px;
    overflow-y: auto;
}
.auth-form-wrap { width: 100%; max-width: 400px; }
.auth-form-title {
    font-size: 26px; font-weight: 900; color: var(--navy);
    letter-spacing: -.5px; margin-bottom: 4px;
}
.auth-form-title span { font-size: 22px; }
.auth-form-sub { font-size: 13px; color: var(--text-gray); margin-bottom: 28px; }

/* Tabs Email / Téléphone */
.auth-tabs {
    display: flex; background: #fff; border: 1px solid #E5E7EB;
    border-radius: var(--radius-md); padding: 3px; margin-bottom: 22px;
}
.auth-tab {
    flex: 1; text-align: center; padding: 8px 6px;
    font-size: 12px; font-weight: 600; color: var(--text-gray);
    border-radius: 7px; cursor: pointer; transition: all .15s;
    display: flex; align-items: center; justify-content: center; gap: 5px;
}
.auth-tab.active { background: var(--navy); color: #fff; box-shadow: var(--shadow-sm); }

/* Form fields */
.form-group { margin-bottom: 16px; }
.form-label {
    display: block; font-size: 10px; font-weight: 800;
    color: var(--text-gray); letter-spacing: 1px; text-transform: uppercase; margin-bottom: 6px;
}
.form-input-wrap { position: relative; }
.form-input-icon {
    position: absolute; left: 14px; top: 50%; transform: translateY(-50%);
    color: var(--text-light); font-size: 14px; pointer-events: none;
}
.form-input {
    width: 100%; padding: 12px 14px 12px 42px;
    background: #fff; border: 1.5px solid #E5E7EB;
    border-radius: var(--radius-md); font-size: 14px; color: var(--navy);
    outline: none; transition: border-color .2s, box-shadow .2s;
    font-family: 'Inter', sans-serif;
}
.form-input:focus { border-color: var(--orange); box-shadow: 0 0 0 3px rgba(245,158,11,.12); }
.form-input.error { border-color: #EF4444; }
.input-error { font-size: 11px; color: #EF4444; margin-top: 4px; font-weight: 500; }
.show-pass {
    position: absolute; right: 14px; top: 50%; transform: translateY(-50%);
    color: var(--text-light); cursor: pointer; font-size: 14px; background: none; border: none;
    transition: color .2s;
}
.show-pass:hover { color: var(--navy); }

.form-row-flex { display: flex; justify-content: space-between; align-items: center; margin-bottom: 16px; }
.form-check { display: flex; align-items: center; gap: 7px; font-size: 13px; color: var(--text-gray); cursor: pointer; }
.form-check input { accent-color: var(--orange); width: 15px; height: 15px; cursor: pointer; }
.form-forgot { font-size: 12px; color: var(--orange); font-weight: 600; text-decoration: none; }
.form-forgot:hover { text-decoration: underline; }

/* Submit */
.btn-submit {
    width: 100%; padding: 13px; background: var(--navy);
    color: #fff; font-size: 14px; font-weight: 800;
    border: none; border-radius: var(--radius-md); cursor: pointer;
    display: flex; align-items: center; justify-content: center; gap: 8px;
    transition: transform .15s, box-shadow .15s, background .15s;
    letter-spacing: .2px; margin-bottom: 16px;
}
.btn-submit:hover { background: #0a1421; transform: translateY(-1px); box-shadow: 0 5px 20px rgba(0,0,0,.2); }
.btn-submit:disabled { opacity: .6; cursor: not-allowed; transform: none; }

/* Divider */
.divider-or {
    display: flex; align-items: center; gap: 12px;
    margin-bottom: 14px;
}
.divider-line { flex: 1; height: 1px; background: #E5E7EB; }
.divider-text { font-size: 11px; color: var(--text-light); font-weight: 600; white-space: nowrap; }

/* Error box */
.error-box {
    background: #FEF2F2; border: 1px solid #FECACA;
    border-radius: var(--radius-md); padding: 10px 14px;
    font-size: 12px; color: #B91C1C; font-weight: 500;
    margin-bottom: 14px; display: flex; align-items: center; gap: 8px;
}

/* Bottom link */
.auth-bottom-link { text-align: center; font-size: 13px; color: var(--text-gray); margin-top: 16px; }
.auth-bottom-link a { color: var(--orange); font-weight: 700; text-decoration: none; }
.auth-bottom-link a:hover { text-decoration: underline; }

@media (max-width: 900px) {
    body { overflow: auto; }
    .auth-wrap { grid-template-columns: 1fr; height: auto; }
    .auth-left { display: none; }
    .auth-right { padding: 32px 20px; min-height: calc(100vh - 64px); }
}
</style>
@endpush

@section('content')
<div class="auth-wrap">

    {{-- ── LEFT ── --}}
    <div class="auth-left">
        <div class="auth-left-grid"></div>
        <div class="circles-wrap">
            @foreach([500,400,300,220,140] as $i => $size)
            <div class="ring" style="width:{{$size}}px;height:{{$size}}px;--d:{{ 5+$i }}s"></div>
            @endforeach
        </div>

        <div class="auth-left-content">
            <div class="auth-badge"><div class="auth-badge-dot"></div>PLATEFORME N°1 MAROC</div>
            <h2 class="auth-left-title">Gérez votre flotte<br>d'engins <em>en ligne</em></h2>
            <p class="auth-left-sub">La plateforme de référence pour louer et gérer vos engins de chantier au Maroc.</p>
        </div>

        <div class="auth-stats">
            <div><div class="auth-stat-n">500+</div><div class="auth-stat-l">Machines</div></div>
            <div><div class="auth-stat-n">120+</div><div class="auth-stat-l">Propriétaires</div></div>
            <div><div class="auth-stat-n">98%</div><div class="auth-stat-l">Satisfaction</div></div>
        </div>

        <div class="auth-trust">
            <div class="auth-trust-item"><i class="fas fa-check-circle"></i>Inscription 100% gratuite</div>
            <div class="auth-trust-item"><i class="fas fa-check-circle"></i>Mise en ligne en 5 minutes</div>
            <div class="auth-trust-item"><i class="fas fa-check-circle"></i>Support WhatsApp disponible</div>
        </div>
    </div>

    {{-- ── RIGHT ── --}}
    <div class="auth-right">
        <div class="auth-form-wrap">
            <div class="auth-form-title">Bon retour ! 👋</div>
            <div class="auth-form-sub">Connectez-vous à votre espace</div>

            {{-- Tabs --}}
            <div class="auth-tabs">
                <div class="auth-tab active" onclick="setTab('email',this)">
                    <i class="fas fa-envelope"></i> Email
                </div>
                <div class="auth-tab" onclick="setTab('phone',this)">
                    <i class="fas fa-mobile-alt"></i> Téléphone
                </div>
            </div>

            {{-- Error --}}
            <div id="login-error" class="error-box" style="display:none">
                <i class="fas fa-exclamation-circle"></i>
                <span id="login-error-msg">Identifiants incorrects</span>
            </div>

            {{-- Email field --}}
            <div class="form-group" id="email-group">
                <label class="form-label">Email</label>
                <div class="form-input-wrap">
                    <i class="fas fa-envelope form-input-icon"></i>
                    <input type="email" id="login-email" class="form-input" placeholder="votre@email.com">
                </div>
            </div>

            {{-- Phone field (hidden by default) --}}
            <div class="form-group" id="phone-group" style="display:none">
                <label class="form-label">Téléphone / WhatsApp</label>
                <div class="form-input-wrap">
                    <i class="fas fa-mobile-alt form-input-icon"></i>
                    <input type="tel" id="login-phone" class="form-input" placeholder="+212 6 00 00 00 00">
                </div>
            </div>

            {{-- Password --}}
            <div class="form-group">
                <label class="form-label">Mot de passe</label>
                <div class="form-input-wrap">
                    <i class="fas fa-lock form-input-icon"></i>
                    <input type="password" id="login-password" class="form-input" placeholder="••••••••"
                           onkeydown="if(event.key==='Enter') doLogin()">
                    <button type="button" class="show-pass" onclick="togglePass('login-password',this)">
                        <i class="fas fa-eye"></i>
                    </button>
                </div>
            </div>

            {{-- Remember + Forgot --}}
            <div class="form-row-flex">
                <label class="form-check">
                    <input type="checkbox" id="remember"> Se souvenir de moi
                </label>
                <a href="#" class="form-forgot">Mot de passe oublié ?</a>
            </div>

            {{-- Submit --}}
            <button class="btn-submit" id="login-btn" onclick="doLogin()">
                Se connecter <i class="fas fa-arrow-right"></i>
            </button>

            <div class="divider-or">
                <div class="divider-line"></div>
                <div class="divider-text">ou continuer avec</div>
                <div class="divider-line"></div>
            </div>

            {{-- Social login buttons --}}
            <div style="display:grid;grid-template-columns:1fr 1fr;gap:10px;margin-bottom:16px">
                <button onclick="showFlash('Google login — à connecter avec votre OAuth','warning')"
                    style="display:flex;align-items:center;justify-content:center;gap:8px;padding:10px 14px;border:1.5px solid #E5E7EB;border-radius:var(--radius-md);background:#fff;font-size:13px;font-weight:600;color:var(--navy);cursor:pointer;transition:all .15s"
                    onmouseover="this.style.borderColor='#4285F4';this.style.boxShadow='0 0 0 3px rgba(66,133,244,.1)'"
                    onmouseout="this.style.borderColor='#E5E7EB';this.style.boxShadow='none'">
                    <svg width="18" height="18" viewBox="0 0 48 48"><path fill="#EA4335" d="M24 9.5c3.54 0 6.71 1.22 9.21 3.6l6.85-6.85C35.9 2.38 30.47 0 24 0 14.62 0 6.51 5.38 2.56 13.22l7.98 6.19C12.43 13.72 17.74 9.5 24 9.5z"/><path fill="#4285F4" d="M46.98 24.55c0-1.57-.15-3.09-.38-4.55H24v9.02h12.94c-.58 2.96-2.26 5.48-4.78 7.18l7.73 6c4.51-4.18 7.09-10.36 7.09-17.65z"/><path fill="#FBBC05" d="M10.53 28.59c-.48-1.45-.76-2.99-.76-4.59s.27-3.14.76-4.59l-7.98-6.19C.92 16.46 0 20.12 0 24c0 3.88.92 7.54 2.56 10.78l7.97-6.19z"/><path fill="#34A853" d="M24 48c6.48 0 11.93-2.13 15.89-5.81l-7.73-6c-2.18 1.48-4.97 2.31-8.16 2.31-6.26 0-11.57-4.22-13.47-9.91l-7.98 6.19C6.51 42.62 14.62 48 24 48z"/></svg>
                    Google
                </button>
                <button onclick="showFlash('Facebook login — à connecter avec votre App ID','warning')"
                    style="display:flex;align-items:center;justify-content:center;gap:8px;padding:10px 14px;border:1.5px solid #1877F2;border-radius:var(--radius-md);background:#1877F2;font-size:13px;font-weight:600;color:#fff;cursor:pointer;transition:all .15s"
                    onmouseover="this.style.background='#1466d8'" onmouseout="this.style.background='#1877F2'">
                    <i class="fab fa-facebook-f" style="font-size:16px"></i>
                    Facebook
                </button>
            </div>

            {{-- Demo logins --}}
            <div style="background:#F9FAFB;border:1px solid #F0F0F0;border-radius:var(--radius-md);padding:10px 14px;margin-bottom:14px">
                <div style="font-size:10px;font-weight:700;color:var(--text-light);letter-spacing:.8px;text-transform:uppercase;margin-bottom:8px">Accès démo rapide</div>
                <div style="display:grid;grid-template-columns:1fr 1fr;gap:8px">
                    <button onclick="fillDemo('client@test.com','password')"
                        style="padding:8px;border:1px solid #E5E7EB;border-radius:7px;background:#fff;font-size:12px;font-weight:600;color:var(--navy);cursor:pointer;transition:all .15s"
                        onmouseover="this.style.borderColor='var(--orange)'" onmouseout="this.style.borderColor='#E5E7EB'">
                        🙋 Demo Client
                    </button>
                    <button onclick="fillDemo('owner@test.com','password')"
                        style="padding:8px;border:1px solid #E5E7EB;border-radius:7px;background:#fff;font-size:12px;font-weight:600;color:var(--navy);cursor:pointer;transition:all .15s"
                        onmouseover="this.style.borderColor='var(--orange)'" onmouseout="this.style.borderColor='#E5E7EB'">
                        🏗 Demo Propriétaire
                    </button>
                </div>
            </div>

            <div class="auth-bottom-link">
                Pas encore de compte ? <a href="/register">Créer un compte gratuit</a>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
let loginTab = 'email';

function setTab(tab, btn) {
    loginTab = tab;
    document.querySelectorAll('.auth-tab').forEach(t => t.classList.remove('active'));
    btn.classList.add('active');
    document.getElementById('email-group').style.display = tab === 'email' ? '' : 'none';
    document.getElementById('phone-group').style.display = tab === 'phone' ? '' : 'none';
}

function togglePass(id, btn) {
    const inp = document.getElementById(id);
    const isPass = inp.type === 'password';
    inp.type = isPass ? 'text' : 'password';
    btn.querySelector('i').className = isPass ? 'fas fa-eye-slash' : 'fas fa-eye';
}

function fillDemo(email, pass) {
    document.getElementById('login-email').value = email;
    document.getElementById('login-password').value = pass;
}

async function doLogin() {
    const btn = document.getElementById('login-btn');
    const errBox = document.getElementById('login-error');
    const errMsg = document.getElementById('login-error-msg');
    const email    = document.getElementById('login-email').value.trim();
    const password = document.getElementById('login-password').value;

    if (!email || !password) {
        errMsg.textContent = 'Veuillez remplir tous les champs.';
        errBox.style.display = 'flex'; return;
    }

    btn.disabled = true;
    btn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Connexion...';
    errBox.style.display = 'none';

    try {
        const res = await API.post('/api/login', { email, password });

        // ✅ API.post كترجع { ok, status, data }
        if (!res.ok) throw new Error(res.data?.message || 'Identifiants incorrects');

        // ✅ الداتا فـ res.data
        localStorage.setItem('auth_token', res.data.token);
        localStorage.setItem('auth_user', JSON.stringify(res.data.user));

        showFlash('Connexion réussie ! Bienvenue ' + res.data.user.name.split(' ')[0], 'success');

        setTimeout(() => {
            window.location.href = res.data.user.role === 'owner'
                ? '/dashboard/owner' : '/dashboard/client';
        }, 800);

    } catch(e) {
        errMsg.textContent = e.message;
        errBox.style.display = 'flex';
        btn.disabled = false;
        btn.innerHTML = 'Se connecter <i class="fas fa-arrow-right"></i>';
    }
}

// Redirect if already logged in
if (getToken()) {
    const u = getUser();
    window.location.href = u?.role === 'owner' ? '/dashboard/owner' : '/dashboard/client';
}
</script>
@endpush