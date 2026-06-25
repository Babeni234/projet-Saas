<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AutomationLog extends Model
{
    public $timestamps = false;

    protected $fillable = ['automation_rule_id', 'status', 'message', 'context', 'created_at'];

    protected function casts(): array
    {
        return [
            'context' => 'array',
            'created_at' => 'datetime',
        ];
    }

    public function rule()
    {
        return $this->belongsTo(AutomationRule::class, 'automation_rule_id');
    }
}
