

{{-- ── Bouton flottant --}}
<button id="chat-toggle" onclick="toggleChat()" aria-label="Ouvrir le chat">
    <i class="fa fa-comment-dots" id="chat-icon-open"></i>
    <i class="fa fa-times"        id="chat-icon-close" style="display:none;"></i>
    <span id="chat-badge" style="display:none;">1</span>
</button>

{{-- ── Fenêtre du chatbot --}}
<div id="chat-window" style="display:none;">

    {{-- Header --}}
    <div class="chat-header">
        <div class="chat-avatar">
            <i class="fa fa-robot"></i>
        </div>
        <div class="chat-header-info">
            <strong>Assistant Rentify</strong>
            <span class="chat-status"><span class="dot"></span> En ligne</span>
        </div>
        <button class="chat-close-btn" onclick="toggleChat()">
            <i class="fa fa-times"></i>
        </button>
    </div>

    {{-- Corps : messages --}}
    <div id="chat-body">
        {{-- Message de bienvenue initial --}}
        <div class="msg bot">
            <div class="msg-bubble">
                Bonjour ! 👋 Je suis l'assistant Rentify.<br>
                Comment puis-je vous aider aujourd'hui ?
            </div>
            <span class="msg-time">{{ now()->format('H:i') }}</span>
        </div>
    </div>

    {{-- Suggestions rapides --}}
    <div id="chat-suggestions">
        <button class="suggestion-btn" onclick="envoyerSuggestion('Comment réserver ?')">
            📅 Réserver
        </button>
        <button class="suggestion-btn" onclick="envoyerSuggestion('Types de machines')">
            🏗️ Machines
        </button>
        <button class="suggestion-btn" onclick="envoyerSuggestion('Contacter le support')">
            📞 Support
        </button>
        <button class="suggestion-btn" onclick="envoyerSuggestion('Télécharger mon contrat')">
            📄 Contrat
        </button>
    </div>

    {{-- Zone de saisie --}}
    <div class="chat-input-wrap">
        <input type="text"
               id="chat-input"
               placeholder="Écrivez votre message..."
               onkeydown="if(event.key==='Enter') envoyerMessage()"
               maxlength="300"
               autocomplete="off">
        <button class="chat-send-btn" onclick="envoyerMessage()">
            <i class="fa fa-paper-plane"></i>
        </button>
    </div>

</div>

{{-- ═══════════════════════════════════════ --}}
{{-- CSS                                     --}}
{{-- ═══════════════════════════════════════ --}}
<style>
/* Bouton flottant */
#chat-toggle {
    position: fixed;
    bottom: 28px;
    right: 28px;
    z-index: 9990;
    width: 58px;
    height: 58px;
    border-radius: 50%;
    background: linear-gradient(135deg, #F59E0B, #fbbf24);
    border: none;
    color: #0F1B2D;
    font-size: 1.3rem;
    cursor: pointer;
    box-shadow: 0 6px 20px rgba(245,158,11,0.45);
    transition: transform 0.2s, box-shadow 0.2s;
    display: flex;
    align-items: center;
    justify-content: center;
}
#chat-toggle:hover {
    transform: scale(1.1);
    box-shadow: 0 10px 30px rgba(245,158,11,0.55);
}

/* Badge notification */
#chat-badge {
    position: absolute;
    top: -4px;
    right: -4px;
    width: 20px;
    height: 20px;
    background: #ef4444;
    color: #fff;
    border-radius: 50%;
    font-size: 0.7rem;
    font-weight: 700;
    display: flex;
    align-items: center;
    justify-content: center;
}

/* Fenêtre chat */
#chat-window {
    position: fixed;
    bottom: 100px;
    right: 28px;
    z-index: 9989;
    width: 360px;
    max-height: 530px;
    border-radius: 20px;
    overflow: hidden;
    box-shadow: 0 20px 60px rgba(15,27,45,0.25);
    display: flex;
    flex-direction: column;
    background: #fff;
    animation: slideUp 0.3s ease;
}
@keyframes slideUp {
    from { opacity: 0; transform: translateY(20px); }
    to   { opacity: 1; transform: translateY(0); }
}

/* Header */
.chat-header {
    background: #0F1B2D;
    padding: 14px 18px;
    display: flex;
    align-items: center;
    gap: 12px;
    flex-shrink: 0;
}
.chat-avatar {
    width: 40px;
    height: 40px;
    border-radius: 50%;
    background: rgba(245,158,11,0.2);
    border: 2px solid #F59E0B;
    display: flex;
    align-items: center;
    justify-content: center;
    color: #F59E0B;
    font-size: 1.1rem;
    flex-shrink: 0;
}
.chat-header-info {
    flex: 1;
    display: flex;
    flex-direction: column;
}
.chat-header-info strong {
    color: #fff;
    font-size: 0.9rem;
}
.chat-status {
    font-size: 0.72rem;
    color: rgba(255,255,255,0.55);
    display: flex;
    align-items: center;
    gap: 5px;
}
.chat-status .dot {
    width: 7px;
    height: 7px;
    border-radius: 50%;
    background: #10b981;
    display: inline-block;
    animation: pulse 2s infinite;
}
@keyframes pulse {
    0%,100% { opacity: 1; }
    50%      { opacity: 0.4; }
}
.chat-close-btn {
    background: none;
    border: none;
    color: rgba(255,255,255,0.5);
    cursor: pointer;
    font-size: 0.9rem;
    transition: color 0.2s;
}
.chat-close-btn:hover { color: #fff; }

/* Corps messages */
#chat-body {
    flex: 1;
    overflow-y: auto;
    padding: 16px;
    display: flex;
    flex-direction: column;
    gap: 12px;
    background: #f8f9fa;
    scrollbar-width: thin;
    scrollbar-color: #e5e7eb transparent;
}

/* Bulles de messages */
.msg { display: flex; flex-direction: column; max-width: 82%; }
.msg.bot  { align-self: flex-start; }
.msg.user { align-self: flex-end; align-items: flex-end; }

.msg-bubble {
    padding: 10px 14px;
    border-radius: 16px;
    font-size: 0.85rem;
    line-height: 1.55;
    white-space: pre-line;
    word-break: break-word;
}
.msg.bot  .msg-bubble {
    background: #fff;
    color: #1f2937;
    border-bottom-left-radius: 4px;
    box-shadow: 0 2px 8px rgba(0,0,0,0.07);
}
.msg.user .msg-bubble {
    background: linear-gradient(135deg, #F59E0B, #fbbf24);
    color: #0F1B2D;
    border-bottom-right-radius: 4px;
}
.msg-time {
    font-size: 0.68rem;
    color: #9ca3af;
    margin-top: 3px;
    padding: 0 4px;
}

/* Indicateur typing */
.typing-bubble .msg-bubble {
    padding: 12px 16px;
}
.typing-dots {
    display: flex;
    gap: 4px;
    align-items: center;
}
.typing-dots span {
    width: 7px;
    height: 7px;
    border-radius: 50%;
    background: #9ca3af;
    animation: bounce 1.2s infinite;
}
.typing-dots span:nth-child(2) { animation-delay: 0.2s; }
.typing-dots span:nth-child(3) { animation-delay: 0.4s; }
@keyframes bounce {
    0%,80%,100% { transform: translateY(0); }
    40%          { transform: translateY(-6px); }
}

/* Suggestions rapides */
#chat-suggestions {
    padding: 10px 14px;
    display: flex;
    flex-wrap: wrap;
    gap: 6px;
    border-top: 1px solid #f3f4f6;
    background: #fff;
    flex-shrink: 0;
}
.suggestion-btn {
    padding: 5px 12px;
    border: 1.5px solid #e5e7eb;
    border-radius: 99px;
    background: #fff;
    color: #374151;
    font-size: 0.75rem;
    cursor: pointer;
    transition: all 0.2s;
    white-space: nowrap;
}
.suggestion-btn:hover {
    border-color: #F59E0B;
    color: #F59E0B;
    background: rgba(245,158,11,0.05);
}

/* Zone de saisie */
.chat-input-wrap {
    display: flex;
    align-items: center;
    padding: 10px 14px;
    border-top: 1px solid #f3f4f6;
    background: #fff;
    gap: 8px;
    flex-shrink: 0;
}
.chat-input-wrap input {
    flex: 1;
    border: 1.5px solid #e5e7eb;
    border-radius: 10px;
    padding: 9px 14px;
    font-size: 0.85rem;
    outline: none;
    color: #1f2937;
    background: #f9fafb;
    transition: border-color 0.2s;
}
.chat-input-wrap input:focus {
    border-color: #F59E0B;
    background: #fff;
}
.chat-send-btn {
    width: 38px;
    height: 38px;
    border-radius: 10px;
    background: linear-gradient(135deg, #F59E0B, #fbbf24);
    border: none;
    color: #0F1B2D;
    cursor: pointer;
    font-size: 0.9rem;
    display: flex;
    align-items: center;
    justify-content: center;
    transition: transform 0.15s;
    flex-shrink: 0;
}
.chat-send-btn:hover { transform: scale(1.08); }

/* Responsive mobile */
@media (max-width: 480px) {
    #chat-window {
        width: calc(100vw - 20px);
        right: 10px;
        bottom: 90px;
    }
    #chat-toggle { right: 16px; bottom: 20px; }
}
</style>

{{-- ═══════════════════════════════════════ --}}
{{-- JAVASCRIPT                              --}}
{{-- ═══════════════════════════════════════ --}}
<script>
(function () {

    /* ── État du chat ── */
    let chatOuvert = false;

    /* ── Ouvrir / Fermer la fenêtre ── */
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

    /* ── Envoyer via suggestion rapide ── */
    window.envoyerSuggestion = function (texte) {
        document.getElementById('chat-input').value = texte;
        envoyerMessage();
    };

    /* ── Envoyer un message ── */
    window.envoyerMessage = function () {
        const input = document.getElementById('chat-input');
        const texte = input.value.trim();
        if (!texte) return;

        // Afficher le message utilisateur
        ajouterMessage(texte, 'user');
        input.value = '';

        // Cacher les suggestions après le premier message
        document.getElementById('chat-suggestions').style.display = 'none';

        // Afficher l'indicateur "en train d'écrire..."
        const typingId = afficherTyping();

        // Appel à l'API Laravel
        fetch('/api/chatbot', {
            method : 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN' : document.querySelector('meta[name="csrf-token"]')
                                     ? document.querySelector('meta[name="csrf-token"]').content
                                     : ''
            },
            body: JSON.stringify({ message: texte })
        })
        .then(function (res) { return res.json(); })
        .then(function (data) {
            supprimerTyping(typingId);
            ajouterMessage(data.reponse, 'bot', data.heure);
        })
        .catch(function () {
            supprimerTyping(typingId);
            ajouterMessage("Une erreur est survenue. Réessayez dans un instant 🔄", 'bot');
        });
    };

    /* ── Ajoute une bulle de message ── */
    function ajouterMessage(texte, type, heure) {
        const body    = document.getElementById('chat-body');
        const maintenant = heure || new Date().toLocaleTimeString('fr-FR', { hour: '2-digit', minute: '2-digit' });

        const div = document.createElement('div');
        div.className = 'msg ' + type;
        div.innerHTML =
            '<div class="msg-bubble">' + escapeHtml(texte) + '</div>' +
            '<span class="msg-time">' + maintenant + '</span>';

        body.appendChild(div);
        scrollerBas();
    }

    /* ── Indicateur typing ── */
    function afficherTyping() {
        const body = document.getElementById('chat-body');
        const id   = 'typing-' + Date.now();
        const div  = document.createElement('div');
        div.className = 'msg bot typing-bubble';
        div.id        = id;
        div.innerHTML = '<div class="msg-bubble"><div class="typing-dots"><span></span><span></span><span></span></div></div>';
        body.appendChild(div);
        scrollerBas();
        return id;
    }

    function supprimerTyping(id) {
        const el = document.getElementById(id);
        if (el) el.remove();
    }

    /* ── Scroll vers le dernier message ── */
    function scrollerBas() {
        const body = document.getElementById('chat-body');
        setTimeout(function () { body.scrollTop = body.scrollHeight; }, 50);
    }

    /* ── Échappe le HTML pour sécurité ── */
    function escapeHtml(str) {
        return str
            .replace(/&/g, '&amp;')
            .replace(/</g, '&lt;')
            .replace(/>/g, '&gt;')
            .replace(/\n/g, '<br>');
    }

    /* ── Badge de bienvenue après 3 secondes ── */
    setTimeout(function () {
        if (!chatOuvert) {
            document.getElementById('chat-badge').style.display = 'flex';
        }
    }, 3000);

})();
</script>