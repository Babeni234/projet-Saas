<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use App\Traits\HasCustomUuid;

class Illustration extends Model
{
    use HasFactory, HasCustomUuid;

    protected $fillable = [
        'company_profile_id',
        'agency_id',
        'target_type',
        'target_id',
        'target_name',
        'file_path',
        'audio_path',
        'file_name',
        'media_type',
        'mime_type',
        'file_size',
        'description',
        'synced',
    ];

    public function companyProfile(): BelongsTo
    {
        return $this->belongsTo(CompanyProfile::class, 'company_profile_id');
    }

    public function agency(): BelongsTo
    {
        return $this->belongsTo(Agency::class);
    }

    public function immotokLikes(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(ImmotokLike::class, 'illustration_id');
    }

    public function immotokFavorites(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(ImmotokFavorite::class, 'illustration_id');
    }

    public function immotokComments(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(ImmotokComment::class, 'illustration_id');
    }
}
