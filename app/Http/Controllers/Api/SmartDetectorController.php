<?php
// app/Http/Controllers/Api/IotCheckinController.php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use App\Models\Checkin;
use App\Models\Rfid;
use App\Models\SpotDetector;
use App\Models\Vendor;

class SmartDetectorController extends Controller
{
    /**
     * Checkin via IoT Device dengan RFID
     * Format payload:
     * {
     *   "device_id": "POS001",
     *   "rfid_uid": "1234567890",
     *   "timestamp": "2024-01-15 10:30:00",
     *   "signature": "hmac_signature"
     * }
     */
    public function checkin(Request $request)
    {
        Log::info('🚀 IOT CHECKIN REQUEST', $request->all());

        // Validasi input
        $validator = Validator::make($request->all(), [
            'device_id' => 'required|string|exists:spot_detectors,device_id',
            'rfid_uid' => 'required|string|exists:rfid_tags,uid',
            'timestamp' => 'required|date',
            'signature' => 'required|string'
        ]);

        if ($validator->fails()) {
            Log::error('❌ IOT CHECKIN VALIDATION FAILED', $validator->errors()->toArray());
            return response()->json([
                'status' => 'error',
                'message' => 'Data tidak valid',
                'errors' => $validator->errors()
            ], Response::HTTP_BAD_REQUEST);
        }

        try {
            // Verifikasi device
            $device = SpotDetector::where('device_id', $request->device_id)->first();
            if (!$device->is_active) {
                throw new \Exception('Device tidak aktif');
            }

            Log::info('🔑 SIGNATURE CHECK', [
                'received' => $request->signature,
                'raw_data' => $request->device_id . $request->rfid_uid . $request->timestamp,
            ]);


            // Verifikasi signature (basic example)
            $expectedSignature = hash_hmac(
                'sha256',
                $request->device_id . $request->rfid_uid . $request->timestamp,
                $device->secret_key
            );

            if (!hash_equals($expectedSignature, $request->signature)) {
                throw new \Exception('Signature tidak valid');
            }

            // Cari vendor berdasarkan RFID
            $rfidTag = Rfid::where('uid', $request->rfid_uid)->first();
            $vendor = $rfidTag->vendor;

            if (!$vendor) {
                throw new \Exception('Vendor tidak ditemukan untuk RFID ini');
            }


            // 1️⃣ Auto checkout jika masih ada checkin aktif di device lain
            $activeCheckin = Checkin::where('vendor_id', $vendor->id)
                ->where('status', 'checked_in')
                ->first();

            if ($activeCheckin) {
                // Jika checkin dari device lain → auto checkout
                if ($activeCheckin->latitude != $device->latitude || $activeCheckin->longitude != $device->longitude) {
                    $activeCheckin->update([
                        'status' => 'auto_checked_out',
                        'checkout_time' => now(),
                    ]);

                    Log::info('📍 Auto checkout karena pindah lokasi', [
                        'vendor_id' => $vendor->id,
                        'from_location' => $activeCheckin->location_name,
                        'to_location' => $device->location_name,
                    ]);
                }
            }

            // Create checkin record
            $checkin = Checkin::create([
                'user_id' => $vendor->user_id,
                'vendor_id' => $vendor->id,
                'rfid_tag_id' => $rfidTag->id,
                'latitude' => $device->latitude, // Koordinat dari device IoT
                'longitude' => $device->longitude,
                'location_name' => $device->location_name,
                'checkin_time' => $request->timestamp,
                'status' => 'checked_in'
            ]);

            Log::info('🎉 IOT CHECKIN SUCCESS', [
                'checkin_id' => $checkin->id,
                'vendor_id' => $vendor->id,
                'device_id' => $device->device_id
            ]);

            return response()->json([
                'status' => 'success',
                'message' => 'Checkin berhasil',
                'data' => [
                    'checkin_id' => $checkin->id,
                    'vendor_name' => $vendor->business_name,
                    'checkin_time' => $checkin->checkin_time,
                    'location' => $device->location_name
                ]
            ], Response::HTTP_OK);
        } catch (\Exception $e) {
            Log::error('💥 IOT CHECKIN FAILED', [
                'error' => $e->getMessage(),
                'device_id' => $request->device_id,
                'rfid_uid' => $request->rfid_uid
            ]);

            return response()->json([
                'status' => 'error',
                'message' => 'Checkin gagal: ' . $e->getMessage()
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * Checkout via IoT Device
     */
    public function checkout(Request $request)
    {
        Log::info('🚪 IOT CHECKOUT REQUEST', $request->all());

        $validator = Validator::make($request->all(), [
            'device_id' => 'required|string|exists:spot_detectors,device_id',
            'rfid_uid' => 'required|string|exists:rfid_tags,uid',
            'timestamp' => 'required|date',
            'signature' => 'required|string'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'message' => 'Data tidak valid',
                'errors' => $validator->errors()
            ], Response::HTTP_BAD_REQUEST);
        }

        try {
            $device = SpotDetector::where('device_id', $request->device_id)->first();
            $rfidTag = Rfid::where('uid', $request->rfid_uid)->first();
            $vendor = $rfidTag->vendor;


            // Cari checkin aktif
            $activeCheckin = Checkin::where('vendor_id', $vendor->id)
                ->where('status', 'checked_in')
                ->whereDate('checkin_time', today())
                ->first();

            Log::info('🔍 CHECKOUT DEBUG', [
                'vendor_id' => $vendor->id,
                'checkins_today' => Checkin::where('vendor_id', $vendor->id)
                    ->whereDate('checkin_time', today())
                    ->get(),
            ]);


            if (!$activeCheckin) {
                throw new \Exception('Tidak ada checkin aktif ditemukan');
            }

            // Update checkout
            $activeCheckin->update([
                'checkout_time' => $request->timestamp,
                'status' => 'checked_out'
            ]);

            Log::info('✅ IOT CHECKOUT SUCCESS', [
                'checkin_id' => $activeCheckin->id,
                'vendor_id' => $vendor->id
            ]);

            return response()->json([
                'status' => 'success',
                'message' => 'Checkout berhasil',
                'data' => [
                    'checkin_id' => $activeCheckin->id,
                    'checkout_time' => $activeCheckin->checkout_time
                ]
            ], Response::HTTP_OK);
        } catch (\Exception $e) {
            Log::error('❌ IOT CHECKOUT FAILED', [
                'error' => $e->getMessage()
            ]);

            return response()->json([
                'status' => 'error',
                'message' => 'Checkout gagal: ' . $e->getMessage()
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * Get device info dan status checkin terakhir
     */
    public function deviceStatus($deviceId)
    {
        try {
            $device = SpotDetector::where('device_id', $deviceId)->firstOrFail();

            $activeCheckins = Checkin::where('latitude', $device->latitude)
                ->where('longitude', $device->longitude)
                ->where('status', 'checked_in')
                ->with('vendor')
                ->get();

            return response()->json([
                'status' => 'success',
                'data' => [
                    'device' => [
                        'device_id' => $device->device_id,
                        'location_name' => $device->location_name,
                        'latitude' => $device->latitude,
                        'longitude' => $device->longitude,
                        'is_active' => $device->is_active
                    ],
                    'active_vendors' => $activeCheckins->map(function ($checkin) {
                        return [
                            'vendor_name' => $checkin->vendor->business_name,
                            'checkin_time' => $checkin->checkin_time
                        ];
                    })
                ]
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Device tidak ditemukan'
            ], Response::HTTP_NOT_FOUND);
        }
    }
}
