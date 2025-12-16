<?php

use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\Student\StudentDashboardController;
use App\Http\Controllers\Company\CompanyDashboardController;
use App\Http\Controllers\Admin\AdminDashboardController;
use App\Http\Controllers\Admin\UserManagementController;
use App\Http\Controllers\Admin\VacancyManagementController;
use App\Http\Controllers\Admin\PostManagementController;
use App\Http\Controllers\Admin\CompanyManagementController;
use App\Http\Controllers\Admin\AboutManagementController;
use App\Http\Controllers\Admin\HighlightManagementController;
use App\Http\Controllers\Admin\SettingController;
use App\Http\Controllers\Admin\MajorManagementController;
use Illuminate\Support\Facades\Route;

// Public Routes
Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/informasi', [HomeController::class, 'information'])->name('information');
Route::get('/informasi/{slug}', [HomeController::class, 'informationShow'])->name('information.show');
Route::get('/perusahaan', [HomeController::class, 'companies'])->name('companies');
Route::get('/perusahaan/{id}', [HomeController::class, 'companyShow'])->name('companies.show');
Route::get('/lowongan', [HomeController::class, 'vacancies'])->name('vacancies');
Route::get('/lowongan/{id}', [HomeController::class, 'vacancyShow'])->name('vacancies.show');
Route::get('/tentang', [HomeController::class, 'about'])->name('about');


// Auth Routes
Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login']);
    
    Route::get('/register/siswa', [AuthController::class, 'showRegisterStudent'])->name('register.student');
    Route::post('/register/siswa', [AuthController::class, 'registerStudent']);
    
    Route::get('/register/perusahaan', [AuthController::class, 'showRegisterCompany'])->name('register.company');
    Route::post('/register/perusahaan', [AuthController::class, 'registerCompany']);
});

Route::post('/logout', [AuthController::class, 'logout'])->name('logout')->middleware('auth');

// Student Routes
Route::prefix('student')->name('student.')->middleware(['auth', 'role:student', 'approved'])->group(function () {
    Route::get('/dashboard', [StudentDashboardController::class, 'index'])->name('dashboard');
    Route::get('/profile', [StudentDashboardController::class, 'profile'])->name('profile');
    Route::put('/profile', [StudentDashboardController::class, 'updateProfile'])->name('profile.update');
    
    Route::get('/apply/{vacancy}', [StudentDashboardController::class, 'applyForm'])->name('apply.form');
    Route::post('/apply/{vacancy}', [StudentDashboardController::class, 'submitApplication'])->name('apply.submit');
    Route::get('/applications/{id}', [StudentDashboardController::class, 'applicationShow'])->name('applications.show');
});

// Company Routes
Route::prefix('company')->name('company.')->middleware(['auth', 'role:company', 'approved'])->group(function () {
    Route::get('/dashboard', [CompanyDashboardController::class, 'index'])->name('dashboard');
    Route::get('/profile', [CompanyDashboardController::class, 'profile'])->name('profile');
    Route::put('/profile', [CompanyDashboardController::class, 'updateProfile'])->name('profile.update');
    
    // Vacancy Management
    Route::get('/vacancies', [CompanyDashboardController::class, 'vacancies'])->name('vacancies');
    Route::get('/vacancies/create', [CompanyDashboardController::class, 'vacancyCreate'])->name('vacancies.create');
    Route::post('/vacancies', [CompanyDashboardController::class, 'vacancyStore'])->name('vacancies.store');
    Route::get('/vacancies/{id}/edit', [CompanyDashboardController::class, 'vacancyEdit'])->name('vacancies.edit');
    Route::put('/vacancies/{id}', [CompanyDashboardController::class, 'vacancyUpdate'])->name('vacancies.update');
    Route::delete('/vacancies/{id}', [CompanyDashboardController::class, 'vacancyDestroy'])->name('vacancies.destroy');
    
    // Application Management
    Route::get('/applications', [CompanyDashboardController::class, 'applications'])->name('applications');
    Route::get('/applications/{id}', [CompanyDashboardController::class, 'applicationShow'])->name('applications.show');
    Route::patch('/applications/{id}/status', [CompanyDashboardController::class, 'applicationUpdateStatus'])->name('applications.update-status');
});

// Admin Routes
Route::prefix('admin')->name('admin.')->middleware(['auth', 'role:admin'])->group(function () {
    Route::get('/dashboard', [AdminDashboardController::class, 'index'])->name('dashboard');
    
    // User Management
    Route::prefix('users')->name('users.')->group(function () {
        Route::get('/', [UserManagementController::class, 'index'])->name('index');
        Route::get('/pending', [UserManagementController::class, 'pending'])->name('pending'); // Harus di atas /{id}
        Route::get('/{id}', [UserManagementController::class, 'show'])->name('show');
        Route::get('/{id}/edit', [UserManagementController::class, 'edit'])->name('edit');
        Route::put('/{id}', [UserManagementController::class, 'update'])->name('update');
        Route::patch('/{id}/reset-password', [UserManagementController::class, 'resetPassword'])->name('reset-password');
        Route::patch('/{id}/approve', [UserManagementController::class, 'approve'])->name('approve');
        Route::patch('/{id}/reject', [UserManagementController::class, 'reject'])->name('reject');
        Route::delete('/{id}', [UserManagementController::class, 'destroy'])->name('destroy');
    });
    
    // Vacancy Management (Admin Approval)
    Route::prefix('vacancies')->name('vacancies.')->group(function () {
        Route::get('/', [VacancyManagementController::class, 'index'])->name('index');
        Route::get('/pending', [VacancyManagementController::class, 'pending'])->name('pending');
        Route::get('/{id}', [VacancyManagementController::class, 'show'])->name('show');
        Route::patch('/{id}/approve', [VacancyManagementController::class, 'approve'])->name('approve');
        Route::patch('/{id}/reject', [VacancyManagementController::class, 'reject'])->name('reject');
        Route::delete('/{id}', [VacancyManagementController::class, 'destroy'])->name('destroy');
    });
    
    // Post Management
    Route::resource('posts', PostManagementController::class);
    Route::post('/posts/upload-image', [PostManagementController::class, 'uploadImage'])
        ->name('posts.upload-image');
    
    // Company Management
    Route::prefix('companies')->name('companies.')->group(function () {
        Route::get('/', [CompanyManagementController::class, 'index'])->name('index');
        Route::get('/{id}', [CompanyManagementController::class, 'show'])->name('show');
        Route::get('/{id}/edit', [CompanyManagementController::class, 'edit'])->name('edit');
        Route::put('/{id}', [CompanyManagementController::class, 'update'])->name('update');
    });
    
    // About Page Management
    Route::get('/about', [AboutManagementController::class, 'edit'])->name('about.edit');
    Route::put('/about', [AboutManagementController::class, 'update'])->name('about.update');
    
    // Clear Cache
    Route::post('/cache/clear', [SettingController::class, 'clearCache'])->name('cache.clear');
    
    // Settings
    Route::prefix('settings')->name('settings.')->group(function () {
        Route::get('/', [SettingController::class, 'index'])->name('index');
        Route::put('/update', [SettingController::class, 'update'])->name('update');
        
        // Optional: API endpoints for getting settings
        Route::get('/api/{key}', [SettingController::class, 'getSetting'])->name('api.get');
        Route::get('/api', [SettingController::class, 'getAllSettings'])->name('api.all');
    });
    Route::prefix('majors')->name('majors.')->group(function () {
        Route::get('/', [MajorManagementController::class, 'index'])->name('index');
        Route::get('/create', [MajorManagementController::class, 'create'])->name('create');
        Route::post('/', [MajorManagementController::class, 'store'])->name('store');
        Route::get('/{id}/edit', [MajorManagementController::class, 'edit'])->name('edit');
        Route::put('/{id}', [MajorManagementController::class, 'update'])->name('update');
        Route::delete('/{id}', [MajorManagementController::class, 'destroy'])->name('destroy');
        Route::patch('/{id}/toggle-status', [MajorManagementController::class, 'toggleStatus'])->name('toggle-status');
    });
});
