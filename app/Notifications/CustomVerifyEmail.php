<?php

namespace App\Notifications;

use Illuminate\Auth\Notifications\VerifyEmail as BaseVerifyEmail;
use Illuminate\Notifications\Messages\MailMessage;

class CustomVerifyEmail extends BaseVerifyEmail
{
    /**
     * Get the mail representation of the notification.
     */
    public function toMail($notifiable): MailMessage
    {
        $verificationUrl = $this->verificationUrl($notifiable);

        return (new MailMessage)
            ->subject('Verify Your SmartHarvest Account')
            ->greeting('Welcome to SmartHarvest!')
            ->line('Thank you for registering with SmartHarvest - Optimize Your Planting with Data.')
            ->line('To complete your registration and access the dashboard, please verify your email address by clicking the button below:')
            ->action('Verify Email Address', $verificationUrl)
            ->line('This verification link will expire in 60 minutes.')
            ->line('After verification, you will be able to set your password and access your account.')
            ->line('If you did not create an account, no further action is required and you can safely ignore this email.')
            ->salutation('Best regards,  
SmartHarvest Team  
Optimize Your Planting with Data');
    }
}
