<?php

namespace App\Livewire\Chat;

use Livewire\Component;
use App\Models\Chat;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class ChatRoom extends Component
{
    public $userId;
    public $user;
    public $message = '';
    public $chats = [];

    public function mount($userId)
    {
        $this->userId = $userId;
        $this->user = User::find($userId);
        $this->loadChats();
    }

    public function loadChats()
    {
        $this->chats = Chat::where(function($query) {
                $query->where('user_id', Auth::id())
                      ->where('receiver_id', $this->userId);
            })
            ->orWhere(function($query) {
                $query->where('user_id', $this->userId)
                      ->where('receiver_id', Auth::id());
            })
            ->orderBy('created_at', 'asc')
            ->get();
    }

    public function sendMessage()
    {
        if (!empty(trim($this->message))) {
            Chat::create([
                'user_id' => Auth::id(),
                'receiver_id' => $this->userId,
                'message' => trim($this->message),
                'is_read' => false,
            ]);

            $this->message = '';
            $this->loadChats();
        }
    }

    public function render()
    {
        return view('livewire.chat.chat-room')->layout('layouts.app');
    }
}