<?php
// app/Models/SpotDetector.php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SpotDetector extends Model
{
    use HasFactory;

    protected $fillable = [
        'device_id',
        'device_name', 
        'location_name',
        'latitude',
        'longitude',
        'secret_key',
        'is_active',
        'description'
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'latitude' => 'decimal:8',
        'longitude' => 'decimal:8'
    ];

    // Relasi ke checkins yang terjadi di device ini
    public function checkins()
    {
        return $this->hasMany(Checkin::class, 'latitude', 'latitude')
                    ->where('longitude', $this->longitude);
    }
}