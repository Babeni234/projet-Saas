<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Str;

class PaiementLoyer extends Model
{
    use SoftDeletes;

    protected $table = 'paiement_loyers';

    protected $fillable = [
        'uuid',
        'reference',
        'company_profile_id',
        'agency_id',
        'locataire_id',
        'contrat_id',
        'date_reglement',
        'montant_total',
        'mode_reglement',
        'reference_tx',
        'deleted',
    ];

    protected $casts = [
        'date_reglement' => 'date',
        'montant_total' => 'decimal:2',
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
                $company = CompanyProfile::find($model->company_profile_id);
                $companyName = $company?->legal_name ?? 'COMP';
                $firstLetter = Str::upper(Str::substr(preg_replace('/[^A-Za-z0-9]/', '', $companyName), 0, 1));
                if (empty($firstLetter)) {
                    $firstLetter = 'C';
                }
                $dateStr = now()->format('Ymd');
                $random = mt_rand(1000, 9999);
                $model->reference = "PAIE-{$firstLetter}-{$dateStr}-{$random}";
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

    public function moisPayes(): HasMany
    {
        return $this->hasMany(MoisPaye::class, 'paiement_loyer_id');
    }
}
