@extends('layouts.admin')

@section('title', 'Lowongan Pending - Admin BKK')
@section('page-title', 'Lowongan Pending Approval')

@section('content')
<div class="card">
    <div class="card-header bg-white py-3">
        <div class="d-flex justify-content-between align-items-center">
            <h5 class="mb-0 fw-bold">
                <i class="bi bi-briefcase-fill me-2"></i>Lowongan Menunggu Approval
            </h5>
            <span class="badge bg-warning">{{ $pendingVacancies->total() }} Pending</span>
        </div>
    </div>
    <div class="card-body p-0">
        @if($pendingVacancies->count() > 0)
            @foreach($pendingVacancies as $vacancy)
            <div class="border-bottom p-4">
                <div class="row">
                    <div class="col-md-8">
                        <div class="d-flex align-items-start mb-3">
                            @if($vacancy->company->logo)
                                <img src="{{ Storage::url($vacancy->company->logo) }}" 
                                     class="rounded me-3" 
                                     style="width: 60px; height: 60px; object-fit: cover;">
                            @else
                                <div class="bg-primary text-white rounded d-flex align-items-center justify-content-center me-3" 
                                     style="width: 60px; height: 60px;">
                                    <i class="bi bi-building fs-4"></i>
                                </div>
                            @endif
                            <div class="flex-grow-1">
                                <h5 class="fw-bold mb-1">{{ $vacancy->title }}</h5>
                                <p class="text-muted mb-2">{{ $vacancy->company->name }}</p>
                                <div class="mb-2">
                                    <span class="badge bg-{{ $vacancy->type === 'internship' ? 'info' : 'success' }}">
                                        {{ get_vacancy_type_label($vacancy->type) }}
                                    </span>
                                    <span class="badge bg-secondary">{{ $vacancy->location }}</span>
                                </div>
                            </div>
                        </div>

                        <div class="mb-3">
                            <h6 class="fw-bold mb-2">Deskripsi:</h6>
                            <p class="text-muted small">{{ Str::limit($vacancy->description, 200) }}</p>
                        </div>

                        <div class="row g-2 text-muted small">
                            <div class="col-md-6">
                                <i class="bi bi-people"></i> Kuota: {{ $vacancy->quota }} posisi
                            </div>
                            <div class="col-md-6">
                                <i class="bi bi-calendar-event"></i> Deadline: {{ format_date_indonesian($vacancy->deadline) }}
                            </div>
                            @if($vacancy->salary_min || $vacancy->salary_max)
                            <div class="col-12">
                                <i class="bi bi-cash-stack"></i> Gaji: 
                                @if($vacancy->salary_min && $vacancy->salary_max)
                                    {{ format_currency($vacancy->salary_min) }} - {{ format_currency($vacancy->salary_max) }}
                                @elseif($vacancy->salary_min)
                                    Mulai dari {{ format_currency($vacancy->salary_min) }}
                                @else
                                    Hingga {{ format_currency($vacancy->salary_max) }}
                                @endif
                            </div>
                            @endif
                        </div>
                    </div>

                    <div class="col-md-4">
                        <div class="d-grid gap-2">
                            <a href="{{ route('admin.vacancies.show', $vacancy->id) }}" 
                               class="btn btn-outline-primary">
                                <i class="bi bi-eye me-2"></i>Review Detail
                            </a>
                            
                            <form method="POST" action="{{ route('admin.vacancies.approve', $vacancy->id) }}">
                                @csrf
                                @method('PATCH')
                                <button type="submit" 
                                        class="btn btn-success w-100"
                                        onclick="return confirm('Approve lowongan ini?')">
                                    <i class="bi bi-check-circle me-2"></i>Approve
                                </button>
                            </form>

                            <button type="button" 
                                    class="btn btn-danger" 
                                    data-bs-toggle="modal" 
                                    data-bs-target="#rejectModal{{ $vacancy->id }}">
                                <i class="bi bi-x-circle me-2"></i>Reject
                            </button>
                        </div>

                        <div class="mt-3 text-muted small text-center">
                            Diajukan {{ time_ago_indonesian($vacancy->created_at) }}
                        </div>
                    </div>
                </div>
            </div>

            <!-- Reject Modal -->
            <div class="modal fade" id="rejectModal{{ $vacancy->id }}" tabindex="-1">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <form method="POST" action="{{ route('admin.vacancies.reject', $vacancy->id) }}">
                            @csrf
                            @method('PATCH')
                            <div class="modal-header">
                                <h5 class="modal-title">Tolak Lowongan</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                            </div>
                            <div class="modal-body">
                                <div class="alert alert-warning">
                                    <i class="bi bi-exclamation-triangle me-2"></i>
                                    Menolak lowongan: <strong>{{ $vacancy->title }}</strong>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">Alasan Penolakan <span class="text-danger">*</span></label>
                                    <textarea class="form-control" 
                                              name="rejection_reason" 
                                              rows="3" 
                                              required 
                                              placeholder="Jelaskan alasan penolakan..."></textarea>
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                                <button type="submit" class="btn btn-danger">Tolak Lowongan</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
            @endforeach

            <div class="p-3">
                {{ $pendingVacancies->links() }}
            </div>
        @else
            <div class="text-center py-5">
                <i class="bi bi-check-circle text-success display-1"></i>
                <h5 class="mt-3">Tidak Ada Lowongan Pending</h5>
                <p class="text-muted">Semua lowongan sudah diproses</p>
            </div>
        @endif
    </div>
</div>
@endsection