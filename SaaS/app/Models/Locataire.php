<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Str;

class Locataire extends Model
{
    use SoftDeletes;

    protected $table = 'locataires';

    protected $fillable = [
        'uuid',
        'user_id',
        'company_profile_id',
        'agency_id',
        'telephone',
        'profil',
        'documentations',
        'statut',
        'deleted',
    ];

    protected $casts = [
        'documentations' => 'array',
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

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function company(): BelongsTo
    {
        return $this->belongsTo(CompanyProfile::class, 'company_profile_id');
    }

    public function agency(): BelongsTo
    {
        return $this->belongsTo(Agency::class, 'agency_id');
    }

    public function affectations(): HasMany
    {
        return $this->hasMany(Affectation::class, 'locataire_id');
    }
}
