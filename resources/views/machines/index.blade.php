@extends('layouts.app')
@section('title', 'Catalogue de machines — Rentify')

@push('styles')
    {{-- ── Leaflet CSS ── --}}
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" crossorigin="" />

    <style>
        .catalogue-header {
            background: var(--navy);
            padding: 32px 0 28px;
        }

        .cat-header-inner {
            max-width: 1280px;
            margin: 0 auto;
            padding: 0 32px;
        }

        .cat-header-title {
            color: #fff;
            font-size: 24px;
            font-weight: 900;
            letter-spacing: -.5px;
            margin-bottom: 4px;
        }

        .cat-header-count {
            color: rgba(255, 255, 255, .4);
            font-size: 13px;
        }

        .catalogue-body {
            max-width: 1280px;
            margin: 0 auto;
            padding: 28px 32px;
            display: grid;
            grid-template-columns: 280px 1fr;
            gap: 28px;
            align-items: start;
        }

        /* ── Search bar ── */
        .cat-search-bar {
            grid-column: 1/-1;
            position: relative;
        }

        .cat-search-bar input {
            width: 100%;
            padding: 14px 18px 14px 48px;
            background: #fff;
            border: 1.5px solid #E5E7EB;
            border-radius: 100px;
            font-size: 14px;
            color: var(--navy);
            outline: none;
            transition: border-color .2s, box-shadow .2s;
            font-family: 'Inter', sans-serif;
        }

        .cat-search-bar input:focus {
            border-color: var(--orange);
            box-shadow: 0 0 0 3px rgba(245, 158, 11, .12);
        }

        .cat-search-icon {
            position: absolute;
            left: 18px;
            top: 50%;
            transform: translateY(-50%);
            color: var(--text-light);
            font-size: 15px;
        }

        /* ── Sidebar filters ── */
        .filters-sidebar {
            background: #fff;
            border: 1px solid #F0F0F0;
            border-radius: var(--radius-lg);
            overflow: hidden;
            position: sticky;
            top: 90px;
        }

        .filter-group {
            padding: 18px 20px;
            border-bottom: 1px solid #F5F5F5;
        }

        .filter-group:last-child {
            border-bottom: none;
        }

        .filter-title {
            font-size: 11px;
            font-weight: 800;
            color: var(--navy);
            letter-spacing: 1px;
            text-transform: uppercase;
            margin-bottom: 12px;
        }

        .filter-btn {
            display: block;
            width: 100%;
            text-align: left;
            padding: 9px 12px;
            border-radius: var(--radius-md);
            font-size: 13px;
            font-weight: 500;
            color: var(--text-gray);
            border: none;
            background: transparent;
            cursor: pointer;
            transition: background .15s, color .15s;
        }

        .filter-btn:hover {
            background: #F9FAFB;
            color: var(--navy);
        }

        .filter-btn.active {
            background: var(--navy);
            color: #fff;
            font-weight: 600;
        }

        .filter-check {
            display: flex;
            align-items: center;
            gap: 9px;
            padding: 7px 0;
            cursor: pointer;
            font-size: 13px;
            color: var(--text-gray);
        }

        .filter-check input[type=checkbox] {
            width: 16px;
            height: 16px;
            accent-color: var(--orange);
            cursor: pointer;
        }

        .filter-check:hover {
            color: var(--navy);
        }

        .price-range-wrap {
            padding: 4px 0;
        }

        .price-slider {
            width: 100%;
            accent-color: var(--orange);
            height: 4px;
            cursor: pointer;
            margin: 8px 0;
        }

        .price-labels {
            display: flex;
            justify-content: space-between;
            font-size: 11px;
            color: var(--text-light);
            font-weight: 500;
        }

        .dispo-toggle {
            display: flex;
            align-items: center;
            justify-content: space-between;
            cursor: pointer;
        }

        .toggle-switch {
            width: 40px;
            height: 22px;
            background: #E5E7EB;
            border-radius: 100px;
            position: relative;
            transition: background .2s;
            cursor: pointer;
            flex-shrink: 0;
        }

        .toggle-switch.on {
            background: var(--orange);
        }

        .toggle-knob {
            position: absolute;
            top: 2px;
            left: 2px;
            width: 18px;
            height: 18px;
            background: #fff;
            border-radius: 50%;
            transition: transform .2s;
            box-shadow: 0 1px 4px rgba(0, 0, 0, .2);
        }

        .toggle-switch.on .toggle-knob {
            transform: translateX(18px);
        }

        /* ── Results grid ── */
        .results-header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-bottom: 16px;
        }

        .results-count {
            font-size: 13px;
            color: var(--text-gray);
            font-weight: 500;
        }

        .results-count strong {
            color: var(--navy);
        }

        .sort-select {
            font-size: 13px;
            color: var(--navy);
            font-weight: 500;
            border: 1px solid #E5E7EB;
            border-radius: var(--radius-md);
            padding: 7px 12px;
            outline: none;
            cursor: pointer;
            font-family: 'Inter', sans-serif;
            background: #fff;
        }

        .machines-catalogue {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 16px;
        }

        .mc-card {
            background: #fff;
            border: 1px solid #F0F0F0;
            border-radius: var(--radius-lg);
            overflow: hidden;
            cursor: pointer;
            transition: transform .2s, box-shadow .2s, border-color .2s;
            animation: cardIn .4s ease both;
        }

        @keyframes cardIn {
            from {
                opacity: 0;
                transform: translateY(16px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .mc-card:hover {
            transform: translateY(-4px);
            box-shadow: 0 12px 36px rgba(0, 0, 0, .1);
            border-color: var(--orange);
        }

        .mc-card:hover .mc-card-img-inner {
            transform: scale(1.06);
        }

        .mc-card-img {
            height: 170px;
            overflow: hidden;
            position: relative;
            background: var(--navy-light);
        }

        .mc-card-img-inner {
            width: 100%;
            height: 100%;
            object-fit: cover;
            transition: transform .35s ease;
        }

        .mc-card-ph {
            width: 100%;
            height: 100%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 52px;
            background: linear-gradient(135deg, #152236, #1D3557);
        }

        .mc-card-body {
            padding: 14px 15px;
        }

        .mc-card-type {
            font-size: 9px;
            font-weight: 800;
            color: var(--orange);
            letter-spacing: 1px;
            text-transform: uppercase;
            margin-bottom: 3px;
        }

        .mc-card-name {
            font-size: 14px;
            font-weight: 800;
            color: var(--navy);
            margin-bottom: 5px;
            letter-spacing: -.2px;
        }

        .mc-card-loc {
            font-size: 12px;
            color: var(--text-gray);
            display: flex;
            align-items: center;
            gap: 4px;
            margin-bottom: 12px;
        }

        .mc-card-loc i {
            color: var(--orange);
            font-size: 10px;
        }

        .mc-card-footer {
            display: flex;
            align-items: center;
            justify-content: space-between;
            border-top: 1px solid #F5F5F5;
            padding-top: 10px;
        }

        .mc-card-price {
            font-size: 18px;
            font-weight: 900;
            color: var(--navy);
        }

        .mc-card-price small {
            font-size: 11px;
            color: var(--text-light);
            font-weight: 400;
        }

        /* ── Loading skeleton ── */
        .skeleton {
            background: linear-gradient(90deg, #f0f0f0 25%, #e8e8e8 50%, #f0f0f0 75%);
            background-size: 200% 100%;
            animation: shimmer 1.5s infinite;
            border-radius: 6px;
        }

        @keyframes shimmer {
            0% {
                background-position: 200% 0;
            }

            100% {
                background-position: -200% 0;
            }
        }

        .skeleton-card {
            background: #fff;
            border: 1px solid #F0F0F0;
            border-radius: var(--radius-lg);
            overflow: hidden;
        }

        /* ── Pagination ── */
        .pagination-wrap {
            display: flex;
            justify-content: center;
            gap: 6px;
            margin-top: 32px;
        }

        .page-btn {
            width: 36px;
            height: 36px;
            border: 1px solid #E5E7EB;
            border-radius: var(--radius-md);
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 13px;
            font-weight: 600;
            color: var(--text-gray);
            cursor: pointer;
            transition: all .15s;
            background: #fff;
        }

        .page-btn:hover,
        .page-btn.active {
            background: var(--navy);
            border-color: var(--navy);
            color: #fff;
        }

        /* ══════════════════════════════════════════════════
                       FEATURE 4 — Carte Leaflet
                       ══════════════════════════════════════════════════ */

        /* Popup Leaflet personnalisé couleurs Rentify */
        .rentify-popup .leaflet-popup-content-wrapper {
            background: #0F1B2D;
            color: #fff;
            border-radius: 10px;
            padding: 0;
            overflow: hidden;
            box-shadow: 0 8px 24px rgba(0, 0, 0, 0.4);
            min-width: 220px;
        }

        .rentify-popup .leaflet-popup-tip {
            background: #0F1B2D;
        }

        .rentify-popup .leaflet-popup-close-button {
            color: #F59E0B !important;
            font-size: 18px !important;
            top: 8px !important;
            right: 8px !important;
        }

        .map-popup-img {
            width: 100%;
            height: 120px;
            object-fit: cover;
        }

        .map-popup-body {
            padding: 12px 14px;
        }

        .map-popup-name {
            font-size: 14px;
            font-weight: 700;
            color: #F59E0B;
            margin-bottom: 4px;
        }

        .map-popup-cat {
            font-size: 12px;
            color: #94a3b8;
            margin-bottom: 8px;
        }

        .map-popup-price {
            font-size: 15px;
            font-weight: 700;
            color: #fff;
            margin-bottom: 10px;
        }

        .map-popup-price span {
            color: #F59E0B;
        }

        .map-popup-btn {
            display: block;
            background: #F59E0B;
            color: #0F1B2D !important;
            text-align: center;
            padding: 7px;
            border-radius: 6px;
            font-weight: 700;
            font-size: 13px;
            text-decoration: none;
        }

        .map-popup-btn:hover {
            background: #d97706;
        }

        /* Marker personnalisé */
        .custom-marker {
            background: #F59E0B;
            border: 3px solid #0F1B2D;
            border-radius: 50% 50% 50% 0;
            transform: rotate(-45deg);
            width: 30px;
            height: 30px;
            display: flex;
            align-items: center;
            justify-content: center;
            box-shadow: 0 3px 10px rgba(0, 0, 0, 0.4);
            animation: markerPulse 2s infinite;
        }

        .custom-marker i {
            transform: rotate(45deg);
            color: #0F1B2D;
            font-size: 13px;
        }

        @keyframes markerPulse {
            0% {
                box-shadow: 0 0 0 0 rgba(245, 158, 11, 0.6);
            }

            70% {
                box-shadow: 0 0 0 10px rgba(245, 158, 11, 0);
            }

            100% {
                box-shadow: 0 0 0 0 rgba(245, 158, 11, 0);
            }
        }

        @media (max-width: 1100px) {
            .machines-catalogue {
                grid-template-columns: repeat(2, 1fr);
            }
        }

        @media (max-width: 768px) {
            .catalogue-body {
                grid-template-columns: 1fr;
            }

            .filters-sidebar {
                position: static;
            }

            .machines-catalogue {
                grid-template-columns: 1fr;
            }

            .cat-header-inner,
            .cat-search-bar {
                padding: 0 16px;
            }
        }



        /* ── Bouton carte animé ── */
        .btn-map-toggle {
            background: transparent;
            color: #F59E0B;
            border: 2px solid #F59E0B;
            border-radius: 50px;
            padding: 10px 24px;
            font-size: 14px;
            position: relative;
            overflow: hidden;
            transition: color .3s ease, box-shadow .3s ease;
        }

        /* Effet vague au clic */
        .btn-map-toggle::before {
            content: '';
            position: absolute;
            inset: 0;
            background: #F59E0B;
            transform: scaleX(0);
            transform-origin: left;
            transition: transform .3s ease;
            z-index: 0;
            border-radius: 50px;
        }

        .btn-map-toggle:hover::before,
        .btn-map-toggle.active-map::before {
            transform: scaleX(1);
        }

        .btn-map-toggle:hover,
        .btn-map-toggle.active-map {
            color: #0F1B2D !important;
            box-shadow: 0 4px 20px rgba(245, 158, 11, 0.45);
        }

        .btn-map-toggle span,
        .btn-map-toggle i {
            position: relative;
            z-index: 1;
        }

        /* Animation pulse sur l'icône */
        @keyframes iconPulse {

            0%,
            100% {
                transform: scale(1);
            }

            50% {
                transform: scale(1.25);
            }
        }

        .btn-map-toggle:hover i {
            animation: iconPulse .5s ease;
        }


        .btn-reserver {
    background: #0F1B2D;
    color: #F59E0B;
    border: 1.5px solid #F59E0B;
    padding: 10px 22px;
    border-radius: 8px;
    font-size: 13px;
    font-weight: 700;
    cursor: pointer;
    text-decoration: none;
    transition: all .2s;
    display: inline-block;
}
.btn-reserver:hover {
    background: #F59E0B;
    color: #0F1B2D;
}
    </style>
@endpush

@section('content')

    {{-- ── Header ── --}}
    <div class="catalogue-header">
        <div class="cat-header-inner">
            <div class="cat-header-title">Catalogue de machines</div>
            <div class="cat-header-count" id="results-label">Chargement...</div>
        </div>
    </div>

    {{-- ── Search bar ── --}}
    <div style="max-width:1280px;margin:0 auto;padding:20px 32px 0">
        <div class="cat-search-bar" style="margin-bottom:0">
            <i class="fas fa-search cat-search-icon"></i>
            <input type="text" id="search-input" placeholder="Rechercher une machine (JCB, Grue, Excavatrice...)"
                oninput="debounceSearch()">
        </div>
    </div>

    {{-- ══════════════════════════════════════════════════
     FEATURE 4 — Bouton Toggle + Section Carte Leaflet
     ══════════════════════════════════════════════════ --}}
    <div style="max-width:1280px;margin:16px auto 0;padding:0 32px;">

        {{-- Bouton afficher/masquer la carte --}}
        <button id="btnToggleMap" class="btn fw-bold btn-map-toggle" onclick="toggleMap()">
            <i class="fas fa-map-marked-alt me-2" id="mapBtnIcon"></i>
            <span id="mapBtnText">Afficher la carte</span>
        </button>
        <span class="text-muted ms-3 small" id="mapMachinesCount"></span>

        {{-- Section carte (cachée par défaut) --}}
        <div id="mapSection" style="display:none; margin-top:16px;">
            <div class="card border-0 shadow-sm rounded-3 overflow-hidden">

                {{-- Header de la carte --}}
                <div class="card-header d-flex align-items-center justify-content-between" style="background:#0F1B2D;">
                    <div>
                        <i class="fas fa-map-marked-alt text-warning me-2"></i>
                        <span class="text-white fw-bold">Localisation des machines</span>
                    </div>
                    <div class="d-flex gap-2 align-items-center">
                        {{-- Filtre ville depuis la carte --}}
                        <select id="mapCityFilter" class="form-select form-select-sm"
                            style="width:160px;background:#1a2f4a;color:#fff;border-color:#F59E0B;"
                            onchange="filterMapByCity(this.value)">
                            <option value="">Toutes les villes</option>
                        </select>
                        <button class="btn btn-sm btn-outline-warning" onclick="toggleMap()">
                            <i class="fas fa-times"></i>
                        </button>
                    </div>
                </div>

                {{-- La carte --}}
                <div id="rentifyMap" style="height:480px;width:100%;"></div>

                {{-- Légende --}}
                <div class="card-footer" style="background:#0F1B2D;border-top:1px solid #1a2f4a;">
                    <div class="d-flex flex-wrap gap-3 small text-muted">
                        <span><i class="fas fa-circle text-warning me-1"></i>Machine disponible</span>
                        <span><i class="fas fa-mouse-pointer me-1 text-white-50"></i>Cliquez sur un marqueur pour voir les
                            détails</span>
                    </div>
                </div>

            </div>
        </div>
    </div>
    {{-- ══ Fin Feature 4 ══ --}}

    <div class="catalogue-body">

        {{-- ── SIDEBAR FILTRES ── --}}
        <aside class="filters-sidebar">

            {{-- Type de machine --}}
            <div class="filter-group">
                <div class="filter-title">Type de machine</div>
                <button class="filter-btn active" onclick="setType('', this)">Tous</button>
                @foreach (['Excavatrice', 'Camion', 'Grue', 'Manitou', 'Compacteur', 'Bulldozer', 'Niveleuse'] as $t)
                    <button class="filter-btn" onclick="setType('{{ $t }}', this)">{{ $t }}</button>
                @endforeach
            </div>

            {{-- Prix --}}
            <div class="filter-group">
                <div class="filter-title">Prix / Jour (DH)</div>
                <div class="price-range-wrap">
                    <input type="range" class="price-slider" id="price-slider" min="0" max="5000"
                        value="5000" oninput="updatePrice(this.value)">
                    <div class="price-labels">
                        <span>0 dh</span>
                        <span id="price-label" style="color:var(--orange);font-weight:700">5 000 dh</span>
                    </div>
                </div>
            </div>

            {{-- Marque --}}
            <div class="filter-group">
                <div class="filter-title">Marque</div>
                @foreach (['JCB', 'Manitou', 'Volvo', 'Liebherr', 'Caterpillar', 'Komatsu'] as $b)
                    <label class="filter-check">
                        <input type="checkbox" value="{{ $b }}" onchange="applyFilters()"> {{ $b }}
                    </label>
                @endforeach
            </div>

            {{-- Ville --}}
            <div class="filter-group">
                <div class="filter-title">Ville</div>
                <button class="filter-btn active" onclick="setVille('', this)">Toutes les villes</button>
                @foreach (['Casablanca', 'Rabat', 'Marrakech', 'Tanger', 'Fès', 'Agadir'] as $v)
                    <button class="filter-btn" onclick="setVille('{{ $v }}', this)">{{ $v }}</button>
                @endforeach
            </div>

            {{-- Disponibles uniquement --}}
            <div class="filter-group">
                <div class="dispo-toggle" onclick="toggleDispo()">
                    <span style="font-size:13px;font-weight:600;color:var(--navy)">Disponibles uniquement</span>
                    <div class="toggle-switch" id="dispo-toggle">
                        <div class="toggle-knob"></div>
                    </div>
                </div>
            </div>

        </aside>

        {{-- ── GRILLE RÉSULTATS ── --}}
        <div>
            <div class="results-header">
                <div class="results-count" id="count-label">
                    <strong id="count-num">—</strong> machines trouvées
                </div>
                <select class="sort-select" id="sort-select" onchange="applyFilters()">
                    <option value="created_at">Plus récents</option>
                    <option value="price_asc">Prix croissant</option>
                    <option value="price_desc">Prix décroissant</option>
                    <option value="rating">Mieux notés</option>
                </select>
            </div>

            <div class="machines-catalogue" id="machines-grid">
                {{-- Skeletons initiaux --}}
                @for ($i = 0; $i < 6; $i++)
                    <div class="skeleton-card">
                        <div class="skeleton" style="height:170px;border-radius:0"></div>
                        <div style="padding:14px">
                            <div class="skeleton" style="height:10px;width:40%;margin-bottom:8px"></div>
                            <div class="skeleton" style="height:16px;width:80%;margin-bottom:6px"></div>
                            <div class="skeleton" style="height:12px;width:55%;margin-bottom:14px"></div>
                            <div style="display:flex;justify-content:space-between">
                                <div class="skeleton" style="height:22px;width:30%"></div>
                                <div class="skeleton" style="height:32px;width:25%;border-radius:8px"></div>
                            </div>
                        </div>
                    </div>
                @endfor
            </div>

            <div class="pagination-wrap" id="pagination"></div>
        </div>
    </div>

@endsection

@push('scripts')
    {{-- ── Leaflet JS ── --}}
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js" crossorigin=""></script>

    <script>
        /* ══════════════════════════════════════════════════
                       FILTRES CATALOGUE
                       ══════════════════════════════════════════════════ */
        let filters = {
            type: '',
            ville: '',
            maxPrice: 5000,
            disponible: false,
            search: '',
            sort: 'created_at'
        };
        let debounceTimer;
        const emojis = {
            Excavatrice: '🏗',
            Camion: '🚛',
            Grue: '🏙',
            Manitou: '🔧',
            Compacteur: '⚙️',
            Bulldozer: '🚧',
            Niveleuse: '🚜'
        };

        function setType(t, btn) {
            filters.type = t;
            document.querySelectorAll('.filter-group:first-child .filter-btn')
                .forEach(b => b.classList.remove('active'));
            btn.classList.add('active');
            applyFilters();
        }

        function setVille(v, btn) {
            filters.ville = v;
            document.querySelectorAll('.filter-group:nth-child(4) .filter-btn')
                .forEach(b => b.classList.remove('active'));
            btn.classList.add('active');
            applyFilters();
        }

        function updatePrice(v) {
            filters.maxPrice = parseInt(v);
            document.getElementById('price-label').textContent =
                parseInt(v).toLocaleString('fr') + ' dh';
            applyFilters();
        }

        function toggleDispo() {
            filters.disponible = !filters.disponible;
            document.getElementById('dispo-toggle')
                .classList.toggle('on', filters.disponible);
            applyFilters();
        }

        function debounceSearch() {
            clearTimeout(debounceTimer);
            filters.search = document.getElementById('search-input').value;
            debounceTimer = setTimeout(applyFilters, 400);
        }

        async function applyFilters() {
            const sort = document.getElementById('sort-select').value;
            const params = new URLSearchParams();
            if (filters.type) params.set('type', filters.type);
            if (filters.ville) params.set('city', filters.ville);
            if (filters.maxPrice < 5000) params.set('max_price', filters.maxPrice);
            if (filters.disponible) params.set('status', 'available');
            if (filters.search) params.set('q', filters.search);
            if (sort === 'price_asc') {
                params.set('sort', 'price_per_day');
                params.set('order', 'asc');
            }
            if (sort === 'price_desc') {
                params.set('sort', 'price_per_day');
                params.set('order', 'desc');
            }

            history.replaceState(null, '', '/machines?' + params.toString());
            showSkeletons();

            try {
                const data = await API.get('/api/machines?' + params.toString());
                renderMachines(data);
            } catch (e) {
                renderDemo();
            }
        }

        function showSkeletons() {
            document.getElementById('machines-grid').innerHTML = Array(6).fill(0).map(() => `
        <div class="skeleton-card">
            <div class="skeleton" style="height:170px;border-radius:0"></div>
            <div style="padding:14px">
                <div class="skeleton" style="height:10px;width:40%;margin-bottom:8px"></div>
                <div class="skeleton" style="height:16px;width:80%;margin-bottom:6px"></div>
                <div class="skeleton" style="height:12px;width:55%;margin-bottom:14px"></div>
                <div style="display:flex;justify-content:space-between">
                    <div class="skeleton" style="height:22px;width:30%"></div>
                    <div class="skeleton" style="height:32px;width:25%;border-radius:8px"></div>
                </div>
            </div>
        </div>`).join('');
        }

        function renderMachines(data) {
            const machines = data?.data || data || [];
            const total = data?.total || machines.length;

            document.getElementById('count-num').textContent = total;
            document.getElementById('results-label').textContent =
                total + ' machine' + (total > 1 ? 's' : '') +
                ' trouvée' + (total > 1 ? 's' : '');

            if (!machines.length) {
                document.getElementById('machines-grid').innerHTML = `
            <div style="grid-column:1/-1;text-align:center;padding:60px 20px;color:var(--text-light)">
                <div style="font-size:48px;margin-bottom:12px">🔍</div>
                <div style="font-size:16px;font-weight:600;margin-bottom:6px">Aucune machine trouvée</div>
                <div style="font-size:13px">Modifiez vos filtres pour voir plus de résultats</div>
            </div>`;
                return;
            }

            document.getElementById('machines-grid').innerHTML = machines.map((m, i) => {
                const dispo = m.status === 'available';
                const emoji = emojis[m.type] || '🏗';
                const delay = i * 60;
                return `
        <div class="mc-card" style="animation-delay:${delay}ms"
             onclick="window.location.href='/machines/${m.id}'">
            <div class="mc-card-img">
                ${m.primary_image
                    ? `<img src="/storage/${m.primary_image.image_url}"
                                                class="mc-card-img-inner" alt="${m.name}" loading="lazy">`
                    : `<div class="mc-card-ph">${emoji}</div>`}
                <div class="${dispo ? 'badge-disponible' : 'badge-disponible badge-indispo'}">
                    ${dispo ? 'Disponible' : 'Indisponible'}
                </div>
                ${m.ratings_avg
                    ? `<div class="badge-rating">
                                               <i class="fas fa-star"></i>${parseFloat(m.ratings_avg).toFixed(1)}
                                           </div>`
                    : ''}
            </div>
            <div class="mc-card-body">
                <div class="mc-card-type">
                    ${m.type || '—'} • ${m.marque || ''}
                    • <span style="color:var(--text-light);font-weight:500">${m.annee || ''}</span>
                </div>
                <div class="mc-card-name">${m.name}</div>
                <div class="mc-card-loc">
                  <i class="fas fa-map-marker-alt"></i>${m.city || '—'}
                </div>
                <div class="mc-card-footer">
                    <div>
                        <div class="mc-card-price">
                            ${parseInt(m.price_per_day).toLocaleString('fr')}
                            <small>dh/jour</small>
                        </div>
                        <div style="font-size:11px;color:var(--text-light);margin-top:1px">
                            ${parseInt(m.price_per_hour).toLocaleString('fr')} dh/heure
                        </div>
                    </div>
                    <a href="/machines/${m.id}" class="btn-reserver"
                       onclick="event.stopPropagation()">Réserver</a>
                </div>
            </div>
        </div>`;
            }).join('');
        }

        function renderDemo() {
            const demo = [{
                    id: 1,
                    type: 'Excavatrice',
                    marque: 'JCB',
                    annee: 2022,
                    name: 'JCB 3CX Backhoe Loader',
                    location: 'Casablanca',
                    status: 'available',
                    ratings_avg: 4.9,
                    price_per_day: 2400,
                    price_per_hour: 350
                },
                {
                    id: 2,
                    type: 'Manitou',
                    marque: 'Manitou',
                    annee: 2021,
                    name: 'Manitou MT 1840 Télescopique',
                    location: 'Rabat',
                    status: 'available',
                    ratings_avg: 4.8,
                    price_per_day: 1900,
                    price_per_hour: 280
                },
                {
                    id: 3,
                    type: 'Camion',
                    marque: 'Volvo',
                    annee: 2023,
                    name: 'Camion Benne Volvo FH16',
                    location: 'Marrakech',
                    status: 'unavailable',
                    ratings_avg: 4.7,
                    price_per_day: 1300,
                    price_per_hour: 190
                },
                {
                    id: 4,
                    type: 'Grue',
                    marque: 'Liebherr',
                    annee: 2020,
                    name: 'Grue Mobile Liebherr LTM',
                    location: 'Tanger',
                    status: 'available',
                    ratings_avg: 4.9,
                    price_per_day: 3600,
                    price_per_hour: 520
                },
                {
                    id: 5,
                    type: 'Excavatrice',
                    marque: 'Caterpillar',
                    annee: 2022,
                    name: 'Caterpillar 320 Excavatrice',
                    location: 'Casablanca',
                    status: 'available',
                    ratings_avg: 4.8,
                    price_per_day: 2900,
                    price_per_hour: 410
                },
                {
                    id: 6,
                    type: 'Bulldozer',
                    marque: 'Komatsu',
                    annee: 2019,
                    name: 'Komatsu D65 Bulldozer',
                    location: 'Fès',
                    status: 'available',
                    ratings_avg: 4.6,
                    price_per_day: 2600,
                    price_per_hour: 380
                },
            ];
            renderMachines({
                data: demo,
                total: demo.length
            });
        }

        /* ══════════════════════════════════════════════════
           FEATURE 4 — Carte GPS Leaflet
           ══════════════════════════════════════════════════ */
        (function() {
            'use strict';

            // Variables internes à la carte
            let map = null;
            let markersData = [];
            let markersMap = {};
            let mapInitialized = false;
            let mapVisible = false;

            /* -- Toggle affichage / masquage de la carte -- */

            window.toggleMap = function() {
                const section = document.getElementById('mapSection');
                const btn = document.getElementById('btnToggleMap');
                mapVisible = !mapVisible;

                if (mapVisible) {
                    section.style.display = 'block';
                    btn.querySelector('span') && (btn.querySelector('span').textContent = 'Masquer la carte');
                    if (!mapInitialized) {
                        initMap();
                    } else {
                        chargerMachinesSurCarte();
                    }
                } else {
                    section.style.display = 'none';
                    btn.querySelector('span') && (btn.querySelector('span').textContent = 'Afficher la carte');
                }
                btn.classList.toggle('active-map', mapVisible); // ← هذا يكفي
            };

            /* -- Initialisation de la carte Leaflet -- */
            function initMap() {
                // Centré sur le Maroc
                map = L.map('rentifyMap', {
                    center: [31.7917, -7.0926],
                    zoom: 6,
                    zoomControl: true,
                });

                // Tuiles OpenStreetMap — gratuit, pas de clé API
                L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                    attribution: '© <a href="https://www.openstreetmap.org">OpenStreetMap</a>',
                    maxZoom: 18,
                }).addTo(map);

                chargerMachinesSurCarte();
                mapInitialized = true;
            }

            /* -- Charger les machines via l'API et placer les markers -- */
            function chargerMachinesSurCarte() {
                API.get('/api/machines/map-data')
                    .then(function(data) {
                        markersData = data || [];

                        // Afficher le compteur
                        const countEl = document.getElementById('mapMachinesCount');
                        if (countEl) {
                            countEl.textContent =
                                markersData.length + ' machine(s) géolocalisée(s)';
                        }

                        remplirFiltreVilles(markersData);
                        placerMarkers(markersData);

                        // Ajuster le zoom pour voir tous les markers
                        if (markersData.length > 0) {
                            const group = L.featureGroup(Object.values(markersMap));
                            map.fitBounds(group.getBounds().pad(0.2));
                        }
                    })
                    .catch(function(err) {
                        console.error('Erreur chargement carte:', err);
                    });
            }

            /* -- Créer et placer les markers -- */
            function placerMarkers(machines) {
                // Supprimer les markers existants
                Object.values(markersMap).forEach(m => map.removeLayer(m));
                markersMap = {};

                machines.forEach(function(machine) {
                    // Icône personnalisée
                    const icone = L.divIcon({
                        html: '<div class="custom-marker"><i class="fas fa-cog"></i></div>',
                        className: '',
                        iconSize: [30, 30],
                        iconAnchor: [15, 30],
                        popupAnchor: [0, -32],
                    });

                    const marker = L.marker(
                        [machine.latitude, machine.longitude], {
                            icon: icone,
                            title: machine.name
                        }
                    );

                    // Contenu HTML du popup
                    const popupHtml = `
                <img src="${machine.image}"
                     alt="${machine.name}"
                     class="map-popup-img"
                     onerror="this.src='${window.location.origin}/images/machine-placeholder.png'">
                <div class="map-popup-body">
                    <div class="map-popup-name">${machine.name}</div>
                    <div class="map-popup-cat">
                        <i class="fas fa-tag me-1"></i>${machine.category || 'Engins TP'}
                        &nbsp;·&nbsp;
                        <i class="fas fa-map-pin me-1"></i>${machine.city || 'Maroc'}
                    </div>
                    <div class="map-popup-price">
                        <span>${parseFloat(machine.price || 0).toLocaleString('fr-MA')}</span> MAD/jour
                    </div>
                    <a href="${machine.url}" class="map-popup-btn">Voir les détails →</a>
                </div>`;

                    marker.bindPopup(popupHtml, {
                        className: 'rentify-popup',
                        maxWidth: 240,
                        minWidth: 220,
                    });

                    marker.addTo(map);
                    markersMap[machine.id] = marker;
                });
            }

            /* -- Remplir le select des villes -- */
            function remplirFiltreVilles(machines) {
                const select = document.getElementById('mapCityFilter');
                if (!select) return;

                const villes = [...new Set(
                    machines.map(m => m.city).filter(Boolean)
                )].sort();

                villes.forEach(function(ville) {
                    const opt = document.createElement('option');
                    opt.value = ville;
                    opt.textContent = ville;
                    select.appendChild(opt);
                });
            }

            /* -- Filtrer les markers par ville -- */
            window.filterMapByCity = function(ville) {
                const filtrees = ville ?
                    markersData.filter(m => m.city === ville) :
                    markersData;

                placerMarkers(filtrees);

                if (filtrees.length > 0) {
                    const group = L.featureGroup(Object.values(markersMap));
                    map.fitBounds(group.getBounds().pad(0.3));
                }
            };
            window.chargerMachinesSurCarte = chargerMachinesSurCarte;
        })();
        /* ══ Fin Feature 4 ══ */

        /* ── Init — lire les paramètres URL ── */
        const urlP = new URLSearchParams(window.location.search);
        if (urlP.get('type')) filters.type = urlP.get('type');
        if (urlP.get('location')) filters.ville = urlP.get('location');
        applyFilters();
    </script>
@endpush
