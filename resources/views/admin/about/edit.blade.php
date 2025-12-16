@extends('layouts.admin')

@section('title', 'Edit Halaman Tentang - Admin BKK')
@section('page-title', 'Edit Halaman Tentang BKK')

@section('content')
<div class="row">
    <div class="col-lg-10 mx-auto">
        <div class="card">
            <div class="card-body p-4">
                <form method="POST" action="{{ route('admin.about.update') }}" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')

                    <!-- Visi -->
                    <div class="mb-4">
                        <h5 class="fw-bold mb-3">Visi</h5>
                        <textarea class="form-control @error('vision') is-invalid @enderror" 
                                  name="vision" 
                                  rows="4" 
                                  placeholder="Tuliskan visi BKK...">{{ old('vision', $about->vision ?? '') }}</textarea>
                        @error('vision')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Misi dengan TinyMCE -->
                    <div class="mb-4">
                        <h5 class="fw-bold mb-3">Misi</h5>
                        <textarea class="form-control @error('mission') is-invalid @enderror" 
                                  name="mission" 
                                  id="missionEditor"
                                  rows="6" 
                                  placeholder="Tuliskan misi BKK...">{{ old('mission', $about->mission ?? '') }}</textarea>
                        @error('mission')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                        <small class="text-muted">Gunakan editor untuk memformat teks (bold, list, dll)</small>
                    </div>

                    <!-- Program Kerja -->
                    <div class="mb-4">
                        <h5 class="fw-bold mb-3">Program Kerja</h5>
                        <div id="programsContainer">
                            @php
                                $workPrograms = old('work_programs', $about->work_programs ?? []);
                                if(empty($workPrograms)) $workPrograms = [''];
                            @endphp
                            @foreach($workPrograms as $index => $program)
                            <div class="input-group mb-2">
                                <span class="input-group-text">{{ $index + 1 }}</span>
                                <input type="text" 
                                       class="form-control" 
                                       name="work_programs[]" 
                                       value="{{ is_array($program) ? ($program['title'] ?? '') : $program }}"
                                       placeholder="Nama program kerja">
                                <button type="button" class="btn btn-outline-danger" onclick="removeProgram(this)">
                                    <i class="bi bi-trash"></i>
                                </button>
                            </div>
                            @endforeach
                        </div>
                        <button type="button" class="btn btn-sm btn-outline-primary" onclick="addProgram()">
                            <i class="bi bi-plus-circle me-2"></i>Tambah Program
                        </button>
                    </div>

                    <!-- Struktur Organisasi -->
                    <div class="mb-4">
                        <h5 class="fw-bold mb-3">Struktur Organisasi</h5>
                        @if($about->organization_chart ?? false)
                            <div class="mb-3">
                                <img src="{{ Storage::url($about->organization_chart) }}" 
                                     class="img-thumbnail" 
                                     style="max-height: 200px;">
                            </div>
                        @endif
                        <input type="file" 
                               class="form-control @error('organization_chart') is-invalid @enderror" 
                               name="organization_chart" 
                               accept="image/*">
                        <small class="text-muted">Upload gambar struktur organisasi (JPG, PNG). Maksimal 5MB</small>
                        @error('organization_chart')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Email Kontak -->
                    <div class="mb-4">
                        <h5 class="fw-bold mb-3">
                            <i class="bi bi-envelope me-2"></i>Email Tujuan Form Kontak
                        </h5>
                        <div class="alert alert-info">
                            <i class="bi bi-info-circle me-2"></i>
                            Email ini akan menerima pesan dari pengunjung yang mengisi form kontak di halaman Tentang BKK
                        </div>
                        <input type="email" 
                               class="form-control @error('contact_email') is-invalid @enderror" 
                               name="contact_email" 
                               value="{{ old('contact_email', $about->contact_email ?? '') }}"
                               placeholder="contoh: admin@smkn1purwosari.sch.id">
                        @error('contact_email')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                        <small class="text-muted">
                            Pastikan email ini aktif dan dapat menerima email dari sistem
                        </small>
                    </div>

                    <hr class="my-4">

                    <div class="d-flex justify-content-end gap-2">
                        <a href="{{ route('admin.dashboard') }}" class="btn btn-secondary">
                            <i class="bi bi-x-circle me-2"></i>Batal
                        </a>
                        <button type="submit" class="btn btn-primary">
                            <i class="bi bi-save me-2"></i>Simpan Perubahan
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <!-- Preview Button -->
        <div class="text-center mt-3">
            <a href="{{ route('about') }}" target="_blank" class="btn btn-outline-info">
                <i class="bi bi-eye me-2"></i>Preview Halaman Tentang
            </a>
        </div>
    </div>
</div>

@push('scripts')
<script>
// Initialize TinyMCE for Mission field
(function() {
    'use strict';
    
    function initTinyMCE() {
        if (typeof tinymce === 'undefined') {
            console.error('TinyMCE tidak loaded!');
            return;
        }

        if (tinymce.get('missionEditor')) {
            tinymce.get('missionEditor').remove();
        }

        tinymce.init({
            selector: '#missionEditor',
            height: 400,
            promotion: false,
            branding: false,
            menubar: 'edit view insert format',
            plugins: 'advlist autolink lists link charmap preview anchor searchreplace visualblocks code fullscreen insertdatetime help wordcount',
            toolbar: 'undo redo | formatselect | bold italic forecolor backcolor | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | removeformat | help',
            
            // Simplified config - no image upload needed for mission text
            automatic_uploads: false,
            
            setup: function(editor) {
                editor.on('init', function() {
                    console.log('✓ TinyMCE Mission Editor berhasil diinisialisasi');
                });
                editor.on('change', function() {
                    editor.save();
                });
            }
        });
    }

    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', initTinyMCE);
    } else {
        initTinyMCE();
    }
})();

// Program Kerja functions
function addProgram() {
    const container = document.getElementById('programsContainer');
    const count = container.querySelectorAll('input').length + 1;
    const div = document.createElement('div');
    div.className = 'input-group mb-2';
    div.innerHTML = `
        <span class="input-group-text">${count}</span>
        <input type="text" class="form-control" name="work_programs[]" placeholder="Nama program kerja">
        <button type="button" class="btn btn-outline-danger" onclick="removeProgram(this)">
            <i class="bi bi-trash"></i>
        </button>
    `;
    container.appendChild(div);
    updateProgramNumbers();
}

function removeProgram(button) {
    button.closest('.input-group').remove();
    updateProgramNumbers();
}

function updateProgramNumbers() {
    const container = document.getElementById('programsContainer');
    const groups = container.querySelectorAll('.input-group');
    groups.forEach((group, index) => {
        group.querySelector('.input-group-text').textContent = index + 1;
    });
}
</script>
@endpush
@endsection