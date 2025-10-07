<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class VendorSchedule extends Model
{
    use HasFactory;

    protected $fillable = [
        'vendor_id',
        'day',
        'open_time',
        'close_time',
        'is_closed',
        'notes'
    ];

    protected $casts = [
        'open_time' => 'datetime:H:i',
        'close_time' => 'datetime:H:i',
        'is_closed' => 'boolean'
    ];

    public function vendor()
    {
        return $this->belongsTo(Vendor::class);
    }

    // Helper methods
    public function getDayNameAttribute()
    {
        $days = [
            'monday' => 'Senin',
            'tuesday' => 'Selasa',
            'wednesday' => 'Rabu',
            'thursday' => 'Kamis',
            'friday' => 'Jumat',
            'saturday' => 'Sabtu',
            'sunday' => 'Minggu'
        ];

        return $days[$this->day] ?? $this->day;
    }

    public function getOperatingHoursAttribute()
    {
        if ($this->is_closed) {
            return 'Tutup';
        }

        if ($this->open_time && $this->close_time) {
            return $this->open_time->format('H:i') . ' - ' . $this->close_time->format('H:i');
        }

        return '24 Jam';
    }

    public function isOpenNow()
    {
        if ($this->is_closed) {
            return false;
        }

        $now = now();
        $currentTime = $now->format('H:i:s');
        $currentDay = strtolower($now->format('l'));

        if ($currentDay !== $this->day) {
            return false;
        }

        if (!$this->open_time || !$this->close_time) {
            return true; // 24 jam
        }

        return $currentTime >= $this->open_time->format('H:i:s') && 
               $currentTime <= $this->close_time->format('H:i:s');
    }
}