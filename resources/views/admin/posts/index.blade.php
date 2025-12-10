@extends('layouts.admin')

@section('title', 'Manajemen Postingan - Admin BKK')
@section('page-title', 'Manajemen Postingan')

@section('content')
<div class="card">
    <div class="card-header bg-white py-3">
        <div class="d-flex justify-content-between align-items-center">
            <h5 class="mb-0 fw-bold">
                <i class="bi bi-newspaper me-2"></i>Daftar Postingan
            </h5>
            <a href="{{ route('admin.posts.create') }}" class="btn btn-primary">
                <i class="bi bi-plus-circle me-2"></i>Buat Postingan
            </a>
        </div>
    </div>
    <div class="card-body">
        <!-- Filter -->
        <div class="row g-3 mb-4">
            <div class="col-md-3">
                <select class="form-select" onchange="window.location.href=this.value">
                    <option value="{{ route('admin.posts.index') }}">Semua Kategori</option>
                    <option value="{{ route('admin.posts.index', ['category' => 'news']) }}" {{ request('category') == 'news' ? 'selected' : '' }}>Berita</option>
                    <option value="{{ route('admin.posts.index', ['category' => 'documentation']) }}" {{ request('category') == 'documentation' ? 'selected' : '' }}>Dokumentasi</option>
                    <option value="{{ route('admin.posts.index', ['category' => 'guide']) }}" {{ request('category') == 'guide' ? 'selected' : '' }}>Panduan</option>
                    <option value="{{ route('admin.posts.index', ['category' => 'highlight']) }}" {{ request('category') == 'highlight' ? 'selected' : '' }}>Sorotan</option>
                </select>
            </div>
            <div class="col-md-3">
                <select class="form-select" onchange="window.location.href=this.value">
                    <option value="{{ route('admin.posts.index') }}">Semua Status</option>
                    <option value="{{ route('admin.posts.index', ['is_published' => '1']) }}" {{ request('is_published') == '1' ? 'selected' : '' }}>Published</option>
                    <option value="{{ route('admin.posts.index', ['is_published' => '0']) }}" {{ request('is_published') == '0' ? 'selected' : '' }}>Draft</option>
                </select>
            </div>
        </div>

        <!-- Table -->
        @if($posts->count() > 0)
        <div class="table-responsive">
            <table class="table table-hover">
                <thead class="table-light">
                    <tr>
                        <th style="width: 60px;"></th>
                        <th>Judul</th>
                        <th>Kategori</th>
                        <th>Status</th>
                        <th>Views</th>
                        <th>Tanggal</th>
                        <th class="text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($posts as $post)
                    <tr>
                        <td>
                            @if($post->featured_image)
                                <img src="{{ Storage::url($post->featured_image) }}" 
                                     class="rounded" 
                                     style="width: 50px; height: 50px; object-fit: cover;">
                            @else
                                <div class="bg-secondary rounded d-flex align-items-center justify-content-center" 
                                     style="width: 50px; height: 50px;">
                                    <i class="bi bi-image text-white"></i>
                                </div>
                            @endif
                        </td>
                        <td>
                            <strong>{{ Str::limit($post->title, 50) }}</strong>
                            <br>
                            <small class="text-muted">{{ $post->author->email }}</small>
                        </td>
                        <td>
                            <span class="badge bg-primary">{{ $post->category_label }}</span>
                        </td>
                        <td>
                            @if($post->is_published)
                                <span class="badge bg-success">Published</span>
                            @else
                                <span class="badge bg-secondary">Draft</span>
                            @endif
                        </td>
                        <td>{{ $post->views }}</td>
                        <td>
                            <small>{{ $post->created_at->format('d M Y') }}</small>
                        </td>
                        <td class="text-center">
                            <div class="btn-group btn-group-sm">
                                @if($post->is_published)
                                    <a href="{{ route('information.show', $post->slug) }}" 
                                       target="_blank" 
                                       class="btn btn-outline-info">
                                        <i class="bi bi-eye"></i>
                                    </a>
                                @endif
                                <a href="{{ route('admin.posts.edit', $post->id) }}" 
                                   class="btn btn-outline-primary">
                                    <i class="bi bi-pencil"></i>
                                </a>
                                <form method="POST" action="{{ route('admin.posts.destroy', $post->id) }}" class="d-inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" 
                                            class="btn btn-outline-danger"
                                            onclick="return confirm('Yakin ingin menghapus postingan ini?')">
                                        <i class="bi bi-trash"></i>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        
        <div class="mt-3">
            {{ $posts->links() }}
        </div>
        @else
        <div class="text-center py-5">
            <i class="bi bi-inbox text-muted display-1"></i>
            <p class="mt-3 text-muted">Belum ada postingan</p>
            <a href="{{ route('admin.posts.create') }}" class="btn btn-primary">
                <i class="bi bi-plus-circle me-2"></i>Buat Postingan Pertama
            </a>
        </div>
        @endif
    </div>
</div>
@endsection