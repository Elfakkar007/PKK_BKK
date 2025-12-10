<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class AccountApprovedNotification extends Notification implements ShouldQueue
{
    use Queueable;

    public function __construct()
    {
        //
    }

    public function via(object $notifiable): array
    {
        return ['mail', 'database'];
    }

    public function toMail(object $notifiable): MailMessage
    {
        $dashboardRoute = match($notifiable->role) {
            'student' => route('student.dashboard'),
            'company' => route('company.dashboard'),
            default => route('home')
        };

        return (new MailMessage)
            ->subject('Akun Anda Telah Disetujui - BKK SMKN 1 Purwosari')
            ->greeting('Selamat!')
            ->line('Akun Anda telah disetujui oleh admin.')
            ->line('Anda sekarang dapat mengakses semua fitur yang tersedia.')
            ->action('Masuk ke Dashboard', $dashboardRoute)
            ->line('Terima kasih telah bergabung dengan BKK SMKN 1 Purwosari.')
            ->salutation('Salam, Tim BKK SMKN 1 Purwosari');
    }

    public function toDatabase(object $notifiable): array
    {
        return [
            'title' => 'Akun Disetujui',
            'message' => 'Akun Anda telah disetujui. Silakan login untuk mengakses dashboard.',
            'type' => 'approval'
        ];
    }
}