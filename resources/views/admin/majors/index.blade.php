@extends('layouts.admin')

@section('title', 'Data Master Jurusan - Admin BKK')
@section('page-title', 'Data Master Jurusan')

@section('content')
<div class="card">
    <div class="card-header bg-white py-3">
        <div class="d-flex justify-content-between align-items-center">
            <h5 class="mb-0 fw-bold">
                <i class="bi bi-mortarboard me-2"></i>Daftar Jurusan
            </h5>
            <a href="{{ route('admin.majors.create') }}" class="btn btn-primary btn-sm">
                <i class="bi bi-plus-circle me-2"></i>Tambah Jurusan
            </a>
        </div>
    </div>
    <div class="card-body">
        @if($majors->count() > 0)
        <div class="table-responsive">
            <table class="table table-hover">
                <thead class="table-light">
                    <tr>
                        <th width="10%">Kode</th>
                        <th>Nama Jurusan</th>
                        <th>Deskripsi</th>
                        <th width="10%" class="text-center">Urutan</th>
                        <th width="10%" class="text-center">Status</th>
                        <th width="15%" class="text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($majors as $major)
                    <tr>
                        <td><span class="badge bg-secondary">{{ $major->code }}</span></td>
                        <td><strong>{{ $major->name }}</strong></td>
                        <td>
                            @if($major->description)
                                <small class="text-muted">{{ Str::limit($major->description, 50) }}</small>
                            @else
                                <small class="text-muted fst-italic">-</small>
                            @endif
                        </td>
                        <td class="text-center">
                            <span class="badge bg-info">{{ $major->order }}</span>
                        </td>
                        <td class="text-center">
                            <form method="POST" action="{{ route('admin.majors.toggle-status', $major->id) }}" class="d-inline">
                                @csrf
                                @method('PATCH')
                                <button type="submit" 
                                        class="btn btn-sm {{ $major->is_active ? 'btn-success' : 'btn-secondary' }}"
                                        title="{{ $major->is_active ? 'Aktif - Klik untuk nonaktifkan' : 'Nonaktif - Klik untuk aktifkan' }}">
                                    <i class="bi bi-{{ $major->is_active ? 'check-circle-fill' : 'x-circle' }}"></i>
                                    {{ $major->is_active ? 'Aktif' : 'Nonaktif' }}
                                </button>
                            </form>
                        </td>
                        <td class="text-center">
                            <div class="btn-group btn-group-sm">
                                <a href="{{ route('admin.majors.edit', $major->id) }}" 
                                   class="btn btn-warning" 
                                   title="Edit">
                                    <i class="bi bi-pencil"></i>
                                </a>
                                <form method="POST" action="{{ route('admin.majors.destroy', $major->id) }}" class="d-inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" 
                                            class="btn btn-danger"
                                            title="Hapus"
                                            onclick="return confirm('Yakin ingin menghapus jurusan {{ $major->name }}? Data siswa tidak akan terhapus.')">
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
            {{ $majors->links() }}
        </div>
        @else
        <div class="text-center py-5">
            <i class="bi bi-inbox text-muted display-1"></i>
            <p class="mt-3 text-muted">Belum ada data jurusan</p>
            <a href="{{ route('admin.majors.create') }}" class="btn btn-primary">
                <i class="bi bi-plus-circle me-2"></i>Tambah Jurusan Pertama
            </a>
        </div>
        @endif
    </div>
</div>

<div class="card mt-4">
    <div class="card-body">
        <h6 class="fw-bold mb-3">
            <i class="bi bi-info-circle me-2"></i>Informasi
        </h6>
        <ul class="small text-muted mb-0">
            <li>Kode jurusan harus unik dan tidak boleh sama.</li>
            <li>Jurusan yang nonaktif tidak akan muncul di form registrasi siswa/alumni.</li>
            <li>Urutan menentukan tampilan jurusan di dropdown (semakin kecil angka, semakin atas).</li>
            <li>Jurusan tidak dapat dihapus jika masih digunakan oleh siswa/alumni.</li>
        </ul>
    </div>
</div>
@endsection