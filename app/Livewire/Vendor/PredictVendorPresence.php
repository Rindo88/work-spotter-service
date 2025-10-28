<?php

namespace App\Http\Livewire\Vendor;

use Livewire\Component;
use Illuminate\Support\Facades\Http;
use App\Models\Vendor;

class PredictVendorPresence extends Component
{
    public Vendor $vendor;

    // input
    public ?string $start_time = null;
    public ?string $end_time = null;

    // state
    public ?array $result = null;        // hasil dari API (array)
    public ?string $errorMessage = null; // pesan error bila ada
    public bool $loading = false;        // untuk menandai loading state

    protected $rules = [
        'start_time' => 'nullable|date_format:H:i',
        'end_time'   => 'nullable|date_format:H:i',
    ];

    public function mount(Vendor $vendor)
    {
        $this->vendor = $vendor;

        // pastikan state awal terdefinisi
        $this->result = null;
        $this->errorMessage = null;
        $this->loading = false;
    }

    public function predict()
    {
        $this->validate();

        // reset state lama
        $this->result = null;
        $this->errorMessage = null;
        $this->loading = true;

        try {
            // panggil API internal / route controller yang membuat validasi dan menghub ke Gemini
            // ubah route('vendor.predict', $this->vendor) sesuai route kamu
            $response = Http::timeout(60)
                ->withHeaders(['Accept' => 'application/json'])
                ->post(route('vendor.predict', $this->vendor), [
                    'start_time' => $this->start_time,
                    'end_time'   => $this->end_time,
                ]);

            if ($response->failed()) {
                $payload = $response->json() ?? [];
                $this->errorMessage = $payload['message'] ?? ($payload['error'] ?? 'Gagal memproses prediksi (API).');
            } else {
                // hasil sukses — sesuaikan struktur array yang dikembalikan controller kamu
                $this->result = $response->json();
            }
        } catch (\Exception $e) {
            // connection/timeouts/exception lain
            $this->errorMessage = 'Gagal terhubung ke layanan prediksi: ' . $e->getMessage();
        } finally {
            $this->loading = false;
        }
    }

    public function render()
    {
        return view('livewire.vendor.predict-vendor-presence');
    }
}
