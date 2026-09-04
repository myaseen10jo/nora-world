<?php

use Illuminate\Support\Facades\Artisan;

Artisan::command('diagnose', function () {
    $this->line('PHP: ' . phpversion());
    $this->line('APP_DEBUG: ' . config('app.debug'));
    $this->line('APP_ENV: ' . config('app.env'));
    $this->line('DB: ' . config('database.default'));
    $this->line('CACHE: ' . config('cache.default'));
    $this->line('SESSION: ' . config('session.driver'));
    
    try {
        DB::connection()->getPdo();
        $this->line('DB Connection: OK');
    } catch (\Exception $e) {
        $this->line('DB Connection FAILED: ' . $e->getMessage());
    }
    
    // Test view rendering
    try {
        view('home.index')->render();
        $this->line('View render: OK');
    } catch (\Exception $e) {
        $this->line('View render FAILED: ' . $e->getMessage());
    }
});
