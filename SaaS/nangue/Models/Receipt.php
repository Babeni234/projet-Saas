<?php

namespace Nangue\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Receipt extends Model
{
    use HasFactory;

    protected $fillable = [
        'contract_id', 'reference', 'period', 'rent', 'charges', 'total',
        'due_date', 'payment_date', 'status', 'pdf_path',
    ];

    protected function casts(): array
    {
        return [
            'due_date' => 'date',
            'payment_date' => 'date',
            'rent' => 'decimal:2',
            'charges' => 'decimal:2',
            'total' => 'decimal:2',
        ];
    }

    public function contract()
    {
        return $this->belongsTo(Contract::class);
    }
}
