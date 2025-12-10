<?php

namespace App\Notifications;

use App\Models\JobVacancy;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class VacancyRejectedNotification extends Notification implements ShouldQueue
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
        $message = (new MailMessage)
            ->subject('Lowongan Ditolak - BKK SMKN 1 Purwosari')
            ->greeting('Lowongan Ditolak')
            ->line('Lowongan Anda telah ditinjau oleh admin.')
            ->line('Posisi: ' . $this->vacancy->title);

        if ($this->vacancy->rejection_reason) {
            $message->line('Alasan penolakan: ' . $this->vacancy->rejection_reason);
        }

        $message->line('Silakan perbaiki dan submit kembali jika diperlukan.')
            ->action('Kelola Lowongan', route('company.vacancies'))
            ->salutation('Salam, Tim BKK SMKN 1 Purwosari');

        return $message;
    }

    public function toDatabase(object $notifiable): array
    {
        return [
            'title' => 'Lowongan Ditolak',
            'message' => 'Lowongan untuk posisi ' . $this->vacancy->title . ' ditolak. ' . ($this->vacancy->rejection_reason ?? ''),
            'type' => 'vacancy_rejected',
            'vacancy_id' => $this->vacancy->id
        ];
    }
}