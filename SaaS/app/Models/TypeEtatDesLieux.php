<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class TypeEtatDesLieux extends Model
{
    protected $table = 'type_etat_des_lieux';

    protected $fillable = [
        'company_profile_id',
        'nom',
        'description',
        'template',
    ];

    public function company(): BelongsTo
    {
        return $this->belongsTo(CompanyProfile::class, 'company_profile_id');
    }
}
