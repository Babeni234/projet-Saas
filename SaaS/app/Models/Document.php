<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Document extends Model
{
    protected $fillable = [
        'name', 'file_path', 'file_type', 'file_size',
        'documentable_type', 'documentable_id',
        'document_category_id', 'user_id', 'expires_at',
        'verified', 'verified_at', 'verified_by', 'notes',
        'folder_id', 'is_starred', 'tags',
    ];

    protected function casts(): array
    {
        return [
            'expires_at' => 'date',
            'verified_at' => 'datetime',
            'verified' => 'boolean',
            'file_size' => 'integer',
            'is_starred' => 'boolean',
        ];
    }

    public function documentable()
    {
        return $this->morphTo();
    }

    public function category()
    {
        return $this->belongsTo(DocumentCategory::class, 'document_category_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function folder()
    {
        return $this->belongsTo(DocumentFolder::class, 'folder_id');
    }

    public function versions()
    {
        return $this->hasMany(DocumentVersion::class)->orderBy('version_number', 'desc');
    }

    public function shares()
    {
        return $this->hasMany(DocumentShare::class);
    }
}
