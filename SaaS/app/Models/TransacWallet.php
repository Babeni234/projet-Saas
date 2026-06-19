<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class TransacWallet extends Model
{
    protected $table = 'transacwallet';

    protected $fillable = [
        'wallet_id',
        'type', // 'debit', 'credit', 'recharge', 'virement'
        'amount',
        'description',
        'reference_tx',
    ];

    protected $casts = [
        'amount' => 'decimal:2',
    ];

    public function wallet(): BelongsTo
    {
        return $this->belongsTo(Wallet::class, 'wallet_id');
    }
}
