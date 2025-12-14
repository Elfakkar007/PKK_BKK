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
                                    <form method="POST" 
                                          action="{{ route('admin.users.approve', $user->id) }}" 
                                          class="d-inline">
                                        @csrf
                                        @method('PATCH')
                                        <button type="submit" 
                                                class="btn btn-outline-success"
                                                onclick="return confirmApprove(event, 'Approve user {{ $user->email }}?')">
                                            <i class="bi bi-check-circle"></i>
                                        </button>
                                    </form>
                                    <button type="button" 
                                            class="btn btn-outline-danger" 
                                            onclick="showRejectModal({{ $user->id }}, '{{ $user->email }}')">
                                        <i class="bi bi-x-circle"></i>
                                    </button>
                                </div>
                            </td>
                        </tr>

                        <!-- Detail Modal -->
                        <div class="modal fade" id="detailModal{{ $user->id }}" tabindex="-1">
                            <div class="modal-dialog modal-lg">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title">Detail User</h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                    </div>
                                    <div class="modal-body">
                                        @if($user->student)
                                            <h6 class="fw-bold mb-3">Data Siswa/Alumni</h6>
                                            <div class="row">
                                                <div class="col-md-6 mb-2">
                                                    <strong>Nama Lengkap:</strong><br>
                                                    {{ $user->student->full_name }}
                                                </div>
                                                <div class="col-md-6 mb-2">
                                                    <strong>NIS:</strong><br>
                                                    {{ $user->student->nis }}
                                                </div>
                                                <div class="col-md-6 mb-2">
                                                    <strong>Jenis Kelamin:</strong><br>
                                                    {{ get_gender_label($user->student->gender) }}
                                                </div>
                                                <div class="col-md-6 mb-2">
                                                    <strong>Tempat, Tanggal Lahir:</strong><br>
                                                    {{ $user->student->birth_place }}, {{ format_date_indonesian($user->student->birth_date) }}
                                                </div>
                                                <div class="col-md-6 mb-2">
                                                    <strong>Jurusan:</strong><br>
                                                    {{ $user->student->major }}
                                                </div>
                                                <div class="col-md-6 mb-2">
                                                    <strong>Status:</strong><br>
                                                    {{ $user->student->isAlumni() ? 'Alumni Tahun ' . $user->student->graduation_year : 'Siswa Kelas ' . $user->student->class }}
                                                </div>
                                            </div>
                                        @elseif($user->company)
                                            <h6 class="fw-bold mb-3">Data Perusahaan</h6>
                                            <div class="row">
                                                <div class="col-md-6 mb-2">
                                                    <strong>Nama Perusahaan:</strong><br>
                                                    {{ $user->company->company_type }} {{ $user->company->name }}
                                                </div>
                                                <div class="col-md-6 mb-2">
                                                    <strong>Sektor Industri:</strong><br>
                                                    {{ $user->company->industry_sector }}
                                                </div>
                                                <div class="col-12 mb-2">
                                                    <strong>Alamat:</strong><br>
                                                    {{ $user->company->head_office_address }}
                                                </div>
                                                <div class="col-md-6 mb-2">
                                                    <strong>PIC:</strong><br>
                                                    {{ $user->company->pic_name }}
                                                </div>
                                                <div class="col-md-6 mb-2">
                                                    <strong>Telepon:</strong><br>
                                                    {{ format_phone_number($user->company->pic_phone) }}
                                                </div>
                                                @if($user->company->website)
                                                <div class="col-12 mb-2">
                                                    <strong>Website:</strong><br>
                                                    <a href="{{ $user->company->website }}" target="_blank">
                                                        {{ $user->company->website }}
                                                    </a>
                                                </div>
                                                @endif
                                            </div>
                                        @endif
                                        
                                        <div class="mt-3">
                                            <strong>Email:</strong> {{ $user->email }}<br>
                                            <strong>Tanggal Daftar:</strong> {{ format_date_indonesian($user->created_at) }}
                                        </div>
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                                    </div>
                                </div>
                            </div>
                        </div>
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

@push('scripts')
<script>
function showRejectModal(userId, userEmail) {
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
            // Create form and submit
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