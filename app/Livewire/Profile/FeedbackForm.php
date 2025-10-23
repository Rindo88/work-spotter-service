<?php
// app/Livewire/Profile/FeedbackForm.php

namespace App\Livewire\Profile;

use Livewire\Component;
use Illuminate\Support\Facades\Auth;

class FeedbackForm extends Component
{
    public $rating = 5;
    public $message;
    public $successMessage;

    public function submitFeedback()
    {
        $this->validate([
            'rating' => 'required|integer|min:1|max:5',
            'message' => 'required|string|min:10|max:1000',
        ]);

        // Simpan feedback ke database (bisa ditambahkan model Feedback nanti)
        // Feedback::create([...]);

        $this->successMessage = 'Terima kasih atas masukan Anda!';
        $this->reset(['rating', 'message']);
    }

    public function render()
    {
        return view('livewire.profile.feedback-form');
    }
}