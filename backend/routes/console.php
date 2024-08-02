<?php


use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schedule;
use Illuminate\Support\Facades\Storage;

// Delete auth token by setting time
Schedule::call(function () {
    DB::table('personal_access_tokens')->delete();
})->weekly();

// Clean tokens have expired for over 24h
Schedule::command('sanctum:prune-expired --hours=24')->daily();

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote')->hourly();

//Artisan::command('Delete auth_token by setting time', function () {
//    DB::table('personal_access_tokens')->delete();
//})->purpose('Delete auth token');
