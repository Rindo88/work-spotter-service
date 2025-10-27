<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;

class AiService
{
    protected string $baseUrl = 'https://generativelanguage.googleapis.com/v1beta/';
    protected string $model = 'gemini-2.5-flash';
    protected string $apiKey;

    public function __construct()
    {
        $this->apiKey = env('GEMINI_API_KEY');
    }

    /**
     * Analisis pola kehadiran pedagang menggunakan Gemini
     */
    public function analyzePresencePattern(array $data): ?string
    {
        $prompt = "Analisis pola kehadiran pedagang berikut ini dan berikan ringkasan singkat:\n\n" . json_encode($data, JSON_PRETTY_PRINT);

        // Gabungkan BASE URL + 'models/' + NAMA MODEL + ':' + 'generateContent'
        $endpoint = $this->baseUrl . 'models/' . $this->model . ':generateContent';

        $response = Http::post($endpoint . '?key=' . $this->apiKey, [
            // Payload/Body yang Anda buat sudah BENAR
            'contents' => [
                [
                    'parts' => [
                        ['text' => $prompt]
                    ]
                ]
            ]
        ]);

        if ($response->failed()) {
            // Gunakan throw atau log, jangan 'dd()' dalam kode produksi

            $errorData = $response->json();
            $errorMessage = $errorData['error']['message'] ?? 'Kesalahan API tidak diketahui.';

            return 'Gagal memproses permintaan ke AI. (Detail: ' . $errorMessage . ')';
        }

        return $response->json('candidates.0.content.parts.0.text') ?? 'Tidak ada respons dari AI.';
    }


    public function askGemini(string $prompt): ?string
    {

        $endpoint = $this->baseUrl . 'models/' . $this->model . ':generateContent';


        $response = Http::post($endpoint . '?key=' . $this->apiKey, [
            'contents' => [
                [
                    'parts' => [['text' => $prompt]]
                ]
            ]
        ]);

        if ($response->failed()) {

            $errorData = $response->json();

            if (is_null($errorData)) {
                return 'Gagal memproses ke Gemini API. (Kesalahan koneksi atau respons tidak valid)';
            }

            // Jika respons adalah JSON, proses seperti biasa
            $errorMessage = $errorData['error']['message'] ?? 'Kesalahan API tidak diketahui.';
            return 'Gagal memproses ke Gemini API.';
        }

        return $response->json('candidates.0.content.parts.0.text') ?? 'Tidak ada respons dari AI.';
    }
}
