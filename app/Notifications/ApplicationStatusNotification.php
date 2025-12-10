<?php

namespace App\Notifications;

use App\Models\Application;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class ApplicationStatusNotification extends Notification implements ShouldQueue
{
    use Queueable;

    protected $application;

    public function __construct(Application $application)
    {
        $this->application = $application;
    }

    public function via(object $notifiable): array
    {
        return ['mail', 'database'];
    }

    public function toMail(object $notifiable): MailMessage
    {
        $statusText = match($this->application->status) {
            'reviewed' => 'sedang ditinjau',
            'accepted' => 'diterima',
            'rejected' => 'ditolak',
            default => 'diperbarui'
        };

        $message = (new MailMessage)
            ->subject('Status Lamaran Diperbarui - BKK SMKN 1 Purwosari')
            ->greeting('Status Lamaran Anda')
            ->line('Status lamaran Anda untuk posisi ' . $this->application->jobVacancy->title . ' telah ' . $statusText . '.');

        if ($this->application->company_notes) {
            $message->line('Catatan dari perusahaan: ' . $this->application->company_notes);
        }

        $message->action('Lihat Detail', route('student.applications.show', $this->application->id))
            ->salutation('Salam, Tim BKK SMKN 1 Purwosari');

        return $message;
    }

    public function toDatabase(object $notifiable): array
    {
        return [
            'title' => 'Status Lamaran Diperbarui',
            'message' => 'Status lamaran Anda untuk ' . $this->application->jobVacancy->title . ' telah diperbarui menjadi ' . $this->application->status_label,
            'type' => 'application_status',
            'application_id' => $this->application->id
        ];
    }
}