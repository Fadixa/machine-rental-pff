@extends('layouts.app')
@section('title', 'Mon espace — Rentify')

@push('styles')
<style>
.dash-wrap {
    max-width: 1280px; margin: 0 auto; padding: 28px 32px;
    display: grid; grid-template-columns: 240px 1fr; gap: 24px; align-items: start;
}
.dash-sidebar {
    background: #fff; border: 1px solid #F0F0F0;
    border-radius: var(--radius-lg); overflow: hidden;
    position: sticky; top: 90px;
}
.dash-user-block {
    background: var(--navy); padding: 20px 18px;
    display: flex; align-items: center; gap: 12px;
}
.dash-avatar {
    width: 44px; height: 44px; background: var(--orange);
    border-radius: 50%; display: flex; align-items: center; justify-content: center;
    font-size: 18px; font-weight: 800; color: #111; flex-shrink: 0;
}
.dash-user-name { color: #fff; font-size: 14px; font-weight: 700; }
.dash-user-role {
    display: inline-flex; align-items: center; gap: 4px;
    background: rgba(245,158,11,.2); color: var(--orange);
    font-size: 10px; font-weight: 700; padding: 2px 8px;
    border-radius: 100px; margin-top: 3px;
}
.dash-nav { padding: 10px 0; }
.dash-nav-item {
    display: flex; align-items: center; gap: 10px;
    padding: 10px 18px; font-size: 13px; font-weight: 500;
    color: var(--text-gray); cursor: pointer; transition: all .15s;
    border-left: 3px solid transparent; text-decoration: none;
}
.dash-nav-item:hover { background: #F9FAFB; color: var(--navy); }
.dash-nav-item.active {
    background: rgba(245,158,11,.06); color: var(--orange);
    border-left-color: var(--orange); font-weight: 700;
}
.dash-nav-item i { width: 16px; text-align: center; font-size: 13px; }
.dash-nav-sep { height: 1px; background: #F5F5F5; margin: 6px 0; }
.btn-logout {
    display: flex; align-items: center; gap: 10px;
    padding: 10px 18px; font-size: 13px; font-weight: 500;
    color: #EF4444; cursor: pointer; width: 100%; border: none;
    background: none; text-align: left; transition: background .15s;
}
.btn-logout:hover { background: #FEF2F2; }
.dash-main { }
.dash-header { margin-bottom: 22px; }
.dash-title { font-size: 22px; font-weight: 900; color: var(--navy); letter-spacing: -.4px; }
.dash-subtitle { font-size: 13px; color: var(--text-gray); margin-top: 3px; }
.kpi-grid { display: grid; grid-template-columns: repeat(3,1fr); gap: 14px; margin-bottom: 22px; }
.kpi-card {
    background: #fff; border: 1px solid #F0F0F0; border-radius: var(--radius-lg);
    padding: 18px 18px; transition: box-shadow .2s;
}
.kpi-card:hover { box-shadow: var(--shadow-md); }
.kpi-label {
    font-size: 11px; font-weight: 700; color: var(--text-light);
    text-transform: uppercase; letter-spacing: .8px; margin-bottom: 8px;
    display: flex; align-items: center; gap: 6px;
}
.kpi-label i { color: var(--orange); }
.kpi-value { font-size: 28px; font-weight: 900; color: var(--navy); letter-spacing: -1px; }
.kpi-sub { font-size: 11px; color: var(--text-light); margin-top: 2px; }
.dash-panel {
    background: #fff; border: 1px solid #F0F0F0;
    border-radius: var(--radius-lg); overflow: hidden; margin-bottom: 20px;
}
.panel-header {
    display: flex; align-items: center; justify-content: space-between;
    padding: 16px 20px; border-bottom: 1px solid #F5F5F5;
}
.panel-title { font-size: 15px; font-weight: 800; color: var(--navy); }
.panel-badge {
    font-size: 11px; font-weight: 700; padding: 3px 10px;
    border-radius: 100px; background: rgba(245,158,11,.12); color: var(--orange);
}
.res-table { width: 100%; border-collapse: collapse; }
.res-table th {
    text-align: left; padding: 10px 20px;
    font-size: 10px; font-weight: 800; color: var(--text-light);
    letter-spacing: 1px; text-transform: uppercase;
    background: #FAFAFA; border-bottom: 1px solid #F5F5F5;
}
.res-table td {
    padding: 14px 20px; border-bottom: 1px solid #F9F9F9;
    font-size: 13px; color: var(--navy); vertical-align: middle;
}
.res-table tr:last-child td { border-bottom: none; }
.res-table tr:hover td { background: #FAFAFA; }
.stat-badge {
    display: inline-flex; align-items: center; gap: 5px;
    font-size: 11px; font-weight: 700; padding: 3px 10px; border-radius: 100px;
}
.sb-pending   { background: #FEF3C7; color: #92400E; }
.sb-accepted  { background: #D1FAE5; color: #065F46; }
.sb-rejected  { background: #FEE2E2; color: #991B1B; }
.sb-completed { background: #EDE9FE; color: #5B21B6; }
.sb-default   { background: #F3F4F6; color: #6B7280; }
.machine-name-cell { font-weight: 700; color: var(--navy); }
.machine-type-cell { font-size: 11px; color: var(--text-light); }
.empty-state { padding: 48px 20px; text-align: center; color: var(--text-light); }
.empty-state-icon  { font-size: 40px; margin-bottom: 10px; opacity: .5; }
.empty-state-title { font-size: 15px; font-weight: 700; color: var(--navy); margin-bottom: 5px; }
.empty-state-sub   { font-size: 13px; }
.btn-contrat {
    background: linear-gradient(135deg, #F59E0B, #d97706);
    color: #fff; border: none; font-weight: 600; font-size: 0.78rem;
    border-radius: 6px; transition: all 0.2s ease;
    box-shadow: 0 2px 8px rgba(245,158,11,0.35);
}
.btn-contrat:hover:not(:disabled) {
    transform: translateY(-2px);
    box-shadow: 0 4px 14px rgba(245,158,11,0.5); color: #fff;
}
.btn-contrat:disabled { opacity: 0.65; cursor: not-allowed; }
.btn-contrat .spinner-border { width: 0.75rem; height: 0.75rem; border-width: 0.1em; }

/* ── Feature D — Bouton WhatsApp réservations ── */
.btn-wa-res {
    display: inline-flex; align-items: center; gap: 4px;
    background: #25D366; color: #fff; border: none;
    border-radius: 6px; padding: 5px 10px;
    font-size: 0.78rem; font-weight: 600; text-decoration: none;
    transition: background .2s; white-space: nowrap; cursor: pointer;
}
.btn-wa-res:hover { background: #1ebe5d; color: #fff; }
.btn-wa-res i { font-size: 0.95rem; }

@media (max-width: 900px) {
    .dash-wrap { grid-template-columns: 1fr; }
    .dash-sidebar { position: static; }
    .kpi-grid { grid-template-columns: 1fr 1fr; }
}
@media (max-width: 600px) {
    .dash-wrap { padding: 16px; }
    .kpi-grid { grid-template-columns: 1fr; }
}
</style>
@endpush

@section('content')
<div class="dash-wrap" id="dash-root">
    <div style="grid-column:1/-1;text-align:center;padding:60px;color:var(--text-light)">
        <div style="font-size:32px;animation:spin 1s linear infinite;display:inline-block">⚙️</div>
        <p style="margin-top:12px">Chargement...</p>
    </div>
</div>
@endsection

@push('scripts')
<script>
(function () {

    if (!getToken() || !getUser()) {
        localStorage.removeItem('auth_token');
        localStorage.removeItem('auth_user');
        window.location.replace('/login');
        return;
    }
    const user = getUser();
    if (user?.role === 'owner') {
        window.location.replace('/dashboard/owner');
        return;
    }

    async function loadClientDash() {
        const root      = document.getElementById('dash-root');
        const initial   = (user?.name || 'C').charAt(0).toUpperCase();
        const firstName = (user?.name || 'Client').split(' ')[0];

        let reservations = [];
        try {
            const d      = await API.get('/api/reservations');
            reservations = d?.data || (Array.isArray(d) ? d : []);
        } catch (e) {
            reservations = getDemoReservations();
        }

        const total   = Array.isArray(reservations) ? reservations.length : 0;
        const active  = Array.isArray(reservations) ? reservations.filter(r => r.status === 'accepted').length  : 0;
        const pending = Array.isArray(reservations) ? reservations.filter(r => r.status === 'pending').length   : 0;

        root.innerHTML = `
        <aside class="dash-sidebar">
            <div class="dash-user-block">
                <div class="dash-avatar">${initial}</div>
                <div>
                    <div class="dash-user-name">${user?.name || 'Client'}</div>
                    <div class="dash-user-role">
                        <i class="fas fa-circle" style="font-size:5px"></i> Client
                    </div>
                </div>
            </div>
            <nav class="dash-nav">
                <a href="/dashboard/client" class="dash-nav-item active">
                    <i class="fas fa-th-large"></i> Tableau de bord
                </a>
                <a href="/machines" class="dash-nav-item">
                    <i class="fas fa-search"></i> Chercher un engin
                </a>
                <div class="dash-nav-sep"></div>
                <a href="/profile" class="dash-nav-item">
                    <i class="fas fa-user"></i> Mon profil
                </a>
                <button class="btn-logout" onclick="doLogout()">
                    <i class="fas fa-sign-out-alt"></i> Déconnexion
                </button>
            </nav>
        </aside>

        <main class="dash-main">
            <div class="dash-header">
                <div class="dash-title">Bonjour, ${firstName} 👋</div>
                <div class="dash-subtitle">Voici un résumé de votre activité sur Rentify</div>
            </div>

            <div class="kpi-grid">
                <div class="kpi-card fade-up" data-delay="0">
                    <div class="kpi-label"><i class="fas fa-calendar-check"></i> Réservations</div>
                    <div class="kpi-value">${total}</div>
                    <div class="kpi-sub">au total</div>
                </div>
                <div class="kpi-card fade-up" data-delay="80">
                    <div class="kpi-label"><i class="fas fa-clock"></i> En cours</div>
                    <div class="kpi-value">${active}</div>
                    <div class="kpi-sub">locations actives</div>
                </div>
                <div class="kpi-card fade-up" data-delay="160">
                    <div class="kpi-label"><i class="fas fa-hourglass-half"></i> En attente</div>
                    <div class="kpi-value">${pending}</div>
                    <div class="kpi-sub">réponse propriétaire</div>
                </div>
            </div>

            <div class="dash-panel fade-up" data-delay="200">
                <div class="panel-header">
                    <div class="panel-title">Mes réservations</div>
                    <div class="panel-badge">${total} au total</div>
                </div>
                ${renderReservations(reservations)}
            </div>

            <div style="background:var(--navy);border-radius:var(--radius-lg);padding:24px 28px;
                        display:flex;align-items:center;justify-content:space-between;
                        flex-wrap:wrap;gap:16px"
                 class="fade-up" data-delay="280">
                <div>
                    <div style="font-size:16px;font-weight:800;color:#fff;margin-bottom:4px">
                        Besoin d'un engin ?
                    </div>
                    <div style="font-size:13px;color:rgba(255,255,255,.4)">
                        Parcourez 500+ machines disponibles partout au Maroc
                    </div>
                </div>
                <a href="/machines" class="btn-orange">
                    <i class="fas fa-search"></i> Chercher un engin
                </a>
            </div>
        </main>`;

        document.querySelectorAll('.fade-up').forEach(el => {
            el.style.transitionDelay = (el.dataset.delay || 0) + 'ms';
            setTimeout(() => el.classList.add('visible'), 50);
        });
    }

    // ── Feature D — Construit le lien WhatsApp pour une réservation acceptée ──
    function whatsappResBtn(r) {
        if (r.status !== 'accepted') return '';

        const phone = r.machine?.owner?.phone ?? null;
        if (!phone) return '';

        // Normalisation → +212XXXXXXXXX
        let num = phone.replace(/\s+/g, '').replace(/^0/, '212');
        if (!num.startsWith('+')) num = '+' + num;

        const machineName = r.machine?.name  ?? 'la machine';
        const startDate   = r.start_date     ?? '—';
        const endDate     = r.end_date       ?? '—';

        const msg = encodeURIComponent(
            `Bonjour, ma réservation pour "${machineName}" (du ${startDate} au ${endDate}) a été acceptée sur Rentify. Comment procéder pour le règlement ?`
        );

        return `<a href="https://wa.me/${num.replace('+','')}?text=${msg}"
                   target="_blank" rel="noopener"
                   class="btn-wa-res ms-1"
                   title="Contacter le propriétaire sur WhatsApp">
                    <i class="fab fa-whatsapp"></i> WhatsApp
                </a>`;
    }

    // ── Rendu du tableau des réservations ──
    function renderReservations(reservations) {
        if (!reservations.length) {
            return `<div class="empty-state">
                <div class="empty-state-icon">📋</div>
                <div class="empty-state-title">Aucune réservation</div>
                <div class="empty-state-sub">Vos réservations apparaîtront ici</div>
            </div>`;
        }

        const statusMap = {
            pending:   { label: 'En attente', cls: 'sb-pending'   },
            accepted:  { label: 'Confirmée',  cls: 'sb-accepted'  },
            rejected:  { label: 'Refusée',    cls: 'sb-rejected'  },
            completed: { label: 'Terminée',   cls: 'sb-completed' },
        };

        return `<table class="res-table">
            <thead>
                <tr>
                    <th>Machine</th>
                    <th>Dates</th>
                    <th>Durée</th>
                    <th>Total</th>
                    <th>Statut</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                ${reservations.map(r => {
                    const st = statusMap[r.status] || { label: r.status, cls: 'sb-default' };

                    const btnContrat = r.status === 'accepted'
                        ? `<button class="btn btn-sm btn-contrat ms-1"
                                   onclick="telechargerContrat(${r.id}, this)"
                                   title="Télécharger le contrat PDF">
                               <i class="fas fa-file-pdf me-1"></i>Contrat
                           </button>`
                        : '';

                    return `<tr>
                        <td>
                            <div class="machine-name-cell">${r.machine?.name || '—'}</div>
                            <div class="machine-type-cell">
                                ${r.machine?.type || ''} · ${r.machine?.city || ''}
                            </div>
                        </td>
                        <td>
                            ${r.start_date || '—'}<br>
                            <span style="color:var(--text-light);font-size:11px">
                                → ${r.end_date || '—'}
                            </span>
                        </td>
                        <td>${r.nb_days || '—'} jour(s)</td>
                        <td style="font-weight:800">
                            ${parseInt(r.total_price || 0).toLocaleString('fr')} DH
                        </td>
                        <td><span class="stat-badge ${st.cls}">${st.label}</span></td>
                        <td style="white-space:nowrap">
                            <a href="/machines/${r.machine_id}"
                               class="btn btn-sm btn-outline-secondary"
                               title="Voir la machine">
                                <i class="fas fa-eye"></i>
                            </a>
                            ${btnContrat}
                            ${whatsappResBtn(r)}
                        </td>
                    </tr>`;
                }).join('')}
            </tbody>
        </table>`;
    }

    // ── Données de démonstration (fallback) ──
    function getDemoReservations() {
        return [
            {
                id: 1, machine_id: 1,
                machine: {
                    name: 'JCB 3CX Backhoe Loader', type: 'Excavatrice', city: 'Casablanca',
                    owner: { name: 'Karim', phone: '0612345678' }
                },
                start_date: '2025-05-10', end_date: '2025-05-15',
                nb_days: 5, total_price: 12600, status: 'accepted'
            },
            {
                id: 2, machine_id: 2,
                machine: {
                    name: 'Manitou MT 1840', type: 'Manitou', city: 'Rabat',
                    owner: { name: 'Karim', phone: '0612345678' }
                },
                start_date: '2025-05-20', end_date: '2025-05-22',
                nb_days: 3, total_price: 5985, status: 'pending'
            },
            {
                id: 3, machine_id: 3,
                machine: {
                    name: 'Camion Benne Volvo FH16', type: 'Camion', city: 'Marrakech',
                    owner: { name: 'Karim', phone: null }
                },
                start_date: '2025-04-01', end_date: '2025-04-07',
                nb_days: 7, total_price: 9555, status: 'completed'
            },
        ];
    }

    // ── Déconnexion ──
    window.doLogout = function () {
        localStorage.removeItem('auth_token');
        localStorage.removeItem('auth_user');
        API.post('/api/logout', {}).finally(() => {
            window.location.replace('/');
        });
    };

    // ── Téléchargement contrat PDF ──
    window.telechargerContrat = async function (reservationId, btn) {
        const labelOriginal = btn.innerHTML;
        btn.disabled  = true;
        btn.innerHTML = `<span class="spinner-border spinner-border-sm me-1" role="status"></span>Génération…`;

        try {
            const token    = localStorage.getItem('auth_token');
            const response = await fetch(`/api/reservations/${reservationId}/contrat`, {
                method:  'GET',
                headers: { 'Authorization': `Bearer ${token}`, 'Accept': 'application/pdf' }
            });

            if (!response.ok) {
                const erreur = await response.json().catch(() => ({}));
                throw new Error(erreur.message || `Erreur ${response.status}`);
            }

            const blob        = await response.blob();
            const url         = URL.createObjectURL(blob);
            const lien        = document.createElement('a');
            const disposition = response.headers.get('Content-Disposition') || '';
            const match       = disposition.match(/filename[^;=\n]*=(?:(['"])(.+?)\1|([^;\n]*))/i);
            lien.download     = match ? (match[2] || match[3]) : `contrat-RENTIFY-${reservationId}.pdf`;
            lien.href = url;
            document.body.appendChild(lien);
            lien.click();
            setTimeout(() => { URL.revokeObjectURL(url); document.body.removeChild(lien); }, 200);
            showFlash('Contrat téléchargé avec succès !', 'success');

        } catch (erreur) {
            console.error('[Rentify] Erreur contrat :', erreur);
            showFlash(erreur.message || 'Impossible de générer le contrat.', 'error');
        } finally {
            btn.disabled  = false;
            btn.innerHTML = labelOriginal;
        }
    };

    loadClientDash();

})();
</script>
@endpush