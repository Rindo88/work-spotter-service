<?php
// app/Livewire/Profile/ProfileManager.php

namespace App\Livewire\Profile;

use Livewire\Component;
use Illuminate\Support\Facades\Auth;

class ProfileManager extends Component
{
    public $currentRole = 'user';
    public $hasVendorProfile = false;
    
    public function mount()
    {
        $user = Auth::user();
        
        // Load vendor relationship dengan aman
        $this->hasVendorProfile = $user && $user->vendor;
        
        if ($this->hasVendorProfile) {
            $this->currentRole = session('current_profile_role', 'user');
        } else {
            $this->currentRole = 'user';
        }
    }
    
    public function switchRole($role)
    {
        // Hanya boleh switch ke vendor jika punya vendor profile
        if ($role === 'vendor' && !$this->hasVendorProfile) {
            return;
        }
        
        $this->currentRole = $role;
        session(['current_profile_role' => $role]);
    }
    
    public function render()
    {
        return view('livewire.profile.profile-manager');
    }
}