<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class CompanyProfile extends Model
{
    protected $fillable = [
        'user_id',
        'business_type',
        'legal_name',
        'registration_number',
        'tax_id',
        'country',
        'address',
        'city',
        'postal_code',
        'legal_representative_name',
        'legal_representative_id_number',
        'phone',
        'logo_path',
        'verification_status',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function legalDocuments(): HasMany
    {
        return $this->hasMany(CompanyLegalDocument::class);
    }
}
