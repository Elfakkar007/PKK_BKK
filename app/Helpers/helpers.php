<?php

if (!function_exists('get_role_label')) {
    /**
     * Get Indonesian role label
     */
    function get_role_label($role)
    {
        return match($role) {
            'admin' => 'Administrator',
            'student' => 'Siswa/Alumni',
            'company' => 'Perusahaan',
            default => ucfirst($role)
        };
    }
}

if (!function_exists('get_status_label')) {
    /**
     * Get Indonesian status label
     */
    function get_status_label($status)
    {
        return match($status) {
            'pending' => 'Menunggu',
            'approved' => 'Disetujui',
            'rejected' => 'Ditolak',
            'reviewed' => 'Ditinjau',
            'interview' => 'Wawancara',
            'technical_test' => 'Tes Teknis',
            'accepted' => 'Diterima',
            'closed' => 'Ditutup',
            default => ucfirst($status)
        };
    }
}

if (!function_exists('get_status_badge_class')) {
    /**
     * Get Bootstrap badge class based on status
     */
    function get_status_badge_class($status)
    {
        return match($status) {
            'pending' => 'bg-warning',
            'approved', 'accepted' => 'bg-success',
            'rejected' => 'bg-danger',
            'reviewed' => 'bg-info',
            'interview' => 'bg-primary',
            'technical_test' => 'bg-secondary',
            'closed' => 'bg-secondary',
            default => 'bg-secondary'
        };
    }
}

if (!function_exists('get_vacancy_type_label')) {
    /**
     * Get Indonesian vacancy type label
     */
    function get_vacancy_type_label($type)
    {
        return match($type) {
            'internship' => 'Magang',
            'fulltime' => 'Full Time',
            default => ucfirst($type)
        };
    }
}

if (!function_exists('format_currency')) {
    /**
     * Format currency to Indonesian Rupiah
     */
    function format_currency($amount)
    {
        return 'Rp ' . number_format($amount, 0, ',', '.');
    }
}

if (!function_exists('format_date_indonesian')) {
    /**
     * Format date to Indonesian format
     */
    function format_date_indonesian($date)
    {
        if (!$date) return '-';
        
        $months = [
            1 => 'Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni',
            'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'
        ];
        
        $date = \Carbon\Carbon::parse($date);
        return $date->format('d') . ' ' . $months[$date->format('n')] . ' ' . $date->format('Y');
    }
}

if (!function_exists('time_ago_indonesian')) {
    /**
     * Get time ago in Indonesian
     */
    function time_ago_indonesian($datetime)
    {
        if (!$datetime) return '-';
        
        $date = \Carbon\Carbon::parse($datetime);
        $now = \Carbon\Carbon::now();
        
        $diff = $date->diff($now);
        
        if ($diff->y > 0) {
            return $diff->y . ' tahun yang lalu';
        } elseif ($diff->m > 0) {
            return $diff->m . ' bulan yang lalu';
        } elseif ($diff->d > 0) {
            return $diff->d . ' hari yang lalu';
        } elseif ($diff->h > 0) {
            return $diff->h . ' jam yang lalu';
        } elseif ($diff->i > 0) {
            return $diff->i . ' menit yang lalu';
        } else {
            return 'Baru saja';
        }
    }
}

if (!function_exists('get_gender_label')) {
    /**
     * Get Indonesian gender label
     */
    function get_gender_label($gender)
    {
        return match($gender) {
            'male' => 'Laki-laki',
            'female' => 'Perempuan',
            default => '-'
        };
    }
}

if (!function_exists('format_phone_number')) {
    /**
     * Format phone number to Indonesian format
     */
    function format_phone_number($phone)
    {
        if (!$phone) return '-';
        
        // Remove all non-numeric characters
        $phone = preg_replace('/[^0-9]/', '', $phone);
        
        // Convert to 08xx format if starts with 62
        if (substr($phone, 0, 2) === '62') {
            $phone = '0' . substr($phone, 2);
        }
        
        return $phone;
    }
}

if (!function_exists('calculate_age')) {
    /**
     * Calculate age from birth date
     */
    function calculate_age($birthDate)
    {
        if (!$birthDate) return null;
        
        return \Carbon\Carbon::parse($birthDate)->age;
    }
}

    if (!function_exists('settings')) {
    /**
     * Get setting value by key
     * FIXED: Added proper error handling and default value support
     */
    function settings($key, $default = null)
    {
        try {
            // Try to get from cache first (with shorter cache time)
            $cacheKey = "setting_{$key}";
            
            return \Illuminate\Support\Facades\Cache::remember($cacheKey, 300, function () use ($key, $default) {
                $setting = \App\Models\Setting::where('key', $key)->first();
                return $setting ? $setting->value : $default;
            });
        } catch (\Exception $e) {
            \Illuminate\Support\Facades\Log::error("Error getting setting {$key}: " . $e->getMessage());
            return $default;
        }
    }
}

if (!function_exists('is_deadline_approaching')) {
    function is_deadline_approaching($deadline)
    {
        if (!$deadline) return false;
        
        $deadline = \Carbon\Carbon::parse($deadline);
        $now = \Carbon\Carbon::now();
        
        return $deadline->diffInDays($now) <= 7 && $deadline->isFuture();
    }
}