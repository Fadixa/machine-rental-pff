<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Rentify — Location d\'engins de chantier au Maroc')</title>
    <meta name="description" content="@yield('meta_description', 'Louez vos engins de chantier en un clic. La plateforme N°1 au Maroc pour la location de machines de travaux publics.')">

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@600;700;800&family=DM+Sans:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">

    <style>
        /* ═══════════════════════════════════════════
           DESIGN SYSTEM — Gold / Crème — V10 CLAIR
        ═══════════════════════════════════════════ */
        :root {
            /* Gold */
            --gold:#D4AF37; --gold-dk:#9A7D20; --gold-lt:#F5E88A;
            --gold-pale:#FEF9E7; --gold-glow:rgba(212,175,55,.25);
            /* Crème */
            --cream:#FAF7F0; --cream2:#F0EBE0; --cream3:#E8DDD0;
            /* Texte */
            --txt-dark:#1a1a2e; --txt-mid:#5a5660; --txt-light:#9992a4;
            /* Backgrounds */
            --bg-page:#FAF7F0; --bg-white:#fff; --bg-card:#fff;
            /* Bordures */
            --border:rgba(212,175,55,.15);
            --border-md:rgba(212,175,55,.25);
            --border-strong:rgba(212,175,55,.4);
            /* Ombres */
            --shadow-sm:0 2px 8px rgba(15,27,45,.05);
            --shadow-md:0 4px 20px rgba(15,27,45,.08);
            --shadow-lg:0 8px 40px rgba(15,27,45,.12);
            /* Radius */
            --radius-sm:6px; --radius-md:10px; --radius-lg:14px; --radius-xl:20px;
            /* Statuts */
            --green:#10b981; --red:#ef4444;
        }

        *,*::before,*::after { box-sizing:border-box; margin:0; padding:0; }
        html { scroll-behavior:smooth; }
        body {
            font-family:'DM Sans',system-ui,sans-serif;
            background:var(--cream);
            color:var(--txt-dark);
            line-height:1.6;
            overflow-x:hidden;
        }

        /* ═══════════════════════════════════════════
           NAVBAR
        ═══════════════════════════════════════════ */
        .rentify-nav {
            position:fixed; top:0; left:0; right:0; z-index:1000;
            background:#fff;
            border-bottom:1px solid var(--border);
            transition:box-shadow .3s ease;
        }
        .rentify-nav.scrolled {
            box-shadow:0 4px 24px rgba(212,175,55,.12);
        }
        .nav-inner {
            display:flex; align-items:center;
            justify-content:space-between;
            height:64px; padding:0 32px;
            max-width:1280px; margin:0 auto;
        }

        /* Logo */
        .nav-logo {
            display:flex; align-items:center;
            gap:10px; text-decoration:none; flex-shrink:0;
        }
        .nav-logo-img {
            height:42px; width:auto; object-fit:contain; display:block;
            border-radius:10px;
            box-shadow:0 2px 12px rgba(212,175,55,.35),0 1px 4px rgba(15,27,45,.1);
            transition:transform .2s ease,box-shadow .2s ease;
        }
        .nav-logo:hover .nav-logo-img {
            transform:translateY(-2px) scale(1.03);
            box-shadow:0 6px 22px rgba(212,175,55,.45),0 2px 8px rgba(15,27,45,.12);
        }
        .nav-logo-text { display:none; }

        /* Links */
        .nav-links {
            display:flex; align-items:center;
            gap:2px; flex:1; margin:0 16px;
        }
        .nav-link {
            color:var(--txt-mid); font-size:13px; font-weight:500;
            text-decoration:none; padding:6px 14px; border-radius:8px;
            transition:color .2s,background .2s;
            position:relative; display:inline-flex;
            align-items:center; gap:5px; white-space:nowrap;
        }
        .nav-link:hover { color:var(--txt-dark); background:var(--gold-pale); }
        .nav-link.active { color:var(--gold-dk); background:var(--gold-pale); font-weight:700; }
        .nav-link.active::after {
            content:''; position:absolute; bottom:-1px;
            left:14px; right:14px; height:2px;
            background:var(--gold); border-radius:2px;
        }

        /* Actions */
        .nav-actions {
            display:flex; align-items:center;
            gap:8px; flex-shrink:0;
        }
        .btn-connexion {
            color:var(--txt-mid); font-size:13px; font-weight:500;
            text-decoration:none; padding:7px 16px; border-radius:8px;
            transition:color .2s,background .2s;
        }
        .btn-connexion:hover { color:var(--txt-dark); background:var(--gold-pale); }

        .btn-inscrire {
            background:var(--gold); color:var(--txt-dark)!important;
            font-size:13px; font-weight:700; text-decoration:none;
            padding:8px 20px; border-radius:8px;
            border:1.5px solid var(--gold);
            transition:all .2s;
        }
        .btn-inscrire:hover {
            background:var(--gold-dk); border-color:var(--gold-dk);
            color:#fff!important;
            transform:translateY(-1px);
            box-shadow:0 4px 16px var(--gold-glow);
        }

        /* Pill "Ajouter machine" */
        .btn-add-machine {
            display:inline-flex; align-items:center; gap:6px;
            padding:7px 16px; border-radius:50px; font-size:.82rem; font-weight:700;
            background:var(--gold-pale); color:var(--gold-dk)!important;
            text-decoration:none;
            border:1.5px solid var(--border-strong);
            transition:all .2s; white-space:nowrap;
        }
        .btn-add-machine:hover {
            background:var(--gold); color:var(--txt-dark)!important;
            border-color:var(--gold);
        }

        /* Avatar dropdown */
        .nav-user-menu { position:relative; }
        .nav-user-btn {
            display:flex; align-items:center; gap:8px;
            background:var(--gold-pale);
            border:1.5px solid var(--border-md);
            border-radius:100px; padding:5px 12px 5px 5px;
            cursor:pointer; transition:background .2s;
            color:var(--txt-dark); font-family:inherit;
        }
        .nav-user-btn:hover { background:var(--cream2); }
        .nav-user-avatar {
            width:28px; height:28px; background:var(--gold);
            border-radius:50%; display:flex; align-items:center;
            justify-content:center; font-size:12px; font-weight:700;
            color:var(--txt-dark); flex-shrink:0;
            overflow:hidden;
        }
        /* ✅ FIX — photo dans avatar navbar */
        .nav-user-avatar img {
            width:28px; height:28px; border-radius:50%;
            object-fit:cover; display:block;
        }
        .nav-user-name { font-size:12px; font-weight:600; color:var(--txt-dark); }

        /* Dropdown menu */
        #nav-drop {
            position:absolute; top:calc(100% + 10px); right:0;
            background:#fff; border:1px solid var(--border-md);
            border-radius:14px; min-width:200px;
            box-shadow:0 8px 32px rgba(15,27,45,.1);
            opacity:0; visibility:hidden; transform:translateY(-8px);
            transition:all .2s; z-index:300;
        }
        #nav-drop.open { opacity:1; visibility:visible; transform:translateY(0); }
        .nav-drop-header {
            padding:12px 16px;
            border-bottom:1px solid var(--border);
            background:var(--gold-pale);
            border-radius:14px 14px 0 0;
        }
        .nav-drop-name  { font-size:.88rem; font-weight:700; color:var(--txt-dark); }
        .nav-drop-email { font-size:.73rem; color:var(--txt-light); margin-top:2px; }
        .nav-drop-item {
            display:flex; align-items:center; gap:9px;
            padding:9px 16px; font-size:.84rem; color:var(--txt-mid);
            text-decoration:none; transition:background .15s;
            background:none; border:none; width:100%;
            cursor:pointer; font-family:inherit; text-align:left;
        }
        .nav-drop-item:hover { background:var(--gold-pale); color:var(--txt-dark); }
        .nav-drop-item i { color:var(--gold); width:14px; text-align:center; }
        .nav-drop-sep { height:1px; background:var(--border); margin:4px 0; }
        .nav-drop-logout { color:#ef4444!important; }
        .nav-drop-logout:hover { background:#FEF2F2!important; }
        .nav-drop-logout i { color:#ef4444!important; }

        /* Favoris badge */
        .nav-fav-badge {
            position:absolute; top:-4px; right:-6px;
            background:var(--gold); color:var(--txt-dark);
            font-size:.6rem; font-weight:900;
            min-width:16px; height:16px; border-radius:100px;
            display:flex; align-items:center; justify-content:center;
            padding:0 3px; border:2px solid #fff;
        }

        /* ═══════════════════════════════════════════
           BUTTONS GLOBAUX
        ═══════════════════════════════════════════ */
        .btn-gold {
            background:var(--gold); color:var(--txt-dark)!important;
            font-weight:700; border:none; border-radius:8px;
            padding:10px 22px; font-size:13px; cursor:pointer;
            transition:all .2s; text-decoration:none;
            display:inline-flex; align-items:center; gap:7px;
        }
        .btn-gold:hover {
            background:var(--gold-dk); color:#fff!important;
            transform:translateY(-1px);
            box-shadow:0 5px 18px var(--gold-glow);
        }
        /* btn-navy → reconverti en gold outline pour le thème clair */
        .btn-navy {
            background:#fff; color:var(--gold-dk)!important;
            font-weight:700;
            border:2px solid var(--gold);
            border-radius:8px; padding:10px 22px;
            font-size:13px; cursor:pointer; transition:all .2s;
            text-decoration:none;
            display:inline-flex; align-items:center; gap:7px;
        }
        .btn-navy:hover {
            background:var(--gold); color:var(--txt-dark)!important;
            transform:translateY(-1px);
            box-shadow:0 5px 18px var(--gold-glow);
        }
        .btn-outline {
            background:transparent; color:var(--gold-dk)!important;
            font-weight:700; border:2px solid var(--gold);
            border-radius:8px; padding:9px 22px;
            font-size:13px; cursor:pointer; transition:all .2s;
            text-decoration:none;
            display:inline-flex; align-items:center; gap:7px;
        }
        .btn-outline:hover {
            background:var(--gold); color:var(--txt-dark)!important;
        }
        /* Alias rétrocompat */
        .btn-orange,.btn-dark {
            background:var(--gold); color:var(--txt-dark)!important;
            font-weight:700; border:none; border-radius:8px;
            padding:10px 22px; font-size:13px; cursor:pointer;
            transition:all .2s; text-decoration:none;
            display:inline-flex; align-items:center; gap:7px;
        }
        .btn-orange:hover,.btn-dark:hover {
            background:var(--gold-dk); color:#fff!important;
            transform:translateY(-1px);
        }

        /* ═══════════════════════════════════════════
           FLASH TOASTS
        ═══════════════════════════════════════════ */
        .flash-container {
            position:fixed; top:80px; right:20px;
            z-index:9999; display:flex; flex-direction:column; gap:8px;
        }
        .flash-toast {
            background:#fff; border-radius:10px; padding:12px 16px;
            box-shadow:var(--shadow-lg);
            display:flex; align-items:center; gap:10px;
            min-width:280px; font-size:13px; font-weight:500;
            animation:toastIn .3s ease; border-left:4px solid;
        }
        .flash-toast.success { border-color:#10B981; color:#065f46; }
        .flash-toast.error   { border-color:#EF4444; color:#991b1b; }
        .flash-toast.warning { border-color:var(--gold); color:var(--gold-dk); }
        @keyframes toastIn {
            from { opacity:0; transform:translateX(20px); }
            to   { opacity:1; transform:translateX(0); }
        }

        /* ═══════════════════════════════════════════
           ANIMATIONS
        ═══════════════════════════════════════════ */
        .fade-up {
            opacity:0; transform:translateY(28px);
            transition:opacity .6s ease,transform .6s ease;
        }
        .fade-up.visible { opacity:1; transform:translateY(0); }
        .fade-in { opacity:0; transition:opacity .5s ease; }
        .fade-in.visible { opacity:1; }

        /* ═══════════════════════════════════════════
           PAGE HEADER
        ═══════════════════════════════════════════ */
        .page-header {
            background:var(--cream);
            border-bottom:1px solid var(--border);
            padding:80px 0 24px;
        }
        .page-header-inner {
            max-width:1280px; margin:0 auto; padding:0 32px;
        }
        .page-header-title {
            color:var(--txt-dark); font-size:26px; font-weight:800;
            margin-bottom:4px; letter-spacing:-.4px;
            font-family:'Playfair Display',Georgia,serif;
        }
        .page-header-sub { color:var(--txt-light); font-size:13px; }

        /* ═══════════════════════════════════════════
           FOOTER — clair
        ═══════════════════════════════════════════ */
        .rentify-footer {
            background:#fff;
            border-top:2px solid var(--border-md);
            padding:52px 0 0;
        }
        .footer-inner {
            max-width:1280px; margin:0 auto; padding:0 32px 44px;
            display:grid; grid-template-columns:1.5fr 1fr 1fr; gap:48px;
        }
        .footer-logo-img {
            height:52px; width:auto; object-fit:contain;
            display:block; border-radius:8px;
        }
        .footer-brand-name { color:var(--txt-dark); font-size:16px; font-weight:800; }
        .footer-brand-name span { color:var(--gold); }
        .footer-brand-desc {
            color:var(--txt-light); font-size:13px;
            line-height:1.75; margin-top:12px;
        }
        .footer-divider {
            width:36px; height:2px;
            background:linear-gradient(90deg,var(--gold),transparent);
            margin-top:18px; border-radius:2px;
        }
        .footer-col-title {
            color:var(--gold-dk); font-size:10px; font-weight:800;
            letter-spacing:1.4px; text-transform:uppercase;
            margin-bottom:16px; display:flex; align-items:center; gap:8px;
        }
        .footer-col-title::after {
            content:''; flex:1; height:1px;
            background:var(--border);
        }
        .footer-links { display:flex; flex-direction:column; gap:10px; }
        .footer-links a {
            color:var(--txt-mid); font-size:13px; text-decoration:none;
            transition:color .2s,padding-left .2s;
            display:flex; align-items:center; gap:6px;
        }
        .footer-links a::before { content:'›'; color:var(--gold); opacity:0; transition:opacity .2s; }
        .footer-links a:hover { color:var(--txt-dark); padding-left:4px; }
        .footer-links a:hover::before { opacity:1; }
        .footer-contact-item {
            display:flex; align-items:center; gap:12px;
            color:var(--txt-mid); font-size:13px; margin-bottom:12px;
            transition:color .2s;
        }
        .footer-contact-item:hover { color:var(--txt-dark); }
        .footer-contact-icon {
            width:30px; height:30px; border-radius:8px;
            background:var(--gold-pale);
            border:1px solid var(--border-md);
            display:flex; align-items:center;
            justify-content:center; flex-shrink:0;
        }
        .footer-contact-icon i { color:var(--gold-dk); font-size:12px; }
        .footer-bottom {
            border-top:1px solid var(--border);
            padding:18px 32px; max-width:1280px; margin:0 auto;
            display:flex; justify-content:space-between; align-items:center;
        }
        .footer-copy { color:var(--txt-light); font-size:12px; }
        .footer-pfe  { color:var(--txt-light); font-size:12px; font-style:italic; }

        /* ═══════════════════════════════════════════
           SCROLLBAR
        ═══════════════════════════════════════════ */
        ::-webkit-scrollbar { width:6px; }
        ::-webkit-scrollbar-track { background:var(--cream2); }
        ::-webkit-scrollbar-thumb { background:var(--gold); border-radius:3px; }

        /* ═══════════════════════════════════════════
           UTILITY
        ═══════════════════════════════════════════ */
        .text-gold,.text-orange { color:var(--gold)!important; }
        .bg-gold   { background:var(--gold)!important; }
        .bg-cream  { background:var(--cream)!important; }
        .container-rentify { max-width:1280px; margin:0 auto; padding:0 32px; }

        /* ═══════════════════════════════════════════
           RESPONSIVE
        ═══════════════════════════════════════════ */
        @media(max-width:992px) {
            .nav-links { display:none; }
            .nav-inner { padding:0 16px; }
            .footer-inner { grid-template-columns:1fr; gap:28px; }
            .container-rentify { padding:0 16px; }
            .nav-user-name { display:none; }
        }
        .navbar-nav {
            flex-direction:row!important;
            flex-wrap:nowrap!important;
            align-items:center; gap:8px;
        }
    </style>

    @stack('styles')
</head>
<body>

{{-- ══ NAVBAR ══ --}}
<nav class="rentify-nav" id="rentify-nav">
    <div class="nav-inner">
        <a href="/" class="nav-logo">
            <img
                src="{{ asset('images/logo.jpeg') }}"
                alt="Rentify"
                class="nav-logo-img"
                onerror="this.style.display='none';this.nextElementSibling.style.display='flex';"
            >
            {{-- Fallback texte --}}
            <div class="nav-logo-text" style="display:none;align-items:center;gap:10px;">
                <div style="width:36px;height:36px;background:var(--gold);border-radius:8px;
                            display:flex;align-items:center;justify-content:center;font-size:18px;">🏗</div>
                <div>
                    <div style="font-family:'Playfair Display',serif;font-size:16px;
                                font-weight:800;color:var(--txt-dark);">
                        Rent<span style="color:var(--gold)">ify</span>
                    </div>
                    <div style="font-size:10px;color:var(--txt-light);">Location d'engins · Maroc</div>
                </div>
            </div>
        </a>

        <div class="nav-links" id="nav-links"></div>
        <div class="nav-actions" id="nav-actions"></div>
    </div>
</nav>

{{-- ══ FLASH ══ --}}
<div class="flash-container" id="flash-container">
    @if(session('success'))
        <div class="flash-toast success">
            <i class="fas fa-check-circle" style="color:#10B981"></i>{{ session('success') }}
        </div>
    @endif
    @if(session('error'))
        <div class="flash-toast error">
            <i class="fas fa-exclamation-circle" style="color:#EF4444"></i>{{ session('error') }}
        </div>
    @endif
</div>

{{-- ══ MAIN ══ --}}
<main style="padding-top:64px;">
    @yield('content')
</main>

{{-- ══ FOOTER ══ --}}
<footer class="rentify-footer">
    <div class="footer-inner">

        {{-- Brand --}}
        <div>
            <div style="display:flex;align-items:center;gap:12px;margin-bottom:14px">
                <img
                    src="{{ asset('images/logo.jpeg') }}"
                    alt="Rentify"
                    class="footer-logo-img"
                    onerror="this.outerHTML='<div style=\'width:40px;height:40px;background:var(--gold);border-radius:10px;display:flex;align-items:center;justify-content:center;font-size:20px;\'>🏗</div>'"
                >
                <div class="footer-brand-name">
                    Rent<span>ify</span>
                </div>
            </div>
            <div class="footer-brand-desc">
                La plateforme de référence pour la location d'engins de chantier au Maroc.
                Connectez-vous avec les meilleurs propriétaires de machines.
            </div>
            <div class="footer-divider"></div>
        </div>

        {{-- Navigation --}}
        <div>
            <div class="footer-col-title">Navigation</div>
            <div class="footer-links">
                <a href="/">Accueil</a>
                <a href="/machines">Machines</a>
                <a href="/#a-propos">À propos</a>
                <a href="/contact">Contact</a>
                <a href="/dashboard/client">Mon espace</a>
            </div>
        </div>

        {{-- Contact --}}
        <div>
            <div class="footer-col-title">Contact</div>
            <div class="footer-contact-item">
                <div class="footer-contact-icon"><i class="fas fa-envelope"></i></div>
                contact@rentify.ma
            </div>
            <div class="footer-contact-item">
                <div class="footer-contact-icon"><i class="fas fa-phone"></i></div>
                +212 6 00 00 00 00
            </div>
            <div class="footer-contact-item">
                <div class="footer-contact-icon"><i class="fas fa-map-marker-alt"></i></div>
                Casablanca, Maroc
            </div>
        </div>

    </div>
    <div class="footer-bottom">
        <div class="footer-copy">© 2026 Rentify.ma. Tous droits réservés.</div>
        <div class="footer-pfe">Projet de fin d'études — Développement Digital</div>
    </div>
</footer>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

<script>
/* ════════════════════════════════════════════
   GLOBALS
════════════════════════════════════════════ */
window.getUser     = () => JSON.parse(localStorage.getItem('auth_user') || 'null');
window.getToken    = () => localStorage.getItem('auth_token');
window.requireAuth = () => { if (!getToken()) { window.location.href='/login'; return false; } return true; };
window.getFavorites   = () => JSON.parse(localStorage.getItem('rentify_favorites') || '[]');
window.isFavorite     = id => getFavorites().includes(Number(id));
window.toggleFavorite = id => {
    const favs = getFavorites(), idx = favs.indexOf(Number(id));
    if (idx >= 0) favs.splice(idx,1); else favs.push(Number(id));
    localStorage.setItem('rentify_favorites', JSON.stringify(favs));
    return idx < 0;
};

/* ════════════════════════════════════════════
   API HELPER
════════════════════════════════════════════ */
window.API = {
    headers(withAuth=true) {
        const h = {'Content-Type':'application/json','Accept':'application/json'};
        const t = localStorage.getItem('auth_token');
        if (withAuth && t) h['Authorization'] = 'Bearer ' + t;
        return h;
    },
    async get(url) {
        const r = await fetch(url, {headers:this.headers()});
        if (r.status === 401) {
            localStorage.removeItem('auth_token');
            localStorage.removeItem('auth_user');
            window.location.replace('/login'); return null;
        }
        return r.json();
    },
    async post(url, body) {
        const r = await fetch(url, {method:'POST',headers:this.headers(),body:JSON.stringify(body)});
        return {ok:r.ok, status:r.status, data:await r.json()};
    },
    async put(url, body) {
        const r = await fetch(url, {method:'PUT',headers:this.headers(),body:JSON.stringify(body)});
        return {ok:r.ok, status:r.status, data:await r.json()};
    },
    async patch(url, body={}) {
        const r = await fetch(url, {method:'PATCH',headers:this.headers(),body:JSON.stringify(body)});
        return {ok:r.ok, data:await r.json()};
    },
    async delete(url) {
        const r = await fetch(url, {method:'DELETE',headers:this.headers()});
        return {ok:r.ok, data:await r.json()};
    }
};

/* ════════════════════════════════════════════
   NAVBAR — BUILD PAR RÔLE
════════════════════════════════════════════ */
(function buildNav() {
    const token = localStorage.getItem('auth_token');
    const user  = JSON.parse(localStorage.getItem('auth_user') || 'null');
    const path  = window.location.pathname;

    function lnk(href, label, icon='') {
        const active = (path === href || (href !== '/' && path.startsWith(href))) ? 'active' : '';
        return `<a href="${href}" class="nav-link ${active}">
            ${icon ? `<i class="${icon}" style="color:var(--gold);font-size:11px"></i>` : ''}
            ${label}
        </a>`;
    }

    /* ✅ FIX — génère le contenu avatar (photo ou initiale) */
    function buildAvatarContent(u) {
        const photo = u.profile_photo_path || u.avatar || null;
        const init  = (u.name || 'U')[0].toUpperCase();
        if (photo) {
            return `<img
                src="/storage/${photo}?t=${Date.now()}"
                alt="${init}"
                style="width:28px;height:28px;border-radius:50%;object-fit:cover;display:block;"
                onerror="this.outerHTML='<span style=&quot;font-size:12px;font-weight:700;&quot;>${init}</span>'">`;
        }
        return init;
    }

    function avatarDrop(u) {
        const fn   = u.name.split(' ')[0];
        const dash = {admin:'/dashboard/admin',owner:'/dashboard/owner',chauffeur:'/dashboard/chauffeur'}[u.role]||'/dashboard/client';

        const items = u.role === 'owner' ? `
            <a href="/dashboard/owner" class="nav-drop-item"><i class="fas fa-tachometer-alt"></i> Dashboard</a>
            <a href="/machines/create" class="nav-drop-item"><i class="fas fa-plus"></i> Ajouter machine</a>
            <a href="/profile"         class="nav-drop-item"><i class="fas fa-user"></i> Mon profil</a>`
        : u.role === 'client' ? `
            <a href="/dashboard/client" class="nav-drop-item"><i class="fas fa-th-large"></i> Mon espace</a>
            <a href="/favoris"          class="nav-drop-item"><i class="fas fa-heart"></i> Mes favoris</a>
            <a href="/profile"          class="nav-drop-item"><i class="fas fa-user"></i> Mon profil</a>`
        : u.role === 'chauffeur' ? `
            <a href="/dashboard/chauffeur" class="nav-drop-item"><i class="fas fa-hard-hat"></i> Mes missions</a>
            <a href="/profile"             class="nav-drop-item"><i class="fas fa-user"></i> Mon profil</a>`
        : `<a href="${dash}" class="nav-drop-item"><i class="fas fa-th-large"></i> Dashboard</a>
           <a href="/profile" class="nav-drop-item"><i class="fas fa-user"></i> Mon profil</a>`;

        return `
        <div class="nav-user-menu" id="nav-user-menu">
            <button class="nav-user-btn" onclick="toggleNavDrop()">
                <div class="nav-user-avatar" id="nav-avatar-el">${buildAvatarContent(u)}</div>
                <span class="nav-user-name">${fn}</span>
                <i class="fas fa-chevron-down" id="nav-chev"
                   style="font-size:9px;opacity:.4;color:var(--txt-mid);transition:transform .2s"></i>
            </button>
            <div id="nav-drop">
                <div class="nav-drop-header">
                    <div class="nav-drop-name">${u.name}</div>
                    <div class="nav-drop-email">${u.email||''}</div>
                </div>
                ${items}
                <div class="nav-drop-sep"></div>
                <button class="nav-drop-item nav-drop-logout" onclick="doNavLogout()">
                    <i class="fas fa-sign-out-alt"></i> Déconnexion
                </button>
            </div>
        </div>`;
    }

    const linksEl   = document.getElementById('nav-links');
    const actionsEl = document.getElementById('nav-actions');

    if (!token || !user) {
        /* GUEST */
        linksEl.innerHTML = `
            ${lnk('/','Accueil')}
            ${lnk('/machines','Machines')}
            ${lnk('/#a-propos','À propos')}
            ${lnk('/contact','Contact')}`;
        actionsEl.innerHTML = `
            <a href="/login"    class="btn-connexion">Connexion</a>
            <a href="/register" class="btn-inscrire">S'inscrire</a>`;

    } else if (user.role === 'client') {
        linksEl.innerHTML = `
            ${lnk('/','Accueil')}
            ${lnk('/machines','Machines')}
            ${lnk('/#a-propos','À propos')}
            ${lnk('/contact','Contact')}
            ${lnk('/dashboard/client','Mon espace','fas fa-user')}`;
        actionsEl.innerHTML = avatarDrop(user);

    } else if (user.role === 'owner') {
        linksEl.innerHTML = `
            ${lnk('/','Accueil')}
            ${lnk('/machines','Machines')}
            ${lnk('/dashboard/owner','Dashboard')}
            ${lnk('/#a-propos','À propos')}
            ${lnk('/contact','Contact')}`;
        actionsEl.innerHTML = `
            <a href="/machines/create" class="btn-add-machine">
                <i class="fas fa-plus"></i> Ajouter machine
            </a>
            ${avatarDrop(user)}`;

    } else if (user.role === 'admin') {
        linksEl.innerHTML = `
            ${lnk('/dashboard/admin','Dashboard')}
            ${lnk('/contact','Contact')}`;
        actionsEl.innerHTML = avatarDrop(user);

    } else if (user.role === 'chauffeur') {
        linksEl.innerHTML = `
            ${lnk('/dashboard/chauffeur','Mes missions')}
            ${lnk('/contact','Contact')}`;
        actionsEl.innerHTML = avatarDrop(user);
    }
})();

/* ── Dropdown toggle ── */
window.toggleNavDrop = function() {
    const drop = document.getElementById('nav-drop');
    const chev = document.getElementById('nav-chev');
    if (!drop) return;
    drop.classList.toggle('open');
    if (chev) chev.style.transform = drop.classList.contains('open') ? 'rotate(180deg)' : '';
};
document.addEventListener('click', function(e) {
    if (!e.target.closest('#nav-user-menu')) {
        document.getElementById('nav-drop')?.classList.remove('open');
        const chev = document.getElementById('nav-chev');
        if (chev) chev.style.transform = '';
    }
});

/* ── Logout ── */
window.doNavLogout = function() {
    fetch('/api/logout', {method:'POST', headers:API.headers()}).finally(() => {
        localStorage.removeItem('auth_token');
        localStorage.removeItem('auth_user');
        window.location.replace('/login');
    });
};

/* ════════════════════════════════════════════
   ✅ FIX — Refresh avatar navbar sans reload
   Appelé depuis profile/index.blade.php après upload
════════════════════════════════════════════ */
window.refreshNavAvatar = function() {
    const u = window.getUser();
    if (!u) return;
    const el = document.getElementById('nav-avatar-el');
    if (!el) return;
    const photo = u.profile_photo_path || u.avatar || null;
    const init  = (u.name || 'U')[0].toUpperCase();
    if (photo) {
        el.innerHTML = `<img
            src="/storage/${photo}?t=${Date.now()}"
            alt="${init}"
            style="width:28px;height:28px;border-radius:50%;object-fit:cover;display:block;"
            onerror="this.outerHTML='<span style=font-size:12px;font-weight:700>${init}</span>'">`;
    } else {
        el.innerHTML = init;
    }
};

/* ── Favoris badge ── */
window.updateFavBadge  = function(count) {
    const b = document.getElementById('nav-fav-badge');
    if (!b) return;
    b.textContent = count;
    b.style.display = count > 0 ? 'flex' : 'none';
};
window.refreshFavsBadge = () => updateFavBadge(getFavorites().length);

/* ── Scroll shadow ── */
window.addEventListener('scroll', () => {
    document.getElementById('rentify-nav')
        ?.classList.toggle('scrolled', window.scrollY > 40);
}, {passive:true});

/* ── Flash toasts ── */
document.querySelectorAll('.flash-toast')
    .forEach(t => setTimeout(() => t.remove(), 4000));

window.showFlash = function(msg, type='success') {
    const icons  = {success:'fa-check-circle',error:'fa-exclamation-circle',warning:'fa-exclamation-triangle'};
    const colors = {success:'#10B981',error:'#EF4444',warning:'#D4AF37'};
    const el = document.createElement('div');
    el.className = `flash-toast ${type}`;
    el.innerHTML = `<i class="fas ${icons[type]||icons.success}" style="color:${colors[type]||colors.success}"></i>${msg}`;
    document.getElementById('flash-container').appendChild(el);
    setTimeout(() => el.remove(), 4000);
};

/* ── Scroll animations ── */
const _observer = new IntersectionObserver((entries) => {
    entries.forEach(e => {
        if (e.isIntersecting) {
            setTimeout(() => e.target.classList.add('visible'), e.target.dataset.delay || 0);
            _observer.unobserve(e.target);
        }
    });
}, {threshold:0.1});
document.querySelectorAll('.fade-up,.fade-in').forEach(el => _observer.observe(el));
</script>

@stack('scripts')
@include('chatbot.widget')
</body>
</html>