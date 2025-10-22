<?php
// app/Livewire/Profile/ProfileSettings.php

namespace App\Livewire\Profile;

use Livewire\Component;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;

class ProfileSettings extends Component
{
    public $current_password;
    public $new_password;
    public $new_password_confirmation;
    public $successMessage;
    public $errorMessage;

    public function updatePassword()
    {
        $this->validate([
            'current_password' => ['required', 'current_password'],
            'new_password' => ['required', 'confirmed', Password::defaults()],
        ]);

        $user = Auth::user();
        $user->update([
            'password' => Hash::make($this->new_password),
        ]);

        $this->reset(['current_password', 'new_password', 'new_password_confirmation']);
        $this->successMessage = 'Password berhasil diperbarui!';
    }

    public function logoutOtherDevices()
    {
        $this->validate([
            'current_password' => ['required', 'current_password'],
        ]);

        Auth::logoutOtherDevices($this->current_password);
        $this->successMessage = 'Sesi perangkat lain telah diakhiri!';
    }

    public function deleteAccount()
    {
        $this->validate([
            'current_password' => ['required', 'current_password'],
        ]);

        $user = Auth::user();
        Auth::logout();
        $user->delete();

        session()->invalidate();
        session()->regenerateToken();

        return redirect('/');
    }

    public function render()
    {
        return view('livewire.profile.profile-settings');
    }
}