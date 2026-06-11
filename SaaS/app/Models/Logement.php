<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Str;

class Logement extends Model
{
    use SoftDeletes;

    protected $table = 'logements';

    protected $fillable = [
        'uuid',
        'reference',
        'company_profile_id',
        'agency_id',
        'batiment_id',
        'categorie_id',
        'etage',
        'surface',
        'loyer',
        'statut',
        'deleted',
    ];

    protected $casts = [
        'deleted' => 'boolean',
        'loyer'   => 'decimal:2',
        'etage'   => 'integer',
        'surface' => 'integer',
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
                $model->reference = 'BIEN-' . str_pad($count + 1, 5, '0', STR_PAD_LEFT);
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

    public function categorie(): BelongsTo
    {
        return $this->belongsTo(Categorie::class, 'categorie_id');
    }
}
