<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class WorkflowInstanceLog extends Model
{
    public $timestamps = false;

    protected $fillable = [
        'workflow_instance_id', 'workflow_step_id', 'status',
        'output', 'error', 'started_at', 'completed_at',
    ];

    protected function casts(): array
    {
        return [
            'output' => 'array',
            'started_at' => 'datetime',
            'completed_at' => 'datetime',
        ];
    }

    public function instance() { return $this->belongsTo(WorkflowInstance::class, 'workflow_instance_id'); }

    public function step() { return $this->belongsTo(WorkflowStep::class, 'workflow_step_id'); }
}
