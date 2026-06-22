<?php

namespace Nangue\Models;

use Illuminate\Database\Eloquent\Model;

class Deposit extends Model
{
    protected $fillable = [
        'contract_id', 'amount', 'received_at', 'returned_at',
        'returned_amount', 'deduction_notes', 'deductions', 'status', 'bank_account',
    ];

    protected function casts(): array
    {
        return [
            'received_at' => 'date',
            'returned_at' => 'date',
            'amount' => 'decimal:2',
            'returned_amount' => 'decimal:2',
            'deductions' => 'array',
        ];
    }

    public function contract() { return $this->belongsTo(Contract::class); }
}
