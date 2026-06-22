<?php

namespace Nangue\Models;

use Illuminate\Database\Eloquent\Model;

class RentControlZone extends Model
{
    protected $fillable = [
        'city', 'zone', 'max_price_per_sqm',
        'reference_rent', 'reference_rent_plus', 'reference_rent_minus',
    ];
}
