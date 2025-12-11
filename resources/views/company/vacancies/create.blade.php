@extends('layouts.app')

@section('title', 'Buat Lowongan - BKK SMKN 1 Purwosari')

@section('content')
<div class="container py-5">
    <div class="row">
        <div class="col-lg-8 mx-auto">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h2 class="fw-bold mb-0">Buat Lowongan Baru</h2>
                <a href="{{ route('company.vacancies') }}" class="btn btn-outline-secondary">
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

            <div class="alert alert-info">
                <i class="bi bi-info-circle me-2"></i>
                Lowongan yang Anda buat akan ditinjau oleh admin terlebih dahulu sebelum dipublikasikan.
            </div>

            <form method="POST" action="{{ route('company.vacancies.store') }}">
                @csrf

                <div class="card border-0 shadow-sm mb-4">
                    <div class="card-body p-4">
                        <h5 class="fw-bold mb-3">Informasi Lowongan</h5>

                        <div class="mb-3">
                            <label class="form-label">Judul Posisi <span class="text-danger">*</span></label>
                            <input type="text" 
                                   class="form-control @error('title') is-invalid @enderror" 
                                   name="title" 
                                   value="{{ old('title') }}" 
                                   placeholder="Contoh: Web Developer, Staff Administrasi"
                                   required>
                            @error('title')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Tipe Pekerjaan <span class="text-danger">*</span></label>
                                <select class="form-select @error('type') is-invalid @enderror" 
                                        name="type" 
                                        required>
                                    <option value="">Pilih...</option>
                                    <option value="internship" {{ old('type') == 'internship' ? 'selected' : '' }}>Magang</option>
                                    <option value="fulltime" {{ old('type') == 'fulltime' ? 'selected' : '' }}>Full Time</option>
                                </select>
                                @error('type')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6 mb-3">
                                <label class="form-label">Lokasi <span class="text-danger">*</span></label>
                                <input type="text" 
                                       class="form-control @error('location') is-invalid @enderror" 
                                       name="location" 
                                       value="{{ old('location') }}" 
                                       placeholder="Contoh: Surabaya, Pasuruan"
                                       required>
                                @error('location')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Deskripsi Pekerjaan <span class="text-danger">*</span></label>
                            <textarea class="form-control @error('description') is-invalid @enderror" 
                                      name="description" 
                                      rows="6" 
                                      placeholder="Jelaskan tugas dan tanggung jawab..."
                                      required>{{ old('description') }}</textarea>
                            <small class="text-muted">Jelaskan secara detail tentang pekerjaan ini</small>
                            @error('description')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Persyaratan <span class="text-danger">*</span></label>
                            <textarea class="form-control @error('requirements') is-invalid @enderror" 
                                      name="requirements" 
                                      rows="6" 
                                      placeholder="Tuliskan persyaratan dan kualifikasi..."
                                      required>{{ old('requirements') }}</textarea>
                            <small class="text-muted">Contoh: Pendidikan minimal SMK, Menguasai MS Office, dll.</small>
                            @error('requirements')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>

                <div class="card border-0 shadow-sm mb-4">
                    <div class="card-body p-4">
                        <h5 class="fw-bold mb-3">Detail Tambahan</h5>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Gaji Minimum (Opsional)</label>
                                <div class="input-group">
                                    <span class="input-group-text">Rp</span>
                                    <input type="number" 
                                           class="form-control @error('salary_min') is-invalid @enderror" 
                                           name="salary_min" 
                                           value="{{ old('salary_min') }}" 
                                           placeholder="0"
                                           min="0">
                                </div>
                                @error('salary_min')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6 mb-3">
                                <label class="form-label">Gaji Maximum (Opsional)</label>
                                <div class="input-group">
                                    <span class="input-group-text">Rp</span>
                                    <input type="number" 
                                           class="form-control @error('salary_max') is-invalid @enderror" 
                                           name="salary_max" 
                                           value="{{ old('salary_max') }}" 
                                           placeholder="0"
                                           min="0">
                                </div>
                                @error('salary_max')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Kuota Posisi <span class="text-danger">*</span></label>
                                <input type="number" 
                                       class="form-control @error('quota') is-invalid @enderror" 
                                       name="quota" 
                                       value="{{ old('quota', 1) }}" 
                                       min="1"
                                       required>
                                <small class="text-muted">Jumlah orang yang dibutuhkan</small>
                                @error('quota')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6 mb-3">
                                <label class="form-label">Batas Akhir Lamaran <span class="text-danger">*</span></label>
                                <input type="date" 
                                       class="form-control @error('deadline') is-invalid @enderror" 
                                       name="deadline" 
                                       value="{{ old('deadline') }}" 
                                       min="{{ date('Y-m-d', strtotime('+1 day')) }}"
                                       required>
                                @error('deadline')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>
                </div>

                <div class="alert alert-warning">
                    <i class="bi bi-exclamation-triangle me-2"></i>
                    <strong>Perhatian:</strong> Pastikan semua informasi yang Anda masukkan sudah benar. Lowongan akan ditinjau oleh admin sebelum dipublikasikan.
                </div>

                <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                    <a href="{{ route('company.vacancies') }}" class="btn btn-outline-secondary">
                        Batal
                    </a>
                    <button type="submit" class="btn btn-primary">
                        <i class="bi bi-send me-2"></i>Submit Lowongan
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection