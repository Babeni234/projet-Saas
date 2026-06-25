<?php

namespace App\Services\Ai;

use App\Models\AutomationRule;
use App\Models\Contract;
use App\Models\Incident;
use App\Models\IncidentComment;
use App\Models\Portfolio;
use App\Models\Property;
use App\Models\Receipt;
use App\Models\Tenant;
use App\Models\TeamInvitation;
use App\Models\Visit;
use App\Models\Document;
use App\Models\Workflow;
use App\Models\WorkflowStep;
use App\Models\DocumentFolder;
use App\Models\DocumentShare;
use App\Models\BankAccount;
use App\Models\AccountingCategory;
use App\Models\Transaction;
use App\Models\Budget;

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
            // ── Lecture ──
            ['name' => 'get_overview', 'description' => 'Résumé complet : biens, locataires, contrats, finances, visites, incidents, alertes', 'parameters' => ['type' => 'object', 'properties' => []]],
            ['name' => 'get_properties', 'description' => 'Liste détaillée des biens avec statut, loyer, surface, locataires', 'parameters' => ['type' => 'object', 'properties' => []]],
            ['name' => 'get_tenants', 'description' => 'Liste des locataires avec email, téléphone, contrat, bien', 'parameters' => ['type' => 'object', 'properties' => []]],
            ['name' => 'get_contracts', 'description' => 'Contrats actifs, échéances, montants, locataires associés', 'parameters' => ['type' => 'object', 'properties' => []]],
            ['name' => 'get_finances', 'description' => 'Revenus mensuels, impayés, quittances en attente/en retard', 'parameters' => ['type' => 'object', 'properties' => []]],
            ['name' => 'get_visits', 'description' => 'Visites à venir, passées, annulées avec détails', 'parameters' => ['type' => 'object', 'properties' => []]],
            ['name' => 'get_incidents', 'description' => 'Demandes d\'intervention en cours, urgentes, résolues', 'parameters' => ['type' => 'object', 'properties' => []]],
            ['name' => 'get_alerts', 'description' => 'Tout ce qui nécessite attention : impayés, urgences, contrats qui expirent', 'parameters' => ['type' => 'object', 'properties' => []]],
            ['name' => 'search_documents', 'description' => 'Chercher dans les documents (baux, DPE, diagnostics) par mot-clé', 'parameters' => ['type' => 'object', 'properties' => ['query' => ['type' => 'string', 'description' => 'Mot-clé recherché']], 'required' => ['query']]],
            ['name' => 'search_all', 'description' => 'Recherche intelligente dans tous les éléments (biens, locataires, contrats, documents)', 'parameters' => ['type' => 'object', 'properties' => ['query' => ['type' => 'string']], 'required' => ['query']]],

            // ── Actions (CRUD) ──
            ['name' => 'create_property', 'description' => 'Créer un nouveau bien', 'parameters' => ['type' => 'object', 'properties' => ['title' => ['type' => 'string'], 'type' => ['type' => 'string'], 'address' => ['type' => 'string'], 'city' => ['type' => 'string'], 'rent_amount' => ['type' => 'number'], 'surface' => ['type' => 'number', 'optional' => true], 'rooms' => ['type' => 'integer', 'optional' => true], 'description' => ['type' => 'string', 'optional' => true]], 'required' => ['title', 'type', 'address', 'city', 'rent_amount']]],
            ['name' => 'update_incident_status', 'description' => 'Changer le statut d\'un incident/demande d\'intervention', 'parameters' => ['type' => 'object', 'properties' => ['incident_id' => ['type' => 'integer'], 'status' => ['type' => 'string', 'enum' => ['reported', 'in_progress', 'resolved', 'closed']], 'resolution_notes' => ['type' => 'string', 'optional' => true]], 'required' => ['incident_id', 'status']]],
            ['name' => 'add_incident_comment', 'description' => 'Ajouter un commentaire à un incident', 'parameters' => ['type' => 'object', 'properties' => ['incident_id' => ['type' => 'integer'], 'content' => ['type' => 'string']], 'required' => ['incident_id', 'content']]],
            ['name' => 'mark_receipt_paid', 'description' => 'Marquer une quittance comme payée', 'parameters' => ['type' => 'object', 'properties' => ['receipt_id' => ['type' => 'integer'], 'payment_date' => ['type' => 'string', 'optional' => true]], 'required' => ['receipt_id']]],
            ['name' => 'generate_report', 'description' => 'Générer un rapport analytique complet avec tendances, risques et recommandations', 'parameters' => ['type' => 'object', 'properties' => ['focus' => ['type' => 'string', 'enum' => ['finances', 'occupation', 'incidents', 'global'], 'optional' => true]], 'required' => []]],

            // ── Pro : Portfolios ──
            ['name' => 'get_portfolios', 'description' => 'Liste des portfolios (groupes de biens) avec nombre de biens', 'parameters' => ['type' => 'object', 'properties' => []]],
            ['name' => 'create_portfolio', 'description' => 'Créer un nouveau portfolio pour grouper des biens', 'parameters' => ['type' => 'object', 'properties' => ['name' => ['type' => 'string'], 'description' => ['type' => 'string', 'optional' => true], 'color' => ['type' => 'string', 'optional' => true]], 'required' => ['name']]],

            // ── Pro : Équipe ──
            ['name' => 'get_team', 'description' => 'Liste des membres de l\'équipe et invitations en attente', 'parameters' => ['type' => 'object', 'properties' => []]],
            ['name' => 'invite_team_member', 'description' => 'Inviter un collaborateur par email', 'parameters' => ['type' => 'object', 'properties' => ['email' => ['type' => 'string'], 'name' => ['type' => 'string', 'optional' => true], 'role' => ['type' => 'string', 'enum' => ['agent', 'viewer', 'manager'], 'optional' => true]], 'required' => ['email']]],

            // ── Pro : Automatisation ──
            ['name' => 'get_automation_rules', 'description' => 'Liste des règles d\'automatisation avec statut et logs', 'parameters' => ['type' => 'object', 'properties' => []]],
            ['name' => 'toggle_automation_rule', 'description' => 'Activer ou désactiver une règle d\'automatisation', 'parameters' => ['type' => 'object', 'properties' => ['rule_id' => ['type' => 'integer'], 'active' => ['type' => 'boolean']], 'required' => ['rule_id', 'active']]],

            // ── Pro : Rapport Pro ──
            ['name' => 'generate_pro_report', 'description' => 'Rapport Pro complet avec KPIs, tendances, portfolios et recommandations avancées', 'parameters' => ['type' => 'object', 'properties' => ['focus' => ['type' => 'string', 'enum' => ['finances', 'portfolios', 'team', 'global'], 'optional' => true]], 'required' => []]],

            // ── Pro : Workflows ──
            ['name' => 'get_workflows', 'description' => 'Liste des workflows avec nombre d\'étapes et statut', 'parameters' => ['type' => 'object', 'properties' => []]],
            ['name' => 'run_workflow', 'description' => 'Exécuter un workflow manuellement', 'parameters' => ['type' => 'object', 'properties' => ['workflow_id' => ['type' => 'integer']], 'required' => ['workflow_id']]],
            ['name' => 'get_workflow_templates', 'description' => 'Liste des templates de workflow disponibles', 'parameters' => ['type' => 'object', 'properties' => []]],
            ['name' => 'create_workflow_from_template', 'description' => 'Créer un workflow depuis un template prédéfini', 'parameters' => ['type' => 'object', 'properties' => ['template_id' => ['type' => 'integer'], 'name' => ['type' => 'string', 'optional' => true]], 'required' => ['template_id']]],

            // ── Pro : Comptabilité ──
            ['name' => 'get_accounting_summary', 'description' => 'Résumé financier : soldes, revenus/dépenses du mois, budgets', 'parameters' => ['type' => 'object', 'properties' => []]],
            ['name' => 'get_bank_accounts', 'description' => 'Liste des comptes bancaires avec solde', 'parameters' => ['type' => 'object', 'properties' => []]],
            ['name' => 'get_transactions', 'description' => 'Dernières transactions avec filtre optionnel (type, statut, catégorie)', 'parameters' => ['type' => 'object', 'properties' => ['type' => ['type' => 'string', 'optional' => true], 'status' => ['type' => 'string', 'optional' => true], 'limit' => ['type' => 'integer', 'optional' => true]], 'required' => []]],
            ['name' => 'record_transaction', 'description' => 'Enregistrer une transaction (revenu/dépense) sur un compte bancaire', 'parameters' => ['type' => 'object', 'properties' => ['bank_account_id' => ['type' => 'integer'], 'type' => ['type' => 'string', 'enum' => ['income', 'expense']], 'amount' => ['type' => 'number'], 'description' => ['type' => 'string', 'optional' => true], 'category_id' => ['type' => 'integer', 'optional' => true], 'transaction_date' => ['type' => 'string', 'optional' => true]], 'required' => ['bank_account_id', 'type', 'amount']]],

            // ── Pro : GED ──
            ['name' => 'get_ged_summary', 'description' => 'Résumé de la GED : nombre de dossiers, documents, favoris', 'parameters' => ['type' => 'object', 'properties' => []]],
            ['name' => 'search_ged_documents', 'description' => 'Rechercher des documents dans la GED par mot-clé', 'parameters' => ['type' => 'object', 'properties' => ['query' => ['type' => 'string']], 'required' => ['query']]],

            // ── Génération ──
            ['name' => 'generate_description', 'description' => 'Générer une description d\'annonce pour un bien', 'parameters' => ['type' => 'object', 'properties' => ['property_id' => ['type' => 'integer']], 'required' => ['property_id']]],
        ];
    }

    public function execute(string $toolName, array $args = []): array
    {
        return match ($toolName) {
            'get_overview' => $this->getAllOverview(),
            'get_properties' => $this->getProperties(),
            'get_tenants' => $this->getTenants(),
            'get_contracts' => $this->getContracts(),
            'get_finances' => $this->getFinances(),
            'get_visits' => $this->getVisits(),
            'get_incidents' => $this->getIncidents(),
            'get_alerts' => $this->getAlerts(),
            'search_documents' => $this->searchDocuments($args['query'] ?? ''),
            'search_all' => $this->searchAll($args['query'] ?? ''),
            'get_portfolios' => $this->getPortfolios(),
            'create_portfolio' => $this->createPortfolio($args),
            'get_team' => $this->getTeam(),
            'invite_team_member' => $this->inviteTeamMember($args),
            'get_automation_rules' => $this->getAutomationRules(),
            'toggle_automation_rule' => $this->toggleAutomationRule($args['rule_id'], $args['active'] ?? true),
            'generate_pro_report' => $this->generateProReport($args['focus'] ?? 'global'),
            'get_workflows' => $this->getWorkflows(),
            'run_workflow' => $this->runWorkflow($args['workflow_id']),
            'get_workflow_templates' => $this->getWorkflowTemplates(),
            'create_workflow_from_template' => $this->createWorkflowFromTemplate($args['template_id'], $args['name'] ?? null),
            'get_accounting_summary' => $this->getAccountingSummary(),
            'get_bank_accounts' => $this->getBankAccounts(),
            'get_transactions' => $this->getTransactions($args['type'] ?? null, $args['status'] ?? null, $args['limit'] ?? 10),
            'record_transaction' => $this->recordTransaction($args),
            'get_ged_summary' => $this->getGedSummary(),
            'search_ged_documents' => $this->searchGedDocuments($args['query']),
            'create_property' => $this->createProperty($args),
            'update_incident_status' => $this->updateIncidentStatus($args['incident_id'], $args['status'], $args['resolution_notes'] ?? null),
            'add_incident_comment' => $this->addIncidentComment($args['incident_id'], $args['content']),
            'mark_receipt_paid' => $this->markReceiptPaid($args['receipt_id'], $args['payment_date'] ?? null),
            'generate_report' => $this->generateReport($args['focus'] ?? 'global'),
            'generate_description' => $this->generateDescription($args['property_id']),
            default => ['success' => false, 'error' => "Outil inconnu: $toolName"],
        };
    }

    // ═══════════════════════════════
    //  LECTURE
    // ═══════════════════════════════

    protected function getProperties(): array
    {
        $properties = Property::where('user_id', $this->userId)
            ->withCount(['contracts as active_contracts' => fn($q) => $q->where('status', 'active')])
            ->get();

        return [ 'success' => true, 'data' => [
            'total' => $properties->count(),
            'rented' => $properties->filter(fn($p) => $p->active_contracts > 0)->count(),
            'available' => $properties->filter(fn($p) => $p->active_contracts === 0)->count(),
            'monthly_revenue' => $properties->sum('rent_amount'),
            'list' => $properties->map(fn($p) => [
                'id' => $p->id, 'title' => $p->title, 'type' => $p->type,
                'city' => $p->city, 'address' => $p->address,
                'surface' => $p->surface, 'rooms' => $p->rooms,
                'rent' => (float)$p->rent_amount, 'charges' => (float)$p->charges,
                'status' => $p->active_contracts > 0 ? 'Loué' : 'Libre',
                'tenant_count' => $p->active_contracts,
                'created_at' => $p->created_at->format('d/m/Y'),
            ]),
        ]];
    }

    protected function getTenants(): array
    {
        $tenants = Tenant::where('user_id', $this->userId)
            ->with(['contracts' => fn($q) => $q->with('property')->where('status', 'active')])
            ->get();

        return [ 'success' => true, 'data' => [
            'total' => $tenants->count(),
            'active' => $tenants->filter(fn($t) => $t->contracts->isNotEmpty())->count(),
            'list' => $tenants->map(fn($t) => [
                'id' => $t->id, 'name' => $t->name, 'email' => $t->email,
                'phone' => $t->phone, 'property' => $t->contracts->first()?->property->title ?? 'Aucun',
                'property_id' => $t->contracts->first()?->property->id,
                'contract_status' => $t->contracts->first()?->status ?? 'Sans contrat',
                'contract_id' => $t->contracts->first()?->id,
                'has_portal' => $t->tenantUser()->exists(),
            ]),
        ]];
    }

    protected function getContracts(): array
    {
        $contracts = Contract::whereHas('property', fn($q) => $q->where('user_id', $this->userId))
            ->with('property', 'tenant')->get();

        $endingSoon = $contracts->filter(fn($c) => $c->status === 'active' && $c->end_date && $c->end_date->isFuture() && $c->end_date->diffInDays(now()) <= 60);

        return [ 'success' => true, 'data' => [
            'total' => $contracts->count(), 'active' => $contracts->where('status', 'active')->count(),
            'ending_soon' => $endingSoon->count(),
            'monthly_revenue' => $contracts->where('status', 'active')->sum('rent_amount'),
            'list' => $contracts->map(fn($c) => [
                'id' => $c->id, 'property' => $c->property->title ?? '-', 'property_id' => $c->property_id,
                'tenant' => $c->tenant->name ?? '-', 'tenant_id' => $c->tenant_id,
                'rent' => (float)$c->rent_amount, 'charges' => (float)$c->charges,
                'total' => (float)($c->rent_amount + $c->charges),
                'deposit' => (float)$c->deposit, 'start' => $c->start_date->format('d/m/Y'),
                'end' => $c->end_date?->format('d/m/Y') ?? 'Indéterminé', 'status' => $c->status,
            ]),
        ]];
    }

    protected function getFinances(): array
    {
        $receipts = Receipt::whereHas('contract.property', fn($q) => $q->where('user_id', $this->userId))
            ->with('contract.property')->orderBy('created_at', 'desc')->get();

        return [ 'success' => true, 'data' => [
            'monthly_paid' => $receipts->where('status', 'paid')->filter(fn($r) => $r->created_at->month === now()->month)->sum('total'),
            'yearly_paid' => $receipts->where('status', 'paid')->filter(fn($r) => $r->created_at->year === now()->year)->sum('total'),
            'overdue_count' => $receipts->where('status', 'overdue')->count(),
            'overdue_total' => $receipts->where('status', 'overdue')->sum('total'),
            'pending_count' => $receipts->where('status', 'pending')->count(),
            'pending_total' => $receipts->where('status', 'pending')->sum('total'),
            'paid_count' => $receipts->where('status', 'paid')->count(),
            'paid_total' => $receipts->where('status', 'paid')->sum('total'),
            'overdue_list' => $receipts->where('status', 'overdue')->map(fn($r) => [
                'id' => $r->id, 'reference' => $r->reference, 'period' => $r->period,
                'total' => (float)$r->total, 'due_date' => $r->due_date->format('d/m/Y'),
                'property' => $r->contract->property->title ?? '-',
            ])->values(),
        ]];
    }

    protected function getVisits(): array
    {
        $visits = Visit::with('property')->where('landlord_id', $this->userId)->orderBy('date', 'desc')->get();
        $upcoming = $visits->where('date', '>=', now()->format('Y-m-d'))->where('status', 'scheduled');

        return [ 'success' => true, 'data' => [
            'total' => $visits->count(), 'upcoming' => $upcoming->count(),
            'past' => $visits->where('date', '<', now()->format('Y-m-d'))->count(),
            'completed' => $visits->where('status', 'completed')->count(),
            'cancelled' => $visits->where('status', 'cancelled')->count(),
            'upcoming_list' => $upcoming->map(fn($v) => [
                'id' => $v->id, 'property' => $v->property->title ?? '-',
                'visitor' => $v->visitor_name, 'phone' => $v->visitor_phone,
                'date' => $v->date, 'time' => $v->time, 'status' => $v->status,
            ])->values(),
        ]];
    }

    protected function getIncidents(): array
    {
        $incidents = Incident::with('property')->where('landlord_id', $this->userId)->orderBy('created_at', 'desc')->get();

        return [ 'success' => true, 'data' => [
            'total' => $incidents->count(),
            'open' => $incidents->whereIn('status', ['reported', 'in_progress'])->count(),
            'resolved' => $incidents->where('status', 'resolved')->count(),
            'urgent' => $incidents->where('urgency', 'emergency')->whereIn('status', ['reported', 'in_progress'])->count(),
            'open_list' => $incidents->whereIn('status', ['reported', 'in_progress'])->map(fn($i) => [
                'id' => $i->id, 'title' => $i->title, 'description' => $i->description,
                'property' => $i->property->title ?? '-', 'category' => $i->category,
                'urgency' => $i->urgency, 'status' => $i->status,
                'created_at' => $i->created_at->format('d/m/Y'),
            ])->values(),
        ]];
    }

    protected function getAlerts(): array
    {
        $alerts = [];
        $countOverdue = Receipt::whereHas('contract.property', fn($q) => $q->where('user_id', $this->userId))->where('status', 'overdue')->count();
        if ($countOverdue > 0) $alerts[] = ['type' => 'overdue', 'severity' => 'high', 'message' => "$countOverdue quittance(s) en retard de paiement"];

        $countUrgent = Incident::where('landlord_id', $this->userId)->where('urgency', 'emergency')->whereIn('status', ['reported', 'in_progress'])->count();
        if ($countUrgent > 0) $alerts[] = ['type' => 'emergency', 'severity' => 'urgent', 'message' => "$countUrgent urgence(s) signalée(s)"];

        $countEnding = Contract::whereHas('property', fn($q) => $q->where('user_id', $this->userId))->where('status', 'active')->whereNotNull('end_date')->whereBetween('end_date', [now(), now()->addDays(60)])->count();
        if ($countEnding > 0) $alerts[] = ['type' => 'contract_ending', 'severity' => 'medium', 'message' => "$countEnding contrat(s) se terminent dans moins de 60 jours"];

        $pendingVisits = Visit::where('landlord_id', $this->userId)->where('date', '>=', now()->format('Y-m-d'))->where('status', 'scheduled')->count();
        if ($pendingVisits > 0) $alerts[] = ['type' => 'upcoming_visits', 'severity' => 'info', 'message' => "$pendingVisits visite(s) à venir"];

        return [ 'success' => true, 'data' => ['count' => count($alerts), 'alerts' => $alerts]];
    }

    protected function searchDocuments(string $query): array
    {
        $docs = Document::where(function ($q) use ($query) {
            $q->where('name', 'like', "%{$query}%")
              ->orWhere('notes', 'like', "%{$query}%");
        })->with('category')->get();

        return [ 'success' => true, 'data' => [
            'count' => $docs->count(),
            'results' => $docs->map(fn($d) => [
                'id' => $d->id, 'name' => $d->name, 'type' => $d->file_type,
                'category' => $d->category?->name ?? 'Général',
                'expires_at' => $d->expires_at?->format('d/m/Y'),
                'verified' => $d->verified,
            ]),
        ]];
    }

    protected function searchAll(string $query): array
    {
        $properties = Property::where('user_id', $this->userId)
            ->where(function ($q) use ($query) {
                $q->where('title', 'like', "%{$query}%")
                  ->orWhere('description', 'like', "%{$query}%")
                  ->orWhere('address', 'like', "%{$query}%")
                  ->orWhere('city', 'like', "%{$query}%");
            })->get();

        $tenants = Tenant::where('user_id', $this->userId)
            ->where(function ($q) use ($query) {
                $q->where('name', 'like', "%{$query}%")
                  ->orWhere('email', 'like', "%{$query}%")
                  ->orWhere('phone', 'like', "%{$query}%");
            })->get();

        return [ 'success' => true, 'data' => [
            'properties_count' => $properties->count(),
            'tenants_count' => $tenants->count(),
            'properties' => $properties->map(fn($p) => ['id' => $p->id, 'title' => $p->title, 'type' => 'property']),
            'tenants' => $tenants->map(fn($t) => ['id' => $t->id, 'name' => $t->name, 'type' => 'tenant']),
        ]];
    }

    // ═══════════════════════════════
    //  ACTIONS (CRUD)
    // ═══════════════════════════════

    protected function createProperty(array $data): array
    {
        $data['user_id'] = $this->userId;
        $property = Property::create($data);
        return [ 'success' => true, 'data' => ['id' => $property->id, 'title' => $property->title, 'message' => 'Bien créé avec succès.']];
    }

    protected function updateIncidentStatus(int $id, string $status, ?string $notes): array
    {
        $incident = Incident::where('landlord_id', $this->userId)->findOrFail($id);
        $incident->update(['status' => $status, 'resolution_notes' => $notes, 'resolved_at' => in_array($status, ['resolved', 'closed']) ? now() : $incident->resolved_at]);
        return [ 'success' => true, 'data' => ['id' => $incident->id, 'status' => $status, 'message' => "Incident #$id mis à jour: $status"]];
    }

    protected function addIncidentComment(int $incidentId, string $content): array
    {
        $incident = Incident::where('landlord_id', $this->userId)->findOrFail($incidentId);
        $comment = IncidentComment::create(['incident_id' => $incidentId, 'user_id' => $this->userId, 'content' => $content]);
        return [ 'success' => true, 'data' => ['id' => $comment->id, 'message' => 'Commentaire ajouté.']];
    }

    protected function markReceiptPaid(int $receiptId, ?string $paymentDate): array
    {
        $receipt = Receipt::whereHas('contract.property', fn($q) => $q->where('user_id', $this->userId))->findOrFail($receiptId);
        $receipt->update(['status' => 'paid', 'payment_date' => $paymentDate ?? now()->format('Y-m-d')]);
        return [ 'success' => true, 'data' => ['id' => $receipt->id, 'message' => "Quittance #$receiptId marquée payée."]];
    }

    // ═══════════════════════════════
    //  ANALYTIQUE & GÉNÉRATION
    // ═══════════════════════════════

    protected function generateReport(string $focus): array
    {
        $props = $this->getProperties()['data'];
        $finances = $this->getFinances()['data'];
        $contracts = $this->getContracts()['data'];
        $incidents = $this->getIncidents()['data'];
        $alerts = $this->getAlerts()['data'];

        $report = "## 📊 Rapport Intelligent ImmoSaas\n\n";
        $report .= "**Généré le :** " . now()->format('d/m/Y à H:i') . "\n\n";

        $report .= "### 🏠 Occupation\n";
        $report .= "- **{$props['total']}** biens dont **{$props['rented']}** loués et **{$props['available']}** libres\n";
        $report .= "- Taux d'occupation : " . ($props['total'] > 0 ? round(($props['rented'] / $props['total']) * 100) : 0) . "%\n";
        $report .= "- Revenu mensuel potentiel : " . number_format($props['monthly_revenue'], 0, ',', ' ') . " €\n\n";

        $report .= "### 💰 Finances\n";
        $report .= "- Payé ce mois : " . number_format($finances['monthly_paid'], 0, ',', ' ') . " €\n";
        $report .= "- Payé cette année : " . number_format($finances['yearly_paid'], 0, ',', ' ') . " €\n";

        $perte = $finances['overdue_total'] + $finances['pending_total'];
        if ($perte > 0) {
            $report .= "- ⚠️ **Montant à risque : " . number_format($perte, 0, ',', ' ') . " €**\n";
            $report .= "  - En retard : {$finances['overdue_count']} quittance(s) — " . number_format($finances['overdue_total'], 0, ',', ' ') . " €\n";
            $report .= "  - En attente : {$finances['pending_count']} quittance(s) — " . number_format($finances['pending_total'], 0, ',', ' ') . " €\n";
        } else {
            $report .= "- ✅ Aucun impayé — tout est à jour\n";
        }

        if ($focus === 'finances') {
            $report .= "\n**Détail des impayés :**\n";
            foreach ($finances['overdue_list'] as $r) {
                $report .= "- {$r['reference']} — {$r['period']} — {$r['property']} — " . number_format($r['total'], 0, ',', ' ') . " € (échéance: {$r['due_date']})\n";
            }
        }

        $report .= "\n### 🔧 Incidents\n";
        $report .= "- **{$incidents['open']}** demandes en cours";
        if ($incidents['urgent'] > 0) $report .= " dont **{$incidents['urgent']} urgence(s)** 🚨";
        $report .= "\n- **{$incidents['resolved']}** résolues\n";

        $report .= "\n### ⚠️ Alertes\n";
        if ($alerts['count'] > 0) {
            foreach ($alerts['alerts'] as $a) {
                $report .= "- [{$a['severity']}] {$a['message']}\n";
            }
        } else {
            $report .= "- ✅ Aucune alerte\n";
        }

        $report .= "\n### 🎯 Recommandations\n";
        $recommendations = [];

        if ($finances['overdue_count'] > 0) $recommendations[] = "Relancer les {$finances['overdue_count']} locataires en impayé";
        if ($incidents['urgent'] > 0) $recommendations[] = "Traiter les {$incidents['urgent']} urgences signalées";
        if ($contracts['ending_soon'] > 0) $recommendations[] = "Contacter les {$contracts['ending_soon']} locataires dont le contrat expire bientôt pour proposer un renouvellement";
        if ($props['available'] > 0) $recommendations[] = "Mettre en avant les {$props['available']} biens libres pour accélérer la relocation";

        if (empty($recommendations)) $recommendations[] = "Tout va bien —继续保持 cette dynamique !";

        foreach ($recommendations as $r) {
            $report .= "- ✅ $r\n";
        }

        return ['success' => true, 'data' => ['report' => $report]];
    }

    protected function generateDescription(int $propertyId): array
    {
        $property = Property::with('contracts')->find($propertyId);
        if (!$property || $property->user_id !== $this->userId) {
            return ['success' => false, 'error' => 'Bien introuvable.'];
        }

        $desc = "**{$property->title}**\n\n";
        $desc .= $property->description ?? "À louer : " . ($property->type ?? 'appartement') . " situé à {$property->city}.\n\n";
        $desc .= "**Caractéristiques :**\n";
        if ($property->surface) $desc .= "- Surface : {$property->surface} m²\n";
        if ($property->rooms) $desc .= "- Pièces : {$property->rooms}\n";
        if ($property->rent_amount) $desc .= "- Loyer : " . number_format($property->rent_amount, 0, ',', ' ') . " €/mois\n";
        if ($property->charges) $desc .= "- Charges : " . number_format($property->charges, 0, ',', ' ') . " €/mois\n";
        $desc .= "\n📍 {$property->address}, {$property->city}\n";
        $desc .= "\n📞 Contactez-nous dès maintenant pour organiser une visite !";

        return ['success' => true, 'data' => ['description' => $desc]];
    }

    // ═══════════════════════════════
    //  PRO : PORTFOLIOS
    // ═══════════════════════════════

    protected function getPortfolios(): array
    {
        $portfolios = Portfolio::where('user_id', $this->userId)
            ->withCount('properties')
            ->orderBy('name')
            ->get();

        return ['success' => true, 'data' => [
            'total' => $portfolios->count(),
            'list' => $portfolios->map(fn($p) => [
                'id' => $p->id, 'name' => $p->name, 'description' => $p->description,
                'color' => $p->color, 'properties_count' => $p->properties_count,
                'created_at' => $p->created_at->format('d/m/Y'),
            ]),
        ]];
    }

    protected function createPortfolio(array $args): array
    {
        $data = ['user_id' => $this->userId, 'name' => $args['name'], 'color' => $args['color'] ?? '#6366f1'];
        if (isset($args['description'])) $data['description'] = $args['description'];

        $portfolio = Portfolio::create($data);

        return ['success' => true, 'data' => [
            'id' => $portfolio->id, 'name' => $portfolio->name,
            'message' => "Portfolio '{$portfolio->name}' créé avec succès.",
        ]];
    }

    // ═══════════════════════════════
    //  PRO : TEAM
    // ═══════════════════════════════

    protected function getTeam(): array
    {
        $members = \App\Models\TeamMember::where('user_id', $this->userId)
            ->with('member')->get();

        $invitations = TeamInvitation::where('user_id', $this->userId)
            ->whereNull('accepted_at')->get();

        return ['success' => true, 'data' => [
            'members_count' => $members->count(),
            'pending_invitations' => $invitations->count(),
            'members' => $members->map(fn($m) => [
                'id' => $m->member_id, 'name' => $m->member->name ?? 'Inconnu',
                'email' => $m->member->email ?? '', 'role' => $m->role,
            ]),
            'invitations' => $invitations->map(fn($i) => [
                'email' => $i->email, 'role' => $i->role,
                'expires_at' => $i->expires_at?->format('d/m/Y'),
            ]),
        ]];
    }

    protected function inviteTeamMember(array $args): array
    {
        $existingInvite = TeamInvitation::where('user_id', $this->userId)
            ->where('email', $args['email'])->whereNull('accepted_at')->first();
        if ($existingInvite) {
            return ['success' => false, 'error' => 'Une invitation est déjà en cours pour cet email.'];
        }

        TeamInvitation::create([
            'user_id' => $this->userId, 'email' => $args['email'],
            'name' => $args['name'] ?? null, 'role' => $args['role'] ?? 'agent',
            'token' => \Illuminate\Support\Str::random(64),
            'expires_at' => now()->addDays(7),
        ]);

        return ['success' => true, 'data' => ['message' => "Invitation envoyée à {$args['email']}."]];
    }

    // ═══════════════════════════════
    //  PRO : AUTOMATION
    // ═══════════════════════════════

    protected function getAutomationRules(): array
    {
        $rules = AutomationRule::where('user_id', $this->userId)
            ->withCount('logs')->orderBy('name')->get();

        return ['success' => true, 'data' => [
            'total' => $rules->count(), 'active' => $rules->where('is_active', true)->count(),
            'list' => $rules->map(fn($r) => [
                'id' => $r->id, 'name' => $r->name, 'trigger' => $r->trigger_type,
                'action' => $r->action_type, 'is_active' => $r->is_active,
                'logs_count' => $r->logs_count, 'last_run' => $r->last_run_at?->format('d/m/Y H:i'),
            ]),
        ]];
    }

    protected function toggleAutomationRule(int $ruleId, bool $active): array
    {
        $rule = AutomationRule::where('user_id', $this->userId)->findOrFail($ruleId);
        $rule->update(['is_active' => $active]);

        $status = $active ? 'activée' : 'désactivée';
        return ['success' => true, 'data' => ['message' => "Règle '{$rule->name}' {$status}."]];
    }

    // ═══════════════════════════════
    //  PRO : RAPPORT PRO
    // ═══════════════════════════════

    protected function generateProReport(string $focus): array
    {
        $props = $this->getProperties()['data'];
        $finances = $this->getFinances()['data'];
        $portfolios = $this->getPortfolios()['data'];
        $team = $this->getTeam()['data'];
        $automation = $this->getAutomationRules()['data'];

        $report = "## 📊 Rapport Pro ImmoSaas\n\n";
        $report .= "**Généré le :** " . now()->format('d/m/Y à H:i') . "\n\n";

        $report .= "### 🏠 Vue d'ensemble\n";
        $report .= "- **{$props['total']}** biens, **{$portfolios['total']}** portfolios\n";
        $report .= "- **{$team['members_count']}** membres dans l'équipe" . ($team['pending_invitations'] > 0 ? ", **{$team['pending_invitations']}** invitation(s) en attente" : "") . "\n";
        $report .= "- **{$automation['active']}** automatisations actives / **{$automation['total']}** totales\n";
        $report .= "- Revenu mensuel : " . number_format($props['monthly_revenue'] ?? 0, 0, ',', ' ') . " €\n\n";

        if ($focus === 'portfolios' || $focus === 'global') {
            $report .= "### 📁 Portfolios\n";
            foreach ($portfolios['list'] as $pf) {
                $report .= "- **{$pf['name']}** : {$pf['properties_count']} bien(s)\n";
            }
            $report .= "\n";
        }

        if ($focus === 'finances' || $focus === 'global') {
            $report .= "### 💰 Finances\n";
            $report .= "- Payé ce mois : " . number_format($finances['monthly_paid'], 0, ',', ' ') . " €\n";
            $report .= "- En retard : {$finances['overdue_count']} (" . number_format($finances['overdue_total'], 0, ',', ' ') . " €)\n\n";
        }

        $report .= "### 🎯 Recommandations Pro\n";
        $recs = [];
        if ($portfolios['total'] === 0 && $props['total'] > 0) $recs[] = "Créez des portfolios pour organiser vos {$props['total']} biens";
        if ($team['members_count'] === 0) $recs[] = "Invitez des collaborateurs pour déléguer la gestion";
        if ($automation['total'] === 0) $recs[] = "Créez des automatisations pour gagner du temps (relances impayés, rappels visites)";
        if ($finances['overdue_count'] > 0) $recs[] = "Utilisez les automatisations pour les relances d'impayés";
        if (empty($recs)) $recs[] = "Tout est optimisé — bravo !";

        foreach ($recs as $r) {
            $report .= "- ✅ $r\n";
        }

        return ['success' => true, 'data' => ['report' => $report]];
    }

    // ═══════════════════════════════
    //  PRO : WORKFLOWS
    // ═══════════════════════════════

    protected function getWorkflows(): array
    {
        $workflows = Workflow::where('user_id', $this->userId)
            ->withCount('steps', 'instances')
            ->orderBy('name')
            ->get();

        return ['success' => true, 'data' => [
            'total' => $workflows->count(),
            'list' => $workflows->map(fn($w) => [
                'id' => $w->id, 'name' => $w->name, 'description' => $w->description,
                'trigger_type' => $w->trigger_type, 'is_active' => $w->is_active,
                'steps_count' => $w->steps_count, 'instances_count' => $w->instances_count,
                'created_at' => $w->created_at->format('d/m/Y'),
            ]),
        ]];
    }

    protected function runWorkflow(int $workflowId): array
    {
        $workflow = Workflow::where('user_id', $this->userId)->find($workflowId);
        if (!$workflow) return ['success' => false, 'error' => 'Workflow introuvable.'];
        if ($workflow->steps()->count() === 0) return ['success' => false, 'error' => 'Ce workflow n\'a aucune étape.'];

        // Create and run instance
        $firstStep = $workflow->firstStep();
        $instance = $workflow->instances()->create([
            'user_id' => $this->userId, 'status' => 'running',
            'current_step_id' => $firstStep?->id, 'context' => [],
            'started_at' => now(),
        ]);

        return ['success' => true, 'data' => [
            'instance_id' => $instance->id, 'status' => $instance->status,
            'message' => "Workflow '{$workflow->name}' lancé avec " . $workflow->steps()->count() . ' étape(s).',
        ]];
    }

    protected function getWorkflowTemplates(): array
    {
        $templates = \App\Models\WorkflowTemplate::where('is_built_in', true)->get();

        return ['success' => true, 'data' => [
            'total' => $templates->count(),
            'list' => $templates->map(fn($t) => [
                'id' => $t->id, 'name' => $t->name, 'description' => $t->description,
                'category' => $t->category, 'steps' => count($t->config['steps'] ?? []),
            ]),
        ]];
    }

    protected function createWorkflowFromTemplate(int $templateId, ?string $name): array
    {
        $template = \App\Models\WorkflowTemplate::find($templateId);
        if (!$template) return ['success' => false, 'error' => 'Template introuvable.'];

        $config = $template->config;
        $workflow = Workflow::create([
            'user_id' => $this->userId, 'name' => $name ?? $template->name,
            'description' => $template->description, 'is_active' => false,
            'trigger_type' => $config['trigger_type'] ?? 'manual',
            'trigger_config' => $config['trigger_config'] ?? [],
        ]);

        if (!empty($config['steps'])) {
            foreach ($config['steps'] as $i => $s) {
                $workflow->steps()->create([
                    'name' => $s['name'], 'description' => $s['description'] ?? null,
                    'step_type' => $s['step_type'], 'config' => $s['config'] ?? [],
                    'order' => $s['order'] ?? ($i + 1),
                ]);
            }
        }

        return ['success' => true, 'data' => [
            'workflow_id' => $workflow->id,
            'message' => "Workflow '{$workflow->name}' créé depuis le template.",
        ]];
    }

    // ═══════════════════════════════
    //  PRO : COMPTABILITÉ
    // ═══════════════════════════════

    protected function getAccountingSummary(): array
    {
        $userId = $this->userId;
        $accounts = BankAccount::where('user_id', $userId)->get();
        $month = now()->month;
        $year = now()->year;

        $income = (float) Transaction::where('user_id', $userId)
            ->where('type', 'income')->where('status', 'completed')
            ->whereYear('transaction_date', $year)->whereMonth('transaction_date', $month)
            ->sum('amount');

        $expense = (float) Transaction::where('user_id', $userId)
            ->where('type', 'expense')->where('status', 'completed')
            ->whereYear('transaction_date', $year)->whereMonth('transaction_date', $month)
            ->sum('amount');

        $budgets = Budget::where('user_id', $userId)
            ->where('year', $year)->where('month', $month)
            ->with('category')->get();

        return ['success' => true, 'data' => [
            'total_balance' => (float) $accounts->sum('balance'),
            'accounts_count' => $accounts->count(),
            'monthly_income' => $income,
            'monthly_expense' => $expense,
            'net' => $income - $expense,
            'budgets' => $budgets->map(fn($b) => [
                'category' => $b->category?->name ?? 'Sans catégorie',
                'budgeted' => (float) $b->amount,
                'spent' => (float) $b->spent,
                'remaining' => max(0, (float) $b->amount - (float) $b->spent),
            ]),
        ]];
    }

    protected function getBankAccounts(): array
    {
        $accounts = BankAccount::where('user_id', $this->userId)
            ->withCount('transactions')
            ->orderBy('name')
            ->get();

        return ['success' => true, 'data' => [
            'list' => $accounts->map(fn($a) => [
                'id' => $a->id, 'name' => $a->name, 'bank_name' => $a->bank_name,
                'type' => $a->type, 'balance' => (float) $a->balance,
                'iban' => $a->iban, 'transactions_count' => $a->transactions_count,
                'is_active' => $a->is_active,
            ]),
        ]];
    }

    protected function getTransactions(?string $type, ?string $status, int $limit): array
    {
        $query = Transaction::where('user_id', $this->userId)
            ->with(['bankAccount', 'category']);

        if ($type) $query->where('type', $type);
        if ($status) $query->where('status', $status);

        $transactions = $query->latest('transaction_date')->take($limit)->get();

        return ['success' => true, 'data' => [
            'list' => $transactions->map(fn($t) => [
                'id' => $t->id, 'type' => $t->type, 'amount' => (float) $t->amount,
                'description' => $t->description,
                'account' => $t->bankAccount?->name, 'category' => $t->category?->name,
                'date' => $t->transaction_date?->format('d/m/Y'), 'status' => $t->status,
            ]),
        ]];
    }

    protected function recordTransaction(array $args): array
    {
        $bankAccount = BankAccount::find($args['bank_account_id']);
        if (!$bankAccount || $bankAccount->user_id !== $this->userId) {
            return ['success' => false, 'error' => 'Compte bancaire introuvable.'];
        }

        $txn = Transaction::create([
            'user_id' => $this->userId,
            'bank_account_id' => $args['bank_account_id'],
            'category_id' => $args['category_id'] ?? null,
            'type' => $args['type'],
            'amount' => $args['amount'],
            'description' => $args['description'] ?? null,
            'transaction_date' => $args['transaction_date'] ?? now()->format('Y-m-d'),
            'status' => 'completed',
        ]);

        $sign = $args['type'] === 'income' ? 1 : -1;
        $bankAccount->increment('balance', $sign * $args['amount']);

        return ['success' => true, 'data' => [
            'id' => $txn->id, 'amount' => (float) $txn->amount,
            'type' => $txn->type, 'description' => $txn->description,
            'message' => 'Transaction enregistrée avec succès.',
        ]];
    }

    // ═══════════════════════════════
    //  PRO : GED
    // ═══════════════════════════════

    protected function getGedSummary(): array
    {
        $userId = $this->userId;

        $foldersCount = DocumentFolder::where('user_id', $userId)->count();
        $documentsCount = Document::where('user_id', $userId)->count();
        $starredCount = Document::where('user_id', $userId)->where('is_starred', true)->count();
        $sharedCount = DocumentShare::whereHas('document', fn($q) => $q->where('user_id', $userId))->count();

        $recentDocuments = Document::where('user_id', $userId)
            ->latest()->take(5)->get();

        return ['success' => true, 'data' => [
            'folders' => $foldersCount,
            'documents' => $documentsCount,
            'starred' => $starredCount,
            'shared' => $sharedCount,
            'recent' => $recentDocuments->map(fn($d) => [
                'id' => $d->id, 'name' => $d->name,
                'type' => $d->file_type, 'size' => $d->file_size,
                'created_at' => $d->created_at->format('d/m/Y'),
            ]),
        ]];
    }

    protected function searchGedDocuments(string $query): array
    {
        $documents = Document::where('user_id', $this->userId)
            ->where(function ($q) use ($query) {
                $q->where('name', 'like', "%{$query}%")
                  ->orWhere('tags', 'like', "%{$query}%")
                  ->orWhere('notes', 'like', "%{$query}%");
            })
            ->with('folder')
            ->latest()
            ->take(10)
            ->get();

        return ['success' => true, 'data' => [
            'total' => $documents->count(),
            'list' => $documents->map(fn($d) => [
                'id' => $d->id, 'name' => $d->name,
                'type' => $d->file_type, 'size' => $d->file_size,
                'folder' => $d->folder?->name, 'is_starred' => $d->is_starred,
            ]),
        ]];
    }

    protected function getAllOverview(): array
    {
        return ['success' => true, 'data' => [
            'properties' => $this->getProperties()['data'],
            'tenants' => $this->getTenants()['data'],
            'contracts' => $this->getContracts()['data'],
            'finances' => $this->getFinances()['data'],
            'visits' => $this->getVisits()['data'],
            'incidents' => $this->getIncidents()['data'],
            'alerts' => $this->getAlerts()['data'],
        ]];
    }
}
