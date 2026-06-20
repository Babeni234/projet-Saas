<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PendingWalletPayment extends Model
{
    protected $table = 'pending_wallet_payments';

    protected $fillable = [
        'company_profile_id',
        'agency_id',
        'locataire_id',
        'type',
        'target_id',
        'amount',
        'token',
        'data',
        'status',
    ];

    protected $casts = [
        'amount' => 'decimal:2',
        'data' => 'array',
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
}
