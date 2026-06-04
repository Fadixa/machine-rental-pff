@extends('layouts.app')

@section('title', 'Contact — Rentify')

@section('content')

<section class="contact-section">

    {{-- ══ HERO + STATS BAR ══ --}}
    <div class="contact-hero">
        <div class="hero-bg-pattern"></div>

        <div class="hero-content-row">
            <div class="hero-machine-img">
                <img src="/images/img3.png" alt="Machine BTP Rentify" class="machine-float">
                <div class="machine-glow"></div>
            </div>
            <div class="hero-text">
                <span class="badge-support">
                    <i class="fa fa-headset"></i>&nbsp; SUPPORT 24H
                </span>
                <h1>Contactez-nous</h1>
                <p>Notre équipe répond à chaque message<br>en moins de <strong>24 heures.</strong></p>
            </div>
        </div>

        {{-- Stats bar collée en bas du hero --}}
        <div class="stats-bar">
            <div class="stats-inner">
                <div class="stat-item">
                    <span class="stat-num">24h</span>
                    <span class="stat-label">Temps de réponse</span>
                </div>
                <div class="stat-sep"></div>
                <div class="stat-item">
                    <span class="stat-num">6j/7</span>
                    <span class="stat-label">Disponibilité</span>
                </div>
                <div class="stat-sep"></div>
                <div class="stat-item">
                    <span class="stat-num">100%</span>
                    <span class="stat-label">Satisfaction client</span>
                </div>
                <div class="stat-sep"></div>
                <div class="stat-item">
                    <span class="stat-num">FR · AR · EN</span>
                    <span class="stat-label">Langues supportées</span>
                </div>
            </div>
        </div>
    </div>

    {{-- ══ MAIN CARD ══ --}}
    <div class="container">
        <div class="contact-card">

            {{-- ── Colonne gauche : infos ──────────────────── --}}
            <div class="contact-info">

                <div class="info-brand">
                    <img src="/images/logo.jpeg" alt="Rentify" class="info-logo">
                    <div>
                        <h2>On est là pour vous</h2>
                        <p class="info-tagline">Une question sur une réservation,<br>une machine ou votre compte ?</p>
                    </div>
                </div>

                <ul class="info-list">
                    <li>
                        <div class="info-icon"><i class="fa fa-envelope"></i></div>
                        <div>
                            <span class="info-label">Email</span>
                            <span class="info-val">support@rentify.ma</span>
                        </div>
                    </li>
                    <li>
                        <div class="info-icon"><i class="fa fa-phone"></i></div>
                        <div>
                            <span class="info-label">Téléphone</span>
                            <span class="info-val">+212 600 000 000</span>
                        </div>
                    </li>
                    <li>
                        <div class="info-icon"><i class="fa fa-map-marker-alt"></i></div>
                        <div>
                            <span class="info-label">Adresse</span>
                            <span class="info-val">Casablanca, Maroc</span>
                        </div>
                    </li>
                </ul>

                <div class="horaires-block">
                    <div class="horaires-title"><i class="fa fa-clock me-2"></i>Horaires d'ouverture</div>
                    <div class="horaires-row">
                        <span>Lun – Ven</span><span>9h00 – 18h00</span>
                    </div>
                    <div class="horaires-row">
                        <span>Samedi</span><span>9h00 – 13h00</span>
                    </div>
                    <div class="horaires-row dimanche">
                        <span>Dimanche</span><span>Fermé</span>
                    </div>
                </div>

                {{-- Machine décorative --}}
                <div class="info-machine-deco">
                    <img src="/images/img4.png" alt="" class="deco-machine" aria-hidden="true">
                    <div class="deco-label">Fleet Rentify — +50 machines BTP</div>
                </div>

            </div>

            {{-- ── Colonne droite : formulaire ──────────────── --}}
            <div class="contact-form-wrap">

                {{-- ✅ Bloc succès --}}
                <div id="alert-success" class="success-block" style="display:none;">
                    <div class="success-icon-wrap">
                        <div class="success-icon"><i class="fa fa-check"></i></div>
                        <div class="success-rings"></div>
                    </div>
                    <h3>Message envoyé !</h3>
                    <p>Merci de nous avoir contactés.<br>
                       Notre équipe vous répondra sous <strong>24h</strong>.</p>
                    <button onclick="resetContact()" class="btn-retry">
                        <i class="fa fa-arrow-left me-2"></i>Envoyer un autre message
                    </button>
                </div>

                {{-- Alerte erreur --}}
                <div id="alert-error" class="alert-error-custom" style="display:none;">
                    <i class="fa fa-exclamation-circle"></i>
                    <span id="error-text">Une erreur est survenue.</span>
                </div>

                {{-- ── Formulaire ── --}}
                <div id="contact-form">

                    <div class="form-header">
                        <h3>Envoyez-nous un message</h3>
                        <p>Remplissez le formulaire ci-dessous et nous vous répondrons rapidement.</p>
                    </div>

                    <div class="form-row-2">
                        <div class="form-group-custom">
                            <label for="nom">
                                <i class="fa fa-user me-1"></i>
                                Nom Complet <span class="required">*</span>
                            </label>
                            <input type="text" id="nom" placeholder="Ex : Youssef El Mansouri"
                                   class="input-custom" required>
                        </div>
                        <div class="form-group-custom">
                            <label for="email">
                                <i class="fa fa-envelope me-1"></i>
                                Adresse Email <span class="required">*</span>
                            </label>
                            <input type="email" id="email" placeholder="vous@exemple.com"
                                   class="input-custom" required>
                        </div>
                    </div>

                    <div class="form-row-2">
                        <div class="form-group-custom">
                            <label for="tel">
                                <i class="fa fa-phone me-1"></i>
                                Téléphone <span class="optional">(optionnel)</span>
                            </label>
                            <input type="tel" id="tel" placeholder="+212 6XX XXX XXX"
                                   class="input-custom">
                        </div>
                        <div class="form-group-custom">
                            <label for="sujet">
                                <i class="fa fa-tag me-1"></i>
                                Sujet <span class="required">*</span>
                            </label>
                            <select id="sujet" class="input-custom" required>
                                <option value="">-- Choisissez un sujet --</option>
                                <option value="reservation">🗓 Question sur une réservation</option>
                                <option value="machine">🚜 Problème avec une machine</option>
                                <option value="paiement">💳 Question de paiement</option>
                                <option value="compte">👤 Problème de compte</option>
                                <option value="autre">💬 Autre</option>
                            </select>
                        </div>
                    </div>

                    <div class="form-group-custom">
                        <label for="message">
                            <i class="fa fa-comment-alt me-1"></i>
                            Message <span class="required">*</span>
                        </label>
                        <textarea id="message" rows="5"
                                  placeholder="Décrivez votre demande en détail..."
                                  class="input-custom" required></textarea>
                        <span id="char-count" class="char-count">0 / 500</span>
                    </div>

                    {{-- Quick topics --}}
                    <div class="quick-topics">
                        <span class="qt-label">Sujets fréquents :</span>
                        <button type="button" class="qt-chip" onclick="quickFill('reservation','Comment annuler ma réservation ?')">Annulation</button>
                        <button type="button" class="qt-chip" onclick="quickFill('paiement','J\'ai une question concernant mon paiement.')">Paiement</button>
                        <button type="button" class="qt-chip" onclick="quickFill('machine','Je rencontre un problème avec une machine.')">Machine</button>
                        <button type="button" class="qt-chip" onclick="quickFill('compte','Je n\'arrive pas à accéder à mon compte.')">Compte</button>
                    </div>

                    <button id="btn-envoyer" class="btn-envoyer" onclick="envoyerContact()">
                        <span id="btn-text">
                            <i class="fa fa-paper-plane me-2"></i>Envoyer le message
                        </span>
                        <span id="btn-loading" style="display:none;">
                            <i class="fa fa-spinner fa-spin me-2"></i>Envoi en cours...
                        </span>
                    </button>

                    <p class="form-note">
                        <i class="fa fa-lock me-1"></i>
                        Vos données sont protégées et ne seront jamais partagées.
                    </p>
                </div>
            </div>

        </div>
    </div>

</section>

{{-- ═══════════════════════════════════════════════════════════ --}}
{{-- STYLES                                                      --}}
{{-- ═══════════════════════════════════════════════════════════ --}}
<style>
/* ── Variables ── */
:root {
    --gold: #D4AF37;
    --gold-dk: #9A7D20;
    --gold-lt: #F5E88A;
    --gold-pale: #FEF9E7;
    --gold-glow: rgba(212,175,55,.25);
    /* Lon ghamq dyal info column o button */
    --gold-deep: #C9A227;
    --gold-deep-dk: #A8871F;
    --cream: #FAF7F0;
    --cream2: #F0EBE0;
    --cream3: #E8DDD0;
    --navy: #0F1B2D;
    --txt-dark: #1a1a2e;
    --txt-mid: #5a5660;
    --txt-light: #9992a4;
}

/* ── Section globale ── */
.contact-section {
    background: var(--cream);
    min-height: 100vh;
    padding-bottom: 100px;
}

/* ══ HERO ══════════════════════════════════════ */
.contact-hero {
    position: relative;
    background: #fff;
    border-bottom: 1.5px solid rgba(212,175,55,.2);
    overflow: hidden;
    padding: 60px 0 0;
    display: flex;
    align-items: flex-end;
    justify-content: center;
    gap: 60px;
    min-height: 280px;
}
.hero-bg-pattern {
    position: absolute;
    inset: 0;
    background-image:
        radial-gradient(circle at 15% 50%, rgba(212,175,55,.08) 0%, transparent 60%),
        radial-gradient(circle at 85% 20%, rgba(212,175,55,.06) 0%, transparent 50%);
    pointer-events: none;
}
.hero-text {
    position: relative;
    z-index: 2;
    text-align: left;
    padding-bottom: 50px;
}
.badge-support {
    display: inline-flex;
    align-items: center;
    gap: 6px;
    background: var(--gold-pale);
    color: var(--gold-dk);
    border: 1.5px solid var(--gold);
    padding: 5px 16px;
    border-radius: 99px;
    font-size: 0.75rem;
    font-weight: 700;
    letter-spacing: 1.2px;
    margin-bottom: 18px;
}
.hero-text h1 {
    font-family: 'Playfair Display', serif;
    font-size: 3rem;
    font-weight: 800;
    color: var(--navy);
    margin: 0 0 12px;
    line-height: 1.1;
}
.hero-text p {
    color: var(--txt-mid);
    font-size: 1rem;
    line-height: 1.7;
    margin: 0;
}
.hero-text p strong { color: var(--gold-dk); }

/* Machine image flottante */
.hero-machine-img {
    position: relative;
    z-index: 2;
    align-self: flex-end;
}
.machine-float {
    width: 380px;
    max-width: 100%;
    display: block;
    object-fit: contain;
    filter: drop-shadow(0 20px 40px rgba(212,175,55,.2));
    animation: machineFloat 4s ease-in-out infinite;
}
.machine-glow {
    position: absolute;
    bottom: 0;
    left: 50%;
    transform: translateX(-50%);
    width: 280px;
    height: 30px;
    background: radial-gradient(ellipse, rgba(212,175,55,.3) 0%, transparent 70%);
    border-radius: 50%;
    filter: blur(8px);
    animation: glowPulse 4s ease-in-out infinite;
}
@keyframes machineFloat {
    0%, 100% { transform: translateY(0); }
    50%       { transform: translateY(-12px); }
}
@keyframes glowPulse {
    0%, 100% { opacity: .6; transform: translateX(-50%) scaleX(1); }
    50%       { opacity: 1;  transform: translateX(-50%) scaleX(1.15); }
}

/* ══ STATS BAR ══════════════════════════════════ */
.stats-bar {
    background: var(--gold-pale);
    border-bottom: 1.5px solid var(--gold);
}
.stats-inner {
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 0;
    padding: 18px 0;
    flex-wrap: wrap;
}
.stat-item {
    display: flex;
    flex-direction: column;
    align-items: center;
    padding: 0 40px;
}
.stat-num {
    font-family: 'Playfair Display', serif;
    font-size: 1.4rem;
    font-weight: 700;
    color: var(--gold-dk);
    line-height: 1;
}
.stat-label {
    font-size: 0.72rem;
    color: var(--txt-mid);
    font-weight: 500;
    letter-spacing: 0.5px;
    margin-top: 3px;
}
.stat-sep {
    width: 1px;
    height: 36px;
    background: rgba(212,175,55,.35);
}

/* ══ MAIN CARD ════════════════════════════════════ */
.container { max-width: 1050px; margin: 0 auto; padding: 0 24px; }

.contact-card {
    display: grid;
    grid-template-columns: 340px 1fr;
    border-radius: 20px;
    overflow: hidden;
    box-shadow: 0 24px 80px rgba(15,27,45,.1), 0 0 0 1.5px rgba(212,175,55,.25);
    margin-top: 56px;
    background: #fff;
}

/* ── Colonne infos — lon ghamq #C9A227 ─ */
.contact-info {
    background: #C9A227;
    padding: 44px 36px;
    display: flex;
    flex-direction: column;
    gap: 28px;
}

.info-brand {
    display: flex;
    align-items: flex-start;
    gap: 14px;
}
.info-logo {
    width: 44px;
    height: 44px;
    border-radius: 10px;
    object-fit: cover;
    border: 2px solid rgba(255,255,255,.4);
    flex-shrink: 0;
}
.info-brand h2 {
    font-family: 'Playfair Display', serif;
    font-size: 1.25rem;
    font-weight: 700;
    color: var(--navy);
    margin: 0 0 4px;
}
.info-tagline {
    font-size: 0.82rem;
    color: var(--navy);
    opacity: .75;
    line-height: 1.5;
    margin: 0;
}

/* Liste infos */
.info-list {
    list-style: none;
    padding: 0;
    margin: 0;
    display: flex;
    flex-direction: column;
    gap: 16px;
}
.info-list li {
    display: flex;
    align-items: center;
    gap: 14px;
}
.info-icon {
    width: 38px;
    height: 38px;
    background: rgba(15,27,45,.12);
    border: 1.5px solid rgba(15,27,45,.15);
    border-radius: 10px;
    display: flex;
    align-items: center;
    justify-content: center;
    color: var(--navy);
    font-size: 0.85rem;
    flex-shrink: 0;
}
.info-label {
    display: block;
    font-size: 0.68rem;
    font-weight: 700;
    text-transform: uppercase;
    letter-spacing: 0.8px;
    color: rgba(15,27,45,.55);
    margin-bottom: 1px;
}
.info-val {
    display: block;
    font-size: 0.88rem;
    font-weight: 600;
    color: var(--navy);
}

/* Horaires */
.horaires-block {
    background: rgba(15,27,45,.08);
    border: 1px solid rgba(15,27,45,.12);
    border-radius: 12px;
    padding: 16px 18px;
}
.horaires-title {
    font-size: 0.75rem;
    font-weight: 700;
    text-transform: uppercase;
    letter-spacing: 0.8px;
    color: var(--navy);
    margin-bottom: 12px;
    opacity: .7;
}
.horaires-row {
    display: flex;
    justify-content: space-between;
    font-size: 0.82rem;
    color: var(--navy);
    padding: 4px 0;
    border-bottom: 1px dashed rgba(15,27,45,.1);
}
.horaires-row:last-child { border-bottom: none; }
.horaires-row span:last-child { font-weight: 600; }
.horaires-row.dimanche span:last-child { color: #b91c1c; }

/* Machine déco bas */
.info-machine-deco {
    margin-top: auto;
    padding-top: 10px;
}
.deco-machine {
    width: 100%;
    height: 110px;
    object-fit: contain;
    filter: drop-shadow(0 8px 20px rgba(15,27,45,.2));
    display: block;
}
.deco-label {
    text-align: center;
    font-size: 0.7rem;
    font-weight: 600;
    color: rgba(15,27,45,.55);
    letter-spacing: 0.5px;
    margin-top: 6px;
}

/* ── Colonne formulaire ─ */
.contact-form-wrap {
    background: #fff;
    padding: 44px 48px;
}
.form-header {
    margin-bottom: 28px;
    padding-bottom: 22px;
    border-bottom: 1.5px solid var(--cream2);
}
.form-header h3 {
    font-family: 'Playfair Display', serif;
    font-size: 1.45rem;
    font-weight: 700;
    color: var(--navy);
    margin: 0 0 6px;
}
.form-header p {
    font-size: 0.87rem;
    color: var(--txt-mid);
    margin: 0;
}

/* Groupes */
.form-row-2 {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 16px;
}
.form-group-custom {
    margin-bottom: 18px;
    position: relative;
}
.form-group-custom label {
    display: block;
    font-size: 0.82rem;
    font-weight: 600;
    color: var(--txt-dark);
    margin-bottom: 6px;
}
.form-group-custom label i { color: var(--gold-dk); }
.required { color: var(--gold-dk); }
.optional  { color: var(--txt-light); font-weight: 400; font-size: 0.78rem; }

/* Inputs */
.input-custom {
    width: 100%;
    padding: 10px 14px;
    border: 1.5px solid var(--cream3);
    border-radius: 10px;
    font-size: 0.88rem;
    color: var(--txt-dark);
    background: var(--cream);
    transition: border-color .2s, box-shadow .2s, background .2s;
    outline: none;
    font-family: inherit;
    box-sizing: border-box;
}
.input-custom:focus {
    border-color: var(--gold);
    box-shadow: 0 0 0 3px rgba(212,175,55,.12);
    background: #fff;
}
textarea.input-custom { resize: vertical; min-height: 120px; }
select.input-custom    { cursor: pointer; }

/* Compteur */
.char-count {
    position: absolute;
    bottom: 8px; right: 12px;
    font-size: 0.7rem;
    color: var(--txt-light);
}

/* Quick topics */
.quick-topics {
    display: flex;
    flex-wrap: wrap;
    align-items: center;
    gap: 8px;
    margin-bottom: 20px;
}
.qt-label {
    font-size: 0.75rem;
    font-weight: 600;
    color: var(--txt-mid);
    white-space: nowrap;
}
.qt-chip {
    padding: 5px 14px;
    border: 1.5px solid rgba(212,175,55,.4);
    background: var(--gold-pale);
    color: var(--gold-dk);
    border-radius: 99px;
    font-size: 0.75rem;
    font-weight: 600;
    cursor: pointer;
    transition: background .15s, border-color .15s, color .15s;
    font-family: inherit;
}
.qt-chip:hover {
    background: #C9A227;
    border-color: #C9A227;
    color: var(--navy);
}

/* Bouton principal — lon ghamq #C9A227 */
.btn-envoyer {
    width: 100%;
    padding: 13px;
    background: #C9A227;
    color: var(--txt-dark);
    border: none;
    border-radius: 12px;
    font-size: 0.95rem;
    font-weight: 700;
    cursor: pointer;
    transition: transform .15s, box-shadow .15s, background .15s;
    font-family: inherit;
    margin-top: 4px;
}
.btn-envoyer:hover {
    background: #A8871F;
    color: #fff;
    transform: translateY(-2px);
    box-shadow: 0 8px 28px rgba(201,162,39,.4);
}
.btn-envoyer:active  { transform: scale(0.98); }
.btn-envoyer:disabled { opacity: .6; cursor: not-allowed; transform: none; }

/* Note confidentialité */
.form-note {
    text-align: center;
    font-size: 0.73rem;
    color: var(--txt-light);
    margin: 12px 0 0;
}
.form-note i { color: var(--gold-dk); }

/* ── Bloc succès ── */
.success-block {
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    text-align: center;
    padding: 60px 30px;
    gap: 18px;
    min-height: 360px;
}
.success-icon-wrap { position: relative; }
.success-rings {
    position: absolute;
    inset: -16px;
    border-radius: 50%;
    border: 2px solid rgba(16,185,129,.25);
    animation: ringPulse 2s ease infinite;
}
.success-rings::after {
    content: '';
    position: absolute;
    inset: -10px;
    border-radius: 50%;
    border: 1.5px solid rgba(16,185,129,.12);
    animation: ringPulse 2s ease .4s infinite;
}
@keyframes ringPulse {
    0%   { transform: scale(1); opacity: 1; }
    100% { transform: scale(1.4); opacity: 0; }
}
.success-icon {
    width: 80px; height: 80px;
    border-radius: 50%;
    background: linear-gradient(135deg, #10b981, #34d399);
    display: flex; align-items: center; justify-content: center;
    font-size: 2rem;
    color: #fff;
    animation: popIn .45s cubic-bezier(.175,.885,.32,1.275);
    position: relative; z-index: 1;
}
@keyframes popIn {
    from { transform: scale(0); opacity: 0; }
    to   { transform: scale(1); opacity: 1; }
}
.success-block h3 {
    font-family: 'Playfair Display', serif;
    font-size: 1.7rem; font-weight: 800;
    color: var(--navy); margin: 0;
}
.success-block p {
    color: var(--txt-mid); font-size: .95rem;
    line-height: 1.7; margin: 0;
}
.btn-retry {
    padding: 10px 26px;
    background: transparent;
    border: 2px solid #C9A227;
    color: #A8871F;
    border-radius: 10px;
    font-weight: 600; font-size: .88rem;
    cursor: pointer; transition: all .2s;
    font-family: inherit;
}
.btn-retry:hover { background: #C9A227; color: var(--navy); }

/* Alerte erreur */
.alert-error-custom {
    padding: 12px 16px;
    border-radius: 10px;
    font-size: .87rem;
    margin-bottom: 18px;
    display: flex;
    align-items: center;
    gap: 10px;
    background: #fef2f2;
    border: 1.5px solid #fca5a5;
    color: #991b1b;
}

/* ══ RESPONSIVE ══ */
@media (max-width: 900px) {
    .contact-card { grid-template-columns: 1fr; }
    .contact-hero { flex-direction: column; align-items: center; padding: 50px 24px 0; gap: 20px; }
    .hero-text { text-align: center; padding-bottom: 30px; }
    .hero-text h1 { font-size: 2.2rem; }
    .machine-float { width: 260px; }
    .contact-form-wrap { padding: 36px 28px; }
    .contact-info { padding: 36px 28px; }
    .form-row-2 { grid-template-columns: 1fr; }
    .stat-item { padding: 0 20px; }
}
@media (max-width: 500px) {
    .stats-inner { gap: 12px; }
    .stat-sep { display: none; }
}
</style>

{{-- ═══════════════════════════════════════════════════════════ --}}
{{-- SCRIPTS                                                     --}}
{{-- ═══════════════════════════════════════════════════════════ --}}
<script>
/* Compteur caractères */
document.getElementById('message').addEventListener('input', function () {
    var len = this.value.length;
    var counter = document.getElementById('char-count');
    counter.textContent = len + ' / 500';
    counter.style.color = len > 450 ? '#ef4444' : '#9992a4';
    if (len > 500) this.value = this.value.substring(0, 500);
});

/* Remplissage rapide (quick topics) */
function quickFill(sujet, message) {
    document.getElementById('sujet').value = sujet;
    document.getElementById('message').value = message;
    var len = message.length;
    document.getElementById('char-count').textContent = len + ' / 500';
    document.getElementById('message').focus();
}

/* Validation */
function validerChamps() {
    var nom   = document.getElementById('nom').value.trim();
    var email = document.getElementById('email').value.trim();
    var sujet = document.getElementById('sujet').value;
    var msg   = document.getElementById('message').value.trim();
    var regex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
    if (!nom)               return 'Veuillez entrer votre nom complet.';
    if (!email)             return 'Veuillez entrer votre adresse email.';
    if (!regex.test(email)) return 'Adresse email invalide.';
    if (!sujet)             return 'Veuillez choisir un sujet.';
    if (msg.length < 10)    return 'Le message doit contenir au moins 10 caractères.';
    return null;
}

/* Envoi */
function envoyerContact() {
    document.getElementById('alert-success').style.display = 'none';
    document.getElementById('alert-error').style.display   = 'none';

    var erreur = validerChamps();
    if (erreur) {
        document.getElementById('error-text').textContent    = erreur;
        document.getElementById('alert-error').style.display = 'flex';
        return;
    }

    var btn = document.getElementById('btn-envoyer');
    btn.disabled = true;
    document.getElementById('btn-text').style.display    = 'none';
    document.getElementById('btn-loading').style.display = 'inline';

    /* Simulation — remplacer par fetch() si route Laravel disponible */
    setTimeout(function () {
        document.getElementById('contact-form').style.display = 'none';
        document.getElementById('alert-success').style.display = 'flex';
        document.querySelector('.contact-form-wrap')
                .scrollIntoView({ behavior: 'smooth', block: 'start' });
    }, 1800);
}

/* Reset */
function resetContact() {
    document.getElementById('alert-success').style.display = 'none';
    document.getElementById('contact-form').style.display  = 'block';
    ['nom','email','tel','message'].forEach(function(id) {
        document.getElementById(id).value = '';
    });
    document.getElementById('sujet').value             = '';
    document.getElementById('char-count').textContent  = '0 / 500';
    var btn = document.getElementById('btn-envoyer');
    btn.disabled = false;
    document.getElementById('btn-text').style.display    = 'inline';
    document.getElementById('btn-loading').style.display = 'none';
}
</script>

@endsection