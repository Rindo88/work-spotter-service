<?php

namespace App\Http\Controllers;

use App\Models\Vendor;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function index()
    {
        // Ambil 6 vendor untuk grid dan 3 vendor untuk rekomendasi
        $recommendedVendors = Vendor::with('category')
            ->orderByDesc('rating_avg')
            ->take(3)
            ->get();

        $vendors = Vendor::with('category')
            ->latest()
            ->paginate(6);

        return view('home.index', compact('vendors', 'recommendedVendors'));
    }
}
