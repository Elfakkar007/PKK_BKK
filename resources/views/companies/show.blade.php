@extends('layouts.app')

@section('title', $company->name . ' - BKK SMKN 1 Purwosari')

@section('content')
<div class="container py-5">
    <a href="{{ route('companies') }}" class="btn btn-sm btn-outline-secondary mb-4">
        <i class="bi bi-arrow-left me-2"></i>Kembali
    </a>

    <div class="row">
        <!-- Company Profile -->
        <div class="col-lg-8 mb-4">
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-body p-4">
                    <div class="d-flex align-items-start mb-4">
                        @if($company->logo)
                            <img src="{{ Storage::url($company->logo) }}" 
                                 alt="{{ $company->name }}" 
                                 class="rounded border me-4"
                                 style="width: 120px; height: 120px; object-fit: contain;">
                        @else
                            <div class="bg-primary text-white rounded d-flex align-items-center justify-content-center me-4" 
                                 style="width: 120px; height: 120px;">
                                <i class="bi bi-building display-4"></i>
                            </div>
                        @endif
                        
                        <div class="flex-grow-1">
                            <h2 class="fw-bold mb-2">{{ $company->name }}</h2>
                            <p class="text-muted mb-2">
                                <i class="bi bi-tag me-1"></i>{{ $company->industry_sector }}
                            </p>
                            <p class="text-muted mb-0">
                                <i class="bi bi-briefcase me-1"></i>
                                {{ $company->activeVacancies->count() }} Lowongan Aktif
                            </p>
                        </div>
                    </div>

                    @if($company->description)
                    <div class="mb-4">
                        <h5 class="fw-bold mb-3">Tentang Perusahaan</h5>
                        <p class="text-justify" style="line-height: 1.8;">
                            {{ $company->description }}
                        </p>
                    </div>
                    @endif

                    <div class="mb-4">
                        <h5 class="fw-bold mb-3">Informasi Kontak</h5>
                        <div class="row g-3">
                            <div class="col-md-6">
                                <div class="d-flex align-items-start">
                                    <div class="bg-light rounded p-2 me-3">
                                        <i class="bi bi-geo-alt text-primary fs-5"></i>
                                    </div>
                                    <div>
                                        <small class="text-muted d-block">Alamat Kantor Pusat</small>
                                        <strong>{{ $company->head_office_address }}</strong>
                                    </div>
                                </div>
                            </div>
                            
                            @if($company->branch_addresses && count($company->branch_addresses) > 0)
                            <div class="col-md-6">
                                <div class="d-flex align-items-start">
                                    <div class="bg-light rounded p-2 me-3">
                                        <i class="bi bi-building text-primary fs-5"></i>
                                    </div>
                                    <div>
                                        <small class="text-muted d-block">Alamat Cabang</small>
                                        @foreach($company->branch_addresses as $branch)
                                            <strong class="d-block">{{ $branch }}</strong>
                                        @endforeach
                                    </div>
                                </div>
                            </div>
                            @endif
                            
                            <div class="col-md-6">
                                <div class="d-flex align-items-start">
                                    <div class="bg-light rounded p-2 me-3">
                                        <i class="bi bi-person text-primary fs-5"></i>
                                    </div>
                                    <div>
                                        <small class="text-muted d-block">Penanggung Jawab</small>
                                        <strong>{{ $company->pic_name }}</strong>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="col-md-6">
                                <div class="d-flex align-items-start">
                                    <div class="bg-light rounded p-2 me-3">
                                        <i class="bi bi-phone text-primary fs-5"></i>
                                    </div>
                                    <div>
                                        <small class="text-muted d-block">Telepon/WhatsApp</small>
                                        <strong>{{ format_phone_number($company->pic_phone) }}</strong>
                                    </div>
                                </div>
                            </div>
                            
                            @if($company->pic_email)
                            <div class="col-md-6">
                                <div class="d-flex align-items-start">
                                    <div class="bg-light rounded p-2 me-3">
                                        <i class="bi bi-envelope text-primary fs-5"></i>
                                    </div>
                                    <div>
                                        <small class="text-muted d-block">Email</small>
                                        <strong>{{ $company->pic_email }}</strong>
                                    </div>
                                </div>
                            </div>
                            @endif
                            
                            @if($company->website)
                            <div class="col-md-6">
                                <div class="d-flex align-items-start">
                                    <div class="bg-light rounded p-2 me-3">
                                        <i class="bi bi-globe text-primary fs-5"></i>
                                    </div>
                                    <div>
                                        <small class="text-muted d-block">Website</small>
                                        <a href="{{ $company->website }}" target="_blank" class="fw-bold">
                                            Kunjungi Website <i class="bi bi-box-arrow-up-right"></i>
                                        </a>
                                    </div>
                                </div>
                            </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            <!-- Active Vacancies -->
            @if($company->activeVacancies->count() > 0)
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white py-3">
                    <h5 class="mb-0 fw-bold">
                        <i class="bi bi-briefcase me-2"></i>Lowongan Aktif
                    </h5>
                </div>
                <div class="card-body p-0">
                    <div class="list-group list-group-flush">
                        @foreach($company->activeVacancies as $vacancy)
                        <a href="{{ route('vacancies.show', $vacancy->id) }}" 
                           class="list-group-item list-group-item-action">
                            <div class="d-flex justify-content-between align-items-start">
                                <div class="flex-grow-1">
                                    <h6 class="mb-1 fw-bold">{{ $vacancy->title }}</h6>
                                    <p class="mb-2 text-muted small">
                                        <i class="bi bi-geo-alt"></i> {{ $vacancy->location }} &bull; 
                                        <span class="badge bg-{{ $vacancy->type === 'internship' ? 'info' : 'success' }}">
                                            {{ get_vacancy_type_label($vacancy->type) }}
                                        </span>
                                    </p>
                                    <p class="mb-0 text-muted small">
                                        <i class="bi bi-calendar-event"></i> 
                                        Deadline: {{ format_date_indonesian($vacancy->deadline) }}
                                    </p>
                                </div>
                                <div class="text-end ms-3">
                                    <div class="badge bg-light text-dark">
                                        {{ $vacancy->remainingQuota() }} / {{ $vacancy->quota }} posisi
                                    </div>
                                </div>
                            </div>
                        </a>
                        @endforeach
                    </div>
                </div>
            </div>
            @endif
        </div>

        <!-- Sidebar -->
        <div class="col-lg-4">
            <!-- Quick Stats -->
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-body">
                    <h6 class="fw-bold mb-3">Statistik</h6>
                    <div class="d-flex justify-content-between align-items-center mb-3 pb-3 border-bottom">
                        <div>
                            <i class="bi bi-briefcase text-primary me-2"></i>
                            <span>Lowongan Aktif</span>
                        </div>
                        <h5 class="mb-0 text-primary">{{ $company->activeVacancies->count() }}</h5>
                    </div>
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <i class="bi bi-clock-history text-muted me-2"></i>
                            <span>Bergabung</span>
                        </div>
                        <span class="text-muted">{{ $company->created_at->format('Y') }}</span>
                    </div>
                </div>
            </div>

            <!-- Call to Action -->
            @guest
            <div class="card border-0 shadow-sm bg-primary text-white">
                <div class="card-body text-center">
                    <i class="bi bi-briefcase display-4 mb-3"></i>
                    <h5 class="fw-bold mb-3">Tertarik Bekerja di Sini?</h5>
                    <p class="mb-3">Daftar sekarang untuk melamar lowongan dari perusahaan ini</p>
                    <a href="{{ route('register.student') }}" class="btn btn-light w-100">
                        <i class="bi bi-person-plus me-2"></i>Daftar Sekarang
                    </a>
                </div>
            </div>
            @endguest
        </div>
    </div>
</div>
@endsection