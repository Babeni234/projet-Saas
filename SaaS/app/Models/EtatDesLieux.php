<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Str;

class EtatDesLieux extends Model
{
    protected $table = 'etat_des_lieux';

    protected $fillable = [
        'uuid',
        'company_profile_id',
        'agency_id',
        'batiment_id',
        'logement_id',
        'locataire_id',
        'contrat_id',
        'type_etat_des_lieux_id',
        'reference',
        'date',
        'statut',
        'instructions',
        'content',
    ];

    protected $casts = [
        'date' => 'date',
    ];

    protected static function boot(): void
    {
        parent::boot();

        static::creating(function (self $model) {
            if (empty($model->uuid)) {
                $model->uuid = (string) Str::uuid();
            }
        });
    }

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

    public function logement(): BelongsTo
    {
        return $this->belongsTo(Logement::class, 'logement_id');
    }

    public function locataire(): BelongsTo
    {
        return $this->belongsTo(Locataire::class, 'locataire_id');
    }

    public function contrat(): BelongsTo
    {
        return $this->belongsTo(Contrat::class, 'contrat_id');
    }

    public function typeEtatDesLieux(): BelongsTo
    {
        return $this->belongsTo(TypeEtatDesLieux::class, 'type_etat_des_lieux_id');
    }
}
