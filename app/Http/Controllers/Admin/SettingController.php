<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Setting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Cache;

class SettingController extends Controller
{
    /**
     * Display settings page
     */
    public function index()
    {
        return view('admin.settings.index');
    }

    /**
     * Update settings
     * FIXED: Better error handling and cache clearing
     */
    public function update(Request $request)
    {
        $validated = $request->validate([
            'site_name' => 'nullable|string|max:255',
            'site_tagline' => 'nullable|string|max:255',
            'site_description' => 'nullable|string|max:500',
            'contact_address' => 'nullable|string|max:500',
            'contact_phone' => 'nullable|string|max:50',
            'contact_email' => 'nullable|email|max:255',
            'social_facebook' => 'nullable|url|max:255',
            'social_instagram' => 'nullable|url|max:255',
            'social_twitter' => 'nullable|url|max:255',
            'social_youtube' => 'nullable|url|max:255',
            'site_logo' => 'nullable|image|mimes:jpg,jpeg,png,gif,svg|max:2048',
            'feature_registration' => 'nullable|boolean',
            'feature_apply_job' => 'nullable|boolean',
            'feature_notifications' => 'nullable|boolean',
            'maintenance_mode' => 'nullable|boolean',
            'maintenance_message' => 'nullable|string|max:500',
        ]);

        try {
            // Get all settings except file upload and meta fields
            $settings = $request->except(['_token', '_method', 'site_logo']);

            // Convert checkbox values
            $checkboxFields = [
                'feature_registration',
                'feature_apply_job', 
                'feature_notifications',
                'maintenance_mode'
            ];

            foreach ($checkboxFields as $field) {
                $settings[$field] = $request->has($field) ? '1' : '0';
            }

            // Save all settings one by one
            foreach ($settings as $key => $value) {
                Setting::set($key, $value ?? '');
                Log::info("Setting saved: {$key} = " . ($value ?? 'NULL'));
            }

            // Handle logo upload
            if ($request->hasFile('site_logo')) {
                $logoPath = $this->handleLogoUpload($request->file('site_logo'));
                Setting::set('site_logo', $logoPath);
                Log::info("Logo uploaded: {$logoPath}");
            }

            // CRITICAL: Clear all cache
            Setting::clearCache();
            Cache::flush(); // Force clear all cache
            
            // Clear Laravel cache
            Artisan::call('cache:clear');
            Artisan::call('config:clear');
            Artisan::call('view:clear');
            
            Log::info("Settings updated successfully and cache cleared");

            return redirect()->route('admin.settings.index')
                ->with('success', 'Settings berhasil diperbarui! Cache telah dibersihkan.');

        } catch (\Exception $e) {
            Log::error('Error updating settings: ' . $e->getMessage());
            Log::error('Stack trace: ' . $e->getTraceAsString());
            
            return redirect()->route('admin.settings.index')
                ->with('error', 'Gagal memperbarui settings: ' . $e->getMessage());
        }
    }

    /**
     * Handle logo upload
     */
    private function handleLogoUpload($file)
    {
        try {
            // Delete old logo if exists
            $oldLogo = Setting::get('site_logo');
            if ($oldLogo && Storage::disk('public')->exists($oldLogo)) {
                Storage::disk('public')->delete($oldLogo);
                Log::info("Old logo deleted: {$oldLogo}");
            }

            // Upload new logo
            $filename = 'logo_' . time() . '.' . $file->getClientOriginalExtension();
            $path = $file->storeAs('settings/logos', $filename, 'public');
            
            Log::info("New logo uploaded: {$path}");
            
            return $path;

        } catch (\Exception $e) {
            Log::error('Error uploading logo: ' . $e->getMessage());
            throw $e;
        }
    }

    /**
     * Clear application cache (AJAX endpoint)
     * FIXED: Return proper JSON response
     */
    public function clearCache(Request $request)
    {
        try {
            // Clear different types of cache
            Artisan::call('cache:clear');
            Artisan::call('config:clear');
            Artisan::call('route:clear');
            Artisan::call('view:clear');
            
            // Clear settings cache specifically
            Setting::clearCache();
            
            // Force clear all cache
            Cache::flush();

            Log::info('Cache cleared by admin: ' . auth()->user()->email);

            // ALWAYS return JSON for AJAX requests
            return response()->json([
                'success' => true,
                'message' => 'Cache berhasil dihapus!'
            ]);

        } catch (\Exception $e) {
            Log::error('Error clearing cache: ' . $e->getMessage());

            return response()->json([
                'success' => false,
                'message' => 'Gagal menghapus cache: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get specific setting value (API endpoint)
     */
    public function getSetting(Request $request, $key)
    {
        try {
            $value = Setting::get($key);
            
            return response()->json([
                'success' => true,
                'key' => $key,
                'value' => $value
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get all settings (API endpoint)
     */
    public function getAllSettings(Request $request)
    {
        try {
            $settings = Setting::getAllSettings();
            
            return response()->json([
                'success' => true,
                'settings' => $settings
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 500);
        }
    }
}