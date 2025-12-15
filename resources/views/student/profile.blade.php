@extends('layouts.app')

@section('title', 'Profile - BKK SMKN 1 Purwosari')

@section('content')
<div class="container py-5">
    <div class="row">
        <div class="col-lg-8 mx-auto">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h2 class="fw-bold mb-0">Profile Saya</h2>
                <a href="{{ route('student.dashboard') }}" class="btn btn-outline-secondary">
                    <i class="bi bi-arrow-left me-2"></i>Kembali
                </a>
            </div>

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

            <!-- Profile Card -->
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-body p-4">
                    <form method="POST" 
                          action="{{ route('student.profile.update') }}" 
                          enctype="multipart/form-data"
                          id="profileForm">
                        @csrf
                        @method('PUT')

                        <!-- Photo Section -->
                        <div class="text-center mb-4">
                            @if($student->photo)
                                <img src="{{ Storage::url($student->photo) }}" 
                                     alt="Foto Profile" 
                                     class="rounded-circle mb-3"
                                     id="photoPreview"
                                     style="width: 150px; height: 150px; object-fit: cover;">
                            @else
                                <div class="bg-secondary text-white rounded-circle d-inline-flex align-items-center justify-content-center mb-3"
                                     id="photoPreview" 
                                     style="width: 150px; height: 150px;">
                                    <i class="bi bi-person display-4"></i>
                                </div>
                            @endif
                            
                            <div>
                                <label for="photo" class="btn btn-sm btn-outline-primary">
                                    <i class="bi bi-camera me-2"></i>Ubah Foto
                                </label>
                                <input type="file" 
                                       id="photo" 
                                       name="photo" 
                                       class="d-none" 
                                       accept="image/*"
                                       onchange="previewPhoto(this)">
                                <p class="text-muted small mt-2 mb-0">Maksimal 2MB, format: JPG, PNG</p>
                            </div>
                        </div>

                        <hr class="my-4">

                        <!-- Personal Information -->
                        <h5 class="fw-bold mb-3">Informasi Pribadi</h5>
                        
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Nama Lengkap <span class="text-danger">*</span></label>
                                <input type="text" 
                                       class="form-control @error('full_name') is-invalid @enderror" 
                                       name="full_name" 
                                       value="{{ old('full_name', $student->full_name) }}" 
                                       required>
                                @error('full_name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            
                            <div class="col-md-6 mb-3">
                                <label class="form-label">NIS</label>
                                <input type="text" 
                                       class="form-control" 
                                       value="{{ $student->nis }}" 
                                       disabled>
                                <small class="text-muted">NIS tidak dapat diubah</small>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Jenis Kelamin</label>
                                <input type="text" 
                                       class="form-control" 
                                       value="{{ get_gender_label($student->gender) }}" 
                                       disabled>
                            </div>
                            
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Status</label>
                                <input type="text" 
                                       class="form-control" 
                                       value="{{ $student->isAlumni() ? 'Alumni' : 'Siswa Aktif' }}" 
                                       disabled>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Jurusan</label>
                                <input type="text" 
                                       class="form-control" 
                                       value="{{ $student->major }}" 
                                       disabled>
                            </div>
                            
                            <div class="col-md-6 mb-3">
                                <label class="form-label">
                                    {{ $student->isAlumni() ? 'Tahun Kelulusan' : 'Kelas' }}
                                </label>
                                <input type="text" 
                                       class="form-control" 
                                       value="{{ $student->isAlumni() ? $student->graduation_year : $student->class }}" 
                                       disabled>
                            </div>
                        </div>

                        <hr class="my-4">

                        <!-- Contact Information -->
                        <h5 class="fw-bold mb-3">Informasi Kontak</h5>

                        <div class="mb-3">
                            <label class="form-label">Email</label>
                            <input type="email" 
                                   class="form-control" 
                                   value="{{ $student->user->email }}" 
                                   disabled>
                            <small class="text-muted">Email tidak dapat diubah</small>
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label">No. HP/WhatsApp</label>
                                <input type="text" 
                                       class="form-control @error('phone') is-invalid @enderror" 
                                       name="phone" 
                                       value="{{ old('phone', $student->phone) }}"
                                       placeholder="08xxxxxxxxxx">
                                @error('phone')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Alamat Lengkap</label>
                            <textarea class="form-control @error('address') is-invalid @enderror" 
                                      name="address" 
                                      rows="3">{{ old('address', $student->address) }}</textarea>
                            @error('address')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <hr class="my-4">

                        <!-- CV Upload -->
                        <h5 class="fw-bold mb-3">Curriculum Vitae (CV)</h5>
                        
                        @if($student->cv_path)
                        <div class="alert alert-success mb-3">
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <i class="bi bi-file-earmark-pdf me-2"></i>
                                    <strong>CV sudah terupload</strong>
                                </div>
                                <a href="{{ Storage::url($student->cv_path) }}" 
                                   target="_blank" 
                                   class="btn btn-sm btn-outline-success">
                                    <i class="bi bi-eye me-1"></i>Lihat CV
                                </a>
                            </div>
                        </div>
                        @endif

                        <div class="mb-3">
                            <label class="form-label">Upload CV Baru (Opsional)</label>
                            <input type="file" 
                                   class="form-control @error('cv_path') is-invalid @enderror" 
                                   name="cv_path" 
                                   accept=".pdf"
                                   id="cvFile">
                            <small class="text-muted">Format: PDF, Maksimal 5MB</small>
                            @error('cv_path')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="d-grid gap-2 d-md-flex justify-content-md-end mt-4">
                            <a href="{{ route('student.dashboard') }}" class="btn btn-outline-secondary">
                                Batal
                            </a>
                            <button type="button" class="btn btn-primary" onclick="confirmSubmitProfile()">
                                <i class="bi bi-save me-2"></i>Simpan Perubahan
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
function previewPhoto(input) {
    if (input.files && input.files[0]) {
        // Validate file size
        if (input.files[0].size > 2 * 1024 * 1024) {
            Swal.fire({
                icon: 'error',
                title: 'File Terlalu Besar',
                text: 'Ukuran foto maksimal 2MB',
                confirmButtonColor: '#dc3545'
            });
            input.value = '';
            return;
        }

        const reader = new FileReader();
        reader.onload = function(e) {
            const preview = document.getElementById('photoPreview');
            preview.innerHTML = '<img src="' + e.target.result + '" class="rounded-circle" style="width: 150px; height: 150px; object-fit: cover;">';
        }
        reader.readAsDataURL(input.files[0]);
    }
}

// Validate CV file
document.getElementById('cvFile')?.addEventListener('change', function(e) {
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

function confirmSubmitProfile() {
    Swal.fire({
        title: 'Simpan Perubahan?',
        text: 'Yakin ingin menyimpan perubahan profile Anda?',
        icon: 'question',
        showCancelButton: true,
        confirmButtonColor: '#0d6efd',
        cancelButtonColor: '#6c757d',
        confirmButtonText: 'Ya, Simpan!',
        cancelButtonText: 'Batal',
        reverseButtons: true
    }).then((result) => {
        if (result.isConfirmed) {
            document.getElementById('profileForm').submit();
        }
    });
}
</script>
@endpush
@endsection