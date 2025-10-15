<?php

namespace App\Livewire\Notification;

use Livewire\Component;
use Illuminate\Support\Facades\Auth;

class NotificationsIndex extends Component
{
    public $notifications;

    public function mount()
    {
        $this->notifications = Auth::user()->notifications()->latest()->get();
    }

    public function markAllAsRead()
    {
        Auth::user()->unreadNotifications->markAsRead();
        $this->notifications = Auth::user()->notifications()->latest()->get();
    }

    public function render()
    {
        return view('livewire.notification.notifications-index')->layout('layouts.livewire');
    }
}
