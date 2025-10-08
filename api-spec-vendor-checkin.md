# Work Spotter - IoT Checkin API Documentation

## Base URL
```
https://yourdomain.com/api
```

## Authentication
API menggunakan HMAC signature berdasarkan secret key per device.

## Endpoints

### 1. Checkin via IoT Device

Mendaftarkan kehadiran pedagang di lokasi tertentu menggunakan RFID.

**Endpoint:** `POST /vendor/iot/checkin`

**Headers:**
```
Content-Type: application/json
```

**Request Body:**
```json
{
  "device_id": "SPOT001",
  "rfid_uid": "RFID123456789",
  "timestamp": "2024-01-15 14:30:00",
  "signature": "generated_hmac_signature"
}
```

**Field Description:**
- `device_id` (string, required): ID unik device SpotDetector
- `rfid_uid` (string, required): UID kartu RFID vendor
- `timestamp` (string, required): Waktu checkin format `Y-m-d H:i:s`
- `signature` (string, required): HMAC-SHA256 signature

**Signature Generation:**
```javascript
// Contoh di device IoT
const message = device_id + rfid_uid + timestamp;
const signature = hash_hmac('sha256', message, secret_key);
```

**Success Response (200):**
```json
{
  "status": "success",
  "message": "Checkin berhasil",
  "data": {
    "checkin_id": 123,
    "vendor_name": "Warung Makan Sederhana",
    "checkin_time": "2024-01-15 14:30:00",
    "location": "Pasar Senen - Pintu Utama"
  }
}
```

**Error Responses:**
- `400 Bad Request` - Data tidak valid
```json
{
  "status": "error",
  "message": "Data tidak valid",
  "errors": {
    "device_id": ["The selected device id is invalid."]
  }
}
```

- `401 Unauthorized` - Signature tidak valid
```json
{
  "status": "error", 
  "message": "Signature tidak valid"
}
```

- `500 Internal Server Error` - Server error
```json
{
  "status": "error",
  "message": "Checkin gagal: Device tidak aktif"
}
```

### 2. Checkout via IoT Device

Mencatat kepulangan pedagang dari lokasi.

**Endpoint:** `POST /vendor/iot/checkout`

**Request Body:**
```json
{
  "device_id": "SPOT001",
  "rfid_uid": "RFID123456789", 
  "timestamp": "2024-01-15 18:30:00",
  "signature": "generated_hmac_signature"
}
```

**Success Response (200):**
```json
{
  "status": "success",
  "message": "Checkout berhasil",
  "data": {
    "checkin_id": 123,
    "checkout_time": "2024-01-15 18:30:00"
  }
}
```

### 3. Get Device Status

Mendapatkan informasi device dan vendor yang sedang aktif.

**Endpoint:** `GET /vendor/iot/device/{device_id}/status`

**Example:** `GET /vendor/iot/device/SPOT001/status`

**Success Response (200):**
```json
{
  "status": "success",
  "data": {
    "device": {
      "device_id": "SPOT001",
      "location_name": "Pasar Senen - Pintu Utama",
      "latitude": -6.2088,
      "longitude": 106.8456,
      "is_active": true
    },
    "active_vendors": [
      {
        "vendor_name": "Warung Makan Sederhana",
        "checkin_time": "2024-01-15 14:30:00"
      }
    ]
  }
}
```

## Testing dengan cURL

### Test Checkin:
```bash
curl -X POST https://yourdomain.com/api/v1/iot/checkin \
  -H "Content-Type: application/json" \
  -d '{
    "device_id": "SPOT001",
    "rfid_uid": "RFID123456789",
    "timestamp": "2024-01-15 14:30:00",
    "signature": "a1b2c3d4e5f6..."
  }'
```

### Test Device Status:
```bash
curl https://yourdomain.com/api/v1/iot/device/SPOT001/status
```

## Data Sample yang Tersedia

### Spot Detectors:
| Device ID | Location | Latitude | Longitude | Secret Key |
|-----------|----------|----------|-----------|------------|
| SPOT001 | Pasar Senen - Pintu Utama | -6.2088 | 106.8456 | spot001_secret_key_2024 |
| SPOT002 | Pasar Senen - Food Court | -6.2090 | 106.8460 | spot002_secret_key_2024 |
| SPOT003 | Pasar Senen - Parkir Pedagang | -6.2085 | 106.8448 | spot003_secret_key_2024 |

### RFID Tags:
| UID | Vendor | Status |
|-----|--------|--------|
| RFID123456789 | Warung Makan Sederhana | Active |

## Flow Diagram

```
Pedagang Tap RFID → IoT Device → HTTP POST /iot/checkin → Laravel API
       ↓
  Validasi Signature
       ↓
  Cari Data Vendor
       ↓
  Auto Checkout Lama (jika ada)
       ↓  
  Create Checkin Record
       ↓
  Response Success ke IoT
```

## Error Handling

- Log semua request dan error di `storage/logs/laravel.log`
- Response selalu konsisten format JSON
- HTTP status code sesuai dengan jenis error
- Message error informatif untuk debugging

---

## Updated RFID Model (tanpa field type)

```php
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
```

## Updated Seeder RFID (tanpa type)

```php
// Dalam SpotDetectorSeeder
$rfidTags = [
    [
        'uid' => 'RFID123456789',
        'vendor_id' => $vendor->id,
        'is_active' => true,
        'description' => 'Kartu RFID untuk Warung Makan Sederhana'
    ]
];
```

Sekarang dokumentasi API lengkap sudah tersedia dalam format yang mudah dicopy! 🚀