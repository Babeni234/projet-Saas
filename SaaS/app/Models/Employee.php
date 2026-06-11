<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

use App\Traits\HasCustomUuid;

class Employee extends Model
{
    use HasFactory, HasCustomUuid;

    protected $fillable = [
        'user_id',
        'company_profile_id',
        'agency_id',
        'phone',
        'position',
        'matricule',
    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($employee) {
            if (empty($employee->matricule)) {
                $latest = static::orderBy('id', 'desc')->first();
                $nextId = $latest ? $latest->id + 1 : 1;
                while (static::where('matricule', 'EMP-' . str_pad($nextId, 5, '0', STR_PAD_LEFT))->exists()) {
                    $nextId++;
                }
                $employee->matricule = 'EMP-' . str_pad($nextId, 5, '0', STR_PAD_LEFT);
            }
        });
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function companyProfile(): BelongsTo
    {
        return $this->belongsTo(CompanyProfile::class);
    }

    public function agency(): BelongsTo
    {
        return $this->belongsTo(Agency::class);
    }
}
