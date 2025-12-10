<?php

namespace App\Notifications;

use App\Models\JobVacancy;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class VacancyApprovedNotification extends Notification implements ShouldQueue
{
    use Queueable;

    protected $vacancy;

    public function __construct(JobVacancy $vacancy)
    {
        $this->vacancy = $vacancy;
    }

    public function via(object $notifiable): array
    {
        return ['mail', 'database'];
    }

    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject('Lowongan Disetujui - BKK SMKN 1 Purwosari')
            ->greeting('Selamat!')
            ->line('Lowongan Anda telah disetujui oleh admin.')
            ->line('Posisi: ' . $this->vacancy->title)
            ->line('Tipe: ' . ($this->vacancy->type === 'internship' ? 'Magang' : 'Full Time'))
            ->line('Lowongan Anda sekarang dapat dilihat oleh siswa/alumni.')
            ->action('Lihat Lowongan', route('company.vacancies'))
            ->salutation('Salam, Tim BKK SMKN 1 Purwosari');
    }

    public function toDatabase(object $notifiable): array
    {
        return [
            'title' => 'Lowongan Disetujui',
            'message' => 'Lowongan untuk posisi ' . $this->vacancy->title . ' telah disetujui.',
            'type' => 'vacancy_approved',
            'vacancy_id' => $this->vacancy->id
        ];
    }
}