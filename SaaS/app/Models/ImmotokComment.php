<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class ImmotokComment extends Model
{
    use HasFactory;

    protected $table = 'immotok_comments';

    protected $fillable = [
        'immotok_client_id',
        'user_id',
        'illustration_id',
        'company_profile_id',
        'parent_id',
        'text',
    ];

    public function client(): BelongsTo
    {
        return $this->belongsTo(ImmotokClient::class, 'immotok_client_id');
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function illustration(): BelongsTo
    {
        return $this->belongsTo(Illustration::class, 'illustration_id');
    }

    public function companyProfile(): BelongsTo
    {
        return $this->belongsTo(CompanyProfile::class, 'company_profile_id');
    }

    public function parent(): BelongsTo
    {
        return $this->belongsTo(ImmotokComment::class, 'parent_id');
    }

    public function replies(): HasMany
    {
        return $this->hasMany(ImmotokComment::class, 'parent_id')->orderBy('created_at', 'asc');
    }
}
