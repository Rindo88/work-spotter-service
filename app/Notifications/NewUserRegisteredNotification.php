<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Notification;

class NewUserRegisteredNotification extends Notification implements ShouldQueue
{
    use Queueable;

    protected string $userName;

    public function __construct(string $userName)
    {
        $this->userName = $userName;
    }

    public function via($notifiable)
    {
        // Simpan ke database + kirim via mail kalau kamu mau
        return ['database'];
    }

    public function toDatabase($notifiable)
    {
        return [
            'title' => 'Selamat Datang!',
            'message' => "Halo {$this->userName}, terima kasih sudah mendaftar. Silakan cek email untuk verifikasi akun kamu.",
            'action_url' => route('verification.notice'), // arahkan ke halaman verifikasi email
            'icon' => 'fa-envelope', // opsional, untuk tampilan front-end
        ];
    }
}
