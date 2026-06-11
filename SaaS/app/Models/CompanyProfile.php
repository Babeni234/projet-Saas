<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

use App\Traits\HasCustomUuid;

class CompanyProfile extends Model
{
    use HasCustomUuid;
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

    public function employees(): HasMany
    {
        return $this->hasMany(Employee::class);
    }

    public function agencies(): HasMany
    {
        return $this->hasMany(Agency::class);
    }
}
