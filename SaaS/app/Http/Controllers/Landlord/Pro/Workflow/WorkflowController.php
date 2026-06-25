<?php

namespace App\Http\Controllers\Landlord\Pro\Workflow;

use App\Http\Controllers\Controller;
use App\Models\Workflow;
use App\Models\WorkflowInstance;
use App\Models\WorkflowInstanceLog;
use App\Models\WorkflowStep;
use App\Models\WorkflowStepConnection;
use App\Models\WorkflowTemplate;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Inertia\Inertia;

class WorkflowController extends Controller
{
    public function index()
    {
        $workflows = Workflow::where('user_id', auth()->id())
            ->withCount('steps', 'instances')
            ->orderBy('name')
            ->get();

        $templates = WorkflowTemplate::where('is_built_in', true)->get();

        return Inertia::render('Landlord/Pro/Workflows/Index', [
            'workflows' => $workflows,
            'templates' => $templates,
            'triggers' => Workflow::TRIGGERS,
        ]);
    }

    public function create()
    {
        return Inertia::render('Landlord/Pro/Workflows/Builder', [
            'workflow' => null,
            'step_types' => WorkflowStep::TYPES,
            'triggers' => Workflow::TRIGGERS,
            'templates' => WorkflowTemplate::where('is_built_in', true)->get(),
        ]);
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string|max:2000',
            'trigger_type' => 'required|string',
            'trigger_config' => 'nullable|array',
            'is_active' => 'boolean',
            'steps' => 'nullable|array',
            'steps.*.name' => 'required|string|max:255',
            'steps.*.description' => 'nullable|string|max:1000',
            'steps.*.step_type' => 'required|string',
            'steps.*.config' => 'nullable|array',
            'steps.*.order' => 'required|integer',
        ]);

        $data['user_id'] = auth()->id();
        $data['trigger_config'] = $data['trigger_config'] ?? [];
        $data['is_active'] = $data['is_active'] ?? true;

        $workflow = Workflow::create(collect($data)->except('steps')->toArray());

        if (!empty($data['steps'])) {
            foreach ($data['steps'] as $stepData) {
                $step = $workflow->steps()->create([
                    'name' => $stepData['name'],
                    'description' => $stepData['description'] ?? null,
                    'step_type' => $stepData['step_type'],
                    'config' => $stepData['config'] ?? [],
                    'order' => $stepData['order'],
                ]);

                // Auto-connect to previous step (linear chain)
                if ($step->order > 1) {
                    $prevStep = $workflow->steps()->where('order', $step->order - 1)->first();
                    if ($prevStep) {
                        $workflow->connections()->create([
                            'from_step_id' => $prevStep->id,
                            'to_step_id' => $step->id,
                        ]);
                    }
                }
            }
        }

        return redirect()->route('landlord.pro.workflows.show', $workflow->id)
            ->with('success', 'Workflow créé avec succès.');
    }

    public function show(Workflow $workflow)
    {
        if ($workflow->user_id !== auth()->id()) abort(403);

        $workflow->load(['steps', 'connections.fromStep', 'connections.toStep']);

        $instances = WorkflowInstance::where('workflow_id', $workflow->id)
            ->with('currentStep')
            ->orderBy('created_at', 'desc')
            ->take(20)
            ->get();

        return Inertia::render('Landlord/Pro/Workflows/Show', [
            'workflow' => $workflow,
            'instances' => $instances,
            'step_types' => WorkflowStep::TYPES,
            'step_type_icons' => WorkflowStep::TYPE_ICONS,
            'step_type_colors' => WorkflowStep::TYPE_COLORS,
            'triggers' => Workflow::TRIGGERS,
        ]);
    }

    public function edit(Workflow $workflow)
    {
        if ($workflow->user_id !== auth()->id()) abort(403);

        $workflow->load(['steps', 'connections']);

        return Inertia::render('Landlord/Pro/Workflows/Builder', [
            'workflow' => $workflow,
            'step_types' => WorkflowStep::TYPES,
            'step_type_icons' => WorkflowStep::TYPE_ICONS,
            'step_type_colors' => WorkflowStep::TYPE_COLORS,
            'triggers' => Workflow::TRIGGERS,
            'templates' => [],
        ]);
    }

    public function update(Request $request, Workflow $workflow)
    {
        if ($workflow->user_id !== auth()->id()) abort(403);

        $data = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string|max:2000',
            'trigger_type' => 'required|string',
            'trigger_config' => 'nullable|array',
            'is_active' => 'boolean',
        ]);

        $workflow->update($data);

        return redirect()->route('landlord.pro.workflows.show', $workflow->id)
            ->with('success', 'Workflow mis à jour.');
    }

    public function destroy(Workflow $workflow)
    {
        if ($workflow->user_id !== auth()->id()) abort(403);

        $workflow->instances()->each(fn($i) => $i->logs()->delete());
        $workflow->instances()->delete();
        $workflow->connections()->delete();
        $workflow->steps()->delete();
        $workflow->delete();

        return redirect()->route('landlord.pro.workflows.index')
            ->with('success', 'Workflow supprimé.');
    }

    // ── Step management ──

    public function addStep(Request $request, Workflow $workflow)
    {
        if ($workflow->user_id !== auth()->id()) abort(403);

        $data = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string|max:1000',
            'step_type' => 'required|string',
            'config' => 'nullable|array',
            'after_step_id' => 'nullable|exists:workflow_steps,id',
        ]);

        $maxOrder = $workflow->steps()->max('order') ?? 0;
        $newOrder = $maxOrder + 1;

        $step = $workflow->steps()->create([
            'name' => $data['name'],
            'description' => $data['description'] ?? null,
            'step_type' => $data['step_type'],
            'config' => $data['config'] ?? [],
            'order' => $newOrder,
        ]);

        // Connect after_step → new step
        if ($data['after_step_id'] ?? null) {
            $workflow->connections()->create([
                'from_step_id' => $data['after_step_id'],
                'to_step_id' => $step->id,
            ]);
        }

        return redirect()->route('landlord.pro.workflows.show', $workflow->id)
            ->with('success', 'Étape ajoutée.');
    }

    public function updateStep(Request $request, WorkflowStep $step)
    {
        if ($step->workflow->user_id !== auth()->id()) abort(403);

        $data = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string|max:1000',
            'config' => 'nullable|array',
        ]);

        $step->update($data);

        return back()->with('success', 'Étape mise à jour.');
    }

    public function reorderSteps(Request $request, Workflow $workflow)
    {
        if ($workflow->user_id !== auth()->id()) abort(403);

        $request->validate(['steps' => 'required|array', 'steps.*.id' => 'exists:workflow_steps,id', 'steps.*.order' => 'integer']);

        foreach ($request->steps as $s) {
            WorkflowStep::where('id', $s['id'])->update(['order' => $s['order']]);
        }

        return back()->with('success', 'Ordre mis à jour.');
    }

    public function removeStep(WorkflowStep $step)
    {
        if ($step->workflow->user_id !== auth()->id()) abort(403);

        $workflow = $step->workflow;
        $step->connections()->delete();
        $step->delete();

        // Reindex order
        $workflow->steps()->orderBy('order')->get()->each(fn($s, $i) => $s->update(['order' => $i + 1]));

        return redirect()->route('landlord.pro.workflows.show', $workflow->id)
            ->with('success', 'Étape supprimée.');
    }

    // ── Execution ──

    public function run(Workflow $workflow)
    {
        if ($workflow->user_id !== auth()->id()) abort(403);

        $instance = $this->executeWorkflow($workflow);

        return back()->with(
            $instance->status === 'completed' ? 'success' : 'error',
            "Workflow exécuté : {$instance->status}."
        );
    }

    public function instances(Workflow $workflow)
    {
        if ($workflow->user_id !== auth()->id()) abort(403);

        $instances = WorkflowInstance::where('workflow_id', $workflow->id)
            ->with('currentStep', 'logs.step')
            ->orderBy('created_at', 'desc')
            ->paginate(15);

        return Inertia::render('Landlord/Pro/Workflows/Show', [
            'workflow' => $workflow->load('steps', 'connections.fromStep', 'connections.toStep'),
            'instances' => $instances,
            'step_types' => WorkflowStep::TYPES,
            'step_type_icons' => WorkflowStep::TYPE_ICONS,
            'step_type_colors' => WorkflowStep::TYPE_COLORS,
            'triggers' => Workflow::TRIGGERS,
        ]);
    }

    // ── Templates ──

    public function fromTemplate(Request $request)
    {
        $data = $request->validate([
            'template_id' => 'required|exists:workflow_templates,id',
            'name' => 'nullable|string|max:255',
        ]);

        $template = WorkflowTemplate::findOrFail($data['template_id']);
        $config = $template->config;

        $workflow = Workflow::create([
            'user_id' => auth()->id(),
            'name' => $data['name'] ?? $template->name,
            'description' => $template->description,
            'trigger_type' => $config['trigger_type'] ?? 'manual',
            'trigger_config' => $config['trigger_config'] ?? [],
            'is_active' => false,
        ]);

        if (!empty($config['steps'])) {
            $stepMap = [];
            foreach ($config['steps'] as $i => $s) {
                $step = $workflow->steps()->create([
                    'name' => $s['name'], 'description' => $s['description'] ?? null,
                    'step_type' => $s['step_type'], 'config' => $s['config'] ?? [],
                    'order' => $s['order'] ?? ($i + 1),
                ]);
                $stepMap[$s['id'] ?? $i] = $step->id;
            }
            if (!empty($config['connections'])) {
                foreach ($config['connections'] as $c) {
                    $workflow->connections()->create([
                        'from_step_id' => $stepMap[$c['from_step_id']] ?? null,
                        'to_step_id' => $stepMap[$c['to_step_id']] ?? null,
                        'condition_label' => $c['condition_label'] ?? null,
                    ]);
                }
            }
        }

        return redirect()->route('landlord.pro.workflows.show', $workflow->id)
            ->with('success', 'Workflow créé depuis le template.');
    }

    // ═══════════════════════════════════════
    //  WORKFLOW EXECUTION ENGINE
    // ═══════════════════════════════════════

    public function executeWorkflow(Workflow $workflow, array $initialContext = []): WorkflowInstance
    {
        $firstStep = $workflow->firstStep();
        if (!$firstStep) {
            $instance = $workflow->instances()->create([
                'user_id' => auth()->id() ?? $workflow->user_id,
                'status' => 'failed',
                'context' => $initialContext,
                'started_at' => now(),
                'completed_at' => now(),
            ]);
            $instance->logs()->create([
                'workflow_step_id' => 0, 'status' => 'failed',
                'error' => 'Aucune étape dans le workflow.', 'started_at' => now(), 'completed_at' => now(),
            ]);
            return $instance;
        }

        $instance = $workflow->instances()->create([
            'user_id' => auth()->id() ?? $workflow->user_id,
            'status' => 'running',
            'current_step_id' => $firstStep->id,
            'context' => $initialContext,
            'started_at' => now(),
        ]);

        try {
            $this->processStep($instance, $firstStep);
            $instance->update(['status' => 'completed', 'completed_at' => now()]);
        } catch (\Throwable $e) {
            $instance->update(['status' => 'failed', 'completed_at' => now()]);
            $instance->logs()->create([
                'workflow_step_id' => $instance->current_step_id ?? 0,
                'status' => 'failed', 'error' => $e->getMessage(),
                'started_at' => now(), 'completed_at' => now(),
            ]);
        }

        return $instance->fresh()->load('logs.step');
    }

    protected function processStep(WorkflowInstance $instance, WorkflowStep $step): void
    {
        $instance->update(['current_step_id' => $step->id]);
        $context = $instance->context ?? [];

        $log = $instance->logs()->create([
            'workflow_step_id' => $step->id,
            'status' => 'running',
            'started_at' => now(),
        ]);

        try {
            $output = match ($step->step_type) {
                'action' => $this->executeAction($step, $context),
                'condition' => $this->evaluateCondition($step, $context),
                'delay' => $this->executeDelay($step, $context),
                'approval' => $this->executeApproval($step, $context),
                'notification' => $this->executeNotification($step, $context),
                'webhook' => $this->executeWebhook($step, $context),
                default => ['success' => true, 'message' => 'Étape exécutée.'],
            };

            $context = array_merge($context, $output);
            $instance->update(['context' => $context]);

            $log->update(['status' => 'completed', 'output' => $output, 'completed_at' => now()]);

            // Find next step
            $nextStep = $this->getNextStep($instance, $step, $output);
            if ($nextStep) {
                $this->processStep($instance, $nextStep);
            }
        } catch (\Throwable $e) {
            $log->update(['status' => 'failed', 'error' => $e->getMessage(), 'completed_at' => now()]);
            throw $e;
        }
    }

    protected function getNextStep(WorkflowInstance $instance, WorkflowStep $currentStep, array $stepOutput): ?WorkflowStep
    {
        // Find connections from this step
        $connections = $currentStep->workflow->connections()
            ->where('from_step_id', $currentStep->id)
            ->get();

        if ($connections->isEmpty()) return null;

        // For condition steps, follow the matching branch
        if ($currentStep->step_type === 'condition') {
            $conditionMet = $stepOutput['condition_met'] ?? false;
            $label = $conditionMet ? 'yes' : 'no';
            $conn = $connections->firstWhere('condition_label', $label) ?? $connections->first();
        } else {
            $conn = $connections->first();
        }

        return $conn?->toStep;
    }

    // ── Step Handlers ──

    protected function executeAction(WorkflowStep $step, array $context): array
    {
        $config = $step->config ?? [];
        $actionType = $config['action'] ?? 'log';

        return match ($actionType) {
            'mark_receipt_overdue' => $this->actionMarkOverdue($config, $context),
            'send_reminder' => ['action' => 'send_reminder', 'triggered' => true],
            'update_status' => ['action' => 'update_status', 'status' => $config['status'] ?? 'updated'],
            'create_task' => ['action' => 'create_task', 'task' => $config['task_name'] ?? 'Tâche'],
            default => ['action' => 'log', 'message' => "Action: {$step->name}"],
        };
    }

    protected function evaluateCondition(WorkflowStep $step, array $context): array
    {
        $config = $step->config ?? [];
        $field = $config['field'] ?? '';
        $operator = $config['operator'] ?? 'equals';
        $value = $config['value'] ?? '';

        $actualValue = $context[$field] ?? null;
        $met = match ($operator) {
            'equals' => $actualValue == $value,
            'not_equals' => $actualValue != $value,
            'greater_than' => $actualValue > $value,
            'less_than' => $actualValue < $value,
            'contains' => is_string($actualValue) && str_contains($actualValue, $value),
            'is_empty' => empty($actualValue),
            'is_not_empty' => !empty($actualValue),
            default => true,
        };

        return ['condition_met' => $met, 'field' => $field, 'operator' => $operator, 'value' => $value, 'actual' => $actualValue];
    }

    protected function executeDelay(WorkflowStep $step, array $context): array
    {
        $config = $step->config ?? [];
        $minutes = $config['minutes'] ?? 0;
        $hours = $config['hours'] ?? 0;
        $days = $config['days'] ?? 0;

        $totalMinutes = $minutes + ($hours * 60) + ($days * 1440);

        if ($totalMinutes > 0) {
            // In a real app, this would be queued. For now we log it.
            return ['delayed' => true, 'minutes' => $totalMinutes, 'message' => "Attente de {$totalMinutes} minute(s)"];
        }

        return ['delayed' => false];
    }

    protected function executeApproval(WorkflowStep $step, array $context): array
    {
        $config = $step->config ?? [];
        // In a real app, this would create a pending approval task
        return [
            'approval_required' => true,
            'assigned_to' => $config['assigned_to'] ?? 'owner',
            'status' => 'pending_approval',
            'message' => "Approbation requise : {$step->name}",
        ];
    }

    protected function executeNotification(WorkflowStep $step, array $context): array
    {
        $config = $step->config ?? [];
        $channel = $config['channel'] ?? 'email';
        $template = $config['template'] ?? 'default';

        return [
            'notification_sent' => true,
            'channel' => $channel,
            'template' => $template,
            'to' => $config['to'] ?? 'tenant',
            'message' => "Notification {$channel} envoyée",
        ];
    }

    protected function executeWebhook(WorkflowStep $step, array $context): array
    {
        $config = $step->config ?? [];
        $url = $config['url'] ?? '';

        return [
            'webhook_called' => !empty($url),
            'url' => $url,
            'status' => 'simulated',
        ];
    }

    protected function actionMarkOverdue(array $config, array $context): array
    {
        return ['action' => 'mark_overdue', 'triggered' => true];
    }
}
