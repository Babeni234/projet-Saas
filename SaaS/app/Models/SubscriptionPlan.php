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
        'billing_cycle',
        'features',
        'popular',
        'color',
    ];

    protected $casts = [
        'features' => 'array',
        'popular' => 'boolean',
        'price' => 'double',
    ];
}
