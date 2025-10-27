<?php

namespace App\Http\Controllers;

use App\Models\Vendor;
use App\Services\AiService;
use Illuminate\Http\Request;
use Carbon\Carbon;

class VendorPredictionController extends Controller
{
    protected AiService $ai;

    public function __construct(AiService $ai)
    {
        $this->ai = $ai;
    }

    public function predict(Request $request, Vendor $vendor)
    {
        $request->validate([
            'start_time' => 'nullable|date_format:H:i',
            'end_time'   => 'nullable|date_format:H:i',
        ]);

        $start = $request->input('start_time') ?? Carbon::now()->format('H:i');
        $end   = $request->input('end_time');

        // Ambil data check-in vendor
        $checkins = $vendor->checkins()
            ->orderByDesc('created_at')
            ->limit(50)
            ->get(['latitude', 'longitude', 'location_name', 'created_at']);

        // Validasi minimal data historis
        if ($checkins->count() < 20) {
            return response()->json([
                'error' => true,
                'message' => "Data tidak mencukupi untuk analisis. Minimal 20 data check-in diperlukan (saat ini: {$checkins->count()}).",
            ], 422);
        }

        // Cek apakah check-in terakhir lebih dari 7 hari terakhir
        $hasRecentActivity = $checkins->where('created_at', '>=', now()->subDays(7))->count() > 0;

        if (!$hasRecentActivity) {
            return response()->json([
                'error' => true,
                'message' => "Pedagang ini belum melakukan check-in dalam 7 hari terakhir. Tidak bisa dilakukan prediksi yang akurat.",
            ], 422);
        }

        // Bangun prompt untuk AI
        $prompt = "Analisis kebiasaan kehadiran pedagang berikut:\n"
            . "Nama: {$vendor->business_name}\n"
            . "Kategori: {$vendor->category->name}\n"
            . "Total data check-in: {$checkins->count()}\n"
            . "Berikut riwayat lokasi terbaru:\n"
            . json_encode($checkins->take(30), JSON_PRETTY_PRINT)
            . "\n\n"
            . "Prediksi kehadiran untuk periode waktu: " . ($end ? "{$start} - {$end}" : "sekitar jam {$start}") . ".\n"
            . "Berikan hasil dengan format:\n"
            . "- Status kehadiran (aktif / istirahat / tidak hadir)\n"
            . "- Lokasi kemungkinan berada\n"
            . "- Waktu sibuk berikutnya\n"
            . "- Rekomendasi tindakan (misalnya waktu kunjungan ideal)\n";

        // Kirim ke AI
        $result = $this->ai->askGemini($prompt);

        return response()->json([
            'vendor' => $vendor->business_name,
            'start_time' => $start,
            'end_time' => $end,
            'ai_result' => $result,
        ]);
    }
}
