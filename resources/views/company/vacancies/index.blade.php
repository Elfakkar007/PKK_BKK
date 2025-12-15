@extends('layouts.app')

@section('title', 'Kelola Lowongan - BKK SMKN 1 Purwosari')

@section('content')
<div class="container py-5">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2 class="fw-bold mb-1">Kelola Lowongan</h2>
            <p class="text-muted mb-0">Kelola semua lowongan pekerjaan perusahaan Anda</p>
        </div>
        <a href="{{ route('company.vacancies.create') }}" class="btn btn-primary">
            <i class="bi bi-plus-circle me-2"></i>Buat Lowongan
        </a>
    </div>

    <!-- Filter Tabs -->
    <ul class="nav nav-tabs mb-4">
        <li class="nav-item">
            <a class="nav-link {{ !request('status') || request('status') == 'all' ? 'active' : '' }}" 
               href="{{ route('company.vacancies') }}">
                Semua
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link {{ request('status') == 'pending' ? 'active' : '' }}" 
               href="{{ route('company.vacancies', ['status' => 'pending']) }}">
                Pending Approval
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link {{ request('status') == 'approved' ? 'active' : '' }}" 
               href="{{ route('company.vacancies', ['status' => 'approved']) }}">
                Aktif
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link {{ request('status') == 'rejected' ? 'active' : '' }}" 
               href="{{ route('company.vacancies', ['status' => 'rejected']) }}">
                Ditolak
            </a>
        </li>
    </ul>

    @if($vacancies->count() > 0)
    <div class="row g-4">
        @foreach($vacancies as $vacancy)
        <div class="col-md-6 col-lg-4">
            <div class="card h-100 border-0 shadow-sm">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-start mb-3">
                        <span class="badge bg-{{ $vacancy->type === 'internship' ? 'info' : 'success' }}">
                            {{ get_vacancy_type_label($vacancy->type) }}
                        </span>
                        <span class="badge {{ get_status_badge_class($vacancy->status) }}">
                            {{ get_status_label($vacancy->status) }}
                        </span>
                    </div>

                    <h5 class="card-title fw-bold">{{ $vacancy->title }}</h5>
                    
                    <div class="mb-3">
                        <p class="mb-1 text-muted small">
                            <i class="bi bi-geo-alt"></i> {{ $vacancy->location }}
                        </p>
                        <p class="mb-1 text-muted small">
                            <i class="bi bi-calendar-event"></i> 
                            Deadline: {{ format_date_indonesian($vacancy->deadline) }}
                        </p>
                        <p class="mb-1 text-muted small">
                            <i class="bi bi-people"></i> 
                            {{ $vacancy->remainingQuota() }} / {{ $vacancy->quota }} posisi tersisa
                        </p>
                        <p class="mb-0 text-muted small">
                            <i class="bi bi-inbox"></i> 
                            {{ $vacancy->applications_count }} lamaran
                        </p>
                    </div>

                    @if($vacancy->isExpired())
                        <div class="alert alert-danger py-2 mb-3">
                            <small><i class="bi bi-exclamation-triangle me-1"></i>Lowongan telah berakhir</small>
                        </div>
                    @elseif($vacancy->status === 'rejected')
                        <div class="alert alert-danger py-2 mb-3">
                            <small><strong>Ditolak:</strong> {{ $vacancy->rejection_reason }}</small>
                        </div>
                    @elseif($vacancy->status === 'pending')
                        <div class="alert alert-warning py-2 mb-3">
                            <small><i class="bi bi-clock me-1"></i>Menunggu approval admin</small>
                        </div>
                    @endif

                    <div class="btn-group w-100" role="group">
                        @if($vacancy->isApproved())
                            <a href="{{ route('vacancies.show', $vacancy->id) }}" 
                               class="btn btn-sm btn-outline-primary" 
                               target="_blank"
                               title="Lihat">
                                <i class="bi bi-eye"></i>
                            </a>
                        @endif
                        <a href="{{ route('company.vacancies.edit', $vacancy->id) }}" 
                           class="btn btn-sm btn-outline-secondary"
                           title="Edit">
                            <i class="bi bi-pencil"></i>
                        </a>
                        <form method="POST" 
                              action="{{ route('company.vacancies.destroy', $vacancy->id) }}" 
                              class="d-inline delete-form"
                              data-title="Hapus Lowongan"
                              data-text="Yakin ingin menghapus lowongan '{{ $vacancy->title }}'?">
                            @csrf
                            @method('DELETE')
                            <button type="submit" 
                                    class="btn btn-sm btn-outline-danger delete-btn"
                                    title="Hapus">
                                <i class="bi bi-trash"></i>
                            </button>
                        </form>
                    </div>
                </div>
                <div class="card-footer bg-light text-muted small">
                    Dibuat {{ time_ago_indonesian($vacancy->created_at) }}
                </div>
            </div>
        </div>
        @endforeach
    </div>

    <div class="mt-4">
        {{ $vacancies->links() }}
    </div>
    @else
    <div class="text-center py-5">
        <i class="bi bi-inbox display-1 text-muted"></i>
        <h4 class="mt-3 mb-2">Belum Ada Lowongan</h4>
        <p class="text-muted mb-4">Mulai buat lowongan untuk menemukan kandidat terbaik</p>
        <a href="{{ route('company.vacancies.create') }}" class="btn btn-primary">
            <i class="bi bi-plus-circle me-2"></i>Buat Lowongan Pertama
        </a>
    </div>
    @endif
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
            const text = this.dataset.text || 'Yakin ingin menghapus lowongan ini?';
            
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