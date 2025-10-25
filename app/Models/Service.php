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

    // Relasi favorites
       public function favorites()
    {
        return $this->hasMany(Favorite::class);
    }

    // Count favorites
    public function getFavoritesCountAttribute()
    {
        return $this->favorites()->count();
    }

    // Relasi vendor
    public function vendor()
    {
        return $this->belongsTo(Vendor::class);
    }
}
