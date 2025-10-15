<?php

namespace App\Livewire\Chat;

use Livewire\Component;
use App\Models\Vendor;
use App\Models\Chat;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Computed;

class Index extends Component
{
    public $userType;
    public $error = null;

    public function mount()
    {
        try {
            $this->userType = Auth::user()->role; 
            
            // Debug: pastikan user type terdeteksi
            if (!in_array($this->userType, ['user', 'vendor'])) {
                $this->error = "Tipe pengguna tidak dikenali: {$this->userType}";
            }
        } catch (\Exception $e) {
            $this->error = "Gagal memuat data: " . $e->getMessage();
        }
    }

    #[Computed] 
    public function chatPartners()
    {
        if ($this->error) {
            return collect();
        }

        try {
            if ($this->userType === 'user') {
                // Untuk USER: ambil vendor yang pernah dichat
                $vendorIds = Chat::where('user_id', Auth::id())
                    ->select('vendor_id')
                    ->distinct()
                    ->pluck('vendor_id')
                    ->toArray();

                if (empty($vendorIds)) {
                    return collect();
                }

                return Vendor::whereIn('id', $vendorIds)
                    ->with(['user'])
                    ->withCount(['chats as unread_messages_count' => function($query) {
                        $query->where('user_id', Auth::id())
                              ->where('sender_type', 'vendor')
                              ->where('is_read', false);
                    }])
                    ->get()
                    ->each(function($vendor) {
                        // Tambahkan latest message manually
                        $vendor->latest_message = Chat::where('user_id', Auth::id())
                            ->where('vendor_id', $vendor->id)
                            ->latest()
                            ->first();
                    });

            } else {
                // Untuk VENDOR: ambil user yang pernah chat
                $vendor = Auth::user()->vendor;
                
                if (!$vendor) {
                    $this->error = "Data vendor tidak ditemukan";
                    return collect();
                }

                $userIds = Chat::where('vendor_id', $vendor->id)
                    ->select('user_id')
                    ->distinct()
                    ->pluck('user_id')
                    ->toArray();

                if (empty($userIds)) {
                    return collect();
                }

                return \App\Models\User::whereIn('id', $userIds)
                    ->withCount(['chats as unread_messages_count' => function($query) use ($vendor) {
                        $query->where('vendor_id', $vendor->id)
                              ->where('sender_type', 'user')
                              ->where('is_read', false);
                    }])
                    ->get()
                    ->each(function($user) use ($vendor) {
                        // Tambahkan latest message manually
                        $user->latest_message = Chat::where('user_id', $user->id)
                            ->where('vendor_id', $vendor->id)
                            ->latest()
                            ->first();
                    });
            }
        } catch (\Exception $e) {
            $this->error = "Gagal memuat daftar chat: " . $e->getMessage();
            return collect();
        }
    }

    public function render()
    {
        // DEBUG: Log untuk memastikan component di-render
        \Log::info('Chat Index Component Rendered', [
            'user_id' => Auth::id(),
            'user_type' => $this->userType,
            'error' => $this->error,
            'chat_partners_count' => $this->chatPartners->count()
        ]);

        return view('livewire.chat.index', [
            'error' => $this->error
        ])->layout('layouts.livewire');
    }
}