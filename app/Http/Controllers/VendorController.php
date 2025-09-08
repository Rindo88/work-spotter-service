<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Chat;
use App\Models\Vendor;
use App\Models\ServiceCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class VendorController extends Controller
{
    public function showRegistrationForm()
    {
        $categories = Category::all();
        return view('vendor.register', compact('categories'));
    }

    public function register(Request $request)
    {
        $validated = $request->validate([
            'business_name' => ['required', 'string', 'max:255', 'unique:vendors'],
            'category_id' => ['required', 'exists:categories,id'],
            'description' => ['required', 'string', 'max:1000'],
            'address' => ['required', 'string', 'max:500'],
            'latitude' => ['required', 'numeric', 'between:-90,90'],
            'longitude' => ['required', 'numeric', 'between:-180,180'],
            'profile_picture' => ['nullable', 'image', 'max:2048'],
            'phone' => ['required', 'string', 'max:15', 'regex:/^[0-9]{9,13}$/'],
            'email' => ['required', 'email', 'max:255'],
        ]);

        // Handle profile picture upload
        $profilePicturePath = null;
        if ($request->hasFile('profile_picture')) {
            $profilePicturePath = $request->file('profile_picture')->store('vendor-photos', 'public');
        }

        // Create vendor
        $vendor = Vendor::create([
            'user_id' => Auth::id(),
            'business_name' => $validated['business_name'],
            'category_id' => $validated['category_id'],
            'description' => $validated['description'],
            'address' => $validated['address'],
            'latitude' => $validated['latitude'],
            'longitude' => $validated['longitude'],
            'profile_picture' => $profilePicturePath,
        ]);

        // Update user phone and email
        $user = Auth::user();
        $user->phone = $validated['phone'];
        $user->email = $validated['email'];
        $user->role = 'vendor';
        $user->save();

        return redirect()->route('profile')
            ->with('success', 'Pendaftaran pedagang berhasil!');
    }


     public function dashboard()
    {
        $vendor = Auth::user()->vendor;
        
        // Stats calculation
        $stats = [
            'today_visitors' => $this->getTodayVisitors($vendor),
        ];
        
        $recentMessages = $this->getRecentMessages($vendor);
        
        // Business hours
        $businessHours = $this->getBusinessHours($vendor);
        
        return view('vendor.dashboard', compact(
            'vendor', 
            'recentMessages',
            'businessHours'
        ));
    }
    
    private function getTodayVisitors(Vendor $vendor)
    {
        // Logic to get today's visitors
        return rand(5, 20); // Placeholder
    }

    
    
    
    private function getRecentMessages(Vendor $vendor)
    {
        return Chat::where('vendor_id', $vendor->id)
            ->with('user')
            ->orderBy('created_at', 'desc')
            ->take(5)
            ->get()
            ->map(function($chat) {
                return (object) [
                    'customer_name' => $chat->user->name,
                    'message' => $chat->message,
                    'created_at' => $chat->created_at,
                    'unread' => !$chat->is_read
                ];
            });
    }
    
    private function getBusinessHours(Vendor $vendor)
    {
        // Default business hours
        return [
            'Senin' => ['open' => '08:00', 'close' => '17:00'],
            'Selasa' => ['open' => '08:00', 'close' => '17:00'],
            'Rabu' => ['open' => '08:00', 'close' => '17:00'],
            'Kamis' => ['open' => '08:00', 'close' => '17:00'],
            'Jumat' => ['open' => '08:00', 'close' => '17:00'],
            'Sabtu' => ['open' => '09:00', 'close' => '15:00'],
            'Minggu' => ['open' => null, 'close' => null],
        ];
    }
    
    private function getStatusColor($status)
    {
        $colors = [
            'pending' => 'warning',
            'confirmed' => 'info',
            'processing' => 'primary',
            'completed' => 'success',
            'cancelled' => 'danger'
        ];
        
        return $colors[$status] ?? 'secondary';
    }
}
