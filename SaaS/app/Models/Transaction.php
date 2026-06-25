<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    protected $fillable = [
        'user_id', 'bank_account_id', 'category_id', 'type', 'amount',
        'description', 'transaction_date', 'status', 'reference',
        'payment_method', 'property_id', 'tenant_id', 'receipt_id',
        'is_reconciled', 'reconciled_at',
    ];

    public const TYPES = [
        'income' => 'Revenu',
        'expense' => 'Dépense',
        'transfer' => 'Virement',
    ];

    public const STATUSES = [
        'pending' => 'En attente',
        'completed' => 'Effectué',
        'cancelled' => 'Annulé',
    ];

    public const PAYMENT_METHODS = [
        'bank_transfer' => 'Virement bancaire',
        'cash' => 'Espèces',
        'card' => 'Carte bancaire',
        'check' => 'Chèque',
        'online' => 'Paiement en ligne',
    ];

    protected function casts(): array
    {
        return [
            'amount' => 'decimal:2',
            'transaction_date' => 'date',
            'is_reconciled' => 'boolean',
            'reconciled_at' => 'datetime',
        ];
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function bankAccount()
    {
        return $this->belongsTo(BankAccount::class);
    }

    public function category()
    {
        return $this->belongsTo(AccountingCategory::class, 'category_id');
    }

    public function property()
    {
        return $this->belongsTo(Property::class);
    }

    public function tenant()
    {
        return $this->belongsTo(Tenant::class);
    }

    public function receipt()
    {
        return $this->belongsTo(Receipt::class);
    }
}
