<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Visit extends Model
{
    protected $fillable = [
        'property_id', 'landlord_id', 'visitor_name', 'visitor_phone',
        'visitor_email', 'date', 'time', 'notes', 'status',
    ];

    protected function casts(): array
    {
        return [
            'date' => 'date',
        ];
    }

    public function property()
    {
        return $this->belongsTo(Property::class);
    }
}
