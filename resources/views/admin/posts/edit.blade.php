@extends('layouts.admin')

@section('title', 'Edit Postingan - Admin BKK')
@section('page-title', 'Edit Postingan')

@section('content')
<div class="row">
    <div class="col-lg-10 mx-auto">
        <div class="card">
            <div class="card-body p-4">
                <form method="POST" action="{{ route('admin.posts.update', $post->id) }}" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')

                    <div class="mb-3">
                        <label class="form-label">Judul <span class="text-danger">*</span></label>
                        <input type="text" 
                               class="form-control @error('title') is-invalid @enderror" 
                               name="title" 
                               value="{{ old('title', $post->title) }}" 
                               required>
                        @error('title')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Kategori <span class="text-danger">*</span></label>
                            <select class="form-select @error('category') is-invalid @enderror" 
                                    name="category" 
                                    required>
                                <option value="news" {{ old('category', $post->category) == 'news' ? 'selected' : '' }}>Berita</option>
                                <option value="documentation" {{ old('category', $post->category) == 'documentation' ? 'selected' : '' }}>Dokumentasi</option>
                                <option value="guide" {{ old('category', $post->category) == 'guide' ? 'selected' : '' }}>Panduan</option>
                                <option value="highlight" {{ old('category', $post->category) == 'highlight' ? 'selected' : '' }}>Sorotan</option>
                            </select>
                            @error('category')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-6 mb-3">
                            <label class="form-label">Featured Image</label>
                            @if($post->featured_image)
                                <div class="mb-2">
                                    <img src="{{ Storage::url($post->featured_image) }}" 
                                         class="img-thumbnail" 
                                         style="max-height: 100px;">
                                </div>
                            @endif
                            <input type="file" 
                                   class="form-control @error('featured_image') is-invalid @enderror" 
                                   name="featured_image" 
                                   accept="image/*">
                            <small class="text-muted">Kosongkan jika tidak ingin mengubah</small>
                            @error('featured_image')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Excerpt (Ringkasan)</label>
                        <textarea class="form-control @error('excerpt') is-invalid @enderror" 
                                  name="excerpt" 
                                  rows="2">{{ old('excerpt', $post->excerpt) }}</textarea>
                        @error('excerpt')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Konten <span class="text-danger">*</span></label>
                        <textarea class="form-control @error('content') is-invalid @enderror" 
                                  name="content" 
                                  rows="15" 
                                  required>{{ old('content', $post->content) }}</textarea>
                        @error('content')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <div class="form-check">
                            <input class="form-check-input" 
                                   type="checkbox" 
                                   name="is_published" 
                                   id="is_published" 
                                   value="1"
                                   {{ old('is_published', $post->is_published) ? 'checked' : '' }}>
                            <label class="form-check-label" for="is_published">
                                Publikasikan
                            </label>
                        </div>
                    </div>

                    <div class="alert alert-info">
                        <small>
                            <strong>Info:</strong> Postingan ini sudah dilihat {{ $post->views }} kali.
                            Terakhir diupdate {{ time_ago_indonesian($post->updated_at) }}.
                        </small>
                    </div>

                    <hr class="my-4">

                    <div class="d-flex justify-content-end gap-2">
                        <a href="{{ route('admin.posts.index') }}" class="btn btn-secondary">
                            <i class="bi bi-x-circle me-2"></i>Batal
                        </a>
                        <button type="submit" class="btn btn-primary">
                            <i class="bi bi-save me-2"></i>Update Postingan
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection