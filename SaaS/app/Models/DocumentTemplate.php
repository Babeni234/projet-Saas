<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DocumentTemplate extends Model
{
    protected $fillable = [
        'user_id', 'name', 'type', 'description', 'content', 'color', 'is_built_in',
    ];

    public const TYPES = [
        'bail' => 'Bail',
        'avenant' => 'Avenant',
        'etat_lieux' => 'État des lieux',
        'quittance' => 'Quittance',
        'other' => 'Autre',
    ];

    protected function casts(): array
    {
        return [
            'content' => 'array',
            'is_built_in' => 'boolean',
        ];
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
