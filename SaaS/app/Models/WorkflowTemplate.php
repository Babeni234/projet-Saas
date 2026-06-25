<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class WorkflowTemplate extends Model
{
    protected $fillable = ['name', 'description', 'category', 'config', 'is_built_in'];

    protected function casts(): array
    {
        return [
            'config' => 'array',
            'is_built_in' => 'boolean',
        ];
    }

    public const CATEGORIES = [
        'general' => 'Général',
        'financial' => 'Finances & Quittances',
        'incident' => 'Incidents',
        'visit' => 'Visites',
        'contract' => 'Contrats & Baux',
    ];
}
