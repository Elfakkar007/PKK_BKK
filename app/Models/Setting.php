<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;

class Setting extends Model
{
    use HasFactory;

    protected $fillable = [
        'key',
        'value',
        'type',
    ];

    /**
     * Get setting value by key
     * FIXED: Improved caching and error handling
     */
    public static function get($key, $default = null)
    {
        try {
            $cacheKey = "setting_{$key}";
            
            // Use shorter cache time (5 minutes) for better responsiveness
            return Cache::remember($cacheKey, 300, function () use ($key, $default) {
                $setting = self::where('key', $key)->first();
                return $setting ? $setting->value : $default;
            });
        } catch (\Exception $e) {
            Log::error("Error getting setting {$key}: " . $e->getMessage());
            return $default;
        }
    }

    /**
     * Set setting value
     * FIXED: Clear cache immediately after setting
     */
    public static function set($key, $value, $type = 'text')
    {
        try {
            $setting = self::updateOrCreate(
                ['key' => $key],
                ['value' => $value, 'type' => $type]
            );

            // 1. Hapus cache spesifik key ini
            Cache::forget("setting_{$key}");
            
            // 2. WAJIB: Hapus juga cache global 'all_settings'
            // Ini yang kemungkinan besar bikin tampilan tidak berubah
            Cache::forget('all_settings'); 
            
            Log::info("Setting updated: {$key}");
            
            return $setting;
        } catch (\Exception $e) {
            Log::error("Error setting {$key}: " . $e->getMessage());
            throw $e;
        }
    }

    /**
     * Clear all settings cache
     * FIXED: More thorough cache clearing
     */
    public static function clearCache()
    {
        try {
            // Get all setting keys
            $settings = self::all();
            
            foreach ($settings as $setting) {
                Cache::forget("setting_{$setting->key}");
            }
            
            // Clear tagged cache if available
            if (method_exists(Cache::getStore(), 'tags')) {
                Cache::tags(['settings'])->flush();
            }
            
            // Clear all cache (as fallback)
            Cache::flush();
            
            Log::info("All settings cache cleared");
            
            return true;
        } catch (\Exception $e) {
            Log::error("Error clearing settings cache: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Get all settings as key-value array
     */
    public static function getAllSettings()
    {
        try {
            return Cache::remember('all_settings', 300, function () {
                return self::pluck('value', 'key')->toArray();
            });
        } catch (\Exception $e) {
            Log::error("Error getting all settings: " . $e->getMessage());
            return [];
        }
    }
}