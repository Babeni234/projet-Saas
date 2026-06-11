<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Categorie extends Model
{
    protected $table = 'categories';

    protected $fillable = [
        'company_profile_id',
        'nom',
        'description',
        'deleted',
    ];

    protected $casts = [
        'deleted' => 'boolean',
    ];

    public function company(): BelongsTo
    {
        return $this->belongsTo(CompanyProfile::class, 'company_profile_id');
    }

    public function logements(): HasMany
    {
        return $this->hasMany(Logement::class, 'categorie_id');
    }
}
