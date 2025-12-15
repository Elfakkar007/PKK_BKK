@extends('layouts.admin')

@section('title', 'Manajemen Highlights - Admin BKK')
@section('page-title', 'Manajemen Highlights')

@section('content')
<div class="card">
    <div class="card-header bg-white py-3">
        <div class="d-flex justify-content-between align-items-center">
            <h5 class="mb-0 fw-bold">
                <i class="bi bi-star me-2"></i>Daftar Highlights
            </h5>
            <a href="{{ route('admin.highlights.create') }}" class="btn btn-primary">
                <i class="bi bi-plus-circle me-2"></i>Tambah Highlight
            </a>
        </div>
    </div>
    <div class="card-body">
        @if($highlights->count() > 0)
        <div class="table-responsive">
            <table class="table table-hover">
                <thead class="table-light">
                    <tr>
                        <th style="width: 80px;">Gambar</th>
                        <th>Judul</th>
                        <th>Order</th>
                        <th>Status</th>
                        <th class="text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($highlights as $highlight)
                    <tr>
                        <td>
                            @if($highlight->image)
                                <img src="{{ Storage::url($highlight->image) }}" 
                                     class="rounded" 
                                     style="width: 60px; height: 60px; object-fit: cover;">
                            @else
                                <div class="bg-secondary rounded d-flex align-items-center justify-content-center" 
                                     style="width: 60px; height: 60px;">
                                    <i class="bi bi-image text-white"></i>
                                </div>
                            @endif
                        </td>
                        <td>
                            <strong>{{ $highlight->title }}</strong><br>
                            <small class="text-muted">{{ Str::limit($highlight->description, 50) }}</small>
                        </td>
                        <td>
                            <span class="badge bg-primary">{{ $highlight->order }}</span>
                        </td>
                        <td>
                            @if($highlight->is_active)
                                <span class="badge bg-success">Aktif</span>
                            @else
                                <span class="badge bg-secondary">Nonaktif</span>
                            @endif
                        </td>
                        <td class="text-center">
                            <div class="btn-group btn-group-sm">
                                @if($highlight->link)
                                    <a href="{{ $highlight->link }}" 
                                       target="_blank" 
                                       class="btn btn-outline-info"
                                       title="Buka Link">
                                        <i class="bi bi-link-45deg"></i>
                                    </a>
                                @endif
                                <a href="{{ route('admin.highlights.edit', $highlight->id) }}" 
                                   class="btn btn-outline-primary"
                                   title="Edit">
                                    <i class="bi bi-pencil"></i>
                                </a>
                                <form method="POST" 
                                      action="{{ route('admin.highlights.destroy', $highlight->id) }}" 
                                      class="d-inline delete-form"
                                      data-title="Hapus Highlight"
                                      data-text="Yakin ingin menghapus highlight '{{ $highlight->title }}'?">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" 
                                            class="btn btn-outline-danger delete-btn"
                                            title="Hapus">
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
            {{ $highlights->links() }}
        </div>
        @else
        <div class="text-center py-5">
            <i class="bi bi-inbox text-muted display-1"></i>
            <p class="mt-3 text-muted">Belum ada highlight</p>
            <a href="{{ route('admin.highlights.create') }}" class="btn btn-primary">
                <i class="bi bi-plus-circle me-2"></i>Tambah Highlight Pertama
            </a>
        </div>
        @endif
    </div>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Handle delete confirmation
    const deleteForms = document.querySelectorAll('.delete-form');
    
    deleteForms.forEach(form => {
        form.addEventListener('submit', function(e) {
            e.preventDefault();
            
            const title = this.dataset.title || 'Konfirmasi Hapus';
            const text = this.dataset.text || 'Yakin ingin menghapus data ini?';
            
            Swal.fire({
                title: title,
                text: text,
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#dc3545',
                cancelButtonColor: '#6c757d',
                confirmButtonText: 'Ya, Hapus!',
                cancelButtonText: 'Batal',
                reverseButtons: true
            }).then((result) => {
                if (result.isConfirmed) {
                    this.submit();
                }
            });
        });
    });
});
</script>
@endpush