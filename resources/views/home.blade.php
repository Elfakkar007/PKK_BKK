@extends('layouts.app')

@section('title', 'Beranda')

@section('content')
<!-- Hero Section dengan Background Image -->
<section class="hero-section position-relative" style="min-height: 600px;">
    <!-- Background Image dengan Overlay -->
    <div class="hero-background position-absolute w-100 h-100 top-0 start-0" 
         style="background: linear-gradient(rgba(0, 0, 0, 0.6), rgba(0, 0, 0, 0.6)), 
                url('https://images.unsplash.com/photo-1552581234-26160f608093?w=1920&q=80&fm=jpg&fit=crop') center center/cover no-repeat;
                z-index: 0;">
    </div>
    
    <!-- Fallback gradient jika gambar tidak load -->
    <div class="hero-fallback position-absolute w-100 h-100 top-0 start-0"
         style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
                z-index: -1;">
    </div>
    
    <!-- Content -->
    <div class="container position-relative" style="z-index: 1;">
        <div class="row min-vh-75 align-items-center justify-content-center text-center py-5">
            <div class="col-lg-8">
                <!-- Judul -->
                <h1 class="display-3 fw-bold text-white mb-4 animate__animated animate__fadeInDown">
                    {{ settings('site_name', 'BKK SMKN 1 PURWOSARI') }}
                </h1>
                
                <!-- Tagline -->
                <p class="lead text-white mb-5 fs-4 animate__animated animate__fadeInUp" style="animation-delay: 0.2s;">
                    {{ settings('site_tagline', 'Membangun Karir, Mewujudkan Impian') }}
                </p>
                
                <!-- Buttons -->
                <div class="d-flex gap-3 justify-content-center flex-wrap animate__animated animate__fadeInUp" style="animation-delay: 0.4s;">
                    <a href="{{ route('vacancies') }}" class="btn btn-primary btn-lg px-5 py-3 shadow-lg">
                        <i class="bi bi-briefcase me-2"></i>Lihat Lowongan
                    </a>
                    @guest
                        <a href="{{ route('register.student') }}" class="btn btn-light btn-lg px-5 py-3 shadow-lg">
                            <i class="bi bi-person-plus me-2"></i>Daftar Sekarang
                        </a>
                    @endguest
                </div>
            </div>
        </div>
    </div>
    
    <!-- Scroll Down Indicator -->
    <div class="position-absolute bottom-0 start-50 translate-middle-x pb-4" style="z-index: 1;">
        <a href="#stats" class="text-white text-decoration-none">
            <i class="bi bi-chevron-down fs-1 animate__animated animate__bounce animate__infinite"></i>
        </a>
    </div>
</section>

<style>
/* Hero Section Styling */
.hero-section {
    position: relative;
    overflow: hidden;
}

.min-vh-75 {
    min-height: 75vh;
}

/* Preload background image */
.hero-background::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background: inherit;
    opacity: 0;
    transition: opacity 0.5s ease-in;
}

.hero-background.loaded::before {
    opacity: 1;
}

/* Button Hover Effects */
.hero-section .btn-primary {
    background: #667eea;
    border: none;
    transition: all 0.3s ease;
}

.hero-section .btn-primary:hover {
    background: #764ba2;
    transform: translateY(-3px);
    box-shadow: 0 10px 25px rgba(102, 126, 234, 0.4) !important;
}

.hero-section .btn-light:hover {
    background: #f8f9fa;
    transform: translateY(-3px);
    box-shadow: 0 10px 25px rgba(0, 0, 0, 0.2) !important;
}

/* Animasi */
@keyframes fadeInDown {
    from {
        opacity: 0;
        transform: translateY(-30px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

@keyframes fadeInUp {
    from {
        opacity: 0;
        transform: translateY(30px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

.animate__animated {
    animation-duration: 1s;
    animation-fill-mode: both;
}

.animate__fadeInDown {
    animation-name: fadeInDown;
}

.animate__fadeInUp {
    animation-name: fadeInUp;
}

.animate__bounce {
    animation-name: bounce;
    animation-duration: 2s;
}

@keyframes bounce {
    0%, 20%, 50%, 80%, 100% {
        transform: translateY(0);
    }
    40% {
        transform: translateY(-10px);
    }
    60% {
        transform: translateY(-5px);
    }
}

.animate__infinite {
    animation-iteration-count: infinite;
}

/* Responsive */
@media (max-width: 768px) {
    .hero-section {
        min-height: 500px;
    }
    
    .hero-section h1 {
        font-size: 2.5rem !important;
    }
    
    .hero-section .lead {
        font-size: 1.1rem !important;
    }
    
    .hero-section .btn {
        padding: 0.75rem 2rem !important;
        font-size: 0.9rem !important;
    }
}
</style>

@push('scripts')
<script>
// Preload background image
document.addEventListener('DOMContentLoaded', function() {
    const heroBackground = document.querySelector('.hero-background');
    if (heroBackground) {
        const img = new Image();
        img.onload = function() {
            heroBackground.classList.add('loaded');
        };
        img.src = 'https://images.unsplash.com/photo-1552581234-26160f608093?w=1920&q=80&fm=jpg&fit=crop';
    }
});
</script>
@endpush

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
@php
    // Ambil maksimal 3 sorotan terbaru untuk ditampilkan
    $displayedHighlights = $highlights->take(3);
@endphp
<section class="py-5 bg-gradient" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);">
    <div class="container">
        <div class="text-center mb-5 text-white">
            <h2 class="fw-bold">Sorotan BKK</h2>
            <p class="mb-0">Prestasi, pengumuman, dan profil alumni sukses</p>
        </div>
        
        <div class="highlights-carousel-wrapper" style="perspective: 1200px; overflow: visible;">
            <div class="highlights-carousel-track d-flex justify-content-center align-items-center position-relative" 
                 style="min-height: 380px;" 
                 id="highlightsTrack">
                @foreach($displayedHighlights as $index => $highlight)
                <div class="highlight-card position-absolute" 
                     data-index="{{ $index }}"
                     style="transition: all 0.8s cubic-bezier(0.4, 0, 0.2, 1); transform-style: preserve-3d;">
                    <div class="card shadow-lg border-0 overflow-hidden" 
                         style="width: 480px; border-radius: 16px; height: 280px;">
                        <div class="row g-0 h-100">
                            <!-- Image Side (Landscape - 60%) -->
                            <div class="col-7">
                                @if($highlight->featured_image)
                                    <img src="{{ Storage::url($highlight->featured_image) }}" 
                                         class="h-100 w-100" 
                                         alt="{{ $highlight->title }}"
                                         style="object-fit: cover;">
                                @else
                                    <div class="h-100 w-100 bg-white d-flex align-items-center justify-content-center">
                                        <i class="bi bi-star-fill text-warning" style="font-size: 80px;"></i>
                                    </div>
                                @endif
                            </div>
                            <!-- Content Side (40%) -->
                            <div class="col-5 d-flex flex-column">
                                <div class="card-body d-flex flex-column justify-content-center bg-white p-3 h-100">
                                    <span class="badge bg-warning text-dark mb-2 align-self-start">
                                        <i class="bi bi-star-fill me-1"></i>SOROTAN
                                    </span>
                                    <h6 class="card-title fw-bold mb-2" style="font-size: 0.95rem; line-height: 1.3; display: -webkit-box; -webkit-line-clamp: 3; -webkit-box-orient: vertical; overflow: hidden;">
                                        {{ $highlight->title }}
                                    </h6>
                                    <p class="card-text text-muted mb-3 flex-grow-1" style="font-size: 0.8rem; line-height: 1.4; display: -webkit-box; -webkit-line-clamp: 4; -webkit-box-orient: vertical; overflow: hidden;">
                                        {{ $highlight->excerpt }}
                                    </p>
                                    <a href="{{ route('information.show', $highlight->slug) }}" 
                                       class="btn btn-sm btn-primary w-100" style="font-size: 0.85rem;">
                                        Selengkapnya
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
            
            <!-- Custom Indicators -->
            @if($displayedHighlights->count() > 1)
            <div class="text-center mt-5">
                <div class="d-inline-flex gap-2 align-items-center">
                    @foreach($displayedHighlights as $index => $highlight)
                    <button type="button" 
                            class="highlight-indicator"
                            data-index="{{ $index }}"
                            style="width: 10px; height: 10px; border-radius: 50%; border: 2px solid white; background-color: {{ $index === 0 ? 'white' : 'rgba(255,255,255,0.4)' }}; padding: 0; cursor: pointer; transition: all 0.3s;"
                            aria-label="Slide {{ $index + 1 }}"></button>
                    @endforeach
                </div>
            </div>
            @endif
            
            <!-- Link ke semua sorotan jika ada lebih dari 3 -->
            @if($highlights->count() > 3)
            <div class="text-center mt-4">
                <a href="{{ route('information', ['category' => 'highlight']) }}" 
                   class="btn btn-light btn-sm">
                    <i class="bi bi-star me-1"></i>
                    Lihat Semua Sorotan ({{ $highlights->count() }})
                </a>
            </div>
            @endif
        </div>
    </div>
</section>

@push('styles')
<style>
.highlight-card .card {
    will-change: transform, opacity;
}

.highlight-indicator:hover {
    transform: scale(1.3);
    background-color: white !important;
}

@media (max-width: 991px) {
    .highlights-carousel-track {
        flex-direction: column !important;
        min-height: auto !important;
        gap: 30px;
    }
    
    .highlight-card {
        position: relative !important;
        transform: none !important;
        opacity: 1 !important;
    }
    
    .highlight-card .card {
        width: 100% !important;
        max-width: 480px;
        height: auto !important;
    }
    
    .highlight-card .card .row {
        flex-direction: column !important;
    }
    
    .highlight-card .card .col-7,
    .highlight-card .card .col-5 {
        width: 100% !important;
    }
    
    .highlight-card .card .col-7 img,
    .highlight-card .card .col-7 > div {
        height: 200px !important;
    }
}
</style>
@endpush

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const totalHighlights = {{ $displayedHighlights->count() }};
    
    if (totalHighlights <= 1 || window.innerWidth < 992) return;
    
    let currentIndex = 0;
    const cards = document.querySelectorAll('.highlight-card');
    const indicators = document.querySelectorAll('.highlight-indicator');
    
    // Posisi kartu dalam carousel (seperti roda putar)
    const positions = {
        center: { x: 0, z: 0, scale: 1.2, opacity: 1, rotateY: 0 },
        right: { x: 400, z: -200, scale: 0.75, opacity: 0.6, rotateY: -25 },
        left: { x: -400, z: -200, scale: 0.75, opacity: 0.6, rotateY: 25 }
    };
    
    function getCardPosition(cardIndex, centerIndex) {
        const diff = (cardIndex - centerIndex + totalHighlights) % totalHighlights;
        
        if (diff === 0) return 'center';
        if (diff === 1) return 'right';
        if (diff === totalHighlights - 1) return 'left';
        
        // Kartu yang tidak terlihat (jika ada lebih dari 3)
        return 'hidden';
    }
    
    function updateCarousel(instant = false) {
        cards.forEach((card, index) => {
            const position = getCardPosition(index, currentIndex);
            const pos = positions[position];
            
            if (position === 'hidden') {
                card.style.transform = 'translateX(-1000px)';
                card.style.opacity = '0';
                card.style.zIndex = '0';
                card.style.pointerEvents = 'none';
                return;
            }
            
            const transitionDuration = instant ? '0s' : '0.8s';
            card.style.transition = `all ${transitionDuration} cubic-bezier(0.4, 0, 0.2, 1)`;
            
            card.style.transform = `
                translateX(${pos.x}px) 
                translateZ(${pos.z}px) 
                scale(${pos.scale}) 
                rotateY(${pos.rotateY}deg)
            `;
            card.style.opacity = pos.opacity;
            card.style.zIndex = position === 'center' ? '10' : '5';
            card.style.pointerEvents = 'auto';
            
            // Shadow berdasarkan posisi
            const cardElement = card.querySelector('.card');
            if (position === 'center') {
                cardElement.style.boxShadow = '0 25px 50px rgba(0,0,0,0.4)';
                cardElement.style.filter = 'brightness(1.05)';
            } else {
                cardElement.style.boxShadow = '0 10px 30px rgba(0,0,0,0.2)';
                cardElement.style.filter = 'brightness(0.85)';
            }
        });
        
        // Update indicators
        indicators.forEach((indicator, index) => {
            if (index === currentIndex) {
                indicator.style.backgroundColor = 'white';
                indicator.style.transform = 'scale(1.4)';
            } else {
                indicator.style.backgroundColor = 'rgba(255,255,255,0.4)';
                indicator.style.transform = 'scale(1)';
            }
        });
    }
    
    function nextSlide() {
        currentIndex = (currentIndex + 1) % totalHighlights;
        updateCarousel();
    }
    
    // Auto slide setiap 3 detik
    let autoSlideInterval = setInterval(nextSlide, 3000);
    
    // Manual control via indicators
    indicators.forEach((indicator) => {
        indicator.addEventListener('click', function() {
            clearInterval(autoSlideInterval);
            currentIndex = parseInt(this.getAttribute('data-index'));
            updateCarousel();
            
            // Restart auto slide
            autoSlideInterval = setInterval(nextSlide, 3000);
        });
    });
    
    // Initialize
    updateCarousel(true);
});
</script>
@endpush
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
@endsection