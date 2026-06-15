<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class TypeDepense extends Model
{
    protected $table = 'type_depenses';

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
