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
    ];

    protected function casts(): array
    {
        return [
            'expires_at' => 'date',
            'verified_at' => 'datetime',
            'verified' => 'boolean',
            'file_size' => 'integer',
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
}
