<?php

namespace App\Livewire\Chat;

use Livewire\Component;
use App\Models\Chat;
use App\Models\Vendor;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Computed;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class Room extends Component
{
    public $vendorId;
    public $partner;
    public $message = '';
    public $userType;
    public $error = '';

    public function mount($vendorId = null)
    {
        try {
            if (!$vendorId) {
                $this->error = "ID vendor tidak valid";
                return;
            }

            $this->vendorId = $vendorId;
            $this->userType = Auth::user()->role;

            \Log::info('Chat Room Mounted', [
                'user_id' => Auth::id(),
                'user_type' => $this->userType,
                'vendor_id' => $this->vendorId
            ]);

            if ($this->userType === 'user') {
                // User chatting dengan Vendor
                $this->partner = Vendor::with('user')->find($this->vendorId);
                
                if (!$this->partner) {
                    $this->error = "Vendor tidak ditemukan";
                    return;
                }

                if (!$this->partner->user) {
                    $this->error = "Data vendor tidak lengkap";
                    return;
                }
            } else {
                // Vendor chatting - vendorId sebenarnya adalah userId
                $this->partner = User::find($this->vendorId);
                
                if (!$this->partner) {
                    $this->error = "User tidak ditemukan";
                    return;
                }
            }

            $this->markAsRead();
            
        } catch (ModelNotFoundException $e) {
            $this->error = "Data tidak ditemukan";
        } catch (\Exception $e) {
            $this->error = "Terjadi kesalahan sistem: " . $e->getMessage();
            \Log::error('Chat Room Error: ' . $e->getMessage());
        }
    }

    #[Computed]
    public function chats()
    {
        if ($this->error) {
            return collect();
        }

        try {
            if ($this->userType === 'user') {
                return Chat::where('user_id', Auth::id())
                    ->where('vendor_id', $this->vendorId)
                    ->with(['user', 'vendor.user'])
                    ->orderBy('created_at', 'asc')
                    ->get();
            } else {
                $vendor = Auth::user()->vendor;
                if (!$vendor) {
                    $this->error = "Data vendor tidak ditemukan";
                    return collect();
                }
                
                return Chat::where('user_id', $this->vendorId) // vendorId adalah userId di sini
                    ->where('vendor_id', $vendor->id)
                    ->with(['user', 'vendor.user'])
                    ->orderBy('created_at', 'asc')
                    ->get();
            }
        } catch (\Exception $e) {
            $this->error = "Gagal memuat pesan: " . $e->getMessage();
            return collect();
        }
    }

    public function sendMessage()
    {
        if ($this->error) {
            return;
        }

        $this->validate([
            'message' => 'required|string|min:1|max:1000',
        ]);

        try {
            if ($this->userType === 'user') {
                Chat::create([
                    'user_id' => Auth::id(),
                    'vendor_id' => $this->vendorId,
                    'sender_type' => 'user',
                    'message' => trim($this->message),
                    'is_read' => false,
                ]);
            } else {
                $vendor = Auth::user()->vendor;
                if (!$vendor) {
                    $this->error = "Data vendor tidak ditemukan";
                    return;
                }
                
                Chat::create([
                    'user_id' => $this->vendorId, // vendorId adalah userId
                    'vendor_id' => $vendor->id,
                    'sender_type' => 'vendor', 
                    'message' => trim($this->message),
                    'is_read' => false,
                ]);
            }

            $this->reset('message');
            $this->markAsRead();
            
        } catch (\Exception $e) {
            $this->error = "Gagal mengirim pesan: " . $e->getMessage();
        }
    }

    public function markAsRead()
    {
        if ($this->error) {
            return;
        }

        try {
            if ($this->userType === 'user') {
                Chat::where('user_id', Auth::id())
                    ->where('vendor_id', $this->vendorId)
                    ->where('sender_type', 'vendor')
                    ->where('is_read', false)
                    ->update(['is_read' => true]);
            } else {
                $vendor = Auth::user()->vendor;
                if (!$vendor) return;
                
                Chat::where('user_id', $this->vendorId)
                    ->where('vendor_id', $vendor->id)
                    ->where('sender_type', 'user')
                    ->where('is_read', false)
                    ->update(['is_read' => true]);
            }
        } catch (\Exception $e) {
            \Log::error('Gagal mark as read: ' . $e->getMessage());
        }
    }

    public function render()
    {
        // DEBUG
        \Log::info('Chat Room Rendered', [
            'error' => $this->error,
            'chats_count' => $this->chats->count(),
            'partner' => $this->partner ? get_class($this->partner) : 'null'
        ]);

        $title = "Chat";
        
        if (!$this->error && $this->partner) {
            if ($this->userType === 'user') {
                $title = "Chat dengan " . ($this->partner->user->name ?? $this->partner->business_name ?? 'Vendor');
            } else {
                $title = "Chat dengan " . ($this->partner->name ?? 'User');
            }
        }

        return view('livewire.chat.room', [
            'error' => $this->error
        ])->layout('layouts.livewire')->title($title);
    }
}