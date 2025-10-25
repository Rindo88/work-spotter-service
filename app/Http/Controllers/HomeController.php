<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Vendor;
use App\Models\Review;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class HomeController extends Controller
{
    public function index()
    {
        // Ambil 6 vendor untuk grid dan 3 vendor untuk rekomendasi
        $recommendedVendors = Vendor::with('category')
            ->select('vendors.*', DB::raw('(SELECT COUNT(*) FROM reviews WHERE reviews.vendor_id = vendors.id) as review_count'))
            ->orderByDesc('rating_avg')
            ->take(3)
            ->get();

        $vendors = Vendor::with('category')
            ->select('vendors.*', DB::raw('(SELECT COUNT(*) FROM reviews WHERE reviews.vendor_id = vendors.id) as review_count'))
            ->latest()
            ->paginate(6);
            
        // Ambil kategori untuk ditampilkan di home
        $categories = Category::orderBy('id')
            ->take(6)
            ->get();

        return view('home.index', compact('vendors', 'recommendedVendors', 'categories'));
    }

    public function loadMoreVendors(Request $request)
    {
        $page = $request->get('page', 1);
        $perPage = 6;
        
        $vendors = Vendor::with('category')
            ->select('vendors.*', DB::raw('(SELECT COUNT(*) FROM reviews WHERE reviews.vendor_id = vendors.id) as review_count'))
            ->latest()
            ->skip(($page - 1) * $perPage)
            ->take($perPage)
            ->get();

        $hasMore = Vendor::count() > ($page * $perPage);

        $html = '';
        foreach ($vendors as $vendor) {
            $html .= view('partials.vendor-card', compact('vendor'))->render();
        }

        return response()->json([
            'html' => $html,
            'hasMore' => $hasMore,
            'nextPage' => $page + 1
        ]);
    }
}
