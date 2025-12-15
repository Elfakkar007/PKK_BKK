@extends('layouts.app')

@section('title', $post->title . ' - BKK SMKN 1 Purwosari')

@push('styles')
@if($post->isDocumentation())
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/lightbox2/2.11.4/css/lightbox.min.css">
<style>
.documentation-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(250px, 1fr));
    gap: 1rem;
}
.documentation-grid img {
    width: 100%;
    height: 250px;
    object-fit: cover;
    border-radius: 8px;
    cursor: pointer;
    transition: transform 0.3s ease;
}
.documentation-grid img:hover {
    transform: scale(1.05);
}
</style>
@endif

@if($post->isGuide())
<style>
.guide-content {
    font-size: 1.1rem;
    line-height: 1.8;
    max-width: 800px;
    margin: 0 auto;
}
.guide-content ol {
    padding-left: 2rem;
}
.guide-content ol li {
    margin-bottom: 1.5rem;
    padding-left: 0.5rem;
}
.guide-content h3 {
    margin-top: 2rem;
    margin-bottom: 1rem;
    color: #333;
}
.download-section {
    background: #f8f9fa;
    border-left: 4px solid #0d6efd;
    padding: 1.5rem;
    margin: 2rem 0;
}
</style>
@endif

@if($post->isHighlight())
<style>
.highlight-hero {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: white;
    padding: 4rem 0;
    margin-bottom: 3rem;
}
</style>
@endif
@endpush

@section('content')
<div class="container py-5">
    @if($post->isHighlight())
    <!-- Highlight Hero Section -->
    <div class="highlight-hero rounded-4 mb-4">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-lg-6">
                    <span class="badge bg-warning text-dark mb-3">
                        <i class="bi bi-star-fill me-1"></i>SOROTAN
                    </span>
                    <h1 class="display-4 fw-bold mb-3">{{ $post->title }}</h1>
                    <p class="lead">{{ $post->excerpt }}</p>
                </div>
                @if($post->featured_image)
                <div class="col-lg-6">
                    <img src="{{ Storage::url($post->featured_image) }}" 
                         alt="{{ $post->title }}" 
                         class="img-fluid rounded shadow-lg">
                </div>
                @endif
            </div>
        </div>
    </div>
    @endif

    <div class="row">
        <div class="col-lg-{{ $post->isGuide() ? '10' : '8' }} mx-auto">
            @if(!$post->isHighlight())
            <!-- Back Button -->
            <a href="{{ route('information', ['category' => $post->category]) }}" 
               class="btn btn-sm btn-outline-secondary mb-4">
                <i class="bi bi-arrow-left me-2"></i>Kembali
            </a>

            <!-- Post Header (Non-Highlight) -->
            <div class="mb-4">
                <div class="mb-3">
                    <span class="badge bg-primary">{{ $post->category_label }}</span>
                </div>
                
                <h1 class="fw-bold mb-3">{{ $post->title }}</h1>
                
                @if(!$post->isGuide())
                <div class="d-flex flex-wrap gap-3 text-muted mb-3">
                    <div>
                        <i class="bi bi-calendar3"></i>
                        {{ format_date_indonesian($post->published_at) }}
                    </div>
                    <div>
                        <i class="bi bi-person"></i>
                        {{ $post->author->email }}
                    </div>
                    <div>
                        <i class="bi bi-eye"></i>
                        {{ $post->views }} views
                    </div>
                </div>
                @endif

                @if($post->excerpt && !$post->isHighlight())
                <p class="lead text-muted">{{ $post->excerpt }}</p>
                @endif
            </div>

            <!-- Featured Image (Except Documentation) -->
            @if($post->featured_image && !$post->isDocumentation())
            <div class="mb-4">
                <img src="{{ Storage::url($post->featured_image) }}" 
                     alt="{{ $post->title }}" 
                     class="img-fluid rounded shadow">
            </div>
            @endif
            @endif

            <!-- Content Section based on Category -->
            @if($post->isDocumentation())
                <!-- Documentation: Grid Gallery -->
                <div class="card border-0 shadow-sm mb-4">
                    <div class="card-body p-4">
                        <h5 class="fw-bold mb-3">
                            <i class="bi bi-camera me-2"></i>Album Foto
                        </h5>
                        @if($post->excerpt)
                        <p class="text-muted mb-4">{{ $post->excerpt }}</p>
                        @endif

                        @php
                            $images = $post->getContentImages();
                        @endphp

                        @if(count($images) > 0)
                        <div class="documentation-grid" id="documentationGallery">
                            @foreach($images as $index => $image)
                            <a href="{{ $image }}" data-lightbox="gallery" data-title="{{ $post->title }}">
                                <img src="{{ $image }}" alt="Foto {{ $index + 1 }}" loading="lazy">
                            </a>
                            @endforeach
                        </div>
                        @else
                        <div class="alert alert-info">
                            <i class="bi bi-info-circle me-2"></i>
                            Belum ada foto dalam dokumentasi ini.
                        </div>
                        @endif
                    </div>
                </div>

            @elseif($post->isGuide())
                <!-- Guide: Clean Reading Experience -->
                <div class="card border-0 shadow-sm mb-4">
                    <div class="card-body p-5">
                        <div class="guide-content">
                            {!! nl2br(e($post->content)) !!}
                        </div>

                        @php
                            $pdfLinks = $post->getPdfLinks();
                        @endphp

                        @if(count($pdfLinks) > 0)
                        <div class="download-section rounded">
                            <h5 class="fw-bold mb-3">
                                <i class="bi bi-file-earmark-pdf me-2"></i>File Pendukung
                            </h5>
                            @foreach($pdfLinks as $pdf)
                            <div class="mb-2">
                                <a href="{{ $pdf }}" class="btn btn-outline-primary" target="_blank">
                                    <i class="bi bi-download me-2"></i>Download PDF
                                </a>
                            </div>
                            @endforeach
                        </div>
                        @endif
                    </div>
                </div>

            @else
                <!-- News & Highlight: Standard Article -->
                <div class="card border-0 shadow-sm mb-4">
                    <div class="card-body p-4">
                        <div class="post-content" style="line-height: 1.8; font-size: 1.05rem;">
                            {!! nl2br(e($post->content)) !!}
                        </div>
                    </div>
                </div>
            @endif

            <!-- Share Section (All Categories) -->
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-body">
                    <h6 class="fw-bold mb-3">
                        <i class="bi bi-share me-2"></i>Bagikan
                    </h6>
                    <div class="d-flex gap-2 flex-wrap">
                        <a href="https://wa.me/?text={{ urlencode($post->title . ' - ' . route('information.show', $post->slug)) }}" 
                           target="_blank" 
                           class="btn btn-success">
                            <i class="bi bi-whatsapp me-2"></i>WhatsApp
                        </a>
                        <a href="https://www.facebook.com/sharer/sharer.php?u={{ route('information.show', $post->slug) }}" 
                           target="_blank" 
                           class="btn btn-primary">
                            <i class="bi bi-facebook me-2"></i>Facebook
                        </a>
                        <a href="https://twitter.com/intent/tweet?url={{ route('information.show', $post->slug) }}&text={{ urlencode($post->title) }}" 
                           target="_blank" 
                           class="btn btn-info text-white">
                            <i class="bi bi-twitter me-2"></i>Twitter
                        </a>
                        <button onclick="copyLink()" class="btn btn-secondary">
                            <i class="bi bi-link-45deg me-2"></i>Salin Link
                        </button>
                    </div>
                </div>
            </div>

            <!-- Related Posts -->
            @if($relatedPosts->count() > 0)
            <div class="mt-5">
                <h4 class="fw-bold mb-4">
                    {{ $post->category_label }} Lainnya
                </h4>
                <div class="row g-4">
                    @foreach($relatedPosts as $related)
                    <div class="col-md-4">
                        <div class="card h-100 border-0 shadow-sm">
                            @if($related->featured_image)
                                <img src="{{ Storage::url($related->featured_image) }}" 
                                     class="card-img-top" 
                                     alt="{{ $related->title }}"
                                     style="height: 150px; object-fit: cover;">
                            @endif
                            <div class="card-body">
                                <h6 class="card-title">
                                    <a href="{{ route('information.show', $related->slug) }}" 
                                       class="text-decoration-none text-dark">
                                        {{ Str::limit($related->title, 50) }}
                                    </a>
                                </h6>
                                @if(!$post->isGuide())
                                <small class="text-muted">
                                    {{ format_date_indonesian($related->published_at) }}
                                </small>
                                @endif
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
            @endif
        </div>
    </div>
</div>

@push('scripts')
@if($post->isDocumentation())
<script src="https://cdnjs.cloudflare.com/ajax/libs/lightbox2/2.11.4/js/lightbox.min.js"></script>
<script>
lightbox.option({
    'resizeDuration': 200,
    'wrapAround': true,
    'albumLabel': 'Foto %1 dari %2'
});
</script>
@endif

<script>
function copyLink() {
    const url = window.location.href;
    navigator.clipboard.writeText(url).then(() => {
        alert('Link berhasil disalin!');
    });
}
</script>
@endpush
@endsection