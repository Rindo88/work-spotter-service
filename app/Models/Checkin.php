<?php
// app/Models/Checkin.php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Checkin extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'vendor_id', 
        'rfid_tag_id',
        'checkin_time',
        'checkout_time',
        'status',
        'location_name',
        'longitude',
        'latitude'
    ];

    protected $casts = [
        'checkin_time' => 'datetime',
        'checkout_time' => 'datetime'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function vendor()
    {
        return $this->belongsTo(Vendor::class);
    }

    public function rfidTag()
    {
        return $this->belongsTo(Rfid::class);
    }
}