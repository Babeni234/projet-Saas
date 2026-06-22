<?php

namespace Nangue\Models;

use Illuminate\Database\Eloquent\Model;

class PaymentTransaction extends Model
{
    protected $fillable = [
        'subscription_id', 'receipt_id', 'payment_method_id',
        'provider_transaction_id', 'amount', 'currency', 'status',
        'failure_reason', 'processed_at',
    ];

    protected function casts(): array
    {
        return [
            'processed_at' => 'datetime',
            'amount' => 'decimal:2',
        ];
    }

    public function subscription() { return $this->belongsTo(Subscription::class); }
    public function receipt() { return $this->belongsTo(Receipt::class); }
    public function paymentMethod() { return $this->belongsTo(PaymentMethod::class); }
}
