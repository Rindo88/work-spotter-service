<?php

namespace App\Http\Controllers;

use App\Models\Vendor;
use App\Models\Category;
use Illuminate\Http\Request;

class QuickAccessController extends Controller
{
    public function index(Request $request)
    {
        $type = $request->query('type', 'all');
        
        $query = Vendor::query()->with('category');
        
        // Filter berdasarkan tipe akses cepat
        switch ($type) {
            case 'informal':
                $query->whereHas('category', function ($q) {
                    $q->where('name', 'like', '%Pedagang Informal%')
                      ->orWhere('name', 'like', '%UMKM%')
                      ->orWhere('name', 'like', '%Kaki Lima%');
                });
                $title = 'Pedagang Informal';
                $description = 'Kaki lima & UMKM';
                break;
                
            case 'top-rated':
                $query->where('rating', '>=', 4.5);
                $title = 'Rating Tertinggi';
                $description = 'Terbaik & Terpercaya';
                break;
                
            case 'nearby':
                // Implementasi filter jarak terdekat
                $title = 'Terdekat Dengan Anda';
                $description = 'Dalam radius 5km';
                break;
                
            case 'promo':
                $query->where('has_promo', true);
                $title = 'Promo Spesial';
                $description = 'Diskon & Penawaran';
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