<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Chat extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'vendor_id', 
        'sender_type',
        'message',
        'attachment_url',
        'is_read'
    ];

    protected $attributes = [
        'is_read' => false,
        'sender_type' => 'user' // Default value
    ];

    protected $casts = [
        'is_read' => 'boolean',
        'created_at' => 'datetime',
        'updated_at' => 'datetime'
    ];

    // Relationship ke user (konsumen)
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Relationship ke vendor (pedagang)  
    public function vendor()
    {
        return $this->belongsTo(Vendor::class);
    }

    // Scope untuk chat antara user dan vendor tertentu
    public function scopeBetweenUserAndVendor($query, $userId, $vendorId)
    {
        return $query->where('user_id', $userId)
                    ->where('vendor_id', $vendorId);
    }

    // Scope untuk semua chat user dengan vendor manapun
    public function scopeUserChats($query, $userId)
    {
        return $query->where('user_id', $userId);
    }

    // Scope untuk semua chat vendor dengan user manapun  
    public function scopeVendorChats($query, $vendorId)
    {
        return $query->where('vendor_id', $vendorId);
    }

    // Scope pesan belum dibaca
    public function scopeUnread($query)
    {
        return $query->where('is_read', false);
    }

    // Helper method untuk cek pengirim
    public function isSentByUser()
    {
        return $this->sender_type === 'user';
    }

    public function isSentByVendor()
    {
        return $this->sender_type === 'vendor';
    }

    // Accessor untuk pesan yang aman
    public function getSafeMessageAttribute()
    {
        return e($this->message); // Escape HTML untuk keamanan
    }
}