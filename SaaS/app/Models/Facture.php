<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Str;

class Facture extends Model
{
    use SoftDeletes;

    protected $table = 'factures';

    protected $fillable = [
        'uuid',
        'numero',
        'company_profile_id',
        'agency_id',
        'locataire_id',
        'contrat_id',
        'type_facture_id',
        'periode',
        'date_emission',
        'date_echeance',
        'items',
        'total',
        'montant_paye',
        'statut',
        'mode_reglement',
        'deleted',
    ];

    protected $casts = [
        'items' => 'array',
        'total' => 'decimal:2',
        'montant_paye' => 'decimal:2',
        'date_emission' => 'date',
        'date_echeance' => 'date',
        'deleted' => 'boolean',
    ];

    protected static function boot(): void
    {
        parent::boot();

        static::creating(function (self $model) {
            if (empty($model->uuid)) {
                $model->uuid = (string) Str::uuid();
            }
            if (empty($model->numero)) {
                $company = CompanyProfile::find($model->company_profile_id);
                $companyName = $company?->legal_name ?? 'COMP';
                $firstLetter = Str::upper(Str::substr(preg_replace('/[^A-Za-z0-9]/', '', $companyName), 0, 1));
                if (empty($firstLetter)) {
                    $firstLetter = 'C';
                }
                $dateStr = now()->format('Ymd');
                $random = mt_rand(1000, 9999);
                $model->numero = "FACT-{$firstLetter}-{$dateStr}-{$random}";
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

    public function typeFacture(): BelongsTo
    {
        return $this->belongsTo(TypeFacture::class, 'type_facture_id');
    }
}
