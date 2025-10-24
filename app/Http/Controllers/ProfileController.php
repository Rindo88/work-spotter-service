<?php
// app/Http/Controllers/ProfileController.php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ProfileController extends Controller
{
    public function index()
    {
        return view('profile.index');
    }

    public function general()
    {
        return view('profile.general');
    }

    public function security()
    {
        return view('profile.security');
    }

    public function favorites()
    {
        return view('profile.favorites');
    }

    public function help()
    {
        return view('profile.help');
    }

    public function feedback()
    {
        return view('profile.feedback');
    }

    public function switchRole(Request $request)
    {
        $role = $request->input('role');
        $user = Auth::user();

        if ($role === 'vendor' && !$user->vendor) {
            return response()->json(['success' => false, 'message' => 'Anda belum terdaftar sebagai vendor.']);
        }

        session(['current_profile_role' => $role]);
        
        return response()->json(['success' => true]);
    }
}