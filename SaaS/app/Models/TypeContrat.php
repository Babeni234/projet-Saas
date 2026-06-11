<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class TypeContrat extends Model
{
    protected $table = 'type_contrats';

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

    public function categories(): HasMany
    {
        return $this->hasMany(Categorie::class, 'type_contrat_id');
    }

    public function affectations(): HasMany
    {
        return $this->hasMany(Affectation::class, 'type_contrat_id');
    }
}
