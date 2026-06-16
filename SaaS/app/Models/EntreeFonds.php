<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Str;

class EntreeFonds extends Model
{
    use SoftDeletes;

    protected $table = 'entree_fonds';

    protected $fillable = [
        'uuid',
        'company_profile_id',
        'agency_id',
        'titre',
        'description',
        'montant',
        'date_entree',
        'categorie',
        'reference',
        'statut',
        'deleted',
    ];

    protected $casts = [
        'montant' => 'decimal:2',
        'date_entree' => 'date',
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
                    $firstLetter = 'E';
                }
                $dateStr = now()->format('Ymd');
                $random = mt_rand(1000, 9999);
                $model->reference = "EF-{$firstLetter}-{$dateStr}-{$random}";
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
}
