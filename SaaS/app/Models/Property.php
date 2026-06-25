<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Property extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'user_id', 'title', 'description', 'property_type', 'transaction_type',
        'address', 'city', 'postal_code', 'price', 'surface', 'rooms', 'bedrooms',
        'bathrooms', 'furnished', 'amenities', 'images', 'available_from',
        'charges_included', 'deposit', 'min_lease_duration', 'status', 'reference',
        'latitude', 'longitude',
    ];

    protected function casts(): array
    {
        return [
            'furnished' => 'boolean',
            'charges_included' => 'boolean',
            'amenities' => 'array',
            'images' => 'array',
            'price' => 'decimal:2',
            'surface' => 'decimal:2',
            'deposit' => 'decimal:2',
            'latitude' => 'decimal:7',
            'longitude' => 'decimal:7',
            'available_from' => 'date',
        ];
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function contracts()
    {
        return $this->hasMany(Contract::class);
    }
}
