<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Illuminate\Support\Str;

class Maintenance extends Model
{
    protected $table = 'maintenances';

    protected $fillable = [
        'uuid',
        'company_profile_id',
        'agency_id',
        'type_maintenance_id',
        'description',
        'priorite',
        'budget',
        'statut',
        'assigned_to',
        'maintenanceable_type',
        'maintenanceable_id',
        'date_debut_execution',
        'date_fin_execution',
        'duree_execution_minutes',
    ];

    protected $casts = [
        'budget' => 'decimal:2',
        'date_debut_execution' => 'datetime',
        'date_fin_execution' => 'datetime',
        'duree_execution_minutes' => 'integer',
    ];

    protected static function boot(): void
    {
        parent::boot();

        static::creating(function (self $model) {
            if (empty($model->uuid)) {
                $model->uuid = (string) Str::uuid();
            }
        });

        static::created(function (self $model) {
            if ($model->maintenanceable) {
                $target = $model->maintenanceable;
                $target->statut_maintenance = 'En cours de maintenance';
                $target->save();
            }
        });

        static::updated(function (self $model) {
            if ($model->isDirty('statut') && $model->statut === 'Terminé') {
                $model->syncTargetStatus();
            }
        });

        static::deleted(function (self $model) {
            $model->syncTargetStatus();
        });
    }

    /**
     * Synchronise le statut de maintenance de la cible.
     */
    public function syncTargetStatus(): void
    {
        $target = $this->maintenanceable;
        if (!$target) return;

        // Vérifie s'il reste d'autres tickets actifs pour cette cible
        $hasActive = static::where('maintenanceable_type', $this->maintenanceable_type)
            ->where('maintenanceable_id', $this->maintenanceable_id)
            ->where('id', '!=', $this->id)
            ->whereIn('statut', ['Créé', 'En cours'])
            ->exists();

        $newStatus = $hasActive ? 'En cours de maintenance' : 'Sain';
        $target->statut_maintenance = $newStatus;
        $target->save();
    }

    public function company(): BelongsTo
    {
        return $this->belongsTo(CompanyProfile::class, 'company_profile_id');
    }

    public function agency(): BelongsTo
    {
        return $this->belongsTo(Agency::class, 'agency_id');
    }

    public function typeMaintenance(): BelongsTo
    {
        return $this->belongsTo(TypeMaintenance::class, 'type_maintenance_id');
    }

    public function assignedUser(): BelongsTo
    {
        return $this->belongsTo(User::class, 'assigned_to');
    }

    public function maintenanceable(): MorphTo
    {
        return $this->morphTo();
    }
}
