<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class WorkflowStep extends Model
{
    protected $fillable = [
        'workflow_id', 'name', 'description', 'step_type', 'config', 'order',
    ];

    protected function casts(): array
    {
        return ['config' => 'array'];
    }

    public const TYPES = [
        'action' => 'Action',
        'condition' => 'Condition',
        'delay' => 'Attente',
        'approval' => 'Approbation',
        'notification' => 'Notification',
        'webhook' => 'Webhook',
    ];

    public const TYPE_ICONS = [
        'action' => '⚡',
        'condition' => '🔀',
        'delay' => '⏳',
        'approval' => '✅',
        'notification' => '📧',
        'webhook' => '🔗',
    ];

    public const TYPE_COLORS = [
        'action' => 'indigo',
        'condition' => 'amber',
        'delay' => 'cyan',
        'approval' => 'emerald',
        'notification' => 'blue',
        'webhook' => 'violet',
    ];

    public function workflow() { return $this->belongsTo(Workflow::class); }
}
