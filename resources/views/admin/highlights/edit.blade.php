@extends('layouts.admin')

@section('title', 'Edit Highlight - Admin BKK')
@section('page-title', 'Edit Highlight')

@section('content')
<div class="row">
    <div class="col-lg-8 mx-auto">
        <div class="card">
            <div class="card-body p-4">
                <form method="POST" action="{{ route('admin.highlights.update', $highlight->id) }}" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')

                    <div class="mb-3">
                        <label class="form-label">Judul <span class="text-danger">*</span></label>
                        <input type="text" 
                               class="form-control @error('title') is-invalid @enderror" 
                               name="title" 
                               value="{{ old('title', $highlight->title) }}" 
                               required>
                        @error('title')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Deskripsi</label>
                        <textarea class="form-control @error('description') is-invalid @enderror" 
                                  name="description" 
                                  rows="3">{{ old('description', $highlight->description) }}</textarea>
                        @error('description')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Gambar</label>
                        @if($highlight->image)
                            <div class="mb-2">
                                <img src="{{ Storage::url($highlight->image) }}" 
                                     class="img-thumbnail" 
                                     style="max-height: 150px;">
                            </div>
                        @endif
                        <input type="file" 
                               class="form-control @error('image') is-invalid @enderror" 
                               name="image" 
                               accept="image/*">
                        <small class="text-muted">Kosongkan jika tidak ingin mengubah. Maksimal 2MB</small>
                        @error('image')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Link (Opsional)</label>
                        <input type="url" 
                               class="form-control @error('link') is-invalid @enderror" 
                               name="link" 
                               value="{{ old('link', $highlight->link) }}"
                               placeholder="https://...">
                        @error('link')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Urutan <span class="text-danger">*</span></label>
                            <input type="number" 
                                   class="form-control @error('order') is-invalid @enderror" 
                                   name="order" 
                                   value="{{ old('order', $highlight->order) }}" 
                                   min="0"
                                   required>
                            <small class="text-muted">Angka lebih kecil muncul lebih dulu</small>
                            @error('order')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-6 mb-3">
                            <label class="form-label">Status</label>
                            <div class="form-check form-switch mt-2">
                                <input class="form-check-input" 
                                       type="checkbox" 
                                       name="is_active" 
                                       id="is_active" 
                                       value="1"
                                       {{ old('is_active', $highlight->is_active) ? 'checked' : '' }}>
                                <label class="form-check-label" for="is_active">
                                    Aktif
                                </label>
                            </div>
                        </div>
                    </div>

                    <hr class="my-4">

                    <div class="d-flex justify-content-end gap-2">
                        <a href="{{ route('admin.highlights.index') }}" class="btn btn-secondary">
                            <i class="bi bi-x-circle me-2"></i>Batal
                        </a>
                        <button type="submit" class="btn btn-primary">
                            <i class="bi bi-save me-2"></i>Update Highlight
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection