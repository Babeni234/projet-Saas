<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Str;

class Renouvellement extends Model
{
    use SoftDeletes;

    protected $table = 'renouvellements';

    protected $fillable = [
        'uuid',
        'reference',
        'company_profile_id',
        'agency_id',
        'locataire_id',
        'contrat_id',
        'nouveau_loyer',
        'cycle_paiement',
        'duree',
        'frais_contrat',
        'motif_demande',
        'motif_rejet',
        'statut',
        'deleted',
    ];

    protected $casts = [
        'nouveau_loyer' => 'decimal:2',
        'frais_contrat' => 'decimal:2',
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
                $model->reference = 'RNV-' . str_pad($count + 1, 5, '0', STR_PAD_LEFT);
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

    public function contrat(): BelongsTo
    {
        return $this->belongsTo(Contrat::class, 'contrat_id');
    }
}
