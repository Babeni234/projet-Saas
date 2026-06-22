<?php

namespace Nangue\Models;

use Illuminate\Database\Eloquent\Model;

class Subscription extends Model
{
    protected $fillable = [
        'contract_id', 'payment_method_id', 'status', 'amount',
        'frequency', 'day_of_month', 'next_payment_date', 'last_payment_date',
        'cancelled_at',
    ];

    protected function casts(): array
    {
        return [
            'next_payment_date' => 'date',
            'last_payment_date' => 'date',
            'cancelled_at' => 'date',
            'amount' => 'decimal:2',
        ];
    }

    public function contract() { return $this->belongsTo(Contract::class); }
    public function paymentMethod() { return $this->belongsTo(PaymentMethod::class); }
    public function transactions() { return $this->hasMany(PaymentTransaction::class); }
}
