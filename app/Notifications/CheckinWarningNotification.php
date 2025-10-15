<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class CheckinWarningNotification extends Notification
{
    use Queueable;

    public function __construct(
        protected int $stage,
        protected int $hours
    ) {}

    public function via($notifiable): array
    {
        return ['database']; // bisa kamu ganti ke mail, FCM, dsb
    }

    public function toArray($notifiable): array
    {
        $messages = [
            1 => "Anda sudah check-in lebih dari 3 jam, segera checkout jika sudah selesai.",
            2 => "Sudah lebih dari 6 jam check-in tanpa checkout.",
            3 => "Anda sudah aktif lebih dari 10 jam.",
            4 => "Sudah lebih dari 12 jam! Sistem akan auto-checkout malam ini.",
            5 => "Anda sudah lebih dari 20 jam aktif. Sistem akan otomatis logout sebentar lagi.",
        ];

        return [
            'title' => 'Peringatan Check-in Lama',
            'message' => $messages[$this->stage] ?? 'Peringatan check-in lama.',
            'duration' => "{$this->hours} jam",
        ];
    }
}
