@extends('layouts.template')

@section('title', 'Pengguna Lulusan')

@section('content')
    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <div>
                <h4 class="card-title mb-0">Daftar Pengguna Lulusan</h4>
                @if (request()->filled('search'))
                    <small class="text-muted">
                        <i class="fas fa-search"></i> Hasil pencarian untuk: "{{ request('search') }}"
                    </small>
                @endif
            </div>
            <div class="d-flex gap-2">
                <a href="{{ route('penggunaLulusan.create') }}" class="btn btn-primary">
                    <i class="fas fa-plus"></i> Tambah Data
                </a>

                {{-- Dropdown Export dengan perbaikan --}}
                <div class="dropdown">
                    <button class="btn btn-success dropdown-toggle" type="button" id="dropdownExport"
                        data-bs-toggle="dropdown" aria-expanded="false" data-bs-auto-close="true">
                        <i class="fas fa-download"></i> Export
                    </button>
                    <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="dropdownExport" style="min-width: 280px;">
                        <li>
                            <h6 class="dropdown-header">
                                <i class="fas fa-file-download me-2"></i>Export Data Alumni Survey
                            </h6>
                        </li>
                        <li>
                            <a class="dropdown-item d-flex align-items-start py-3"
                                href="{{ route('penggunaLulusan.exportSudahIsiSurvey') }}">
                                <i class="fas fa-file-excel text-success me-3 mt-1"></i>
                                <div class="flex-grow-1">
                                    <div class="fw-bold">Alumni Sudah Survey</div>
                                    <small class="text-muted">Data alumni yang sudah mengisi survey</small>
                                </div>
                            </a>
                        </li>
                        <li>
                            <hr class="dropdown-divider">
                        </li>
                        <li>
                            <a class="dropdown-item d-flex align-items-start py-3"
                                href="{{ route('penggunaLulusan.export') }}">
                                <i class="fas fa-file-excel text-warning me-3 mt-1"></i>
                                <div class="flex-grow-1">
                                    <div class="fw-bold">Alumni Belum Survey</div>
                                    <small class="text-muted">Data alumni yang belum mengisi survey</small>
                                </div>
                            </a>
                        </li>
                    </ul>
                </div>
            </div>
        </div>

        <div class="card-body">
            @if (session('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            {{-- Search Form --}}
            <div class="row mb-4">
                <div class="col-md-6">
                    <form method="GET" action="{{ route('penggunaLulusan.index') }}" class="d-flex gap-2">
                        <div class="input-group">
                            <span class="input-group-text">
                                <i class="fas fa-search"></i>
                            </span>
                            <input type="text" name="search" class="form-control"
                                placeholder="Cari berdasarkan nama, jabatan, atau email atasan..."
                                value="{{ request('search') }}" autocomplete="off">
                            <button class="btn btn-primary" type="submit">
                                <i class="fas fa-search"></i> Cari
                            </button>
                            @if (request('search'))
                                <a href="{{ route('penggunaLulusan.index') }}" class="btn btn-outline-secondary">
                                    <i class="fas fa-times"></i> Reset
                                </a>
                            @endif
                        </div>
                    </form>
                </div>
            </div>

            {{-- Data Table --}}
            <div class="table-responsive">
                <table class="table table-striped table-hover align-items-center mb-0" id="penggunaLulusanTable">
                    <thead class="table-light">
                        <tr>
                            <th class="text-center" style="width: 50px; min-width: 50px;">No</th>
                            <th style="width: 250px; min-width: 200px;">Nama Atasan</th>
                            <th style="width: 200px; min-width: 150px;">Jabatan Atasan</th>
                            <th style="width: 300px; min-width: 250px;">Email Atasan</th>
                            <th class="text-center" style="width: 150px; min-width: 120px;">Jumlah Alumni</th>
                            <th class="text-center" style="width: 150px; min-width: 120px;">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($penggunaLulusan as $item)
                            <tr>
                                <td class="text-center">{{ $loop->iteration }}</td>
                                <td>
                                    <div class="d-flex align-items-center">
                                        <div class="avatar avatar-sm rounded-circle bg-gradient-primary text-white me-2">
                                            <i class="fas fa-user"></i>
                                        </div>
                                        <div>
                                            <h6 class="mb-0 text-sm">{{ $item->nama_atasan }}</h6>
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <span class="badge bg-info px-3 py-2">{{ $item->jabatan_atasan }}</span>
                                </td>
                                <td>
                                    <a href="mailto:{{ $item->email_atasan }}"
                                        class="text-decoration-none text-primary text-truncate d-block"
                                        style="max-width: 280px;" title="{{ $item->email_atasan }}">
                                        <i class="fas fa-envelope me-1"></i>{{ $item->email_atasan }}
                                    </a>
                                </td>
                                <td class="text-center">
                                    @php
                                        $jumlahAlumni = $item->alumni()->count();
                                    @endphp
                                    @if ($jumlahAlumni > 0)
                                        <span class="badge bg-success px-3 py-2">
                                            <i class="fas fa-users me-1"></i>{{ $jumlahAlumni }} Alumni
                                        </span>
                                    @else
                                        <span class="badge bg-secondary px-3 py-2">
                                            <i class="fas fa-user-slash me-1"></i>0 Alumni
                                        </span>
                                    @endif
                                </td>
                                <td class="text-center">
                                    <div class="btn-group" role="group">
                                        {{-- Tombol Lihat Alumni --}}
                                        @if ($item->alumni()->count() > 0)
                                            <a href="{{ route('penggunaLulusan.showAlumni', $item->id_pengguna_lulusan) }}"
                                                class="btn btn-sm btn-info"
                                                title="Lihat Alumni ({{ $item->alumni()->count() }} orang)"
                                                data-bs-toggle="tooltip">
                                                <i class="fas fa-users"></i>
                                            </a>
                                        @endif

                                        {{-- Tombol Edit --}}
                                        <a href="{{ route('penggunaLulusan.edit', $item->id_pengguna_lulusan) }}"
                                            class="btn btn-sm btn-primary" title="Edit Data" data-bs-toggle="tooltip">
                                            <i class="fas fa-edit"></i>
                                        </a>

                                        {{-- Tombol Delete --}}
                                        <form action="{{ route('penggunaLulusan.destroy', $item->id_pengguna_lulusan) }}"
                                            method="POST" style="display:inline;">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-danger" title="Hapus Data"
                                                data-bs-toggle="tooltip"
                                                onclick="return confirm('Apakah Anda yakin ingin menghapus data ini?\n\nPerhatian: {{ $item->alumni()->count() }} alumni yang terkait akan kehilangan referensi atasan.')">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </form>
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
                                            <a href="{{ route('penggunaLulusan.index') }}" class="btn btn-primary">
                                                <i class="fas fa-undo me-1"></i> Reset Pencarian
                                            </a>
                                        @else
                                            <p class="text-muted mb-3">Belum ada data pengguna lulusan</p>
                                            <a href="{{ route('penggunaLulusan.create') }}" class="btn btn-primary">
                                                <i class="fas fa-plus me-1"></i> Tambah Data Pertama
                                            </a>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    {{-- Include jQuery --}}
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

    {{-- DataTable Initialization --}}
    <script>
        $(document).ready(function() {
            $('#penggunaLulusanTable').DataTable({
                "pageLength": 25,
                "order": [
                    [1, 'asc']
                ], // Default sort by nama atasan
                "columnDefs": [{
                    "orderable": false,
                    "targets": [0, 5] // No dan Actions column tidak bisa di-sort
                }],
                "searching": false, // Disable built-in search karena kita pakai custom search
                "scrollX": true,
                "scrollCollapse": true,
                "processing": true,
                "autoWidth": false, // Disable auto width calculation
                "drawCallback": function(settings) {
                    // Re-number kolom No setelah sorting/paging
                    var api = this.api();
                    var start = api.page.info().start;
                    api.column(0, {
                        page: 'current'
                    }).nodes().each(function(cell, i) {
                        cell.innerHTML = start + i + 1;
                    });

                    // Re-initialize tooltips after table redraw
                    $('[data-bs-toggle="tooltip"]').tooltip();
                },
                "language": {
                    "processing": 'Memuat data...',
                    "emptyTable": "Tidak ada data yang tersedia",
                    "zeroRecords": "Tidak ada data yang cocok",
                    "lengthMenu": "Tampilkan _MENU_ data per halaman",
                    "info": "Menampilkan _START_ sampai _END_ dari _TOTAL_ data",
                    "infoEmpty": "Menampilkan 0 sampai 0 dari 0 data",
                    "infoFiltered": "(difilter dari _MAX_ total data)",
                    "paginate": {
                        "first": "Pertama",
                        "last": "Terakhir",
                        "next": "Berikutnya",
                        "previous": "Sebelumnya"
                    }
                }
            });

            // Initialize tooltips
            $('[data-bs-toggle="tooltip"]').tooltip();
        });
    </script>

    <style>
        .dropdown-menu {
            border: 1px solid #dee2e6;
            box-shadow: 0 .5rem 1rem rgba(0, 0, 0, .15);
            border-radius: 0.375rem;
            z-index: 1050 !important;
        }

        .dropdown-menu.show {
            display: block !important;
        }

        .dropdown-item:hover {
            background-color: #f8f9fa;
        }

        .dropdown-header {
            font-weight: 600;
            color: #495057;
            border-bottom: 1px solid #dee2e6;
            margin-bottom: 0.5rem;
            padding-bottom: 0.5rem;
        }

        .card-header {
            overflow: visible !important;
        }

        .card {
            overflow: visible !important;
        }

        /* Ensure dropdown stays above other elements */
        .dropdown {
            position: relative;
            z-index: 1000;
        }

        .dropdown-menu {
            position: absolute !important;
            will-change: transform;
        }

        /* Better spacing for dropdown items with descriptions */
        .dropdown-item.d-flex {
            white-space: normal;
            line-height: 1.4;
        }

        .dropdown-item .fw-bold {
            color: #212529;
        }

        .dropdown-item small {
            line-height: 1.3;
        }
    </style>
@endsection
