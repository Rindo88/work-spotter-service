<?php

namespace App\Livewire\Notification;

use Livewire\Component;
use Illuminate\Support\Facades\Auth;

class NotificationsUser extends Component
{
    public $notifications = [];
    public $unreadCount = 0;
    public $verificationSent = false;

    public function mount()
    {
        $this->loadNotifications();
    }

    public function loadNotifications()
    {
        $user = Auth::user();

        if (!$user) return;

        $this->notifications = $user->notifications()
            ->latest()
            ->take(5)
            ->get();

        $this->unreadCount = $user->unreadNotifications()->count();
    }

    public function markAsRead($id)
    {
        $notif = Auth::user()->notifications()->find($id);
        if ($notif && !$notif->read_at) {
            $notif->markAsRead();
        }
        $this->loadNotifications();
    }


    // verifikasi email
    public function sendVerificationEmail()
    {
        $user = Auth::user();

        if ($user->hasVerifiedEmail()) {
            $this->dispatch('notify', 'Email kamu sudah diverifikasi.');
            return;
        }

        try {
            $user->sendEmailVerificationNotification();
            $this->verificationSent = true;
            $this->dispatch('notify', 'Email verifikasi telah dikirim! Silakan cek inbox.');
        } catch (\Exception $e) {
            $this->dispatch('notify', 'Gagal mengirim email verifikasi: ' . $e->getMessage());
        }
    }


    public function render()
    {
        return view('livewire.notification.notifications-user');
    }
}
