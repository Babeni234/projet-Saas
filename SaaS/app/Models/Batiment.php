<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Str;

class Batiment extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'uuid',
        'synced',
        'company_profile_id',
        'agency_id',
        'proprietaire_id',
        'nom',
        'reference',
        'pays',
        'ville',
        'quartier',
        'adresse',
        'code_postal',
        'latitude',
        'longitude',
        'etages',
        'appartements',
        'surface_totale',
        'annee_construction',
        'type_batiment',
        'type_structure',
        'nombre_parkings',
        'ascenseur',
        'gardiennage',
        'piscine',
        'generatrice',
        'statut',
        'description',
        'notes_internes',
        'metadata',
        'deleted',
    ];

    protected $casts = [
        'metadata'         => 'array',
        'ascenseur'        => 'boolean',
        'gardiennage'      => 'boolean',
        'piscine'          => 'boolean',
        'generatrice'      => 'boolean',
        'synced'           => 'boolean',
        'deleted'          => 'boolean',
        'surface_totale'   => 'decimal:2',
        'latitude'         => 'decimal:8',
        'longitude'        => 'decimal:8',
    ];

    /**
     * Génère automatiquement un UUID à la création.
     */
    protected static function boot(): void
    {
        parent::boot();

        static::creating(function (self $model) {
            if (empty($model->uuid)) {
                $model->uuid = (string) Str::uuid();
            }
            // Génère la référence si pas fournie
            if (empty($model->reference)) {
                $count = static::withTrashed()
                               ->where('company_profile_id', $model->company_profile_id)
                               ->count();
                $model->reference = 'BAT-' . str_pad($count + 1, 4, '0', STR_PAD_LEFT);
            }
        });
    }

    // ── Relations ────────────────────────────────────────────────────────────

    public function company(): BelongsTo
    {
        return $this->belongsTo(CompanyProfile::class, 'company_profile_id');
    }

    public function agency(): BelongsTo
    {
        return $this->belongsTo(Agency::class, 'agency_id');
    }

    public function proprietaire(): BelongsTo
    {
        return $this->belongsTo(Proprietaire::class, 'proprietaire_id');
    }

    public function logements(): HasMany
    {
        return $this->hasMany(Logement::class, 'batiment_id');
    }

    // ── Accesseurs ──────────────────────────────────────────────────────────

    public function getInitialesAttribute(): string
    {
        $parts = explode(' ', $this->nom);
        return strtoupper(
            implode('', array_map(fn($p) => $p[0] ?? '', array_slice($parts, 0, 2)))
        );
    }
}
