<?php

use Illuminate\Support\Facades\Schedule;


Schedule::command('checkin:auto-checkout')->dailyAt('00:01');
Schedule::command('checkin:send-warnings')->everyThirtyMinutes();


