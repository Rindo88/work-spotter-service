<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Checkin;

class AutoCheckoutExpired extends Command
{
    protected $signature = 'checkin:auto-checkout';
    protected $description = 'Auto checkout semua checkin yang belum checkout dari hari sebelumnya';

    public function handle(): void
    {
        $count = Checkin::where('status', 'checked_in')
            ->whereDate('checkin_time', '<', today())
            ->update([
                'status' => 'auto_checked_out',
                'checkout_time' => now(),
            ]);

        $this->info("✅ $count checkin otomatis di-checkout karena sudah lewat hari.");
    }
}
