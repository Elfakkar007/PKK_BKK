<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Setting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Log;

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
     */
    public function update(Request $request)
    {
        // Validasi input
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
            // Ambil semua settings kecuali file upload dan meta fields
            $settings = $request->except(['_token', '_method', 'site_logo']);

            // Convert checkbox values (jika tidak di-check, tidak akan ada di request)
            $checkboxFields = [
                'feature_registration',
                'feature_apply_job', 
                'feature_notifications',
                'maintenance_mode'
            ];

            foreach ($checkboxFields as $field) {
                $settings[$field] = $request->has($field) ? '1' : '0';
            }

            // Simpan semua settings
            foreach ($settings as $key => $value) {
                Setting::set($key, $value ?? '');
            }

            // Handle logo upload
            if ($request->hasFile('site_logo')) {
                $this->handleLogoUpload($request->file('site_logo'));
            }

            // Clear cache
            Setting::clearCache();

            return redirect()->route('admin.settings.index')
                ->with('success', 'Settings berhasil diperbarui!');

        } catch (\Exception $e) {
            Log::error('Error updating settings: ' . $e->getMessage());
            
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
            // Hapus logo lama jika ada
            $oldLogo = Setting::get('site_logo');
            if ($oldLogo && Storage::disk('public')->exists($oldLogo)) {
                Storage::disk('public')->delete($oldLogo);
            }

            // Upload logo baru dengan nama yang di-sanitize
            $filename = 'logo_' . time() . '.' . $file->getClientOriginalExtension();
            $path = $file->storeAs('settings/logos', $filename, 'public');
            
            // Simpan path ke database
            Setting::set('site_logo', $path);

            return $path;

        } catch (\Exception $e) {
            Log::error('Error uploading logo: ' . $e->getMessage());
            throw $e;
        }
    }

    /**
     * Clear application cache (AJAX endpoint)
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

            // Log the action
            Log::info('Cache cleared by admin: ' . auth()->user()->email);

            // ALWAYS return JSON for AJAX requests
            return response()->json([
                'success' => true,
                'message' => 'Cache berhasil dihapus!'
            ]);

        } catch (\Exception $e) {
            Log::error('Error clearing cache: ' . $e->getMessage());

            // ALWAYS return JSON for errors too
            return response()->json([
                'success' => false,
                'message' => 'Gagal menghapus cache: ' . $e->getMessage()
            ], 500);
        }
    }
    /**
     * Get specific setting value (API endpoint - optional)
     */
    public function getSetting(Request $request, $key)
    {
        $value = Setting::get($key);
        
        return response()->json([
            'success' => true,
            'key' => $key,
            'value' => $value
        ]);
    }

    /**
     * Get all settings (API endpoint - optional)
     */
    public function getAllSettings(Request $request)
    {
        $settings = Setting::all();
        
        return response()->json([
            'success' => true,
            'settings' => $settings
        ]);
    }
}