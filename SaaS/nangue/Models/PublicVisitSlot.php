<?php

namespace Nangue\Models;

use Illuminate\Database\Eloquent\Model;

class PublicVisitSlot extends Model
{
    protected $fillable = [
        'property_id', 'date', 'start_time', 'end_time',
        'max_visitors', 'booked_count', 'status',
    ];

    protected function casts(): array
    {
        return ['date' => 'date'];
    }

    public function property() { return $this->belongsTo(Property::class); }
    public function bookings() { return $this->hasMany(PublicVisitBooking::class); }
}
