{{-- ── Bouton flottant --}}
<button id="chat-toggle" onclick="toggleChat()" aria-label="Ouvrir le chat">
    <i class="fa fa-comment-dots" id="chat-icon-open"></i>
    <i class="fa fa-times"        id="chat-icon-close" style="display:none;"></i>
    <span id="chat-badge" style="display:none;">1</span>
</button>

{{-- ── Fenêtre du chatbot --}}
<div id="chat-window" style="display:none;">
    <div class="chat-header">
        <div class="chat-avatar"><i class="fa fa-robot"></i></div>
        <div class="chat-header-info">
            <strong>Assistant Rentify</strong>
            <span class="chat-status"><span class="dot"></span> En ligne</span>
        </div>
        <button class="chat-close-btn" onclick="toggleChat()">
            <i class="fa fa-times"></i>
        </button>
    </div>

    <div id="chat-body">
        <div class="msg bot">
            <div class="msg-bubble">
                Bonjour ! 👋 Je suis l'assistant Rentify.<br>
                Comment puis-je vous aider aujourd'hui ?
            </div>
            <span class="msg-time">{{ now()->format('H:i') }}</span>
        </div>
    </div>

    <div id="chat-suggestions">
        <button class="suggestion-btn" onclick="envoyerSuggestion('Comment réserver ?')">📅 Réserver</button>
        <button class="suggestion-btn" onclick="envoyerSuggestion('Types de machines')">🏗️ Machines</button>
        <button class="suggestion-btn" onclick="envoyerSuggestion('Contacter le support')">📞 Support</button>
        <button class="suggestion-btn" onclick="envoyerSuggestion('Télécharger mon contrat')">📄 Contrat</button>
    </div>

    <div class="chat-input-wrap">
        <input type="text" id="chat-input"
               placeholder="Écrivez votre message..."
               onkeydown="if(event.key==='Enter') envoyerMessage()"
               maxlength="400" autocomplete="off">
        <button class="chat-send-btn" onclick="envoyerMessage()">
            <i class="fa fa-paper-plane"></i>
        </button>
    </div>
</div>

<style>
:root {
  --gold:#D4AF37; --gold-dk:#9A7D20; --gold-pale:#FEF9E7;
  --cream:#FAF7F0; --cream2:#F0EBE0; --cream3:#E8DDD0;
  --txt-dark:#1a1a2e; --txt-mid:#5a5660; --txt-light:#9992a4;
}
#chat-toggle {
    position:fixed; bottom:28px; right:28px; z-index:9990;
    width:58px; height:58px; border-radius:50%;
    background:var(--gold); border:none; color:var(--txt-dark);
    font-size:1.3rem; cursor:pointer;
    box-shadow:0 6px 20px rgba(212,175,55,.45);
    transition:transform .2s,box-shadow .2s;
    display:flex; align-items:center; justify-content:center;
}
#chat-toggle:hover { transform:scale(1.1); box-shadow:0 10px 30px rgba(212,175,55,.55); }
#chat-badge {
    position:absolute; top:-4px; right:-4px;
    width:20px; height:20px; background:#ef4444; color:#fff;
    border-radius:50%; font-size:.7rem; font-weight:700;
    display:flex; align-items:center; justify-content:center;
}
#chat-window {
    position:fixed; bottom:100px; right:28px; z-index:9989;
    width:360px; max-height:530px; border-radius:20px; overflow:hidden;
    box-shadow:0 20px 60px rgba(15,27,45,.2);
    border:1.5px solid rgba(212,175,55,.2);
    display:flex; flex-direction:column; background:#fff;
    animation:slideUp .3s ease;
}
@keyframes slideUp { from{opacity:0;transform:translateY(20px)} to{opacity:1;transform:translateY(0)} }
.chat-header {
    background:var(--gold-pale); border-bottom:1.5px solid rgba(212,175,55,.25);
    padding:14px 18px; display:flex; align-items:center; gap:12px; flex-shrink:0;
}
.chat-avatar {
    width:40px; height:40px; border-radius:50%;
    background:rgba(212,175,55,.15); border:2px solid var(--gold);
    display:flex; align-items:center; justify-content:center;
    color:var(--gold-dk); font-size:1.1rem; flex-shrink:0;
}
.chat-header-info { flex:1; display:flex; flex-direction:column; }
.chat-header-info strong { color:var(--txt-dark); font-size:.9rem; font-weight:700; }
.chat-status { font-size:.72rem; color:var(--txt-mid); display:flex; align-items:center; gap:5px; }
.chat-status .dot {
    width:7px; height:7px; border-radius:50%; background:#10b981;
    display:inline-block; animation:blink 2s infinite;
}
@keyframes blink { 0%,100%{opacity:1} 50%{opacity:.4} }
.chat-close-btn {
    background:var(--cream2); border:none; color:var(--txt-mid);
    cursor:pointer; font-size:.85rem; width:28px; height:28px;
    border-radius:7px; display:flex; align-items:center; justify-content:center; transition:all .2s;
}
.chat-close-btn:hover { background:var(--cream3); color:var(--txt-dark); }
#chat-body {
    flex:1; overflow-y:auto; padding:16px;
    display:flex; flex-direction:column; gap:12px;
    background:var(--cream); scrollbar-width:thin; scrollbar-color:var(--cream3) transparent;
}
.msg { display:flex; flex-direction:column; max-width:85%; }
.msg.bot  { align-self:flex-start; }
.msg.user { align-self:flex-end; align-items:flex-end; }
.msg-bubble {
    padding:10px 14px; border-radius:16px;
    font-size:.85rem; line-height:1.6; word-break:break-word;
}
.msg.bot .msg-bubble {
    background:#fff; color:var(--txt-dark); border-bottom-left-radius:4px;
    box-shadow:0 2px 8px rgba(0,0,0,.06); border:1px solid rgba(212,175,55,.1);
}
.msg.user .msg-bubble {
    background:var(--gold); color:var(--txt-dark); border-bottom-right-radius:4px;
}
.msg-time { font-size:.68rem; color:var(--txt-light); margin-top:3px; padding:0 4px; }
.typing-dots { display:flex; gap:4px; align-items:center; padding:2px 0; }
.typing-dots span {
    width:7px; height:7px; border-radius:50%; background:var(--txt-light);
    animation:bounce 1.2s infinite;
}
.typing-dots span:nth-child(2){animation-delay:.2s}
.typing-dots span:nth-child(3){animation-delay:.4s}
@keyframes bounce { 0%,80%,100%{transform:translateY(0)} 40%{transform:translateY(-6px)} }
#chat-suggestions {
    padding:10px 14px; display:flex; flex-wrap:wrap; gap:6px;
    border-top:1px solid var(--cream3); background:#fff; flex-shrink:0;
}
.suggestion-btn {
    padding:5px 12px; border:1.5px solid var(--cream3); border-radius:99px;
    background:#fff; color:var(--txt-mid); font-size:.75rem; cursor:pointer;
    transition:all .2s; white-space:nowrap;
}
.suggestion-btn:hover { border-color:var(--gold); color:var(--gold-dk); background:var(--gold-pale); }
.chat-input-wrap {
    display:flex; align-items:center; padding:10px 14px;
    border-top:1px solid var(--cream3); background:#fff; gap:8px; flex-shrink:0;
}
.chat-input-wrap input {
    flex:1; border:1.5px solid var(--cream3); border-radius:10px;
    padding:9px 14px; font-size:.85rem; outline:none; color:var(--txt-dark);
    background:var(--cream); transition:border-color .2s; font-family:'DM Sans',sans-serif;
}
.chat-input-wrap input:focus { border-color:var(--gold); background:#fff; }
.chat-input-wrap input::placeholder { color:var(--txt-light); }
.chat-send-btn {
    width:38px; height:38px; border-radius:10px;
    background:var(--gold); border:none; color:var(--txt-dark);
    cursor:pointer; font-size:.9rem; display:flex;
    align-items:center; justify-content:center;
    transition:transform .15s,background .15s; flex-shrink:0;
}
.chat-send-btn:hover { transform:scale(1.08); background:var(--gold-dk); color:#fff; }
@media(max-width:480px) {
    #chat-window { width:calc(100vw - 20px); right:10px; bottom:90px; }
    #chat-toggle  { right:16px; bottom:20px; }
}
</style>

<script>
(function () {

/* ══════════════════════════════════════════════════════════════
   RENTIFY CHATBOT — 100% OFFLINE
   Moteur : matching partiel normalisé + score pondéré
   25 catégories · variantes FR / darija / anglais
══════════════════════════════════════════════════════════════ */

const KB = [

  /* ─── SALUTATIONS ─── */
  {
    keys:['slm','salam','slam','bonjour','bonsoir','salut','hello','hi','hey','bjr','bsr','slt','ahlan','mrhba','marhba','cava','ça va','ca va','wach','labas','lbes','kif','kifach','how are','good morning','good evening'],
    reps:[
      'Bonjour ! 👋 Bienvenue chez Rentify.\nJe peux vous aider avec :\n📅 Réservations · 🏗 Machines · 💰 Tarifs\n📄 Contrats · 👤 Compte · 📞 Support\n\nQuelle est votre question ?',
      'Salam ! 😊 Comment puis-je vous aider aujourd\'hui ?\nPosez-moi n\'importe quelle question sur Rentify.',
      'Bonjour ! Ravi de vous aider. 🌟\nN\'hésitez pas à me poser votre question sur la location d\'engins au Maroc.'
    ]
  },

  /* ─── REMERCIEMENTS / POLITESSE ─── */
  {
    keys:['merci','شكرا','shukran','thank','bravo','super','parfait','nickel','top','excellent','genial','génial','cool','ok merci','tres bien','très bien','barak','barakallah','jazak'],
    reps:[
      '😊 Avec plaisir ! N\'hésitez pas si vous avez d\'autres questions.\nBonne location avec Rentify ! 🏗✨',
      '🙏 C\'est un plaisir ! N\'hésitez pas à revenir.',
      'Avec plaisir ! 😊 Rentify est là pour vous.'
    ]
  },

  /* ─── AU REVOIR ─── */
  {
    keys:['au revoir','bye','goodbye','bslama','bslema','a bientot','à bientôt','ciao','tchao','yallah','yala'],
    reps:[
      'Au revoir ! 👋 À très bientôt sur Rentify.',
      'Bonne journée ! 🌟 Revenez quand vous voulez.',
      'À bientôt ! N\'hésitez pas si vous avez d\'autres questions. 😊'
    ]
  },

  /* ─── C'EST QUOI RENTIFY ─── */
  {
    keys:['cest quoi','c\'est quoi','qu\'est ce','quest ce','kif kayn','chno','rentify','plateforme','comment ca marche','comment ça marche','fonctionn','marche','comment fonctionne','how it works','about'],
    reps:[
      '🏗 Rentify est la plateforme N°1 de location d\'engins de chantier au Maroc.\n\nComment ça marche :\n1. 🔍 Cherchez une machine\n2. 📅 Réservez aux dates souhaitées\n3. ✅ Le propriétaire confirme sous 24h\n4. 📄 Un contrat PDF est généré\n5. 🏗 Utilisez la machine\n6. ⭐ Laissez un avis\n\nSimple, rapide et sécurisé ! 🇲🇦'
    ]
  },

  /* ─── RÉSERVATION ─── */
  {
    keys:['reserv','réserv','louer','loue','location','book','commander','commande','kifach nresrv','comment reserver','how to book','kira','kra'],
    reps:[
      '📅 Pour réserver une machine :\n\n1. Connectez-vous à votre compte\n2. Allez sur /machines et choisissez\n3. Cliquez "Réserver" et choisissez vos dates\n4. Confirmez — le propriétaire valide sous 24h\n5. Votre contrat PDF est généré automatiquement ✅\n\nDes questions ? Je suis là ! 😊'
    ]
  },

  /* ─── MACHINES / CATALOGUE ─── */
  {
    keys:['machine','engin','materiel','matériel','excavat','bulldoz','grue','chargeuse','compacteur','nacelle','tractopelle','camion','type','catalogue','equipement','équipement','quel machine','quelles machines','liste','chno kayn','chno 3ndkom'],
    reps:[
      '🏗 Les machines disponibles sur Rentify :\n\n🔨 Excavatrice · 🏗 Grue · 🚜 Bulldozer\n🪣 Chargeuse · 🛞 Compacteur · 🔺 Nacelle\n🚛 Tractopelle · 🚚 Camion\n\nConsultez tout le catalogue sur /machines 🔍\nFiltrez par type, ville et disponibilité.'
    ]
  },

  /* ─── PRIX / TARIFS ─── */
  {
    keys:['prix','tarif','cout','coût','combien','cher','paiement','payer','pay','mad','dirham','thaman','bchhal','b9ach','b7al','montant','budget'],
    reps:[
      '💰 Les tarifs Rentify :\n\n• Prix en MAD/jour affiché sur chaque machine\n• Calculé automatiquement selon vos dates\n• Paiement sécurisé après validation\n• Aucun frais caché ✅\n\nConsultez les prix sur /machines 👀'
    ]
  },

  /* ─── CONTRAT / PDF ─── */
  {
    keys:['contrat','contract','pdf','document','facture','telecharg','télécharg','download','imprimer','telecharger','contrat pdf','mon contrat'],
    reps:[
      '📄 Télécharger votre contrat :\n\n1. Allez dans Mon espace → Mes réservations\n2. Cliquez sur la réservation concernée\n3. Bouton "📄 Télécharger le contrat PDF"\n\nLe contrat est généré automatiquement après validation ✅\nIl contient toutes les informations légales.'
    ]
  },

  /* ─── SUPPORT / CONTACT ─── */
  {
    keys:['support','contact','aide','help','assist','probleme','problème','souci','bug','erreur','signaler','joindre','appel','telephone','téléphone','numero','numéro','email','mail'],
    reps:[
      '📞 Contacter l\'équipe Rentify :\n\n📧 contact@rentify.ma\n📱 +212 6 00 00 00 00\n📍 Casablanca, Maroc\n\nFormulaire de contact → /contact\n⏰ Réponse sous 24h ouvrables\n\nComment puis-je vous aider directement ? 😊'
    ]
  },

  /* ─── COMPTE / INSCRIPTION ─── */
  {
    keys:['compte','inscription','register','créer','creer','profil','profile','mot de passe','password','login','connexion','connecter','deconnecter','déconnecter','creer compte','nouveau compte','sign up','log in','s inscrire','s\'inscrire','dash','espace'],
    reps:[
      '👤 Gestion de compte :\n\n• Créer un compte → /register\n• Se connecter → /login\n• Modifier le profil → /profile\n• Mot de passe oublié → /login → "Mot de passe oublié"\n• Mon espace client → /dashboard/client\n\nProblème de connexion ? → contact@rentify.ma 📧'
    ]
  },

  /* ─── ANNULATION ─── */
  {
    keys:['annul','cancel','refus','remboursement','rembours','retrait','annuler','supprimer reservation'],
    reps:[
      '❌ Annuler une réservation :\n\n• Avant validation : annulation libre depuis Mon espace\n• Après validation : contactez le propriétaire via la messagerie\n• Remboursement selon les conditions du contrat\n\nPour toute demande urgente → contact@rentify.ma 📧'
    ]
  },

  /* ─── STATUT RÉSERVATION ─── */
  {
    keys:['statut','status','état','etat','en cours','accept','accepté','attente','validat','confirm','refus','termine','terminé','suivi','ou en est','où en est'],
    reps:[
      '📋 Statuts de vos réservations :\n\n⏳ En attente — le propriétaire examine votre demande\n✅ Acceptée — réservation confirmée !\n❌ Refusée — machine non disponible\n🔄 En cours — location en cours\n🏁 Terminée — mission accomplie\n\nSuivi dans Mon espace → Réservations 📱'
    ]
  },

  /* ─── PROPRIÉTAIRE / OWNER ─── */
  {
    keys:['proprietaire','propriétaire','owner','louer ma machine','ajouter machine','publier','mettre en location','vendre','mes machines','dashboard owner','gerer','gérer','gagner','revenu','revenue'],
    reps:[
      '🏠 Vous avez des machines à louer ?\n\n1. Créez un compte Owner sur /register\n2. Ajoutez vos machines → /machines/create\n3. Fixez votre prix/jour et disponibilités\n4. Recevez des demandes de réservation\n5. Gérez tout depuis /dashboard/owner 📊\n\nGagnez des revenus avec votre parc machines ! 💰'
    ]
  },

  /* ─── DISPONIBILITÉS ─── */
  {
    keys:['disponib','disponibilité','disponibilite','libre','occup','quand','calendrier','date','periode','période','horaire','planning'],
    reps:[
      '📅 Vérifier la disponibilité :\n\n1. Ouvrez la fiche machine sur /machines\n2. Le calendrier de disponibilité est affiché\n3. Les dates grises = occupées\n4. Choisissez vos dates libres → Réserver ✅\n\nChaque machine a son propre calendrier mis à jour en temps réel.'
    ]
  },

  /* ─── LIVRAISON ─── */
  {
    keys:['livraison','livrer','transport','deplacement','déplacement','adresse','chantier','site'],
    reps:[
      '🚚 Livraison sur chantier :\n\nCertains propriétaires proposent la livraison. Cette info est visible sur la fiche machine (icône 🚚).\n\nAprès réservation confirmée, contactez le propriétaire directement pour organiser la logistique 📬'
    ]
  },

  /* ─── VILLES / ZONES ─── */
  {
    keys:['maroc','casablanca','rabat','tanger','fes','fès','marrakech','agadir','oujda','meknes','meknès','tetouan','tétouan','ville','region','région','zone','ou','où','disponible ou','dans quelle'],
    reps:[
      '📍 Rentify couvre tout le Maroc !\n\nMachines disponibles dans :\n🏙 Casablanca · Rabat · Tanger · Fès\n🏙 Marrakech · Agadir · Oujda · Meknès\n🏙 Tétouan · et toutes les villes\n\nFiltrez par ville sur /machines 🗺'
    ]
  },

  /* ─── DURÉE ─── */
  {
    keys:['duree','durée','combien de temps','jours','semaine','mois','long','court','minimum','maximum','min','max'],
    reps:[
      '⏱ Durée de location :\n\n• Minimum : 1 jour\n• Maximum : selon le propriétaire\n• Prix calculé automatiquement : nb jours × prix/jour\n• Affiché clairement avant confirmation ✅\n\nPlus la durée est longue, plus vous avez intérêt à négocier directement avec le propriétaire 😉'
    ]
  },

  /* ─── AVIS / NOTES ─── */
  {
    keys:['avis','note','etoile','étoile','star','commentaire','evaluat','évaluat','rating','feedback','notation'],
    reps:[
      '⭐ Laisser un avis :\n\nAprès une location terminée :\n→ Mon espace → Réservations terminées → "Laisser un avis"\n\nNotez la machine et le propriétaire (1 à 5 étoiles)\nVos avis aident toute la communauté Rentify ! 🙏'
    ]
  },

  /* ─── ASSURANCE ─── */
  {
    keys:['assurance','assuranc','garantie','accident','dommage','responsabilit','bris','panne','casse','endommag'],
    reps:[
      '🛡 Assurance & Garanties :\n\nChaque location est encadrée par un contrat légal.\nNous recommandons de vérifier avec le propriétaire les conditions d\'assurance avant la location.\n\nEn cas de problème → contact@rentify.ma 📧\nNotre équipe vous accompagne.'
    ]
  },

  /* ─── CHAUFFEUR / OPÉRATEUR ─── */
  {
    keys:['chauffeur','conducteur','operateur','opérateur','driver','pilote','avec chauffeur','avec operateur'],
    reps:[
      '👷 Location avec opérateur :\n\nCertaines machines sont disponibles avec un chauffeur/opérateur qualifié. Cette option est précisée sur la fiche machine.\n\nPour les demandes spécifiques → contact@rentify.ma 📧'
    ]
  },

  /* ─── FACTURE ─── */
  {
    keys:['facture','invoic','recu','reçu','quittance','justificatif','fiscalite','fiscalité','tva'],
    reps:[
      '🧾 Facturation :\n\nLa facture est générée avec le contrat PDF après validation.\n\nAccessible dans :\n→ Mon espace → Réservations → 📄 Télécharger\n\nPour une facture officielle avec cachet → contact@rentify.ma 📧'
    ]
  },

  /* ─── SÉCURITÉ / CONFIANCE ─── */
  {
    keys:['securite','sécurité','fiable','confiance','arnaque','verif','vérif','safe','protect','donnees','données'],
    reps:[
      '🔒 Sécurité sur Rentify :\n\n✅ Tous les propriétaires sont vérifiés\n✅ Paiement sécurisé\n✅ Contrat légal pour chaque location\n✅ Support disponible en cas de litige\n✅ Données protégées\n\nVous pouvez louer en toute confiance ! 🛡'
    ]
  },

  /* ─── OUI / NON / RÉPONSES COURTES ─── */
  {
    keys:['^oui$','^non$','^ok$','^d accord$','^daccord$','^oki$','^wah$','^iyeh$','^nta9$','^compris$','^vu$','^bien$'],
    reps:[
      'D\'accord ! Y a-t-il autre chose que je peux faire pour vous ? 😊',
      'Parfait ! N\'hésitez pas si vous avez d\'autres questions. 🌟',
      'Bien reçu ! Puis-je vous aider avec autre chose ? 😊'
    ]
  },

  /* ─── QUESTIONS SUR LE BOT ─── */
  {
    keys:['qui es tu','qui etes vous','tu es quoi','c est quoi toi','bot','robot','ia','intelligence','automatique','chatbot'],
    reps:[
      '🤖 Je suis l\'assistant virtuel de Rentify !\nJe fonctionne 24h/24 pour répondre à vos questions sur la location d\'engins.\n\nPour les demandes complexes, notre équipe humaine est disponible sur contact@rentify.ma 📧'
    ]
  }

];

/* ══════════════════════
   NORMALISATION
══════════════════════ */
function norm(str) {
  return str.toLowerCase()
    .normalize('NFD').replace(/[\u0300-\u036f]/g,'')
    .replace(/[''`]/g,"'")
    .trim();
}

/* ══════════════════════
   MATCHING SCORÉ
══════════════════════ */
function repondre(msg) {
  const n = norm(msg);
  let best = null, top = 0;

  for (const cat of KB) {
    let score = 0;
    for (const key of cat.keys) {
      const nk = norm(key);
      // Test regex pour les clés courtes (^...$)
      if (key.startsWith('^')) {
        if (new RegExp(key).test(n)) score += 20;
      } else if (n.includes(nk)) {
        score += nk.length; // mots longs = plus de poids
      }
    }
    if (score > top) { top = score; best = cat; }
  }

  if (best && top > 0) {
    const reps = best.reps;
    return reps[Math.floor(Math.random() * reps.length)];
  }

  // ── Fallback intelligent ──
  const fallbacks = [
    '🤔 Je n\'ai pas tout compris. Essayez de reformuler ou choisissez un sujet :\n\n📅 "Comment réserver ?"\n🏗 "Types de machines"\n💰 "Prix et tarifs"\n📄 "Mon contrat"\n📞 "Contacter le support"',
    '💬 Pourriez-vous reformuler votre question ?\nVoici ce que je peux faire pour vous :\n\n• Réservations & disponibilités\n• Catalogue de machines\n• Tarifs & paiement\n• Compte & profil\n• Support & contact',
    '🙏 Je n\'ai pas bien saisi. Posez votre question différemment ou contactez directement notre équipe :\n📧 contact@rentify.ma\n📱 +212 6 00 00 00 00'
  ];
  return fallbacks[Math.floor(Math.random() * fallbacks.length)];
}

/* ══════════════════════
   TOGGLE / ÉTAT
══════════════════════ */
let chatOuvert = false;

window.toggleChat = function () {
  chatOuvert = !chatOuvert;
  const win = document.getElementById('chat-window');
  if (chatOuvert) {
    win.style.display = 'flex';
    document.getElementById('chat-icon-open').style.display  = 'none';
    document.getElementById('chat-icon-close').style.display = 'block';
    document.getElementById('chat-badge').style.display      = 'none';
    document.getElementById('chat-input').focus();
    scrollerBas();
  } else {
    win.style.display = 'none';
    document.getElementById('chat-icon-open').style.display  = 'block';
    document.getElementById('chat-icon-close').style.display = 'none';
  }
};

window.envoyerSuggestion = function (t) {
  document.getElementById('chat-input').value = t;
  envoyerMessage();
};

window.envoyerMessage = function () {
  const input = document.getElementById('chat-input');
  const texte = input.value.trim();
  if (!texte) return;
  ajouterMessage(texte, 'user');
  input.value = '';
  document.getElementById('chat-suggestions').style.display = 'none';
  const tid = afficherTyping();
  // Délai naturel 500-900ms
  setTimeout(function () {
    supprimerTyping(tid);
    ajouterMessage(repondre(texte), 'bot');
  }, 500 + Math.random() * 400);
};

function ajouterMessage(texte, type) {
  const body = document.getElementById('chat-body');
  const now  = new Date().toLocaleTimeString('fr-FR',{hour:'2-digit',minute:'2-digit'});
  const div  = document.createElement('div');
  div.className = 'msg ' + type;
  div.innerHTML =
    '<div class="msg-bubble">' + esc(texte) + '</div>' +
    '<span class="msg-time">' + now + '</span>';
  body.appendChild(div);
  scrollerBas();
}

function afficherTyping() {
  const body = document.getElementById('chat-body');
  const id   = 'ty-' + Date.now();
  const div  = document.createElement('div');
  div.className = 'msg bot'; div.id = id;
  div.innerHTML = '<div class="msg-bubble"><div class="typing-dots"><span></span><span></span><span></span></div></div>';
  body.appendChild(div); scrollerBas(); return id;
}
function supprimerTyping(id) { const e = document.getElementById(id); if(e) e.remove(); }
function scrollerBas() { const b = document.getElementById('chat-body'); setTimeout(()=>b.scrollTop=b.scrollHeight,50); }
function esc(s) { return s.replace(/&/g,'&amp;').replace(/</g,'&lt;').replace(/>/g,'&gt;').replace(/\n/g,'<br>'); }

setTimeout(()=>{ if(!chatOuvert) document.getElementById('chat-badge').style.display='flex'; }, 3000);

})();
</script>