<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Devise extends Model
{
    protected $table = 'devises';

    protected $fillable = [
        'company_profile_id',
        'devise',
        'code',
        'symbole',
    ];

    public function company(): BelongsTo
    {
        return $this->belongsTo(CompanyProfile::class, 'company_profile_id');
    }
}
