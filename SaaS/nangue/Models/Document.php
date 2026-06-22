<?php

namespace Nangue\Models;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Document extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'name', 'file_path', 'file_type', 'file_size',
        'documentable_type', 'documentable_id', 'document_category_id',
        'user_id', 'expires_at', 'verified', 'verified_at', 'verified_by', 'notes',
    ];

    protected function casts(): array
    {
        return [
            'expires_at' => 'date',
            'verified' => 'boolean',
            'verified_at' => 'datetime',
        ];
    }

    public function documentable() { return $this->morphTo(); }
    public function category() { return $this->belongsTo(DocumentCategory::class, 'document_category_id'); }
    public function user() { return $this->belongsTo(User::class); }
    public function verifiedBy() { return $this->belongsTo(User::class, 'verified_by'); }
}
