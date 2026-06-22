<?php

namespace Nangue\Models;

use Illuminate\Database\Eloquent\Model;

class FiscalExpense extends Model
{
    protected $fillable = [
        'fiscal_year_id', 'property_id', 'category', 'description',
        'amount', 'expense_date', 'document_path', 'deductible',
    ];

    protected function casts(): array
    {
        return [
            'expense_date' => 'date',
            'deductible' => 'boolean',
            'amount' => 'decimal:2',
        ];
    }

    public function fiscalYear() { return $this->belongsTo(FiscalYear::class); }
    public function property() { return $this->belongsTo(Property::class); }
}
