<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class WorkflowStepConnection extends Model
{
    public $timestamps = false;

    protected $fillable = ['workflow_id', 'from_step_id', 'to_step_id', 'condition_label'];

    public function workflow() { return $this->belongsTo(Workflow::class); }

    public function fromStep() { return $this->belongsTo(WorkflowStep::class, 'from_step_id'); }

    public function toStep() { return $this->belongsTo(WorkflowStep::class, 'to_step_id'); }
}
