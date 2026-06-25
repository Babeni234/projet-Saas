<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AccountingCategory extends Model
{
    protected $fillable = [
        'user_id', 'name', 'type', 'color', 'icon', 'is_active', 'sort_order',
    ];

    public const TYPES = [
        'income' => 'Revenus',
        'expense' => 'Dépenses',
    ];

    protected function casts(): array
    {
        return [
            'is_active' => 'boolean',
            'sort_order' => 'integer',
        ];
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function transactions()
    {
        return $this->hasMany(Transaction::class, 'category_id');
    }

    public function budgets()
    {
        return $this->hasMany(Budget::class, 'category_id');
    }
}
