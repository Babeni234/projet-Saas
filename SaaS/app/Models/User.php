<?php

namespace App\Models;

use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'email',
        'password',
        'is_pro',
        'company_name',
        'siret',
        'phone_pro',
        'address_pro',
        'logo_path',
        'pro_settings',
        'country',
        'region',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'is_pro' => 'boolean',
            'pro_settings' => 'array',
        ];
    }

    public function portfolios()
    {
        return $this->hasMany(Portfolio::class);
    }

    public function teamInvitations()
    {
        return $this->hasMany(TeamInvitation::class);
    }

    public function teamMembers()
    {
        return $this->hasMany(TeamMember::class, 'user_id');
    }

    public function teamOf()
    {
        return $this->hasMany(TeamMember::class, 'member_id');
    }

    public function automationRules()
    {
        return $this->hasMany(AutomationRule::class);
    }
}
