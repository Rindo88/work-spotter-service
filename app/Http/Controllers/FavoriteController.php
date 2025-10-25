<?php
// app/Http/Controllers/FavoriteController.php

namespace App\Http\Controllers;

use App\Models\Favorite;
use App\Models\Vendor;
use App\Models\Service;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class FavoriteController extends Controller
{
    // Tampilkan halaman favorit
    public function index()
    {
        $user = Auth::user();
        
        $vendorFavorites = $user->vendorFavorites()->get()->pluck('vendor');
        $serviceFavorites = $user->serviceFavorites()->get()->pluck('service');
        
        return view('profile.favorites', compact('vendorFavorites', 'serviceFavorites'));
    }

    // Tambah favorit vendor
    public function favoriteVendor(Request $request, Vendor $vendor)
    {
        $user = Auth::user();

        // Cek apakah sudah difavoritkan
        if ($user->hasFavoritedVendor($vendor->id)) {
            return response()->json(['message' => 'Vendor sudah difavoritkan'], 422);
        }

        Favorite::create([
            'user_id' => $user->id,
            'vendor_id' => $vendor->id,
            'service_id' => null,
        ]);

        return response()->json([
            'message' => 'Vendor berhasil ditambahkan ke favorit',
            'favorites_count' => $vendor->favorites_count + 1
        ]);
    }

    // Tambah favorit service
    public function favoriteService(Request $request, Service $service)
    {
        $user = Auth::user();

        // Cek apakah sudah difavoritkan
        if ($user->hasFavoritedService($service->id)) {
            return response()->json(['message' => 'Service sudah difavoritkan'], 422);
        }

        Favorite::create([
            'user_id' => $user->id,
            'vendor_id' => $service->vendor_id,
            'service_id' => $service->id,
        ]);

        return response()->json([
            'message' => 'Service berhasil ditambahkan ke favorit',
            'favorites_count' => $service->favorites_count + 1
        ]);
    }

    // Hapus favorit
    public function unfavorite(Request $request, $favoriteId)
    {
        $favorite = Favorite::where('user_id', Auth::id())->findOrFail($favoriteId);
        $favorite->delete();

        return response()->json(['message' => 'Berhasil dihapus dari favorit']);
    }

    // Hapus favorit by vendor/service ID
    public function unfavoriteVendor(Request $request, Vendor $vendor)
    {
        Favorite::where('user_id', Auth::id())
            ->where('vendor_id', $vendor->id)
            ->whereNull('service_id')
            ->delete();

        return response()->json(['message' => 'Vendor dihapus dari favorit']);
    }

    public function unfavoriteService(Request $request, Service $service)
    {
        Favorite::where('user_id', Auth::id())
            ->where('service_id', $service->id)
            ->delete();

        return response()->json(['message' => 'Service dihapus dari favorit']);
    }
}