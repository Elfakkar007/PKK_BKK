<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Schema;
use App\Models\Setting;

class AppServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        // Pengecekan agar tidak error saat artisan migrate
        if (Schema::hasTable('settings')) {
            $settings = Setting::getAllSettings(); // Pastikan method ini ada di Model Setting

            // 1. Override Config Bawaan Laravel (PENTING untuk Nama Web)
            if (isset($settings['site_name'])) {
                Config::set('app.name', $settings['site_name']);
            }
            
            // 2. Share ke SEMUA View Blade
            // Jadi di blade bisa panggil $settings['social_facebook'] langsung
            View::share('app_settings', $settings);
        }
    }
}