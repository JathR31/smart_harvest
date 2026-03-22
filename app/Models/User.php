<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use App\Notifications\CustomVerifyEmail;
use App\Notifications\CustomPasswordReset;

class User extends Authenticatable implements MustVerifyEmail
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    /**
     * Send the email verification notification.
     */
    public function sendEmailVerificationNotification()
    {
        $this->notify(new CustomVerifyEmail);
    }

    /**
     * Send the password reset notification.
     */
    public function sendPasswordResetNotification($token)
    {
        $this->notify(new CustomPasswordReset($token));
    }

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'username',
        'password',
        'role',
        'is_superadmin',
        'admin_type',
        'google2fa_secret',
        'google2fa_enabled',
        'status',
        'phone',
        'phone_number',
        'phone_verified_at',
        'otp_code',
        'otp_expires_at',
        'otp_attempts',
        'verification_method',
        'location',
        'farm_name',
        'farm_size',
        'primary_crop',
        'crop_types',
        'years_experience',
        'bio',
        'last_login',
        // DA Admin-specific fields
        'office',
        'position',
        'employee_id',
        'admin_permissions',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
        'google2fa_secret',
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
            'phone_verified_at' => 'datetime',
            'otp_expires_at' => 'datetime',
            'password' => 'hashed',
            'password_set_at' => 'datetime',
            'last_login' => 'datetime',
            'is_superadmin' => 'boolean',
            'google2fa_enabled' => 'boolean',
            'admin_permissions' => 'array',
        ];
    }
    
    /**
     * Check if the user's phone number is verified.
     *
     * @return bool
     */
    public function hasVerifiedPhone()
    {
        return !is_null($this->phone_verified_at);
    }
    
    /**
     * Check if the user is fully verified (email or phone based on method).
     *
     * @return bool
     */
    public function isFullyVerified()
    {
        if ($this->verification_method === 'sms') {
            return $this->hasVerifiedPhone();
        }
        return $this->hasVerifiedEmail();
    }

    /**
     * Check if the user is a superadmin.
     *
     * @return bool
     */
    public function getIsSuperadminAttribute()
    {
        // Check if user has superadmin role or is marked as superadmin
        return $this->role === 'Superadmin' || 
               $this->admin_type === 'superadmin' ||
               $this->email === 'superadmin@smartharvest.ph';
    }

    /**
     * Check if the user is a DA-CAR admin.
     *
     * @return bool
     */
    public function getIsDacarAdminAttribute()
    {
        return $this->role === 'Admin' && !$this->is_superadmin;
    }
}
