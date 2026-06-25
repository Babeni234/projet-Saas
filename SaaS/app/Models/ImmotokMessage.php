<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ImmotokMessage extends Model
{
    use HasFactory;

    protected $table = 'immotok_messages';

    protected $fillable = [
        'immotok_client_id',
        'company_profile_id',
        'agency_id',
        'sender',
        'message',
        'is_ai_reply',
        'is_read',
    ];

    protected $casts = [
        'is_ai_reply' => 'boolean',
        'is_read' => 'boolean',
    ];

    public function client(): BelongsTo
    {
        return $this->belongsTo(ImmotokClient::class, 'immotok_client_id');
    }

    public function companyProfile(): BelongsTo
    {
        return $this->belongsTo(CompanyProfile::class, 'company_profile_id');
    }

    public function agency(): BelongsTo
    {
        return $this->belongsTo(Agency::class);
    }
}
