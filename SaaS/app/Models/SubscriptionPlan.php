<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SubscriptionPlan extends Model
{
    protected $fillable = [
        'name',
        'slug',
        'account_type',
        'price',
        'max_logements',
        'max_locataires',
        'max_employees',
        'max_agencies',
        'max_buildings',
        'has_ai',
        'billing_cycle',
        'features',
        'popular',
        'color',
    ];

    protected $casts = [
        'features' => 'array',
        'popular' => 'boolean',
        'price' => 'double',
        'max_logements' => 'integer',
        'max_locataires' => 'integer',
        'max_employees' => 'integer',
        'max_agencies' => 'integer',
        'max_buildings' => 'integer',
        'has_ai' => 'boolean',
    ];
}
