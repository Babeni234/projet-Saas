<?php

namespace App\Services\Ai;

use App\Models\Contract;
use App\Models\Property;
use App\Models\Receipt;
use App\Models\Tenant;
use App\Models\Visit;
use App\Models\Incident;

class AiTools
{
    protected int $userId;

    public function __construct(int $userId)
    {
        $this->userId = $userId;
    }

    public function listTools(): array
    {
        return [
            [
                'name' => 'get_properties',
                'description' => 'Liste des biens du bailleur avec statut (loué/libre) et nombre de locataires',
                'parameters' => ['type' => 'object', 'properties' => []],
            ],
            [
                'name' => 'get_tenants',
                'description' => 'Liste des locataires actifs et leurs contrats',
                'parameters' => ['type' => 'object', 'properties' => []],
            ],
            [
                'name' => 'get_contracts',
                'description' => 'Affiche les contrats actifs, à échéance, et leurs montants',
                'parameters' => ['type' => 'object', 'properties' => []],
            ],
            [
                'name' => 'get_finances',
                'description' => 'Revenus mensuels, quittances impayées ou en retard',
                'parameters' => ['type' => 'object', 'properties' => []],
            ],
            [
                'name' => 'get_visits',
                'description' => 'Visites à venir, planifiées ou récentes',
                'parameters' => ['type' => 'object', 'properties' => []],
            ],
            [
                'name' => 'get_incidents',
                'description' => 'Demandes d\'intervention en cours des locataires',
                'parameters' => ['type' => 'object', 'properties' => []],
            ],
            [
                'name' => 'get_alerts',
                'description' => 'Alertes importantes : impayés, visites annulées, documents expirés',
                'parameters' => ['type' => 'object', 'properties' => []],
            ],
            [
                'name' => 'get_all_overview',
                'description' => 'Résumé complet de tout : biens, locataires, contrats, finances, alertes',
                'parameters' => ['type' => 'object', 'properties' => []],
            ],
        ];
    }

    public function execute(string $toolName, array $args = []): array
    {
        return match ($toolName) {
            'get_properties' => $this->getProperties(),
            'get_tenants' => $this->getTenants(),
            'get_contracts' => $this->getContracts(),
            'get_finances' => $this->getFinances(),
            'get_visits' => $this->getVisits(),
            'get_incidents' => $this->getIncidents(),
            'get_alerts' => $this->getAlerts(),
            'get_all_overview' => $this->getAllOverview(),
            default => ['success' => false, 'error' => "Outil inconnu : $toolName"],
        };
    }

    protected function getProperties(): array
    {
        $properties = Property::where('user_id', $this->userId)
            ->withCount(['contracts as active_contracts' => fn($q) => $q->where('status', 'active')])
            ->get();

        return [
            'success' => true,
            'data' => [
                'total' => $properties->count(),
                'rented' => $properties->filter(fn($p) => $p->status === 'rented' || $p->active_contracts > 0)->count(),
                'available' => $properties->filter(fn($p) => $p->status !== 'rented' && $p->active_contracts === 0)->count(),
                'list' => $properties->map(fn($p) => [
                    'id' => $p->id,
                    'title' => $p->title,
                    'city' => $p->city,
                    'type' => $p->type,
                    'rent' => $p->rent_amount,
                    'status' => $p->active_contracts > 0 ? 'Loué' : ($p->status === 'rented' ? 'Loué' : 'Libre'),
                    'tenant_count' => $p->active_contracts,
                ]),
            ],
        ];
    }

    protected function getTenants(): array
    {
        $tenants = Tenant::where('user_id', $this->userId)
            ->with(['contracts' => fn($q) => $q->with('property'), 'contracts' => fn($q) => $q->where('status', 'active')])
            ->get();

        return [
            'success' => true,
            'data' => [
                'total' => $tenants->count(),
                'active' => $tenants->filter(fn($t) => $t->contracts->isNotEmpty())->count(),
                'list' => $tenants->map(fn($t) => [
                    'id' => $t->id,
                    'name' => $t->name,
                    'email' => $t->email,
                    'phone' => $t->phone,
                    'property' => $t->contracts->first()?->property->title ?? 'Aucun',
                    'contract_status' => $t->contracts->first()?->status ?? 'Sans contrat',
                ]),
            ],
        ];
    }

    protected function getContracts(): array
    {
        $contracts = Contract::whereHas('property', fn($q) => $q->where('user_id', $this->userId))
            ->with('property', 'tenant')
            ->get();

        $now = now();
        $endingSoon = $contracts->filter(fn($c) => $c->status === 'active' && $c->end_date && $c->end_date->diffInDays($now, false) >= -30 && $c->end_date->diffInDays($now, false) <= 30);

        return [
            'success' => true,
            'data' => [
                'total' => $contracts->count(),
                'active' => $contracts->where('status', 'active')->count(),
                'ending_soon' => $endingSoon->count(),
                'monthly_revenue' => $contracts->where('status', 'active')->sum('rent_amount'),
                'list' => $contracts->map(fn($c) => [
                    'id' => $c->id,
                    'property' => $c->property->title ?? '-',
                    'tenant' => $c->tenant->name ?? '-',
                    'rent' => $c->rent_amount,
                    'charges' => $c->charges,
                    'total' => $c->rent_amount + $c->charges,
                    'start' => $c->start_date->format('d/m/Y'),
                    'end' => $c->end_date?->format('d/m/Y') ?? 'Indéterminé',
                    'status' => $c->status,
                ]),
            ],
        ];
    }

    protected function getFinances(): array
    {
        $receipts = Receipt::whereHas('contract.property', fn($q) => $q->where('user_id', $this->userId))
            ->with('contract.property')
            ->orderBy('created_at', 'desc')
            ->get();

        $monthlyPaid = $receipts->where('status', 'paid')
            ->whereMonth('created_at', now()->month)
            ->sum('total');

        $overdue = $receipts->where('status', 'overdue');
        $pending = $receipts->where('status', 'pending');

        return [
            'success' => true,
            'data' => [
                'monthly_paid' => $monthlyPaid,
                'overdue_count' => $overdue->count(),
                'overdue_total' => $overdue->sum('total'),
                'pending_count' => $pending->count(),
                'pending_total' => $pending->sum('total'),
                'total_receipts' => $receipts->count(),
                'overdue_list' => $overdue->map(fn($r) => [
                    'reference' => $r->reference,
                    'period' => $r->period,
                    'total' => $r->total,
                    'property' => $r->contract->property->title ?? '-',
                ])->values(),
            ],
        ];
    }

    protected function getVisits(): array
    {
        $visits = Visit::with('property')
            ->where('landlord_id', $this->userId)
            ->orderBy('date', 'desc')
            ->get();

        $upcoming = $visits->where('date', '>=', now()->format('Y-m-d'))->where('status', 'scheduled');

        return [
            'success' => true,
            'data' => [
                'total' => $visits->count(),
                'upcoming' => $upcoming->count(),
                'completed' => $visits->where('status', 'completed')->count(),
                'cancelled' => $visits->where('status', 'cancelled')->count(),
                'upcoming_list' => $upcoming->map(fn($v) => [
                    'id' => $v->id,
                    'property' => $v->property->title ?? '-',
                    'visitor' => $v->visitor_name,
                    'date' => $v->date,
                    'time' => $v->time,
                    'status' => $v->status,
                ])->values(),
            ],
        ];
    }

    protected function getIncidents(): array
    {
        $incidents = Incident::with('property')
            ->where('landlord_id', $this->userId)
            ->orderBy('created_at', 'desc')
            ->get();

        return [
            'success' => true,
            'data' => [
                'total' => $incidents->count(),
                'open' => $incidents->whereIn('status', ['reported', 'in_progress'])->count(),
                'resolved' => $incidents->where('status', 'resolved')->count(),
                'urgent' => $incidents->where('urgency', 'emergency')->whereIn('status', ['reported', 'in_progress'])->count(),
                'open_list' => $incidents->whereIn('status', ['reported', 'in_progress'])->map(fn($i) => [
                    'id' => $i->id,
                    'title' => $i->title,
                    'property' => $i->property->title ?? '-',
                    'category' => $i->category,
                    'urgency' => $i->urgency,
                    'status' => $i->status,
                ])->values(),
            ],
        ];
    }

    protected function getAlerts(): array
    {
        $alerts = [];

        $overdue = Receipt::whereHas('contract.property', fn($q) => $q->where('user_id', $this->userId))
            ->where('status', 'overdue')
            ->count();
        if ($overdue > 0) $alerts[] = "$overdue quittance(s) en retard";

        $incidents = Incident::where('landlord_id', $this->userId)
            ->where('urgency', 'emergency')
            ->whereIn('status', ['reported', 'in_progress'])
            ->count();
        if ($incidents > 0) $alerts[] = "$incidents urgence(s) signalée(s)";

        $contractsEnding = Contract::whereHas('property', fn($q) => $q->where('user_id', $this->userId))
            ->where('status', 'active')
            ->whereNotNull('end_date')
            ->whereBetween('end_date', [now(), now()->addDays(30)])
            ->count();
        if ($contractsEnding > 0) $alerts[] = "$contractsEnding contrat(s) se terminent dans moins de 30 jours";

        return [
            'success' => true,
            'data' => [
                'alert_count' => count($alerts),
                'alerts' => $alerts,
            ],
        ];
    }

    protected function getAllOverview(): array
    {
        return [
            'success' => true,
            'data' => [
                'properties' => $this->getProperties()['data'],
                'tenants' => $this->getTenants()['data'],
                'contracts' => $this->getContracts()['data'],
                'finances' => $this->getFinances()['data'],
                'visits' => $this->getVisits()['data'],
                'incidents' => $this->getIncidents()['data'],
                'alerts' => $this->getAlerts()['data'],
            ],
        ];
    }
}
