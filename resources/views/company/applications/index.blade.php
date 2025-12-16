@extends('layouts.app')

@section('title', 'Kelola Lamaran')

@section('content')
<div class="container py-5">
    <div class="mb-4">
        <h2 class="fw-bold mb-1">Kelola Lamaran</h2>
        <p class="text-muted mb-0">Tinjau dan kelola semua lamaran yang masuk</p>
    </div>

    <!-- Filter Tabs -->
    <ul class="nav nav-tabs mb-4 flex-wrap">
        <li class="nav-item">
            <a class="nav-link {{ $status == 'all' ? 'active' : '' }}" 
               href="{{ route('company.applications') }}">
                Semua ({{ $applications->total() }})
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link {{ $status == 'pending' ? 'active' : '' }}" 
               href="{{ route('company.applications', ['status' => 'pending']) }}">
                Baru
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link {{ $status == 'reviewed' ? 'active' : '' }}" 
               href="{{ route('company.applications', ['status' => 'reviewed']) }}">
                Ditinjau
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link {{ $status == 'interview' ? 'active' : '' }}" 
               href="{{ route('company.applications', ['status' => 'interview']) }}">
                Wawancara
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link {{ $status == 'technical_test' ? 'active' : '' }}" 
               href="{{ route('company.applications', ['status' => 'technical_test']) }}">
                Tes Teknis
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link {{ $status == 'accepted' ? 'active' : '' }}" 
               href="{{ route('company.applications', ['status' => 'accepted']) }}">
                Diterima
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link {{ $status == 'rejected' ? 'active' : '' }}" 
               href="{{ route('company.applications', ['status' => 'rejected']) }}">
                Ditolak
            </a>
        </li>
    </ul>

    @if($applications->count() > 0)
    <div class="card border-0 shadow-sm">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover mb-0">
                    <thead class="table-light">
                        <tr>
                            <th>Pelamar</th>
                            <th>Posisi</th>
                            <th>Tanggal Lamar</th>
                            <th>Status</th>
                            <th class="text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($applications as $application)
                        <tr>
                            <td>
                                <div class="d-flex align-items-center">
                                    @if($application->student->photo)
                                        <img src="{{ Storage::url($application->student->photo) }}" 
                                             class="rounded-circle me-3" 
                                             style="width: 40px; height: 40px; object-fit: cover;">
                                    @else
                                        <div class="bg-secondary text-white rounded-circle d-flex align-items-center justify-content-center me-3" 
                                             style="width: 40px; height: 40px;">
                                            <i class="bi bi-person"></i>
                                        </div>
                                    @endif
                                    <div>
                                        <strong>{{ $application->full_name }}</strong><br>
                                        <small class="text-muted">{{ $application->student->major }}</small>
                                    </div>
                                </div>
                            </td>
                            <td>
                                <strong>{{ $application->jobVacancy->title }}</strong><br>
                                <span class="badge bg-{{ $application->jobVacancy->type === 'internship' ? 'info' : 'success' }}">
                                    {{ get_vacancy_type_label($application->jobVacancy->type) }}
                                </span>
                            </td>
                            <td>
                                <small>{{ $application->created_at->format('d M Y') }}</small><br>
                                <small class="text-muted">{{ time_ago_indonesian($application->created_at) }}</small>
                            </td>
                            <td>
                                <span class="badge {{ get_status_badge_class($application->status) }}">
                                    {{ $application->status_label }}
                                </span>
                            </td>
                            <td class="text-center">
                                <a href="{{ route('company.applications.show', $application->id) }}" 
                                   class="btn btn-sm btn-outline-primary">
                                    <i class="bi bi-eye"></i> Review
                                </a>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
        <div class="card-footer bg-white">
            {{ $applications->links() }}
        </div>
    </div>
    @else
    <div class="text-center py-5">
        <i class="bi bi-inbox display-1 text-muted"></i>
        <h4 class="mt-3 mb-2">Belum Ada Lamaran</h4>
        <p class="text-muted">
            @if($status !== 'all')
                Tidak ada lamaran dengan status {{ $status }}
            @else
                Belum ada lamaran yang masuk
            @endif
        </p>
        @if($status !== 'all')
            <a href="{{ route('company.applications') }}" class="btn btn-primary mt-3">
                Lihat Semua Lamaran
            </a>
        @endif
    </div>
    @endif
</div>
@endsection