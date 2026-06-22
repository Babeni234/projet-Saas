<?php

namespace Nangue\Models;

use Illuminate\Database\Eloquent\Model;

class PublicVisitBooking extends Model
{
    protected $fillable = [
        'slot_id', 'property_id', 'visitor_name', 'visitor_email',
        'visitor_phone', 'message', 'status', 'confirmation_token', 'confirmed_at',
    ];

    protected function casts(): array
    {
        return ['confirmed_at' => 'datetime'];
    }

    public function slot() { return $this->belongsTo(PublicVisitSlot::class); }
    public function property() { return $this->belongsTo(Property::class); }
}
