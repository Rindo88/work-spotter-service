<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Chat;
use App\Models\Checkin;
use App\Models\Vendor;
use App\Models\ServiceCategory;
use App\Models\VendorSchedule;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class VendorController extends Controller
{
    // private function createDefaultSchedules(Vendor $vendor)
    // {
    //     $days = ['monday', 'tuesday', 'wednesday', 'thursday', 'friday', 'saturday', 'sunday'];

    //     foreach ($days as $day) {
    //         $isWeekend = in_array($day, ['saturday', 'sunday']);

    //         VendorSchedule::create([
    //             'vendor_id' => $vendor->id,
    //             'day' => $day,
    //             'open_time' => $isWeekend ? '09:00:00' : '08:00:00',
    //             'close_time' => $isWeekend ? '17:00:00' : '20:00:00',
    //             'is_closed' => $day === 'sunday' && $vendor->is_informal,
    //             'notes' => $day === 'sunday' ? 'Hari Minggu tutup' : null
    //         ]);
    //     }
    // }

    public function show(Vendor $vendor)
    {
        $vendor->load(['user', 'category', 'reviews.user', 'services', 'schedules']);

        $latestCheckin = null;
        $currentLocation = null;

        if ($vendor->type === 'informal') {
            $latestCheckin = Checkin::where('vendor_id', $vendor->id)
                ->where('status', 'checked_in')
                ->whereDate('checkin_time', today())
                ->latest()
                ->first();

            if ($latestCheckin) {
                $currentLocation = [
                    'latitude' => $latestCheckin->latitude,
                    'longitude' => $latestCheckin->longitude,
                    'location_name' => $latestCheckin->location_name,
                    'checkin_time' => $latestCheckin->checkin_time,
                    'is_active' => true
                ];
            }
        }

        $services = $vendor->services()->latest()->get();

        return view('vendor.show', compact(
            'vendor',
            'services',
            'latestCheckin',
            'currentLocation'
        ));
    }

    public function storeReview(Request $request, Vendor $vendor)
    {
        $request->validate([
            'rating' => 'required|integer|between:1,5',
            'comment' => 'required|string|max:500',
        ]);

        // Create review
        $vendor->reviews()->create([
            'user_id' => Auth::user()->id,
            'rating' => $request->rating,
            'comment' => $request->comment,
        ]);

        // Update vendor rating average
        $vendor->update([
            'rating_avg' => $vendor->reviews()->avg('rating')
        ]);

        return redirect()->back()->with('success', 'Review berhasil ditambahkan!');
    }


    public function showRegistrationForm()
    {
        $categories = Category::all();
        return view('vendor.register', compact('categories'));
    }

    public function register(Request $request)
    {
        $validated = $request->validate([
            'business_name' => 'required|min:3|max:255',
            'category_id' => 'required|exists:service_categories,id',
            'description' => 'required|min:10|max:1000',
            'address' => 'required|min:10|max:500',
            'phone' => 'required|regex:/^[0-9]{9,13}$/',
            'email' => 'required|email',
            'latitude' => 'required|numeric|between:-90,90',
            'longitude' => 'required|numeric|between:-180,180',
            'type' => 'required|in:formal,informal',
            'is_mobile' => 'boolean',
            'operational_notes' => 'nullable|string|max:500',
            'business_images.*' => 'image|max:2048',
            'business_license' => 'nullable|file|mimes:pdf,jpg,png|max:5120',
            'schedules' => 'nullable|array'
        ]);

        // Create vendor
        $vendor = Vendor::create([
            'user_id' => Auth::id(),
            'business_name' => $validated['business_name'],
            'category_id' => $validated['category_id'],
            'description' => $validated['description'],
            'address' => $validated['address'],
            'latitude' => $validated['latitude'],
            'longitude' => $validated['longitude'],
            'type' => $validated['type'],
            'is_mobile' => $validated['is_mobile'] ?? false,
            'operational_notes' => $validated['operational_notes'] ?? null,
        ]);

        // Handle business images
        if ($request->hasFile('business_images')) {
            foreach ($request->file('business_images') as $image) {
                $path = $image->store('vendor-images', 'public');
                // Save to vendor images table
            }
        }

        // Handle business license for formal vendors
        if ($validated['type'] === 'formal' && $request->hasFile('business_license')) {
            $licensePath = $request->file('business_license')->store('vendor-licenses', 'public');
            // Save license path
        }

        // Create schedules for informal vendors
        if ($validated['type'] === 'informal' && !empty($validated['schedules'])) {
            foreach ($validated['schedules'] as $day => $schedule) {
                VendorSchedule::create([
                    'vendor_id' => $vendor->id,
                    'day' => $day,
                    'open_time' => $schedule['open'] ?? null,
                    'close_time' => $schedule['close'] ?? null,
                    'is_closed' => $schedule['closed'] ?? false,
                ]);
            }
        }

        // Update user information
        $user = Auth::user();
        $user->phone = $validated['phone'];
        $user->email = $validated['email'];
        $user->role = 'vendor';
        $user->save();

        return redirect()->route('vendor.dashboard')
            ->with('success', 'Pendaftaran berhasil!');
    }


    public function dashboard()
    {
        return view('vendor.dashboard');
    }

    public function profile()
    {
        return view('vendor.profile');
    }

    public function services()
    {
        return view('vendor.services');
    }

    public function schedule()
    {
        return view('vendor.schedule');
    }

    public function rfid()
    {
        return view('vendor.rfid');
    }

    public function location()
    {
        return view('vendor.location');
    }
}
