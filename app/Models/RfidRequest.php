<?php
// app/Models/RfidRequest.php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RfidRequest extends Model
{
    use HasFactory;

    protected $fillable = [
        'vendor_id',
        'status',
        'approved_by',
        'approved_at',
        'tracking_number',
        'admin_notes'
    ];

    protected $casts = [
        'approved_at' => 'datetime',
    ];

    public function vendor()
    {
        return $this->belongsTo(Vendor::class);
    }

    public function approver()
    {
        return $this->belongsTo(User::class, 'approved_by');
    }

    public function isPending()
    {
        return $this->status === 'pending';
    }

    public function isApproved()
    {
        return $this->status === 'approved';
    }

    public function isRejected()
    {
        return $this->status === 'rejected';
    }

    public function isProcessing()
    {
        return $this->status === 'processing';
    }

    public function isShipped()
    {
        return $this->status === 'shipped';
    }

    public function isDelivered()
    {
        return $this->status === 'delivered';
    }

    public function getStatusBadgeAttribute()
    {
        $statuses = [
            'pending' => ['class' => 'bg-warning text-dark', 'text' => 'Menunggu'],
            'approved' => ['class' => 'bg-info text-white', 'text' => 'Disetujui'],
            'rejected' => ['class' => 'bg-danger text-white', 'text' => 'Ditolak'],
            'processing' => ['class' => 'bg-primary text-white', 'text' => 'Diproses'],
            'shipped' => ['class' => 'bg-success text-white', 'text' => 'Dikirim'],
            'delivered' => ['class' => 'bg-success text-white', 'text' => 'Terkirim']
        ];
        
        return $statuses[$this->status];
    }
}