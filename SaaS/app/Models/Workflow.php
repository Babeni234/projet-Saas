<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Workflow extends Model
{
    protected $fillable = [
        'user_id', 'name', 'description', 'trigger_type',
        'trigger_config', 'is_active',
    ];

    protected function casts(): array
    {
        return [
            'trigger_config' => 'array',
            'is_active' => 'boolean',
        ];
    }

    public const TRIGGERS = [
        'manual' => 'Déclenchement manuel',
        'scheduled' => 'Planifié (cron)',
        'receipt_overdue' => 'Quittance en retard',
        'contract_ending' => 'Contrat qui expire',
        'visit_reminder' => 'Rappel de visite (J-1)',
        'incident_reported' => 'Incident signalé (urgence)',
        'rent_due' => 'Loyer à échoir (J-3)',
    ];

    public function user() { return $this->belongsTo(User::class); }

    public function steps() { return $this->hasMany(WorkflowStep::class)->orderBy('order'); }

    public function connections() { return $this->hasMany(WorkflowStepConnection::class); }

    public function instances() { return $this->hasMany(WorkflowInstance::class); }

    public function firstStep(): ?WorkflowStep
    {
        return $this->steps()->orderBy('order')->first();
    }

    public function duplicate(string $newName): self
    {
        $clone = $this->replicate()->fill(['name' => $newName, 'is_active' => false]);
        $clone->save();

        $stepMap = [];
        foreach ($this->steps as $step) {
            $newStep = $step->replicate();
            $newStep->workflow_id = $clone->id;
            $newStep->save();
            $stepMap[$step->id] = $newStep->id;
        }

        foreach ($this->connections as $conn) {
            $clone->connections()->create([
                'from_step_id' => $stepMap[$conn->from_step_id],
                'to_step_id' => $stepMap[$conn->to_step_id],
                'condition_label' => $conn->condition_label,
            ]);
        }

        return $clone->fresh()->load('steps', 'connections');
    }
}
