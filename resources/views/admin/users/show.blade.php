@extends('layouts.admin')

@section('title', 'Detail User - Admin BKK')
@section('page-title', 'Detail User')

@section('content')
<div class="row">
    <div class="col-lg-4 mb-4">
        <!-- User Info Card -->
        <div class="card">
            <div class="card-body text-center">
                <div class="bg-primary text-white rounded-circle d-flex align-items-center justify-content-center mx-auto mb-3" 
                     style="width: 80px; height: 80px;">
                    <i class="bi bi-{{ $user->isStudent() ? 'person' : 'building' }} fs-1"></i>
                </div>
                
                <h5 class="fw-bold mb-1">
                    @if($user->student)
                        {{ $user->student->full_name }}
                    @elseif($user->company)
                        {{ $user->company->name }}
                    @endif
                </h5>
                
                <p class="text-muted mb-2">{{ $user->email }}</p>
                
                <div class="d-flex justify-content-center gap-2 mb-3">
                    <span class="badge bg-secondary">{{ get_role_label($user->role) }}</span>
                    <span class="badge {{ get_status_badge_class($user->status) }}">
                        {{ get_status_label($user->status) }}
                    </span>
                </div>
                
                <hr>
                
                <div class="text-start small">
                    <div class="d-flex justify-content-between mb-2">
                        <span class="text-muted">Tanggal Daftar:</span>
                        <strong>{{ format_date_indonesian($user->created_at) }}</strong>
                    </div>
                    <div class="d-flex justify-content-between mb-2">
                        <span class="text-muted">Terakhir Update:</span>
                        <strong>{{ format_date_indonesian($user->updated_at) }}</strong>
                    </div>
                    @if($user->email_verified_at)
                    <div class="d-flex justify-content-between">
                        <span class="text-muted">Email Verified:</span>
                        <i class="bi bi-check-circle-fill text-success"></i>
                    </div>
                    @endif
                </div>
            </div>
            
            <div class="card-footer bg-white">
                <div class="d-grid gap-2">
                    <a href="{{ route('admin.users.edit', $user->id) }}" class="btn btn-primary">
                        <i class="bi bi-pencil me-1"></i>Edit User
                    </a>
                    <button type="button" class="btn btn-warning" data-bs-toggle="modal" data-bs-target="#resetPasswordModal">
                        <i class="bi bi-key me-1"></i>Reset Password
                    </button>
                    <a href="{{ route('admin.users.index') }}" class="btn btn-secondary">
                        <i class="bi bi-arrow-left me-1"></i>Kembali
                    </a>
                </div>
            </div>
        </div>
    </div>
    
    <div class="col-lg-8">
        @if($user->student)
            <!-- Student Details -->
            <div class="card mb-4">
                <div class="card-header bg-white">
                    <h5 class="mb-0 fw-bold">
                        <i class="bi bi-person-badge me-2"></i>Data Siswa/Alumni
                    </h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="text-muted small">Nama Lengkap</label>
                            <p class="mb-0 fw-semibold">{{ $user->student->full_name }}</p>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="text-muted small">NIS</label>
                            <p class="mb-0 fw-semibold">{{ $user->student->nis }}</p>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="text-muted small">Jenis Kelamin</label>
                            <p class="mb-0">{{ get_gender_label($user->student->gender) }}</p>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="text-muted small">Tempat, Tanggal Lahir</label>
                            <p class="mb-0">{{ $user->student->birth_place }}, {{ format_date_indonesian($user->student->birth_date) }}</p>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="text-muted small">Jurusan</label>
                            <p class="mb-0">{{ $user->student->major }}</p>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="text-muted small">Status</label>
                            <p class="mb-0">
                                @if($user->student->isAlumni())
                                    <span class="badge bg-info">Alumni Tahun {{ $user->student->graduation_year }}</span>
                                @else
                                    <span class="badge bg-success">Siswa Kelas {{ $user->student->class }}</span>
                                @endif
                            </p>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="text-muted small">Nomor Telepon</label>
                            <p class="mb-0">{{ format_phone_number($user->student->phone) }}</p>
                        </div>
                        <div class="col-12 mb-3">
                            <label class="text-muted small">Alamat</label>
                            <p class="mb-0">{{ $user->student->address }}</p>
                        </div>
                        @if($user->student->photo)
                        <div class="col-md-6 mb-3">
                            <label class="text-muted small">Foto</label>
                            <div class="mt-2">
                                <img src="{{ Storage::url($user->student->photo) }}" 
                                     class="img-thumbnail" 
                                     style="max-height: 150px; cursor: pointer;"
                                     onclick="window.open('{{ Storage::url($user->student->photo) }}', '_blank')">
                            </div>
                        </div>
                        @endif
                        @if($user->student->cv_path)
                        <div class="col-md-6 mb-3">
                            <label class="text-muted small">CV</label>
                            <div class="mt-2">
                                <a href="{{ Storage::url($user->student->cv_path) }}" 
                                   target="_blank" 
                                   class="btn btn-sm btn-outline-primary">
                                    <i class="bi bi-file-pdf me-1"></i>Lihat CV
                                </a>
                            </div>
                        </div>
                        @endif
                    </div>
                </div>
            </div>
        @elseif($user->company)
            <!-- Company Details -->
            <div class="card mb-4">
                <div class="card-header bg-white">
                    <h5 class="mb-0 fw-bold">
                        <i class="bi bi-building me-2"></i>Data Perusahaan
                    </h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-12 mb-3">
                            <label class="text-muted small">Nama Perusahaan</label>
                            <p class="mb-0 fw-semibold fs-5">{{ $user->company->company_type }} {{ $user->company->name }}</p>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="text-muted small">Sektor Industri</label>
                            <p class="mb-0"><span class="badge bg-secondary">{{ $user->company->industry_sector }}</span></p>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="text-muted small">Website</label>
                            <p class="mb-0">
                                @if($user->company->website)
                                    <a href="{{ $user->company->website }}" target="_blank" class="text-decoration-none">
                                        <i class="bi bi-link-45deg me-1"></i>{{ $user->company->website }}
                                    </a>
                                @else
                                    <span class="text-muted">-</span>
                                @endif
                            </p>
                        </div>
                        @if($user->company->description)
                        <div class="col-12 mb-3">
                            <label class="text-muted small">Deskripsi</label>
                            <p class="mb-0">{{ $user->company->description }}</p>
                        </div>
                        @endif
                        <div class="col-12 mb-3">
                            <label class="text-muted small">Alamat Kantor Pusat</label>
                            <p class="mb-0">{{ $user->company->head_office_address }}</p>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- PIC Details -->
            <div class="card mb-4">
                <div class="card-header bg-white">
                    <h5 class="mb-0 fw-bold">
                        <i class="bi bi-person-badge me-2"></i>Penanggung Jawab (PIC)
                    </h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="text-muted small">Nama PIC</label>
                            <p class="mb-0 fw-semibold">{{ $user->company->pic_name }}</p>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="text-muted small">Telepon/WhatsApp</label>
                            <p class="mb-0">
                                <i class="bi bi-telephone me-1"></i>{{ format_phone_number($user->company->pic_phone) }}
                            </p>
                        </div>
                        @if($user->company->pic_email)
                        <div class="col-12 mb-3">
                            <label class="text-muted small">Email PIC</label>
                            <p class="mb-0">
                                <i class="bi bi-envelope me-1"></i>{{ $user->company->pic_email }}
                            </p>
                        </div>
                        @endif
                    </div>
                </div>
            </div>
            
            <!-- Documents -->
            <div class="card">
                <div class="card-header bg-white">
                    <h5 class="mb-0 fw-bold">
                        <i class="bi bi-file-earmark-text me-2"></i>Dokumen
                    </h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="text-muted small">Logo Perusahaan</label>
                            @if($user->company->logo)
                                <div class="mt-2">
                                    <img src="{{ Storage::url($user->company->logo) }}" 
                                         class="img-thumbnail" 
                                         style="max-height: 120px; cursor: pointer;"
                                         onclick="window.open('{{ Storage::url($user->company->logo) }}', '_blank')">
                                </div>
                            @else
                                <p class="mb-0 text-muted">Tidak ada logo</p>
                            @endif
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="text-muted small">Dokumen Legalitas</label>
                            @if($user->company->legality_doc)
                                <div class="mt-2">
                                    <a href="{{ Storage::url($user->company->legality_doc) }}" 
                                       target="_blank" 
                                       class="btn btn-sm btn-outline-primary">
                                        <i class="bi bi-file-pdf me-1"></i>Lihat Dokumen
                                    </a>
                                </div>
                            @else
                                <p class="mb-0 text-muted">Tidak ada dokumen</p>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        @endif
    </div>
</div>

<!-- Reset Password Modal -->
<div class="modal fade" id="resetPasswordModal" tabindex="-1" data-bs-backdrop="static">
    <div class="modal-dialog">
        <div class="modal-content">
            <form method="POST" action="{{ route('admin.users.reset-password', $user->id) }}">
                @csrf
                @method('PATCH')
                <div class="modal-header">
                    <h5 class="modal-title">Reset Password User</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="alert alert-warning">
                        <i class="bi bi-exclamation-triangle me-2"></i>
                        Anda akan mereset password untuk: <strong>{{ $user->email }}</strong>
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label fw-bold">Password Baru <span class="text-danger">*</span></label>
                        <input type="password" 
                               class="form-control @error('new_password') is-invalid @enderror" 
                               name="new_password" 
                               placeholder="Minimal 8 karakter"
                               required>
                        @error('new_password')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label fw-bold">Konfirmasi Password <span class="text-danger">*</span></label>
                        <input type="password" 
                               class="form-control @error('new_password_confirmation') is-invalid @enderror" 
                               name="new_password_confirmation" 
                               placeholder="Ulangi password baru"
                               required>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-warning">
                        <i class="bi bi-key me-1"></i>Reset Password
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

@if($errors->any())
@push('scripts')
<script>
    // Show modal if there are validation errors
    var resetPasswordModal = new bootstrap.Modal(document.getElementById('resetPasswordModal'));
    resetPasswordModal.show();
</script>
@endpush
@endif

@endsection