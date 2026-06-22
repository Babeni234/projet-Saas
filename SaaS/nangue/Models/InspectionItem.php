<?php

namespace Nangue\Models;

use Illuminate\Database\Eloquent\Model;

class InspectionItem extends Model
{
    protected $fillable = ['inspection_id', 'room', 'item', 'condition', 'comment', 'photo'];

    public function inspection() { return $this->belongsTo(Inspection::class); }
}
