<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Reconciliation extends Model
{
    protected $fillable = [
        'user_id', 'bank_account_id', 'date_from', 'date_to',
        'opening_balance', 'closing_balance', 'statement_balance',
        'difference', 'status', 'notes', 'started_at', 'completed_at',
    ];

    public const STATUSES = [
        'pending' => 'En cours',
        'completed' => 'Rapproché',
        'cancelled' => 'Annulé',
    ];

    protected function casts(): array
    {
        return [
            'date_from' => 'date',
            'date_to' => 'date',
            'opening_balance' => 'decimal:2',
            'closing_balance' => 'decimal:2',
            'statement_balance' => 'decimal:2',
            'difference' => 'decimal:2',
            'started_at' => 'datetime',
            'completed_at' => 'datetime',
        ];
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function bankAccount()
    {
        return $this->belongsTo(BankAccount::class);
    }
}
