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
        // CEK APAKAH USER TERAUTENTIKASI SEBELUM MENGAKSES DATA
        if (!Auth::check()) {
            $this->notifications = [];
            $this->unreadCount = 0;
            return;
        }

        $user = Auth::user();
        $this->notifications = $user->notifications()
            ->latest()
            ->take(5)
            ->get();

        $this->unreadCount = $user->unreadNotifications()->count();
    }

    public function markAsRead($id)
    {
        // CEK APAKAH USER TERAUTENTIKASI
        if (!Auth::check()) return;

        $notif = Auth::user()->notifications()->find($id);
        if ($notif && !$notif->read_at) {
            $notif->markAsRead();
        }
        $this->loadNotifications();
    }

    // verifikasi email
    public function sendVerificationEmail()
    {
        // CEK APAKAH USER TERAUTENTIKASI
        if (!Auth::check()) {
            $this->dispatch('notify', 'Silakan login terlebih dahulu.');
            return;
        }

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