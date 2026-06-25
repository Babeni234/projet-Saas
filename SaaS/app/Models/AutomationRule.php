<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AutomationRule extends Model
{
    protected $fillable = [
        'user_id', 'name', 'description', 'trigger_type', 'trigger_config',
        'action_type', 'action_config', 'is_active', 'last_run_at',
    ];

    protected function casts(): array
    {
        return [
            'trigger_config' => 'array',
            'action_config' => 'array',
            'is_active' => 'boolean',
            'last_run_at' => 'datetime',
        ];
    }

    public const TRIGGERS = [
        'receipt_overdue' => 'Quittance en retard',
        'contract_ending' => 'Contrat qui expire',
        'visit_reminder' => 'Rappel de visite',
        'incident_reported' => 'Incident signalé',
        'rent_due' => 'Loyer à échoir',
    ];

    public const ACTIONS = [
        'send_email' => 'Envoyer un email',
        'send_sms' => 'Envoyer un SMS',
        'create_reminder' => 'Créer un rappel',
        'mark_overdue' => 'Marquer comme impayé',
        'notify_tenant' => 'Notifier le locataire',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function logs()
    {
        return $this->hasMany(AutomationLog::class, 'automation_rule_id');
    }
}
