<?php

namespace App\Notifications;

use Ichtrojan\Otp\Otp;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class ForgetPasswordNotification extends Notification
{
    use Queueable;

    public $message;
    public $otp;
    public $subject;


    /**
     * Create a new notification instance.
     */
    public function __construct()
    {
        $this->message = 'استخدم هذا ال OTP لأعاده تعيين كلمة المرور الخاصة بك';
        $this->otp = new Otp();
        $this->subject = 'تعين كلمة المرور الجديدة';
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['mail'];
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail(object $notifiable): MailMessage
    {
        $otp = $this->otp->generate($notifiable->email, 'numeric', 4, 6);
        return (new MailMessage)
            ->subject($this->subject)
            ->greeting("{$notifiable->name} مرحبا ! ")
            ->line($this->message)
            ->line("الكود الخاص بتعيين كلمة المرور الجديدة الخاص بك :")
            ->line($otp->token);
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            //
        ];
    }
}
