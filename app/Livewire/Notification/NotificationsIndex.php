<?php

namespace App\Livewire\Notification;

use Livewire\Component;
use Illuminate\Support\Facades\Auth;

class NotificationsIndex extends Component
{
    public $notifications;
    public $verificationSent = false;

    public function mount()
    {
        $this->notifications = Auth::user()->notifications()->latest()->get();
    }

    public function markAllAsRead()
    {
        Auth::user()->unreadNotifications->markAsRead();
        $this->notifications = Auth::user()->notifications()->latest()->get();
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
        return view('livewire.notification.notifications-index')->layout('layouts.livewire');
    }
}
