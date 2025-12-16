@extends('layouts.app')

@section('title', 'Tentang BKK')

@section('content')
<!-- Hero Section -->
<section class="bg-primary text-white py-5">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-lg-8">
                <h1 class="display-4 fw-bold mb-4">{{ settings('site_name', 'Bursa Kerja Khusus SMKN 1 Purwosari') }}</h1>
                <p class="lead mb-4">
                    {{ settings('site_tagline', 'Membantu siswa dan alumni menemukan peluang karir terbaik melalui jaringan perusahaan mitra yang terpercaya.') }}
                </p>
            </div>
        </div>
    </div>
</section>

<!-- Vision & Mission -->
<section class="py-5">
    <div class="container">
        <div class="row g-4">
            @if($about->hasVision())
            <div class="col-md-6">
                <div class="card h-100 border-0 shadow-sm">
                    <div class="card-body p-4">
                        <div class="text-center mb-4">
                            <div class="bg-primary bg-opacity-10 rounded-circle d-inline-flex align-items-center justify-content-center mb-3" 
                                 style="width: 80px; height: 80px;">
                                <i class="bi bi-eye text-primary display-5"></i>
                            </div>
                            <h3 class="fw-bold">Visi</h3>
                        </div>
                        <p class="text-center lead" style="line-height: 1.8;">
                            {{ $about->vision }}
                        </p>
                    </div>
                </div>
            </div>
            @endif

            @if($about->hasMission())
            <div class="col-md-6">
                <div class="card h-100 border-0 shadow-sm">
                    <div class="card-body p-4">
                        <div class="text-center mb-4">
                            <div class="bg-success bg-opacity-10 rounded-circle d-inline-flex align-items-center justify-content-center mb-3" 
                                 style="width: 80px; height: 80px;">
                                <i class="bi bi-flag text-success display-5"></i>
                            </div>
                            <h3 class="fw-bold">Misi</h3>
                        </div>
                        <p class="text-justify" style="line-height: 1.8;">
                            {{ $about->mission }}
                        </p>
                    </div>
                </div>
            </div>
            @endif
        </div>
    </div>
</section>

<!-- Work Programs -->
@if($about->hasWorkPrograms())
<section class="py-5 bg-light">
    <div class="container">
        <div class="text-center mb-5">
            <h2 class="fw-bold mb-3">Program Kerja BKK</h2>
            <p class="text-muted">Program-program unggulan untuk mendukung siswa dan alumni</p>
        </div>
        
        <div class="row g-4">
            @foreach($about->work_programs as $index => $program)
            <div class="col-md-6 col-lg-4">
                <div class="card h-100 border-0 shadow-sm">
                    <div class="card-body p-4">
                        <div class="d-flex align-items-start mb-3">
                            <div class="bg-primary text-white rounded-circle d-flex align-items-center justify-content-center me-3" 
                                 style="width: 50px; height: 50px; flex-shrink: 0;">
                                <h4 class="mb-0">{{ $index + 1 }}</h4>
                            </div>
                            <div>
                                <h5 class="fw-bold mb-2">{{ $program['title'] ?? $program }}</h5>
                                @if(is_array($program) && isset($program['description']))
                                    <p class="text-muted mb-0">{{ $program['description'] }}</p>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
    </div>
</section>
@endif

<!-- Organization Structure -->
@if($about->hasOrganizationChart())
<section class="py-5">
    <div class="container">
        <div class="text-center mb-5">
            <h2 class="fw-bold mb-3">Struktur Organisasi</h2>
            <p class="text-muted">Tim BKK SMKN 1 Purwosari</p>
        </div>
        
        <div class="text-center">
            <img src="{{ Storage::url($about->organization_chart) }}" 
                 alt="Struktur Organisasi BKK" 
                 class="img-fluid rounded shadow"
                 style="max-width: 100%; height: auto;">
        </div>
    </div>
</section>
@endif

<!-- Statistics -->
<section class="py-5 bg-primary text-white">
    <div class="container">
        <div class="text-center mb-5">
            <h2 class="fw-bold mb-3">BKK dalam Angka</h2>
        </div>
        
        <div class="row g-4 text-center">
            <div class="col-md-3 col-6">
                <div class="mb-3">
                    <i class="bi bi-people display-3"></i>
                </div>
                <h2 class="fw-bold mb-1">500+</h2>
                <p class="mb-0">Alumni Tersalurkan</p>
            </div>
            <div class="col-md-3 col-6">
                <div class="mb-3">
                    <i class="bi bi-building display-3"></i>
                </div>
                <h2 class="fw-bold mb-1">{{ App\Models\Company::whereHas('user', fn($q) => $q->where('status', 'approved'))->count() }}+</h2>
                <p class="mb-0">Perusahaan Mitra</p>
            </div>
            <div class="col-md-3 col-6">
                <div class="mb-3">
                    <i class="bi bi-briefcase display-3"></i>
                </div>
                <h2 class="fw-bold mb-1">{{ App\Models\JobVacancy::active()->count() }}+</h2>
                <p class="mb-0">Lowongan Aktif</p>
            </div>
            <div class="col-md-3 col-6">
                <div class="mb-3">
                    <i class="bi bi-star display-3"></i>
                </div>
                <h2 class="fw-bold mb-1">95%</h2>
                <p class="mb-0">Tingkat Kepuasan</p>
            </div>
        </div>
    </div>
</section>

<!-- Contact Section -->
<section class="py-5">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-lg-6 mb-4 mb-lg-0">
                <h2 class="fw-bold mb-4">Hubungi Kami</h2>
                <div class="mb-4">
                     @if(settings('contact_address'))
                    <div class="d-flex align-items-start mb-3">
                        <div class="bg-primary text-white rounded p-3 me-3">
                            <i class="bi bi-geo-alt fs-5"></i>
                        </div>
                        <div>
                            <h6 class="fw-bold mb-1">Alamat</h6>
                            <strong>{{ settings('contact_address') }}</strong>
                        </div>
                    </div>
                    @endif
                    
                    @if(settings('contact_phone'))
                    <div class="d-flex align-items-start mb-3">
                        <div class="bg-success text-white rounded p-3 me-3">
                            <i class="bi bi-telephone fs-5"></i>
                        </div>
                        <div>
                            <h6 class="fw-bold mb-1">Telepon</h6>
                            <strong>{{ settings('contact_phone') }}</strong>
                        </div>
                    </div>
                    @endif
                    
                    @if(settings('contact_email'))
                    <div class="d-flex align-items-start mb-3">
                        <div class="bg-info text-white rounded p-3 me-3">
                            <i class="bi bi-envelope fs-5"></i>
                        </div>
                        <div>
                            <h6 class="fw-bold mb-1">Email</h6>
                            <strong>{{ settings('contact_email') }}</strong>
                        </div>
                    </div>
                    @endif
                    
                    <div class="d-flex align-items-start">
                        <div class="bg-warning text-white rounded p-3 me-3">
                            <i class="bi bi-clock fs-5"></i>
                        </div>
                        <div>
                            <h6 class="fw-bold mb-1">Jam Operasional</h6>
                            <p class="mb-0">
                                Senin - Jumat: 06:45 - 14:50 WIB<br>
                            </p>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="col-lg-6">
                <div class="card border-0 shadow-sm">
                    <div class="card-body p-4">
                        <h5 class="fw-bold mb-4">Kirim Pesan</h5>
                        <form>
                            <div class="mb-3">
                                <label class="form-label">Nama</label>
                                <input type="text" class="form-control" required>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Email</label>
                                <input type="email" class="form-control" required>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Subjek</label>
                                <input type="text" class="form-control" required>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Pesan</label>
                                <textarea class="form-control" rows="4" required></textarea>
                            </div>
                            <button type="submit" class="btn btn-primary w-100">
                                <i class="bi bi-send me-2"></i>Kirim Pesan
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection