<?php

namespace App\Livewire\Components;

use Livewire\Component;

class ScheduleForm extends Component
{
    public $schedules = [];
    public $hasSchedule = false;
    public $componentId; // Tambahkan property untuk ID
    
    // Event listeners
    protected $listeners = ['resetSchedules' => 'resetScheduleData'];

    public function mount($existingSchedules = null, $hasSchedule = false)
    {
        $this->hasSchedule = $hasSchedule;
        $this->componentId = uniqid('schedule-'); // Generate unique ID
        
        if ($existingSchedules) {
            $this->schedules = $existingSchedules;
        } else {
            $this->initializeSchedules();
        }
    }

    public function initializeSchedules()
    {
        $days = ['monday', 'tuesday', 'wednesday', 'thursday', 'friday', 'saturday', 'sunday'];
        
        foreach ($days as $day) {
            $this->schedules[$day] = [
                'open_time' => '08:00',
                'close_time' => '17:00',
                'is_closed' => $day === 'sunday',
                'notes' => ''
            ];
        }
    }

    public function updatedHasSchedule($value)
    {
        // Jika checkbox dicentang, inisialisasi jadwal
        if ($value && empty(array_filter($this->schedules))) {
            $this->initializeSchedules();
        }
        
        // Emit event ke parent component
        $this->dispatch('schedule-toggle', hasSchedule: $value);
        
        // Jika schedule di-update, emit data ke parent
        if ($value) {
            $this->emitScheduleData();
        }
    }

    public function updatedSchedules($value, $key)
    {
        // Parse the key to get the day and field
        $keyParts = explode('.', $key);
        if (count($keyParts) >= 3) {
            $day = $keyParts[1];
            $field = $keyParts[2];
            
            // Validasi waktu
            if (in_array($field, ['open_time', 'close_time']) && $value) {
                $this->validateTime($day, $field, $value);
            }
            
            // Emit data ke parent component
            $this->emitScheduleData();
        }
    }

    private function validateTime($day, $field, $time)
    {
        // Validasi format waktu HH:MM
        if (!preg_match('/^([0-1][0-9]|2[0-3]):[0-5][0-9]$/', $time)) {
            $this->addError("schedules.{$day}.{$field}", 'Format waktu tidak valid. Gunakan format HH:MM');
            return;
        }

        // Validasi open_time harus sebelum close_time
        if ($field === 'close_time' && !($this->schedules[$day]['is_closed'] ?? false)) {
            $openTime = strtotime($this->schedules[$day]['open_time'] ?? '');
            $closeTime = strtotime($time);
            
            if ($openTime && $closeTime && $closeTime <= $openTime) {
                $this->addError("schedules.{$day}.{$field}", 'Waktu tutup harus setelah waktu buka');
            }
        }
    }

    public function emitScheduleData()
    {
        if ($this->hasSchedule) {
            $this->dispatch('schedule-updated', schedules: $this->schedules);
        } else {
            $this->dispatch('schedule-updated', schedules: []);
        }
    }

    public function resetScheduleData()
    {
        $this->hasSchedule = false;
        $this->schedules = [];
        $this->initializeSchedules();
        $this->dispatch('schedule-updated', schedules: []);
    }

    public function validateSchedule()
    {
        if (!$this->hasSchedule) {
            return true;
        }

        $hasValidSchedule = false;
        
        foreach ($this->schedules as $day => $schedule) {
            $closed = $schedule['is_closed'] ?? false;
            $open = $schedule['open_time'] ?? '';
            $close = $schedule['close_time'] ?? '';

            if (!$closed) {
                if (empty($open) || empty($close)) {
                    $this->addError("schedules.{$day}.open_time", "Waktu operasional hari {$day} harus diisi");
                    return false;
                }
                
                $openTime = strtotime($open);
                $closeTime = strtotime($close);
                
                if ($openTime && $closeTime && $closeTime <= $openTime) {
                    $this->addError("schedules.{$day}.close_time", "Waktu tutup harus setelah waktu buka pada hari {$day}");
                    return false;
                }
                
                $hasValidSchedule = true;
            }
        }

        if (!$hasValidSchedule) {
            $this->addError('schedules', 'Setidaknya satu hari harus memiliki jadwal operasional');
            return false;
        }

        return true;
    }

    public function getScheduleData()
    {
        return [
            'has_schedule' => $this->hasSchedule,
            'schedules' => $this->hasSchedule ? $this->schedules : []
        ];
    }

    public function render()
    {
        return view('livewire.components.schedule-form');
    }
}