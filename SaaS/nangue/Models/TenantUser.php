<?php

namespace Nangue\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Auth\Authenticatable;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;

class TenantUser extends Model implements AuthenticatableContract
{
    use Authenticatable;

    protected $fillable = ['tenant_id', 'email', 'password', 'locale', 'last_login_at', 'is_active'];

    protected $hidden = ['password', 'remember_token'];

    protected function casts(): array
    {
        return [
            'last_login_at' => 'datetime',
            'is_active' => 'boolean',
        ];
    }

    public function tenant() { return $this->belongsTo(Tenant::class); }
}
