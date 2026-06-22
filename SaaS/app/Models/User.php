<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
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
        'landlord_verified',
        'landlord_verification_status',
        'verification_documents',
        'additional_info',
        'verified_at',
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
            'landlord_verified' => 'boolean',
            'verification_documents' => 'array',
            'verified_at' => 'datetime',
        ];
    }

    public function properties()
    {
        return $this->hasMany(\Nangue\Models\Property::class);
    }

    public function contracts()
    {
        return $this->hasMany(\Nangue\Models\Contract::class, 'landlord_id');
    }

    public function visits()
    {
        return $this->hasMany(\Nangue\Models\Visit::class, 'landlord_id');
    }

    public function conversations()
    {
        return $this->hasMany(\Nangue\Models\Conversation::class);
    }

    public function messages()
    {
        return $this->hasMany(\Nangue\Models\Message::class);
    }

    public function favorites()
    {
        return $this->hasMany(\Nangue\Models\Favorite::class);
    }

    public function savedSearches()
    {
        return $this->hasMany(\Nangue\Models\SavedSearch::class);
    }

    public function tenants()
    {
        return $this->hasMany(\Nangue\Models\Tenant::class);
    }

    public function incidents()
    {
        return $this->hasMany(\Nangue\Models\Incident::class, 'landlord_id');
    }

    public function inspections()
    {
        return $this->hasMany(\Nangue\Models\Inspection::class, 'landlord_id');
    }

    public function documents()
    {
        return $this->hasMany(\Nangue\Models\Document::class);
    }

    public function paymentMethods()
    {
        return $this->hasMany(\Nangue\Models\PaymentMethod::class);
    }

    public function rentRevisions()
    {
        return $this->hasMany(\Nangue\Models\RentRevision::class, 'landlord_id');
    }

    public function notificationLogs()
    {
        return $this->hasMany(\Nangue\Models\NotificationLog::class);
    }

    public function fiscalYears()
    {
        return $this->hasMany(\Nangue\Models\FiscalYear::class);
    }
}
