<?php

namespace App\Livewire\Notification;

use Livewire\Component;
use Illuminate\Support\Facades\Auth;

class NotificationDetail extends Component
{
    public $notification;

    public function mount($id)
    {
        $this->notification = Auth::user()
            ->notifications()
            ->where('id', $id)
            ->firstOrFail();
    }

    // ✅ Tombol untuk menandai sudah dibaca
    public function markAsRead()
    {
        if (is_null($this->notification->read_at)) {
            $this->notification->markAsRead();
            $this->dispatch('notify', 'Notifikasi telah ditandai sebagai dibaca.');
        }

        // Refresh data di halaman
        $this->notification = $this->notification->fresh();
    }
    

    public function render()
    {
        return view('livewire.notification.notification-detail')
            ->layout('layouts.livewire');
    }
}
