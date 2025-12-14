@extends('layouts.admin')

@section('title', 'Manajemen Perusahaan - Admin BKK')
@section('page-title', 'Manajemen Perusahaan')

@section('content')
<div class="card">
    <div class="card-header bg-white py-3">
        <h5 class="mb-0 fw-bold">
            <i class="bi bi-building me-2"></i>Daftar Perusahaan Mitra
        </h5>
    </div>
    <div class="card-body">
        <!-- Filter -->
        <form method="GET" class="row g-3 mb-4">
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
                <input type="text" name="search" class="form-control" placeholder="Cari perusahaan..." value="{{ request('search') }}">
            </div>
            <div class="col-md-2">
                <button type="submit" class="btn btn-primary w-100">
                    <i class="bi bi-search"></i> Cari
                </button>
            </div>
        </form>

        <!-- Table -->
        @if($companies->count() > 0)
        <div class="table-responsive">
            <table class="table table-hover">
                <thead class="table-light">
                    <tr>
                        <th>Perusahaan</th>
                        <th>Sektor</th>
                        <th>Email</th>
                        <th>Lowongan Aktif</th>
                        <th class="text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($companies as $company)
                    <tr>
                        <td>
                            <div class="d-flex align-items-center">
                                @if($company->logo)
                                    <img src="{{ Storage::url($company->logo) }}" 
                                         class="rounded me-3" 
                                         style="width: 50px; height: 50px; object-fit: cover;">
                                @else
                                    <div class="bg-primary text-white rounded d-flex align-items-center justify-content-center me-3" 
                                         style="width: 50px; height: 50px;">
                                        <i class="bi bi-building"></i>
                                    </div>
                                @endif
                                <div>
                                    <strong>{{ $company->name }}</strong><br>
                                    <small class="text-muted">{{ $company->company_type }}</small>
                                </div>
                            </div>
                        </td>
                        <td>
                            <span class="badge bg-secondary">{{ $company->industry_sector }}</span>
                        </td>
                        <td>{{ $company->user->email }}</td>
                        <td>
                            <span class="badge bg-success">{{ $company->activeVacancies->count() }} Aktif</span>
                        </td>
                        <td class="text-center">
                            <div class="btn-group btn-group-sm">
                                <a href="{{ route('admin.companies.show', $company->id) }}" 
                                   class="btn btn-outline-info">
                                    <i class="bi bi-eye"></i>
                                </a>
                                <a href="{{ route('admin.companies.edit', $company->id) }}" 
                                   class="btn btn-outline-primary">
                                    <i class="bi bi-pencil"></i>
                                </a>
                            </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        
        <div class="mt-3">
            {{ $companies->links() }}
        </div>
        @else
        <div class="text-center py-5">
            <i class="bi bi-inbox text-muted display-1"></i>
            <p class="mt-3 text-muted">Tidak ada perusahaan ditemukan</p>
        </div>
        @endif
    </div>
</div>
@endsection