{{-- filepath: c:\laragon\www\PBL_TracerStudy\PBL_TracerStudy\resources\views\admin\Admin\indexAdmin.blade.php --}}
@extends('layouts.template')

@section('title', 'Data Admin')

@section('content')
    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <div>
                <h4 class="card-title mb-0">Data Admin</h4>
                @if (request()->filled('search'))
                    <small class="text-muted">
                        <i class="fas fa-search"></i> Hasil pencarian untuk: "{{ request('search') }}"
                    </small>
                @endif
            </div>
            <a href="{{ route('admin.create') }}" class="btn btn-primary">
                <i class="fas fa-plus"></i> Tambah Admin
            </a>
        </div>
        <div class="card-body">
            @if (session('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            @if (session('error'))
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    {{ session('error') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            {{-- Search Form --}}
            <div class="row mb-4">
                <div class="col-md-6">
                    <form method="GET" action="{{ route('admin.index') }}" class="d-flex gap-2">
                        <div class="input-group">
                            <span class="input-group-text">
                                <i class="fas fa-search"></i>
                            </span>
                            <input type="text" name="search" class="form-control"
                                placeholder="Cari berdasarkan nama admin..."
                                value="{{ request('search') }}" autocomplete="off">
                            <button class="btn btn-primary" type="submit">
                                <i class="fas fa-search"></i> Cari
                            </button>
                            @if (request('search'))
                                <a href="{{ route('admin.index') }}" class="btn btn-outline-secondary">
                                    <i class="fas fa-times"></i> Reset
                                </a>
                            @endif
                        </div>
                    </form>
                </div>
            </div>

            {{-- Data Table --}}
            <div class="table-responsive">
                <table class="table table-striped table-hover align-items-center mb-0">
                    <thead class="table-light">
                        <tr>
                            <th class="text-center" style="width: 50px;">No</th>
                            <th>Username</th>
                            <th>Email</th>
                            <th>Nama Admin</th>
                            <th class="text-center">Role</th>
                            <th class="text-center" style="width: 150px;">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($admins as $admin)
                            <tr>
                                <td class="text-center">
                                    {{ ($admins->currentPage() - 1) * $admins->perPage() + $loop->iteration }}</td>
                                {{-- SESUDAH (tanpa bulatan) --}}
                                <td>
                                    <h6 class="mb-0 text-sm">{{ $admin->username }}</h6>
                                </td>
                                <td>
                                    <a href="mailto:{{ $admin->email }}" class="text-decoration-none text-primary">
                                        <i class="fas fa-envelope me-1"></i>{{ $admin->email }}
                                    </a>
                                </td>
                                <td>{{ $admin->nama }}</td>
                                <td class="text-center">
                                    @if ($admin->role === 'super_admin')
                                        <span class="badge bg-danger px-3 py-2">Super Admin</span>
                                    @else
                                        <span class="badge bg-primary px-3 py-2">Admin</span>
                                    @endif
                                </td>
                                <td class="text-center">
                                    <div class="btn-group" role="group">
                                        <a href="{{ route('admin.edit', $admin->id_admin) }}"
                                            class="btn btn-sm btn-warning" title="Edit Admin" data-bs-toggle="tooltip">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        @if ($admin->id_admin != Auth::user()->id_admin)
                                            <form action="{{ route('admin.destroy', $admin->id_admin) }}" method="POST"
                                                style="display:inline;">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-sm btn-danger" title="Hapus Admin"
                                                    data-bs-toggle="tooltip"
                                                    onclick="return confirm('Yakin ingin menghapus admin ini?')">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                            </form>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="text-center py-5">
                                    <div class="d-flex flex-column align-items-center">
                                        <div class="avatar avatar-xl rounded-circle bg-gradient-secondary mb-3">
                                            <i class="fas fa-search fa-2x text-white"></i>
                                        </div>
                                        <h5 class="text-muted mb-2">Tidak ada data yang ditemukan</h5>
                                        @if (request('search'))
                                            <p class="text-muted mb-3">Coba ubah kata kunci pencarian Anda</p>
                                            <a href="{{ route('admin.index') }}" class="btn btn-primary">
                                                <i class="fas fa-undo me-1"></i> Reset Pencarian
                                            </a>
                                        @else
                                            <p class="text-muted mb-3">Belum ada data admin</p>
                                            <a href="{{ route('admin.create') }}" class="btn btn-primary">
                                                <i class="fas fa-plus me-1"></i> Tambah Admin Pertama
                                            </a>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            {{-- Pagination --}}
            @if ($admins->hasPages())
                <div class="d-flex justify-content-between align-items-center mt-4">
                    <div class="text-muted">
                        Menampilkan {{ $admins->firstItem() ?? 0 }} sampai {{ $admins->lastItem() ?? 0 }}
                        dari {{ $admins->total() }} data
                    </div>
                    <div>
                        {{ $admins->appends(request()->query())->links() }}
                    </div>
                </div>
            @endif
        </div>
    </div>

    <script>
        // Initialize tooltips
        document.addEventListener('DOMContentLoaded', function() {
            var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
            var tooltipList = tooltipTriggerList.map(function(tooltipTriggerEl) {
                return new bootstrap.Tooltip(tooltipTriggerEl);
            });
        });
    </script>
@endsection
