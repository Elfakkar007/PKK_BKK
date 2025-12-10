@extends('layouts.app')

@section('title', 'Lowongan Pekerjaan - BKK SMKN 1 Purwosari')

@section('content')
<!-- Page Header -->
<section class="bg-light py-5">
    <div class="container">
        <div class="row">
            <div class="col-lg-8">
                <h1 class="fw-bold mb-3">Lowongan Pekerjaan</h1>
                <p class="lead text-muted mb-0">
                    Temukan peluang karir terbaik dari perusahaan mitra kami
                </p>
            </div>
        </div>
    </div>
</section>

<!-- Filter & Search Section -->
<section class="py-4 bg-white border-bottom">
    <div class="container">
        <form method="GET" action="{{ route('vacancies') }}" id="filterForm">
            <div class="row g-3 align-items-center">
                <div class="col-md-3">
                    <select name="type" class="form-select" onchange="document.getElementById('filterForm').submit()">
                        <option value="all" {{ $type == 'all' ? 'selected' : '' }}>Semua Tipe</option>
                        <option value="internship" {{ $type == 'internship' ? 'selected' : '' }}>Magang</option>
                        <option value="fulltime" {{ $type == 'fulltime' ? 'selected' : '' }}>Full Time</option>
                    </select>
                </div>
                <div class="col-md-4">
                    <input type="text" name="search" class="form-control" placeholder="Cari lowongan..." value="{{ request('search') }}">
                </div>
                <div class="col-md-3">
                    <input type="text" name="location" class="form-control" placeholder="Lokasi..." value="{{ request('location') }}">
                </div>
                <div class="col-md-2">
                    <button type="submit" class="btn btn-primary w-100">
                        <i class="bi bi-search me-1"></i>Cari
                    </button>
                </div>
            </div>
        </form>
    </div>
</section>

<!-- Vacancies List -->
<section class="py-5">
    <div class="container">
        <!-- Results Info -->
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h5 class="mb-0">
                Menampilkan {{ $vacancies->total() }} lowongan
                @if($type !== 'all')
                    <span class="badge bg-primary">{{ get_vacancy_type_label($type) }}</span>
                @endif
            </h5>
            
            @if(request('search') || request('location'))
            <a href="{{ route('vacancies') }}" class="btn btn-sm btn-outline-secondary">
                <i class="bi bi-x-circle me-1"></i>Reset Filter
            </a>
            @endif
        </div>

        @if($vacancies->count() > 0)
        <div class="row g-4">
            @foreach($vacancies as $vacancy)
            <div class="col-md-6 col-lg-4">
                <div class="card h-100 position-relative">
                    <!-- Vacancy Type Badge -->
                    <div class="position-absolute top-0 end-0 m-3">
                        <span class="badge bg-{{ $vacancy->type === 'internship' ? 'info' : 'success' }}">
                            {{ get_vacancy_type_label($vacancy->type) }}
                        </span>
                    </div>

                    <div class="card-body d-flex flex-column">
                        <!-- Company Logo & Name -->
                        <div class="d-flex align-items-start mb-3">
                            @if($vacancy->company->logo)
                                <img src="{{ Storage::url($vacancy->company->logo) }}" 
                                     alt="{{ $vacancy->company->name }}" 
                                     class="rounded" 
                                     style="width: 60px; height: 60px; object-fit: cover;">
                            @else
                                <div class="bg-primary text-white rounded d-flex align-items-center justify-content-center" 
                                     style="width: 60px; height: 60px;">
                                    <i class="bi bi-building fs-3"></i>
                                </div>
                            @endif
                            <div class="ms-3 flex-grow-1">
                                <h5 class="card-title mb-1">{{ $vacancy->title }}</h5>
                                <p class="text-muted small mb-0">{{ $vacancy->company->name }}</p>
                            </div>
                        </div>

                        <!-- Vacancy Details -->
                        <div class="mb-3 flex-grow-1">
                            <p class="mb-2">
                                <i class="bi bi-geo-alt text-primary"></i>
                                <span class="ms-2">{{ $vacancy->location }}</span>
                            </p>
                            
                            @if($vacancy->salary_min || $vacancy->salary_max)
                            <p class="mb-2">
                                <i class="bi bi-cash-stack text-success"></i>
                                <span class="ms-2">
                                    @if($vacancy->salary_min && $vacancy->salary_max)
                                        {{ format_currency($vacancy->salary_min) }} - {{ format_currency($vacancy->salary_max) }}
                                    @elseif($vacancy->salary_min)
                                        Mulai dari {{ format_currency($vacancy->salary_min) }}
                                    @else
                                        Hingga {{ format_currency($vacancy->salary_max) }}
                                    @endif
                                </span>
                            </p>
                            @endif
                            
                            <p class="mb-2">
                                <i class="bi bi-calendar-event text-danger"></i>
                                <span class="ms-2">Deadline: {{ format_date_indonesian($vacancy->deadline) }}</span>
                            </p>
                            
                            <p class="mb-0">
                                <i class="bi bi-people text-info"></i>
                                <span class="ms-2">
                                    Kuota: {{ $vacancy->remainingQuota() }} dari {{ $vacancy->quota }} posisi
                                </span>
                            </p>
                        </div>

                        <!-- Deadline Warning -->
                        @if(is_deadline_approaching($vacancy->deadline))
                        <div class="alert alert-warning py-2 mb-3">
                            <small><i class="bi bi-exclamation-triangle me-1"></i>Segera berakhir!</small>
                        </div>
                        @endif

                        <!-- Action Button -->
                        <div class="d-grid">
                            <a href="{{ route('vacancies.show', $vacancy->id) }}" class="btn btn-primary">
                                <i class="bi bi-eye me-1"></i>Lihat Detail
                            </a>
                        </div>
                    </div>

                    <!-- Card Footer -->
                    <div class="card-footer bg-light text-muted small">
                        <i class="bi bi-clock"></i>
                        Diposting {{ time_ago_indonesian($vacancy->created_at) }}
                    </div>
                </div>
            </div>
            @endforeach
        </div>

        <!-- Pagination -->
        <div class="mt-5">
            {{ $vacancies->links() }}
        </div>
        @else
        <!-- Empty State -->
        <div class="text-center py-5">
            <i class="bi bi-inbox display-1 text-muted"></i>
            <h4 class="mt-3 mb-2">Tidak Ada Lowongan</h4>
            <p class="text-muted">
                @if(request('search') || request('location'))
                    Tidak ditemukan lowongan yang sesuai dengan pencarian Anda.
                @else
                    Belum ada lowongan pekerjaan tersedia saat ini.
                @endif
            </p>
            @if(request('search') || request('location'))
            <a href="{{ route('vacancies') }}" class="btn btn-primary mt-3">
                <i class="bi bi-arrow-left me-2"></i>Lihat Semua Lowongan
            </a>
            @endif
        </div>
        @endif
    </div>
</section>

<!-- CTA Section -->
@guest
<section class="py-5 bg-light">
    <div class="container text-center">
        <h3 class="fw-bold mb-3">Tertarik dengan lowongan ini?</h3>
        <p class="lead text-muted mb-4">
            Daftar sekarang untuk mulai melamar pekerjaan
        </p>
        <a href="{{ route('register.student') }}" class="btn btn-primary btn-lg">
            <i class="bi bi-person-plus me-2"></i>Daftar Sekarang
        </a>
    </div>
</section>
@endguest
@endsection