{{-- resources/views/admin/companies/show.blade.php --}}
@extends('layouts.admin')

@section('title', 'Detail Perusahaan - Admin BKK')
@section('page-title', 'Detail Perusahaan')

@section('content')
<div class="mb-3">
    <a href="{{ route('admin.companies.index') }}" class="btn btn-sm btn-outline-secondary">
        <i class="bi bi-arrow-left me-2"></i>Kembali
    </a>
</div>

<div class="row">
    <div class="col-lg-8">
        <div class="card shadow-sm">
            <div class="card-body p-4">
                <div class="text-center mb-4">
                    @if($company->logo)
                        <img src="{{ Storage::url($company->logo) }}" 
                             class="rounded mb-3" 
                             style="max-height: 150px;">
                    @endif
                    <h3 class="fw-bold">{{ $company->name }}</h3>
                    <p class="text-muted">{{ $company->company_type }} - {{ $company->industry_sector }}</p>
                </div>

                @if($company->description)
                <div class="mb-4">
                    <h5 class="fw-bold mb-3">Deskripsi</h5>
                    <p>{{ $company->description }}</p>
                </div>
                @endif

                <div class="mb-4">
                    <h5 class="fw-bold mb-3">Informasi Kontak</h5>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <small class="text-muted">Email</small>
                            <p class="mb-0 fw-bold">{{ $company->user->email }}</p>
                        </div>
                        <div class="col-md-6 mb-3">
                            <small class="text-muted">Website</small>
                            <p class="mb-0 fw-bold">{{ $company->website ?? '-' }}</p>
                        </div>
                        <div class="col-12 mb-3">
                            <small class="text-muted">Alamat</small>
                            <p class="mb-0 fw-bold">{{ $company->head_office_address }}</p>
                        </div>
                        <div class="col-md-6 mb-3">
                            <small class="text-muted">PIC</small>
                            <p class="mb-0 fw-bold">{{ $company->pic_name }}</p>
                        </div>
                        <div class="col-md-6 mb-3">
                            <small class="text-muted">Telepon PIC</small>
                            <p class="mb-0 fw-bold">{{ $company->pic_phone }}</p>
                        </div>
                    </div>
                </div>

                <div>
                    <h5 class="fw-bold mb-3">Lowongan</h5>
                    @if($company->jobVacancies->count() > 0)
                        <div class="list-group">
                            @foreach($company->jobVacancies as $vacancy)
                            <div class="list-group-item">
                                <div class="d-flex justify-content-between align-items-center">
                                    <div>
                                        <h6 class="mb-1">{{ $vacancy->title }}</h6>
                                        <small class="text-muted">{{ format_date_indonesian($vacancy->created_at) }}</small>
                                    </div>
                                    <span class="badge {{ get_status_badge_class($vacancy->status) }}">
                                        {{ get_status_label($vacancy->status) }}
                                    </span>
                                </div>
                            </div>
                            @endforeach
                        </div>
                    @else
                        <p class="text-muted">Belum ada lowongan</p>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <div class="col-lg-4">
        <div class="card shadow-sm">
            <div class="card-body">
                <h6 class="fw-bold mb-3">Aksi</h6>
                <div class="d-grid gap-2">
                    <a href="{{ route('admin.companies.edit', $company->id) }}" class="btn btn-primary">
                        <i class="bi bi-pencil me-2"></i>Edit Data
                    </a>
                    <a href="{{ route('companies.show', $company->id) }}" target="_blank" class="btn btn-outline-info">
                        <i class="bi bi-eye me-2"></i>Lihat di Website
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection