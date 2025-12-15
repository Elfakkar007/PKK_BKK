@extends('layouts.admin')

@section('title', 'Dashboard Admin')
@section('page-title', 'Dashboard')

@section('content')
<!-- Statistics Row -->
<div class="row g-4 mb-4">
    <div class="col-xl-3 col-md-6">
        <div class="card stat-card border-start border-primary border-4">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <p class="text-muted mb-1 small">Total Users</p>
                        <h3 class="mb-0 fw-bold">{{ $stats['total_users'] }}</h3>
                        @if($stats['pending_users'] > 0)
                            <small class="text-warning">
                                <i class="bi bi-clock"></i> {{ $stats['pending_users'] }} pending
                            </small>
                        @endif
                    </div>
                    <div class="bg-primary bg-opacity-10 rounded-circle p-3">
                        <i class="bi bi-people text-primary fs-2"></i>
                    </div>
                </div>
            </div>
            <div class="card-footer bg-white">
                <a href="{{ route('admin.users.index') }}" class="text-decoration-none small">
                    Kelola Users <i class="bi bi-arrow-right"></i>
                </a>
            </div>
        </div>
    </div>

    <div class="col-xl-3 col-md-6">
        <div class="card stat-card border-start border-success border-4">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <p class="text-muted mb-1 small">Lowongan Aktif</p>
                        <h3 class="mb-0 fw-bold text-success">{{ $stats['active_vacancies'] }}</h3>
                        @if($stats['pending_vacancies'] > 0)
                            <small class="text-warning">
                                <i class="bi bi-clock"></i> {{ $stats['pending_vacancies'] }} pending
                            </small>
                        @endif
                    </div>
                    <div class="bg-success bg-opacity-10 rounded-circle p-3">
                        <i class="bi bi-briefcase text-success fs-2"></i>
                    </div>
                </div>
            </div>
            <div class="card-footer bg-white">
                <a href="{{ route('admin.vacancies.index') }}" class="text-decoration-none small">
                    Kelola Lowongan <i class="bi bi-arrow-right"></i>
                </a>
            </div>
        </div>
    </div>

    <div class="col-xl-3 col-md-6">
        <div class="card stat-card border-start border-info border-4">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <p class="text-muted mb-1 small">Total Lamaran</p>
                        <h3 class="mb-0 fw-bold text-info">{{ $stats['total_applications'] }}</h3>
                        <small class="text-muted">
                            {{ $stats['pending_applications'] }} pending
                        </small>
                    </div>
                    <div class="bg-info bg-opacity-10 rounded-circle p-3">
                        <i class="bi bi-file-text text-info fs-2"></i>
                    </div>
                </div>
            </div>
            <div class="card-footer bg-white">
                <small class="text-muted">{{ $stats['accepted_applications'] }} diterima</small>
            </div>
        </div>
    </div>

    <div class="col-xl-3 col-md-6">
        <div class="card stat-card border-start border-warning border-4">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <p class="text-muted mb-1 small">Perusahaan</p>
                        <h3 class="mb-0 fw-bold text-warning">{{ $stats['companies'] }}</h3>
                        <small class="text-muted">Mitra aktif</small>
                    </div>
                    <div class="bg-warning bg-opacity-10 rounded-circle p-3">
                        <i class="bi bi-building text-warning fs-2"></i>
                    </div>
                </div>
            </div>
            <div class="card-footer bg-white">
                <a href="{{ route('admin.companies.index') }}" class="text-decoration-none small">
                    Lihat Perusahaan <i class="bi bi-arrow-right"></i>
                </a>
            </div>
        </div>
    </div>
</div>

<!-- Quick Actions -->
<div class="row g-4 mb-4">
    <div class="col-lg-6">
        <div class="card">
            <div class="card-header bg-white">
                <h5 class="mb-0">
                    <i class="bi bi-lightning-fill text-warning me-2"></i>
                    Aksi Cepat
                </h5>
            </div>
            <div class="card-body">
                <div class="row g-3">
                    <div class="col-6">
                        <a href="{{ route('admin.users.pending') }}" class="btn btn-outline-primary w-100">
                            <i class="bi bi-person-check me-2"></i>
                            Approve Users
                            @if($stats['pending_users'] > 0)
                                <span class="badge bg-danger ms-1">{{ $stats['pending_users'] }}</span>
                            @endif
                        </a>
                    </div>
                    <div class="col-6">
                        <a href="{{ route('admin.vacancies.pending') }}" class="btn btn-outline-success w-100">
                            <i class="bi bi-briefcase-fill me-2"></i>
                            Approve Lowongan
                            @if($stats['pending_vacancies'] > 0)
                                <span class="badge bg-warning ms-1">{{ $stats['pending_vacancies'] }}</span>
                            @endif
                        </a>
                    </div>
                    <div class="col-6">
                        <a href="{{ route('admin.posts.create') }}" class="btn btn-outline-info w-100">
                            <i class="bi bi-plus-circle me-2"></i>
                            Buat Postingan
                        </a>
                    </div>
                    <div class="col-6">
                        <a href="{{ route('admin.settings.index') }}" class="btn btn-outline-secondary w-100">
                            <i class="bi bi-gear me-2"></i>
                            Settings
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-lg-6">
        <div class="card">
            <div class="card-header bg-white">
                <h5 class="mb-0">
                    <i class="bi bi-bar-chart-fill text-primary me-2"></i>
                    Statistik Konten
                </h5>
            </div>
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center mb-3 pb-3 border-bottom">
                    <div>
                        <h6 class="mb-1">Postingan Published</h6>
                        <small class="text-muted">Berita, panduan & dokumentasi</small>
                    </div>
                    <h4 class="mb-0 text-primary">{{ $stats['published_posts'] }}</h4>
                </div>
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h6 class="mb-1">Draft Postingan</h6>
                        <small class="text-muted">Belum dipublikasikan</small>
                    </div>
                    <h4 class="mb-0 text-muted">{{ $stats['draft_posts'] }}</h4>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row g-4">
    <!-- Pending Users -->
    @if($recentUsers->count() > 0)
    <div class="col-lg-4">
        <div class="card">
            <div class="card-header bg-white d-flex justify-content-between align-items-center">
                <h6 class="mb-0 fw-bold">
                    <i class="bi bi-person-plus me-2"></i>User Baru
                </h6>
                <span class="badge bg-warning">{{ $recentUsers->count() }}</span>
            </div>
            <div class="card-body p-0">
                <div class="list-group list-group-flush">
                    @foreach($recentUsers as $user)
                    <div class="list-group-item">
                        <div class="d-flex justify-content-between align-items-start">
                            <div>
                                <h6 class="mb-1">
                                    @if($user->student)
                                        {{ $user->student->full_name }}
                                    @elseif($user->company)
                                        {{ $user->company->name }}
                                    @endif
                                </h6>
                                <small class="text-muted">
                                    <i class="bi bi-envelope"></i> {{ $user->email }}<br>
                                    <span class="badge bg-secondary">{{ get_role_label($user->role) }}</span>
                                </small>
                            </div>
                        </div>
                        <div class="mt-2">
                            <a href="{{ route('admin.users.index') }}" class="btn btn-sm btn-outline-primary">
                                Review
                            </a>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
    @endif

    <!-- Pending Vacancies -->
    @if($recentVacancies->count() > 0)
    <div class="col-lg-4">
        <div class="card">
            <div class="card-header bg-white d-flex justify-content-between align-items-center">
                <h6 class="mb-0 fw-bold">
                    <i class="bi bi-briefcase me-2"></i>Lowongan Baru
                </h6>
                <span class="badge bg-warning">{{ $recentVacancies->count() }}</span>
            </div>
            <div class="card-body p-0">
                <div class="list-group list-group-flush">
                    @foreach($recentVacancies as $vacancy)
                    <div class="list-group-item">
                        <h6 class="mb-1">{{ $vacancy->title }}</h6>
                        <small class="text-muted">
                            {{ $vacancy->company->name }}<br>
                            <span class="badge bg-{{ $vacancy->type === 'internship' ? 'info' : 'success' }}">
                                {{ get_vacancy_type_label($vacancy->type) }}
                            </span>
                        </small>
                        <div class="mt-2">
                            <a href="{{ route('admin.vacancies.show', $vacancy->id) }}" class="btn btn-sm btn-outline-primary">
                                Review
                            </a>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
    @endif

    <!-- Recent Applications -->
    <div class="col-lg-4">
        <div class="card">
            <div class="card-header bg-white">
                <h6 class="mb-0 fw-bold">
                    <i class="bi bi-file-text me-2"></i>Lamaran Terbaru
                </h6>
            </div>
            <div class="card-body p-0">
                @if($recentApplications->count() > 0)
                    <div class="list-group list-group-flush">
                        @foreach($recentApplications as $application)
                        <div class="list-group-item">
                            <h6 class="mb-1">{{ $application->student->full_name }}</h6>
                            <small class="text-muted">
                                {{ $application->jobVacancy->title }}<br>
                                <span class="text-muted">{{ $application->jobVacancy->company->name }}</span>
                            </small>
                            <div class="mt-2">
                                <span class="badge {{ get_status_badge_class($application->status) }}">
                                    {{ $application->status_label }}
                                </span>
                            </div>
                        </div>
                        @endforeach
                    </div>
                @else
                    <div class="text-center py-4">
                        <i class="bi bi-inbox text-muted fs-1"></i>
                        <p class="text-muted mt-2 mb-0">Belum ada lamaran</p>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>

<!-- Monthly Statistics Chart Placeholder -->
@if(!empty($monthlyStats))
<div class="row mt-4">
    <div class="col-12">
        <div class="card">
            <div class="card-header bg-white">
                <h5 class="mb-0">
                    <i class="bi bi-graph-up text-success me-2"></i>
                    Statistik 6 Bulan Terakhir
                </h5>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-sm">
                        <thead>
                            <tr>
                                <th>Bulan</th>
                                <th class="text-center">Users Baru</th>
                                <th class="text-center">Lowongan</th>
                                <th class="text-center">Lamaran</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($monthlyStats as $month => $data)
                            <tr>
                                <td>{{ $month }}</td>
                                <td class="text-center">
                                    <span class="badge bg-primary">{{ $data['users'] }}</span>
                                </td>
                                <td class="text-center">
                                    <span class="badge bg-success">{{ $data['vacancies'] }}</span>
                                </td>
                                <td class="text-center">
                                    <span class="badge bg-info">{{ $data['applications'] }}</span>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endif
@endsection