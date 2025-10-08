<?php
// app/Models/Rfid.php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Rfid extends Model
{
    use HasFactory;

    protected $fillable = [
        'uid',
        'vendor_id',    
        'is_active',
        'description'
    ];

    protected $casts = [
        'is_active' => 'boolean'
    ];

    // Relasi ke vendor
    public function vendor()
    {
        return $this->belongsTo(Vendor::class);
    }

    // Relasi ke checkins
    public function checkins()
    {
        return $this->hasMany(Checkin::class, 'rfid_tag_id');
    }
}