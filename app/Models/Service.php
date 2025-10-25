<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Service extends Model
{
    protected $fillable = [
        'vendor_id',
        'name',
        'price',
        'description',
        'image_url',
    ];

    public function vendor()
    {
        return $this->belongsTo(Vendor::class);
    }

    
}
