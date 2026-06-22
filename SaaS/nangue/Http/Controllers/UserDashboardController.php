<?php

namespace Nangue\Http\Controllers;

use App\Http\Controllers\Controller;
use Inertia\Inertia;
use Inertia\Response;
use Nangue\Models\Property;
use Nangue\Models\Contract;

class UserDashboardController extends Controller
{
    public function __invoke(): Response
    {
        $userId = auth()->id();
        $properties = Property::forUser($userId)->get();
        $publications = Property::forUser($userId)->whereIn('status', ['active', 'pending'])->get();

        $stats = [
            'properties' => $properties->count(),
            'propertiesChange' => '+1 ce mois',
            'publications' => $publications->count(),
            'publicationsChange' => $publications->where('status', 'active')->count() . ' actives',
            'views' => number_format($properties->sum('views')),
            'viewsChange' => '+18 % vs mois dernier',
            'messages' => 0,
            'messagesChange' => '0 non lus',
        ];

        $activities = collect();
        if ($properties->isNotEmpty()) {
            $activities->push(['title' => 'Bien créé : ' . $properties->first()->title, 'time' => $properties->first()->created_at->diffForHumans()]);
        }

        return Inertia::render('Nangue/User/Dashboard', [
            'stats' => $stats,
            'properties' => $properties->map(fn ($p) => [
                'id' => $p->id,
                'title' => $p->title,
                'address' => $p->address,
                'type' => $p->transaction_type === 'rent' ? 'Location' : 'Vente',
                'rooms' => $p->rooms,
                'price' => number_format($p->price, 0, ',', ' ') . ' €' . ($p->transaction_type === 'rent' ? ' / mois' : ''),
                'status' => $p->status,
                'image' => $p->images[0] ?? null,
            ]),
            'publications' => $publications->map(fn ($p) => [
                'id' => $p->id,
                'title' => $p->title,
                'reference' => $p->reference,
                'type' => $p->transaction_type === 'rent' ? 'Location' : 'Vente',
                'status' => $p->status,
                'statusLabel' => $p->status === 'active' ? 'En ligne' : ($p->status === 'pending' ? 'En validation' : 'Brouillon'),
                'views' => $p->views,
                'date' => $p->created_at->format('d M Y'),
            ]),
            'activities' => $activities,
        ]);
    }
}
