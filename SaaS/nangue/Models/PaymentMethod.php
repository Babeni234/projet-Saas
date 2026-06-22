<?php

namespace Nangue\Models;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;

class PaymentMethod extends Model
{
    protected $fillable = [
        'user_id', 'type', 'provider', 'provider_id', 'last_four', 'brand',
        'iban_last_four', 'bic', 'mandate_id', 'mandate_signed_at', 'is_default', 'is_active',
    ];

    protected function casts(): array
    {
        return [
            'mandate_signed_at' => 'date',
            'is_default' => 'boolean',
            'is_active' => 'boolean',
        ];
    }

    public function user() { return $this->belongsTo(User::class); }
}
