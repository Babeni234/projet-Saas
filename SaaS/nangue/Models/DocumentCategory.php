<?php

namespace Nangue\Models;

use Illuminate\Database\Eloquent\Model;

class DocumentCategory extends Model
{
    protected $fillable = ['name', 'slug', 'type', 'required'];

    protected function casts(): array
    {
        return ['required' => 'boolean'];
    }

    public function documents() { return $this->hasMany(Document::class); }
}
