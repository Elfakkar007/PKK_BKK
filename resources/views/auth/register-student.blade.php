@extends('layouts.app')

@section('title', 'Registrasi Siswa/Alumni - BKK SMKN 1 Purwosari')

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-8 col-lg-7">
            <div class="card shadow">
                <div class="card-body p-4 p-md-5">
                    <div class="text-center mb-4">
                        <i class="bi bi-person-plus-fill text-primary display-4"></i>
                        <h3 class="mt-3 fw-bold">Registrasi Siswa/Alumni</h3>
                        <p class="text-muted">Lengkapi formulir di bawah ini untuk mendaftar</p>
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
                    @if(settings('feature_registration', '1') === '1')
                    <form method="POST" action="{{ route('register.student') }}">
                        @csrf

                        <!-- Account Information -->
                        <h5 class="mb-3"><i class="bi bi-shield-lock me-2"></i>Informasi Akun</h5>
                        
                        <div class="mb-3">
                            <label for="email" class="form-label">Email <span class="text-danger">*</span></label>
                            <input type="email" class="form-control @error('email') is-invalid @enderror" 
                                   id="email" name="email" value="{{ old('email') }}" required>
                            <small class="text-muted">Gunakan email aktif untuk menerima notifikasi</small>
                            @error('email')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="password" class="form-label">Password <span class="text-danger">*</span></label>
                                <input type="password" class="form-control @error('password') is-invalid @enderror" 
                                       id="password" name="password" required>
                                <small class="text-muted">Minimal 8 karakter</small>
                                @error('password')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="password_confirmation" class="form-label">Konfirmasi Password <span class="text-danger">*</span></label>
                                <input type="password" class="form-control" 
                                       id="password_confirmation" name="password_confirmation" required>
                            </div>
                        </div>

                        <hr class="my-4">

                        <!-- Personal Information -->
                        <h5 class="mb-3"><i class="bi bi-person me-2"></i>Informasi Pribadi</h5>

                        <div class="mb-3">
                            <label for="full_name" class="form-label">Nama Lengkap <span class="text-danger">*</span></label>
                            <input type="text" class="form-control @error('full_name') is-invalid @enderror" 
                                   id="full_name" name="full_name" value="{{ old('full_name') }}" required>
                            @error('full_name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="nis" class="form-label">NIS <span class="text-danger">*</span></label>
                                <input type="text" class="form-control @error('nis') is-invalid @enderror" 
                                       id="nis" name="nis" value="{{ old('nis') }}" required>
                                @error('nis')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="gender" class="form-label">Jenis Kelamin <span class="text-danger">*</span></label>
                                <select class="form-select @error('gender') is-invalid @enderror" 
                                        id="gender" name="gender" required>
                                    <option value="">Pilih...</option>
                                    <option value="male" {{ old('gender') == 'male' ? 'selected' : '' }}>Laki-laki</option>
                                    <option value="female" {{ old('gender') == 'female' ? 'selected' : '' }}>Perempuan</option>
                                </select>
                                @error('gender')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="status" class="form-label">Status <span class="text-danger">*</span></label>
                                <select class="form-select @error('status') is-invalid @enderror" 
                                        id="status" name="status" required onchange="toggleGraduationYear()">
                                    <option value="">Pilih...</option>
                                    <option value="student" {{ old('status') == 'student' ? 'selected' : '' }}>Siswa Aktif</option>
                                    <option value="alumni" {{ old('status') == 'alumni' ? 'selected' : '' }}>Alumni</option>
                                </select>
                                @error('status')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-6 mb-3" id="graduation_year_group" style="display: none;">
                                <label for="graduation_year" class="form-label">Tahun Kelulusan <span class="text-danger">*</span></label>
                                <input type="number" class="form-control @error('graduation_year') is-invalid @enderror" 
                                       id="graduation_year" name="graduation_year" value="{{ old('graduation_year') }}" 
                                       min="2000" max="{{ date('Y') }}">
                                @error('graduation_year')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="row" id="class_group">
                            <div class="col-md-6 mb-3">
                                <label for="class" class="form-label">Kelas <span class="text-danger">*</span></label>
                                <select class="form-select @error('class') is-invalid @enderror" 
                                        id="class" name="class">
                                    <option value="">Pilih...</option>
                                    <option value="X" {{ old('class') == 'X' ? 'selected' : '' }}>X</option>
                                    <option value="XI" {{ old('class') == 'XI' ? 'selected' : '' }}>XI</option>
                                    <option value="XII" {{ old('class') == 'XII' ? 'selected' : '' }}>XII</option>
                                </select>
                                @error('class')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="major" class="form-label">Jurusan <span class="text-danger">*</span></label>
                                <select class="form-select @error('major') is-invalid @enderror" 
                                        id="major" name="major" required>
                                    <option value="">Pilih...</option>
                                    <option value="TKJ" {{ old('major') == 'TKJ' ? 'selected' : '' }}>Teknik Komputer dan Jaringan</option>
                                    <option value="RPL" {{ old('major') == 'RPL' ? 'selected' : '' }}>Rekayasa Perangkat Lunak</option>
                                    <option value="MM" {{ old('major') == 'MM' ? 'selected' : '' }}>Multimedia</option>
                                    <option value="OTKP" {{ old('major') == 'OTKP' ? 'selected' : '' }}>Otomatisasi Tata Kelola Perkantoran</option>
                                    <option value="AKL" {{ old('major') == 'AKL' ? 'selected' : '' }}>Akuntansi dan Keuangan Lembaga</option>
                                </select>
                                @error('major')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="birth_place" class="form-label">Tempat Lahir <span class="text-danger">*</span></label>
                                <input type="text" class="form-control @error('birth_place') is-invalid @enderror" 
                                       id="birth_place" name="birth_place" value="{{ old('birth_place') }}" required>
                                @error('birth_place')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="birth_date" class="form-label">Tanggal Lahir <span class="text-danger">*</span></label>
                                <input type="date" class="form-control @error('birth_date') is-invalid @enderror" 
                                       id="birth_date" name="birth_date" value="{{ old('birth_date') }}" required>
                                @error('birth_date')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="form-check mb-4">
                            <input class="form-check-input" type="checkbox" id="terms" required>
                            <label class="form-check-label" for="terms">
                                Saya menyetujui <a href="#" target="_blank">syarat dan ketentuan</a> yang berlaku
                            </label>
                        </div>

                        <button type="submit" class="btn btn-primary w-100 py-2">
                            <i class="bi bi-person-plus me-2"></i>Daftar Sekarang
                        </button>

                        <div class="text-center mt-3">
                            <p class="text-muted mb-0">
                                Sudah punya akun? 
                                <a href="{{ route('login') }}">Login di sini</a>
                            </p>
                        </div>
                    </form>
                    @else
                    <div class="alert alert-warning">
                        <i class="bi bi-exclamation-triangle me-2"></i>
                        Registrasi sementara ditutup. Silakan hubungi admin untuk informasi lebih lanjut.
                    </div>
                @endif
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
function toggleGraduationYear() {
    const status = document.getElementById('status').value;
    const graduationYearGroup = document.getElementById('graduation_year_group');
    const classGroup = document.getElementById('class_group');
    const graduationYearInput = document.getElementById('graduation_year');
    const classInput = document.getElementById('class');
    
    if (status === 'alumni') {
        graduationYearGroup.style.display = 'block';
        graduationYearInput.required = true;
        classGroup.style.display = 'none';
        classInput.required = false;
        classInput.value = '';
    } else if (status === 'student') {
        graduationYearGroup.style.display = 'none';
        graduationYearInput.required = false;
        graduationYearInput.value = '';
        classGroup.style.display = 'flex';
        classInput.required = true;
    } else {
        graduationYearGroup.style.display = 'none';
        classGroup.style.display = 'flex';
        graduationYearInput.required = false;
        classInput.required = false;
    }
}

// Initialize on page load
document.addEventListener('DOMContentLoaded', function() {
    toggleGraduationYear();
});
</script>
@endpush
@endsection