<!DOCTYPE html>
<html lang="fr" data-theme="dark">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Rentify — Location d\'engins de chantier au Maroc')</title>
    <meta name="description" content="@yield('meta_description', 'Louez vos engins de chantier en un clic. La plateforme N°1 au Maroc pour la location de machines de travaux publics.')">

    {{-- Bootstrap 5 CSS --}}
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    {{-- Font Awesome --}}
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" rel="stylesheet">
    {{-- Google Fonts --}}
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">

    <style>
        /* ════════════════════════════════════════════════════════
           VARIABLES & BASE
        ════════════════════════════════════════════════════════ */
        :root {
            --navy:        #0F1B2D;
            --navy-light:  #152236;
            --navy-mid:    #1D3557;
            --navy-card:   #1a2d44;
            --orange:      #F59E0B;
            --orange-dark: #D97706;
            --orange-glow: rgba(245,158,11,0.35);
            --bg-page:     #F0F2F5;
            --bg-white:    #FFFFFF;
            --text-dark:   #111827;
            --text-gray:   #6B7280;
            --text-light:  #9CA3AF;
            --border:      rgba(255,255,255,0.08);
            --radius-sm:   6px;
            --radius-md:   10px;
            --radius-lg:   14px;
            --radius-xl:   20px;
            --shadow-sm:   0 2px 8px rgba(0,0,0,.08);
            --shadow-md:   0 4px 20px rgba(0,0,0,.12);
            --shadow-lg:   0 8px 40px rgba(0,0,0,.18);
        }

        *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }

        html { scroll-behavior: smooth; }

        body {
            font-family: 'Inter', system-ui, sans-serif;
            background: var(--bg-page);
            color: var(--text-dark);
            line-height: 1.6;
            overflow-x: hidden;
        }

        /* ════════════════════════════════════════════════════════
           NAVBAR
        ════════════════════════════════════════════════════════ */
        .rentify-nav {
            position: fixed;
            top: 0; left: 0; right: 0;
            z-index: 1000;
            background: rgba(15, 27, 45, 0.72);
            backdrop-filter: blur(18px);
            -webkit-backdrop-filter: blur(18px);
            border-bottom: 1px solid var(--border);
            transition: background .3s ease, box-shadow .3s ease;
            padding: 0;
        }
        .rentify-nav.scrolled {
            background: rgba(15, 27, 45, 0.97);
            box-shadow: 0 4px 24px rgba(0,0,0,.4);
        }
        .nav-inner {
            display: flex;
            align-items: center;
            justify-content: space-between;
            height: 64px;
            padding: 0 32px;
            max-width: 1280px;
            margin: 0 auto;
        }
        .nav-logo {
            display: flex;
            align-items: center;
            gap: 10px;
            text-decoration: none;
        }
        .nav-logo-mark {
            width: 36px; height: 36px;
            background: var(--orange);
            border-radius: 8px;
            display: flex; align-items: center; justify-content: center;
            font-size: 18px;
            animation: navLogoBounce 3s ease-in-out infinite;
            box-shadow: 0 0 0 0 var(--orange-glow);
        }
        @keyframes navLogoBounce {
            0%,100% { transform: translateY(0); box-shadow: 0 0 0 0 var(--orange-glow); }
            50%      { transform: translateY(-3px); box-shadow: 0 0 0 8px transparent; }
        }
        .nav-logo-text { line-height: 1.1; }
        .nav-logo-name {
            color: #fff;
            font-size: 15px;
            font-weight: 800;
            letter-spacing: -.3px;
        }
        .nav-logo-name span { color: var(--orange); }
        .nav-logo-sub {
            color: rgba(255,255,255,.4);
            font-size: 9px;
            font-weight: 500;
            letter-spacing: .3px;
        }
        .nav-links {
            display: flex;
            align-items: center;
            gap: 4px;
        }
        .nav-link {
            color: rgba(255,255,255,.65);
            font-size: 13px;
            font-weight: 500;
            text-decoration: none;
            padding: 6px 14px;
            border-radius: var(--radius-md);
            transition: color .2s, background .2s;
            position: relative;
        }
        .nav-link:hover, .nav-link.active {
            color: #fff;
            background: rgba(255,255,255,.06);
        }
        .nav-link.active::after {
            content: '';
            position: absolute;
            bottom: -1px; left: 14px; right: 14px;
            height: 2px;
            background: var(--orange);
            border-radius: 2px;
        }
        .nav-actions {
            display: flex;
            align-items: center;
            gap: 8px;
        }
        .btn-nav-dark-mode {
            width: 36px; height: 36px;
            background: rgba(255,255,255,.06);
            border: 1px solid var(--border);
            border-radius: 50%;
            color: rgba(255,255,255,.7);
            cursor: pointer;
            display: flex; align-items: center; justify-content: center;
            font-size: 14px;
            transition: all .2s;
        }
        .btn-nav-dark-mode:hover { background: rgba(255,255,255,.12); color: #fff; }
        .btn-connexion {
            color: rgba(255,255,255,.8);
            font-size: 13px;
            font-weight: 500;
            text-decoration: none;
            padding: 7px 16px;
            border-radius: var(--radius-md);
            transition: color .2s;
        }
        .btn-connexion:hover { color: #fff; }
        .btn-inscrire {
            background: var(--orange);
            color: #111;
            font-size: 13px;
            font-weight: 700;
            text-decoration: none;
            padding: 8px 18px;
            border-radius: var(--radius-md);
            transition: transform .15s, box-shadow .15s, background .15s;
            letter-spacing: .2px;
        }
        .btn-inscrire:hover {
            background: var(--orange-dark);
            color: #111;
            transform: translateY(-1px);
            box-shadow: 0 4px 16px var(--orange-glow);
        }
        /* User menu when logged in */
        .nav-user-menu {
            position: relative;
        }
        .nav-user-btn {
            display: flex; align-items: center; gap: 8px;
            background: rgba(255,255,255,.06);
            border: 1px solid var(--border);
            border-radius: 100px;
            padding: 5px 12px 5px 5px;
            cursor: pointer;
            transition: background .2s;
            color: #fff;
            text-decoration: none;
        }
        .nav-user-btn:hover { background: rgba(255,255,255,.1); color: #fff; }
        .nav-user-avatar {
            width: 28px; height: 28px;
            background: var(--orange);
            border-radius: 50%;
            display: flex; align-items: center; justify-content: center;
            font-size: 12px;
            font-weight: 700;
            color: #111;
        }
        .nav-user-name { font-size: 12px; font-weight: 600; }

        /* ════════════════════════════════════════════════════════
           FLASH MESSAGES
        ════════════════════════════════════════════════════════ */
        .flash-container {
            position: fixed;
            top: 80px; right: 20px;
            z-index: 9999;
            display: flex;
            flex-direction: column;
            gap: 8px;
        }
        .flash-toast {
            background: #fff;
            border-radius: var(--radius-md);
            padding: 12px 16px;
            box-shadow: var(--shadow-lg);
            display: flex;
            align-items: center;
            gap: 10px;
            min-width: 280px;
            font-size: 13px;
            font-weight: 500;
            animation: toastIn .3s ease;
            border-left: 4px solid;
        }
        .flash-toast.success { border-color: #10B981; color: #065f46; }
        .flash-toast.error   { border-color: #EF4444; color: #991b1b; }
        .flash-toast.warning { border-color: var(--orange); color: #92400e; }
        @keyframes toastIn {
            from { opacity: 0; transform: translateX(20px); }
            to   { opacity: 1; transform: translateX(0); }
        }

        /* ════════════════════════════════════════════════════════
           GLOBAL ANIMATIONS
        ════════════════════════════════════════════════════════ */
        .fade-up {
            opacity: 0;
            transform: translateY(28px);
            transition: opacity .6s ease, transform .6s ease;
        }
        .fade-up.visible {
            opacity: 1;
            transform: translateY(0);
        }
        .fade-in {
            opacity: 0;
            transition: opacity .5s ease;
        }
        .fade-in.visible { opacity: 1; }

        /* ════════════════════════════════════════════════════════
           GLOBAL BUTTONS
        ════════════════════════════════════════════════════════ */
        .btn-orange {
            background: var(--orange);
            color: #111;
            font-weight: 700;
            border: none;
            border-radius: var(--radius-md);
            padding: 10px 22px;
            font-size: 13px;
            cursor: pointer;
            transition: transform .15s, box-shadow .15s, background .15s;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 7px;
        }
        .btn-orange:hover {
            background: var(--orange-dark);
            color: #111;
            transform: translateY(-1px);
            box-shadow: 0 5px 18px var(--orange-glow);
        }
        .btn-dark {
            background: var(--navy);
            color: #fff;
            font-weight: 700;
            border: none;
            border-radius: var(--radius-md);
            padding: 10px 22px;
            font-size: 13px;
            cursor: pointer;
            transition: transform .15s, box-shadow .15s;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 7px;
        }
        .btn-dark:hover {
            background: #0a1421;
            color: #fff;
            transform: translateY(-1px);
            box-shadow: 0 5px 18px rgba(0,0,0,.3);
        }
        .btn-outline-orange {
            background: transparent;
            color: var(--orange);
            font-weight: 700;
            border: 1.5px solid var(--orange);
            border-radius: var(--radius-md);
            padding: 9px 22px;
            font-size: 13px;
            cursor: pointer;
            transition: all .15s;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 7px;
        }
        .btn-outline-orange:hover {
            background: var(--orange);
            color: #111;
        }

        /* ════════════════════════════════════════════════════════
           PAGE HEADER (catalogue, fiche, etc.)
        ════════════════════════════════════════════════════════ */
        .page-header {
            background: var(--navy);
            padding: 80px 0 24px;
        }
        .page-header-inner {
            max-width: 1280px;
            margin: 0 auto;
            padding: 0 32px;
        }
        .page-header-title {
            color: #fff;
            font-size: 26px;
            font-weight: 800;
            margin-bottom: 4px;
            letter-spacing: -.4px;
        }
        .page-header-sub {
            color: rgba(255,255,255,.45);
            font-size: 13px;
        }

        /* ════════════════════════════════════════════════════════
           FOOTER
        ════════════════════════════════════════════════════════ */
        .rentify-footer {
            background: var(--navy);
            border-top: 1px solid var(--border);
            padding: 48px 0 0;
        }
        .footer-inner {
            max-width: 1280px;
            margin: 0 auto;
            padding: 0 32px;
            display: grid;
            grid-template-columns: 1.5fr 1fr 1fr;
            gap: 48px;
            padding-bottom: 40px;
        }
        .footer-brand-name {
            color: #fff;
            font-size: 16px;
            font-weight: 800;
        }
        .footer-brand-name span { color: var(--orange); }
        .footer-brand-desc {
            color: rgba(255,255,255,.4);
            font-size: 13px;
            line-height: 1.7;
            margin-top: 10px;
        }
        .footer-col-title {
            color: var(--orange);
            font-size: 11px;
            font-weight: 700;
            letter-spacing: 1px;
            text-transform: uppercase;
            margin-bottom: 14px;
        }
        .footer-links {
            display: flex;
            flex-direction: column;
            gap: 9px;
        }
        .footer-links a {
            color: rgba(255,255,255,.5);
            font-size: 13px;
            text-decoration: none;
            transition: color .2s;
        }
        .footer-links a:hover { color: var(--orange); }
        .footer-contact-item {
            display: flex;
            align-items: center;
            gap: 10px;
            color: rgba(255,255,255,.5);
            font-size: 13px;
            margin-bottom: 9px;
        }
        .footer-contact-item i { color: var(--orange); width: 14px; }
        .footer-bottom {
            border-top: 1px solid var(--border);
            padding: 16px 32px;
            max-width: 1280px;
            margin: 0 auto;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        .footer-copy {
            color: rgba(255,255,255,.25);
            font-size: 12px;
        }
        .footer-pfe {
            color: rgba(255,255,255,.25);
            font-size: 12px;
            font-style: italic;
        }

        /* ════════════════════════════════════════════════════════
           SCROLLBAR
        ════════════════════════════════════════════════════════ */
        ::-webkit-scrollbar { width: 6px; }
        ::-webkit-scrollbar-track { background: var(--navy); }
        ::-webkit-scrollbar-thumb { background: var(--orange); border-radius: 3px; }

        /* ════════════════════════════════════════════════════════
           UTILITY
        ════════════════════════════════════════════════════════ */
        .text-orange { color: var(--orange) !important; }
        .bg-navy     { background: var(--navy) !important; }
        .container-rentify { max-width: 1280px; margin: 0 auto; padding: 0 32px; }

        @media (max-width: 992px) {
            .nav-links { display: none; }
            .nav-inner { padding: 0 16px; }
            .footer-inner { grid-template-columns: 1fr; gap: 28px; }
            .container-rentify { padding: 0 16px; }
        }


        .navbar-nav {
    flex-direction: row !important;
    flex-wrap: nowrap !important;
    align-items: center;
    gap: 8px;
}
    </style>

    @stack('styles')
</head>
<body>

    {{-- ══ NAVBAR ══ --}}
    <nav class="rentify-nav" id="rentify-nav">
        <div class="nav-inner">
            {{-- Logo --}}
            <a href="/" class="nav-logo">
                <div class="nav-logo-mark">🏗</div>
                <div class="nav-logo-text">
                    <div class="nav-logo-name">Rent<span>ify</span></div>
                    <div class="nav-logo-sub">Location d'engins · Maroc</div>
                </div>
            </a>

            {{-- Links --}}
            <div class="nav-links">
                <a href="/" class="nav-link {{ request()->is('/') ? 'active' : '' }}">Accueil</a>
                <a href="/machines" class="nav-link {{ request()->is('machines*') ? 'active' : '' }}">Parcourir</a>
        <a href="/#comment-ca-marche" class="nav-link">Comment ça marche</a>
<a href="{{ route('contact') }}" class="nav-link {{ request()->is('contact') ? 'active' : '' }}">Contact</a>
            </div>

            {{-- Actions --}}
            <div class="nav-actions">
                <button class="btn-nav-dark-mode" id="darkModeBtn" title="Mode sombre">
                    <i class="fas fa-moon"></i>
                </button>

                @php $authUser = json_decode(request()->cookie('auth_user') ?? 'null'); @endphp

                <div id="nav-auth-zone">
                    {{-- Rempli dynamiquement par JS depuis localStorage --}}
                </div>
            </div>
        </div>
    </nav>

    {{-- ══ FLASH TOASTS ══ --}}
    <div class="flash-container" id="flash-container">
        @if(session('success'))
            <div class="flash-toast success">
                <i class="fas fa-check-circle" style="color:#10B981"></i>
                {{ session('success') }}
            </div>
        @endif
        @if(session('error'))
            <div class="flash-toast error">
                <i class="fas fa-exclamation-circle" style="color:#EF4444"></i>
                {{ session('error') }}
            </div>
        @endif
    </div>

    {{-- ══ MAIN CONTENT ══ --}}
    <main style="padding-top: 64px;">
        @yield('content')
    </main>

    {{-- ══ FOOTER ══ --}}
    <footer class="rentify-footer">
        <div class="footer-inner">
            {{-- Brand --}}
            <div>
                <div style="display:flex;align-items:center;gap:10px;margin-bottom:12px">
                    <div style="width:34px;height:34px;background:var(--orange);border-radius:8px;display:flex;align-items:center;justify-content:center;font-size:16px">🏗</div>
                    <div class="footer-brand-name">Rent<span>ify</span></div>
                </div>
                <div class="footer-brand-desc">
                    La plateforme de référence pour la location d'engins de chantier au Maroc.
                    Connectez-vous avec les meilleurs propriétaires de machines.
                </div>
            </div>

            {{-- Navigation --}}
            <div>
                <div class="footer-col-title">Navigation</div>
                <div class="footer-links">
                    <a href="/">Accueil</a>
                    <a href="/machines">Parcourir</a>
                    <a href="/#comment-ca-marche">Comment ça marche</a>
                    <a href="/dashboard/client">Tableau de bord</a>
                    <a href="/login">Connexion</a>
                </div>
            </div>

            {{-- Contact --}}
            <div>
                <div class="footer-col-title">Contact</div>
                <div class="footer-contact-item">
                    <i class="fas fa-envelope"></i>
                    contact@rentify.ma
                </div>
                <div class="footer-contact-item">
                    <i class="fas fa-phone"></i>
                    +212 6 00 00 00 00
                </div>
                <div class="footer-contact-item">
                    <i class="fas fa-map-marker-alt"></i>
                    Casablanca, Maroc
                </div>
            </div>
        </div>

        <div class="footer-bottom">
            <div class="footer-copy">© 2025 Rentify.ma. Tous droits réservés.</div>
            <div class="footer-pfe">Projet de fin d'études — Développement Digital</div>
        </div>
    </footer>

    {{-- ══ SCRIPTS ══ --}}
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

    <script>
    /* ── Navbar scroll effect ── */
    const nav = document.getElementById('rentify-nav');
    window.addEventListener('scroll', () => {
        nav.classList.toggle('scrolled', window.scrollY > 40);
    });

    /* ── Auth zone dynamique (localStorage) ── */
    function updateNavAuth() {
        const token = localStorage.getItem('auth_token');
        const user  = JSON.parse(localStorage.getItem('auth_user') || 'null');
        const zone  = document.getElementById('nav-auth-zone');
        if (!zone) return;

        if (token && user) {
            const initial = (user.name || 'U').charAt(0).toUpperCase();
            const dashUrl = user.role === 'owner' ? '/dashboard/owner' : '/dashboard/client';
            zone.innerHTML = `
                <a href="${dashUrl}" class="nav-user-btn">
                    <div class="nav-user-avatar">${initial}</div>
                    <span class="nav-user-name">${user.name.split(' ')[0]}</span>
                    <i class="fas fa-chevron-down" style="font-size:9px;opacity:.6"></i>
                </a>`;
        } else {
            zone.innerHTML = `
                <a href="/login" class="btn-connexion">Connexion</a>
                <a href="/register" class="btn-inscrire">S'inscrire</a>`;
        }
    }
    updateNavAuth();

    /* ── Flash auto-dismiss ── */
    document.querySelectorAll('.flash-toast').forEach(t => {
        setTimeout(() => t.remove(), 4000);
    });

    /* ── showFlash utility (callable from pages) ── */
    window.showFlash = function(msg, type = 'success') {
        const icons = { success: 'fa-check-circle', error: 'fa-exclamation-circle', warning: 'fa-exclamation-triangle' };
        const colors = { success: '#10B981', error: '#EF4444', warning: '#F59E0B' };
        const el = document.createElement('div');
        el.className = `flash-toast ${type}`;
        el.innerHTML = `<i class="fas ${icons[type]}" style="color:${colors[type]}"></i>${msg}`;
        document.getElementById('flash-container').appendChild(el);
        setTimeout(() => el.remove(), 4000);
    };

    /* ── Scroll animations (Intersection Observer) ── */
    const observer = new IntersectionObserver((entries) => {
        entries.forEach((e, i) => {
            if (e.isIntersecting) {
                setTimeout(() => {
                    e.target.classList.add('visible');
                }, (e.target.dataset.delay || 0));
                observer.unobserve(e.target);
            }
        });
    }, { threshold: 0.1 });

    document.querySelectorAll('.fade-up, .fade-in').forEach(el => observer.observe(el));

    /* ── API helper global ── */
    window.API = {
        headers(withAuth = true) {
            const h = { 'Content-Type': 'application/json', 'Accept': 'application/json' };
            const token = localStorage.getItem('auth_token');
            if (withAuth && token) h['Authorization'] = `Bearer ${token}`;
            return h;
        },
        async get(url) {
    const r = await fetch(url, { headers: this.headers() });

    if (r.status === 401) {
        
        localStorage.removeItem('auth_token');
        localStorage.removeItem('auth_user');
        window.location.replace('/login'); 
        return null;
    }

    return r.json();
},
        async post(url, body) {
            const r = await fetch(url, { method: 'POST', headers: this.headers(), body: JSON.stringify(body) });
            return { ok: r.ok, status: r.status, data: await r.json() };
        },
        async patch(url, body = {}) {
            const r = await fetch(url, { method: 'PATCH', headers: this.headers(), body: JSON.stringify(body) });
            return { ok: r.ok, data: await r.json() };
        }
    };

    window.getUser = () => JSON.parse(localStorage.getItem('auth_user') || 'null');
    window.getToken = () => localStorage.getItem('auth_token');
    window.requireAuth = () => {
        if (!getToken()) { window.location.href = '/login'; return false; }
        return true;
    };
    </script>

    @stack('scripts')

    {{-- FEATURE 6 : Chatbot Widget --}}
@include('chatbot.widget')
</body>
</html>