<?php

namespace Nangue\Models;

use Illuminate\Database\Eloquent\Model;

class InsuranceGuarantee extends Model
{
    protected $fillable = [
        'contract_id', 'tenant_id', 'type', 'provider', 'reference_number',
        'covered_amount', 'start_date', 'end_date', 'status', 'document_path', 'notes',
    ];

    protected function casts(): array
    {
        return [
            'start_date' => 'date',
            'end_date' => 'date',
            'covered_amount' => 'decimal:2',
        ];
    }

    public function contract() { return $this->belongsTo(Contract::class); }
    public function tenant() { return $this->belongsTo(Tenant::class); }
}
