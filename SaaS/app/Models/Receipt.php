<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Receipt extends Model
{
    protected $fillable = [
        'contract_id', 'reference', 'period', 'rent', 'charges',
        'total', 'due_date', 'payment_date', 'status', 'pdf_path',
    ];

    protected function casts(): array
    {
        return [
            'rent' => 'decimal:2',
            'charges' => 'decimal:2',
            'total' => 'decimal:2',
            'due_date' => 'date',
            'payment_date' => 'date',
        ];
    }

    public function contract()
    {
        return $this->belongsTo(Contract::class);
    }
}
