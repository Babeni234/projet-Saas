<?php

namespace Nangue\Models;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;

class FiscalYear extends Model
{
    protected $fillable = [
        'user_id', 'year', 'total_revenue', 'total_expenses',
        'net_income', 'status', 'data',
    ];

    protected function casts(): array
    {
        return [
            'data' => 'array',
            'total_revenue' => 'decimal:2',
            'total_expenses' => 'decimal:2',
            'net_income' => 'decimal:2',
        ];
    }

    public function user() { return $this->belongsTo(User::class); }
    public function expenses() { return $this->hasMany(FiscalExpense::class); }

    public function recalculate(): void
    {
        $this->total_expenses = $this->expenses()->sum('amount');
        $this->net_income = $this->total_revenue - $this->total_expenses;
        $this->save();
    }
}
