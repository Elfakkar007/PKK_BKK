@extends('layouts.app')

@section('title', 'Detail')

@section('content')
<div class="container py-5">
    <div class="row">
        <div class="col-lg-8 mx-auto">
            <a href="{{ route('student.dashboard') }}" class="btn btn-sm btn-outline-secondary mb-4">
                <i class="bi bi-arrow-left me-2"></i>Kembali ke Dashboard
            </a>

            <!-- Status Alert -->
            @if($application->isAccepted())
                <div class="alert alert-success">
                    <div class="d-flex align-items-center">
                        <i class="bi bi-check-circle-fill fs-3 me-3"></i>
                        <div>
                            <h5 class="mb-1">Selamat! Lamaran Anda Diterima</h5>
                            <p class="mb-0">Perusahaan akan menghubungi Anda segera.</p>
                        </div>
                    </div>
                </div>
            @elseif($application->isRejected())
                <div class="alert alert-danger">
                    <div class="d-flex align-items-center">
                        <i class="bi bi-x-circle-fill fs-3 me-3"></i>
                        <div>
                            <h5 class="mb-1">Lamaran Ditolak</h5>
                            <p class="mb-0">Jangan berkecil hati, masih banyak peluang lainnya.</p>
                        </div>
                    </div>
                </div>
            @elseif($application->isReviewed())
                <div class="alert alert-info">
                    <div class="d-flex align-items-center">
                        <i class="bi bi-eye-fill fs-3 me-3"></i>
                        <div>
                            <h5 class="mb-1">Lamaran Sedang Ditinjau</h5>
                            <p class="mb-0">Perusahaan sedang meninjau lamaran Anda.</p>
                        </div>
                    </div>
                </div>
            @else
                <div class="alert alert-warning">
                    <div class="d-flex align-items-center">
                        <i class="bi bi-clock-fill fs-3 me-3"></i>
                        <div>
                            <h5 class="mb-1">Lamaran Menunggu Review</h5>
                            <p class="mb-0">Lamaran Anda akan segera ditinjau oleh perusahaan.</p>
                        </div>
                    </div>
                </div>
            @endif

            <!-- Job Vacancy Info -->
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-body p-4">
                    <h5 class="fw-bold mb-3">Informasi Lowongan</h5>
                    
                    <div class="d-flex align-items-start mb-3">
                        @if($application->jobVacancy->company->logo)
                            <img src="{{ Storage::url($application->jobVacancy->company->logo) }}" 
                                 alt="{{ $application->jobVacancy->company->name }}" 
                                 class="rounded me-3"
                                 style="width: 60px; height: 60px; object-fit: cover;">
                        @endif
                        <div>
                            <h6 class="fw-bold mb-1">{{ $application->jobVacancy->title }}</h6>
                            <p class="text-muted mb-1">{{ $application->jobVacancy->company->name }}</p>
                            <div>
                                <span class="badge bg-{{ $application->jobVacancy->type === 'internship' ? 'info' : 'success' }}">
                                    {{ get_vacancy_type_label($application->jobVacancy->type) }}
                                </span>
                                <span class="badge bg-secondary">{{ $application->jobVacancy->location }}</span>
                            </div>
                        </div>
                    </div>

                    <a href="{{ route('vacancies.show', $application->jobVacancy->id) }}" 
                       class="btn btn-outline-primary btn-sm">
                        <i class="bi bi-eye me-2"></i>Lihat Detail Lowongan
                    </a>
                </div>
            </div>

            <!-- Application Status -->
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-body p-4">
                    <h5 class="fw-bold mb-3">Status Lamaran</h5>
                    
                    <div class="mb-3">
                        <div class="d-flex justify-content-between align-items-center mb-2">
                            <span class="fw-bold">Status Saat Ini:</span>
                            <span class="badge {{ get_status_badge_class($application->status) }} badge-lg">
                                {{ $application->status_label }}
                            </span>
                        </div>
                        
                        <div class="d-flex justify-content-between align-items-center text-muted small">
                            <span>Tanggal Melamar:</span>
                            <span>{{ format_date_indonesian($application->created_at) }}</span>
                        </div>
                        
                        @if($application->reviewed_at)
                        <div class="d-flex justify-content-between align-items-center text-muted small">
                            <span>Terakhir Diupdate:</span>
                            <span>{{ format_date_indonesian($application->reviewed_at) }}</span>
                        </div>
                        @endif
                    </div>

                    @if($application->company_notes)
                    <div class="alert alert-light border">
                        <h6 class="fw-bold mb-2">
                            <i class="bi bi-chat-left-text me-2"></i>Catatan dari Perusahaan
                        </h6>
                        <p class="mb-0">{{ $application->company_notes }}</p>
                    </div>
                    @endif

                    <!-- Timeline -->
                    <div class="mt-4">
                        <h6 class="fw-bold mb-3">Timeline Lamaran</h6>
                        <div class="timeline">
                            <div class="timeline-item">
                                <div class="timeline-marker bg-success"></div>
                                <div class="timeline-content">
                                    <h6 class="mb-1">Lamaran Dikirim</h6>
                                    <small class="text-muted">{{ $application->created_at->format('d M Y, H:i') }}</small>
                                </div>
                            </div>
                            
                            @if($application->reviewed_at)
                            <div class="timeline-item">
                                <div class="timeline-marker bg-{{ $application->isAccepted() ? 'success' : ($application->isRejected() ? 'danger' : 'info') }}"></div>
                                <div class="timeline-content">
                                    <h6 class="mb-1">{{ $application->status_label }}</h6>
                                    <small class="text-muted">{{ $application->reviewed_at->format('d M Y, H:i') }}</small>
                                </div>
                            </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            <!-- Application Data -->
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-body p-4">
                    <h5 class="fw-bold mb-3">Data Lamaran</h5>
                    
                    <div class="row mb-3">
                        <div class="col-md-4 fw-bold">Nama Lengkap</div>
                        <div class="col-md-8">{{ $application->full_name }}</div>
                    </div>
                    
                    <div class="row mb-3">
                        <div class="col-md-4 fw-bold">Tempat, Tanggal Lahir</div>
                        <div class="col-md-8">
                            {{ $application->birth_place }}, {{ format_date_indonesian($application->birth_date) }}
                        </div>
                    </div>
                    
                    <div class="row mb-3">
                        <div class="col-md-4 fw-bold">Email</div>
                        <div class="col-md-8">{{ $application->email }}</div>
                    </div>
                    
                    <div class="row mb-3">
                        <div class="col-md-4 fw-bold">No. HP</div>
                        <div class="col-md-8">{{ format_phone_number($application->phone) }}</div>
                    </div>
                    
                    <div class="row mb-3">
                        <div class="col-md-4 fw-bold">Alamat</div>
                        <div class="col-md-8">{{ $application->address }}</div>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-4 fw-bold">CV</div>
                        <div class="col-md-8">
                            <a href="{{ Storage::url($application->cv_path) }}" 
                               target="_blank" 
                               class="btn btn-sm btn-outline-primary">
                                <i class="bi bi-file-earmark-pdf me-2"></i>Lihat CV
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@push('styles')
<style>
.timeline {
    position: relative;
    padding-left: 30px;
}

.timeline::before {
    content: '';
    position: absolute;
    left: 7px;
    top: 10px;
    bottom: 10px;
    width: 2px;
    background: #dee2e6;
}

.timeline-item {
    position: relative;
    padding-bottom: 20px;
}

.timeline-marker {
    position: absolute;
    left: -30px;
    width: 16px;
    height: 16px;
    border-radius: 50%;
    border: 2px solid white;
    box-shadow: 0 0 0 2px #dee2e6;
}

.badge-lg {
    padding: 0.5rem 1rem;
    font-size: 0.875rem;
}
</style>
@endpush
@endsection