<?php
// app/Livewire/Profile/VendorProfileView.php

namespace App\Livewire\Profile;

use Livewire\Component;
use Illuminate\Support\Facades\Auth;

class VendorProfileView extends Component
{
    public function render()
    {
        $user = Auth::user();
        $vendor = $user->vendor;
        $hasRfid = $vendor->hasActiveRfid();
        
        return view('livewire.profile.vendor-profile-view', [
            'user' => $user,
            'vendor' => $vendor,
            'hasRfid' => $hasRfid
        ]);
    }
}