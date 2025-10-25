<?php

namespace App\Http\Controllers;

use App\Models\Vendor;
use App\Models\Category;
use App\Models\Favorite;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class QuickAccessController extends Controller
{
    public function index(Request $request)
    {
        $type = $request->query('type', 'all');

        $query = Vendor::query()->with('category');

        // Filter berdasarkan tipe akses cepat
        switch ($type) {
            case 'informal':
                $query->where('type', 'informal');
                $title = 'Pedagang Informal';
                $description = 'Kaki lima & Keliling';
                break;

            case 'formal':
                $query->where('type', 'formal');
                $title = 'Pedagang Formal';
                $description = 'Toko & Lapak';
                break;

            case 'top-rated':
                // RATING TERTINGGI - berdasarkan average rating dari reviews
                $query->withAvg('reviews', 'rating')
                    ->withCount('reviews')
                    ->having('reviews_avg_rating', '>=', 1) // Minimal ada 1 rating
                    ->orderBy('reviews_avg_rating', 'desc')
                    ->orderBy('reviews_count', 'desc'); // Jika rating sama, urutkan oleh jumlah review
                $title = 'Rating Tertinggi';
                $description = 'Terbaik & Terpercaya';
                break;

            case 'nearby':
                // Implementasi filter jarak terdekat
                $query->orderBy('created_at', 'desc'); // Temporary
                $title = 'Terdekat Dengan Anda';
                $description = 'Dalam radius 5km';
                break;

            case 'favorite':
                // VENDOR PALING FAVORIT - berdasarkan jumlah favorit terbanyak
                $query->withCount('favorites')
                    ->having('favorites_count', '>', 0)
                    ->orderBy('favorites_count', 'desc');
                $title = 'Paling Favorit';
                $description = 'Banyak Disukai';
                break;

            default:
                $title = 'Semua Akses Cepat';
                $description = 'Pilihan kategori populer';
                break;
        }

        $vendors = $query->paginate(10);

        return view('quick-access.index', [
            'vendors' => $vendors,
            'title' => $title,
            'description' => $description,
            'type' => $type
        ]);
    }
}
