@extends('layouts.app')

@section('title', 'Detail Lamaran')

@section('content')
<div class="container py-5">
    <div class="row">
        <div class="col-lg-8 mx-auto">
            <a href="{{ route('company.applications') }}" class="btn btn-sm btn-outline-secondary mb-4">
                <i class="bi bi-arrow-left me-2"></i>Kembali
            </a>

            <!-- Candidate Profile -->
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-body p-4">
                    <div class="d-flex align-items-start mb-4">
                        @if($application->student->photo)
                            <img src="{{ Storage::url($application->student->photo) }}" 
                                 class="rounded-circle me-4" 
                                 style="width: 100px; height: 100px; object-fit: cover;">
                        @else
                            <div class="bg-secondary text-white rounded-circle d-flex align-items-center justify-content-center me-4" 
                                 style="width: 100px; height: 100px;">
                                <i class="bi bi-person display-4"></i>
                            </div>
                        @endif
                        
                        <div class="flex-grow-1">
                            <h3 class="fw-bold mb-2">{{ $application->full_name }}</h3>
                            <p class="text-muted mb-2">
                                {{ $application->student->major }}
                                @if($application->student->isAlumni())
                                    - Alumni {{ $application->student->graduation_year }}
                                @else
                                    - Siswa Kelas {{ $application->student->class }}
                                @endif
                            </p>
                            <div>
                                <span class="badge {{ get_status_badge_class($application->status) }} badge-lg">
                                    {{ $application->status_label }}
                                </span>
                            </div>
                        </div>
                    </div>

                    <div class="alert alert-light border">
                        <h6 class="fw-bold mb-3">Melamar untuk:</h6>
                        <h5 class="mb-1">{{ $application->jobVacancy->title }}</h5>
                        <p class="text-muted mb-0">
                            <span class="badge bg-{{ $application->jobVacancy->type === 'internship' ? 'info' : 'success' }}">
                                {{ get_vacancy_type_label($application->jobVacancy->type) }}
                            </span>
                            <span class="ms-2">{{ $application->jobVacancy->location }}</span>
                        </p>
                    </div>
                </div>
            </div>

            <!-- Contact Information -->
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-body p-4">
                    <h5 class="fw-bold mb-3">Informasi Kontak</h5>
                    
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <small class="text-muted d-block">Email</small>
                            <strong>{{ $application->email }}</strong>
                        </div>
                        <div class="col-md-6 mb-3">
                            <small class="text-muted d-block">No. HP/WhatsApp</small>
                            <strong>{{ format_phone_number($application->phone) }}</strong>
                            <a href="https://wa.me/{{ format_phone_number($application->phone) }}" 
                               target="_blank" 
                               class="btn btn-sm btn-success ms-2">
                                <i class="bi bi-whatsapp"></i>
                            </a>
                        </div>
                        <div class="col-12 mb-3">
                            <small class="text-muted d-block">Alamat</small>
                            <strong>{{ $application->address }}</strong>
                        </div>
                        <div class="col-md-6">
                            <small class="text-muted d-block">Tempat, Tanggal Lahir</small>
                            <strong>{{ $application->birth_place }}, {{ format_date_indonesian($application->birth_date) }}</strong>
                        </div>
                        <div class="col-md-6">
                            <small class="text-muted d-block">Usia</small>
                            <strong>{{ calculate_age($application->birth_date) }} tahun</strong>
                        </div>
                    </div>
                </div>
            </div>

            <!-- CV -->
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-body p-4">
                    <h5 class="fw-bold mb-3">Curriculum Vitae</h5>
                    <a href="{{ Storage::url($application->cv_path) }}" 
                       target="_blank" 
                       class="btn btn-outline-primary">
                        <i class="bi bi-file-earmark-pdf me-2"></i>Lihat CV
                    </a>
                </div>
            </div>

            <!-- Update Status -->
            <div class="card border-0 shadow-sm">
                <div class="card-body p-4">
                    <h5 class="fw-bold mb-3">Update Status Lamaran</h5>
                    
                    <form method="POST" action="{{ route('company.applications.update-status', $application->id) }}">
                        @csrf
                        @method('PATCH')

                        <div class="mb-3">
                            <label class="form-label">Status <span class="text-danger">*</span></label>
                            <select class="form-select @error('status') is-invalid @enderror" 
                                    name="status" 
                                    required>
                                <option value="pending" {{ $application->status == 'pending' ? 'selected' : '' }}>Menunggu</option>
                                <option value="reviewed" {{ $application->status == 'reviewed' ? 'selected' : '' }}>Sedang Ditinjau</option>
                                <option value="accepted" {{ $application->status == 'accepted' ? 'selected' : '' }}>Diterima</option>
                                <option value="rejected" {{ $application->status == 'rejected' ? 'selected' : '' }}>Ditolak</option>
                            </select>
                            @error('status')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Catatan untuk Pelamar</label>
                            <textarea class="form-control @error('company_notes') is-invalid @enderror" 
                                      name="company_notes" 
                                      rows="4" 
                                      placeholder="Berikan catatan atau feedback untuk pelamar...">{{ old('company_notes', $application->company_notes) }}</textarea>
                            <small class="text-muted">Catatan ini akan dilihat oleh pelamar</small>
                            @error('company_notes')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                            <a href="{{ route('company.applications') }}" class="btn btn-outline-secondary">
                                Kembali
                            </a>
                            <button type="submit" class="btn btn-primary">
                                <i class="bi bi-save me-2"></i>Update Status
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Timeline -->
            @if($application->reviewed_at)
            <div class="card border-0 shadow-sm mt-4">
                <div class="card-body p-4">
                    <h6 class="fw-bold mb-3">Timeline</h6>
                    <div class="timeline">
                        <div class="timeline-item">
                            <div class="timeline-marker bg-success"></div>
                            <div class="timeline-content">
                                <h6 class="mb-1">Lamaran Diterima</h6>
                                <small class="text-muted">{{ $application->created_at->format('d M Y, H:i') }}</small>
                            </div>
                        </div>
                        <div class="timeline-item">
                            <div class="timeline-marker bg-{{ $application->isAccepted() ? 'success' : ($application->isRejected() ? 'danger' : 'info') }}"></div>
                            <div class="timeline-content">
                                <h6 class="mb-1">{{ $application->status_label }}</h6>
                                <small class="text-muted">{{ $application->reviewed_at->format('d M Y, H:i') }}</small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            @endif
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