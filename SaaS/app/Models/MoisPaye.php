<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class MoisPaye extends Model
{
    protected $table = 'mois_payes';

    protected $fillable = [
        'paiement_loyer_id',
        'periode',
        'loyer_de_base',
        'penalite',
        'total_paye',
    ];

    protected $casts = [
        'loyer_de_base' => 'decimal:2',
        'penalite' => 'decimal:2',
        'total_paye' => 'decimal:2',
    ];

    public function paiementLoyer(): BelongsTo
    {
        return $this->belongsTo(PaiementLoyer::class, 'paiement_loyer_id');
    }
}
