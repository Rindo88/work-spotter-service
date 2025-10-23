<?php
// app/Livewire/Profile/SecurityManager.php

namespace App\Livewire\Profile;

use Livewire\Component;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;
use Illuminate\Support\Facades\Session;

class SecurityManager extends Component
{
    public $current_password;
    public $new_password;
    public $new_password_confirmation;
    public $successMessage;
    public $errorMessage;

    // Event listeners
    protected $listeners = ['passwordUpdated' => 'onPasswordUpdated'];

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
        
        // Dispatch event
        $this->dispatch('password-updated');
        
        $this->successMessage = 'Password berhasil diperbarui!';
        
        // Log activity
        // activity()
        //     ->causedBy($user)
        //     ->log('user_updated_password');
    }

    public function logoutOtherDevices()
    {
        $this->validate([
            'current_password' => ['required', 'current_password'],
        ]);

        Auth::logoutOtherDevices($this->current_password);
        
        $this->successMessage = 'Sesi perangkat lain telah diakhiri!';
        
        // Log activity
        // activity()
        //     ->causedBy(Auth::user())
        //     ->log('user_logged_out_other_devices');
    }

    public function deleteAccount()
    {
        $this->validate([
            'current_password' => ['required', 'current_password'],
        ]);

        $user = Auth::user();
        
        // // Log activity before deletion
        // activity()
        //     ->causedBy($user)
        //     ->log('user_deleted_account');

        // Logout user
        Auth::logout();

        // Delete user
        $user->delete();

        session()->invalidate();
        session()->regenerateToken();

        // Redirect to home with success message
        return redirect('/')->with('success', 'Akun Anda telah berhasil dihapus.');
    }

    public function logout()
    {
        $user = Auth::user();
        
        // // Log activity
        // activity()
        //     ->causedBy($user)
        //     ->log('user_logged_out');

        Auth::logout();
        session()->invalidate();
        session()->regenerateToken();

        return redirect('/');
    }

    // Event handler
    public function onPasswordUpdated()
    {
        $this->successMessage = 'Password telah berhasil diperbarui!';
    }

    public function render()
    {
        return view('livewire.profile.security-manager');
    }
}