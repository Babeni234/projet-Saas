<?php

namespace Nangue\Models;

use Illuminate\Database\Eloquent\Model;

class RentControlCompliance extends Model
{
    protected $table = 'rent_control_compliance';
    protected $fillable = [
        'property_id', 'zone_id', 'current_rent_per_sqm',
        'max_allowed_rent_per_sqm', 'is_compliant', 'excess_amount', 'notes',
    ];

    protected function casts(): array
    {
        return ['is_compliant' => 'boolean'];
    }

    public function property() { return $this->belongsTo(Property::class); }
    public function zone() { return $this->belongsTo(RentControlZone::class); }
}
