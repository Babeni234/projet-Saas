<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ContratTemplate extends Model
{
    protected $table = 'contrat_templates';

    protected $fillable = [
        'company_profile_id',
        'type_contrat_id',
        'content',
        'deleted',
    ];

    protected $casts = [
        'deleted' => 'boolean',
    ];

    public function company(): BelongsTo
    {
        return $this->belongsTo(CompanyProfile::class, 'company_profile_id');
    }

    public function typeContrat(): BelongsTo
    {
        return $this->belongsTo(TypeContrat::class, 'type_contrat_id');
    }
}
