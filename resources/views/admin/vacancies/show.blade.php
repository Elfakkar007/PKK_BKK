@extends('layouts.admin')

@section('title', 'Detail Lowongan - Admin BKK')
@section('page-title', 'Detail Lowongan')

@section('content')
<div class="mb-3">
    <a href="{{ route('admin.vacancies.index') }}" class="btn btn-sm btn-outline-secondary">
        <i class="bi bi-arrow-left me-2"></i>Kembali
    </a>
</div>

<div class="row">
    <div class="col-lg-8">
        <div class="card shadow-sm mb-4">
            <div class="card-body p-4">
                <div class="d-flex justify-content-between align-items-start mb-4">
                    <div>
                        <h3 class="fw-bold mb-2">{{ $vacancy->title }}</h3>
                        <p class="text-muted mb-2">{{ $vacancy->company->name }}</p>
                        <div>
                            <span class="badge bg-{{ $vacancy->type === 'internship' ? 'info' : 'success' }}">
                                {{ get_vacancy_type_label($vacancy->type) }}
                            </span>
                            <span class="badge {{ get_status_badge_class($vacancy->status) }}">
                                {{ get_status_label($vacancy->status) }}
                            </span>
                        </div>
                    </div>
                    @if($vacancy->company->logo)
                        <img src="{{ Storage::url($vacancy->company->logo) }}" 
                             class="rounded" 
                             style="width: 80px; height: 80px; object-fit: cover;">
                    @endif
                </div>

                <div class="row mb-4">
                    <div class="col-md-6 mb-3">
                        <small class="text-muted">Lokasi</small>
                        <p class="mb-0 fw-bold">{{ $vacancy->location }}</p>
                    </div>
                    <div class="col-md-6 mb-3">
                        <small class="text-muted">Deadline</small>
                        <p class="mb-0 fw-bold">{{ format_date_indonesian($vacancy->deadline) }}</p>
                    </div>
                    <div class="col-md-6 mb-3">
                        <small class="text-muted">Kuota</small>
                        <p class="mb-0 fw-bold">{{ $vacancy->quota }} posisi</p>
                    </div>
                    @if($vacancy->salary_min || $vacancy->salary_max)
                    <div class="col-md-6 mb-3">
                        <small class="text-muted">Gaji</small>
                        <p class="mb-0 fw-bold">
                            @if($vacancy->salary_min && $vacancy->salary_max)
                                {{ format_currency($vacancy->salary_min) }} - {{ format_currency($vacancy->salary_max) }}
                            @elseif($vacancy->salary_min)
                                Mulai dari {{ format_currency($vacancy->salary_min) }}
                            @else
                                Hingga {{ format_currency($vacancy->salary_max) }}
                            @endif
                        </p>
                    </div>
                    @endif
                </div>

                <hr>

                <div class="mb-4">
                    <h5 class="fw-bold mb-3">Deskripsi Pekerjaan</h5>
                    <p style="white-space: pre-line;">{{ $vacancy->description }}</p>
                </div>

                <div class="mb-4">
                    <h5 class="fw-bold mb-3">Persyaratan</h5>
                    <p style="white-space: pre-line;">{{ $vacancy->requirements }}</p>
                </div>

                @if($vacancy->status === 'rejected' && $vacancy->rejection_reason)
                <div class="alert alert-danger">
                    <h6 class="fw-bold mb-2">Alasan Penolakan:</h6>
                    <p class="mb-0">{{ $vacancy->rejection_reason }}</p>
                </div>
                @endif
            </div>
        </div>
    </div>

    <div class="col-lg-4">
        <!-- Status Card -->
        <div class="card shadow-sm mb-4">
            <div class="card-body">
                <h6 class="fw-bold mb-3">Status Lowongan</h6>
                
                @if($vacancy->status === 'pending')
                <form method="POST" 
                      action="{{ route('admin.vacancies.approve', $vacancy->id) }}" 
                      class="mb-2 approve-form"
                      data-vacancy-title="{{ $vacancy->title }}">
                    @csrf
                    @method('PATCH')
                    <button type="submit" class="btn btn-success w-100">
                        <i class="bi bi-check-circle me-2"></i>Approve
                    </button>
                </form>

                <button type="button" 
                        class="btn btn-danger w-100" 
                        data-bs-toggle="modal" 
                        data-bs-target="#rejectModal">
                    <i class="bi bi-x-circle me-2"></i>Reject
                </button>
                @else
                <div class="alert alert-{{ $vacancy->status === 'approved' ? 'success' : 'danger' }}">
                    Status: <strong>{{ get_status_label($vacancy->status) }}</strong>
                </div>
                @endif
            </div>
        </div>

        <!-- Applications -->
        <div class="card shadow-sm">
            <div class="card-body">
                <h6 class="fw-bold mb-3">Lamaran</h6>
                <div class="text-center">
                    <h3 class="fw-bold text-primary">{{ $vacancy->applications->count() }}</h3>
                    <p class="text-muted mb-0">Total Lamaran</p>
                </div>
                
                @if($vacancy->applications->count() > 0)
                <hr>
                <a href="{{ route('admin.applications.index', ['vacancy_id' => $vacancy->id]) }}" 
                   class="btn btn-outline-primary w-100">
                    <i class="bi bi-eye me-2"></i>Lihat Semua Lamaran
                </a>
                @endif
            </div>
        </div>
    </div>
</div>

<!-- Reject Modal -->
<div class="modal fade" id="rejectModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <form method="POST" 
      action="{{ route('admin.vacancies.reject', $vacancy->id) }}">
    @csrf
    @method('PATCH')
    <div class="modal-header">
        <h5 class="modal-title">Tolak Lowongan</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
    </div>
    <div class="modal-body">
        <div class="alert alert-warning">
            <i class="bi bi-exclamation-triangle me-2"></i>
            Menolak lowongan: <strong>{{ $vacancy->title }}</strong>
        </div>
        <div class="mb-3">
            <label class="form-label">Alasan Penolakan <span class="text-danger">*</span></label>
            <textarea class="form-control" 
                      name="rejection_reason" 
                      rows="3" 
                      required 
                      minlength="10"
                      placeholder="Jelaskan alasan penolakan..."></textarea>
            <div class="invalid-feedback">
                Alasan penolakan minimal 10 karakter
            </div>
        </div>
    </div>
    <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
        <button type="submit" class="btn btn-danger">Tolak Lowongan</button>
    </div>
</form>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Handle approve confirmation
    const approveForm = document.querySelector('.approve-form');
    if (approveForm) {
        approveForm.addEventListener('submit', function(e) {
            e.preventDefault();
            
            const vacancyTitle = this.dataset.vacancyTitle;
            
            Swal.fire({
                title: 'Approve Lowongan?',
                html: `Approve lowongan <strong>"${vacancyTitle}"</strong>?<br><br>Lowongan akan dipublikasikan dan dapat dilihat oleh siswa.`,
                icon: 'question',
                showCancelButton: true,
                confirmButtonColor: '#198754',
                cancelButtonColor: '#6c757d',
                confirmButtonText: 'Ya, Approve!',
                cancelButtonText: 'Batal',
                reverseButtons: true
            }).then((result) => {
                if (result.isConfirmed) {
                    Swal.fire({
                        title: 'Memproses...',
                        html: 'Mohon tunggu sebentar',
                        allowOutsideClick: false,
                        allowEscapeKey: false,
                        didOpen: () => {
                            Swal.showLoading();
                        }
                    });
                    
                    this.submit();
                }
            });
        });
    }

    // Handle reject form validation (tanpa SweetAlert)
    const rejectModal = document.getElementById('rejectModal');
    if (rejectModal) {
        const form = rejectModal.querySelector('form');
        const textarea = form.querySelector('textarea[name="rejection_reason"]');
        
        form.addEventListener('submit', function(e) {
            // Validasi minimal length
            if (textarea.value.trim().length < 10) {
                e.preventDefault();
                textarea.classList.add('is-invalid');
                textarea.focus();
                return false;
            }
        });
        
        // Remove invalid class when typing
        textarea.addEventListener('input', function() {
            if (this.value.trim().length >= 10) {
                this.classList.remove('is-invalid');
            }
        });
    }
});
</script>
@endpush