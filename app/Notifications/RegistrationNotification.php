<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class RegistrationNotification extends Notification implements ShouldQueue
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
        $roleName = match($notifiable->role) {
            'student' => 'Siswa/Alumni',
            'company' => 'Perusahaan',
            default => 'User'
        };

        return (new MailMessage)
            ->subject('Registrasi Berhasil - BKK SMKN 1 Purwosari')
            ->greeting('Terima kasih telah mendaftar!')
            ->line('Akun Anda sebagai ' . $roleName . ' telah berhasil terdaftar.')
            ->line('Saat ini akun Anda sedang dalam proses verifikasi oleh admin.')
            ->line('Anda akan menerima email notifikasi setelah akun Anda disetujui.')
            ->line('Terima kasih atas kesabaran Anda.')
            ->salutation('Salam, Tim BKK SMKN 1 Purwosari');
    }

    public function toDatabase(object $notifiable): array
    {
        return [
            'title' => 'Registrasi Berhasil',
            'message' => 'Akun Anda berhasil terdaftar dan menunggu persetujuan admin.',
            'type' => 'registration'
        ];
    }
}