<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

// app/Models/Vendor.php
class Vendor extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id', 'business_name', 'description', 'category_id',
        'address', 'latitude', 'longitude', 'profile_picture', 'rating_avg'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function services()
    {
        return $this->hasMany(Service::class);
    }

    public function checkins()
    {
        return $this->hasMany(Checkin::class);
    }

    public function reviews()
    {
        return $this->hasMany(Review::class);
    }

    public function chats()
    {
        return $this->hasMany(Chat::class);
    }
}
