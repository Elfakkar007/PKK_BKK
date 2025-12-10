@extends('layouts.app')

@section('title', 'Profile Perusahaan - BKK SMKN 1 Purwosari')

@section('content')
<div class="container py-5">
    <div class="row">
        <div class="col-lg-10 mx-auto">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h2 class="fw-bold mb-0">Profile Perusahaan</h2>
                <a href="{{ route('company.dashboard') }}" class="btn btn-outline-secondary">
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

            <form method="POST" action="{{ route('company.profile.update') }}" enctype="multipart/form-data">
                @csrf
                @method('PUT')

                <!-- Logo Section -->
                <div class="card border-0 shadow-sm mb-4">
                    <div class="card-body p-4">
                        <h5 class="fw-bold mb-3">Logo Perusahaan</h5>
                        
                        <div class="text-center mb-3">
                            @if($company->logo)
                                <img src="{{ Storage::url($company->logo) }}" 
                                     alt="Logo Perusahaan" 
                                     class="img-fluid rounded mb-3"
                                     id="logoPreview"
                                     style="max-height: 150px;">
                            @else
                                <div class="bg-secondary text-white rounded d-inline-flex align-items-center justify-content-center mb-3"
                                     id="logoPreview" 
                                     style="width: 150px; height: 150px;">
                                    <i class="bi bi-building display-4"></i>
                                </div>
                            @endif
                        </div>
                        
                        <div class="text-center">
                            <label for="logo" class="btn btn-sm btn-outline-primary">
                                <i class="bi bi-upload me-2"></i>Ubah Logo
                            </label>
                            <input type="file" 
                                   id="logo" 
                                   name="logo" 
                                   class="d-none" 
                                   accept="image/*"
                                   onchange="previewLogo(this)">
                            <p class="text-muted small mt-2 mb-0">Maksimal 2MB, format: JPG, PNG</p>
                        </div>
                    </div>
                </div>

                <!-- Company Information -->
                <div class="card border-0 shadow-sm mb-4">
                    <div class="card-body p-4">
                        <h5 class="fw-bold mb-3">Informasi Perusahaan</h5>

                        <div class="row">
                            <div class="col-md-8 mb-3">
                                <label class="form-label">Nama Perusahaan <span class="text-danger">*</span></label>
                                <input type="text" 
                                       class="form-control @error('name') is-invalid @enderror" 
                                       name="name" 
                                       value="{{ old('name', $company->name) }}" 
                                       required>
                                @error('name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-4 mb-3">
                                <label class="form-label">Tipe Perusahaan <span class="text-danger">*</span></label>
                                <select class="form-select @error('company_type') is-invalid @enderror" 
                                        name="company_type" 
                                        required>
                                    <option value="">Pilih...</option>
                                    <option value="PT" {{ old('company_type', $company->company_type) == 'PT' ? 'selected' : '' }}>PT</option>
                                    <option value="CV" {{ old('company_type', $company->company_type) == 'CV' ? 'selected' : '' }}>CV</option>
                                    <option value="UD" {{ old('company_type', $company->company_type) == 'UD' ? 'selected' : '' }}>UD</option>
                                    <option value="Koperasi" {{ old('company_type', $company->company_type) == 'Koperasi' ? 'selected' : '' }}>Koperasi</option>
                                    <option value="Yayasan" {{ old('company_type', $company->company_type) == 'Yayasan' ? 'selected' : '' }}>Yayasan</option>
                                </select>
                                @error('company_type')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Sektor Industri <span class="text-danger">*</span></label>
                            <select class="form-select @error('industry_sector') is-invalid @enderror" 
                                    name="industry_sector" 
                                    required>
                                <option value="">Pilih...</option>
                                <option value="Manufaktur" {{ old('industry_sector', $company->industry_sector) == 'Manufaktur' ? 'selected' : '' }}>Manufaktur</option>
                                <option value="Teknologi Informasi" {{ old('industry_sector', $company->industry_sector) == 'Teknologi Informasi' ? 'selected' : '' }}>Teknologi Informasi</option>
                                <option value="Otomotif" {{ old('industry_sector', $company->industry_sector) == 'Otomotif' ? 'selected' : '' }}>Otomotif</option>
                                <option value="Perhotelan" {{ old('industry_sector', $company->industry_sector) == 'Perhotelan' ? 'selected' : '' }}>Perhotelan</option>
                                <option value="Pendidikan" {{ old('industry_sector', $company->industry_sector) == 'Pendidikan' ? 'selected' : '' }}>Pendidikan</option>
                                <option value="Kesehatan" {{ old('industry_sector', $company->industry_sector) == 'Kesehatan' ? 'selected' : '' }}>Kesehatan</option>
                                <option value="Retail" {{ old('industry_sector', $company->industry_sector) == 'Retail' ? 'selected' : '' }}>Retail</option>
                                <option value="F&B" {{ old('industry_sector', $company->industry_sector) == 'F&B' ? 'selected' : '' }}>Food & Beverage</option>
                                <option value="Konstruksi" {{ old('industry_sector', $company->industry_sector) == 'Konstruksi' ? 'selected' : '' }}>Konstruksi</option>
                                <option value="Lainnya" {{ old('industry_sector', $company->industry_sector) == 'Lainnya' ? 'selected' : '' }}>Lainnya</option>
                            </select>
                            @error('industry_sector')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Deskripsi Perusahaan</label>
                            <textarea class="form-control @error('description') is-invalid @enderror" 
                                      name="description" 
                                      rows="4" 
                                      placeholder="Jelaskan tentang perusahaan Anda...">{{ old('description', $company->description) }}</textarea>
                            @error('description')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Website</label>
                            <input type="url" 
                                   class="form-control @error('website') is-invalid @enderror" 
                                   name="website" 
                                   value="{{ old('website', $company->website) }}"
                                   placeholder="https://www.contoh.com">
                            @error('website')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>

                <!-- Address Information -->
                <div class="card border-0 shadow-sm mb-4">
                    <div class="card-body p-4">
                        <h5 class="fw-bold mb-3">Alamat</h5>

                        <div class="mb-3">
                            <label class="form-label">Alamat Kantor Pusat <span class="text-danger">*</span></label>
                            <textarea class="form-control @error('head_office_address') is-invalid @enderror" 
                                      name="head_office_address" 
                                      rows="3" 
                                      required>{{ old('head_office_address', $company->head_office_address) }}</textarea>
                            @error('head_office_address')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div id="branchesContainer">
                            <label class="form-label">Alamat Cabang (Opsional)</label>
                            @if($company->branch_addresses && count($company->branch_addresses) > 0)
                                @foreach($company->branch_addresses as $index => $branch)
                                <div class="input-group mb-2">
                                    <input type="text" 
                                           class="form-control" 
                                           name="branch_addresses[]" 
                                           value="{{ $branch }}"
                                           placeholder="Alamat cabang {{ $index + 1 }}">
                                    <button type="button" class="btn btn-outline-danger" onclick="removeBranch(this)">
                                        <i class="bi bi-trash"></i>
                                    </button>
                                </div>
                                @endforeach
                            @else
                                <div class="input-group mb-2">
                                    <input type="text" 
                                           class="form-control" 
                                           name="branch_addresses[]" 
                                           placeholder="Alamat cabang 1">
                                    <button type="button" class="btn btn-outline-danger" onclick="removeBranch(this)">
                                        <i class="bi bi-trash"></i>
                                    </button>
                                </div>
                            @endif
                        </div>
                        <button type="button" class="btn btn-sm btn-outline-primary" onclick="addBranch()">
                            <i class="bi bi-plus-circle me-2"></i>Tambah Cabang
                        </button>
                    </div>
                </div>

                <!-- Contact Person -->
                <div class="card border-0 shadow-sm mb-4">
                    <div class="card-body p-4">
                        <h5 class="fw-bold mb-3">Penanggung Jawab (PIC)</h5>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Nama PIC <span class="text-danger">*</span></label>
                                <input type="text" 
                                       class="form-control @error('pic_name') is-invalid @enderror" 
                                       name="pic_name" 
                                       value="{{ old('pic_name', $company->pic_name) }}" 
                                       required>
                                @error('pic_name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6 mb-3">
                                <label class="form-label">No. Telepon/WhatsApp <span class="text-danger">*</span></label>
                                <input type="text" 
                                       class="form-control @error('pic_phone') is-invalid @enderror" 
                                       name="pic_phone" 
                                       value="{{ old('pic_phone', $company->pic_phone) }}" 
                                       placeholder="08xxxxxxxxxx"
                                       required>
                                @error('pic_phone')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Email PIC</label>
                            <input type="email" 
                                   class="form-control @error('pic_email') is-invalid @enderror" 
                                   name="pic_email" 
                                   value="{{ old('pic_email', $company->pic_email) }}">
                            @error('pic_email')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>

                <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                    <a href="{{ route('company.dashboard') }}" class="btn btn-outline-secondary">
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

@push('scripts')
<script>
function previewLogo(input) {
    if (input.files && input.files[0]) {
        const reader = new FileReader();
        reader.onload = function(e) {
            document.getElementById('logoPreview').innerHTML = 
                '<img src="' + e.target.result + '" class="img-fluid rounded" style="max-height: 150px;">';
        }
        reader.readAsDataURL(input.files[0]);
    }
}

function addBranch() {
    const container = document.getElementById('branchesContainer');
    const count = container.querySelectorAll('input').length + 1;
    const div = document.createElement('div');
    div.className = 'input-group mb-2';
    div.innerHTML = `
        <input type="text" class="form-control" name="branch_addresses[]" placeholder="Alamat cabang ${count}">
        <button type="button" class="btn btn-outline-danger" onclick="removeBranch(this)">
            <i class="bi bi-trash"></i>
        </button>
    `;
    container.appendChild(div);
}

function removeBranch(button) {
    button.closest('.input-group').remove();
}
</script>
@endpush
@endsection