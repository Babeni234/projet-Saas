<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

use App\Traits\HasCustomUuid;

class Agency extends Model
{
    use SoftDeletes, HasCustomUuid;

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($agency) {
            if (empty($agency->code)) {
                $latest = static::orderBy('id', 'desc')->first();
                $nextId = $latest ? $latest->id + 1 : 1;
                while (static::where('code', 'AG-' . str_pad($nextId, 5, '0', STR_PAD_LEFT))->exists()) {
                    $nextId++;
                }
                $agency->code = 'AG-' . str_pad($nextId, 5, '0', STR_PAD_LEFT);
            }
        });
    }

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
        'company_profile_id',
        'chef_id',
    ];

    public function companyProfile(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(CompanyProfile::class);
    }

    public function employees(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(Employee::class);
    }

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
