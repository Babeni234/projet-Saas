<?php

namespace Nangue\Support;

class DemoData
{
    public static function userStats(): array
    {
        return [
            'properties' => 3,
            'propertiesChange' => '+1 ce mois',
            'publications' => 5,
            'publicationsChange' => '+2 actives',
            'views' => '1 248',
            'viewsChange' => '+18 % vs mois dernier',
            'messages' => 7,
            'messagesChange' => '3 non lus',
        ];
    }

    public static function landlordStats(): array
    {
        return [
            'properties' => 24,
            'propertiesChange' => '+2 ce trimestre',
            'publications' => 18,
            'publicationsChange' => '12 actives',
            'revenue' => '42 800 €',
            'revenueChange' => '+6,2 % ce mois',
            'tenants' => 19,
            'tenantsChange' => 'Tous avec bail en cours',
            'messages' => 14,
            'messagesChange' => '5 demandes de visite',
        ];
    }

    public static function properties(): array
    {
        return [
            [
                'id' => 1,
                'title' => 'Appartement T3 — Centre-ville',
                'address' => '12 rue de la Paix, Lyon 69002',
                'type' => 'Location',
                'rooms' => 3,
                'price' => '950 € / mois',
                'status' => 'publié',
                'image' => null,
            ],
            [
                'id' => 2,
                'title' => 'Studio meublé — Étudiants',
                'address' => '5 av. Jean Jaurès, Villeurbanne',
                'type' => 'Location',
                'rooms' => 1,
                'price' => '520 € / mois',
                'status' => 'loué',
                'image' => null,
            ],
            [
                'id' => 3,
                'title' => 'Maison avec jardin',
                'address' => '8 chemin des Vignes, Caluire',
                'type' => 'Vente',
                'rooms' => 5,
                'price' => '385 000 €',
                'status' => 'brouillon',
                'image' => null,
            ],
        ];
    }

    public static function publications(): array
    {
        return [
            [
                'id' => 1,
                'title' => 'T3 lumineux — Lyon 2',
                'reference' => 'ANN-2026-0041',
                'type' => 'Location',
                'status' => 'active',
                'statusLabel' => 'En ligne',
                'views' => 342,
                'date' => '28 mai 2026',
            ],
            [
                'id' => 2,
                'title' => 'Studio Villeurbanne',
                'reference' => 'ANN-2026-0038',
                'type' => 'Location',
                'status' => 'active',
                'statusLabel' => 'En ligne',
                'views' => 198,
                'date' => '15 mai 2026',
            ],
            [
                'id' => 3,
                'title' => 'Maison Caluire',
                'reference' => 'ANN-2026-0029',
                'type' => 'Vente',
                'status' => 'pending',
                'statusLabel' => 'En validation',
                'views' => 56,
                'date' => '02 mai 2026',
            ],
        ];
    }

    public static function activities(): array
    {
        return [
            ['title' => 'Nouveau message pour « T3 lumineux »', 'time' => 'Il y a 2 h'],
            ['title' => 'Votre annonce a atteint 300 vues', 'time' => 'Hier'],
            ['title' => 'Brouillon « Maison Caluire » enregistré', 'time' => 'Il y a 3 jours'],
        ];
    }

    public static function company(): array
    {
        return [
            'name' => 'Immo Pro Gestion SARL',
            'legalForm' => 'Société de gestion immobilière',
            'siret' => '823 456 789 00012',
            'portfolio' => 24,
            'rating' => '4,8',
            'occupancy' => 94,
        ];
    }

    public static function tenants(): array
    {
        return [
            ['id' => 1, 'name' => 'Marie Dupont', 'initials' => 'MD', 'property' => 'T3 — Lyon 2', 'rent' => '950 €', 'paymentStatus' => 'à jour'],
            ['id' => 2, 'name' => 'Thomas Martin', 'initials' => 'TM', 'property' => 'Studio — Villeurbanne', 'rent' => '520 €', 'paymentStatus' => 'à jour'],
            ['id' => 3, 'name' => 'Sophie Bernard', 'initials' => 'SB', 'property' => 'T2 — Vénissieux', 'rent' => '720 €', 'paymentStatus' => 'en attente'],
        ];
    }

    public static function revenueChart(): array
    {
        return [
            ['label' => 'Jan', 'percent' => 55],
            ['label' => 'Fév', 'percent' => 62],
            ['label' => 'Mar', 'percent' => 70],
            ['label' => 'Avr', 'percent' => 68],
            ['label' => 'Mai', 'percent' => 85],
            ['label' => 'Juin', 'percent' => 92],
        ];
    }

    public static function alerts(): array
    {
        return [
            ['type' => 'warning', 'message' => '2 baux arrivent à échéance dans les 60 prochains jours.'],
            ['type' => 'info', 'message' => '3 quittances de juin sont prêtes à être envoyées.'],
        ];
    }
}
