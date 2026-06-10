<?php

namespace Nangue\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Inertia\Inertia;
use Inertia\Response;

class MessageController extends Controller
{
    public function index(): Response
    {
        $conversations = [
            [
                'id' => 1,
                'name' => 'Marie Dupont',
                'avatar' => null,
                'last_message' => 'Bonjour, je suis très intéressée par votre appartement. Serait-il possible de visiter samedi ?',
                'time' => '14:30',
                'type' => 'inquiry',
                'property' => 'Appartement T3 centre-ville',
                'property_url' => route('immo.property.show', 1),
                'unread' => 2,
                'online' => true,
            ],
            [
                'id' => 2,
                'name' => 'Pierre Martin',
                'avatar' => null,
                'last_message' => 'Merci pour votre réponse rapide. Je vous envoie mes documents.',
                'time' => 'Hier',
                'type' => 'application',
                'property' => 'Studio moderne',
                'property_url' => route('immo.property.show', 2),
                'unread' => 0,
                'online' => false,
            ],
            [
                'id' => 3,
                'name' => 'Sophie Bernard',
                'avatar' => null,
                'last_message' => 'Le loyer est-il négociable ?',
                'time' => '2 jours',
                'type' => 'general',
                'property' => null,
                'property_url' => null,
                'unread' => 0,
                'online' => false,
            ],
            [
                'id' => 4,
                'name' => 'Lucas Petit',
                'avatar' => null,
                'last_message' => 'Je confirme ma venue pour la visite de demain à 10h.',
                'time' => '3 jours',
                'type' => 'inquiry',
                'property' => 'Maison avec jardin',
                'property_url' => route('immo.property.show', 3),
                'unread' => 1,
                'online' => true,
            ],
        ];

        $selectedConversation = $conversations[0];

        $messages = [
            [
                'id' => 1,
                'content' => 'Bonjour, je suis très intéressée par votre appartement. Serait-il possible de visiter samedi ?',
                'time' => '14:25',
                'is_me' => false,
                'read' => true,
            ],
            [
                'id' => 2,
                'content' => 'Bonjour Marie, oui bien sûr ! Samedi à 14h ça vous convient ?',
                'time' => '14:28',
                'is_me' => true,
                'read' => true,
            ],
            [
                'id' => 3,
                'content' => 'Parfait, 14h samedi c\'est noté. À quelle adresse exactement ?',
                'time' => '14:30',
                'is_me' => false,
                'read' => false,
            ],
        ];

        return Inertia::render('Nangue/User/Messages', [
            'conversations' => $conversations,
            'selectedConversation' => $selectedConversation,
            'messages' => $messages,
        ]);
    }

    public function show($id): Response
    {
        // Logique pour afficher une conversation spécifique
        return Inertia::render('Nangue/User/Messages', [
            'conversations' => [],
            'selectedConversation' => [],
            'messages' => [],
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'conversation_id' => 'required|exists:conversations,id',
            'content' => 'required|string|max:2000',
        ]);

        // Logique d'envoi de message
        // Message::create($validated);

        return back();
    }

    public function markAsRead($id)
    {
        // Logique pour marquer comme lu
        return back();
    }
}
