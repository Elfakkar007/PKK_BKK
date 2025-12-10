@extends('layouts.app')

@section('title', 'Informasi - BKK SMKN 1 Purwosari')

@section('content')
<!-- Page Header -->
<section class="bg-light py-5">
    <div class="container">
        <h1 class="fw-bold mb-3">Informasi BKK</h1>
        <p class="lead text-muted mb-0">
            Berita terkini, dokumentasi kegiatan, dan panduan untuk siswa & alumni
        </p>
    </div>
</section>

<!-- Category Filter -->
<section class="py-4 bg-white border-bottom">
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
                            <i class="bi bi-{{ $category === 'documentation' ? 'camera' : ($category === 'guide' ? 'book' : 'newspaper') }} text-white" style="font-size: 4rem;"></i>
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

        <!-- Pagination -->
        <div class="mt-5">
            {{ $posts->links() }}
        </div>
        @else
        <!-- Empty State -->
        <div class="text-center py-5">
            <i class="bi bi-inbox display-1 text-muted"></i>
            <h4 class="mt-3 mb-2">Belum Ada {{ $post->category_label ?? 'Konten' }}</h4>
            <p class="text-muted">Konten untuk kategori ini akan segera hadir.</p>
        </div>
        @endif
    </div>
</section>
@endsection