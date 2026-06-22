<?php

namespace Nangue\Models;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;

class IncidentComment extends Model
{
    protected $fillable = ['incident_id', 'user_id', 'content'];

    public function incident() { return $this->belongsTo(Incident::class); }
    public function user() { return $this->belongsTo(User::class); }
}
