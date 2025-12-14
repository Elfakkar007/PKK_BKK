@extends('layouts.admin')

@section('title', 'Manajemen Lowongan - Admin BKK')
@section('page-title', 'Manajemen Lowongan')

@section('content')
<div class="card">
    <div class="card-header bg-white py-3">
        <div class="d-flex justify-content-between align-items-center">
            <h5 class="mb-0 fw-bold">
                <i class="bi bi-briefcase me-2"></i>Daftar Lowongan
            </h5>
            <a href="{{ route('admin.vacancies.pending') }}" class="btn btn-warning btn-sm">
                <i class="bi bi-clock-history me-2"></i>Pending Approval
            </a>
        </div>
    </div>
    <div class="card-body">
        <!-- Filter -->
        <form method="GET" class="row g-3 mb-4">
            <div class="col-md-3">
                <select name="status" class="form-select" onchange="this.form.submit()">
                    <option value="all">Semua Status</option>
                    <option value="pending" {{ $status == 'pending' ? 'selected' : '' }}>Pending</option>
                    <option value="approved" {{ $status == 'approved' ? 'selected' : '' }}>Approved</option>
                    <option value="rejected" {{ $status == 'rejected' ? 'selected' : '' }}>Rejected</option>
                </select>
            </div>
            <div class="col-md-3">
                <select name="type" class="form-select" onchange="this.form.submit()">
                    <option value="all">Semua Tipe</option>
                    <option value="internship" {{ $type == 'internship' ? 'selected' : '' }}>Magang</option>
                    <option value="fulltime" {{ $type == 'fulltime' ? 'selected' : '' }}>Full Time</option>
                </select>
            </div>
            <div class="col-md-4">
                <input type="text" name="search" class="form-control" placeholder="Cari lowongan..." value="{{ request('search') }}">
            </div>
            <div class="col-md-2">
                <button type="submit" class="btn btn-primary w-100">
                    <i class="bi bi-search"></i> Cari
                </button>
            </div>
        </form>

        <!-- Table -->
        @if($vacancies->count() > 0)
        <div class="table-responsive">
            <table class="table table-hover">
                <thead class="table-light">
                    <tr>
                        <th>Lowongan</th>
                        <th>Perusahaan</th>
                        <th>Tipe</th>
                        <th>Status</th>
                        <th>Deadline</th>
                        <th class="text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($vacancies as $vacancy)
                    <tr>
                        <td>
                            <strong>{{ $vacancy->title }}</strong><br>
                            <small class="text-muted">{{ $vacancy->location }}</small>
                        </td>
                        <td>
                            <div class="d-flex align-items-center">
                                @if($vacancy->company->logo)
                                    <img src="{{ Storage::url($vacancy->company->logo) }}" 
                                         class="rounded me-2" 
                                         style="width: 30px; height: 30px; object-fit: cover;">
                                @endif
                                {{ $vacancy->company->name }}
                            </div>
                        </td>
                        <td>
                            <span class="badge bg-{{ $vacancy->type === 'internship' ? 'info' : 'success' }}">
                                {{ get_vacancy_type_label($vacancy->type) }}
                            </span>
                        </td>
                        <td>
                            <span class="badge {{ get_status_badge_class($vacancy->status) }}">
                                {{ get_status_label($vacancy->status) }}
                            </span>
                        </td>
                        <td>
                            <small>{{ $vacancy->deadline->format('d M Y') }}</small>
                        </td>
                        <td class="text-center">
                            <div class="btn-group btn-group-sm">
                                <a href="{{ route('admin.vacancies.show', $vacancy->id) }}" 
                                   class="btn btn-outline-info">
                                    <i class="bi bi-eye"></i>
                                </a>
                                @if($vacancy->status === 'pending')
                                    <form method="POST" action="{{ route('admin.vacancies.approve', $vacancy->id) }}" class="d-inline">
                                        @csrf
                                        @method('PATCH')
                                        <button type="submit" 
                                                class="btn btn-outline-success" 
                                                title="Approve"
                                                onclick="return confirmApprove(event, 'Approve lowongan {{ $vacancy->title }}?')">
                                            <i class="bi bi-check-circle"></i>
                                        </button>
                                    </form>
                                @endif
                                <form method="POST" action="{{ route('admin.vacancies.destroy', $vacancy->id) }}" class="d-inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" 
                                            class="btn btn-outline-danger"
                                            onclick="return confirmDelete(event, 'Lowongan dan semua lamaran terkait akan dihapus!')">
                                        <i class="bi bi-trash"></i>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        
        <div class="mt-3">
            {{ $vacancies->links() }}
        </div>
        @else
        <div class="text-center py-5">
            <i class="bi bi-inbox text-muted display-1"></i>
            <p class="mt-3 text-muted">Tidak ada lowongan ditemukan</p>
        </div>
        @endif
    </div>
</div>
@endsection