<?php
// app/Livewire/Vendor/RfidManager.php

namespace App\Livewire\Vendor;

use Livewire\Component;
use Illuminate\Support\Facades\Auth;
use App\Models\RfidTag;
use App\Models\RfidRequest;

class RfidManager extends Component
{
    public $activeTab = 'cards';
    public $showRequestModal = false;
    public $showContactModal = false;

    protected $listeners = ['closeModals'];

    public function mount()
    {
        $vendor = Auth::user()->vendor;
        
        if ($vendor->rfidTags->isEmpty()) {
            $this->activeTab = 'requests';
        }
    }

    public function switchTab($tab)
    {
        $this->activeTab = $tab;
    }

    public function openRequestModal()
    {
        $this->showRequestModal = true;
        $this->dispatch('open-modal', 'requestModal');
    }

    public function closeRequestModal()
    {
        $this->showRequestModal = false;
        $this->dispatch('close-modal', 'requestModal');
    }

    public function openContactModal()
    {
        $this->showContactModal = true;
        $this->dispatch('open-modal', 'contactModal');
    }

    public function closeContactModal()
    {
        $this->showContactModal = false;
        $this->dispatch('close-modal', 'contactModal');
    }

    public function closeModals()
    {
        $this->showRequestModal = false;
        $this->showContactModal = false;
    }

    public function requestRfid()
    {
        $vendor = Auth::user()->vendor;

        // Cek apakah sudah ada request pending
        $existingRequest = RfidRequest::where('vendor_id', $vendor->id)
            ->whereIn('status', ['pending', 'approved', 'processing'])
            ->first();

        if ($existingRequest) {
            $this->dispatch('show-alert', [
                'type' => 'warning',
                'message' => 'Anda sudah memiliki permintaan RFID yang sedang diproses.'
            ]);
            return;
        }

        RfidRequest::create([
            'vendor_id' => $vendor->id,
        ]);

        $this->closeRequestModal();
        
        $this->dispatch('show-alert', [
            'type' => 'success',
            'message' => 'Permintaan kartu RFID berhasil dikirim! Admin akan menghubungi Anda via chat untuk konfirmasi lebih lanjut.'
        ]);
    }

    public function contactSupport()
    {
        $this->openContactModal();
    }

    public function startChat()
    {
        $this->closeContactModal();
        return redirect()->route('chat.index');
    }

    public function render()
    {
        $vendor = Auth::user()->vendor;
        $rfidTags = $vendor->rfidTags;
        $rfidRequests = $vendor->rfidRequests()->latest()->get();

        return view('livewire.vendor.rfid-manager', [
            'rfidTags' => $rfidTags,
            'rfidRequests' => $rfidRequests,
            'hasActiveRfid' => $vendor->hasActiveRfid(),
            'activeRequest' => $vendor->rfidRequests()
                ->whereIn('status', ['pending', 'approved', 'processing', 'shipped'])
                ->first()
        ]);
    }
}