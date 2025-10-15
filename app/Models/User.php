<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'email',
        'password',
        'phone',
        'profile_picture',
        'role'
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }



    public function chats()
    {
        return $this->hasMany(Chat::class);
    }

    public function unreadMessages()
    {
        return $this->hasMany(Chat::class)->where('sender_type', 'vendor')->unread();
    }

    public function latestMessageWithVendor($vendorId = null)
    {
        $query = $this->hasOne(Chat::class)->latest();

        if ($vendorId) {
            $query->where('vendor_id', $vendorId);
        }

        return $query;
    }

    // Relasi ke model Vendor (table vendors)
    public function vendor()
    {
        return $this->hasOne(Vendor::class);
    }

    // Cek apakah user punya vendor profile
    public function hasVendorProfile()
    {
        return $this->vendor !== null;
    }

    // Cek apakah vendor sudah aktif (checkin)
    public function isVendorActive()
    {
        if (!$this->isVendor()) return false;

        return \App\Models\Checkin::where('user_id', $this->id)
            ->where('status', 'checked_in')
            ->whereDate('checkin_time', today())
            ->exists();
    }

    // Get current active checkin
    public function currentCheckin()
    {
        if (!$this->isVendor()) return null;

        return \App\Models\Checkin::where('user_id', $this->id)
            ->where('status', 'checked_in')
            ->with('vendor')
            ->first();
    }


    // Method sederhana untuk cek vendor
    public function getHasVendorAttribute()
    {
        return $this->vendor !== null;
    }

    public function isAdmin(): bool
    {
        return $this->role === 'admin';
    }

    public function isVendor(): bool
    {
        return $this->role === 'vendor' && $this->hasVendorProfile();
    }

    public function isUser(): bool
    {
        return $this->role === 'user';
    }
}
