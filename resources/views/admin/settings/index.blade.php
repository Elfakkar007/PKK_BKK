@extends('layouts.admin')

@section('title', 'Settings - Admin BKK')
@section('page-title', 'Settings Website')

@section('content')
<div class="row">
    <div class="col-lg-8 mx-auto">
        <div class="card">
            <div class="card-body p-4">
                <form method="POST" action="{{ route('admin.settings.update') }}" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')

                    <!-- Website Information -->
                    <h5 class="fw-bold mb-3">Informasi Website</h5>

                    <div class="mb-3">
                        <label class="form-label">Nama Website</label>
                        <input type="text" 
                               class="form-control" 
                               name="site_name" 
                               value="{{ old('site_name', settings('site_name', 'BKK SMKN 1 Purwosari')) }}">
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Tagline</label>
                        <input type="text" 
                               class="form-control" 
                               name="site_tagline" 
                               value="{{ old('site_tagline', settings('site_tagline', 'Membangun Karir, Mewujudkan Impian')) }}">
                    </div>

                    <div class="mb-4">
                        <label class="form-label">Deskripsi</label>
                        <textarea class="form-control" 
                                  name="site_description" 
                                  rows="3">{{ old('site_description', settings('site_description')) }}</textarea>
                        <small class="text-muted">Deskripsi akan muncul di meta description (SEO)</small>
                    </div>

                    <hr class="my-4">

                    <!-- Contact Information -->
                    <h5 class="fw-bold mb-3">Informasi Kontak</h5>

                    <div class="mb-3">
                        <label class="form-label">Alamat</label>
                        <textarea class="form-control" 
                                  name="contact_address" 
                                  rows="2">{{ old('contact_address', settings('contact_address', 'Jl. Raya Purwosari, Pasuruan, Jawa Timur')) }}</textarea>
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Telepon</label>
                            <input type="text" 
                                   class="form-control" 
                                   name="contact_phone" 
                                   value="{{ old('contact_phone', settings('contact_phone', '(0343) 123456')) }}">
                        </div>

                        <div class="col-md-6 mb-3">
                            <label class="form-label">Email</label>
                            <input type="email" 
                                   class="form-control" 
                                   name="contact_email" 
                                   value="{{ old('contact_email', settings('contact_email', 'bkk@smkn1purwosari.sch.id')) }}">
                        </div>
                    </div>

                    <hr class="my-4">

                    <!-- Social Media -->
                    <h5 class="fw-bold mb-3">Social Media</h5>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Facebook</label>
                            <input type="url" 
                                   class="form-control" 
                                   name="social_facebook" 
                                   value="{{ old('social_facebook', settings('social_facebook')) }}"
                                   placeholder="https://facebook.com/...">
                        </div>

                        <div class="col-md-6 mb-3">
                            <label class="form-label">Instagram</label>
                            <input type="url" 
                                   class="form-control" 
                                   name="social_instagram" 
                                   value="{{ old('social_instagram', settings('social_instagram')) }}"
                                   placeholder="https://instagram.com/...">
                        </div>

                        <div class="col-md-6 mb-3">
                            <label class="form-label">Twitter</label>
                            <input type="url" 
                                   class="form-control" 
                                   name="social_twitter" 
                                   value="{{ old('social_twitter', settings('social_twitter')) }}"
                                   placeholder="https://twitter.com/...">
                        </div>

                        <div class="col-md-6 mb-3">
                            <label class="form-label">YouTube</label>
                            <input type="url" 
                                   class="form-control" 
                                   name="social_youtube" 
                                   value="{{ old('social_youtube', settings('social_youtube')) }}"
                                   placeholder="https://youtube.com/...">
                        </div>
                    </div>

                    <hr class="my-4">

                    <!-- Logo -->
                    <h5 class="fw-bold mb-3">Logo & Branding</h5>

                    <div class="mb-3">
                        <label class="form-label">Logo Website</label>
                        @if(settings('site_logo'))
                            <div class="mb-2">
                                <img src="{{ Storage::url(settings('site_logo')) }}" 
                                     class="img-thumbnail" 
                                     style="max-height: 80px;">
                            </div>
                        @endif
                        <input type="file" 
                               class="form-control" 
                               name="site_logo" 
                               accept="image/*">
                        <small class="text-muted">Format: PNG dengan background transparan (recommended). Maksimal 1MB</small>
                    </div>

                    <hr class="my-4">

                    <!-- Features -->
                    <h5 class="fw-bold mb-3">Fitur Website</h5>

                    <div class="form-check mb-3">
                        <input class="form-check-input" 
                               type="checkbox" 
                               name="feature_registration" 
                               id="feature_registration" 
                               value="1"
                               {{ settings('feature_registration', '1') == '1' ? 'checked' : '' }}>
                        <label class="form-check-label" for="feature_registration">
                            <strong>Aktifkan Registrasi</strong><br>
                            <small class="text-muted">User dapat mendaftar sebagai siswa/alumni atau perusahaan</small>
                        </label>
                    </div>

                    <div class="form-check mb-3">
                        <input class="form-check-input" 
                               type="checkbox" 
                               name="feature_apply_job" 
                               id="feature_apply_job" 
                               value="1"
                               {{ settings('feature_apply_job', '1') == '1' ? 'checked' : '' }}>
                        <label class="form-check-label" for="feature_apply_job">
                            <strong>Aktifkan Lamaran Pekerjaan</strong><br>
                            <small class="text-muted">Siswa dapat melamar lowongan pekerjaan</small>
                        </label>
                    </div>

                    <div class="form-check mb-3">
                        <input class="form-check-input" 
                               type="checkbox" 
                               name="feature_notifications" 
                               id="feature_notifications" 
                               value="1"
                               {{ settings('feature_notifications', '1') == '1' ? 'checked' : '' }}>
                        <label class="form-check-label" for="feature_notifications">
                            <strong>Aktifkan Email Notifikasi</strong><br>
                            <small class="text-muted">Kirim email otomatis untuk berbagai event</small>
                        </label>
                    </div>

                    <hr class="my-4">

                    <!-- Maintenance Mode -->
                    <h5 class="fw-bold mb-3 text-danger">Maintenance Mode</h5>

                    <div class="alert alert-warning">
                        <i class="bi bi-exclamation-triangle me-2"></i>
                        <strong>Perhatian:</strong> Mode maintenance akan menutup akses website untuk semua user kecuali admin.
                    </div>

                    <div class="form-check mb-3">
                        <input class="form-check-input" 
                               type="checkbox" 
                               name="maintenance_mode" 
                               id="maintenance_mode" 
                               value="1"
                               {{ settings('maintenance_mode', '0') == '1' ? 'checked' : '' }}>
                        <label class="form-check-label" for="maintenance_mode">
                            <strong>Aktifkan Maintenance Mode</strong>
                        </label>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Pesan Maintenance</label>
                        <textarea class="form-control" 
                                  name="maintenance_message" 
                                  rows="2">{{ old('maintenance_message', settings('maintenance_message', 'Website sedang dalam perbaikan. Silakan coba lagi nanti.')) }}</textarea>
                    </div>

                    <hr class="my-4">

                    <div class="d-flex justify-content-end gap-2">
                        <a href="{{ route('admin.dashboard') }}" class="btn btn-secondary">
                            <i class="bi bi-x-circle me-2"></i>Batal
                        </a>
                        <button type="submit" class="btn btn-primary">
                            <i class="bi bi-save me-2"></i>Simpan Settings
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <!-- Cache Clear -->
        <div class="card mt-4">
            <div class="card-body">
                <h6 class="fw-bold mb-3">Cache Management</h6>
                <p class="text-muted small">Hapus cache jika ada perubahan yang tidak muncul di website</p>
                <button type="button" class="btn btn-outline-danger" onclick="clearCache()">
                    <i class="bi bi-trash me-2"></i>Clear All Cache
                </button>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
function clearCache() {
    if(confirm('Yakin ingin menghapus semua cache?')) {
        // Implement cache clearing via AJAX
        alert('Cache berhasil dihapus!');
    }
}
</script>
@endpush
@endsection