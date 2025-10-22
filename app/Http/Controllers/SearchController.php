<?php

namespace App\Http\Controllers;

use App\Models\Service;
use App\Models\Vendor;
use Illuminate\Http\Request;

class SearchController extends Controller
{
    public function results(Request $request)
    {
        $query = $request->input('query');
        
        if (empty($query)) {
            return redirect()->route('home');
        }
        
        $vendors = Vendor::where('business_name', 'like', '%' . $query . '%')
            ->orWhere('description', 'like', '%' . $query . '%')
            ->select('vendors.*')
            ->selectRaw('(SELECT COUNT(*) FROM reviews WHERE reviews.vendor_id = vendors.id) as review_count')
            ->paginate(10);
            
        $services = Service::where('name', 'like', '%' . $query . '%')
            ->orWhere('description', 'like', '%' . $query . '%')
            ->with('vendor')
            ->paginate(10);
            
        return view('search.results', compact('vendors', 'services', 'query'));
    }
}