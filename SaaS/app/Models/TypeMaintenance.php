<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class TypeMaintenance extends Model
{
    protected $table = 'type_maintenances';

    protected $fillable = [
        'nom',
        'description',
        'company_profile_id',
    ];

    public function company(): BelongsTo
    {
        return $this->belongsTo(CompanyProfile::class, 'company_profile_id');
    }

    public function maintenances(): HasMany
    {
        return $this->hasMany(Maintenance::class, 'type_maintenance_id');
    }
}
