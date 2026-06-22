<?php

namespace Nangue\Models;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Contract extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'landlord_id', 'tenant_id', 'property_id', 'lease_type', 'duration',
        'rent', 'charges', 'deposit', 'start_date', 'end_date', 'status', 'deposit_paid',
    ];

    protected function casts(): array
    {
        return [
            'start_date' => 'date',
            'end_date' => 'date',
            'deposit_paid' => 'boolean',
            'rent' => 'decimal:2',
            'charges' => 'decimal:2',
            'deposit' => 'decimal:2',
        ];
    }

    public function landlord()
    {
        return $this->belongsTo(User::class, 'landlord_id');
    }

    public function tenant()
    {
        return $this->belongsTo(Tenant::class);
    }

    public function property()
    {
        return $this->belongsTo(Property::class);
    }

    public function receipts()
    {
        return $this->hasMany(Receipt::class);
    }
}
