<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote')->hourly();


Artisan::command('cpar:send-assigned-mail', function () {
})->purpose('Process CPAR assigned email notifications');


Schedule::command('app:send-assigned-mail')->everyMinute()
    ->withoutOverlapping();