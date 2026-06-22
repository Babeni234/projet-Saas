<?php

namespace Nangue\Models;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Property extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'user_id', 'title', 'description', 'property_type', 'transaction_type',
        'address', 'city', 'postal_code', 'price', 'surface', 'rooms', 'bedrooms',
        'bathrooms', 'furnished', 'amenities', 'images', 'available_from',
        'charges_included', 'deposit', 'min_lease_duration', 'status', 'reference',
        'views', 'inquiries', 'latitude', 'longitude',
    ];

    protected function casts(): array
    {
        return [
            'furnished' => 'boolean',
            'charges_included' => 'boolean',
            'amenities' => 'array',
            'images' => 'array',
            'available_from' => 'date',
            'price' => 'decimal:2',
            'surface' => 'decimal:2',
            'deposit' => 'decimal:2',
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

    public function visits()
    {
        return $this->hasMany(Visit::class);
    }

    public function favorites()
    {
        return $this->hasMany(Favorite::class);
    }

    public function conversations()
    {
        return $this->hasMany(\Nangue\Models\Conversation::class);
    }

    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }

    public function scopeForUser($query, $userId)
    {
        return $query->where('user_id', $userId);
    }
}
