<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ImmotokSubscription extends Model
{
    use HasFactory;

    protected $table = 'immotok_subscriptions';

    public $timestamps = false;

    protected $fillable = [
        'immotok_client_id',
        'company_profile_id',
    ];

    public function client(): BelongsTo
    {
        return $this->belongsTo(ImmotokClient::class, 'immotok_client_id');
    }

    public function companyProfile(): BelongsTo
    {
        return $this->belongsTo(CompanyProfile::class, 'company_profile_id');
    }
}
