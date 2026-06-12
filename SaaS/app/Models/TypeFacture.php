<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class TypeFacture extends Model
{
    protected $table = 'type_factures';

    protected $fillable = [
        'company_profile_id',
        'nom',
        'description',
        'deleted',
    ];

    protected $casts = [
        'deleted' => 'boolean',
    ];

    public function company(): BelongsTo
    {
        return $this->belongsTo(CompanyProfile::class, 'company_profile_id');
    }
}
