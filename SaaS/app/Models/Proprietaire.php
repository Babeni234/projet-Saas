<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Str;

class Proprietaire extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'uuid',
        'synced',
        'company_profile_id',
        'type',
        'nom',
        'prenom',
        'raison_sociale',
        'siret',
        'numero_contribuable',
        'piece_identite_type',
        'piece_identite_numero',
        'email',
        'telephone',
        'telephone_secondaire',
        'adresse',
        'ville',
        'pays',
        'code_postal',
        'banque',
        'rib',
        'swift',
        'statut',
        'date_debut_contrat',
        'date_fin_contrat',
        'commission_taux',
        'type_contrat',
        'notes',
        'metadata',
        'documents',
        'deleted',
    ];

    protected $casts = [
        'metadata'           => 'array',
        'documents'          => 'array',
        'date_debut_contrat' => 'date',
        'date_fin_contrat'   => 'date',
        'commission_taux'    => 'decimal:2',
        'synced'             => 'boolean',
        'deleted'            => 'boolean',
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
        });
    }

    // ── Relations ────────────────────────────────────────────────────────────

    public function company(): BelongsTo
    {
        return $this->belongsTo(CompanyProfile::class, 'company_profile_id');
    }

    public function batiments(): HasMany
    {
        return $this->hasMany(Batiment::class, 'proprietaire_id');
    }

    // ── Accesseurs ──────────────────────────────────────────────────────────

    /**
     * Nom complet (prénom + nom ou raison sociale).
     */
    public function getNomCompletAttribute(): string
    {
        if ($this->type === 'personne_morale') {
            return $this->raison_sociale ?? $this->nom;
        }

        return trim(($this->prenom ?? '') . ' ' . $this->nom);
    }

    /**
     * Initiales pour avatar.
     */
    public function getInitialesAttribute(): string
    {
        $name = $this->nom_complet;
        $parts = explode(' ', $name);

        return strtoupper(
            implode('', array_map(fn($p) => $p[0] ?? '', array_slice($parts, 0, 2)))
        );
    }
}
