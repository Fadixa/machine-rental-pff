<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Rentify — Connexion</title>
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@400;600;700;900&family=Cormorant+Garamond:wght@300;400;500;600&family=DM+Sans:wght@300;400;500;600&display=swap" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" rel="stylesheet">

<style>
/* ═══════════════════════════════════════════════
   RENTIFY — SLIDING AUTH — GOLD / CRÈME
   Exactement comme le template vert mais Rentify
═══════════════════════════════════════════════ */
:root {
    --gold:       #D9A74A;
    --gold-dk:    #B8892E;
    --gold-light: #F0D68A;
    --gold-glow:  rgba(217,167,74,.22);
    --cream:      #FCFBF7;
    --cream2:     #F5F2EA;
    --cream3:     #EDE8DA;
    --white:      #FFFFFF;
    --charcoal:   #222222;
    --mid:        #666060;
    --light:      #9E9890;
    --red:        #C0392B;
    --radius-lg:  22px;
    --radius:     12px;
    --trans:      0.65s cubic-bezier(.77,0,.175,1);
}

*, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }

body {
    font-family: 'DM Sans', sans-serif;
    background: var(--cream2);
    min-height: 100vh;
    display: flex;
    align-items: center;
    justify-content: center;
    padding: 1.5rem;
}

/* ═══════════ OUTER WRAPPER ═══════════ */
.auth-wrapper {
    width: 100%;
    max-width: 960px;
    height: 580px;
    position: relative;
    border-radius: var(--radius-lg);
    border: 1px solid var(--gold);
    background: var(--cream);
    overflow: hidden;
    box-shadow:
        0 30px 80px rgba(0,0,0,.10),
        0 0 0 4px rgba(217,167,74,.07),
        inset 0 0 60px rgba(217,167,74,.03);
}

/* ═══════════════════════════════════════
   FORMS CONTAINER — two halves, side by side
   Left = Login form | Right = Register form
═══════════════════════════════════════ */
.forms-wrap {
    position: absolute;
    inset: 0;
    display: flex;
    width: 100%;
    height: 100%;
}

/* ── SHARED FORM PANEL ── */
.form-panel {
    width: 50%;
    flex-shrink: 0;
    background: var(--white);
    display: flex;
    align-items: center;
    justify-content: center;
    padding: 3rem 3.5rem;
    position: relative;
    overflow: hidden;
}

/* Login stays on left, register on right */
.panel-login  { border-radius: var(--radius-lg) 0 0 var(--radius-lg); }
.panel-register { border-radius: 0 var(--radius-lg) var(--radius-lg) 0; }

.form-inner {
    width: 100%;
    max-width: 320px;
}

/* ── TITLES ── */
.form-welcome {
    font-family: 'Playfair Display', serif;
    font-size: 2.6rem;
    font-weight: 900;
    color: var(--gold-dk);
    line-height: 1.1;
    margin-bottom: .3rem;
    letter-spacing: -.01em;
}
.form-subtitle {
    font-size: .83rem;
    color: var(--mid);
    margin-bottom: 1.8rem;
}

/* ── INPUTS ── */
.field-group {
    margin-bottom: 1rem;
}
.field-label {
    display: block;
    font-size: .72rem;
    font-weight: 600;
    letter-spacing: .12em;
    text-transform: uppercase;
    color: var(--gold-dk);
    margin-bottom: .4rem;
}
.field-wrap {
    position: relative;
}
.field-icon {
    position: absolute;
    left: 1rem;
    top: 50%;
    transform: translateY(-50%);
    color: var(--light);
    font-size: .82rem;
    pointer-events: none;
    transition: color .2s;
}
.field-input {
    width: 100%;
    padding: .72rem 1rem .72rem 2.5rem;
    border: 1.5px solid var(--cream3);
    border-radius: var(--radius);
    font-family: 'DM Sans', sans-serif;
    font-size: .875rem;
    color: var(--charcoal);
    background: var(--cream);
    outline: none;
    transition: border-color .2s, box-shadow .2s, background .2s;
    -webkit-appearance: none;
}
.field-input::placeholder { color: var(--light); }
.field-input:focus {
    border-color: var(--gold);
    box-shadow: 0 0 0 3.5px var(--gold-glow);
    background: var(--white);
}
.field-wrap:focus-within .field-icon { color: var(--gold-dk); }
.field-input.is-error { border-color: var(--red); }

.field-error {
    display: none;
    font-size: .72rem;
    color: var(--red);
    margin-top: .3rem;
    gap: .3rem;
    align-items: center;
}
.field-error.show { display: flex; }

/* Password toggle */
.pw-toggle {
    position: absolute;
    right: .9rem;
    top: 50%;
    transform: translateY(-50%);
    color: var(--light);
    cursor: pointer;
    background: none;
    border: none;
    font-size: .82rem;
    padding: 0;
    transition: color .2s;
}
.pw-toggle:hover { color: var(--charcoal); }

/* ── FORGOT ── */
.row-remember {
    display: flex;
    align-items: center;
    justify-content: space-between;
    margin-bottom: 1.4rem;
    margin-top: .2rem;
}
.remember-lbl {
    display: flex;
    align-items: center;
    gap: .45rem;
    font-size: .78rem;
    color: var(--mid);
    cursor: pointer;
    user-select: none;
}
.remember-lbl input { accent-color: var(--gold); width:14px; height:14px; }
.forgot-link {
    font-size: .78rem;
    color: var(--gold-dk);
    font-weight: 500;
    text-decoration: none;
    transition: opacity .2s;
}
.forgot-link:hover { opacity: .7; }

/* ── BUTTONS ── */
.btn-main {
    width: 100%;
    padding: .8rem;
    background: var(--gold);
    color: var(--charcoal);
    border: none;
    border-radius: var(--radius);
    font-family: 'DM Sans', sans-serif;
    font-size: .875rem;
    font-weight: 700;
    letter-spacing: .08em;
    text-transform: uppercase;
    cursor: pointer;
    transition: background .2s, transform .15s, box-shadow .2s;
    box-shadow: 0 4px 18px rgba(217,167,74,.35);
    display: flex;
    align-items: center;
    justify-content: center;
    gap: .5rem;
    position: relative;
    overflow: hidden;
}
.btn-main::after {
    content: '';
    position: absolute;
    inset: 0;
    background: linear-gradient(rgba(255,255,255,.15), transparent);
    opacity: 0;
    transition: opacity .2s;
}
.btn-main:hover {
    background: var(--gold-dk);
    transform: translateY(-1px);
    box-shadow: 0 8px 24px rgba(217,167,74,.45);
}
.btn-main:hover::after { opacity: 1; }
.btn-main:active { transform: translateY(0); }

.btn-spinner {
    width: 16px; height: 16px;
    border: 2px solid rgba(34,34,34,.2);
    border-top-color: var(--charcoal);
    border-radius: 50%;
    animation: spin .6s linear infinite;
    display: none;
    position: absolute;
}
@keyframes spin { to { transform: rotate(360deg); } }
.btn-main.loading .btn-label { opacity: 0; }
.btn-main.loading .btn-spinner { display: block; }

/* OR divider */
.or-row {
    display: flex;
    align-items: center;
    gap: .8rem;
    margin: 1.1rem 0;
    color: var(--light);
    font-size: .75rem;
    font-weight: 500;
    letter-spacing: .08em;
}
.or-row::before, .or-row::after {
    content: '';
    flex: 1;
    height: 1px;
    background: var(--cream3);
}

/* Google button */
.btn-google {
    width: 100%;
    padding: .7rem;
    background: var(--cream);
    border: 1.5px solid var(--cream3);
    border-radius: var(--radius);
    cursor: pointer;
    display: flex;
    align-items: center;
    justify-content: center;
    gap: .6rem;
    font-family: 'DM Sans', sans-serif;
    font-size: .83rem;
    font-weight: 500;
    color: var(--charcoal);
    transition: border-color .2s, box-shadow .2s;
    margin-bottom: 1.2rem;
}
.btn-google:hover {
    border-color: var(--gold);
    box-shadow: 0 0 0 3px var(--gold-glow);
}
.btn-google svg { width: 18px; height: 18px; }

/* Switch link text */
.switch-text {
    font-size: .8rem;
    color: var(--mid);
    text-align: center;
}
.switch-text strong {
    color: var(--gold-dk);
    font-weight: 700;
    cursor: pointer;
    text-decoration: none;
    transition: opacity .2s;
}
.switch-text strong:hover { opacity: .7; }

/* ═══════════════════════════════════════
   OVERLAY PANEL — The sliding logo cover
═══════════════════════════════════════ */
.overlay-wrap {
    position: absolute;
    top: 0;
    left: 50%; /* starts on right */
    width: 50%;
    height: 100%;
    z-index: 100;
    overflow: hidden;
    border-radius: 0 var(--radius-lg) var(--radius-lg) 0;
    transition: transform var(--trans), border-radius var(--trans);
    will-change: transform;
}

/* When register mode: slide overlay to the LEFT */
.auth-wrapper.register-mode .overlay-wrap {
    transform: translateX(-100%);
    border-radius: var(--radius-lg) 0 0 var(--radius-lg);
}

/* Overlay background — cream + subtle gold pattern */
.overlay-bg {
    position: absolute;
    inset: 0;
    background:
        radial-gradient(ellipse at 30% 20%, rgba(217,167,74,.18) 0%, transparent 55%),
        radial-gradient(ellipse at 80% 80%, rgba(217,167,74,.12) 0%, transparent 50%),
        linear-gradient(145deg, #FCFBF7 0%, #F5F2EA 50%, #EDE8DA 100%);
}

/* Subtle geometric circles decoration */
.overlay-deco {
    position: absolute;
    inset: 0;
    pointer-events: none;
    overflow: hidden;
}
.overlay-deco::before {
    content: '';
    position: absolute;
    width: 340px; height: 340px;
    border-radius: 50%;
    border: 1px solid rgba(217,167,74,.18);
    top: -80px; right: -80px;
}
.overlay-deco::after {
    content: '';
    position: absolute;
    width: 220px; height: 220px;
    border-radius: 50%;
    border: 1px solid rgba(217,167,74,.12);
    bottom: -50px; left: -50px;
}

/* Inner content panels (left + right messages) */
.overlay-inner {
    position: absolute;
    inset: 0;
    display: flex;
    width: 200%;
    transition: transform var(--trans);
    will-change: transform;
    transform: translateX(-50%);  /* ← default: show right panel */
}
.auth-wrapper.register-mode .overlay-inner {
    transform: translateX(0);     /* ← register: show left panel */
}
.overlay-panel {
    width: 50%;
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    padding: 2.5rem 2rem;
    position: relative;
    z-index: 1;
    text-align: center;
}

/* LOGO */
.logo-ring {
    width: 130px; height: 130px;
    border-radius: 50%;
    border: 1.5px solid var(--gold);
    display: flex;
    align-items: center;
    justify-content: center;
    margin-bottom: 1.5rem;
    background: rgba(255,255,255,.65);
    box-shadow: 0 0 0 6px rgba(217,167,74,.08);
    overflow: hidden;
    flex-shrink: 0;
}
.logo-ring img {
    width: 90px; height: 90px;
    object-fit: contain;
    border-radius: 50%;
}
/* Fallback if no image */
.logo-ring-svg {
    width: 60px; height: 60px;
    color: var(--gold);
}

.brand-name {
    font-family: 'Playfair Display', serif;
    font-size: 2.2rem;
    font-weight: 900;
    letter-spacing: .04em;
    margin-bottom: .35rem;
}
.brand-name .rent { color: var(--charcoal); }
.brand-name .ify  { color: var(--gold); }

.brand-tagline {
    font-size: .8rem;
    color: var(--mid);
    letter-spacing: .05em;
    margin-bottom: 2rem;
    max-width: 200px;
    line-height: 1.6;
}

/* Overlay CTA button */
.btn-overlay {
    padding: .7rem 2rem;
    border: 1.5px solid var(--gold);
    border-radius: 100px;
    background: transparent;
    color: var(--gold-dk);
    font-family: 'DM Sans', sans-serif;
    font-size: .82rem;
    font-weight: 700;
    letter-spacing: .1em;
    text-transform: uppercase;
    cursor: pointer;
    transition: background .2s, color .2s, transform .15s;
}
.btn-overlay:hover {
    background: var(--gold);
    color: var(--charcoal);
    transform: translateY(-1px);
}

.overlay-note {
    font-size: .78rem;
    color: var(--mid);
    margin-bottom: .9rem;
}

/* ═══════════════════════════════════════
   REGISTER MULTI-STEP
═══════════════════════════════════════ */
.step-wrap { width: 100%; }

/* Stepper */
.stepper {
    display: flex;
    align-items: center;
    justify-content: center;
    gap: .5rem;
    margin-bottom: 1.5rem;
}
.step-dot {
    width: 26px; height: 26px;
    border-radius: 50%;
    border: 1.5px solid var(--cream3);
    background: var(--cream);
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: .7rem;
    font-weight: 700;
    color: var(--light);
    transition: all .3s;
    flex-shrink: 0;
}
.step-dot.active {
    border-color: var(--gold);
    background: var(--gold);
    color: var(--charcoal);
    box-shadow: 0 0 0 3px var(--gold-glow);
}
.step-dot.done {
    border-color: var(--gold-dk);
    background: var(--gold-dk);
    color: white;
}
.step-line {
    height: 1.5px;
    width: 40px;
    background: var(--cream3);
    border-radius: 2px;
    transition: background .3s;
}
.step-line.done { background: var(--gold-dk); }

/* Step 1 — Role Selection */
.step-1 { display: block; }
.step-2 { display: none; opacity: 0; transform: translateX(20px); transition: opacity .35s, transform .35s; }
.step-2.visible { display: block; opacity: 1; transform: translateX(0); }

.step-heading {
    font-family: 'Playfair Display', serif;
    font-size: 1.7rem;
    font-weight: 700;
    color: var(--gold-dk);
    margin-bottom: .3rem;
    letter-spacing: .02em;
}
.step-sub {
    font-size: .8rem;
    color: var(--mid);
    margin-bottom: 1.3rem;
}

/* Role cards */
.role-cards {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: .75rem;
    margin-bottom: 1.3rem;
}
.role-card {
    border: 1.5px solid var(--cream3);
    border-radius: var(--radius);
    background: var(--cream);
    padding: 1rem .75rem;
    cursor: pointer;
    text-align: center;
    transition: all .2s;
    position: relative;
    overflow: hidden;
}
.role-card::before {
    content: '';
    position: absolute;
    inset: 0;
    background: linear-gradient(135deg, rgba(217,167,74,.06), transparent);
    opacity: 0;
    transition: opacity .2s;
}
.role-card:hover {
    border-color: var(--gold-light);
    background: white;
}
.role-card:hover::before { opacity: 1; }
.role-card.selected {
    border-color: var(--gold);
    border-width: 2px;
    background: white;
    box-shadow: 0 0 0 3px var(--gold-glow);
}
.role-card.selected::before { opacity: 1; }

.role-card-icon {
    width: 42px; height: 42px;
    border-radius: 10px;
    background: var(--cream2);
    display: flex;
    align-items: center;
    justify-content: center;
    margin: 0 auto .65rem;
    font-size: 1.1rem;
    color: var(--mid);
    transition: background .2s, color .2s;
}
.role-card.selected .role-card-icon {
    background: var(--gold);
    color: var(--charcoal);
}
.role-card-title {
    font-size: .82rem;
    font-weight: 700;
    color: var(--charcoal);
    display: block;
    margin-bottom: .25rem;
}
.role-card-desc {
    font-size: .7rem;
    color: var(--mid);
    line-height: 1.4;
    display: block;
}

/* Check badge on selected */
.role-check {
    position: absolute;
    top: .5rem; right: .5rem;
    width: 16px; height: 16px;
    border-radius: 50%;
    background: var(--gold);
    color: var(--charcoal);
    font-size: .55rem;
    display: none;
    align-items: center;
    justify-content: center;
}
.role-card.selected .role-check { display: flex; }

/* Alert */
.alert-box {
    padding: .65rem .9rem;
    border-radius: var(--radius);
    font-size: .78rem;
    margin-bottom: 1rem;
    display: none;
    align-items: flex-start;
    gap: .5rem;
}
.alert-box.error {
    background: #fdf2f2;
    border: 1px solid rgba(192,57,43,.2);
    color: var(--red);
}

/* ═══════════ RESPONSIVE ═══════════ */
@media (max-width: 768px) {
    body { align-items: flex-start; padding: 1rem; }
    .auth-wrapper {
        height: auto;
        min-height: 100vh;
        border-radius: var(--radius-lg);
        flex-direction: column;
    }
    .forms-wrap { position: relative; flex-direction: column; }
    .form-panel {
        width: 100%;
        border-radius: 0;
        padding: 2.5rem 1.5rem;
    }
    .panel-login { display: block; }
    .panel-register { display: none; }
    .auth-wrapper.register-mode .panel-login { display: none; }
    .auth-wrapper.register-mode .panel-register { display: flex; }
    .overlay-wrap { display: none; }
    .mobile-toggle {
        display: flex;
        justify-content: center;
        padding: 1rem;
        border-top: 1px solid var(--cream3);
    }
}
@media (min-width: 769px) {
    .mobile-toggle { display: none; }
}
</style>
</head>
<body>

<!-- ══════════════════════════════════════════════
     RENTIFY AUTH CONTAINER
══════════════════════════════════════════════ -->
<div class="auth-wrapper" id="authWrapper">

    <!-- ── FORMS CONTAINER (login left / register right) ── -->
    <div class="forms-wrap">

        <!-- LOGIN FORM — LEFT -->
        <div class="form-panel panel-login">
            <div class="form-inner">

                <p class="form-subtitle" style="margin-bottom:.2rem">Bon retour 👋</p>
                <h1 class="form-welcome">Welcome</h1>
                <p class="form-subtitle">Connectez-vous avec votre email</p>

                <!-- Error alert -->
                <div class="alert-box error" id="loginAlert">
                    <i class="fas fa-circle-exclamation"></i>
                    <span id="loginAlertMsg">Email ou mot de passe incorrect.</span>
                </div>

                <form id="loginForm" novalidate>

                    <!-- Email -->
                    <div class="field-group">
                        <label class="field-label">Email Id</label>
                        <div class="field-wrap">
                            <i class="fas fa-envelope field-icon"></i>
                            <input type="email" id="loginEmail" class="field-input" placeholder="votre@email.com" autocomplete="email">
                        </div>
                        <div class="field-error" id="loginEmailErr">
                            <i class="fas fa-circle-xmark"></i> Email invalide.
                        </div>
                    </div>

                    <!-- Password -->
                    <div class="field-group">
                        <label class="field-label">Password</label>
                        <div class="field-wrap">
                            <i class="fas fa-lock field-icon"></i>
                            <input type="password" id="loginPw" class="field-input" placeholder="••••••••••••" autocomplete="current-password">
                            <button type="button" class="pw-toggle" id="loginPwToggle" tabindex="-1">
                                <i class="fas fa-eye" id="loginPwIcon"></i>
                            </button>
                        </div>
                        <div class="field-error" id="loginPwErr">
                            <i class="fas fa-circle-xmark"></i> Minimum 6 caractères.
                        </div>
                    </div>

                    <!-- Remember + Forgot -->
                    <div class="row-remember">
                        <label class="remember-lbl">
                            <input type="checkbox" id="loginRemember">
                            Se souvenir
                        </label>
                        <a href="#" class="forgot-link">Mot de passe oublié ?</a>
                    </div>

                    <!-- Submit -->
                    <button type="submit" class="btn-main" id="loginBtn">
                        <span class="btn-label">LOGIN</span>
                        <span class="btn-spinner"></span>
                    </button>

                    <div class="or-row">OR</div>

                    <!-- Google -->
                    <button type="button" class="btn-google" onclick="alert('Demo: Google login')">
                        <svg viewBox="0 0 24 24">
                            <path fill="#4285F4" d="M22.56 12.25c0-.78-.07-1.53-.2-2.25H12v4.26h5.92c-.26 1.37-1.04 2.53-2.21 3.31v2.77h3.57c2.08-1.92 3.28-4.74 3.28-8.09z"/>
                            <path fill="#34A853" d="M12 23c2.97 0 5.46-.98 7.28-2.66l-3.57-2.77c-.98.66-2.23 1.06-3.71 1.06-2.86 0-5.29-1.93-6.16-4.53H2.18v2.84C3.99 20.53 7.7 23 12 23z"/>
                            <path fill="#FBBC05" d="M5.84 14.09c-.22-.66-.35-1.36-.35-2.09s.13-1.43.35-2.09V7.07H2.18C1.43 8.55 1 10.22 1 12s.43 3.45 1.18 4.93l2.85-2.22.81-.62z"/>
                            <path fill="#EA4335" d="M12 5.38c1.62 0 3.06.56 4.21 1.64l3.15-3.15C17.45 2.09 14.97 1 12 1 7.7 1 3.99 3.47 2.18 7.07l3.66 2.84c.87-2.6 3.3-4.53 6.16-4.53z"/>
                        </svg>
                        Continuer avec Google
                    </button>

                    <p class="switch-text">
                        Don't have account?
                        <strong onclick="switchToRegister()">Register Now</strong>
                    </p>

                </form>
            </div>
        </div>

        <!-- REGISTER FORM — RIGHT -->
        <div class="form-panel panel-register">
            <div class="form-inner">

                <!-- Error alert -->
                <div class="alert-box error" id="regAlert">
                    <i class="fas fa-circle-exclamation"></i>
                    <span id="regAlertMsg">Une erreur est survenue.</span>
                </div>

                <!-- STEP 1 — ROLE -->
                <div class="step-wrap" id="step1">
                    <!-- Stepper -->
                    <div class="stepper">
                        <div class="step-dot active" id="dot1">1</div>
                        <div class="step-line" id="stepLine"></div>
                        <div class="step-dot" id="dot2">2</div>
                    </div>

                    <h2 class="step-heading">Je suis...</h2>
                    <p class="step-sub">Sélectionnez votre profil pour continuer</p>

                    <div class="role-cards">
                        <!-- Client -->
                        <div class="role-card" id="cardClient" onclick="selectRole('client')">
                            <div class="role-check"><i class="fas fa-check"></i></div>
                            <div class="role-card-icon"><i class="fas fa-hard-hat"></i></div>
                            <span class="role-card-title">Client</span>
                            <span class="role-card-desc">Je veux louer des engins BTP</span>
                        </div>

                        <!-- Propriétaire -->
                        <div class="role-card" id="cardOwner" onclick="selectRole('owner')">
                            <div class="role-check"><i class="fas fa-check"></i></div>
                            <div class="role-card-icon"><i class="fas fa-industry"></i></div>
                            <span class="role-card-title">Propriétaire</span>
                            <span class="role-card-desc">Je propose mes engins à la location</span>
                        </div>
                    </div>

                    <button type="button" class="btn-main" id="continuerBtn" onclick="goStep2()" disabled
                        style="opacity:.5; cursor:not-allowed">
                        <span class="btn-label">Continuer &nbsp;→</span>
                    </button>

                    <p class="switch-text" style="margin-top:1rem">
                        Déjà un compte ?
                        <strong onclick="switchToLogin()">Se connecter</strong>
                    </p>
                </div>

                <!-- STEP 2 — FORM FIELDS -->
                <div class="step-wrap step-2" id="step2">
                    <!-- Stepper -->
                    <div class="stepper">
                        <div class="step-dot done" id="dot1b"><i class="fas fa-check" style="font-size:.6rem"></i></div>
                        <div class="step-line done"></div>
                        <div class="step-dot active" id="dot2b">2</div>
                    </div>

                    <div style="display:flex; align-items:center; gap:.7rem; margin-bottom:1.3rem">
                        <button type="button" onclick="goStep1()"
                            style="background:none;border:none;cursor:pointer;color:var(--mid);font-size:.85rem;padding:0;display:flex;align-items:center;gap:.3rem;font-family:'DM Sans',sans-serif">
                            <i class="fas fa-arrow-left" style="font-size:.75rem"></i> Retour
                        </button>
                        <div style="display:flex;align-items:center;gap:.4rem">
                            <span style="font-size:.72rem;color:var(--light);background:var(--cream2);padding:.25rem .6rem;border-radius:100px;font-weight:500"
                                id="selectedRoleTag">Client</span>
                        </div>
                    </div>

                    <h2 class="step-heading" style="font-size:1.5rem;margin-bottom:.2rem">Créer un compte</h2>
                    <p class="step-sub" style="margin-bottom:1.1rem">Renseignez vos informations</p>

                    <form id="registerForm" novalidate>
                        <input type="hidden" id="selectedRole" value="client">

                        <!-- Name -->
                        <div class="field-group">
                            <label class="field-label">Nom complet</label>
                            <div class="field-wrap">
                                <i class="fas fa-user field-icon"></i>
                                <input type="text" id="regName" class="field-input" placeholder="Votre nom complet" autocomplete="name">
                            </div>
                            <div class="field-error" id="regNameErr">
                                <i class="fas fa-circle-xmark"></i> Minimum 3 caractères.
                            </div>
                        </div>

                        <!-- Email -->
                        <div class="field-group">
                            <label class="field-label">Email</label>
                            <div class="field-wrap">
                                <i class="fas fa-envelope field-icon"></i>
                                <input type="email" id="regEmail" class="field-input" placeholder="votre@email.com" autocomplete="email">
                            </div>
                            <div class="field-error" id="regEmailErr">
                                <i class="fas fa-circle-xmark"></i> Email invalide.
                            </div>
                        </div>

                        <!-- Phone -->
                        <div class="field-group">
                            <label class="field-label">Téléphone</label>
                            <div class="field-wrap">
                                <i class="fas fa-phone field-icon"></i>
                                <input type="tel" id="regPhone" class="field-input" placeholder="+212 6XX XXX XXX">
                            </div>
                        </div>

                        <!-- Password -->
                        <div class="field-group">
                            <label class="field-label">Mot de passe</label>
                            <div class="field-wrap">
                                <i class="fas fa-lock field-icon"></i>
                                <input type="password" id="regPw" class="field-input" placeholder="Minimum 6 caractères" autocomplete="new-password">
                                <button type="button" class="pw-toggle" id="regPwToggle" tabindex="-1">
                                    <i class="fas fa-eye" id="regPwIcon"></i>
                                </button>
                            </div>
                            <div class="field-error" id="regPwErr">
                                <i class="fas fa-circle-xmark"></i> Minimum 6 caractères.
                            </div>
                        </div>

                        <!-- Submit -->
                        <button type="submit" class="btn-main" id="registerBtn" style="margin-top:.4rem">
                            <span class="btn-label">
                                <i class="fas fa-user-plus"></i> Créer mon compte
                            </span>
                            <span class="btn-spinner"></span>
                        </button>

                        <p class="switch-text" style="margin-top:.9rem">
                            Déjà un compte ?
                            <strong onclick="switchToLogin()">Se connecter</strong>
                        </p>
                    </form>
                </div>

            </div>
        </div>

    </div><!-- /.forms-wrap -->


    <!-- ══════════════════════════════════════════════
         OVERLAY PANEL — slides over the forms
         Default: covers RIGHT half (register side)
         On register-mode: slides to LEFT (covers login)
    ══════════════════════════════════════════════ -->
    <div class="overlay-wrap" id="overlayWrap">
        <div class="overlay-bg"></div>
        <div class="overlay-deco"></div>

        <!-- Two inner panels: right panel (login state) | left panel (register state) -->
        <div class="overlay-inner" id="overlayInner">

            <!-- LEFT INNER — visible when register mode (overlay is on left) -->
            <div class="overlay-panel">
                <div class="logo-ring">
                    <img src="{{ asset('images/logo.jpeg') }}" alt="Rentify"
    onerror="this.style.display='none'; this.nextElementSibling.style.display='flex'">
                    <i class="fas fa-hard-hat logo-ring-svg" style="display:none;font-size:2rem;color:var(--gold)"></i>
                </div>
                <div class="brand-name"><span class="rent">RENT</span><span class="ify">IFY</span></div>
                <p class="brand-tagline">Location de machines de chantier au Maroc</p>
                <p class="overlay-note" style="font-size:.78rem;color:var(--mid)">Déjà un compte ?</p>
                <button class="btn-overlay" onclick="switchToLogin()">Se connecter</button>
            </div>

            <!-- RIGHT INNER — visible in default login state (overlay is on right) -->
            <div class="overlay-panel">
                <div class="logo-ring">
                    <img src="{{ asset('images/logo.jpeg') }}" alt="Rentify"
    onerror="this.style.display='none'; this.nextElementSibling.style.display='flex'">
                    <i class="fas fa-hard-hat logo-ring-svg" style="display:none;font-size:2rem;color:var(--gold)"></i>
                </div>
                <div class="brand-name"><span class="rent">RENT</span><span class="ify">IFY</span></div>
                <p class="brand-tagline">Location de machines de chantier au Maroc</p>
                <p class="overlay-note" style="font-size:.78rem;color:var(--mid)">Pas encore de compte ?</p>
                <button class="btn-overlay" onclick="switchToRegister()">S'inscrire</button>
            </div>

        </div>
    </div>

    <!-- Mobile toggle (small screens) -->
    <div class="mobile-toggle" id="mobileTgl">
        <p class="switch-text" id="mobileSwitchText">
            Pas de compte ?
            <strong onclick="switchToRegister()">S'inscrire</strong>
        </p>
    </div>

</div><!-- /.auth-wrapper -->


<script>
/* ════════════════════════════════════════════
   RENTIFY AUTH — SLIDING PANEL JS
════════════════════════════════════════════ */

const wrapper  = document.getElementById('authWrapper');
let isRegister = false;
let selectedRole = null;

// ── SWITCH TO REGISTER ──
function switchToRegister() {
    isRegister = true;
    wrapper.classList.add('register-mode');
    // Reset to step 1
    showStep1(false);
    updateMobileToggle();
}

// ── SWITCH TO LOGIN ──
function switchToLogin() {
    isRegister = false;
    wrapper.classList.remove('register-mode');
    updateMobileToggle();
    clearAlerts();
}

// ── MOBILE TOGGLE TEXT ──
function updateMobileToggle() {
    const t = document.getElementById('mobileSwitchText');
    if (isRegister) {
        t.innerHTML = 'Déjà un compte ? <strong onclick="switchToLogin()">Se connecter</strong>';
    } else {
        t.innerHTML = 'Pas de compte ? <strong onclick="switchToRegister()">S\'inscrire</strong>';
    }
}

// ── ROLE SELECTION ──
function selectRole(role) {
    selectedRole = role;
    document.getElementById('selectedRole').value = role;

    document.querySelectorAll('.role-card').forEach(c => c.classList.remove('selected'));
    document.getElementById(role === 'client' ? 'cardClient' : 'cardOwner')
        .classList.add('selected');

    // Enable continuer button
    const btn = document.getElementById('continuerBtn');
    btn.disabled = false;
    btn.style.opacity = '1';
    btn.style.cursor = 'pointer';
}

// ── STEP 1 → STEP 2 ──
function goStep2() {
    if (!selectedRole) return;

    const s1 = document.getElementById('step1');
    const s2 = document.getElementById('step2');

    // Update role tag
    document.getElementById('selectedRoleTag').textContent =
        selectedRole === 'client' ? '👷 Client' : '🏗️ Propriétaire';

    // Fade out step 1
    s1.style.opacity = '0';
    s1.style.transform = 'translateX(-20px)';
    s1.style.transition = 'opacity .3s, transform .3s';

    setTimeout(() => {
        s1.style.display = 'none';
        s2.style.display = 'block';
        // Force reflow
        s2.offsetHeight;
        s2.classList.add('visible');
    }, 300);
}

// ── STEP 2 → STEP 1 ──
function goStep1() {
    const s1 = document.getElementById('step1');
    const s2 = document.getElementById('step2');

    s2.classList.remove('visible');

    setTimeout(() => {
        s2.style.display = 'none';
        s1.style.display = 'block';
        s1.style.opacity = '0';
        s1.style.transform = 'translateX(20px)';
        s1.offsetHeight;
        s1.style.transition = 'opacity .3s, transform .3s';
        s1.style.opacity = '1';
        s1.style.transform = 'translateX(0)';
    }, 300);
}

function showStep1(animate = true) {
    const s1 = document.getElementById('step1');
    const s2 = document.getElementById('step2');
    s2.classList.remove('visible');
    s2.style.display = 'none';
    s1.style.display = 'block';
    s1.style.opacity = '1';
    s1.style.transform = 'none';
}

// ── PASSWORD TOGGLES ──
function initPwToggle(inputId, btnId, iconId) {
    document.getElementById(btnId).addEventListener('click', () => {
        const inp  = document.getElementById(inputId);
        const icon = document.getElementById(iconId);
        const isText = inp.type === 'text';
        inp.type = isText ? 'password' : 'text';
        icon.className = isText ? 'fas fa-eye' : 'fas fa-eye-slash';
    });
}
initPwToggle('loginPw', 'loginPwToggle', 'loginPwIcon');
initPwToggle('regPw', 'regPwToggle', 'regPwIcon');

// ── VALIDATION HELPERS ──
function showFieldError(inputId, errId, show) {
    const inp = document.getElementById(inputId);
    const err = document.getElementById(errId);
    inp?.classList.toggle('is-error', show);
    err?.classList.toggle('show', show);
    return !show;
}
function validEmail(v) { return v.includes('@') && v.includes('.') && v.length > 5; }

function clearAlerts() {
    document.getElementById('loginAlert').style.display = 'none';
    document.getElementById('regAlert').style.display = 'none';
}

function showAlert(id, msgId, msg) {
    const box = document.getElementById(id);
    document.getElementById(msgId).textContent = msg;
    box.style.display = 'flex';
    box.scrollIntoView({ behavior: 'smooth', block: 'nearest' });
}

// ── LOGIN SUBMIT ──
document.getElementById('loginForm').addEventListener('submit', async function(e) {
    e.preventDefault();
    clearAlerts();

    const email = document.getElementById('loginEmail').value.trim();
    const pw    = document.getElementById('loginPw').value;

    let ok = true;
    ok = showFieldError('loginEmail', 'loginEmailErr', !validEmail(email)) && ok;
    ok = showFieldError('loginPw', 'loginPwErr', pw.length < 6) && ok;
    if (!ok) return;

    const btn = document.getElementById('loginBtn');
    btn.classList.add('loading'); btn.disabled = true;

    try {
        const res  = await fetch('/api/login', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json', 'Accept': 'application/json' },
            body: JSON.stringify({
                email, password: pw,
                remember: document.getElementById('loginRemember').checked
            })
        });
        const data = await res.json();

        if (res.ok && data.token) {
            localStorage.setItem('auth_token', data.token);
            localStorage.setItem('auth_user', JSON.stringify(data.user));
            const role = data.user?.role;
            if (role === 'admin')  window.location.replace('/dashboard/admin');
            else if (role === 'owner') window.location.replace('/dashboard/owner');
            else window.location.replace('/dashboard/client');
        } else {
            showAlert('loginAlert', 'loginAlertMsg',
                data.message || 'Email ou mot de passe incorrect.');
        }
    } catch(err) {
        showAlert('loginAlert', 'loginAlertMsg', 'Erreur de connexion au serveur.');
    } finally {
        btn.classList.remove('loading'); btn.disabled = false;
    }
});

// ── REGISTER SUBMIT ──
document.getElementById('registerForm').addEventListener('submit', async function(e) {
    e.preventDefault();
    clearAlerts();

    const name  = document.getElementById('regName').value.trim();
    const email = document.getElementById('regEmail').value.trim();
    const pw    = document.getElementById('regPw').value;
    const phone = document.getElementById('regPhone').value.trim();
    const role  = document.getElementById('selectedRole').value;

    let ok = true;
    ok = showFieldError('regName', 'regNameErr', name.length < 3) && ok;
    ok = showFieldError('regEmail', 'regEmailErr', !validEmail(email)) && ok;
    ok = showFieldError('regPw', 'regPwErr', pw.length < 6) && ok;
    if (!ok) return;

    const btn = document.getElementById('registerBtn');
    btn.classList.add('loading'); btn.disabled = true;

    try {
        const res  = await fetch('/api/register', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json', 'Accept': 'application/json' },
            body: JSON.stringify({
                name, email, password: pw,
                password_confirmation: pw,
                role, phone
            })
        });
        const data = await res.json();

        if (res.ok && data.token) {
            localStorage.setItem('auth_token', data.token);
            localStorage.setItem('auth_user', JSON.stringify(data.user));
            const r = data.user?.role;
            setTimeout(() => {
                if (r === 'owner') window.location.replace('/dashboard/owner');
                else window.location.replace('/dashboard/client');
            }, 300);
        } else {
            showAlert('regAlert', 'regAlertMsg',
                data.message || 'Erreur lors de la création du compte.');
        }
    } catch(err) {
        showAlert('regAlert', 'regAlertMsg', 'Erreur de connexion au serveur.');
    } finally {
        btn.classList.remove('loading'); btn.disabled = false;
    }
});

// ── Check if URL has ?mode=register ──
if (new URLSearchParams(window.location.search).get('mode') === 'register') {
    switchToRegister();
}
</script>

</body>
</html>