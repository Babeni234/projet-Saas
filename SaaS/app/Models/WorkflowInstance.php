<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class WorkflowInstance extends Model
{
    protected $fillable = [
        'workflow_id', 'user_id', 'status', 'current_step_id',
        'context', 'started_at', 'completed_at',
    ];

    protected function casts(): array
    {
        return [
            'context' => 'array',
            'started_at' => 'datetime',
            'completed_at' => 'datetime',
        ];
    }

    public function workflow() { return $this->belongsTo(Workflow::class); }

    public function currentStep() { return $this->belongsTo(WorkflowStep::class, 'current_step_id'); }

    public function logs() { return $this->hasMany(WorkflowInstanceLog::class, 'workflow_instance_id'); }
}
