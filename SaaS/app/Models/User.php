<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

use App\Traits\HasCustomUuid;

class User extends Authenticatable
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, Notifiable, HasCustomUuid, HasApiTokens;

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($user) {
            if (in_array($user->account_type, ['company', 'individual'])) {
                if (empty($user->trial_ends_at)) {
                    $duration = (int) \App\Models\TrialSetting::getValue('trial_duration_days', 14);
                    $user->trial_started_at = now();
                    $user->trial_ends_at = now()->addDays($duration);
                }
            }
        });
    }

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'account_type',
        'subscription_plan',
        'role_id',
        'status',
        'last_login_at',
        'company_profile_id',
        'is_connected',
        'must_logout',
        'trial_ends_at',
        'trial_started_at',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'trial_ends_at' => 'datetime',
            'trial_started_at' => 'datetime',
        ];
    }

    /**
     * The accessors to append to the model's array form.
     *
     * @var array
     */
    protected $appends = [
        'is_trial_active',
        'is_trial_expired',
        'trial_days_left',
        'blocked_modules',
    ];

    public function companyProfile(): HasOne
    {
        return $this->hasOne(CompanyProfile::class);
    }

    public function role(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Role::class);
    }

    public function company(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(CompanyProfile::class, 'company_profile_id');
    }

    public function employee(): \Illuminate\Database\Eloquent\Relations\HasOne
    {
        return $this->hasOne(Employee::class);
    }

    public function locataire(): HasOne
    {
        return $this->hasOne(Locataire::class);
    }

    public function planRelation(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(SubscriptionPlan::class, 'subscription_plan', 'slug');
    }

    public function subscriptions(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(UserSubscription::class);
    }

    public function activeSubscription(): \Illuminate\Database\Eloquent\Relations\HasOne
    {
        return $this->hasOne(UserSubscription::class)->where('status', 'active');
    }

    public function isTrialActive(): bool
    {
        return $this->trial_ends_at !== null && now()->lt($this->trial_ends_at);
    }

    public function hasActiveSubscription(): bool
    {
        return $this->activeSubscription()->exists();
    }

    public function isTrialExpired(): bool
    {
        if (in_array(strtolower(str_replace([' ', '_'], '', $this->account_type)), ['superadmin', 'super_admin'])) {
            return false;
        }

        if ($this->company_profile_id && $this->account_type !== 'company') {
            $owner = self::where('company_profile_id', $this->company_profile_id)
                ->where('account_type', 'company')
                ->first();
            if ($owner) {
                return $owner->isTrialExpired();
            }
        }

        if ($this->hasActiveSubscription()) {
            return false;
        }

        if ($this->trial_ends_at !== null && now()->lt($this->trial_ends_at)) {
            return false;
        }

        return true;
    }

    public function blockedModules(): array
    {
        if (!$this->isTrialExpired()) {
            return [];
        }
        
        return \App\Models\TrialSetting::getValue('blocked_features', ['hotel', 'accounting', 'maintenance']);
    }

    /**
     * Accessor for is_trial_active.
     */
    public function getIsTrialActiveAttribute(): bool
    {
        return $this->isTrialActive();
    }

    /**
     * Accessor for is_trial_expired.
     */
    public function getIsTrialExpiredAttribute(): bool
    {
        return $this->isTrialExpired();
    }

    /**
     * Accessor for trial_days_left.
     */
    public function getTrialDaysLeftAttribute(): int
    {
        if ($this->trial_ends_at) {
            $diff = now()->diffInDays($this->trial_ends_at, false);
            $trialDaysLeft = (int) ceil($diff);
            return $trialDaysLeft < 0 ? 0 : $trialDaysLeft;
        }
        return 0;
    }

    /**
     * Accessor for blocked_modules.
     */
    public function getBlockedModulesAttribute(): array
    {
        return $this->blockedModules();
    }
}
