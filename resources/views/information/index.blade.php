@extends('layouts.app')

@section('title', 'Informasi - BKK SMKN 1 Purwosari')

@push('styles')
<style>
.documentation-card {
    position: relative;
    overflow: hidden;
}
.documentation-card img {
    transition: transform 0.3s ease;
}
.documentation-card:hover img {
    transform: scale(1.1);
}
.documentation-overlay {
    position: absolute;
    bottom: 0;
    left: 0;
    right: 0;
    background: linear-gradient(to top, rgba(0,0,0,0.8), transparent);
    color: white;
    padding: 1.5rem 1rem 1rem;
}
</style>
@endpush

@section('content')
<!-- Page Header -->
<section class="bg-light py-5">
    <div class="container">
        <h1 class="fw-bold mb-3">Informasi BKK</h1>
        <p class="lead text-muted mb-0">
            Berita terkini, dokumentasi kegiatan, panduan, dan sorotan prestasi
        </p>
    </div>
</section>

<!-- Category Filter -->
<section class="py-4 bg-white border-bottom sticky-top" style="top: 0; z-index: 100;">
    <div class="container">
        <div class="d-flex flex-wrap gap-2">
            <a href="{{ route('information') }}" 
               class="btn {{ $category === 'news' ? 'btn-primary' : 'btn-outline-primary' }}">
                <i class="bi bi-newspaper me-2"></i>Berita
            </a>
            <a href="{{ route('information', ['category' => 'documentation']) }}" 
               class="btn {{ $category === 'documentation' ? 'btn-primary' : 'btn-outline-primary' }}">
                <i class="bi bi-camera me-2"></i>Dokumentasi
            </a>
            <a href="{{ route('information', ['category' => 'guide']) }}" 
               class="btn {{ $category === 'guide' ? 'btn-primary' : 'btn-outline-primary' }}">
                <i class="bi bi-book me-2"></i>Panduan
            </a>
            <a href="{{ route('information', ['category' => 'highlight']) }}" 
               class="btn {{ $category === 'highlight' ? 'btn-primary' : 'btn-outline-primary' }}">
                <i class="bi bi-star me-2"></i>Sorotan
            </a>
        </div>
    </div>
</section>

<!-- Posts Grid -->
<section class="py-5">
    <div class="container">
        @if($posts->count() > 0)
        
        @if($category === 'documentation')
            <!-- Documentation: Photo Grid Layout -->
            <div class="row g-3">
                @foreach($posts as $post)
                <div class="col-md-6 col-lg-4">
                    <a href="{{ route('information.show', $post->slug) }}" class="text-decoration-none">
                        <div class="card border-0 shadow-sm documentation-card" style="height: 300px;">
                            @if($post->featured_image)
                                <img src="{{ Storage::url($post->featured_image) }}" 
                                     class="card-img" 
                                     alt="{{ $post->title }}"
                                     style="height: 100%; width: 100%; object-fit: cover;">
                            @else
                                <div class="card-img bg-gradient d-flex align-items-center justify-content-center h-100" 
                                     style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);">
                                    <i class="bi bi-camera text-white" style="font-size: 4rem;"></i>
                                </div>
                            @endif
                            
                            <div class="documentation-overlay">
                                <h5 class="fw-bold mb-1">{{ $post->title }}</h5>
                                <small>
                                    <i class="bi bi-calendar3 me-1"></i>
                                    {{ format_date_indonesian($post->published_at) }}
                                </small>
                            </div>
                        </div>
                    </a>
                </div>
                @endforeach
            </div>
            
        @elseif($category === 'guide')
            <!-- Guide: List Layout (Clean) -->
            <div class="row g-4">
                @foreach($posts as $post)
                <div class="col-12">
                    <div class="card border-0 shadow-sm">
                        <div class="card-body p-4">
                            <div class="row align-items-center">
                                <div class="col-md-2 text-center mb-3 mb-md-0">
                                    <div class="bg-primary text-white rounded-circle d-inline-flex align-items-center justify-content-center" 
                                         style="width: 80px; height: 80px;">
                                        <i class="bi bi-book" style="font-size: 2rem;"></i>
                                    </div>
                                </div>
                                <div class="col-md-8">
                                    <h5 class="fw-bold mb-2">
                                        <a href="{{ route('information.show', $post->slug) }}" 
                                           class="text-decoration-none text-dark">
                                            {{ $post->title }}
                                        </a>
                                    </h5>
                                    <p class="text-muted mb-2">
                                        {{ Str::limit(strip_tags($post->excerpt ?? $post->content), 150) }}
                                    </p>
                                    <small class="text-muted">
                                        <i class="bi bi-eye me-1"></i>{{ $post->views }} views
                                    </small>
                                </div>
                                <div class="col-md-2 text-md-end">
                                    <a href="{{ route('information.show', $post->slug) }}" 
                                       class="btn btn-outline-primary">
                                        Baca Panduan
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
            
        @elseif($category === 'highlight')
            <!-- Highlight: Large Cards -->
            <div class="row g-4">
                @foreach($posts as $post)
                <div class="col-md-6 col-lg-4">
                    <div class="card h-100 border-0 shadow-sm">
                        @if($post->featured_image)
                            <img src="{{ Storage::url($post->featured_image) }}" 
                                 class="card-img-top" 
                                 alt="{{ $post->title }}"
                                 style="height: 280px; object-fit: cover;">
                        @else
                            <div class="card-img-top bg-gradient d-flex align-items-center justify-content-center" 
                                 style="height: 280px; background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);">
                                <i class="bi bi-star-fill text-white" style="font-size: 5rem;"></i>
                            </div>
                        @endif
                        
                        <div class="card-body d-flex flex-column">
                            <div class="mb-2">
                                <span class="badge bg-warning text-dark">
                                    <i class="bi bi-star-fill me-1"></i>SOROTAN
                                </span>
                            </div>
                            
                            <h5 class="card-title fw-bold">{{ $post->title }}</h5>
                            
                            <p class="card-text text-muted flex-grow-1">
                                {{ $post->excerpt }}
                            </p>
                            
                            <div class="d-flex justify-content-between align-items-center mt-3 pt-3 border-top">
                                <small class="text-muted">
                                    <i class="bi bi-eye"></i> {{ $post->views }}
                                </small>
                                <a href="{{ route('information.show', $post->slug) }}" 
                                   class="btn btn-sm btn-primary">
                                    Selengkapnya
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
            
        @else
            <!-- News: Standard Card Layout -->
            <div class="row g-4">
                @foreach($posts as $post)
                <div class="col-md-6 col-lg-4">
                    <div class="card h-100 border-0 shadow-sm">
                        @if($post->featured_image)
                            <img src="{{ Storage::url($post->featured_image) }}" 
                                 class="card-img-top" 
                                 alt="{{ $post->title }}"
                                 style="height: 220px; object-fit: cover;">
                        @else
                            <div class="card-img-top bg-gradient d-flex align-items-center justify-content-center" 
                                 style="height: 220px; background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);">
                                <i class="bi bi-newspaper text-white" style="font-size: 4rem;"></i>
                            </div>
                        @endif
                        
                        <div class="card-body d-flex flex-column">
                            <div class="mb-2">
                                <span class="badge bg-primary">{{ $post->category_label }}</span>
                                <small class="text-muted ms-2">
                                    <i class="bi bi-eye"></i> {{ $post->views }}
                                </small>
                            </div>
                            
                            <h5 class="card-title">
                                <a href="{{ route('information.show', $post->slug) }}" 
                                   class="text-decoration-none text-dark">
                                    {{ $post->title }}
                                </a>
                            </h5>
                            
                            <p class="card-text text-muted flex-grow-1">
                                {{ Str::limit(strip_tags($post->excerpt ?? $post->content), 120) }}
                            </p>
                            
                            <div class="d-flex justify-content-between align-items-center mt-3 pt-3 border-top">
                                <small class="text-muted">
                                    <i class="bi bi-calendar3"></i>
                                    {{ format_date_indonesian($post->published_at) }}
                                </small>
                                <a href="{{ route('information.show', $post->slug) }}" 
                                   class="btn btn-sm btn-outline-primary">
                                    Baca Selengkapnya
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
        @endif

        <!-- Pagination -->
        <div class="mt-5">
            {{ $posts->links() }}
        </div>
        @else
        <!-- Empty State -->
        <div class="text-center py-5">
            <i class="bi bi-inbox display-1 text-muted"></i>
            <h4 class="mt-3 mb-2">Belum Ada {{ 
                $category === 'news' ? 'Berita' : 
                ($category === 'documentation' ? 'Dokumentasi' : 
                ($category === 'guide' ? 'Panduan' : 'Sorotan')) 
            }}</h4>
            <p class="text-muted">Konten untuk kategori ini akan segera hadir.</p>
        </div>
        @endif
    </div>
</section>
@endsection