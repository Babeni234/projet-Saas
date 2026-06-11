<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Str;

class Contrat extends Model
{
    use SoftDeletes;

    protected $table = 'contrats';

    protected $fillable = [
        'uuid',
        'numero',
        'company_profile_id',
        'agency_id',
        'locataire_id',
        'logement_id',
        'type_contrat_id',
        'loyer',
        'caution',
        'debut',
        'fin',
        'content',
        'statut',
        'deleted',
    ];

    protected $casts = [
        'loyer' => 'decimal:2',
        'caution' => 'decimal:2',
        'debut' => 'date',
        'fin' => 'date',
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
                $count = static::withTrashed()
                               ->where('company_profile_id', $model->company_profile_id)
                               ->count();
                $model->numero = 'CTR-' . str_pad($count + 1, 5, '0', STR_PAD_LEFT);
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
