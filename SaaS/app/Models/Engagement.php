<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Engagement extends Model
{
    protected $table = 'engagements';

    protected $fillable = [
        'company_profile_id',
        'agency_id',
        'batiment_id',
        'locataire_id',
        'contrat_id',
        'type_engagement_id',
        'reference',
        'date_debut',
        'date_fin',
        'montant',
        'statut',
        'statut_honneur',
        'instructions',
        'content',
    ];

    public function company(): BelongsTo
    {
        return $this->belongsTo(CompanyProfile::class, 'company_profile_id');
    }

    public function agency(): BelongsTo
    {
        return $this->belongsTo(Agency::class, 'agency_id');
    }

    public function batiment(): BelongsTo
    {
        return $this->belongsTo(Batiment::class, 'batiment_id');
    }

    public function locataire(): BelongsTo
    {
        return $this->belongsTo(Locataire::class, 'locataire_id');
    }

    public function contrat(): BelongsTo
    {
        return $this->belongsTo(Contrat::class, 'contrat_id');
    }

    public function typeEngagement(): BelongsTo
    {
        return $this->belongsTo(TypeEngagement::class, 'type_engagement_id');
    }
}
