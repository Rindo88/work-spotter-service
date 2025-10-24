<?php
// app/Livewire/Profile/UserProfileView.php

namespace App\Livewire\Profile;

use Livewire\Component;
use Illuminate\Support\Facades\Auth;

class UserProfileView extends Component
{
    public $successMessage;
    public $emailVerificationSent = false;

    public function sendEmailVerification()
    {
        $user = Auth::user();

        if ($user && !$user->hasVerifiedEmail()) {
            $user->sendEmailVerificationNotification();
            $this->emailVerificationSent = true;
            $this->successMessage = 'Email verifikasi telah dikirim!';
        }
    }

    public function logout()
    {
        Auth::user();
        Auth::logout();
        session()->invalidate();
        session()->regenerateToken();

        return redirect('/');
    }

    public function render()
    {
        $user = Auth::user();
        $isVendor = $user->vendor !== null;

        return view('livewire.profile.user-profile-view', [
            'user' => $user,
            'isVendor' => $isVendor
        ]);
    }
}
