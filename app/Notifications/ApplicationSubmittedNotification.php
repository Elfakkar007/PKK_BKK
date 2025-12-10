<?php

namespace App\Notifications;

use App\Models\Application;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class ApplicationSubmittedNotification extends Notification implements ShouldQueue
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
        return (new MailMessage)
            ->subject('Lamaran Baru Masuk - BKK SMKN 1 Purwosari')
            ->greeting('Lamaran Baru!')
            ->line('Ada lamaran baru untuk lowongan: ' . $this->application->jobVacancy->title)
            ->line('Pelamar: ' . $this->application->full_name)
            ->line('Email: ' . $this->application->email)
            ->action('Lihat Lamaran', route('company.applications.show', $this->application->id))
            ->salutation('Salam, Tim BKK SMKN 1 Purwosari');
    }

    public function toDatabase(object $notifiable): array
    {
        return [
            'title' => 'Lamaran Baru',
            'message' => 'Lamaran baru untuk ' . $this->application->jobVacancy->title . ' dari ' . $this->application->full_name,
            'type' => 'application',
            'application_id' => $this->application->id
        ];
    }
}