<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Str;

class Tresorerie extends Model
{
    use SoftDeletes;

    protected $table = 'tresoreries';

    protected $fillable = [
        'uuid',
        'company_profile_id',
        'agency_id',
        'montant',
        'date_transaction',
        'source_type',
        'source_id',
        'motif',
        'deleted',
    ];

    protected $casts = [
        'montant' => 'decimal:2',
        'date_transaction' => 'date',
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

    /**
     * Polymorphic source relationship.
     */
    public function source()
    {
        return $this->morphTo();
    }

    /**
     * Helper to sync database entries to treasury.
     */
    public static function enregistrer($source, float $montant, string $motif, ?string $date = null)
    {
        return self::updateOrCreate(
            [
                'source_type' => get_class($source),
                'source_id'   => $source->id,
            ],
            [
                'company_profile_id' => $source->company_profile_id,
                'agency_id'          => $source->agency_id,
                'montant'            => $montant,
                'date_transaction'   => $date ?: now()->toDateString(),
                'motif'              => $motif,
                'deleted'            => false,
            ]
        );
    }
}
