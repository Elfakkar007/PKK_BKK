@extends('layouts.app')

@section('title', 'Dashboard Perusahaan - BKK SMKN 1 Purwosari')

@section('content')
<div class="bg-primary text-white py-4">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-md-8">
                <h2 class="fw-bold mb-2">Dashboard Perusahaan</h2>
                <p class="mb-0">{{ $company->name }}</p>
            </div>
            <div class="col-md-4 text-md-end">
                <a href="{{ route('company.vacancies.create') }}" class="btn btn-light">
                    <i class="bi bi-plus-circle me-2"></i>Buat Lowongan
                </a>
            </div>
        </div>
    </div>
</div>

<div class="container py-5">
    <!-- Statistics Cards -->
    <div class="row g-4 mb-5">
        <div class="col-md-3 col-sm-6">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <p class="text-muted mb-1 small">Total Lowongan</p>
                            <h3 class="fw-bold mb-0">{{ $stats['total_vacancies'] }}</h3>
                        </div>
                        <div class="bg-primary bg-opacity-10 rounded p-3">
                            <i class="bi bi-briefcase text-primary fs-3"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-md-3 col-sm-6">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <p class="text-muted mb-1 small">Lowongan Aktif</p>
                            <h3 class="fw-bold mb-0 text-success">{{ $stats['active_vacancies'] }}</h3>
                        </div>
                        <div class="bg-success bg-opacity-10 rounded p-3">
                            <i class="bi bi-check-circle text-success fs-3"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-md-3 col-sm-6">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <p class="text-muted mb-1 small">Menunggu Approval</p>
                            <h3 class="fw-bold mb-0 text-warning">{{ $stats['pending_vacancies'] }}</h3>
                        </div>
                        <div class="bg-warning bg-opacity-10 rounded p-3">
                            <i class="bi bi-clock-history text-warning fs-3"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-md-3 col-sm-6">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <p class="text-muted mb-1 small">Total Lamaran</p>
                            <h3 class="fw-bold mb-0 text-info">{{ $stats['total_applications'] }}</h3>
                        </div>
                        <div class="bg-info bg-opacity-10 rounded p-3">
                            <i class="bi bi-file-text text-info fs-3"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <!-- Recent Applications -->
        <div class="col-lg-8 mb-4">
            <div class="card shadow-sm">
                <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center">
                    <h5 class="mb-0 fw-bold">
                        <i class="bi bi-inbox me-2"></i>Lamaran Terbaru
                    </h5>
                    @if($stats['pending_applications'] > 0)
                        <span class="badge bg-danger">{{ $stats['pending_applications'] }} Baru</span>
                    @endif
                </div>
                <div class="card-body p-0">
                    @if($recentApplications->count() > 0)
                        <div class="list-group list-group-flush">
                            @foreach($recentApplications as $application)
                            <a href="{{ route('company.applications.show', $application->id) }}" 
                               class="list-group-item list-group-item-action">
                                <div class="d-flex align-items-start">
                                    <div class="flex-shrink-0 me-3">
                                        @if($application->student->photo)
                                            <img src="{{ Storage::url($application->student->photo) }}" 
                                                 class="rounded-circle" 
                                                 style="width: 50px; height: 50px; object-fit: cover;">
                                        @else
                                            <div class="bg-secondary text-white rounded-circle d-flex align-items-center justify-content-center" 
                                                 style="width: 50px; height: 50px;">
                                                <i class="bi bi-person fs-5"></i>
                                            </div>
                                        @endif
                                    </div>
                                    <div class="flex-grow-1">
                                        <div class="d-flex justify-content-between align-items-start">
                                            <div>
                                                <h6 class="mb-1">{{ $application->full_name }}</h6>
                                                <p class="mb-1 text-muted small">
                                                    <strong>{{ $application->jobVacancy->title }}</strong>
                                                </p>
                                                <p class="mb-0 text-muted small">
                                                    <i class="bi bi-calendar3"></i> 
                                                    {{ $application->created_at->diffForHumans() }}
                                                </p>
                                            </div>
                                            <span class="badge {{ get_status_badge_class($application->status) }}">
                                                {{ $application->status_label }}
                                            </span>
                                        </div>
                                    </div>
                                </div>
                            </a>
                            @endforeach
                        </div>
                        <div class="card-footer bg-white text-center">
                            <a href="{{ route('company.applications') }}" class="btn btn-sm btn-outline-primary">
                                Lihat Semua Lamaran <i class="bi bi-arrow-right"></i>
                            </a>
                        </div>
                    @else
                        <div class="text-center py-5">
                            <i class="bi bi-inbox display-1 text-muted"></i>
                            <p class="mt-3 text-muted">Belum ada lamaran masuk</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Sidebar -->
        <div class="col-lg-4">
            <!-- Company Profile Card -->
            <div class="card shadow-sm mb-4">
                <div class="card-body text-center">
                    @if($company->logo)
                        <img src="{{ Storage::url($company->logo) }}" 
                             alt="{{ $company->name }}" 
                             class="img-fluid rounded mb-3"
                             style="max-height: 120px;">
                    @else
                        <div class="bg-primary text-white rounded d-inline-flex align-items-center justify-content-center mb-3" 
                             style="width: 120px; height: 120px;">
                            <i class="bi bi-building display-4"></i>
                        </div>
                    @endif
                    
                    <h5 class="fw-bold mb-1">{{ $company->name }}</h5>
                    <p class="text-muted small mb-3">{{ $company->industry_sector }}</p>
                    
                    <div class="d-grid">
                        <a href="{{ route('company.profile') }}" class="btn btn-outline-primary">
                            <i class="bi bi-pencil me-2"></i>Edit Profile
                        </a>
                    </div>
                </div>
            </div>

            <!-- Quick Actions -->
            <div class="card shadow-sm">
                <div class="card-body">
                    <h6 class="fw-bold mb-3">
                        <i class="bi bi-lightning-fill me-2"></i>Aksi Cepat
                    </h6>
                    <div class="d-grid gap-2">
                        <a href="{{ route('company.vacancies.create') }}" class="btn btn-primary text-start">
                            <i class="bi bi-plus-circle me-2"></i>Buat Lowongan Baru
                        </a>
                        <a href="{{ route('company.vacancies') }}" class="btn btn-outline-secondary text-start">
                            <i class="bi bi-briefcase me-2"></i>Kelola Lowongan
                        </a>
                        <a href="{{ route('company.applications') }}" class="btn btn-outline-info text-start">
                            <i class="bi bi-inbox me-2"></i>Lihat Semua Lamaran
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection