@extends('layouts.app')

@section('title', $vacancy->title . ' - BKK SMKN 1 Purwosari')

@section('content')
<div class="container py-5">
    <div class="row">
        <!-- Main Content -->
        <div class="col-lg-8 mb-4">
            <!-- Vacancy Header -->
            <div class="card shadow-sm mb-4">
                <div class="card-body p-4">
                    <div class="d-flex align-items-start mb-4">
                        @if($vacancy->company->logo)
                            <img src="{{ Storage::url($vacancy->company->logo) }}" 
                                 alt="{{ $vacancy->company->name }}" 
                                 class="rounded border" 
                                 style="width: 80px; height: 80px; object-fit: cover;">
                        @else
                            <div class="bg-primary text-white rounded d-flex align-items-center justify-content-center" 
                                 style="width: 80px; height: 80px;">
                                <i class="bi bi-building fs-1"></i>
                            </div>
                        @endif
                        <div class="ms-3 flex-grow-1">
                            <h1 class="h3 fw-bold mb-2">{{ $vacancy->title }}</h1>
                            <p class="text-muted mb-2">
                                <a href="{{ route('companies.show', $vacancy->company->id) }}" class="text-decoration-none">
                                    {{ $vacancy->company->name }}
                                </a>
                            </p>
                            <span class="badge bg-{{ $vacancy->type === 'internship' ? 'info' : 'success' }}">
                                {{ get_vacancy_type_label($vacancy->type) }}
                            </span>
                        </div>
                    </div>

                    <!-- Quick Info -->
                    <div class="row g-3 mb-4">
                        <div class="col-md-6">
                            <div class="d-flex align-items-center">
                                <div class="bg-light rounded p-3 me-3">
                                    <i class="bi bi-geo-alt text-primary fs-4"></i>
                                </div>
                                <div>
                                    <small class="text-muted d-block">Lokasi</small>
                                    <strong>{{ $vacancy->location }}</strong>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="d-flex align-items-center">
                                <div class="bg-light rounded p-3 me-3">
                                    <i class="bi bi-calendar-event text-danger fs-4"></i>
                                </div>
                                <div>
                                    <small class="text-muted d-block">Deadline</small>
                                    <strong>{{ format_date_indonesian($vacancy->deadline) }}</strong>
                                </div>
                            </div>
                        </div>
                        @if($vacancy->salary_min || $vacancy->salary_max)
                        <div class="col-md-6">
                            <div class="d-flex align-items-center">
                                <div class="bg-light rounded p-3 me-3">
                                    <i class="bi bi-cash-stack text-success fs-4"></i>
                                </div>
                                <div>
                                    <small class="text-muted d-block">Gaji</small>
                                    <strong>
                                        @if($vacancy->salary_min && $vacancy->salary_max)
                                            {{ format_currency($vacancy->salary_min) }} - {{ format_currency($vacancy->salary_max) }}
                                        @elseif($vacancy->salary_min)
                                            Mulai dari {{ format_currency($vacancy->salary_min) }}
                                        @else
                                            Hingga {{ format_currency($vacancy->salary_max) }}
                                        @endif
                                    </strong>
                                </div>
                            </div>
                        </div>
                        @endif
                        <div class="col-md-6">
                            <div class="d-flex align-items-center">
                                <div class="bg-light rounded p-3 me-3">
                                    <i class="bi bi-people text-info fs-4"></i>
                                </div>
                                <div>
                                    <small class="text-muted d-block">Kuota</small>
                                    <strong>{{ $vacancy->remainingQuota() }} dari {{ $vacancy->quota }} posisi</strong>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Apply Button -->
                    @auth
                        @if(Auth::user()->isStudent())
                            @if($vacancy->isFull())
                                <button class="btn btn-secondary w-100 py-3" disabled>
                                    <i class="bi bi-x-circle me-2"></i>Kuota Penuh
                                </button>
                            @elseif($vacancy->isExpired())
                                <button class="btn btn-secondary w-100 py-3" disabled>
                                    <i class="bi bi-x-circle me-2"></i>Lowongan Telah Ditutup
                                </button>
                            @else
                                <a href="{{ route('student.apply.form', $vacancy->id) }}" class="btn btn-primary w-100 py-3">
                                    <i class="bi bi-send me-2"></i>Lamar Sekarang
                                </a>
                            @endif
                        @endif
                    @else
                        <a href="{{ route('login') }}" class="btn btn-primary w-100 py-3">
                            <i class="bi bi-box-arrow-in-right me-2"></i>Login untuk Melamar
                        </a>
                    @endauth
                </div>
            </div>

            <!-- Job Description -->
            <div class="card shadow-sm mb-4">
                <div class="card-body p-4">
                    <h4 class="fw-bold mb-3">
                        <i class="bi bi-file-text me-2"></i>Deskripsi Pekerjaan
                    </h4>
                    <div class="text-justify">
                        {!! nl2br(e($vacancy->description)) !!}
                    </div>
                </div>
            </div>

            <!-- Requirements -->
            <div class="card shadow-sm">
                <div class="card-body p-4">
                    <h4 class="fw-bold mb-3">
                        <i class="bi bi-list-check me-2"></i>Persyaratan
                    </h4>
                    <div class="text-justify">
                        {!! nl2br(e($vacancy->requirements)) !!}
                    </div>
                </div>
            </div>
        </div>

        <!-- Sidebar -->
        <div class="col-lg-4">
            <!-- Company Info -->
            <div class="card shadow-sm mb-4">
                <div class="card-body p-4">
                    <h5 class="fw-bold mb-3">
                        <i class="bi bi-building me-2"></i>Tentang Perusahaan
                    </h5>
                    
                    @if($vacancy->company->logo)
                        <img src="{{ Storage::url($vacancy->company->logo) }}" 
                             alt="{{ $vacancy->company->name }}" 
                             class="img-fluid rounded mb-3">
                    @endif
                    
                    <h6 class="fw-bold">{{ $vacancy->company->name }}</h6>
                    <p class="text-muted small mb-2">
                        <i class="bi bi-tag me-1"></i>{{ $vacancy->company->industry_sector }}
                    </p>
                    
                    @if($vacancy->company->description)
                        <p class="small">{{ Str::limit($vacancy->company->description, 150) }}</p>
                    @endif
                    
                    <a href="{{ route('companies.show', $vacancy->company->id) }}" class="btn btn-outline-primary w-100">
                        Lihat Profil Lengkap
                    </a>
                </div>
            </div>

            <!-- Deadline Alert -->
            @if(is_deadline_approaching($vacancy->deadline))
            <div class="alert alert-warning">
                <i class="bi bi-exclamation-triangle me-2"></i>
                <strong>Segera Berakhir!</strong><br>
                Lowongan ini akan ditutup dalam beberapa hari.
            </div>
            @endif

            <!-- Share Section -->
            <div class="card shadow-sm">
                <div class="card-body p-4">
                    <h5 class="fw-bold mb-3">
                        <i class="bi bi-share me-2"></i>Bagikan
                    </h5>
                    <div class="d-grid gap-2">
                        <a href="https://wa.me/?text={{ urlencode($vacancy->title . ' - ' . route('vacancies.show', $vacancy->id)) }}" 
                           target="_blank" 
                           class="btn btn-success">
                            <i class="bi bi-whatsapp me-2"></i>WhatsApp
                        </a>
                        <a href="https://www.facebook.com/sharer/sharer.php?u={{ route('vacancies.show', $vacancy->id) }}" 
                           target="_blank" 
                           class="btn btn-primary">
                            <i class="bi bi-facebook me-2"></i>Facebook
                        </a>
                        <a href="https://twitter.com/intent/tweet?url={{ route('vacancies.show', $vacancy->id) }}&text={{ urlencode($vacancy->title) }}" 
                           target="_blank" 
                           class="btn btn-info text-white">
                            <i class="bi bi-twitter me-2"></i>Twitter
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Related Vacancies -->
    @if($relatedVacancies->count() > 0)
    <section class="mt-5">
        <h4 class="fw-bold mb-4">Lowongan Lainnya dari {{ $vacancy->company->name }}</h4>
        <div class="row g-4">
            @foreach($relatedVacancies as $related)
            <div class="col-md-4">
                <div class="card h-100">
                    <div class="card-body">
                        <span class="badge bg-{{ $related->type === 'internship' ? 'info' : 'success' }} mb-2">
                            {{ get_vacancy_type_label($related->type) }}
                        </span>
                        <h5 class="card-title">{{ $related->title }}</h5>
                        <p class="card-text text-muted small">
                            <i class="bi bi-geo-alt"></i> {{ $related->location }}<br>
                            <i class="bi bi-calendar-event"></i> {{ format_date_indonesian($related->deadline) }}
                        </p>
                        <a href="{{ route('vacancies.show', $related->id) }}" class="btn btn-outline-primary btn-sm w-100">
                            Lihat Detail
                        </a>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
    </section>
    @endif
</div>
@endsection