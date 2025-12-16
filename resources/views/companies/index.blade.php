@extends('layouts.app')

@section('title', 'Perusahaan Mitra')

@section('content')
<!-- Page Header -->
<section class="bg-light py-5">
    <div class="container">
        <h1 class="fw-bold mb-3">Perusahaan Mitra</h1>
        <p class="lead text-muted mb-0">
            Jelajahi perusahaan-perusahaan terpercaya yang bekerja sama dengan BKK SMKN 1 Purwosari
        </p>
    </div>
</section>

<!-- Filter & Search -->
<section class="py-4 bg-white border-bottom">
    <div class="container">
        <form method="GET" action="{{ route('companies') }}">
            <div class="row g-3">
                <div class="col-md-4">
                    <select name="sector" class="form-select" onchange="this.form.submit()">
                        <option value="">Semua Sektor</option>
                        @foreach($sectors as $sectorItem)
                            <option value="{{ $sectorItem }}" {{ request('sector') == $sectorItem ? 'selected' : '' }}>
                                {{ $sectorItem }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-6">
                    <input type="text" 
                           name="search" 
                           class="form-control" 
                           placeholder="Cari perusahaan..." 
                           value="{{ request('search') }}">
                </div>
                <div class="col-md-2">
                    <button type="submit" class="btn btn-primary w-100">
                        <i class="bi bi-search"></i> Cari
                    </button>
                </div>
            </div>
        </form>
    </div>
</section>

<!-- Companies Grid -->
<section class="py-5">
    <div class="container">
        <div class="mb-4">
            <h5 class="mb-0">
                Menampilkan {{ $companies->total() }} perusahaan
                @if(request('search'))
                    untuk "{{ request('search') }}"
                @endif
            </h5>
        </div>

        @if($companies->count() > 0)
        <div class="row g-4">
            @foreach($companies as $company)
            <div class="col-md-6 col-lg-4">
                <div class="card h-100 border-0 shadow-sm">
                    <div class="card-body text-center">
                        @if($company->logo)
                            <div class="d-flex justify-content-center align-items-center mb-3" style="height: 120px; width: 100%;">
                                <img src="{{ Storage::url($company->logo) }}" 
                                     alt="{{ $company->name }}" 
                                     class="img-fluid rounded"
                                     style="max-height: 120px; max-width: 90%; object-fit: contain; object-position: center;">
                            </div>
                        @else
                            <div class="bg-primary text-white rounded d-inline-flex align-items-center justify-content-center mb-3" 
                                 style="width: 120px; height: 120px;">
                                <i class="bi bi-building display-4"></i>
                            </div>
                        @endif
                        
                        <h5 class="fw-bold mb-2">{{ $company->name }}</h5>
                        <p class="text-muted small mb-3">
                            <i class="bi bi-tag"></i> {{ $company->industry_sector }}
                        </p>
                        
                        @if($company->description)
                            <p class="text-muted small mb-3">
                                {{ Str::limit($company->description, 100) }}
                            </p>
                        @endif
                        
                        <div class="d-flex gap-2 justify-content-center mb-3">
                            @if($company->website)
                                <a href="{{ $company->website }}" 
                                   target="_blank" 
                                   class="btn btn-sm btn-outline-secondary" 
                                   title="Website">
                                    <i class="bi bi-globe"></i>
                                </a>
                            @endif
                            <span class="badge bg-info">
                                {{ $company->activeVacancies->count() }} Lowongan Aktif
                            </span>
                        </div>
                        
                        <a href="{{ route('companies.show', $company->id) }}" 
                           class="btn btn-primary w-100">
                            <i class="bi bi-eye me-2"></i>Lihat Detail
                        </a>
                    </div>
                </div>
            </div>
            @endforeach
        </div>

        <!-- Pagination -->
        <div class="mt-5">
            {{ $companies->links() }}
        </div>
        @else
        <!-- Empty State -->
        <div class="text-center py-5">
            <i class="bi bi-building display-1 text-muted"></i>
            <h4 class="mt-3 mb-2">Tidak Ada Perusahaan</h4>
            <p class="text-muted">
                @if(request('search') || request('sector'))
                    Tidak ditemukan perusahaan yang sesuai dengan filter Anda.
                @else
                    Belum ada perusahaan mitra terdaftar.
                @endif
            </p>
            @if(request('search') || request('sector'))
            <a href="{{ route('companies') }}" class="btn btn-primary mt-3">
                <i class="bi bi-arrow-left me-2"></i>Lihat Semua Perusahaan
            </a>
            @endif
        </div>
        @endif
    </div>
</section>

<!-- CTA Section -->
<!-- CTA Section - Professional & Seamless -->
<section class="py-5 bg-primary text-white" style="margin-bottom: 0;">
    <div class="container text-center py-4">
        <div class="row justify-content-center">
            <div class="col-lg-8">
                <h3 class="fw-bold mb-3">Perusahaan Anda Ingin Bergabung?</h3>
                <p class="lead mb-4">
                    Daftarkan perusahaan Anda dan temukan talenta terbaik dari SMKN 1 Purwosari
                </p>
                @guest
                    <div class="d-flex gap-3 justify-content-center flex-wrap">
                        <a href="{{ route('register.company') }}" class="btn btn-light btn-lg px-5 py-3">
                            <i class="bi bi-building-add me-2"></i>Daftar Perusahaan
                        </a>
                        <a href="{{ route('register.student') }}" class="btn btn-outline-light btn-lg px-5 py-3">
                            <i class="bi bi-person-plus me-2"></i>Daftar Siswa/Alumni
                        </a>
                    </div>
                    <p class="text-white-50 mt-4 mb-0">
                        <small>Sudah punya akun? <a href="{{ route('login') }}" class="text-white fw-bold text-decoration-underline">Login di sini</a></small>
                    </p>
                @else
                    @if(Auth::user()->isStudent())
                        <a href="{{ route('vacancies') }}" class="btn btn-light btn-lg px-5 py-3">
                            <i class="bi bi-briefcase me-2"></i>Lihat Lowongan Kerja
                        </a>
                    @elseif(Auth::user()->isCompany())
                        <a href="{{ route('company.vacancies.create') }}" class="btn btn-light btn-lg px-5 py-3">
                            <i class="bi bi-plus-circle me-2"></i>Buat Lowongan Baru
                        </a>
                    @endif
                @endguest
            </div>
        </div>
    </div>
</section>
@endsection