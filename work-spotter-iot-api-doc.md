# 🚀 Work Spotter - IoT Checkin API Documentation (v1.1)

## Base URL
```
https://yourdomain.com/api/v1
```

---

## 🔐 Authentication
Semua request dari perangkat IoT **harus menggunakan HMAC-SHA256 signature**, yang dibangkitkan berdasarkan *secret key* unik tiap device SpotDetector.

### Cara Kerja Signature:
```javascript
// Contoh di perangkat IoT (pseudocode)
const message = device_id + rfid_uid + timestamp;
const signature = hash_hmac('sha256', message, secret_key);
```

Secret key didapat dari database `spot_detectors.secret_key`.

---

## 📍 1. Check-in via IoT Device

Mendaftarkan kehadiran pedagang di lokasi tertentu (misalnya RFID tap di area SpotDetector).

**Endpoint:**  
`POST /vendor/iot/checkin`

### Headers:
```
Content-Type: application/json
```

### Request Body:
```json
{
  "device_id": "SPOT001",
  "rfid_uid": "RFID123456789",
  "timestamp": "2025-10-13 14:30:00",
  "signature": "generated_hmac_signature"
}
```

### Field Description:
| Field | Type | Required | Description |
|--------|------|-----------|-------------|
| `device_id` | string | ✅ | ID unik SpotDetector |
| `rfid_uid` | string | ✅ | UID RFID pedagang |
| `timestamp` | string | ✅ | Format `Y-m-d H:i:s` |
| `signature` | string | ✅ | HMAC-SHA256 Signature |

---

### ✅ Success Response (200)
```json
{
  "status": "success",
  "message": "Checkin berhasil",
  "data": {
    "checkin_id": 112,
    "vendor_name": "Batagor Mang Dudung",
    "checkin_time": "2025-10-13 14:30:00",
    "device": {
      "device_id": "SPOT001",
      "location": "Cileungsi Plaza"
    }
  }
}
```

### ❌ Error Responses
- **400 Bad Request** – Data tidak valid  
```json
{
  "status": "error",
  "message": "Data tidak valid",
  "errors": {
    "device_id": ["Device ID tidak ditemukan."]
  }
}
```

- **401 Unauthorized** – Signature tidak valid  
```json
{
  "status": "error",
  "message": "Signature tidak valid"
}
```

- **409 Conflict** – Vendor sudah check-in  
```json
{
  "status": "error",
  "message": "Vendor sudah check-in di lokasi ini."
}
```

---

## 📤 2. Checkout via IoT Device

Mencatat kepulangan pedagang dari lokasi.

**Endpoint:**  
`POST /vendor/iot/checkout`

### Request Body:
```json
{
  "device_id": "SPOT001",
  "rfid_uid": "RFID123456789",
  "timestamp": "2025-10-13 18:45:00",
  "signature": "generated_hmac_signature"
}
```

### ✅ Success Response (200)
```json
{
  "status": "success",
  "message": "Checkout berhasil",
  "data": {
    "checkin_id": 112,
    "vendor_name": "Batagor Mang Dudung",
    "checkout_time": "2025-10-13 18:45:00"
  }
}
```

### ❌ Error Response (404)
```json
{
  "status": "error",
  "message": "Tidak ditemukan check-in aktif untuk RFID ini."
}
```

---

## 🛰️ 3. Get Device Status

Menampilkan status terkini SpotDetector dan vendor yang sedang aktif (check-in).

**Endpoint:**  
`GET /vendor/iot/device/{device_id}/status`

**Example:**  
`GET /vendor/iot/device/SPOT001/status`

### ✅ Success Response (200)
```json
{
  "status": "success",
  "data": {
    "device": {
      "device_id": "SPOT001",
      "device_name": "Smart Detector 1",
      "location_name": "Cileungsi Plaza",
      "latitude": -6.3923,
      "longitude": 106.9594,
      "is_active": true
    },
    "active_vendors": [
      {
        "vendor_name": "Batagor Mang Dudung",
        "checkin_time": "2025-10-13 14:30:00",
        "rfid_uid": "RFID123456789"
      }
    ]
  }
}
```

### ❌ Error Response (404)
```json
{
  "status": "error",
  "message": "Device tidak ditemukan atau tidak aktif."
}
```

---

## 🧭 Laravel Routing Example

Tambahkan pada `routes/api.php`:
```php
use App\Http\Controllers\SmartDetectorController;

Route::prefix('vendor/iot')->group(function () {
    Route::post('/checkin', [SmartDetectorController::class, 'checkin']);
    Route::post('/checkout', [SmartDetectorController::class, 'checkout']);
    Route::get('/device/{deviceId}/status', [SmartDetectorController::class, 'deviceStatus']);
});
```

---

## 🧪 Testing via cURL

### Check-in:
```bash
curl -X POST https://yourdomain.com/api/v1/vendor/iot/checkin   -H "Content-Type: application/json"   -d '{
    "device_id": "SPOT001",
    "rfid_uid": "RFID123456789",
    "timestamp": "2025-10-13 14:30:00",
    "signature": "abc123hmac..."
  }'
```

### Device Status:
```bash
curl https://yourdomain.com/api/v1/vendor/iot/device/SPOT001/status
```

---

## 🗂️ Seeder Data

### Smart Spot Detectors
Seeder: `SmartSpotDetectorSeeder.php`

| Device ID | Location | Latitude | Longitude | Secret Key |
|------------|-----------|-----------|------------|-------------|
| SPOT001 | Cileungsi Plaza | -6.3923 | 106.9594 | spot_cileungsi_plaza_key_2025 |
| SPOT002 | Pasar Lama Cileungsi | -6.3935 | 106.9610 | spot_pasar_lama_cileungsi_key_2025 |
| SPOT003 | Perumahan Metland Transyogi | -6.3909 | 106.9652 | spot_perumahan_metland_transyogi_key_2025 |
| SPOT004 | Terminal Cileungsi | -6.3975 | 106.9630 | spot_terminal_cileungsi_key_2025 |
| SPOT005 | RS MH Thamrin Cileungsi | -6.3983 | 106.9618 | spot_rs_mh_thamrin_cileungsi_key_2025 |
| SPOT006 | Pasar Jonggol | -6.4872 | 107.0404 | spot_pasar_jonggol_key_2025 |
| SPOT007 | Pasar Gunung Putri | -6.4287 | 106.9289 | spot_pasar_gunung_putri_key_2025 |
| SPOT008 | Pasar Citeureup | -6.4870 | 106.8844 | spot_pasar_citeureup_key_2025 |
| SPOT009 | Pasar Kemang | -6.4583 | 106.8009 | spot_pasar_kemang_key_2025 |
| SPOT010 | Pasar Cibinong | -6.4821 | 106.8565 | spot_pasar_cibinong_key_2025 |

---

## ⚙️ Error Handling Best Practice

- Semua log request & error disimpan di `storage/logs/laravel.log`
- Response selalu dalam format JSON dengan struktur:
  ```json
  { "status": "success|error", "message": "..." }
  ```
- Gunakan kode HTTP yang sesuai:
  - `200 OK` untuk sukses
  - `400 Bad Request` untuk data invalid
  - `401 Unauthorized` untuk signature salah
  - `404 Not Found` untuk device/vendor tidak ditemukan
  - `409 Conflict` untuk duplikasi check-in
  - `500 Internal Server Error` untuk error server
