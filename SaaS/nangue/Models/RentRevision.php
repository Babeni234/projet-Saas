<?php

namespace Nangue\Models;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;

class RentRevision extends Model
{
    protected $fillable = [
        'contract_id', 'landlord_id', 'previous_rent', 'new_rent',
        'percentage_change', 'index_name', 'index_value_old', 'index_value_new',
        'effective_date', 'status', 'pdf_path', 'applied_at',
    ];

    protected function casts(): array
    {
        return [
            'effective_date' => 'date',
            'applied_at' => 'datetime',
            'previous_rent' => 'decimal:2',
            'new_rent' => 'decimal:2',
            'percentage_change' => 'decimal:3',
            'index_value_old' => 'decimal:3',
            'index_value_new' => 'decimal:3',
        ];
    }

    public function contract() { return $this->belongsTo(Contract::class); }
    public function landlord() { return $this->belongsTo(User::class, 'landlord_id'); }
}
