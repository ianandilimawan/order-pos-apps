<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

\Illuminate\Support\Facades\Schedule::call(function () {
    \App\Models\Order::where('status', 'Pending')
        ->where('payment_status', 'Unpaid')
        ->whereDate('created_at', '<', \Carbon\Carbon::today())
        ->update(['status' => 'Cancelled']);
})->dailyAt('00:01')->description('Cancel unpaid orders from previous days');

\Illuminate\Support\Facades\Schedule::command('backup:database')->dailyAt('02:00')->description('Automatically backup database at 2 AM');
