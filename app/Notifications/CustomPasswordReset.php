<?php

namespace App\Notifications;

use Illuminate\Auth\Notifications\ResetPassword as BaseResetPassword;
use Illuminate\Notifications\Messages\MailMessage;

class CustomPasswordReset extends BaseResetPassword
{
    /**
     * Get the mail representation of the notification.
     */
    public function toMail($notifiable): MailMessage
    {
        $resetUrl = $this->resetUrl($notifiable);
        $fromAddress = config('mail.from.address') ?: 'no-reply@smartharvest.app';

        return (new MailMessage)
            ->from($fromAddress, 'SmartHarvest')
            ->subject('SmartHarvest Password Reset Request')
            ->greeting('Hi ' . $notifiable->name . ',')
            ->line('We received a request to reset the password for your SmartHarvest account.')
            ->line('To reset your password, click the button below:')
            ->action('Reset Password', $resetUrl)
            ->line('This password reset link will expire in 60 minutes.')
            ->line('If you did not request a password reset, no further action is required and you can safely ignore this email.')
            ->line('For security reasons, never share your password reset link with anyone.')
            ->salutation('Best regards,  
SmartHarvest Team  
Optimize Your Planting with Data');
    }
}
