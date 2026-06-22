<?php

namespace Nangue\Models;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Incident extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'property_id', 'contract_id', 'tenant_id', 'landlord_id',
        'title', 'description', 'category', 'urgency', 'status',
        'photos', 'assigned_to', 'resolved_at', 'resolution_notes', 'cost',
    ];

    protected function casts(): array
    {
        return [
            'photos' => 'array',
            'resolved_at' => 'date',
            'cost' => 'decimal:2',
        ];
    }

    public function property() { return $this->belongsTo(Property::class); }
    public function contract() { return $this->belongsTo(Contract::class); }
    public function tenant() { return $this->belongsTo(Tenant::class); }
    public function landlord() { return $this->belongsTo(User::class, 'landlord_id'); }
    public function assignee() { return $this->belongsTo(User::class, 'assigned_to'); }
    public function comments() { return $this->hasMany(IncidentComment::class); }
}
