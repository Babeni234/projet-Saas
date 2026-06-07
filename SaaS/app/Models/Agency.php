<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Agency extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'name',
        'code',
        'description',
        'email',
        'phone',
        'fax',
        'address',
        'city',
        'postal_code',
        'country',
        'latitude',
        'longitude',
        'manager_name',
        'manager_email',
        'manager_phone',
        'status',
        'employee_count',
        'establishment_date',
        'metadata',
    ];

    protected $casts = [
        'metadata' => 'json',
        'establishment_date' => 'date',
        'latitude' => 'float',
        'longitude' => 'float',
    ];

    /**
     * Scope to get only active agencies
     */
    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }

    /**
     * Scope to get only inactive agencies
     */
    public function scopeInactive($query)
    {
        return $query->where('status', 'inactive');
    }

    /**
     * Get the full address
     */
    public function getFullAddressAttribute()
    {
        $parts = [
            $this->address,
            $this->postal_code,
            $this->city,
            $this->country
        ];

        return implode(', ', array_filter($parts));
    }

    /**
     * Get the manager contact info
     */
    public function getManagerContactAttribute()
    {
        return [
            'name' => $this->manager_name,
            'email' => $this->manager_email,
            'phone' => $this->manager_phone,
        ];
    }

    /**
     * Get status badge class
     */
    public function getStatusBadgeAttribute()
    {
        return match($this->status) {
            'active' => 'bg-green-100 text-green-800',
            'inactive' => 'bg-gray-100 text-gray-800',
            'suspended' => 'bg-red-100 text-red-800',
            default => 'bg-gray-100 text-gray-800'
        };
    }

    /**
     * Get status label
     */
    public function getStatusLabelAttribute()
    {
        return match($this->status) {
            'active' => 'Actif',
            'inactive' => 'Inactif',
            'suspended' => 'Suspendu',
            default => 'Inconnu'
        };
    }
}
