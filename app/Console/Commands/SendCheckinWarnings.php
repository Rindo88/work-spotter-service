<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Checkin;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Notification;
use App\Notifications\CheckinWarningNotification;

class SendCheckinWarnings extends Command
{
    protected $signature = 'checkin:send-warnings';
    protected $description = 'Kirim notifikasi peringatan ke pedagang yang belum checkout sesuai jam tertentu';

    public function handle(): void
    {
        $checkins = Checkin::where('status', 'checked_in')->get();
        $now = now();

        foreach ($checkins as $checkin) {
            $hours = $checkin->checkin_time->diffInHours($now);

            $stage = match (true) {
                $hours >= 20 => 5,
                $hours >= 12 => 4,
                $hours >= 10 => 3,
                $hours >= 6  => 2,
                $hours >= 3  => 1,
                default => 0,
            };

            // Jika belum pernah dikirimi stage ini
            if ($stage > 0 && $checkin->warning_stage < $stage) {
                $checkin->update(['warning_stage' => $stage]);

                // Kirim notifikasi ke pedagang
                Notification::send($checkin->vendor, new CheckinWarningNotification($stage, $hours));

                Log::info("Notifikasi stage $stage dikirim ke pedagang ID {$checkin->vendor_id} (checkin {$checkin->id})");
            }
        }

        $this->info('✅ Notifikasi peringatan dikirim ke pedagang yang belum checkout.');
    }
}
