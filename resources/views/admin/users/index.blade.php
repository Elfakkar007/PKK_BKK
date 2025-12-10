@extends('layouts.admin')

@section('title', 'Manajemen User - Admin BKK')
@section('page-title', 'Manajemen User')

@section('content')
<div class="card">
    <div class="card-header bg-white py-3">
        <div class="d-flex justify-content-between align-items-center">
            <h5 class="mb-0 fw-bold">
                <i class="bi bi-people me-2"></i>Daftar User
            </h5>
            <a href="{{ route('admin.users.pending') }}" class="btn btn-warning btn-sm">
                <i class="bi bi-clock-history me-2"></i>Pending Approval
            </a>
        </div>
    </div>
    <div class="card-body">
        <!-- Filter -->
        <form method="GET" class="row g-3 mb-4">
            <div class="col-md-3">
                <select name="role" class="form-select" onchange="this.form.submit()">
                    <option value="all">Semua Role</option>
                    <option value="student" {{ $role == 'student' ? 'selected' : '' }}>Siswa/Alumni</option>
                    <option value="company" {{ $role == 'company' ? 'selected' : '' }}>Perusahaan</option>
                </select>
            </div>
            <div class="col-md-3">
                <select name="status" class="form-select" onchange="this.form.submit()">
                    <option value="all">Semua Status</option>
                    <option value="pending" {{ $status == 'pending' ? 'selected' : '' }}>Pending</option>
                    <option value="approved" {{ $status == 'approved' ? 'selected' : '' }}>Approved</option>
                    <option value="rejected" {{ $status == 'rejected' ? 'selected' : '' }}>Rejected</option>
                </select>
            </div>
            <div class="col-md-4">
                <input type="text" name="search" class="form-control" placeholder="Cari user..." value="{{ request('search') }}">
            </div>
            <div class="col-md-2">
                <button type="submit" class="btn btn-primary w-100">
                    <i class="bi bi-search"></i> Cari
                </button>
            </div>
        </form>

        <!-- Table -->
        @if($users->count() > 0)
        <div class="table-responsive">
            <table class="table table-hover">
                <thead class="table-light">
                    <tr>
                        <th>User</th>
                        <th>Role</th>
                        <th>Status</th>
                        <th>Tanggal Daftar</th>
                        <th class="text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($users as $user)
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
                            <span class="badge {{ get_status_badge_class($user->status) }}">
                                {{ get_status_label($user->status) }}
                            </span>
                        </td>
                        <td>
                            <small>{{ $user->created_at->format('d M Y') }}</small>
                        </td>
                        <td class="text-center">
                            <div class="btn-group btn-group-sm">
                                @if($user->status === 'pending')
                                    <form method="POST" action="{{ route('admin.users.approve', $user->id) }}" class="d-inline">
                                        @csrf
                                        @method('PATCH')
                                        <button type="submit" class="btn btn-success" title="Approve">
                                            <i class="bi bi-check-circle"></i>
                                        </button>
                                    </form>
                                @endif
                                <form method="POST" action="{{ route('admin.users.destroy', $user->id) }}" class="d-inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" 
                                            class="btn btn-danger"
                                            onclick="return confirm('Yakin ingin menghapus user ini?')"
                                            title="Hapus">
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
            {{ $users->links() }}
        </div>
        @else
        <div class="text-center py-5">
            <i class="bi bi-inbox text-muted display-1"></i>
            <p class="mt-3 text-muted">Tidak ada user ditemukan</p>
        </div>
        @endif
    </div>
</div>
@endsection