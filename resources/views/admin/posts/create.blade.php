@extends('layouts.admin')

@section('title', 'Buat Postingan - Admin BKK')
@section('page-title', 'Buat Postingan Baru')

@push('styles')
<style>
.category-help {
    display: none;
    background: #f8f9fa;
    border-left: 4px solid #0d6efd;
    padding: 1rem;
    margin-top: 0.5rem;
    border-radius: 4px;
}
.category-help.active {
    display: block;
}
.category-help h6 {
    color: #0d6efd;
    font-weight: 600;
    margin-bottom: 0.5rem;
}
.category-help ul {
    margin-bottom: 0;
    padding-left: 1.5rem;
}
.category-help li {
    margin-bottom: 0.25rem;
}
</style>
@endpush

@section('content')
<div class="row">
    <div class="col-lg-10 mx-auto">
        <div class="card">
            <div class="card-body p-4">
                <form method="POST" action="{{ route('admin.posts.store') }}" enctype="multipart/form-data">
                    @csrf

                    <div class="mb-3">
                        <label class="form-label">Judul <span class="text-danger">*</span></label>
                        <input type="text" 
                               class="form-control @error('title') is-invalid @enderror" 
                               name="title" 
                               value="{{ old('title') }}" 
                               required>
                        @error('title')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Kategori <span class="text-danger">*</span></label>
                            <select class="form-select @error('category') is-invalid @enderror" 
                                    name="category" 
                                    id="categorySelect"
                                    required>
                                <option value="">Pilih Kategori...</option>
                                <option value="news" {{ old('category') == 'news' ? 'selected' : '' }}>Berita</option>
                                <option value="documentation" {{ old('category') == 'documentation' ? 'selected' : '' }}>Dokumentasi</option>
                                <option value="guide" {{ old('category') == 'guide' ? 'selected' : '' }}>Panduan</option>
                                <option value="highlight" {{ old('category') == 'highlight' ? 'selected' : '' }}>Sorotan</option>
                            </select>
                            @error('category')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror

                            <!-- Category Help Text -->
                            <div id="helpNews" class="category-help">
                                <h6><i class="bi bi-newspaper me-2"></i>Format Berita</h6>
                                <ul>
                                    <li><strong>Judul:</strong> Headline berita</li>
                                    <li><strong>Featured Image:</strong> Gambar utama berita</li>
                                    <li><strong>Excerpt:</strong> Ringkasan singkat berita</li>
                                    <li><strong>Konten:</strong> Isi berita lengkap</li>
                                </ul>
                            </div>

                            <div id="helpDocumentation" class="category-help">
                                <h6><i class="bi bi-camera me-2"></i>Format Dokumentasi</h6>
                                <ul>
                                    <li><strong>Judul:</strong> Nama Event (contoh: "Job Fair 2024")</li>
                                    <li><strong>Featured Image:</strong> Cover album</li>
                                    <li><strong>Excerpt:</strong> Lokasi & tanggal event</li>
                                    <li><strong>Konten:</strong> <span class="text-danger">PENTING!</span> Upload foto-foto menggunakan fitur insert image di editor. Jangan tulis teks panjang. Foto akan otomatis menjadi gallery.</li>
                                </ul>
                            </div>

                            <div id="helpGuide" class="category-help">
                                <h6><i class="bi bi-book me-2"></i>Format Panduan</h6>
                                <ul>
                                    <li><strong>Judul:</strong> Topik panduan (contoh: "Cara Membuat CV ATS Friendly")</li>
                                    <li><strong>Excerpt:</strong> Untuk siapa panduan ini</li>
                                    <li><strong>Konten:</strong> Langkah-langkah (gunakan numbered list 1,2,3). Jika ada PDF, upload dan link di konten.</li>
                                    <li>Tampilan akan bersih tanpa tanggal/penulis (evergreen content)</li>
                                </ul>
                            </div>

                            <div id="helpHighlight" class="category-help">
                                <h6><i class="bi bi-star me-2"></i>Format Sorotan</h6>
                                <ul>
                                    <li><strong>Judul:</strong> Singkat & padat (prestasi/pengumuman)</li>
                                    <li><strong>Featured Image:</strong> Gambar poster/portrait berkualitas tinggi</li>
                                    <li><strong>Excerpt:</strong> <span class="text-danger">WAJIB!</span> Hook/kalimat penarik perhatian</li>
                                    <li><strong>Konten:</strong> Boleh kosong atau minimal (hanya link pendaftaran)</li>
                                    <li>3 terbaru tampil di beranda dengan carousel otomatis</li>
                                </ul>
                            </div>
                        </div>

                        <div class="col-md-6 mb-3">
                            <label class="form-label">Featured Image</label>
                            <input type="file" 
                                   class="form-control @error('featured_image') is-invalid @enderror" 
                                   name="featured_image" 
                                   accept="image/*">
                            <small class="text-muted">Maksimal 2MB, format: JPG, PNG</small>
                            @error('featured_image')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Excerpt (Ringkasan)</label>
                        <textarea class="form-control @error('excerpt') is-invalid @enderror" 
                                  name="excerpt" 
                                  rows="2" 
                                  id="excerptField"
                                  placeholder="Ringkasan singkat dari postingan...">{{ old('excerpt') }}</textarea>
                        <small class="text-muted" id="excerptHelp">Opsional. Akan digunakan sebagai preview.</small>
                        @error('excerpt')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Konten <span class="text-danger">*</span></label>
                        <textarea class="form-control @error('content') is-invalid @enderror" 
                                  name="content" 
                                  rows="15" 
                                  id="contentField"
                                  required>{{ old('content') }}</textarea>
                        <small class="text-muted" id="contentHelp">Isi konten sesuai format kategori yang dipilih. Untuk dokumentasi, gunakan tombol "Insert/edit image" di toolbar.</small>
                        @error('content')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <div class="form-check">
                            <input class="form-check-input" 
                                   type="checkbox" 
                                   name="is_published" 
                                   id="is_published" 
                                   value="1"
                                   {{ old('is_published') ? 'checked' : '' }}>
                            <label class="form-check-label" for="is_published">
                                Publikasikan sekarang
                            </label>
                        </div>
                        <small class="text-muted">Jika tidak dicentang, postingan akan disimpan sebagai draft</small>
                    </div>

                    <hr class="my-4">

                    <div class="d-flex justify-content-end gap-2">
                        <a href="{{ route('admin.posts.index') }}" class="btn btn-secondary">
                            <i class="bi bi-x-circle me-2"></i>Batal
                        </a>
                        <button type="submit" class="btn btn-primary">
                            <i class="bi bi-save me-2"></i>Simpan Postingan
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
(function() {
    'use strict';
    
    function initTinyMCE() {
        if (typeof tinymce === 'undefined') {
            console.error('TinyMCE tidak loaded!');
            return;
        }

        if (tinymce.get('contentField')) {
            tinymce.get('contentField').remove();
        }

        tinymce.init({
            selector: '#contentField',
            height: 500,
            promotion: false,
            branding: false,
            menubar: 'file edit view insert format tools table',
            plugins: 'advlist autolink lists link image charmap preview anchor searchreplace visualblocks code fullscreen insertdatetime media table help wordcount',
            toolbar: 'undo redo | formatselect | bold italic forecolor backcolor | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | removeformat | image media link | code',
            
            // --- BAGIAN INI SUDAH DIPERBAIKI UNTUK MENGHILANGKAN ERROR "reading 'then'" ---
            images_upload_handler: (blobInfo, progress) => {
                return new Promise((resolve, reject) => {
                    const xhr = new XMLHttpRequest();
                    xhr.withCredentials = false;
                    xhr.open('POST', '{{ route("admin.posts.upload-image") }}');

                    // Tambahkan header CSRF Token (Penting untuk Laravel)
                    xhr.setRequestHeader('X-CSRF-TOKEN', '{{ csrf_token() }}');

                    xhr.upload.onprogress = (e) => {
                        if (e.lengthComputable) {
                            progress(e.loaded / e.total * 100);
                        }
                    };

                    xhr.onload = () => {
                        if (xhr.status === 403) {
                            reject({ message: 'HTTP Error: ' + xhr.status, remove: true });
                            return;
                        }

                        if (xhr.status < 200 || xhr.status >= 300) {
                            reject('HTTP Error: ' + xhr.status);
                            return;
                        }

                        const json = JSON.parse(xhr.responseText);

                        if (!json || typeof json.location != 'string') {
                            reject('Invalid JSON: ' + xhr.responseText);
                            return;
                        }

                        // Gunakan resolve, bukan success
                        resolve(json.location);
                    };

                    xhr.onerror = () => {
                        // Gunakan reject, bukan failure
                        reject('Image upload failed due to a XHR Transport error. Code: ' + xhr.status);
                    };

                    const formData = new FormData();
                    formData.append('file', blobInfo.blob(), blobInfo.filename());
                    // Token sudah dikirim via header, tapi kalau controller Anda cek body, biarkan ini:
                    formData.append('_token', '{{ csrf_token() }}');

                    xhr.send(formData);
                });
            },
            // --- AKHIR PERBAIKAN ---

            automatic_uploads: true,
            file_picker_types: 'image',
            image_advtab: true,
            paste_data_images: true,
            relative_urls: false,
            
            setup: function(editor) {
                editor.on('init', function() {
                    console.log('✓ TinyMCE berhasil diinisialisasi');
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

    // Script Logic UI Kategori (Tidak diubah, tetap sama)
    @if(request()->routeIs('admin.posts.create'))
    document.addEventListener('DOMContentLoaded', function() {
        const categorySelect = document.getElementById('categorySelect');
        const excerptField = document.getElementById('excerptField');
        const excerptHelp = document.getElementById('excerptHelp');
        const contentHelp = document.getElementById('contentHelp');
        
        const helpPanels = {
            news: document.getElementById('helpNews'),
            documentation: document.getElementById('helpDocumentation'),
            guide: document.getElementById('helpGuide'),
            highlight: document.getElementById('helpHighlight')
        };

        function updateFormByCategory(category) {
            Object.values(helpPanels).forEach(panel => panel.classList.remove('active'));
            if (helpPanels[category]) {
                helpPanels[category].classList.add('active');
            }

            switch(category) {
                case 'documentation':
                    excerptField.placeholder = 'Contoh: Job Fair SMKN 1 Purwosari - Aula Sekolah, 15 Desember 2024';
                    excerptHelp.textContent = 'Tulis lokasi dan tanggal event.';
                    contentHelp.innerHTML = '<span class="text-danger"><strong>Penting:</strong> Klik tombol gambar di toolbar untuk upload foto.</span>';
                    break;
                case 'guide':
                    excerptField.placeholder = 'Panduan untuk siswa kelas XII';
                    excerptHelp.textContent = 'Jelaskan untuk siapa panduan ini.';
                    contentHelp.textContent = 'Tulis langkah-langkah dengan numbered list.';
                    break;
                case 'highlight':
                    excerptField.placeholder = 'Hook menarik perhatian!';
                    excerptHelp.innerHTML = '<span class="text-danger"><strong>Wajib diisi!</strong></span>';
                    contentHelp.textContent = 'Konten boleh minimal.';
                    break;
                default:
                    excerptField.placeholder = 'Ringkasan singkat...';
                    excerptHelp.textContent = 'Opsional. Preview berita.';
                    contentHelp.textContent = 'Tulis isi berita lengkap.';
            }
        }

        if(categorySelect) {
            categorySelect.addEventListener('change', function() {
                updateFormByCategory(this.value);
            });

            if (categorySelect.value) {
                updateFormByCategory(categorySelect.value);
            }
        }
    });
    @endif
})();
</script>
@endpush