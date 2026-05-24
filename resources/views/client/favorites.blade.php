@extends('layouts.app')
@section('title', 'Mes Favoris — Rentify')

@push('styles')
<style>
:root {
    --gold:#D4AF37; --gold-dk:#9A7D20; --gold-pale:#FEF9E7;
    --navy:#0F1B2D; --navy2:#162540;
    --cream:#FAF7F0; --cream2:#F0EBE0; --cream3:#E8DDD0;
    --txt:#5a5660; --txt-light:#9992a4;
    --red:#ef4444; --green:#10b981;
}
body { background: var(--cream); }

.favs-page { max-width:1280px; margin:0 auto; padding:32px; }

/* ── Page Header ── */
.favs-page-header {
    display:flex; align-items:center; justify-content:space-between;
    flex-wrap:wrap; gap:16px; margin-bottom:28px;
}
.favs-page-title {
    font-family:'Playfair Display',Georgia,serif;
    font-size:1.8rem; font-weight:700; color:var(--navy);
    display:flex; align-items:center; gap:12px;
}
.favs-page-title i { color:var(--gold); font-size:1.5rem; }
.favs-page-sub { font-size:.88rem; color:var(--txt); margin-top:4px; }
.favs-count-badge {
    background:rgba(212,175,55,.12); color:var(--gold-dk);
    border:1px solid rgba(212,175,55,.25);
    font-size:.78rem; font-weight:800;
    padding:5px 16px; border-radius:100px;
    display:flex; align-items:center; gap:6px;
}

/* ── Filter bar ── */
.favs-filter-bar {
    display:flex; gap:10px; flex-wrap:wrap; align-items:center;
    background:#fff; border:1px solid rgba(212,175,55,.15);
    border-radius:12px; padding:14px 18px; margin-bottom:24px;
    box-shadow:0 2px 12px rgba(15,27,45,.04);
}
.favs-filter-bar select,
.favs-filter-bar input {
    border:1px solid rgba(212,175,55,.2); border-radius:8px;
    padding:8px 12px; font-size:.82rem; color:var(--navy);
    background:var(--cream); outline:none; transition:border-color .2s;
    font-family:inherit;
}
.favs-filter-bar select:focus,
.favs-filter-bar input:focus { border-color:var(--gold); }
.favs-filter-bar input { min-width:200px; }
.btn-clear-filter {
    font-size:.78rem; color:var(--txt-light); cursor:pointer;
    background:none; border:none; font-family:inherit; padding:8px;
    text-decoration:underline; transition:color .15s;
}
.btn-clear-filter:hover { color:var(--red); }

/* ── Grid ── */
.favs-grid {
    display:grid;
    grid-template-columns:repeat(auto-fill,minmax(260px,1fr));
    gap:20px;
}

/* ── Fav Card ── */
.fav-card {
    background:#fff; border:1px solid rgba(212,175,55,.12);
    border-radius:16px; overflow:hidden; position:relative;
    transition:transform .22s, box-shadow .22s, border-color .22s;
    animation:cardIn .4s ease both;
}
@keyframes cardIn { from{opacity:0;transform:translateY(14px)} to{opacity:1;transform:translateY(0)} }
.fav-card:hover {
    transform:translateY(-5px);
    box-shadow:0 16px 40px rgba(15,27,45,.1);
    border-color:rgba(212,175,55,.35);
}

/* Photo */
.fav-photo {
    height:160px; overflow:hidden; position:relative;
    background:var(--navy);
}
.fav-photo img {
    width:100%; height:100%; object-fit:cover;
    transition:transform .35s ease;
    filter:brightness(.85);
}
.fav-card:hover .fav-photo img { transform:scale(1.06); filter:brightness(.95); }

/* Remove button */
.fav-remove-btn {
    position:absolute; top:10px; right:10px; z-index:5;
    width:32px; height:32px; border-radius:50%;
    background:rgba(239,68,68,.85); border:none;
    color:#fff; cursor:pointer;
    display:flex; align-items:center; justify-content:center;
    font-size:.82rem; transition:all .2s;
    box-shadow:0 2px 8px rgba(0,0,0,.2);
}
.fav-remove-btn:hover { background:#ef4444; transform:scale(1.1); }

/* Status badge */
.fav-status {
    position:absolute; bottom:10px; left:10px;
    font-size:.65rem; font-weight:800; letter-spacing:.5px;
    padding:3px 10px; border-radius:100px;
    text-transform:uppercase;
}
.fav-status.available { background:rgba(16,185,129,.15); color:#065f46; border:1px solid rgba(16,185,129,.3); }
.fav-status.unavailable { background:rgba(212,175,55,.15); color:var(--gold-dk); border:1px solid rgba(212,175,55,.3); }

/* Body */
.fav-body { padding:14px 16px 16px; }
.fav-type {
    font-size:.65rem; font-weight:800; color:var(--gold);
    letter-spacing:.1em; text-transform:uppercase; margin-bottom:4px;
}
.fav-name {
    font-size:.95rem; font-weight:700; color:var(--navy);
    margin-bottom:5px; font-family:'Playfair Display',Georgia,serif;
}
.fav-city {
    font-size:.78rem; color:var(--txt-light);
    display:flex; align-items:center; gap:4px; margin-bottom:10px;
}
.fav-city i { color:var(--gold); font-size:.72rem; }

/* Price row */
.fav-price-row {
    display:flex; align-items:center; justify-content:space-between;
    padding-top:10px; border-top:1px solid rgba(212,175,55,.1);
    margin-bottom:12px;
}
.fav-price { font-size:1.05rem; font-weight:800; color:var(--navy); }
.fav-price small { font-size:.7rem; font-weight:400; color:var(--txt-light); }
.fav-price-hour { font-size:.75rem; color:var(--txt-light); }

/* Actions */
.fav-actions { display:flex; gap:6px; }
.fav-btn {
    flex:1; border:none; border-radius:8px;
    padding:9px 8px; font-size:.78rem; font-weight:700;
    cursor:pointer; transition:all .15s; font-family:inherit;
    display:flex; align-items:center; justify-content:center; gap:5px;
    text-decoration:none;
}
.fav-btn-detail { background:var(--cream); color:var(--navy); border:1px solid var(--cream3); }
.fav-btn-detail:hover { background:var(--cream2); color:var(--navy); }
.fav-btn-reserve { background:var(--navy); color:var(--gold); }
.fav-btn-reserve:hover { background:var(--navy2); color:var(--gold); }
.fav-btn-wa { background:#25D366; color:#fff; flex:0 0 36px; border-radius:8px; }
.fav-btn-wa:hover { background:#1ebe5d; color:#fff; }

/* ── View toggle ── */
.view-toggle { display:flex; gap:4px; }
.view-btn {
    width:34px; height:34px; border-radius:8px;
    border:1px solid rgba(212,175,55,.2); background:#fff;
    color:var(--txt-light); cursor:pointer;
    display:flex; align-items:center; justify-content:center;
    font-size:.85rem; transition:all .15s;
}
.view-btn.active { background:var(--navy); color:var(--gold); border-color:var(--navy); }

/* ── List view ── */
.favs-list { display:flex; flex-direction:column; gap:12px; }
.fav-list-card {
    background:#fff; border:1px solid rgba(212,175,55,.12);
    border-radius:12px; padding:14px 18px;
    display:flex; align-items:center; gap:16px;
    transition:border-color .2s, transform .2s; position:relative;
    animation:cardIn .35s ease both;
}
.fav-list-card:hover { border-color:rgba(212,175,55,.3); transform:translateX(4px); }
.fav-list-photo {
    width:70px; height:70px; border-radius:50%;
    border:2px solid var(--gold); overflow:hidden; flex-shrink:0;
}
.fav-list-photo img { width:100%; height:100%; object-fit:cover; }
.fav-list-info { flex:1; min-width:0; }
.fav-list-name { font-size:.92rem; font-weight:700; color:var(--navy); margin-bottom:3px; }
.fav-list-meta { font-size:.75rem; color:var(--txt-light); margin-bottom:6px; }
.fav-list-meta i { color:var(--gold); margin-right:3px; }
.fav-list-price { font-size:.9rem; font-weight:800; color:var(--gold-dk); }

/* ── Empty state ── */
.favs-empty {
    text-align:center; padding:80px 20px;
    background:#fff; border:1px solid rgba(212,175,55,.12);
    border-radius:16px;
}
.favs-empty-ico {
    width:80px; height:80px; border-radius:50%;
    background:rgba(212,175,55,.08); border:2px solid rgba(212,175,55,.2);
    display:flex; align-items:center; justify-content:center;
    font-size:2rem; margin:0 auto 20px;
}
.favs-empty-title { font-size:1.1rem; font-weight:700; color:var(--navy); margin-bottom:8px; }
.favs-empty-sub { font-size:.88rem; color:var(--txt-light); margin-bottom:24px; }
.btn-browse {
    display:inline-flex; align-items:center; gap:8px;
    background:var(--navy); color:var(--gold)!important;
    font-size:.88rem; font-weight:700; border:none;
    border-radius:9px; padding:11px 26px; cursor:pointer;
    text-decoration:none; transition:background .15s, transform .15s;
}
.btn-browse:hover { background:var(--navy2); transform:translateY(-1px); }

/* ── Toast ── */
#fav-toast {
    position:fixed; bottom:24px; right:24px; z-index:9999;
    background:var(--navy); color:#fff;
    padding:12px 20px; border-radius:10px; font-size:.88rem; font-weight:600;
    box-shadow:0 4px 20px rgba(15,27,45,.25);
    display:none; align-items:center; gap:10px;
    border-left:3px solid var(--gold);
    animation:slideUp .3s ease;
}
#fav-toast.show { display:flex; }
#fav-toast.err  { border-left-color:var(--red); }
@keyframes slideUp { from{transform:translateY(14px);opacity:0} to{transform:translateY(0);opacity:1} }

/* ── Skeleton ── */
.skel {
    background:linear-gradient(90deg,#f5f0e8 25%,#ede8df 50%,#f5f0e8 75%);
    background-size:200% 100%; animation:shimmer 1.5s infinite; border-radius:6px;
}
@keyframes shimmer { 0%{background-position:200% 0} 100%{background-position:-200% 0} }

@media(max-width:768px) {
    .favs-page { padding:16px; }
    .favs-grid { grid-template-columns:1fr 1fr; }
    .favs-filter-bar { flex-direction:column; align-items:stretch; }
    .favs-filter-bar input { min-width:unset; }
}
@media(max-width:480px) {
    .favs-grid { grid-template-columns:1fr; }
}
</style>
@endpush

@section('content')
<div class="favs-page">

    {{-- Header --}}
    <div class="favs-page-header">
        <div>
            <div class="favs-page-title">
                <i class="fas fa-heart"></i> Mes Favoris
            </div>
            <div class="favs-page-sub">Vos machines sauvegardées pour une réservation rapide</div>
        </div>
        <div style="display:flex;align-items:center;gap:10px">
            <div class="favs-count-badge">
                <i class="fas fa-heart"></i>
                <span id="favs-count">—</span> machine(s)
            </div>
            <div class="view-toggle">
                <button class="view-btn active" id="btn-grid" onclick="setView('grid')" title="Grille">
                    <i class="fas fa-th-large"></i>
                </button>
                <button class="view-btn" id="btn-list" onclick="setView('list')" title="Liste">
                    <i class="fas fa-list"></i>
                </button>
            </div>
        </div>
    </div>

    {{-- Filter bar --}}
    <div class="favs-filter-bar" id="filter-bar" style="display:none">
        <input type="text" id="f-search" placeholder="🔍 Rechercher dans vos favoris..."
            oninput="applyFilters()">
        <select id="f-type" onchange="applyFilters()">
            <option value="">Tous les types</option>
            <option value="excavatrice">Excavatrice</option>
            <option value="grue">Grue</option>
            <option value="bulldozer">Bulldozer</option>
            <option value="chargeuse">Chargeuse</option>
            <option value="compacteur">Compacteur</option>
            <option value="tractopelle">Tractopelle</option>
            <option value="camion">Camion</option>
            <option value="nacelle">Nacelle</option>
        </select>
        <select id="f-city" onchange="applyFilters()">
            <option value="">Toutes les villes</option>
            <option value="casablanca">Casablanca</option>
            <option value="rabat">Rabat</option>
            <option value="marrakech">Marrakech</option>
            <option value="tanger">Tanger</option>
            <option value="fès">Fès</option>
            <option value="agadir">Agadir</option>
        </select>
        <select id="f-sort" onchange="applyFilters()">
            <option value="">Trier par</option>
            <option value="price_asc">Prix croissant</option>
            <option value="price_desc">Prix décroissant</option>
            <option value="name">Nom A-Z</option>
        </select>
        <button class="btn-clear-filter" onclick="clearFilters()">✕ Réinitialiser</button>
    </div>

    {{-- Content --}}
    <div id="favs-content">
        {{-- Skeletons --}}
        <div class="favs-grid" id="favs-skeleton">
            @for($i=0;$i<6;$i++)
            <div style="border-radius:16px;overflow:hidden;border:1px solid rgba(212,175,55,.1);background:#fff">
                <div class="skel" style="height:160px;border-radius:0"></div>
                <div style="padding:14px 16px">
                    <div class="skel" style="height:9px;width:40%;margin-bottom:8px"></div>
                    <div class="skel" style="height:14px;width:78%;margin-bottom:6px"></div>
                    <div class="skel" style="height:10px;width:50%;margin-bottom:14px"></div>
                    <div style="display:flex;gap:6px">
                        <div class="skel" style="height:34px;flex:1;border-radius:8px"></div>
                        <div class="skel" style="height:34px;flex:1;border-radius:8px"></div>
                    </div>
                </div>
            </div>
            @endfor
        </div>
    </div>

</div>

<div id="fav-toast">
    <i class="fas fa-heart" style="color:var(--gold)"></i>
    <span id="toast-msg"></span>
</div>
@endsection

@push('scripts')
<script>
/* ── Guard ── */
// Pas de guard strict — les favoris sont en localStorage, accessible même non connecté

/* ── State ── */
const TYPE_PHOTO = {
    excavatrice:'/images/img3.png', grue:'/images/img4.png',
    bulldozer:'/images/img1.png',   chargeuse:'/images/img2.png',
    compacteur:'/images/img8.png',  nacelle:'/images/img5.png',
    tractopelle:'/images/img7.png', camion:'/images/img9.png',
};
const getPhoto = m => TYPE_PHOTO[(m.type||'').toLowerCase()] || '/images/img7.png';

let allFavMachines = [];
let currentView = 'grid';

/* ── Toast ── */
function toast(msg, isErr=false) {
    const t = document.getElementById('fav-toast');
    document.getElementById('toast-msg').textContent = msg;
    t.className = 'show' + (isErr?' err':'');
    setTimeout(() => t.className = '', 3000);
}

/* ── View toggle ── */
window.setView = function(v) {
    currentView = v;
    document.getElementById('btn-grid').classList.toggle('active', v==='grid');
    document.getElementById('btn-list').classList.toggle('active', v==='list');
    renderMachines(getFiltered());
};

/* ── Load favorites from API ── */
async function loadFavorites() {
    const favIds = getFavorites();

    document.getElementById('favs-count').textContent = favIds.length;

    if (!favIds.length) {
        showEmpty();
        return;
    }

    // Show filter bar
    document.getElementById('filter-bar').style.display = 'flex';

    // Fetch each machine
    const promises = favIds.map(id =>
        API.get(`/api/machines/${id}`).then(d => d?.data || d).catch(() => null)
    );
    const results = await Promise.all(promises);
    allFavMachines = results.filter(Boolean);

    if (!allFavMachines.length) { showEmpty(); return; }

    renderMachines(allFavMachines);
}

/* ── Filters ── */
function getFiltered() {
    let list = [...allFavMachines];
    const search = document.getElementById('f-search')?.value?.toLowerCase() || '';
    const type   = document.getElementById('f-type')?.value?.toLowerCase()   || '';
    const city   = document.getElementById('f-city')?.value?.toLowerCase()   || '';
    const sort   = document.getElementById('f-sort')?.value                  || '';

    if (search) list = list.filter(m => (m.name||'').toLowerCase().includes(search));
    if (type)   list = list.filter(m => (m.type||'').toLowerCase() === type);
    if (city)   list = list.filter(m => (m.city||m.location||'').toLowerCase().includes(city));

    if (sort === 'price_asc')  list.sort((a,b) => (a.price_per_day||0) - (b.price_per_day||0));
    if (sort === 'price_desc') list.sort((a,b) => (b.price_per_day||0) - (a.price_per_day||0));
    if (sort === 'name')       list.sort((a,b) => (a.name||'').localeCompare(b.name||''));

    return list;
}

window.applyFilters = function() { renderMachines(getFiltered()); };
window.clearFilters = function() {
    document.getElementById('f-search').value = '';
    document.getElementById('f-type').value   = '';
    document.getElementById('f-city').value   = '';
    document.getElementById('f-sort').value   = '';
    renderMachines(allFavMachines);
};

/* ── Render ── */
function renderMachines(list) {
    const content = document.getElementById('favs-content');

    if (!list.length) {
        content.innerHTML = `
        <div class="favs-empty">
            <div class="favs-empty-ico">🔍</div>
            <div class="favs-empty-title">Aucun résultat</div>
            <div class="favs-empty-sub">Modifiez vos filtres pour voir plus de machines</div>
            <button class="btn-browse" onclick="clearFilters()"><i class="fas fa-undo"></i> Réinitialiser les filtres</button>
        </div>`;
        return;
    }

    if (currentView === 'list') {
        content.innerHTML = `<div class="favs-list">${list.map((m,i) => buildListCard(m,i)).join('')}</div>`;
    } else {
        content.innerHTML = `<div class="favs-grid">${list.map((m,i) => buildGridCard(m,i)).join('')}</div>`;
    }
}

/* ── Grid Card ── */
function buildGridCard(m, i) {
    const photo = getPhoto(m);
    const price = Number(m.price_per_day||0).toLocaleString('fr-MA');
    const priceH = Number(m.price_per_hour||0).toLocaleString('fr-MA');
    const dispo = m.status === 'available';
    const waUrl = buildWa(m);
    return `
    <div class="fav-card" id="fav-${m.id}" style="animation-delay:${i*55}ms">
        <button class="fav-remove-btn" onclick="removeFav(${m.id})" title="Retirer des favoris">
            <i class="fas fa-heart-broken"></i>
        </button>
        <div class="fav-photo">
            <img src="${photo}" alt="${m.name}" onerror="this.src='/images/img7.png'">
            <div class="fav-status ${dispo?'available':'unavailable'}">
                ${dispo ? '● Disponible' : '● Indisponible'}
            </div>
        </div>
        <div class="fav-body">
            <div class="fav-type">${m.type||'—'}</div>
            <div class="fav-name">${m.name}</div>
            <div class="fav-city"><i class="fas fa-map-marker-alt"></i>${m.city||m.location||'—'}</div>
            <div class="fav-price-row">
                <div>
                    <div class="fav-price">${price} <small>DH/jour</small></div>
                    <div class="fav-price-hour">${priceH} DH/heure</div>
                </div>
                ${m.avg_rating ? `<div style="font-size:.78rem;color:var(--txt)"><i class="fas fa-star" style="color:var(--gold)"></i> ${parseFloat(m.avg_rating).toFixed(1)}</div>` : ''}
            </div>
            <div class="fav-actions">
                <a href="/machines/${m.id}" class="fav-btn fav-btn-detail">
                    <i class="fas fa-eye"></i> Détails
                </a>
                <a href="/machines/${m.id}" class="fav-btn fav-btn-reserve">
                    <i class="fas fa-calendar-check"></i> Réserver
                </a>
                ${waUrl ? `<a href="${waUrl}" target="_blank" class="fav-btn fav-btn-wa" title="WhatsApp"><i class="fab fa-whatsapp"></i></a>` : ''}
            </div>
        </div>
    </div>`;
}

/* ── List Card ── */
function buildListCard(m, i) {
    const photo = getPhoto(m);
    const price = Number(m.price_per_day||0).toLocaleString('fr-MA');
    const dispo = m.status === 'available';
    const waUrl = buildWa(m);
    return `
    <div class="fav-list-card" id="fav-${m.id}" style="animation-delay:${i*45}ms">
        <div class="fav-list-photo">
            <img src="${photo}" alt="${m.name}" onerror="this.src='/images/img7.png'">
        </div>
        <div class="fav-list-info">
            <div class="fav-list-name">${m.name}</div>
            <div class="fav-list-meta">
                <i class="fas fa-map-marker-alt"></i>${m.city||m.location||'—'}
                &nbsp;·&nbsp; <i class="fas fa-tag"></i>${m.type||'—'}
                &nbsp;·&nbsp; <span style="color:${dispo?'var(--green)':'var(--gold-dk)'}">${dispo?'Disponible':'Indisponible'}</span>
            </div>
            <div class="fav-list-price">${price} DH/jour</div>
        </div>
        <div style="display:flex;gap:6px;flex-shrink:0;flex-wrap:wrap">
            <a href="/machines/${m.id}" class="fav-btn fav-btn-detail" style="flex:0 0 auto">
                <i class="fas fa-eye"></i>
            </a>
            <a href="/machines/${m.id}" class="fav-btn fav-btn-reserve" style="flex:0 0 auto">
                <i class="fas fa-calendar-check"></i> Réserver
            </a>
            ${waUrl ? `<a href="${waUrl}" target="_blank" class="fav-btn fav-btn-wa" style="flex:0 0 36px"><i class="fab fa-whatsapp"></i></a>` : ''}
            <button class="fav-btn fav-btn-detail" style="flex:0 0 auto;color:var(--red);border-color:rgba(239,68,68,.2)" onclick="removeFav(${m.id})">
                <i class="fas fa-heart-broken"></i>
            </button>
        </div>
    </div>`;
}

/* ── WhatsApp ── */
function buildWa(m) {
    const phone = m.owner?.phone;
    if (!phone) return null;
    const num = phone.replace(/\D/g,'').replace(/^0/,'212');
    const msg = encodeURIComponent(`Bonjour, je suis intéressé par la location de "${m.name}" sur Rentify.`);
    return `https://wa.me/${num}?text=${msg}`;
}

/* ── Remove fav ── */
window.removeFav = function(id) {
    toggleFavorite(id);
    const card = document.getElementById('fav-'+id);
    if (card) {
        card.style.transition = 'opacity .3s, transform .3s';
        card.style.opacity = '0';
        card.style.transform = 'scale(.92)';
        setTimeout(() => {
            allFavMachines = allFavMachines.filter(m => m.id !== id);
            document.getElementById('favs-count').textContent = allFavMachines.length;
            if (!allFavMachines.length) {
                document.getElementById('filter-bar').style.display = 'none';
                showEmpty();
            } else {
                renderMachines(getFiltered());
            }
        }, 300);
    }
    window.refreshFavsBadge?.();
    toast('Retiré des favoris');
};

/* ── Empty state ── */
function showEmpty() {
    document.getElementById('favs-content').innerHTML = `
    <div class="favs-empty">
        <div class="favs-empty-ico">❤️</div>
        <div class="favs-empty-title">Aucun favori pour l'instant</div>
        <div class="favs-empty-sub">
            Ajoutez des machines à vos favoris depuis le catalogue<br>
            en cliquant sur le cœur ❤️ sur chaque machine.
        </div>
        <a href="/machines" class="btn-browse">
            <i class="fas fa-search"></i> Parcourir le catalogue
        </a>
    </div>`;
    document.getElementById('favs-count').textContent = '0';
}

/* ── Init ── */
loadFavorites();
</script>
@endpush