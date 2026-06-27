<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use App\Traits\HasCustomUuid;
use Illuminate\Database\Eloquent\Relations\HasMany;

class ImmotokClient extends Authenticatable
{
    use HasFactory, HasCustomUuid;

    protected $table = 'immotok_clients';

    protected $fillable = [
        'name',
        'email',
        'phone',
        'password',
        'avatar',
        'api_token',
    ];

    protected $hidden = [
        'password',
    ];

    protected $casts = [
        'password' => 'hashed',
    ];

    public function likes(): HasMany
    {
        return $this->hasMany(ImmotokLike::class, 'immotok_client_id');
    }

    public function favorites(): HasMany
    {
        return $this->hasMany(ImmotokFavorite::class, 'immotok_client_id');
    }

    public function comments(): HasMany
    {
        return $this->hasMany(ImmotokComment::class, 'immotok_client_id');
    }

    public function messages(): HasMany
    {
        return $this->hasMany(ImmotokMessage::class, 'immotok_client_id');
    }

    public function subscriptions(): HasMany
    {
        return $this->hasMany(ImmotokSubscription::class, 'immotok_client_id');
    }
}
