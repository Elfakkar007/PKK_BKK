@extends('layouts.app')

@section('title', $post->title . ' - BKK SMKN 1 Purwosari')

@section('content')
<div class="container py-5">
    <div class="row">
        <div class="col-lg-8 mx-auto">
            <!-- Back Button -->
            <a href="{{ route('information', ['category' => $post->category]) }}" 
               class="btn btn-sm btn-outline-secondary mb-4">
                <i class="bi bi-arrow-left me-2"></i>Kembali
            </a>

            <!-- Post Header -->
            <div class="mb-4">
                <div class="mb-3">
                    <span class="badge bg-primary">{{ $post->category_label }}</span>
                </div>
                
                <h1 class="fw-bold mb-3">{{ $post->title }}</h1>
                
                <div class="d-flex flex-wrap gap-3 text-muted">
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
            </div>

            <!-- Featured Image -->
            @if($post->featured_image)
            <div class="mb-4">
                <img src="{{ Storage::url($post->featured_image) }}" 
                     alt="{{ $post->title }}" 
                     class="img-fluid rounded shadow">
            </div>
            @endif

            <!-- Post Content -->
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-body p-4">
                    <div class="post-content" style="line-height: 1.8;">
                        {!! nl2br(e($post->content)) !!}
                    </div>
                </div>
            </div>

            <!-- Share Section -->
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-body">
                    <h6 class="fw-bold mb-3">
                        <i class="bi bi-share me-2"></i>Bagikan Artikel Ini
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
                <h4 class="fw-bold mb-4">Artikel Terkait</h4>
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
                                <small class="text-muted">
                                    {{ format_date_indonesian($related->published_at) }}
                                </small>
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