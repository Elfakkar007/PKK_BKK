<?php

if (!function_exists('get_role_label')) {
    function get_role_label($role) {
        return match($role) {
            'admin' => 'Administrator',
            'student' => 'Siswa/Alumni',
            'company' => 'Perusahaan',
            default => $role
        };
    }
}

if (!function_exists('get_status_label')) {
    function get_status_label($status) {
        return match($status) {
            'pending' => 'Menunggu',
            'approved' => 'Disetujui',
            'rejected' => 'Ditolak',
            default => $status
        };
    }
}

if (!function_exists('get_status_badge_class')) {
    function get_status_badge_class($status) {
        return match($status) {
            'pending' => 'bg-warning',
            'approved' => 'bg-success',
            'rejected' => 'bg-danger',
            'reviewed' => 'bg-info',
            'accepted' => 'bg-success',
            default => 'bg-secondary'
        };
    }
}

if (!function_exists('get_vacancy_type_label')) {
    function get_vacancy_type_label($type) {
        return match($type) {
            'internship' => 'Magang',
            'fulltime' => 'Full Time',
            default => $type
        };
    }
}

if (!function_exists('get_gender_label')) {
    function get_gender_label($gender) {
        return match($gender) {
            'male' => 'Laki-laki',
            'female' => 'Perempuan',
            default => $gender
        };
    }
}

if (!function_exists('format_date_indonesian')) {
    function format_date_indonesian($date) {
        if (!$date) return '-';
        
        $months = [
            1 => 'Januari', 2 => 'Februari', 3 => 'Maret',
            4 => 'April', 5 => 'Mei', 6 => 'Juni',
            7 => 'Juli', 8 => 'Agustus', 9 => 'September',
            10 => 'Oktober', 11 => 'November', 12 => 'Desember'
        ];
        
        $date = \Carbon\Carbon::parse($date);
        return $date->day . ' ' . $months[$date->month] . ' ' . $date->year;
    }
}

if (!function_exists('time_ago_indonesian')) {
    function time_ago_indonesian($date) {
        if (!$date) return '-';
        
        $date = \Carbon\Carbon::parse($date);
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

if (!function_exists('format_currency')) {
    function format_currency($amount) {
        if (!$amount) return '-';
        return 'Rp ' . number_format($amount, 0, ',', '.');
    }
}

if (!function_exists('format_phone_number')) {
    function format_phone_number($phone) {
        if (!$phone) return '-';
        
        // Remove any non-numeric characters
        $phone = preg_replace('/[^0-9]/', '', $phone);
        
        // Format as Indonesian phone number
        if (substr($phone, 0, 2) == '62') {
            return '+' . substr($phone, 0, 2) . ' ' . substr($phone, 2);
        } elseif (substr($phone, 0, 1) == '0') {
            return substr($phone, 0, 4) . '-' . substr($phone, 4, 4) . '-' . substr($phone, 8);
        }
        
        return $phone;
    }
}

if (!function_exists('calculate_age')) {
    function calculate_age($birthdate) {
        if (!$birthdate) return '-';
        
        $birthdate = \Carbon\Carbon::parse($birthdate);
        return $birthdate->age;
    }
}

if (!function_exists('is_deadline_approaching')) {
    function is_deadline_approaching($deadline) {
        if (!$deadline) return false;
        
        $deadline = \Carbon\Carbon::parse($deadline);
        $now = \Carbon\Carbon::now();
        
        return $deadline->diffInDays($now) <= 7 && $deadline->isFuture();
    }
}

if (!function_exists('settings')) {
    function settings($key, $default = null) {
        return \App\Models\Setting::get($key, $default);
    }
}