<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use App\Notifications\CustomVerifyEmail;

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
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
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
        'crop_types',
        'years_experience',
        'bio',
        'last_login',
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
            'phone_verified_at' => 'datetime',
            'otp_expires_at' => 'datetime',
            'password' => 'hashed',
            'password_set_at' => 'datetime',
            'last_login' => 'datetime',
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
}
