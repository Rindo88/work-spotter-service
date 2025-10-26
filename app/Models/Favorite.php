<?php
// app/Models/Favorite.php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Favorite extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'vendor_id',
        'service_id',
        'favoriteable_type'
    ];

    // Relasi ke User
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Relasi ke Vendor
    public function vendor()
    {
        return $this->belongsTo(Vendor::class);
    }

    // Relasi ke Service (jika ada)
    public function service()
    {
        return $this->belongsTo(Service::class);
    }

    // Scope untuk favorit vendor
    public function scopeVendorFavorites($query)
    {
        return $query->whereNull('service_id');
    }

    // Scope untuk favorit service
    public function scopeServiceFavorites($query)
    {
        return $query->whereNotNull('service_id');
    }
}