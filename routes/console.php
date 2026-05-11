<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote')->hourly();

// Elke dag om 06:00 worden alle websites gescand en opgeslagen in de database.
// Laravel's Scheduler werkt via een cronjob op de server die elke minuut
// `php artisan schedule:run` aanroept; Laravel beslist dan zelf welke taken aan de beurt zijn.
Schedule::command('pagespeed:run')->dailyAt('06:00');
