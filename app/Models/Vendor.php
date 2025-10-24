<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;

// app/Models/Vendor.php
class Vendor extends Model
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'user_id',
        'business_name',
        'description',
        'category_id',
        'address',
        'latitude',
        'longitude',
        'profile_picture',
        'rating_avg',
        'type',
        'is_rfid',
        'operational_notes',
    ];

    protected $casts = [
        'is_rfid' => 'boolean',
        'rating_avg' => 'float'
    ];




    public function unreadMessages()
    {
        return $this->hasMany(Chat::class)->where('sender_type', 'user')->unread();
    }

    public function latestMessageWithUser($userId = null)
    {
        $query = $this->hasOne(Chat::class)->latest();

        if ($userId) {
            $query->where('user_id', $userId);
        }

        return $query;
    }

    public function rfidTags()
    {
        return $this->hasMany(Rfid::class);
    }

    public function rfidRequests()
    {
        return $this->hasMany(RfidRequest::class);
    }

    public function hasActiveRfid()
    {
        return $this->rfidTags()->where('is_active', true)->exists();
    }
    
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

    public function schedules()
    {
        return $this->hasMany(VendorSchedule::class);
    }
    public function isFormal()
    {
        return $this->type === 'formal';
    }

    public function isInformal()
    {
        return $this->type === 'informal';
    }


    public function getCurrentStatusAttribute()
    {
        $todaySchedule = $this->schedules()
            ->where('day', strtolower(now()->format('l')))
            ->first();

        if (!$todaySchedule) {
            return 'unknown';
        }

        return $todaySchedule->isOpenNow() ? 'open' : 'closed';
    }

    public function getTodayScheduleAttribute()
    {
        return $this->schedules()
            ->where('day', strtolower(now()->format('l')))
            ->first();
    }
}
