<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Str;

class Affectation extends Model
{
    use SoftDeletes;

    protected $table = 'affectations';

    protected $fillable = [
        'uuid',
        'reference',
        'company_profile_id',
        'agency_id',
        'locataire_id',
        'logement_id',
        'type_contrat_id',
        'loyer',
        'caution',
        'date_debut',
        'date_fin',
        'type_bail',
        'duree',
        'cycle_paiement',
        'statut',
        'deleted',
    ];

    protected $casts = [
        'loyer' => 'decimal:2',
        'caution' => 'decimal:2',
        'date_debut' => 'date',
        'date_fin' => 'date',
        'deleted' => 'boolean',
    ];

    protected static function boot(): void
    {
        parent::boot();

        static::creating(function (self $model) {
            if (empty($model->uuid)) {
                $model->uuid = (string) Str::uuid();
            }
            if (empty($model->reference)) {
                $count = static::withTrashed()
                               ->where('company_profile_id', $model->company_profile_id)
                               ->count();
                $model->reference = 'AFF-' . str_pad($count + 1, 5, '0', STR_PAD_LEFT);
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

    public function locataire(): BelongsTo
    {
        return $this->belongsTo(Locataire::class, 'locataire_id');
    }

    public function logement(): BelongsTo
    {
        return $this->belongsTo(Logement::class, 'logement_id');
    }

    public function typeContrat(): BelongsTo
    {
        return $this->belongsTo(TypeContrat::class, 'type_contrat_id');
    }
}
