<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ChatbotController extends Controller
{
    private array $kb = [
        [
            'keys' => ['réserver', 'reserver', 'reservation', 'réservation', 'louer', 'location', 'disponible'],
            'rep'  => "Pour réserver une machine :\n1. Parcourez le catalogue\n2. Cliquez sur une machine\n3. Choisissez vos dates\n4. Envoyez votre demande\n\nLe propriétaire vous répond sous 24h ✅"
        ],
        [
            'keys' => ['annuler', 'annulation', 'cancel'],
            'rep'  => "Pour annuler une réservation, rendez-vous dans votre tableau de bord → Mes réservations → Annuler.\n\nContactez-nous : support@rentify.ma 📧"
        ],
        [
            'keys' => ['contrat', 'pdf', 'document', 'telecharger', 'télécharger'],
            'rep'  => "Le contrat PDF est généré automatiquement après acceptation.\n\nTableau de bord → Mes réservations → 📄 Télécharger le contrat"
        ],
        [
            'keys' => ['prix', 'tarif', 'cout', 'coût', 'combien', 'payer', 'paiement'],
            'rep'  => "Les prix sont fixés par chaque propriétaire (par jour).\n\nFiltrez par budget dans le catalogue 💰"
        ],
        [
            'keys' => ['machine', 'engin', 'grue', 'excavatrice', 'bulldozer', 'chargeuse', 'compacteur', 'nacelle'],
            'rep'  => "Rentify propose : grues, excavatrices, bulldozers, chargeuses, compacteurs, nacelles et plus.\n\nParcourez le catalogue 🏗️"
        ],
        [
            'keys' => ['carte', 'map', 'gps', 'localisation', 'ville', 'casablanca', 'rabat', 'proche'],
            'rep'  => "La carte interactive est dans le catalogue !\n\nCliquez sur « 🗺️ Voir la carte » pour voir les machines près de vous 📍"
        ],
        [
            'keys' => ['inscrire', 'inscription', 'register', 'compte', 'creer'],
            'rep'  => "Créez votre compte gratuitement :\n• Client → louer des machines\n• Propriétaire → publier vos machines\n\nCliquez sur « S'inscrire » en haut 👆"
        ],
        [
            'keys' => ['connexion', 'connecter', 'login', 'mot de passe'],
            'rep'  => "Connectez-vous via le bouton « Connexion » en haut.\n\nMot de passe oublié ? Contactez : support@rentify.ma 🔐"
        ],
        [
            'keys' => ['proprietaire', 'propriétaire', 'publier', 'owner'],
            'rep'  => "Vous êtes propriétaire ?\n1. Créez un compte Propriétaire\n2. Publiez vos machines\n3. Gérez les demandes depuis votre tableau de bord 💼"
        ],
        [
            'keys' => ['avis', 'note', 'notation', 'evaluation', 'rating'],
            'rep'  => "Après chaque location, vous pouvez noter la machine.\n\nLes avis aident la communauté ⭐"
        ],
        [
            'keys' => ['support', 'aide', 'help', 'contact', 'probleme', 'problème'],
            'rep'  => "Notre équipe support :\n\n📧 support@rentify.ma\n📞 +212 600 000 000\n⏰ Lun–Ven : 9h–18h"
        ],
        [
            'keys' => ['bonjour', 'bonsoir', 'salut', 'hello', 'salam'],
            'rep'  => "Bonjour ! 👋 Je suis l'assistant Rentify.\n\nJe peux vous aider avec les réservations, les machines, votre compte...\n\nQue puis-je faire pour vous ? 😊"
        ],
        [
            'keys' => ['merci', 'thank'],
            'rep'  => "Avec plaisir ! 😊 Bonne location sur Rentify ! 🏗️"
        ],
    ];

    public function repondre(Request $request)
    {
        $request->validate(['message' => 'required|string|max:500']);

        $msg     = mb_strtolower(trim($request->input('message')));
        $reponse = $this->trouverReponse($msg);

        return response()->json([
            'reponse' => $reponse,
            'heure'   => now()->format('H:i')
        ]);
    }

    private function trouverReponse(string $msg): string
    {
        $msgNorm = $this->normaliser($msg);

        foreach ($this->kb as $item) {
            foreach ($item['keys'] as $keyword) {
                if (str_contains($msgNorm, $this->normaliser($keyword))) {
                    return $item['rep'];
                }
            }
        }

        return "Je n'ai pas bien compris 🤔\n\nEssayez :\n• « Comment réserver ? »\n• « Types de machines ? »\n• « Contacter le support »";
    }

    private function normaliser(string $texte): string
    {
        $from = ['é','è','ê','ë','à','â','ä','ù','û','ü','î','ï','ô','ö','ç','É','È','Ê','À','Â','Ù','Û','Î','Ô','Ç'];
        $to   = ['e','e','e','e','a','a','a','u','u','u','i','i','o','o','c','e','e','e','a','a','u','u','i','o','c'];
        return str_replace($from, $to, mb_strtolower($texte));
    }
}