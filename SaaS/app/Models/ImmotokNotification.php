<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ImmotokNotification extends Model
{
    use HasFactory;

    protected $table = 'immotok_notifications';

    public $timestamps = true;
    const UPDATED_AT = null;

    protected $fillable = [
        'immotok_client_id',
        'company_profile_id',
        'illustration_id',
        'type',
        'title',
        'message',
        'image_url',
        'is_read',
    ];

    protected $casts = [
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
}
