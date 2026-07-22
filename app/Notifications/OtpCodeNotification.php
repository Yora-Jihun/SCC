<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class OtpCodeNotification extends Notification
{
    use Queueable;

    public function __construct(public string $code){}

    public function via(object $notifiable): array
    {
        return ['mail'];
    }

    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject('Your verification code')
            ->line("Your verification code is: {$this->code}")
            ->line('This code expires in ' . config('otp.expiry_minutes') . ' minutes.');
    }
  
    // public function toArray(object $notifiable): array
    // {
    //     return [
            
    //     ];
    // }
}
