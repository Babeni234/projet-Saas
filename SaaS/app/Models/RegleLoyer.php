<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class RegleLoyer extends Model
{
    protected $table = 'regle_loyers';

    protected $fillable = [
        'company_profile_id',
        'jour_declenchement',
        'taux_penalite',
    ];

    protected $casts = [
        'jour_declenchement' => 'integer',
        'taux_penalite' => 'decimal:2',
    ];

    public function company(): BelongsTo
    {
        return $this->belongsTo(CompanyProfile::class, 'company_profile_id');
    }
}
