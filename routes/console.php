<?php

use Illuminate\Support\Facades\Schedule;

Schedule::command('sync:vendor')
    ->dailyAt('00:01')
    ->timezone('Asia/Jakarta')
    ->withoutOverlapping();
