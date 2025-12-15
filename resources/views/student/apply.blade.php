@extends('layouts.app')

@section('title', 'Lamar Pekerjaan - ' . $vacancy->title)

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-lg-8">
            <!-- Progress Indicator -->
            <div class="card shadow-sm mb-4">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div class="text-center flex-fill">
                            <div class="bg-primary text-white rounded-circle d-inline-flex align-items-center justify-content-center mb-2" 
                                 style="width: 40px; height: 40px;">
                                <i class="bi bi-check"></i>
                            </div>
                            <p class="small mb-0 fw-bold">Pilih Lowongan</p>
                        </div>
                        <div class="flex-fill">
                            <hr class="border-2 border-primary">
                        </div>
                        <div class="text-center flex-fill">
                            <div class="bg-primary text-white rounded-circle d-inline-flex align-items-center justify-content-center mb-2" 
                                 style="width: 40px; height: 40px;">
                                2
                            </div>
                            <p class="small mb-0 fw-bold text-primary">Isi Formulir</p>
                        </div>
                        <div class="flex-fill">
                            <hr class="border-2 border-secondary">
                        </div>
                        <div class="text-center flex-fill">
                            <div class="bg-secondary text-white rounded-circle d-inline-flex align-items-center justify-content-center mb-2" 
                                 style="width: 40px; height: 40px;">
                                3
                            </div>
                            <p class="small mb-0 text-muted">Selesai</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Vacancy Info -->
            <div class="card shadow-sm mb-4">
                <div class="card-body">
                    <h5 class="fw-bold mb-3">Melamar untuk:</h5>
                    <div class="d-flex align-items-start">
                        @if($vacancy->company->logo)
                            <img src="{{ Storage::url($vacancy->company->logo) }}" 
                                 alt="{{ $vacancy->company->name }}" 
                                 class="rounded me-3" 
                                 style="width: 60px; height: 60px; object-fit: cover;">
                        @endif
                        <div>
                            <h6 class="fw-bold mb-1">{{ $vacancy->title }}</h6>
                            <p class="text-muted mb-1">{{ $vacancy->company->name }}</p>
                            <small class="text-muted">
                                <i class="bi bi-geo-alt"></i> {{ $vacancy->location }} &bull; 
                                <span class="badge bg-{{ $vacancy->type === 'internship' ? 'info' : 'success' }}">
                                    {{ get_vacancy_type_label($vacancy->type) }}
                                </span>
                            </small>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Application Form -->
            <div class="card shadow-sm">
                <div class="card-body p-4">
                    <h5 class="fw-bold mb-4">
                        <i class="bi bi-file-text me-2"></i>Formulir Lamaran
                    </h5>

                    @if($errors->any())
                        <div class="alert alert-danger">
                            <strong>Terdapat kesalahan:</strong>
                            <ul class="mb-0 mt-2">
                                @foreach($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <form method="POST" 
                          action="{{ route('student.apply.submit', $vacancy->id) }}" 
                          enctype="multipart/form-data"
                          id="applicationForm">
                        @csrf

                        <div class="alert alert-info">
                            <i class="bi bi-info-circle me-2"></i>
                            Data di bawah ini sudah diisi otomatis dari profile Anda. Anda dapat mengubahnya jika diperlukan.
                        </div>

                        <div class="mb-3">
                            <label for="full_name" class="form-label">Nama Lengkap <span class="text-danger">*</span></label>
                            <input type="text" 
                                   class="form-control @error('full_name') is-invalid @enderror" 
                                   id="full_name" 
                                   name="full_name" 
                                   value="{{ old('full_name', $student->full_name) }}" 
                                   required>
                            @error('full_name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="address" class="form-label">Alamat Lengkap <span class="text-danger">*</span></label>
                            <textarea class="form-control @error('address') is-invalid @enderror" 
                                      id="address" 
                                      name="address" 
                                      rows="3" 
                                      required>{{ old('address', $student->address) }}</textarea>
                            @error('address')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="birth_place" class="form-label">Tempat Lahir <span class="text-danger">*</span></label>
                                <input type="text" 
                                       class="form-control @error('birth_place') is-invalid @enderror" 
                                       id="birth_place" 
                                       name="birth_place" 
                                       value="{{ old('birth_place', $student->birth_place) }}" 
                                       required>
                                @error('birth_place')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="birth_date" class="form-label">Tanggal Lahir <span class="text-danger">*</span></label>
                                <input type="date" 
                                       class="form-control @error('birth_date') is-invalid @enderror" 
                                       id="birth_date" 
                                       name="birth_date" 
                                       value="{{ old('birth_date', $student->birth_date->format('Y-m-d')) }}" 
                                       required>
                                @error('birth_date')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="email" class="form-label">Email <span class="text-danger">*</span></label>
                                <input type="email" 
                                       class="form-control @error('email') is-invalid @enderror" 
                                       id="email" 
                                       name="email" 
                                       value="{{ old('email', $student->user->email) }}" 
                                       required>
                                @error('email')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="phone" class="form-label">No. HP/WhatsApp <span class="text-danger">*</span></label>
                                <input type="text" 
                                       class="form-control @error('phone') is-invalid @enderror" 
                                       id="phone" 
                                       name="phone" 
                                       value="{{ old('phone', $student->phone) }}" 
                                       required
                                       placeholder="08xxxxxxxxxx">
                                @error('phone')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="mb-4">
                            <label for="cv" class="form-label">Upload CV (PDF) <span class="text-danger">*</span></label>
                            
                            @if($student->cv_path)
                                <div class="alert alert-success mb-2">
                                    <i class="bi bi-check-circle me-2"></i>
                                    Anda sudah memiliki CV tersimpan. Anda bisa menggunakan CV tersebut atau upload CV baru.
                                    <a href="{{ Storage::url($student->cv_path) }}" target="_blank" class="alert-link">
                                        Lihat CV <i class="bi bi-box-arrow-up-right"></i>
                                    </a>
                                </div>
                                <div class="form-check mb-2">
                                    <input class="form-check-input" type="checkbox" id="use_existing_cv" onchange="toggleCvUpload()">
                                    <label class="form-check-label" for="use_existing_cv">
                                        Gunakan CV yang sudah tersimpan
                                    </label>
                                </div>
                            @endif
                            
                            <input type="file" 
                                   class="form-control @error('cv') is-invalid @enderror" 
                                   id="cv" 
                                   name="cv" 
                                   accept=".pdf"
                                   {{ $student->cv_path ? '' : 'required' }}>
                            <small class="text-muted">Format: PDF, Maksimal 5MB</small>
                            @error('cv')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="alert alert-warning">
                            <i class="bi bi-exclamation-triangle me-2"></i>
                            <strong>Perhatian:</strong> Pastikan semua data yang Anda masukkan sudah benar. Setelah mengirim lamaran, Anda tidak dapat mengubah data.
                        </div>

                        <div class="d-flex gap-3">
                            <a href="{{ route('vacancies.show', $vacancy->id) }}" class="btn btn-outline-secondary">
                                <i class="bi bi-arrow-left me-2"></i>Kembali
                            </a>
                            <button type="button" class="btn btn-primary flex-fill" onclick="confirmSubmitApplication()">
                                <i class="bi bi-send me-2"></i>Kirim Lamaran
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
function toggleCvUpload() {
    const checkbox = document.getElementById('use_existing_cv');
    const cvInput = document.getElementById('cv');
    
    if (checkbox.checked) {
        cvInput.required = false;
        cvInput.disabled = true;
        cvInput.value = '';
    } else {
        cvInput.required = true;
        cvInput.disabled = false;
    }
}

// Validate CV file
document.getElementById('cv')?.addEventListener('change', function(e) {
    const file = e.target.files[0];
    if (file) {
        // Check file type
        if (file.type !== 'application/pdf') {
            Swal.fire({
                icon: 'error',
                title: 'Format File Salah',
                text: 'CV harus dalam format PDF',
                confirmButtonColor: '#dc3545'
            });
            this.value = '';
            return;
        }

        // Check file size (5MB)
        if (file.size > 5 * 1024 * 1024) {
            Swal.fire({
                icon: 'error',
                title: 'File Terlalu Besar',
                text: 'Ukuran CV maksimal 5MB',
                confirmButtonColor: '#dc3545'
            });
            this.value = '';
            return;
        }
    }
});

function confirmSubmitApplication() {
    // Validate required fields
    const form = document.getElementById('applicationForm');
    if (!form.checkValidity()) {
        form.reportValidity();
        return;
    }

    // Check CV
    const cvInput = document.getElementById('cv');
    const useExistingCV = document.getElementById('use_existing_cv');
    
    if (!useExistingCV?.checked && !cvInput.files.length) {
        Swal.fire({
            icon: 'error',
            title: 'CV Belum Diupload',
            text: 'Mohon upload CV atau gunakan CV yang sudah tersimpan',
            confirmButtonColor: '#dc3545'
        });
        return;
    }

    Swal.fire({
        title: 'Kirim Lamaran?',
        html: '<p>Pastikan semua data sudah benar.</p><p class="text-danger mb-0"><strong>Setelah dikirim, lamaran tidak dapat diubah!</strong></p>',
        icon: 'question',
        showCancelButton: true,
        confirmButtonColor: '#0d6efd',
        cancelButtonColor: '#6c757d',
        confirmButtonText: 'Ya, Kirim Lamaran!',
        cancelButtonText: 'Cek Lagi',
        reverseButtons: true
    }).then((result) => {
        if (result.isConfirmed) {
            // Show loading
            Swal.fire({
                title: 'Mengirim Lamaran...',
                html: 'Mohon tunggu sebentar',
                allowOutsideClick: false,
                allowEscapeKey: false,
                didOpen: () => {
                    Swal.showLoading();
                }
            });
            
            form.submit();
        }
    });
}
</script>
@endpush
@endsection