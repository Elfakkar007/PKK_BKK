<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class AccountRejectedNotification extends Notification implements ShouldQueue
{
    use Queueable;

    protected $reason;

    public function __construct($reason = null)
    {
        $this->reason = $reason;
    }

    public function via(object $notifiable): array
    {
        return ['mail', 'database'];
    }

    public function toMail(object $notifiable): MailMessage
    {
        $message = (new MailMessage)
            ->subject('Permohonan Registrasi Ditolak - BKK SMKN 1 Purwosari')
            ->greeting('Mohon Maaf')
            ->line('Permohonan registrasi Anda telah ditinjau oleh admin.');

        if ($this->reason) {
            $message->line('Alasan penolakan: ' . $this->reason);
        } else {
            $message->line('Mohon maaf, permohonan registrasi Anda tidak dapat disetujui.');
        }

        $message->line('Jika Anda merasa ini adalah kesalahan, silakan hubungi admin kami.')
            ->salutation('Salam, Tim BKK SMKN 1 Purwosari');

        return $message;
    }

    public function toDatabase(object $notifiable): array
    {
        return [
            'title' => 'Registrasi Ditolak',
            'message' => $this->reason ?? 'Permohonan registrasi Anda ditolak.',
            'type' => 'rejection'
        ];
    }
}