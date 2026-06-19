<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Wallet extends Model
{
    use SoftDeletes;

    protected $table = 'wallets';

    protected $fillable = [
        'company_profile_id',
        'agency_id',
        'locataire_id',
        'solde',
        'password',
    ];

    protected $casts = [
        'solde' => 'decimal:2',
    ];

    protected $hidden = [
        'password',
    ];

    public function company(): BelongsTo
    {
        return $this->belongsTo(CompanyProfile::class, 'company_profile_id');
    }

    public function agency(): BelongsTo
    {
        return $this->belongsTo(Agency::class, 'agency_id');
    }

    public function locataire(): BelongsTo
    {
        return $this->belongsTo(Locataire::class, 'locataire_id');
    }

    public function transactions(): HasMany
    {
        return $this->hasMany(TransacWallet::class, 'wallet_id');
    }
}
