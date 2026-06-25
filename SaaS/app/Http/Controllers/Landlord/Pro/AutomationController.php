<?php

namespace App\Http\Controllers\Landlord\Pro;

use App\Http\Controllers\Controller;
use App\Models\AutomationLog;
use App\Models\AutomationRule;
use App\Models\Contract;
use App\Models\Incident;
use App\Models\Receipt;
use App\Models\Visit;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Inertia\Inertia;

class AutomationController extends Controller
{
    public function index()
    {
        $rules = AutomationRule::where('user_id', auth()->id())
            ->withCount('logs')
            ->orderBy('name')
            ->get();

        $recentLogs = AutomationLog::whereHas('rule', fn($q) => $q->where('user_id', auth()->id()))
            ->with('rule')
            ->latest('created_at')
            ->take(20)
            ->get();

        return Inertia::render('Landlord/Pro/Automation/Index', [
            'rules' => $rules,
            'recent_logs' => $recentLogs,
            'triggers' => AutomationRule::TRIGGERS,
            'actions' => AutomationRule::ACTIONS,
        ]);
    }

    public function create()
    {
        return Inertia::render('Landlord/Pro/Automation/Create', [
            'triggers' => AutomationRule::TRIGGERS,
            'actions' => AutomationRule::ACTIONS,
        ]);
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string|max:1000',
            'trigger_type' => 'required|string|in:' . implode(',', array_keys(AutomationRule::TRIGGERS)),
            'trigger_config' => 'nullable|array',
            'action_type' => 'required|string|in:' . implode(',', array_keys(AutomationRule::ACTIONS)),
            'action_config' => 'nullable|array',
            'is_active' => 'boolean',
        ]);

        $data['user_id'] = auth()->id();
        $data['is_active'] = $data['is_active'] ?? true;

        AutomationRule::create($data);

        return redirect()->route('landlord.pro.automation.index')
            ->with('success', 'Règle d\'automatisation créée.');
    }

    public function show(AutomationRule $automationRule)
    {
        if ($automationRule->user_id !== auth()->id()) abort(403);

        $automationRule->load(['logs' => fn($q) => $q->latest('created_at')->take(50)]);

        return Inertia::render('Landlord/Pro/Automation/Show', [
            'rule' => $automationRule,
            'triggers' => AutomationRule::TRIGGERS,
            'actions' => AutomationRule::ACTIONS,
        ]);
    }

    public function toggle(AutomationRule $automationRule)
    {
        if ($automationRule->user_id !== auth()->id()) abort(403);

        $automationRule->update(['is_active' => !$automationRule->is_active]);

        return back()->with('success',
            $automationRule->is_active ? 'Règle activée.' : 'Règle désactivée.'
        );
    }

    public function destroy(AutomationRule $automationRule)
    {
        if ($automationRule->user_id !== auth()->id()) abort(403);

        $automationRule->logs()->delete();
        $automationRule->delete();

        return redirect()->route('landlord.pro.automation.index')
            ->with('success', 'Règle supprimée.');
    }

    public function runNow(AutomationRule $automationRule)
    {
        if ($automationRule->user_id !== auth()->id()) abort(403);

        $result = $this->executeRule($automationRule);

        return back()->with(
            $result['status'] === 'success' ? 'success' : 'error',
            $result['message']
        );
    }

    /**
     * Execute a rule - this is the engine that runs automation
     */
    public function executeRule(AutomationRule $rule): array
    {
        $userId = $rule->user_id;
        $context = [];
        $status = 'success';
        $message = '';

        try {
            match ($rule->trigger_type) {
                'receipt_overdue' => $this->handleOverdueReceipts($rule, $userId, $context),
                'contract_ending' => $this->handleContractEnding($rule, $userId, $context),
                'visit_reminder' => $this->handleVisitReminder($rule, $userId, $context),
                'incident_reported' => $this->handleIncidentReported($rule, $userId, $context),
                'rent_due' => $this->handleRentDue($rule, $userId, $context),
                default => throw new \Exception("Trigger inconnu: {$rule->trigger_type}"),
            };

            $message = "Règle '{$rule->name}' exécutée avec succès.";
        } catch (\Throwable $e) {
            $status = 'failed';
            $message = "Erreur: " . $e->getMessage();
        }

        $rule->update(['last_run_at' => now()]);

        AutomationLog::create([
            'automation_rule_id' => $rule->id,
            'status' => $status,
            'message' => $message,
            'context' => $context,
            'created_at' => now(),
        ]);

        return ['status' => $status, 'message' => $message];
    }

    protected function handleOverdueReceipts(AutomationRule $rule, int $userId, array &$context): void
    {
        $overdue = Receipt::whereHas('contract.property', fn($q) => $q->where('user_id', $userId))
            ->where('status', 'overdue')
            ->with('contract.tenant')
            ->get();

        $context['count'] = $overdue->count();
        $context['receipts'] = $overdue->pluck('id');

        if ($rule->action_type === 'notify_tenant') {
            foreach ($overdue as $receipt) {
                // Log what would be done
                $context['notified'][] = [
                    'tenant' => $receipt->contract->tenant->name ?? 'N/A',
                    'receipt' => $receipt->reference,
                    'amount' => $receipt->total,
                ];
            }
        }
    }

    protected function handleContractEnding(AutomationRule $rule, int $userId, array &$context): void
    {
        $daysThreshold = $rule->trigger_config['days'] ?? 30;

        $ending = Contract::whereHas('property', fn($q) => $q->where('user_id', $userId))
            ->where('status', 'active')
            ->whereNotNull('end_date')
            ->whereBetween('end_date', [now(), now()->addDays($daysThreshold)])
            ->with('tenant')
            ->get();

        $context['count'] = $ending->count();
        $context['contracts'] = $ending->pluck('id');
    }

    protected function handleVisitReminder(AutomationRule $rule, int $userId, array &$context): void
    {
        $upcomingVisits = Visit::where('landlord_id', $userId)
            ->where('date', now()->addDay()->format('Y-m-d'))
            ->where('status', 'scheduled')
            ->get();

        $context['count'] = $upcomingVisits->count();
        $context['visits'] = $upcomingVisits->pluck('id');
    }

    protected function handleIncidentReported(AutomationRule $rule, int $userId, array &$context): void
    {
        $urgent = Incident::where('landlord_id', $userId)
            ->where('urgency', 'emergency')
            ->where('status', 'reported')
            ->get();

        $context['count'] = $urgent->count();
        $context['incidents'] = $urgent->pluck('id');
    }

    protected function handleRentDue(AutomationRule $rule, int $userId, array &$context): void
    {
        $dueSoon = Receipt::whereHas('contract.property', fn($q) => $q->where('user_id', $userId))
            ->where('status', 'pending')
            ->whereDate('due_date', now()->addDays(3))
            ->get();

        $context['count'] = $dueSoon->count();
        $context['receipts'] = $dueSoon->pluck('id');
    }
}
