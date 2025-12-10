@extends('layouts.app')

@section('title', 'Edit Lowongan - BKK SMKN 1 Purwosari')

@section('content')
<div class="container py-5">
    <div class="row">
        <div class="col-lg-8 mx-auto">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h2 class="fw-bold mb-0">Edit Lowongan</h2>
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

            @if($vacancy->status === 'rejected')
                <div class="alert alert-danger">
                    <i class="bi bi-x-circle me-2"></i>
                    <strong>Lowongan Ditolak:</strong> {{ $vacancy->rejection_reason }}
                </div>
            @endif

            <form method="POST" action="{{ route('company.vacancies.update', $vacancy->id) }}">
                @csrf
                @method('PUT')

                <div class="card border-0 shadow-sm mb-4">
                    <div class="card-body p-4">
                        <h5 class="fw-bold mb-3">Informasi Lowongan</h5>

                        <div class="mb-3">
                            <label class="form-label">Judul Posisi <span class="text-danger">*</span></label>
                            <input type="text" 
                                   class="form-control @error('title') is-invalid @enderror" 
                                   name="title" 
                                   value="{{ old('title', $vacancy->title) }}" 
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
                                    <option value="internship" {{ old('type', $vacancy->type) == 'internship' ? 'selected' : '' }}>Magang</option>
                                    <option value="fulltime" {{ old('type', $vacancy->type) == 'fulltime' ? 'selected' : '' }}>Full Time</option>
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
                                       value="{{ old('location', $vacancy->location) }}" 
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
                                      required>{{ old('description', $vacancy->description) }}</textarea>
                            @error('description')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Persyaratan <span class="text-danger">*</span></label>
                            <textarea class="form-control @error('requirements') is-invalid @enderror" 
                                      name="requirements" 
                                      rows="6" 
                                      required>{{ old('requirements', $vacancy->requirements) }}</textarea>
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
                                <label class="form-label">Gaji Minimum</label>
                                <div class="input-group">
                                    <span class="input-group-text">Rp</span>
                                    <input type="number" 
                                           class="form-control" 
                                           name="salary_min" 
                                           value="{{ old('salary_min', $vacancy->salary_min) }}" 
                                           min="0">
                                </div>
                            </div>

                            <div class="col-md-6 mb-3">
                                <label class="form-label">Gaji Maximum</label>
                                <div class="input-group">
                                    <span class="input-group-text">Rp</span>
                                    <input type="number" 
                                           class="form-control" 
                                           name="salary_max" 
                                           value="{{ old('salary_max', $vacancy->salary_max) }}" 
                                           min="0">
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Kuota Posisi <span class="text-danger">*</span></label>
                                <input type="number" 
                                       class="form-control @error('quota') is-invalid @enderror" 
                                       name="quota" 
                                       value="{{ old('quota', $vacancy->quota) }}" 
                                       min="1"
                                       required>
                                @error('quota')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6 mb-3">
                                <label class="form-label">Batas Akhir Lamaran <span class="text-danger">*</span></label>
                                <input type="date" 
                                       class="form-control @error('deadline') is-invalid @enderror" 
                                       name="deadline" 
                                       value="{{ old('deadline', $vacancy->deadline->format('Y-m-d')) }}" 
                                       min="{{ date('Y-m-d', strtotime('+1 day')) }}"
                                       required>
                                @error('deadline')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="form-check">
                            <input class="form-check-input" 
                                   type="checkbox" 
                                   name="is_active" 
                                   id="is_active" 
                                   value="1"
                                   {{ old('is_active', $vacancy->is_active) ? 'checked' : '' }}>
                            <label class="form-check-label" for="is_active">
                                Lowongan Aktif
                            </label>
                            <small class="d-block text-muted">Nonaktifkan jika ingin menutup lowongan sementara</small>
                        </div>
                    </div>
                </div>

                <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                    <a href="{{ route('company.vacancies') }}" class="btn btn-outline-secondary">
                        Batal
                    </a>
                    <button type="submit" class="btn btn-primary">
                        <i class="bi bi-save me-2"></i>Simpan Perubahan
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection