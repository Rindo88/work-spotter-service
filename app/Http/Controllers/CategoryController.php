<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Vendor;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    public function show(Category $category, Request $request)
    {
        $query = Vendor::where('category_id', $category->id);
        
        // Filter berdasarkan rating
        if ($request->has('rating') && $request->rating > 0) {
            $query->where('rating_avg', '>=', $request->rating);
        }
        
        // Filter berdasarkan tipe vendor (formal/informal)
        if ($request->has('type') && in_array($request->type, ['formal', 'informal'])) {
            $query->where('type', $request->type);
        }
        
        // Filter berdasarkan lokasi terdekat
        if ($request->has('latitude') && $request->has('longitude')) {
            $lat = $request->latitude;
            $lng = $request->longitude;
            
            // Mengurutkan berdasarkan jarak terdekat menggunakan Haversine formula
            $query->selectRaw("*, 
                (6371 * acos(cos(radians(?)) * cos(radians(latitude)) * cos(radians(longitude) - radians(?)) + sin(radians(?)) * sin(radians(latitude)))) AS distance", 
                [$lat, $lng, $lat])
                ->orderBy('distance');
        }
        
        $vendors = $query->paginate(12);
        
        return view('category.show', compact('category', 'vendors'));
    }
}