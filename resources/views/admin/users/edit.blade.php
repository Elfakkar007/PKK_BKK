@extends('layouts.admin')

@section('title', 'Edit User - Admin BKK')
@section('page-title', 'Edit User')

@section('content')
<div class="row justify-content-center">
    <div class="col-lg-8">
        <div class="card">
            <div class="card-header bg-white">
                <h5 class="mb-0 fw-bold">
                    <i class="bi bi-pencil-square me-2"></i>Edit Data User
                </h5>
            </div>
            <div class="card-body">
                <form method="POST" action="{{ route('admin.users.update', $user->id) }}">
                    @csrf
                    @method('PUT')
                    
                    <!-- User Info Display -->
                    <div class="alert alert-info">
                        <div class="d-flex align-items-center">
                            <div class="bg-primary text-white rounded-circle d-flex align-items-center justify-content-center me-3" 
                                 style="width: 50px; height: 50px;">
                                <i class="bi bi-{{ $user->isStudent() ? 'person' : 'building' }}"></i>
                            </div>
                            <div>
                                <strong>
                                    @if($user->student)
                                        {{ $user->student->full_name }}
                                    @elseif($user->company)
                                        {{ $user->company->name }}
                                    @endif
                                </strong>
                                <br>
                                <small class="text-muted">{{ get_role_label($user->role) }}</small>
                            </div>
                        </div>
                    </div>
                    
                    <hr>
                    
                    <!-- Email -->
                    <div class="mb-3">
                        <label class="form-label fw-bold">
                            Email <span class="text-danger">*</span>
                        </label>
                        <input type="email" 
                               class="form-control @error('email') is-invalid @enderror" 
                               name="email" 
                               value="{{ old('email', $user->email) }}"
                               required>
                        @error('email')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                        <small class="text-muted">Email digunakan untuk login</small>
                    </div>
                    
                    <!-- Status -->
                    <div class="mb-3">
                        <label class="form-label fw-bold">
                            Status Akun <span class="text-danger">*</span>
                        </label>
                        <select class="form-select @error('status') is-invalid @enderror" name="status" required>
                            <option value="pending" {{ old('status', $user->status) == 'pending' ? 'selected' : '' }}>
                                Pending - Menunggu Approval
                            </option>
                            <option value="approved" {{ old('status', $user->status) == 'approved' ? 'selected' : '' }}>
                                Approved - Disetujui
                            </option>
                            <option value="rejected" {{ old('status', $user->status) == 'rejected' ? 'selected' : '' }}>
                                Rejected - Ditolak
                            </option>
                        </select>
                        @error('status')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    
                    <hr>
                    
                    <h6 class="fw-bold mb-3">Ubah Password (Opsional)</h6>
                    <div class="alert alert-warning">
                        <i class="bi bi-info-circle me-2"></i>
                        Isi kolom di bawah hanya jika ingin mengganti password. Kosongkan jika tidak ingin mengubah password.
                    </div>
                    
                    <!-- New Password -->
                    <div class="mb-3">
                        <label class="form-label fw-bold">Password Baru</label>
                        <input type="password" 
                               class="form-control @error('password') is-invalid @enderror" 
                               name="password"
                               placeholder="Minimal 8 karakter">
                        @error('password')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    
                    <!-- Confirm Password -->
                    <div class="mb-4">
                        <label class="form-label fw-bold">Konfirmasi Password</label>
                        <input type="password" 
                               class="form-control" 
                               name="password_confirmation"
                               placeholder="Ulangi password baru">
                    </div>
                    
                    <hr>
                    
                    <!-- Action Buttons -->
                    <div class="d-flex gap-2 justify-content-end">
                        <a href="{{ route('admin.users.show', $user->id) }}" class="btn btn-secondary">
                            <i class="bi bi-x-circle me-1"></i>Batal
                        </a>
                        <button type="submit" class="btn btn-primary">
                            <i class="bi bi-check-circle me-1"></i>Simpan Perubahan
                        </button>
                    </div>
                </form>
            </div>
        </div>
        
        <!-- Additional Info Card -->
        <div class="card mt-4">
            <div class="card-body">
                <h6 class="fw-bold mb-3">
                    <i class="bi bi-info-circle me-2"></i>Informasi
                </h6>
                <ul class="small text-muted mb-0">
                    <li>Untuk mereset password user yang lupa password, Anda dapat menggunakan fitur "Ubah Password" di atas.</li>
                    <li>Email harus unik dan valid untuk setiap user.</li>
                    <li>Status "Approved" memungkinkan user untuk mengakses sistem.</li>
                    <li>Status "Pending" atau "Rejected" akan membatasi akses user.</li>
                    <li class="text-danger fw-bold">Data profil {{ $user->isStudent() ? 'siswa/alumni' : 'perusahaan' }} tidak dapat diubah dari halaman ini. User harus mengubahnya sendiri dari profil mereka.</li>
                </ul>
            </div>
        </div>
    </div>
</div>
@endsection