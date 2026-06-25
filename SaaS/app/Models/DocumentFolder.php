<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DocumentFolder extends Model
{
    protected $fillable = [
        'user_id', 'parent_id', 'name', 'color', 'icon', 'is_shared', 'sort_order',
    ];

    protected function casts(): array
    {
        return [
            'is_shared' => 'boolean',
            'sort_order' => 'integer',
        ];
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function parent()
    {
        return $this->belongsTo(self::class, 'parent_id');
    }

    public function children()
    {
        return $this->hasMany(self::class, 'parent_id')->orderBy('sort_order')->orderBy('name');
    }

    public function documents()
    {
        return $this->hasMany(Document::class, 'folder_id');
    }
}
