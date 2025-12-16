@extends('layouts.admin')

@section('title', 'Edit Jurusan - Admin BKK')
@section('page-title', 'Edit Jurusan')

@section('content')
<div class="row justify-content-center">
    <div class="col-lg-8">
        <div class="card">
            <div class="card-header bg-white">
                <h5 class="mb-0 fw-bold">
                    <i class="bi bi-pencil-square me-2"></i>Edit Jurusan: {{ $major->name }}
                </h5>
            </div>
            <div class="card-body">
                <form method="POST" action="{{ route('admin.majors.update', $major->id) }}">
                    @csrf
                    @method('PUT')
                    
                    <div class="row">
                        <div class="col-md-4 mb-3">
                            <label class="form-label fw-bold">
                                Kode Jurusan <span class="text-danger">*</span>
                            </label>
                            <input type="text" 
                                   class="form-control @error('code') is-invalid @enderror" 
                                   name="code" 
                                   value="{{ old('code', $major->code) }}"
                                   placeholder="Contoh: TKJ"
                                   style="text-transform: uppercase;"
                                   required>
                            @error('code')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <small class="text-muted">Maksimal 10 karakter, harus unik</small>
                        </div>
                        
                        <div class="col-md-8 mb-3">
                            <label class="form-label fw-bold">
                                Nama Jurusan <span class="text-danger">*</span>
                            </label>
                            <input type="text" 
                                   class="form-control @error('name') is-invalid @enderror" 
                                   name="name" 
                                   value="{{ old('name', $major->name) }}"
                                   placeholder="Contoh: Teknik Komputer dan Jaringan"
                                   required>
                            @error('name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label fw-bold">Deskripsi</label>
                        <textarea class="form-control @error('description') is-invalid @enderror" 
                                  name="description" 
                                  rows="3"
                                  placeholder="Deskripsi singkat tentang jurusan ini (opsional)">{{ old('description', $major->description) }}</textarea>
                        @error('description')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-bold">Urutan Tampilan</label>
                            <input type="number" 
                                   class="form-control @error('order') is-invalid @enderror" 
                                   name="order" 
                                   value="{{ old('order', $major->order) }}"
                                   min="0">
                            @error('order')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <small class="text-muted">Semakin kecil angka, semakin atas urutannya</small>
                        </div>
                        
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-bold">Status</label>
                            <div class="form-check form-switch mt-2">
                                <input class="form-check-input" 
                                       type="checkbox" 
                                       name="is_active" 
                                       id="is_active"
                                       {{ old('is_active', $major->is_active) ? 'checked' : '' }}>
                                <label class="form-check-label" for="is_active">
                                    Aktif (ditampilkan di form registrasi)
                                </label>
                            </div>
                        </div>
                    </div>
                    
                    <hr>
                    
                    <div class="d-flex gap-2 justify-content-end">
                        <a href="{{ route('admin.majors.index') }}" class="btn btn-secondary">
                            <i class="bi bi-x-circle me-1"></i>Batal
                        </a>
                        <button type="submit" class="btn btn-primary">
                            <i class="bi bi-check-circle me-1"></i>Simpan Perubahan
                        </button>
                    </div>
                </form>
            </div>
        </div>
        
        <div class="card mt-4">
            <div class="card-body">
                <h6 class="fw-bold mb-3">
                    <i class="bi bi-info-circle me-2"></i>Informasi
                </h6>
                <ul class="small text-muted mb-0">
                    <li>Jurusan ini digunakan oleh <strong>{{ $major->students()->count() }} siswa/alumni</strong>.</li>
                    <li>Mengubah kode jurusan tidak akan mengubah data siswa yang sudah terdaftar.</li>
                    <li>Jika dinonaktifkan, jurusan tidak akan muncul di form registrasi baru.</li>
                </ul>
            </div>
        </div>
    </div>
</div>
@endsection