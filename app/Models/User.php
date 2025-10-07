<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;

use App\Livewire\Profile\UserProfile;
use App\Livewire\Profile\VendorProfileComponent;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'phone',
        'profile_picture',
        'role'
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    // Tambahkan method ini
    public function vendorProfile()
    {
        return $this->hasOne(VendorProfileComponent::class);
    }

    public function customerProfile()
    {
        return $this->hasOne(UserProfile::class);
    }

    public function hasVendorProfile()
    {
        return $this->vendorProfile()->exists();
    }



    public function vendor()
    {
        return $this->hasOne(\App\Models\Vendor::class);
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
