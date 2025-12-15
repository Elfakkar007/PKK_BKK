@extends('layouts.admin')

@section('title', 'User Pending Approval - Admin BKK')
@section('page-title', 'User Pending Approval')

@section('content')
<div class="card">
    <div class="card-header bg-white py-3">
        <div class="d-flex justify-content-between align-items-center">
            <h5 class="mb-0 fw-bold">
                <i class="bi bi-person-check me-2"></i>User Menunggu Approval
            </h5>
            <span class="badge bg-warning">{{ $pendingUsers->total() }} Pending</span>
        </div>
    </div>
    <div class="card-body p-0">
        @if($pendingUsers->count() > 0)
            <div class="table-responsive">
                <table class="table table-hover mb-0">
                    <thead class="table-light">
                        <tr>
                            <th>User</th>
                            <th>Role</th>
                            <th>Detail</th>
                            <th>Tanggal Daftar</th>
                            <th class="text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($pendingUsers as $user)
                        <tr>
                            <td>
                                <div class="d-flex align-items-center">
                                    <div class="bg-secondary text-white rounded-circle d-flex align-items-center justify-content-center me-3" 
                                         style="width: 40px; height: 40px;">
                                        <i class="bi bi-{{ $user->isStudent() ? 'person' : 'building' }}"></i>
                                    </div>
                                    <div>
                                        <strong>
                                            @if($user->student)
                                                {{ $user->student->full_name }}
                                            @elseif($user->company)
                                                {{ $user->company->name }}
                                            @endif
                                        </strong>
                                        <br>
                                        <small class="text-muted">{{ $user->email }}</small>
                                    </div>
                                </div>
                            </td>
                            <td>
                                <span class="badge bg-secondary">
                                    {{ get_role_label($user->role) }}
                                </span>
                            </td>
                            <td>
                                @if($user->student)
                                    <small>
                                        <strong>NIS:</strong> {{ $user->student->nis }}<br>
                                        <strong>Jurusan:</strong> {{ $user->student->major }}<br>
                                        <strong>Status:</strong> {{ $user->student->isAlumni() ? 'Alumni (' . $user->student->graduation_year . ')' : 'Siswa Kelas ' . $user->student->class }}
                                    </small>
                                @elseif($user->company)
                                    <small>
                                        <strong>Industri:</strong> {{ $user->company->industry_sector }}<br>
                                        <strong>PIC:</strong> {{ $user->company->pic_name }}<br>
                                        <strong>Telp:</strong> {{ format_phone_number($user->company->pic_phone) }}
                                    </small>
                                @endif
                            </td>
                            <td>
                                <small>{{ $user->created_at->format('d M Y') }}</small><br>
                                <small class="text-muted">{{ time_ago_indonesian($user->created_at) }}</small>
                            </td>
                            <td class="text-center">
                                <div class="btn-group btn-group-sm" role="group">
                                    <button type="button" 
                                            class="btn btn-outline-info" 
                                            data-bs-toggle="modal" 
                                            data-bs-target="#detailModal{{ $user->id }}">
                                        <i class="bi bi-eye"></i>
                                    </button>
                                    <button type="button" 
                                            class="btn btn-outline-success"
                                            onclick="confirmApprove({{ $user->id }}, '{{ $user->email }}')">
                                        <i class="bi bi-check-circle"></i>
                                    </button>
                                    <button type="button" 
                                            class="btn btn-outline-danger" 
                                            onclick="confirmReject({{ $user->id }}, '{{ $user->email }}')">
                                        <i class="bi bi-x-circle"></i>
                                    </button>
                                </div>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            <div class="card-footer bg-white">
                {{ $pendingUsers->links() }}
            </div>
        @else
            <div class="text-center py-5">
                <i class="bi bi-check-circle text-success display-1"></i>
                <h5 class="mt-3">Tidak Ada User Pending</h5>
                <p class="text-muted">Semua registrasi sudah diproses</p>
            </div>
        @endif
    </div>
</div>

@foreach($pendingUsers as $user)<!-- Detail Modal for each user -->
                        <div class="modal fade" id="detailModal{{ $user->id }}" tabindex="-1" data-bs-backdrop="static">
                            <div class="modal-dialog modal-lg modal-dialog-scrollable">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title">Detail User</h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                    </div>
                                    <div class="modal-body">
                                        @if($user->student)
                                            <h6 class="fw-bold mb-3 text-primary">
                                                <i class="bi bi-person-badge me-2"></i>Data Siswa/Alumni
                                            </h6>
                                            <div class="row">
                                                <div class="col-md-6 mb-3">
                                                    <label class="text-muted small">Nama Lengkap</label>
                                                    <p class="mb-0 fw-semibold">{{ $user->student->full_name }}</p>
                                                </div>
                                                <div class="col-md-6 mb-3">
                                                    <label class="text-muted small">NIS</label>
                                                    <p class="mb-0 fw-semibold">{{ $user->student->nis }}</p>
                                                </div>
                                                <div class="col-md-6 mb-3">
                                                    <label class="text-muted small">Jenis Kelamin</label>
                                                    <p class="mb-0">{{ get_gender_label($user->student->gender) }}</p>
                                                </div>
                                                <div class="col-md-6 mb-3">
                                                    <label class="text-muted small">Tempat, Tanggal Lahir</label>
                                                    <p class="mb-0">{{ $user->student->birth_place }}, {{ format_date_indonesian($user->student->birth_date) }}</p>
                                                </div>
                                                <div class="col-md-6 mb-3">
                                                    <label class="text-muted small">Jurusan</label>
                                                    <p class="mb-0">{{ $user->student->major }}</p>
                                                </div>
                                                <div class="col-md-6 mb-3">
                                                    <label class="text-muted small">Status</label>
                                                    <p class="mb-0">
                                                        @if($user->student->isAlumni())
                                                            <span class="badge bg-info">Alumni Tahun {{ $user->student->graduation_year }}</span>
                                                        @else
                                                            <span class="badge bg-success">Siswa Kelas {{ $user->student->class }}</span>
                                                        @endif
                                                    </p>
                                                </div>
                                            </div>
                                        @elseif($user->company)
                                            <h6 class="fw-bold mb-3 text-primary">
                                                <i class="bi bi-building me-2"></i>Data Perusahaan
                                            </h6>
                                            <div class="row">
                                                <div class="col-12 mb-3">
                                                    <label class="text-muted small">Nama Perusahaan</label>
                                                    <p class="mb-0 fw-semibold fs-5">{{ $user->company->company_type }} {{ $user->company->name }}</p>
                                                </div>
                                                <div class="col-md-6 mb-3">
                                                    <label class="text-muted small">Sektor Industri</label>
                                                    <p class="mb-0"><span class="badge bg-secondary">{{ $user->company->industry_sector }}</span></p>
                                                </div>
                                                <div class="col-md-6 mb-3">
                                                    <label class="text-muted small">Website</label>
                                                    <p class="mb-0">
                                                        @if($user->company->website)
                                                            <a href="{{ $user->company->website }}" target="_blank" class="text-decoration-none">
                                                                <i class="bi bi-link-45deg me-1"></i>{{ $user->company->website }}
                                                            </a>
                                                        @else
                                                            <span class="text-muted">-</span>
                                                        @endif
                                                    </p>
                                                </div>
                                                @if($user->company->description)
                                                <div class="col-12 mb-3">
                                                    <label class="text-muted small">Deskripsi</label>
                                                    <p class="mb-0">{{ $user->company->description }}</p>
                                                </div>
                                                @endif
                                                <div class="col-12 mb-3">
                                                    <label class="text-muted small">Alamat Kantor Pusat</label>
                                                    <p class="mb-0">{{ $user->company->head_office_address }}</p>
                                                </div>
                                            </div>
                                            
                                            <hr>
                                            
                                            <h6 class="fw-bold mb-3 text-primary">
                                                <i class="bi bi-person-badge me-2"></i>Penanggung Jawab (PIC)
                                            </h6>
                                            <div class="row">
                                                <div class="col-md-6 mb-3">
                                                    <label class="text-muted small">Nama PIC</label>
                                                    <p class="mb-0 fw-semibold">{{ $user->company->pic_name }}</p>
                                                </div>
                                                <div class="col-md-6 mb-3">
                                                    <label class="text-muted small">Telepon/WhatsApp</label>
                                                    <p class="mb-0">
                                                        <i class="bi bi-telephone me-1"></i>{{ format_phone_number($user->company->pic_phone) }}
                                                    </p>
                                                </div>
                                                @if($user->company->pic_email)
                                                <div class="col-12 mb-3">
                                                    <label class="text-muted small">Email PIC</label>
                                                    <p class="mb-0">
                                                        <i class="bi bi-envelope me-1"></i>{{ $user->company->pic_email }}
                                                    </p>
                                                </div>
                                                @endif
                                            </div>
                                            
                                            <hr>
                                            
                                            <h6 class="fw-bold mb-3 text-primary">
                                                <i class="bi bi-file-earmark-text me-2"></i>Dokumen
                                            </h6>
                                            <div class="row">
                                                <div class="col-md-6 mb-3">
                                                    <label class="text-muted small">Logo Perusahaan</label>
                                                    @if($user->company->logo)
                                                        <div class="mt-2">
                                                            <img src="{{ Storage::url($user->company->logo) }}" 
                                                                 class="img-thumbnail" 
                                                                 style="max-height: 120px; cursor: pointer;"
                                                                 onclick="window.open('{{ Storage::url($user->company->logo) }}', '_blank')">
                                                        </div>
                                                    @else
                                                        <p class="mb-0 text-muted">Tidak ada logo</p>
                                                    @endif
                                                </div>
                                                <div class="col-md-6 mb-3">
                                                    <label class="text-muted small">Dokumen Legalitas</label>
                                                    @if($user->company->legality_doc)
                                                        <div class="mt-2">
                                                            <a href="{{ Storage::url($user->company->legality_doc) }}" 
                                                               target="_blank" 
                                                               class="btn btn-sm btn-outline-primary">
                                                                <i class="bi bi-file-pdf me-1"></i>Lihat Dokumen
                                                            </a>
                                                        </div>
                                                    @else
                                                        <p class="mb-0 text-muted">Tidak ada dokumen</p>
                                                    @endif
                                                </div>
                                            </div>
                                        @endif
                                        
                                        <hr>
                                        
                                        <div class="row">
                                            <div class="col-md-6 mb-2">
                                                <label class="text-muted small">Email</label>
                                                <p class="mb-0"><i class="bi bi-envelope me-1"></i>{{ $user->email }}</p>
                                            </div>
                                            <div class="col-md-6 mb-2">
                                                <label class="text-muted small">Tanggal Daftar</label>
                                                <p class="mb-0"><i class="bi bi-calendar me-1"></i>{{ format_date_indonesian($user->created_at) }}</p>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                                        <button type="button" class="btn btn-success" data-bs-dismiss="modal" onclick="confirmApprove({{ $user->id }}, '{{ $user->email }}')">
                                            <i class="bi bi-check-circle me-1"></i>Approve
                                        </button>
                                        <button type="button" class="btn btn-danger" data-bs-dismiss="modal" onclick="confirmReject({{ $user->id }}, '{{ $user->email }}')">
                                            <i class="bi bi-x-circle me-1"></i>Reject
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
@endforeach     
@push('scripts')
<script>
function confirmApprove(userId, userEmail) {
    Swal.fire({
        title: 'Approve User?',
        html: `Anda akan menyetujui user: <strong>${userEmail}</strong>`,
        icon: 'question',
        showCancelButton: true,
        confirmButtonColor: '#198754',
        cancelButtonColor: '#6c757d',
        confirmButtonText: 'Ya, Approve!',
        cancelButtonText: 'Batal',
        reverseButtons: true
    }).then((result) => {
        if (result.isConfirmed) {
            // Show loading
            Swal.fire({
                title: 'Memproses...',
                html: 'Mohon tunggu sebentar',
                allowOutsideClick: false,
                allowEscapeKey: false,
                didOpen: () => {
                    Swal.showLoading();
                }
            });
            
            // Create and submit form
            const form = document.createElement('form');
            form.method = 'POST';
            form.action = `/admin/users/${userId}/approve`;
            
            const csrfToken = document.querySelector('meta[name="csrf-token"]').content;
            form.innerHTML = `
                <input type="hidden" name="_token" value="${csrfToken}">
                <input type="hidden" name="_method" value="PATCH">
            `;
            
            document.body.appendChild(form);
            form.submit();
        }
    });
}

function confirmReject(userId, userEmail) {
    Swal.fire({
        title: 'Tolak User',
        html: `
            <div class="alert alert-warning text-start">
                <i class="bi bi-exclamation-triangle me-2"></i>
                Anda akan menolak user: <strong>${userEmail}</strong>
            </div>
            <div class="mb-3 text-start">
                <label class="form-label fw-bold">Alasan Penolakan <span class="text-danger">*</span></label>
                <textarea id="rejection_reason" class="form-control" rows="3" placeholder="Jelaskan alasan penolakan..."></textarea>
            </div>
        `,
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#dc3545',
        cancelButtonColor: '#6c757d',
        confirmButtonText: 'Tolak User',
        cancelButtonText: 'Batal',
        reverseButtons: true,
        preConfirm: () => {
            const reason = document.getElementById('rejection_reason').value;
            if (!reason) {
                Swal.showValidationMessage('Alasan penolakan wajib diisi');
                return false;
            }
            return reason;
        }
    }).then((result) => {
        if (result.isConfirmed) {
            // Show loading
            Swal.fire({
                title: 'Memproses...',
                html: 'Mohon tunggu sebentar',
                allowOutsideClick: false,
                allowEscapeKey: false,
                didOpen: () => {
                    Swal.showLoading();
                }
            });
            
            // Create and submit form
            const form = document.createElement('form');
            form.method = 'POST';
            form.action = `/admin/users/${userId}/reject`;
            
            const csrfToken = document.querySelector('meta[name="csrf-token"]').content;
            form.innerHTML = `
                <input type="hidden" name="_token" value="${csrfToken}">
                <input type="hidden" name="_method" value="PATCH">
                <input type="hidden" name="rejection_reason" value="${result.value}">
            `;
            
            document.body.appendChild(form);
            form.submit();
        }
    });
}
</script>
@endpush
@endsection