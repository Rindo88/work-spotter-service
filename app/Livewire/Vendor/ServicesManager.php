<?php
// app/Livewire/Vendor/ServicesManager.php

namespace App\Livewire\Vendor;

use Livewire\Component;
use Livewire\WithFileUploads;
use Illuminate\Support\Facades\Auth;
use App\Models\Service;

class ServicesManager extends Component
{
    use WithFileUploads;

    public $services = [];
    public $editingServiceId = null;
    public $showServiceModal = false;

    // Form properties
    public $name;
    public $price;
    public $description;
    public $image_url;

    protected $rules = [
        'name' => 'required|string|max:255',
        'price' => 'required|numeric|min:0',
        'description' => 'nullable|string|max:500',
        'image_url' => 'nullable|image|max:2048',
    ];

    public function mount()
    {
        $this->loadServices();
    }

    public function loadServices()
    {
        $vendor = Auth::user()->vendor;
        $this->services = $vendor->services;
    }

    public function openServiceModal($serviceId = null)
    {
        if ($serviceId) {
            $service = Service::where('vendor_id', Auth::user()->vendor->id)
                             ->findOrFail($serviceId);
            
            $this->editingServiceId = $serviceId;
            $this->name = $service->name;
            $this->price = $service->price;
            $this->description = $service->description;
        } else {
            $this->resetForm();
        }
        
        $this->showServiceModal = true;
    }

    public function closeServiceModal()
    {
        $this->showServiceModal = false;
        $this->resetForm();
    }

    public function saveService()
    {
        $this->validate();

        $vendor = Auth::user()->vendor;

        $serviceData = [
            'vendor_id' => $vendor->id,
            'name' => $this->name,
            'price' => $this->price,
            'description' => $this->description,
        ];

        if ($this->image_url) {
            $imagePath = $this->image_url->store('service-images', 'public');
            $serviceData['image_url'] = $imagePath;
        }

        if ($this->editingServiceId) {
            $service = Service::where('vendor_id', $vendor->id)
                             ->findOrFail($this->editingServiceId);
            $service->update($serviceData);
            $message = 'Layanan berhasil diperbarui!';
        } else {
            Service::create($serviceData);
            $message = 'Layanan berhasil ditambahkan!';
        }

        $this->closeServiceModal();
        $this->loadServices();
        
        $this->dispatch('show-alert', [
            'type' => 'success',
            'message' => $message
        ]);
    }

    public function deleteService($serviceId)
    {
        $service = Service::where('vendor_id', Auth::user()->vendor->id)
                         ->findOrFail($serviceId);
        $service->delete();

        $this->loadServices();
        
        $this->dispatch('show-alert', [
            'type' => 'success',
            'message' => 'Layanan berhasil dihapus!'
        ]);
    }

    private function resetForm()
    {
        $this->reset(['name', 'price', 'description', 'image_url', 'editingServiceId']);
        $this->resetErrorBag();
    }

    public function render()
    {
        return view('livewire.vendor.services-manager');
    }
}