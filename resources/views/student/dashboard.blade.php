@extends('layouts.app')

@section('title', 'Dashboard Siswa - BKK SMKN 1 Purwosari')

@section('content')
<div class="bg-primary text-white py-4">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-md-8">
                <h2 class="fw-bold mb-2">Selamat Datang, {{ $student->full_name }}!</h2>
                <p class="mb-0">Kelola lamaran dan profile Anda di sini</p>
            </div>
            <div class="col-md-4 text-md-end">
                <a href="{{ route('vacancies') }}" class="btn btn-light">
                    <i class="bi bi-search me-2"></i>Cari Lowongan
                </a>
            </div>
        </div>
    </div>
</div>

<div class="container py-5">
    <!-- Statistics Cards -->
    <div class="row g-4 mb-5">
        <div class="col-md-3 col-6">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body text-center">
                    <div class="bg-primary bg-opacity-10 rounded-circle d-inline-flex align-items-center justify-content-center mb-3" 
                         style="width: 60px; height: 60px;">
                        <i class="bi bi-file-text text-primary fs-3"></i>
                    </div>
                    <h3 class="fw-bold mb-1">{{ $stats['total'] }}</h3>
                    <p class="text-muted mb-0 small">Total Lamaran</p>
                </div>
            </div>
        </div>
        <div class="col-md-3 col-6">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body text-center">
                    <div class="bg-warning bg-opacity-10 rounded-circle d-inline-flex align-items-center justify-content-center mb-3" 
                         style="width: 60px; height: 60px;">
                        <i class="bi bi-clock-history text-warning fs-3"></i>
                    </div>
                    <h3 class="fw-bold mb-1">{{ $stats['pending'] }}</h3>
                    <p class="text-muted mb-0 small">Menunggu</p>
                </div>
            </div>
        </div>
        <div class="col-md-3 col-6">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body text-center">
                    <div class="bg-info bg-opacity-10 rounded-circle d-inline-flex align-items-center justify-content-center mb-3" 
                         style="width: 60px; height: 60px;">
                        <i class="bi bi-eye text-info fs-3"></i>
                    </div>
                    <h3 class="fw-bold mb-1">{{ $stats['reviewed'] }}</h3>
                    <p class="text-muted mb-0 small">Ditinjau</p>
                </div>
            </div>
        </div>
        <div class="col-md-3 col-6">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body text-center">
                    <div class="bg-success bg-opacity-10 rounded-circle d-inline-flex align-items-center justify-content-center mb-3" 
                         style="width: 60px; height: 60px;">
                        <i class="bi bi-check-circle text-success fs-3"></i>
                    </div>
                    <h3 class="fw-bold mb-1">{{ $stats['accepted'] }}</h3>
                    <p class="text-muted mb-0 small">Diterima</p>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-8 mb-4">
            <!-- Applications List -->
            <div class="card shadow-sm">
                <div class="card-header bg-white py-3">
                    <h5 class="mb-0 fw-bold">
                        <i class="bi bi-list-ul me-2"></i>Riwayat Lamaran
                    </h5>
                </div>
                <div class="card-body p-0">
                    @if($applications->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-hover mb-0">
                                <thead class="table-light">
                                    <tr>
                                        <th>Posisi</th>
                                        <th>Perusahaan</th>
                                        <th>Status</th>
                                        <th>Tanggal</th>
                                        <th></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($applications as $application)
                                    <tr>
                                        <td>
                                            <strong>{{ $application->jobVacancy->title }}</strong><br>
                                            <small class="text-muted">
                                                <span class="badge badge-sm bg-{{ $application->jobVacancy->type === 'internship' ? 'info' : 'success' }}">
                                                    {{ get_vacancy_type_label($application->jobVacancy->type) }}
                                                </span>
                                            </small>
                                        </td>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                @if($application->jobVacancy->company->logo)
                                                    <img src="{{ Storage::url($application->jobVacancy->company->logo) }}" 
                                                         alt="{{ $application->jobVacancy->company->name }}" 
                                                         class="rounded me-2" 
                                                         style="width: 30px; height: 30px; object-fit: cover;">
                                                @endif
                                                <span>{{ Str::limit($application->jobVacancy->company->name, 20) }}</span>
                                            </div>
                                        </td>
                                        <td>
                                            <span class="badge {{ get_status_badge_class($application->status) }}">
                                                {{ $application->status_label }}
                                            </span>
                                        </td>
                                        <td>
                                            <small>{{ $application->created_at->format('d M Y') }}</small>
                                        </td>
                                        <td>
                                            <a href="{{ route('student.applications.show', $application->id) }}" 
                                               class="btn btn-sm btn-outline-primary">
                                                <i class="bi bi-eye"></i>
                                            </a>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        <div class="card-footer bg-white">
                            {{ $applications->links() }}
                        </div>
                    @else
                        <div class="text-center py-5">
                            <i class="bi bi-inbox display-1 text-muted"></i>
                            <p class="mt-3 text-muted">Anda belum memiliki lamaran</p>
                            <a href="{{ route('vacancies') }}" class="btn btn-primary">
                                <i class="bi bi-search me-2"></i>Cari Lowongan
                            </a>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <div class="col-lg-4">
            <!-- Profile Card -->
            <div class="card shadow-sm mb-4">
                <div class="card-body text-center">
                    @if($student->photo)
                        <img src="{{ Storage::url($student->photo) }}" 
                             alt="{{ $student->full_name }}" 
                             class="rounded-circle mb-3" 
                             style="width: 100px; height: 100px; object-fit: cover;">
                    @else
                        <div class="bg-primary text-white rounded-circle d-inline-flex align-items-center justify-content-center mb-3" 
                             style="width: 100px; height: 100px;">
                            <i class="bi bi-person fs-1"></i>
                        </div>
                    @endif
                    
                    <h5 class="fw-bold mb-1">{{ $student->full_name }}</h5>
                    <p class="text-muted small mb-1">{{ $student->major }}</p>
                    <p class="text-muted small mb-3">
                        <span class="badge bg-secondary">
                            {{ $student->status === 'student' ? 'Siswa Aktif' : 'Alumni' }}
                        </span>
                    </p>
                    
                    <div class="d-grid">
                        <a href="{{ route('student.profile') }}" class="btn btn-outline-primary">
                            <i class="bi bi-pencil me-2"></i>Edit Profile
                        </a>
                    </div>
                </div>
            </div>

            <!-- CV Status -->
            <div class="card shadow-sm mb-4">
                <div class="card-body">
                    <h6 class="fw-bold mb-3">
                        <i class="bi bi-file-earmark-pdf me-2"></i>Status CV
                    </h6>
                    @if($student->cv_path)
                        <div class="alert alert-success mb-3">
                            <i class="bi bi-check-circle me-2"></i>CV sudah diunggah
                        </div>
                        <div class="d-grid gap-2">
                            <a href="{{ Storage::url($student->cv_path) }}" 
                               target="_blank" 
                               class="btn btn-sm btn-outline-primary">
                                <i class="bi bi-eye me-2"></i>Lihat CV
                            </a>
                            <a href="{{ route('student.profile') }}" class="btn btn-sm btn-outline-secondary">
                                <i class="bi bi-upload me-2"></i>Update CV
                            </a>
                        </div>
                    @else
                        <div class="alert alert-warning mb-3">
                            <i class="bi bi-exclamation-triangle me-2"></i>CV belum diunggah
                        </div>
                        <a href="{{ route('student.profile') }}" class="btn btn-primary w-100">
                            <i class="bi bi-upload me-2"></i>Upload CV
                        </a>
                    @endif
                </div>
            </div>

            <!-- Quick Links -->
            <div class="card shadow-sm">
                <div class="card-body">
                    <h6 class="fw-bold mb-3">
                        <i class="bi bi-link-45deg me-2"></i>Menu Cepat
                    </h6>
                    <div class="d-grid gap-2">
                        <a href="{{ route('vacancies') }}" class="btn btn-outline-primary text-start">
                            <i class="bi bi-search me-2"></i>Cari Lowongan
                        </a>
                        <a href="{{ route('companies') }}" class="btn btn-outline-secondary text-start">
                            <i class="bi bi-building me-2"></i>Perusahaan Mitra
                        </a>
                        <a href="{{ route('information') }}" class="btn btn-outline-info text-start">
                            <i class="bi bi-info-circle me-2"></i>Informasi & Panduan
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection