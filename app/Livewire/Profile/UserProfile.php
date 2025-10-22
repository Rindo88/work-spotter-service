<?php
// app/Livewire/Profile/UserProfile.php

namespace App\Livewire\Profile;

use Livewire\Component;
use Livewire\WithFileUploads;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;

class UserProfile extends Component
{
    use WithFileUploads;

    public $name;
    public $email;
    public $phone;
    public $profile_picture;
    public $successMessage;
    public $emailVerificationSent = false;

    public function mount()
    {
        $user = Auth::user();
        
        if ($user) {
            $this->name = $user->name;
            $this->email = $user->email;
            $this->phone = $user->phone;
        }
    }

    public function saveProfile()
    {
        $user = Auth::user();
        
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

    public function sendEmailVerification()
    {
        $user = Auth::user();
        
        if ($user && !$user->hasVerifiedEmail()) {
            $user->sendEmailVerificationNotification();
            $this->emailVerificationSent = true;
            $this->successMessage = 'Email verifikasi telah dikirim!';
        }
    }

    public function render()
    {
        $user = Auth::user();
        
        return view('livewire.profile.user-profile', [
            'user' => $user
        ]);
    }
}