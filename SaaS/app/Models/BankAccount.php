<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BankAccount extends Model
{
    protected $fillable = [
        'user_id', 'name', 'bank_name', 'iban', 'bic', 'type',
        'balance', 'color', 'is_active',
    ];

    public const TYPES = [
        'checking' => 'Compte courant',
        'savings' => 'Livret',
        'cash' => 'Caisse',
    ];

    protected function casts(): array
    {
        return [
            'balance' => 'decimal:2',
            'is_active' => 'boolean',
        ];
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function transactions()
    {
        return $this->hasMany(Transaction::class);
    }

    public function reconciliations()
    {
        return $this->hasMany(Reconciliation::class);
    }
}
