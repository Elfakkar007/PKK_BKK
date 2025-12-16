<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckMaintenanceMode
{
    public function handle(Request $request, Closure $next): Response
    {
        // Skip check untuk admin
        if (auth()->check() && auth()->user()->isAdmin()) {
            return $next($request);
        }

        // Cek maintenance mode
        if (settings('maintenance_mode', '0') === '1') {
            $message = settings('maintenance_message', 'Website sedang dalam perbaikan. Silakan coba lagi nanti.');
            
            return response()->view('maintenance', [
                'message' => $message,
                'site_name' => settings('site_name', 'BKK SMKN 1 Purwosari')
            ], 503);
        }

        return $next($request);
    }
}