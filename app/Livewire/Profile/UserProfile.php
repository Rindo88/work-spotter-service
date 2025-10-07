<?php
// app/Livewire/Profile/UserProfile.php

namespace App\Livewire\Profile;

use Livewire\Component;
use Livewire\WithFileUploads;
use Illuminate\Support\Facades\Auth;

class UserProfile extends Component
{
    use WithFileUploads;

    public $name;
    public $email;
    public $phone;
    public $profile_picture;
    public $successMessage;

    public function mount()
    {
        $user = Auth::user();
        
        // Pastikan user tidak null
        if ($user) {
            $this->name = $user->name;
            $this->email = $user->email;
            $this->phone = $user->phone;
        }
    }

    public function saveProfile()
    {
        $user = Auth::user();
        
        // Pastikan user tidak null sebelum validasi
        if (!$user) {
            return;
        }

        $this->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $user->id,
            'phone' => 'nullable|string|max:20',
            'profile_picture' => 'nullable|image|max:1024',
        ]);
        
        if ($this->profile_picture) {
            $photoPath = $this->profile_picture->store('profile-pictures', 'public');
            $user->profile_picture = $photoPath;
        }

        $user->update([
            'name' => $this->name,
            'email' => $this->email,
            'phone' => $this->phone,
        ]);

        $this->successMessage = 'Profile berhasil diperbarui!';
    }

    public function render()
    {
        $user = Auth::user();
        
        return view('livewire.profile.user-profile', [
            'user' => $user
        ]);
    }
}