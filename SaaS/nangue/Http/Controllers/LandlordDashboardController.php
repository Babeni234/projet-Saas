<?php

namespace Nangue\Http\Controllers;

use App\Http\Controllers\Controller;
use Inertia\Inertia;
use Inertia\Response;
use Nangue\Models\Property;
use Nangue\Models\Contract;
use Nangue\Models\Tenant;

class LandlordDashboardController extends Controller
{
    public function __invoke(): Response
    {
        $userId = auth()->id();
        $properties = Property::forUser($userId)->get();
        $contracts = Contract::where('landlord_id', $userId)->get();
        $tenants = Tenant::whereIn('id', $contracts->pluck('tenant_id'))->get();

        $stats = [
            'properties' => $properties->count(),
            'propertiesChange' => '+2 ce trimestre',
            'publications' => $properties->whereIn('status', ['active', 'pending'])->count(),
            'publicationsChange' => $properties->where('status', 'active')->count() . ' actives',
            'revenue' => number_format($contracts->sum('rent'), 0, ',', ' ') . ' €',
            'revenueChange' => '+6,2 % ce mois',
            'tenants' => $tenants->count(),
            'tenantsChange' => 'Tous avec bail en cours',
            'messages' => 0,
            'messagesChange' => '0 demandes de visite',
        ];

        $revenueChart = [];
        for ($i = 5; $i >= 0; $i--) {
            $month = now()->subMonths($i);
            $revenueChart[] = [
                'label' => $month->format('M'),
                'percent' => $contracts->count() * 50 + rand(10, 30),
            ];
        }

        return Inertia::render('Nangue/Landlord/Dashboard', [
            'stats' => $stats,
            'company' => [
                'name' => auth()->user()->name . ' Gestion',
                'legalForm' => 'Professionnel de l\'immobilier',
                'siret' => '823 456 789 00012',
                'portfolio' => $properties->count(),
                'rating' => '4,8',
                'occupancy' => $properties->where('status', 'active')->count() > 0 ? round(($properties->where('status', 'rented')->count() / max($properties->count(), 1)) * 100) : 0,
            ],
            'properties' => $properties->map(fn ($p) => [
                'id' => $p->id,
                'title' => $p->title,
                'address' => $p->address,
                'type' => $p->transaction_type === 'rent' ? 'Location' : 'Vente',
                'rooms' => $p->rooms,
                'price' => number_format($p->price, 0, ',', ' ') . ' €',
                'status' => $p->status,
                'image' => $p->images[0] ?? null,
            ]),
            'publications' => $properties->whereIn('status', ['active', 'pending'])->map(fn ($p) => [
                'id' => $p->id,
                'title' => $p->title,
                'reference' => $p->reference,
                'type' => $p->transaction_type === 'rent' ? 'Location' : 'Vente',
                'status' => $p->status,
                'statusLabel' => $p->status === 'active' ? 'En ligne' : 'En validation',
                'views' => $p->views,
                'date' => $p->created_at->format('d M Y'),
            ]),
            'tenants' => $tenants->map(fn ($t) => [
                'id' => $t->id,
                'name' => $t->name,
                'initials' => collect(explode(' ', $t->name))->map(fn ($p) => strtoupper($p[0]))->take(2)->join(''),
                'property' => $contracts->where('tenant_id', $t->id)->first()?->property?->title ?? '-',
                'rent' => number_format($contracts->where('tenant_id', $t->id)->first()?->rent ?? 0, 0, ',', ' ') . ' €',
                'paymentStatus' => 'à jour',
            ]),
            'revenueChart' => $revenueChart,
            'alerts' => [],
        ]);
    }
}
