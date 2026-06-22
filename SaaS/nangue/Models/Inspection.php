<?php

namespace Nangue\Models;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;

class Inspection extends Model
{
    protected $fillable = [
        'contract_id', 'property_id', 'landlord_id', 'tenant_id',
        'type', 'inspection_date', 'rooms', 'notes', 'status', 'pdf_path',
    ];

    protected function casts(): array
    {
        return [
            'inspection_date' => 'date',
            'rooms' => 'array',
        ];
    }

    public function contract() { return $this->belongsTo(Contract::class); }
    public function property() { return $this->belongsTo(Property::class); }
    public function landlord() { return $this->belongsTo(User::class, 'landlord_id'); }
    public function tenant() { return $this->belongsTo(Tenant::class); }
    public function items() { return $this->hasMany(InspectionItem::class); }
}
