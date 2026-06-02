@extends('layouts.app')

@section('title', 'Contact — Rentify')

@section('content')

<section class="contact-section">
    <div class="container">

        {{-- Titre de la page --}}
        <div class="contact-header text-center mb-5">
            <span class="badge-pill">📬 SUPPORT</span>
            <h1 class="contact-title">Contactez-nous</h1>
            <p class="contact-subtitle">
                Une question ? Notre équipe est là pour vous aider sous 24h.
            </p>
        </div>

        <div class="contact-card">

            {{-- ── Colonne gauche : infos --}}
            <div class="contact-info">
                <h2>On est là pour vous</h2>
                <p>Une question ? Notre équipe est là<br>pour vous aider.</p>

                <ul class="info-list">
                    <li>
                        <i class="fa fa-envelope"></i>
                        <span>support@rentify.ma</span>
                    </li>
                    <li>
                        <i class="fa fa-phone"></i>
                        <span>+212 600 000 000</span>
                    </li>
                    <li>
                        <i class="fa fa-map-marker-alt"></i>
                        <span>Casablanca, Maroc</span>
                    </li>
                </ul>

                {{-- Horaires --}}
                <div class="horaires">
                    <p><strong>Lun – Ven :</strong> 9h00 – 18h00</p>
                    <p><strong>Sam :</strong> 9h00 – 13h00</p>
                </div>
            </div>

            {{-- ── Colonne droite : formulaire --}}
            <div class="contact-form-wrap">

                {{-- ✅ Message succès — remplace le formulaire après envoi --}}
                <div id="alert-success" class="success-block" style="display:none;">
                    <div class="success-icon">
                        <i class="fa fa-check"></i>
                    </div>
                    <h3>Message envoyé !</h3>
                    <p>Merci de nous avoir contactés.<br>
                       Notre équipe vous répondra sous <strong>24h</strong>.</p>
                    <button onclick="resetContact()" class="btn-retry">
                        <i class="fa fa-arrow-left me-2"></i>Envoyer un autre message
                    </button>
                </div>

                {{-- Message erreur --}}
                <div id="alert-error" class="alert-error-custom" style="display:none;">
                    <i class="fa fa-exclamation-circle"></i>
                    <span id="error-text">Une erreur est survenue.</span>
                </div>

                {{-- Formulaire (disparaît après envoi réussi) --}}
                <div id="contact-form">

                    {{-- Nom Complet --}}
                    <div class="form-group-custom">
                        <label for="nom">Nom Complet <span class="required">*</span></label>
                        <input type="text" id="nom" placeholder="Ex : Youssef El Mansouri"
                               class="input-custom" required>
                    </div>

                    {{-- Email --}}
                    <div class="form-group-custom">
                        <label for="email">Adresse Email <span class="required">*</span></label>
                        <input type="email" id="email" placeholder="vous@exemple.com"
                               class="input-custom" required>
                    </div>

                    {{-- Téléphone (optionnel) --}}
                    <div class="form-group-custom">
                        <label for="tel">Téléphone <span class="optional">(optionnel)</span></label>
                        <input type="tel" id="tel" placeholder="+212 6XX XXX XXX"
                               class="input-custom">
                    </div>

                    {{-- Sujet --}}
                    <div class="form-group-custom">
                        <label for="sujet">Sujet <span class="required">*</span></label>
                        <select id="sujet" class="input-custom" required>
                            <option value="">-- Choisissez un sujet --</option>
                            <option value="reservation">Question sur une réservation</option>
                            <option value="machine">Problème avec une machine</option>
                            <option value="paiement">Question de paiement</option>
                            <option value="compte">Problème de compte</option>
                            <option value="autre">Autre</option>
                        </select>
                    </div>

                    {{-- Message --}}
                    <div class="form-group-custom">
                        <label for="message">Message <span class="required">*</span></label>
                        <textarea id="message" rows="5"
                                  placeholder="Décrivez votre demande en détail..."
                                  class="input-custom" required></textarea>
                        <span id="char-count" class="char-count">0 / 500</span>
                    </div>

                    {{-- Bouton Envoyer --}}
                    <button id="btn-envoyer" class="btn-envoyer" onclick="envoyerContact()">
                        <span id="btn-text">
                            <i class="fa fa-paper-plane me-2"></i>Envoyer le message
                        </span>
                        <span id="btn-loading" style="display:none;">
                            <i class="fa fa-spinner fa-spin me-2"></i>Envoi en cours...
                        </span>
                    </button>

                </div>
            </div>

        </div>
    </div>
</section>

{{-- ═══════════════════════════════════════ --}}
{{-- STYLES                                  --}}
{{-- ═══════════════════════════════════════ --}}
<style>
.contact-section {
    padding: 80px 0 100px;
    background: #f8f9fa;
    min-height: 80vh;
}

/* Badge titre */
.badge-pill {
    display: inline-block;
    background: rgba(245,158,11,0.15);
    color: gold;
    border: 1px solid rgba(245,158,11,0.3);
    padding: 6px 18px;
    border-radius: 99px;
    font-size: 0.78rem;
    font-weight: 700;
    letter-spacing: 1px;
    margin-bottom: 14px;
}
.contact-title {
    font-size: 2.4rem;
    font-weight: 800;
    color: #0F1B2D;
    margin-bottom: 10px;
}
.contact-subtitle {
    color: #6b7280;
    font-size: 1rem;
}

/* Carte principale */
.contact-card {
    display: grid;
    grid-template-columns: 1fr 1.6fr;
    border-radius: 20px;
    overflow: hidden;
    box-shadow: 0 20px 60px rgba(15,27,45,0.12);
    max-width: 900px;
    margin: 0 auto;
}

/* Colonne infos (navy) */
.contact-info {
    background:#D4AF37;
    color:black;
    padding: 50px 40px;
}
.contact-info h2 {
    font-size: 1.5rem;
    font-weight: 700;
    margin-bottom: 10px;
    color: black;
}
.contact-info > p {
    color: black;
    font-size: 0.92rem;
    margin-bottom: 32px;
    line-height: 1.6;
}

/* Liste infos */
.info-list {
    list-style: none;
    padding: 0;
    margin: 0 0 32px;
    display: flex;
    flex-direction: column;
    gap: 18px;
}
.info-list li {
    display: flex;
    align-items: center;
    gap: 14px;
    font-size: 0.9rem;
    color: black;
}
.info-list li i {
    width: 36px;
    height: 36px;
    background: rgba(245,158,11,0.15);
    border: 1px solid rgba(245,158,11,0.3);
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    color: black;
    font-size: 0.85rem;
    flex-shrink: 0;
}

/* Horaires */
.horaires {
    border-top: 1px solid rgba(255,255,255,0.1);
    padding-top: 24px;
    font-size: 0.85rem;
    color: rgba(255,255,255,0.55);
    line-height: 1.8;
}
.horaires strong { color: black); }

/* Colonne formulaire */
.contact-form-wrap {
    background: #fff;
    padding: 50px 45px;
}

/* Groupes de champs */
.form-group-custom {
    margin-bottom: 20px;
    position: relative;
}
.form-group-custom label {
    display: block;
    font-size: 0.85rem;
    font-weight: 600;
    color: #374151;
    margin-bottom: 6px;
}
.required { color: #D4AF37; }
.optional  { color: #9ca3af; font-weight: 400; font-size: 0.8rem; }

/* Inputs / Select / Textarea */
.input-custom {
    width: 100%;
    padding: 11px 16px;
    border: 1.5px solid #e5e7eb;
    border-radius: 10px;
    font-size: 0.9rem;
    color: #1f2937;
    background: #f9fafb;
    transition: border-color 0.2s, box-shadow 0.2s;
    outline: none;
    font-family: inherit;
}
.input-custom:focus {
    border-color: #D4AF37;
    box-shadow: 0 0 0 3px rgba(245,158,11,0.12);
    background: #fff;
}
textarea.input-custom { resize: vertical; min-height: 120px; }
select.input-custom    { cursor: pointer; }

/* Compteur caractères */
.char-count {
    position: absolute;
    bottom: 8px;
    right: 12px;
    font-size: 0.72rem;
    color: #9ca3af;
}

/* Bouton Envoyer */
.btn-envoyer {
    width: 100%;
    padding: 13px;
    background: #D4AF37;
    color: white;
    border: none;
    border-radius: 10px;
    font-size: 0.95rem;
    font-weight: 700;
    cursor: pointer;
    transition: transform 0.15s, box-shadow 0.15s, opacity 0.2s;
    margin-top: 6px;
}
.btn-envoyer:hover {
    transform: translateY(-2px);
    box-shadow: 0 8px 24px rgba(245,158,11,0.35);
}
.btn-envoyer:active  { transform: scale(0.98); }
.btn-envoyer:disabled { opacity: 0.6; cursor: not-allowed; transform: none; }

/* ✅ Bloc succès — remplace le formulaire */
.success-block {
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    text-align: center;
    padding: 60px 30px;
    gap: 16px;
    min-height: 320px;
}
.success-icon {
    width: 80px;
    height: 80px;
    border-radius: 50%;
    background: linear-gradient(135deg, #10b981, #34d399);
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 2rem;
    color: black;
    box-shadow: 0 8px 24px rgba(16,185,129,0.35);
    animation: popIn 0.4s ease;
}
@keyframes popIn {
    from { transform: scale(0); opacity: 0; }
    to   { transform: scale(1); opacity: 1; }
}
.success-block h3 {
    font-size: 1.6rem;
    font-weight: 800;
    color: #0F1B2D;
    margin: 0;
}
.success-block p {
    color: #6b7280;
    font-size: 0.95rem;
    line-height: 1.7;
    margin: 0;
}
.btn-retry {
    margin-top: 10px;
    padding: 10px 24px;
    background: transparent;
    border: 2px solid #9A7D20;
    color: #9A7D20;
    border-radius: 10px;
    font-weight: 600;
    font-size: 0.88rem;
    cursor: pointer;
    transition: all 0.2s;
}
.btn-retry:hover {
    background: #9A7D20;
    color: #0F1B2D;
}

/* Alerte erreur */
.alert-error-custom {
    padding: 13px 16px;
    border-radius: 10px;
    font-size: 0.88rem;
    margin-bottom: 20px;
    display: flex;
    align-items: center;
    gap: 10px;
    background: #fef2f2;
    border: 1px solid #fca5a5;
    color: #991b1b;
}

/* Responsive */
@media (max-width: 768px) {
    .contact-card {
        grid-template-columns: 1fr;
    }
    .contact-form-wrap { padding: 35px 25px; }
    .contact-info      { padding: 35px 25px; }
}
</style>

{{-- ═══════════════════════════════════════ --}}
{{-- SCRIPTS                                 --}}
{{-- ═══════════════════════════════════════ --}}
<script>
/* Compteur de caractères pour le textarea */
document.getElementById('message').addEventListener('input', function () {
    const len     = this.value.length;
    const counter = document.getElementById('char-count');
    counter.textContent  = len + ' / 500';
    counter.style.color  = len > 450 ? '#ef4444' : '#9ca3af';
    if (len > 500) this.value = this.value.substring(0, 500);
});

/* Validation côté client */
function validerChamps() {
    const nom   = document.getElementById('nom').value.trim();
    const email = document.getElementById('email').value.trim();
    const sujet = document.getElementById('sujet').value;
    const msg   = document.getElementById('message').value.trim();
    const regex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;

    if (!nom)               return 'Veuillez entrer votre nom complet.';
    if (!email)             return 'Veuillez entrer votre adresse email.';
    if (!regex.test(email)) return 'Adresse email invalide.';
    if (!sujet)             return 'Veuillez choisir un sujet.';
    if (msg.length < 10)    return 'Le message doit contenir au moins 10 caractères.';

    return null; // ✅ OK
}

/* Envoi du formulaire */
function envoyerContact() {
    // Cacher les alertes précédentes
    document.getElementById('alert-success').style.display = 'none';
    document.getElementById('alert-error').style.display   = 'none';

    // Validation
    const erreur = validerChamps();
    if (erreur) {
        document.getElementById('error-text').textContent    = erreur;
        document.getElementById('alert-error').style.display = 'flex';
        return;
    }

    // État chargement — désactiver le bouton
    const btn = document.getElementById('btn-envoyer');
    btn.disabled = true;
    document.getElementById('btn-text').style.display    = 'none';
    document.getElementById('btn-loading').style.display = 'inline';

    // Simulation envoi (remplacer par API.post() si route Laravel disponible)
    setTimeout(function () {

        // ✅ Cacher le formulaire
        document.getElementById('contact-form').style.display = 'none';

        // ✅ Afficher le bloc succès à la place
        document.getElementById('alert-success').style.display = 'flex';

        // Scroll doux vers le haut de la carte
        document.querySelector('.contact-form-wrap')
                .scrollIntoView({ behavior: 'smooth', block: 'start' });

    }, 1800);
}

/* Réinitialiser pour envoyer un nouveau message */
function resetContact() {
    // Cacher le succès, afficher le formulaire
    document.getElementById('alert-success').style.display = 'none';
    document.getElementById('contact-form').style.display  = 'block';

    // Vider tous les champs
    ['nom', 'email', 'tel', 'message'].forEach(function(id) {
        document.getElementById(id).value = '';
    });
    document.getElementById('sujet').value        = '';
    document.getElementById('char-count').textContent = '0 / 500';

    // Réactiver le bouton
    const btn = document.getElementById('btn-envoyer');
    btn.disabled = false;
    document.getElementById('btn-text').style.display    = 'inline';
    document.getElementById('btn-loading').style.display = 'none';
}
</script>

@endsection