<?php

namespace App\Livewire\Notification;

use Livewire\Component;
use Illuminate\Support\Facades\Auth;

class NotificationsVendor extends Component
{
    public $notifications = [];
    public $unreadCount = 0;

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

    public function render()
    {
        return view('livewire.notification.notifications-vendor');
    }
}
