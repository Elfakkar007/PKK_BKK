@extends('layouts.app')

@section('title', 'Registrasi Perusahaan - BKK SMKN 1 Purwosari')

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-10 col-lg-9">
            <div class="card shadow">
                <div class="card-body p-4 p-md-5">
                    <div class="text-center mb-4">
                        <i class="bi bi-building-add text-primary display-4"></i>
                        <h3 class="mt-3 fw-bold">Registrasi Perusahaan</h3>
                        <p class="text-muted">Bergabunglah dengan BKK SMKN 1 Purwosari untuk menemukan talenta terbaik</p>
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

                    <form method="POST" action="{{ route('register.company') }}" enctype="multipart/form-data">
                        @csrf

                        <!-- Account Information -->
                        <h5 class="mb-3"><i class="bi bi-shield-lock me-2"></i>Informasi Akun</h5>
                        
                        <div class="mb-3">
                            <label for="email" class="form-label">Email Perusahaan <span class="text-danger">*</span></label>
                            <input type="email" class="form-control @error('email') is-invalid @enderror" 
                                   id="email" name="email" value="{{ old('email') }}" required>
                            <small class="text-muted">Email akan digunakan untuk login dan menerima notifikasi</small>
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

                        <!-- Company Information -->
                        <h5 class="mb-3"><i class="bi bi-building me-2"></i>Informasi Perusahaan</h5>

                        <div class="row">
                            <div class="col-md-8 mb-3">
                                <label for="name" class="form-label">Nama Perusahaan <span class="text-danger">*</span></label>
                                <input type="text" class="form-control @error('name') is-invalid @enderror" 
                                       id="name" name="name" value="{{ old('name') }}" required>
                                @error('name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-4 mb-3">
                                <label for="company_type" class="form-label">Tipe <span class="text-danger">*</span></label>
                                <select class="form-select @error('company_type') is-invalid @enderror" 
                                        id="company_type" name="company_type" required>
                                    <option value="">Pilih...</option>
                                    <option value="PT" {{ old('company_type') == 'PT' ? 'selected' : '' }}>PT</option>
                                    <option value="CV" {{ old('company_type') == 'CV' ? 'selected' : '' }}>CV</option>
                                    <option value="UD" {{ old('company_type') == 'UD' ? 'selected' : '' }}>UD</option>
                                    <option value="Koperasi" {{ old('company_type') == 'Koperasi' ? 'selected' : '' }}>Koperasi</option>
                                    <option value="Yayasan" {{ old('company_type') == 'Yayasan' ? 'selected' : '' }}>Yayasan</option>
                                </select>
                                @error('company_type')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="industry_sector" class="form-label">Sektor Industri <span class="text-danger">*</span></label>
                            <select class="form-select @error('industry_sector') is-invalid @enderror" 
                                    id="industry_sector" name="industry_sector" required>
                                <option value="">Pilih...</option>
                                <option value="Manufaktur" {{ old('industry_sector') == 'Manufaktur' ? 'selected' : '' }}>Manufaktur</option>
                                <option value="Teknologi Informasi" {{ old('industry_sector') == 'Teknologi Informasi' ? 'selected' : '' }}>Teknologi Informasi</option>
                                <option value="Otomotif" {{ old('industry_sector') == 'Otomotif' ? 'selected' : '' }}>Otomotif</option>
                                <option value="Perhotelan" {{ old('industry_sector') == 'Perhotelan' ? 'selected' : '' }}>Perhotelan</option>
                                <option value="Pendidikan" {{ old('industry_sector') == 'Pendidikan' ? 'selected' : '' }}>Pendidikan</option>
                                <option value="Kesehatan" {{ old('industry_sector') == 'Kesehatan' ? 'selected' : '' }}>Kesehatan</option>
                                <option value="Retail" {{ old('industry_sector') == 'Retail' ? 'selected' : '' }}>Retail</option>
                                <option value="F&B" {{ old('industry_sector') == 'F&B' ? 'selected' : '' }}>Food & Beverage</option>
                                <option value="Konstruksi" {{ old('industry_sector') == 'Konstruksi' ? 'selected' : '' }}>Konstruksi</option>
                                <option value="Lainnya" {{ old('industry_sector') == 'Lainnya' ? 'selected' : '' }}>Lainnya</option>
                            </select>
                            @error('industry_sector')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="description" class="form-label">Deskripsi Perusahaan</label>
                            <textarea class="form-control @error('description') is-invalid @enderror" 
                                      id="description" name="description" rows="3">{{ old('description') }}</textarea>
                            <small class="text-muted">Jelaskan secara singkat tentang perusahaan Anda</small>
                            @error('description')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="head_office_address" class="form-label">Alamat Kantor Pusat <span class="text-danger">*</span></label>
                            <textarea class="form-control @error('head_office_address') is-invalid @enderror" 
                                      id="head_office_address" name="head_office_address" rows="2" required>{{ old('head_office_address') }}</textarea>
                            @error('head_office_address')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <hr class="my-4">

                        <!-- Contact Person -->
                        <h5 class="mb-3"><i class="bi bi-person-badge me-2"></i>Penanggung Jawab (PIC)</h5>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="pic_name" class="form-label">Nama PIC <span class="text-danger">*</span></label>
                                <input type="text" class="form-control @error('pic_name') is-invalid @enderror" 
                                       id="pic_name" name="pic_name" value="{{ old('pic_name') }}" required>
                                @error('pic_name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="pic_phone" class="form-label">No. Telepon/WhatsApp <span class="text-danger">*</span></label>
                                <input type="text" class="form-control @error('pic_phone') is-invalid @enderror" 
                                       id="pic_phone" name="pic_phone" value="{{ old('pic_phone') }}" 
                                       placeholder="08xxxxxxxxxx" required>
                                @error('pic_phone')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="pic_email" class="form-label">Email PIC</label>
                            <input type="email" class="form-control @error('pic_email') is-invalid @enderror" 
                                   id="pic_email" name="pic_email" value="{{ old('pic_email') }}">
                            <small class="text-muted">Opsional, untuk kontak alternatif</small>
                            @error('pic_email')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="website" class="form-label">Website Perusahaan</label>
                            <input type="url" class="form-control @error('website') is-invalid @enderror" 
                                   id="website" name="website" value="{{ old('website') }}" 
                                   placeholder="https://www.contoh.com">
                            @error('website')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <hr class="my-4">

                        <!-- Documents -->
                        <h5 class="mb-3"><i class="bi bi-file-earmark-arrow-up me-2"></i>Dokumen</h5>

                        <div class="mb-3">
                            <label for="logo" class="form-label">Logo Perusahaan</label>
                            <input type="file" class="form-control @error('logo') is-invalid @enderror" 
                                   id="logo" name="logo" accept="image/*">
                            <small class="text-muted">Format: JPG, PNG. Maksimal 2MB</small>
                            @error('logo')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="legality_doc" class="form-label">Dokumen Legalitas (SIUP/NIB)</label>
                            <input type="file" class="form-control @error('legality_doc') is-invalid @enderror" 
                                   id="legality_doc" name="legality_doc" accept=".pdf">
                            <small class="text-muted">Format: PDF. Maksimal 5MB. Dokumen ini akan membantu proses verifikasi</small>
                            @error('legality_doc')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="alert alert-info">
                            <i class="bi bi-info-circle me-2"></i>
                            <strong>Catatan:</strong> Akun perusahaan akan ditinjau oleh admin sebelum dapat mengakses semua fitur. Anda akan menerima email notifikasi setelah akun disetujui.
                        </div>

                        <div class="form-check mb-4">
                            <input class="form-check-input" type="checkbox" id="terms" required>
                            <label class="form-check-label" for="terms">
                                Saya menyetujui <a href="#" target="_blank">syarat dan ketentuan</a> yang berlaku dan bertanggung jawab atas kebenaran data yang saya berikan
                            </label>
                        </div>

                        <button type="submit" class="btn btn-primary w-100 py-2">
                            <i class="bi bi-building-add me-2"></i>Daftar Perusahaan
                        </button>

                        <div class="text-center mt-3">
                            <p class="text-muted mb-0">
                                Sudah punya akun? 
                                <a href="{{ route('login') }}">Login di sini</a>
                            </p>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection