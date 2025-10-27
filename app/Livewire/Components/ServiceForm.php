<?php
// app/Livewire/Components/ServiceForm.php

namespace App\Livewire\Components;

use Livewire\Component;
use Livewire\WithFileUploads;

class ServiceForm extends Component
{
    use WithFileUploads;

    public $services = [];
    public $hasServices = false;
    public $componentId;
    
    protected $listeners = ['resetServices' => 'resetServiceData'];

    public function mount($existingServices = null, $hasServices = false)
    {
        $this->hasServices = $hasServices;
        $this->componentId = uniqid('service-');
        
        if ($existingServices) {
            $this->services = $existingServices;
        } else {
            $this->initializeServices();
        }
    }

    public function initializeServices()
    {
        $this->services = [
            [
                'name' => '',
                'price' => '',
                'description' => '',
                'image' => null,
                'image_url' => null
            ]
        ];
    }

    public function updatedHasServices($value)
    {
        $this->dispatch('service-toggle', hasServices: $value);
        
        if ($value && empty(array_filter($this->services))) {
            $this->initializeServices();
        }
        
        if ($value) {
            $this->emitServiceData();
        } else {
            $this->dispatch('service-updated', services: []);
        }
    }

    public function addService()
    {
        $this->services[] = [
            'name' => '',
            'price' => '',
            'description' => '',
            'image' => null,
            'image_url' => null
        ];
    }

    public function removeService($index)
    {
        if (count($this->services) > 1) {
            unset($this->services[$index]);
            $this->services = array_values($this->services);
        }
        
        $this->emitServiceData();
    }

    public function updatedServices($value, $key)
    {
        $this->emitServiceData();
    }

    public function emitServiceData()
    {
        if ($this->hasServices) {
            $this->dispatch('service-updated', services: $this->services);
        }
    }

    public function resetServiceData()
    {
        $this->hasServices = false;
        $this->services = [];
        $this->initializeServices();
        $this->dispatch('service-updated', services: []);
    }

    public function validateServices()
    {
        if (!$this->hasServices) {
            return true;
        }

        $validCount = 0;
        
        foreach ($this->services as $index => $service) {
            $name = trim($service['name'] ?? '');
            $price = $service['price'] ?? '';
            
            if (!empty($name)) {
                if (empty($price)) {
                    $this->addError("services.{$index}.price", "Harga untuk layanan '{$name}' harus diisi");
                    return false;
                }
                
                if (!is_numeric($price) || $price < 0) {
                    $this->addError("services.{$index}.price", "Harga untuk layanan '{$name}' harus angka yang valid");
                    return false;
                }
                
                $validCount++;
            }
        }

        if ($validCount === 0) {
            $this->addError('services', 'Setidaknya satu layanan harus diisi dengan nama dan harga');
            return false;
        }

        return true;
    }

    public function getServiceData()
    {
        return [
            'has_services' => $this->hasServices,
            'services' => $this->hasServices ? $this->services : []
        ];
    }

    public function render()
    {
        return view('livewire.components.service-form');
    }
}