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
