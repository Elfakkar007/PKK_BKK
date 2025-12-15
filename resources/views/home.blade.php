@extends('layouts.app')

@section('title', 'Beranda')

@section('content')
<!-- Hero Section -->
<section class="hero-section">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-lg-6 mb-4 mb-lg-0">
                <h1 class="display-4 fw-bold mb-4">Bursa Kerja Khusus<br>SMKN 1 Purwosari</h1>
                <p class="lead mb-4">
                    Membantu siswa dan alumni menemukan peluang karir terbaik melalui jaringan perusahaan mitra yang terpercaya.
                </p>
                <div class="d-flex gap-3 flex-wrap">
                    <a href="{{ route('vacancies') }}" class="btn btn-light btn-lg">
                        <i class="bi bi-briefcase me-2"></i>Lihat Lowongan
                    </a>
                    @guest
                        <a href="{{ route('register.student') }}" class="btn btn-outline-light btn-lg">
                            <i class="bi bi-person-plus me-2"></i>Daftar Sekarang
                        </a>
                    @endguest
                </div>
            </div>
            <div class="col-lg-6 text-center">
                @if(file_exists(public_path('images/hero-illustration.png')))
                    <img src="{{ asset('images/hero-illustration.png') }}" alt="Hero" class="img-fluid" style="max-height: 400px;">
                @else
                    <div class="hero-placeholder bg-white bg-opacity-25 rounded-4 d-flex align-items-center justify-content-center" style="height: 400px;">
                        <div class="text-white text-center">
                            <i class="bi bi-briefcase-fill" style="font-size: 100px;"></i>
                            <h3 class="mt-3">BKK SMKN 1 Purwosari</h3>
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>
</section>

<!-- Statistics Section -->
<section class="py-5 bg-light">
    <div class="container">
        <div class="row text-center">
            <div class="col-md-3 col-6 mb-4 mb-md-0">
                <div class="card border-0 h-100">
                    <div class="card-body">
                        <i class="bi bi-building text-primary display-4"></i>
                        <h3 class="mt-3 mb-0">{{ $companiesCount }}</h3>
                        <p class="text-muted mb-0">Perusahaan Mitra</p>
                    </div>
                </div>
            </div>
            <div class="col-md-3 col-6 mb-4 mb-md-0">
                <div class="card border-0 h-100">
                    <div class="card-body">
                        <i class="bi bi-briefcase text-success display-4"></i>
                        <h3 class="mt-3 mb-0">{{ $vacanciesCount }}</h3>
                        <p class="text-muted mb-0">Lowongan Aktif</p>
                    </div>
                </div>
            </div>
            <div class="col-md-3 col-6">
                <div class="card border-0 h-100">
                    <div class="card-body">
                        <i class="bi bi-people text-info display-4"></i>
                        <h3 class="mt-3 mb-0">500+</h3>
                        <p class="text-muted mb-0">Alumni Tersalurkan</p>
                    </div>
                </div>
            </div>
            <div class="col-md-3 col-6">
                <div class="card border-0 h-100">
                    <div class="card-body">
                        <i class="bi bi-star text-warning display-4"></i>
                        <h3 class="mt-3 mb-0">95%</h3>
                        <p class="text-muted mb-0">Tingkat Kepuasan</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Highlights Carousel Section -->
@if($highlights->count() > 0)
<section class="py-5 bg-gradient" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);">
    <div class="container">
        <div class="text-center mb-4 text-white">
            <h2 class="fw-bold">Sorotan BKK</h2>
            <p class="mb-0">Prestasi, pengumuman, dan profil alumni sukses</p>
        </div>
        
        <div id="highlightsCarousel" class="carousel slide" data-bs-ride="carousel" data-bs-interval="5000">
            <div class="carousel-indicators">
                @foreach($highlights as $index => $highlight)
                <button type="button" data-bs-target="#highlightsCarousel" data-bs-slide-to="{{ $index }}" class="{{ $index === 0 ? 'active' : '' }}" aria-current="{{ $index === 0 ? 'true' : 'false' }}" aria-label="Slide {{ $index + 1 }}"></button>
                @endforeach
            </div>
            
            <div class="carousel-inner">
                @foreach($highlights as $index => $highlight)
                <div class="carousel-item {{ $index === 0 ? 'active' : '' }}">
                    <div class="row align-items-center">
                        <div class="col-lg-6 mb-4 mb-lg-0">
                            @if($highlight->featured_image)
                                <img src="{{ Storage::url($highlight->featured_image) }}" 
                                     class="d-block w-100 rounded shadow-lg" 
                                     alt="{{ $highlight->title }}"
                                     style="max-height: 400px; object-fit: cover;">
                            @else
                                <div class="bg-white rounded shadow-lg d-flex align-items-center justify-content-center" style="height: 400px;">
                                    <i class="bi bi-star-fill text-warning" style="font-size: 100px;"></i>
                                </div>
                            @endif
                        </div>
                        <div class="col-lg-6 text-white">
                            <span class="badge bg-warning text-dark mb-3">
                                <i class="bi bi-star-fill me-1"></i>SOROTAN
                            </span>
                            <h3 class="fw-bold mb-3">{{ $highlight->title }}</h3>
                            <p class="lead mb-4">{{ $highlight->excerpt }}</p>
                            <a href="{{ route('information.show', $highlight->slug) }}" class="btn btn-light btn-lg">
                                Baca Selengkapnya <i class="bi bi-arrow-right ms-2"></i>
                            </a>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
            
            @if($highlights->count() > 1)
            <button class="carousel-control-prev" type="button" data-bs-target="#highlightsCarousel" data-bs-slide="prev">
                <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                <span class="visually-hidden">Previous</span>
            </button>
            <button class="carousel-control-next" type="button" data-bs-target="#highlightsCarousel" data-bs-slide="next">
                <span class="carousel-control-next-icon" aria-hidden="true"></span>
                <span class="visually-hidden">Next</span>
            </button>
            @endif
        </div>
    </div>
</section>
@endif

<!-- Latest Vacancies Section -->
@if($activeVacancies->count() > 0)
<section class="py-5">
    <div class="container">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h2 class="fw-bold mb-0">Lowongan Terbaru</h2>
                <p class="text-muted mb-0">Peluang karir terbaik untuk Anda</p>
            </div>
            <a href="{{ route('vacancies') }}" class="btn btn-primary">
                Lihat Semua <i class="bi bi-arrow-right"></i>
            </a>
        </div>
        
        <div class="row g-4">
            @foreach($activeVacancies as $vacancy)
            <div class="col-md-6 col-lg-4">
                <div class="card h-100">
                    <div class="card-body">
                        <div class="d-flex align-items-start mb-3">
                            @if($vacancy->company->logo)
                                <img src="{{ Storage::url($vacancy->company->logo) }}" alt="{{ $vacancy->company->name }}" class="rounded" style="width: 60px; height: 60px; object-fit: cover;">
                            @else
                                <div class="bg-primary text-white rounded d-flex align-items-center justify-content-center" style="width: 60px; height: 60px;">
                                    <i class="bi bi-building fs-3"></i>
                                </div>
                            @endif
                            <div class="ms-3 flex-grow-1">
                                <h5 class="card-title mb-1">{{ $vacancy->title }}</h5>
                                <p class="text-muted small mb-0">{{ $vacancy->company->name }}</p>
                            </div>
                        </div>
                        
                        <div class="mb-3">
                            <span class="badge bg-{{ $vacancy->type === 'internship' ? 'info' : 'success' }} mb-2">
                                {{ get_vacancy_type_label($vacancy->type) }}
                            </span>
                            <p class="mb-2">
                                <i class="bi bi-geo-alt text-muted"></i>
                                <small>{{ $vacancy->location }}</small>
                            </p>
                            <p class="mb-2">
                                <i class="bi bi-calendar text-muted"></i>
                                <small>Deadline: {{ $vacancy->deadline->format('d M Y') }}</small>
                            </p>
                            <p class="mb-0">
                                <i class="bi bi-people text-muted"></i>
                                <small>Kuota: {{ $vacancy->remainingQuota() }} posisi</small>
                            </p>
                        </div>
                        
                        <a href="{{ route('vacancies.show', $vacancy->id) }}" class="btn btn-outline-primary w-100">
                            Lihat Detail
                        </a>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
    </div>
</section>
@endif

<!-- Latest News Section -->
@if($latestNews->count() > 0)
<section class="py-5 bg-light">
    <div class="container">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h2 class="fw-bold mb-0">Berita Terbaru</h2>
                <p class="text-muted mb-0">Informasi dan update terkini dari BKK</p>
            </div>
            <a href="{{ route('information') }}" class="btn btn-primary">
                Lihat Semua <i class="bi bi-arrow-right"></i>
            </a>
        </div>
        
        <div class="row g-4">
            @foreach($latestNews as $post)
            <div class="col-md-4">
                <div class="card h-100">
                    @if($post->featured_image)
                        <img src="{{ Storage::url($post->featured_image) }}" class="card-img-top" alt="{{ $post->title }}" style="height: 200px; object-fit: cover;">
                    @else
                        <div class="card-img-top bg-secondary text-white d-flex align-items-center justify-content-center" style="height: 200px;">
                            <i class="bi bi-newspaper display-1"></i>
                        </div>
                    @endif
                    <div class="card-body d-flex flex-column">
                        <div class="mb-2">
                            <span class="badge bg-primary">{{ $post->category_label }}</span>
                        </div>
                        <h5 class="card-title">{{ $post->title }}</h5>
                        <p class="card-text text-muted small flex-grow-1">
                            {{ Str::limit(strip_tags($post->excerpt ?? $post->content), 100) }}
                        </p>
                        <div class="d-flex justify-content-between align-items-center mt-3">
                            <small class="text-muted">
                                <i class="bi bi-calendar3"></i>
                                {{ $post->published_at->format('d M Y') }}
                            </small>
                            <a href="{{ route('information.show', $post->slug) }}" class="btn btn-sm btn-outline-primary">
                                Baca <i class="bi bi-arrow-right"></i>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
    </div>
</section>
@endif

<!-- Call to Action Section -->
<section class="py-5 bg-primary text-white">
    <div class="container text-center">
        <h2 class="fw-bold mb-3">Siap Memulai Karir Anda?</h2>
        <p class="lead mb-4">Bergabunglah dengan ribuan alumni yang telah berhasil berkarir melalui BKK SMKN 1 Purwosari</p>
        @guest
            <div class="d-flex gap-3 justify-content-center flex-wrap">
                <a href="{{ route('register.student') }}" class="btn btn-light btn-lg">
                    <i class="bi bi-person-plus me-2"></i>Daftar Siswa/Alumni
                </a>
                <a href="{{ route('register.company') }}" class="btn btn-outline-light btn-lg">
                    <i class="bi bi-building-add me-2"></i>Daftar Perusahaan
                </a>
            </div>
        @else
            <a href="{{ route('vacancies') }}" class="btn btn-light btn-lg">
                <i class="bi bi-briefcase me-2"></i>Jelajahi Lowongan
            </a>
        @endguest
    </div>
</section>

@push('styles')
<style>
.carousel-control-prev-icon,
.carousel-control-next-icon {
    background-color: rgba(0,0,0,0.5);
    border-radius: 50%;
    padding: 20px;
}

.carousel-indicators [data-bs-target] {
    width: 12px;
    height: 12px;
    border-radius: 50%;
}
</style>
@endpush
@endsection