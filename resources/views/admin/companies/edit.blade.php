@extends('layouts.admin')

@section('title', 'Edit Perusahaan - Admin BKK')
@section('page-title', 'Edit Data Perusahaan')

@section('content')
<div class="row">
    <div class="col-lg-8 mx-auto">
        <div class="card">
            <div class="card-body p-4">
                <form method="POST" action="{{ route('admin.companies.update', $company->id) }}">
                    @csrf
                    @method('PUT')

                    <div class="mb-3">
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

                    <div class="row">
                        <div class="col-md-6 mb-3">
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

                        <div class="col-md-6 mb-3">
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
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Deskripsi</label>
                        <textarea class="form-control @error('description') is-invalid @enderror" 
                                  name="description" 
                                  rows="4">{{ old('description', $company->description) }}</textarea>
                        @error('description')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="alert alert-info">
                        <i class="bi bi-info-circle me-2"></i>
                        <strong>Catatan:</strong> Data lainnya seperti alamat, kontak PIC, dan logo dapat diubah oleh perusahaan sendiri melalui halaman profile mereka.
                    </div>

                    <hr class="my-4">

                    <div class="d-flex justify-content-end gap-2">
                        <a href="{{ route('admin.companies.show', $company->id) }}" class="btn btn-secondary">
                            <i class="bi bi-x-circle me-2"></i>Batal
                        </a>
                        <button type="submit" class="btn btn-primary">
                            <i class="bi bi-save me-2"></i>Update Data
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection