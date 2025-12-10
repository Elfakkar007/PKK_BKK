<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Student;
use App\Models\Company;
use App\Models\JobVacancy;
use App\Models\Application;
use App\Models\Post;
use Illuminate\Http\Request;

class AdminDashboardController extends Controller
{

    public function index()
    {
        $stats = [
            // User statistics
            'total_users' => User::count(),
            'pending_users' => User::where('status', 'pending')->count(),
            'students' => Student::count(),
            'companies' => Company::count(),
            
            // Vacancy statistics
            'total_vacancies' => JobVacancy::count(),
            'pending_vacancies' => JobVacancy::where('status', 'pending')->count(),
            'active_vacancies' => JobVacancy::active()->count(),
            
            // Application statistics
            'total_applications' => Application::count(),
            'pending_applications' => Application::pending()->count(),
            'accepted_applications' => Application::accepted()->count(),
            
            // Content statistics
            'published_posts' => Post::published()->count(),
            'draft_posts' => Post::where('is_published', false)->count(),
        ];

        // Recent activities
        $recentUsers = User::where('status', 'pending')
            ->latest()
            ->take(5)
            ->get();

        $recentVacancies = JobVacancy::where('status', 'pending')
            ->with('company')
            ->latest()
            ->take(5)
            ->get();

        $recentApplications = Application::with(['student', 'jobVacancy.company'])
            ->latest()
            ->take(5)
            ->get();

        // Monthly statistics (last 6 months)
        $monthlyStats = $this->getMonthlyStats();

        return view('admin.dashboard', compact(
            'stats',
            'recentUsers',
            'recentVacancies',
            'recentApplications',
            'monthlyStats'
        ));
    }

    private function getMonthlyStats()
    {
        $stats = [];
        
        for ($i = 5; $i >= 0; $i--) {
            $date = now()->subMonths($i);
            $monthName = $date->format('M Y');
            
            $stats[$monthName] = [
                'users' => User::whereYear('created_at', $date->year)
                    ->whereMonth('created_at', $date->month)
                    ->count(),
                'vacancies' => JobVacancy::whereYear('created_at', $date->year)
                    ->whereMonth('created_at', $date->month)
                    ->count(),
                'applications' => Application::whereYear('created_at', $date->year)
                    ->whereMonth('created_at', $date->month)
                    ->count(),
            ];
        }
        
        return $stats;
    }
}