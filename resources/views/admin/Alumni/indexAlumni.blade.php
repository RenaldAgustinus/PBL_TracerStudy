{{-- filepath: c:\laragon\www\PBL_TracerStudy\PBL_TracerStudy\resources\views\admin\Alumni\indexAlumni.blade.php --}}
@extends('layouts.template')

@section('title', 'Data Alumni')

@section('content')
    <div class="card">
        {{-- Notifikasi --}}
        @if (session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif
        @if (session('warning'))
            <div class="alert alert-warning alert-dismissible fade show" role="alert">
                {{ session('warning') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif
        @if (session('error'))
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                {{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        {{-- Show import errors --}}
        @if (session('import_errors'))
            <div class="alert alert-warning alert-dismissible fade show" role="alert">
                <h6>Detail Error Import:</h6>
                <ul class="mb-0">
                    @foreach (session('import_errors') as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        {{-- Header Card --}}
        <div class="card-header d-flex justify-content-between align-items-center">
            <div>
                <h4 class="card-title mb-0">Data Alumni</h4>
                @if (request()->anyFilled([
                        'search',
                        'filter_prodi',
                        'filter_jurusan',
                        'filter_tahun_masuk',
                        'filter_profesi',
                        'filter_status',
                        'filter_tahun_lulus',
                    ]))
                    <small class="text-muted">
                        <i class="fas fa-filter"></i> Menampilkan {{ $alumni->count() }} dari total alumni (Terfilter)
                    </small>
                @else
                    <small class="text-muted">Menampilkan {{ $alumni->count() }} total alumni</small>
                @endif
            </div>
            <div class="d-flex gap-2">
                {{-- Tombol Filter --}}
                <button class="btn btn-outline-secondary" id="toggleFilterBtn" onclick="toggleFilter()">
                    <i class="fas fa-filter"></i> Filter
                </button>

                <a href="{{ route('alumni.create') }}" class="btn btn-primary">
                    <i class="fas fa-plus"></i> Tambah Data
                </a>
                <button class="btn btn-info" data-bs-toggle="modal" data-bs-target="#modalImport">
                    <i class="fas fa-file-import"></i> Import Alumni
                </button>

                {{-- Dropdown Download dengan perbaikan --}}
                <div class="dropdown">
                    <button class="btn btn-success dropdown-toggle" type="button" id="dropdownExport"
                        data-bs-toggle="dropdown" aria-expanded="false" data-bs-auto-close="true">
                        <i class="fas fa-download"></i> Download
                    </button>
                    <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="dropdownExport"
                        style="min-width: 300px; max-height: 400px; overflow-y: auto;">
                        <li>
                            <h6 class="dropdown-header">
                                <i class="fas fa-file-download me-2"></i>Template
                            </h6>
                        </li>
                        <li>
                            <a class="dropdown-item d-flex align-items-center py-2" href="{{ route('alumni.template') }}">
                                <i class="fas fa-file-excel text-success me-3"></i>
                                <div>
                                    <div class="fw-bold">Template Import</div>
                                    <small class="text-muted">File Excel untuk import data</small>
                                </div>
                            </a>
                        </li>
                        <li>
                            <hr class="dropdown-divider">
                        </li>
                        <li>
                            <h6 class="dropdown-header">
                                <i class="fas fa-download me-2"></i>Export Data
                            </h6>
                        </li>
                        <li>
                            <a class="dropdown-item d-flex align-items-center py-2" href="#"
                                onclick="exportData('semua')">
                                <i class="fas fa-users text-primary me-3"></i>
                                <div class="flex-grow-1">
                                    <div class="fw-bold">Semua Alumni</div>
                                    @if (request()->anyFilled([
                                            'search',
                                            'filter_prodi',
                                            'filter_jurusan',
                                            'filter_tahun_masuk',
                                            'filter_profesi',
                                            'filter_tahun_lulus',
                                        ]))
                                        <small class="text-muted">{{ $alumni->count() }} data terfilter</small>
                                    @else
                                        <small class="text-muted">{{ $alumni->count() }} total data</small>
                                    @endif
                                </div>
                            </a>
                        </li>
                        <li>
                            <a class="dropdown-item d-flex align-items-center py-2" href="#"
                                onclick="exportData('sudah')">
                                <i class="fas fa-user-check text-success me-3"></i>
                                <div class="flex-grow-1">
                                    <div class="fw-bold">Alumni Sudah Mengisi Lengkap</div>
                                    <small class="text-muted">
                                        {{ $alumni->filter(function ($item) {
                                                return !is_null($item->no_hp) &&
                                                    !is_null($item->email) &&
                                                    !is_null($item->tanggal_kerja_pertama) &&
                                                    !is_null($item->tanggal_mulai_instansi) &&
                                                    !is_null($item->masa_tunggu) &&
                                                    !is_null($item->id_profesi) &&
                                                    !is_null($item->id_pengguna_lulusan) &&
                                                    !is_null($item->id_instansi) &&
                                                    trim($item->no_hp) !== '' &&
                                                    trim($item->email) !== '';
                                            })->count() }}
                                        data lengkap
                                    </small>
                                </div>
                            </a>
                        </li>
                        <li>
                            <a class="dropdown-item d-flex align-items-center py-2" href="#"
                                onclick="exportData('belum')">
                                <i class="fas fa-user-times text-warning me-3"></i>
                                <div class="flex-grow-1">
                                    <div class="fw-bold">Alumni Belum Mengisi Lengkap</div>
                                    <small class="text-muted">
                                        {{ $alumni->filter(function ($item) {
                                                return is_null($item->no_hp) ||
                                                    is_null($item->email) ||
                                                    is_null($item->tanggal_kerja_pertama) ||
                                                    is_null($item->tanggal_mulai_instansi) ||
                                                    is_null($item->masa_tunggu) ||
                                                    is_null($item->id_profesi) ||
                                                    is_null($item->id_pengguna_lulusan) ||
                                                    is_null($item->id_instansi) ||
                                                    trim($item->no_hp ?? '') === '' ||
                                                    trim($item->email ?? '') === '';
                                            })->count() }}
                                        data belum lengkap
                                    </small>
                                </div>
                            </a>
                        </li>
                    </ul>
                </div>
            </div>
        </div>

        {{-- Body Card --}}
        <div class="card-body">
            {{-- Statistics Cards dengan kriteria baru --}}
            <div class="row mb-4">
                <div class="col-md-3">
                    <div class="card border-0 shadow-sm">
                        <div class="card-body text-center">
                            <h5 class="text-primary">{{ $alumni->count() }}</h5>
                            <p class="mb-0 text-muted">Total
                                Alumni{{ request()->anyFilled(['search', 'filter_prodi']) ? ' (Terfilter)' : '' }}</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card border-0 shadow-sm">
                        <div class="card-body text-center">
                            <h5 class="text-success">
                                {{-- Kriteria baru: SEMUA field harus terisi --}}
                                {{ $alumni->filter(function ($item) {
                                        return !is_null($item->no_hp) &&
                                            !is_null($item->email) &&
                                            !is_null($item->tanggal_kerja_pertama) &&
                                            !is_null($item->tanggal_mulai_instansi) &&
                                            !is_null($item->masa_tunggu) &&
                                            !is_null($item->id_profesi) &&
                                            !is_null($item->id_pengguna_lulusan) &&
                                            !is_null($item->id_instansi) &&
                                            trim($item->no_hp) !== '' &&
                                            trim($item->email) !== '';
                                    })->count() }}
                            </h5>
                            <p class="mb-0 text-muted">Sudah Mengisi Lengkap</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card border-0 shadow-sm">
                        <div class="card-body text-center">
                            <h5 class="text-warning">
                                {{-- Kriteria baru: Ada minimal 1 field yang kosong --}}
                                {{ $alumni->filter(function ($item) {
                                        return is_null($item->no_hp) ||
                                            is_null($item->email) ||
                                            is_null($item->tanggal_kerja_pertama) ||
                                            is_null($item->tanggal_mulai_instansi) ||
                                            is_null($item->masa_tunggu) ||
                                            is_null($item->id_profesi) ||
                                            is_null($item->id_pengguna_lulusan) ||
                                            is_null($item->id_instansi) ||
                                            trim($item->no_hp ?? '') === '' ||
                                            trim($item->email ?? '') === '';
                                    })->count() }}
                            </h5>
                            <p class="mb-0 text-muted">Belum Mengisi Lengkap</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card border-0 shadow-sm">
                        <div class="card-body text-center">
                            <h5 class="text-info">
                                {{-- Rata-rata kelengkapan data --}}
                                @php
                                    $totalCompletion = 0;
                                    $totalAlumni = $alumni->count();

                                    if ($totalAlumni > 0) {
                                        foreach ($alumni as $item) {
                                            $requiredFields = [
                                                'no_hp',
                                                'email',
                                                'tanggal_kerja_pertama',
                                                'tanggal_mulai_instansi',
                                                'masa_tunggu',
                                                'id_profesi',
                                                'id_pengguna_lulusan',
                                                'id_instansi',
                                            ];
                                            $completedFields = 0;

                                            foreach ($requiredFields as $field) {
                                                if (!is_null($item->$field) && trim($item->$field) !== '') {
                                                    $completedFields++;
                                                }
                                            }

                                            $totalCompletion += ($completedFields / count($requiredFields)) * 100;
                                        }

                                        $avgCompletion = round($totalCompletion / $totalAlumni, 1);
                                    } else {
                                        $avgCompletion = 0;
                                    }
                                @endphp
                                {{ $avgCompletion }}%
                            </h5>
                            <p class="mb-0 text-muted">Rata-rata Kelengkapan</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Filter Section - Hidden by default --}}
    <div id="filterSection" style="display: none;">
        @include('admin.Alumni.search-filter')
    </div>

    {{-- Data Table Card --}}
    <div class="card">
        <div class="card-body">
            <div class="table-container">
                <div class="table-responsive">
                    <table class="table align-items-center mb-0" id="alumniTable">
                        <thead class="thead-light">
                            <tr>
                                <th>No</th>
                                <th>NIM</th>
                                <th>Nama Alumni</th>
                                <th>Prodi</th>
                                <th>Jurusan</th>
                                <th>No HP</th>
                                <th>Email</th>
                                <th>Tahun Masuk</th>
                                <th>Tanggal Lulus</th>
                                <th>Profesi</th>
                                <th>Instansi</th>
                                <th>Masa Tunggu</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($alumni as $item)
                                <tr>
                                    <td>{{ $loop->iteration }}</td>
                                    <td><strong>{{ $item->nim }}</strong></td>
                                    <td class="text-truncate-tooltip" title="{{ $item->nama_alumni }}">
                                        {{ $item->nama_alumni }}</td>
                                    <td class="text-truncate-tooltip" title="{{ $item->prodi->nama_prodi ?? '-' }}">
                                        {{ $item->prodi->nama_prodi ?? '-' }}</td>
                                    <td class="text-truncate-tooltip" title="{{ $item->prodi->jurusan ?? '-' }}">
                                        {{ $item->prodi->jurusan ?? '-' }}</td>
                                    <td>{{ $item->no_hp ?? '-' }}</td>
                                    <td class="text-truncate-tooltip" title="{{ $item->email ?? '-' }}">
                                        {{ $item->email ?? '-' }}</td>
                                    <td>{{ $item->tahun_masuk ?? '-' }}</td>
                                    <td>{{ $item->tgl_lulus ? \Carbon\Carbon::parse($item->tgl_lulus)->format('d/m/Y') : '-' }}
                                    </td>
                                    <td class="text-truncate-tooltip" title="{{ $item->profesi->nama_profesi ?? '-' }}">
                                        {{ $item->profesi->nama_profesi ?? '-' }}</td>
                                    <td class="text-truncate-tooltip"
                                        title="{{ $item->instansi->nama_instansi ?? '-' }}">
                                        {{ $item->instansi->nama_instansi ?? '-' }}</td>
                                    <td>
                                        @if ($item->masa_tunggu !== null)
                                            <span
                                                class="badge bg-{{ $item->masa_tunggu <= 6 ? 'success' : ($item->masa_tunggu <= 12 ? 'warning' : 'danger') }}">
                                                {{ $item->masa_tunggu }} bulan
                                            </span>
                                        @else
                                            -
                                        @endif
                                    </td>
                                    <td>
                                        <div class="btn-group" role="group">
                                            <a href="{{ route('alumni.edit', $item->nim) }}"
                                                class="btn btn-sm btn-primary" title="Edit">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                            <form action="{{ route('alumni.destroy', $item->nim) }}" method="POST"
                                                style="display:inline;">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-sm btn-danger" title="Hapus"
                                                    onclick="return confirm('Yakin ingin menghapus data ini?')">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="13" class="text-center py-4">
                                        <div class="d-flex flex-column align-items-center">
                                            <i class="fas fa-search fa-3x text-muted mb-3"></i>
                                            <h5 class="text-muted">Tidak ada data yang ditemukan</h5>
                                            <p class="text-muted">Coba ubah kriteria pencarian atau filter Anda</p>
                                            <a href="{{ route('alumni.index') }}" class="btn btn-primary">
                                                <i class="fas fa-undo"></i> Reset Filter
                                            </a>
                                        </div>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    {{-- Include Modal Import --}}
    @include('admin.Alumni.import')

    {{-- Include jQuery --}}
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    {{-- Include SweetAlert2 --}}
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    {{-- JavaScript untuk Export, DataTable, dan Toggle Filter --}}
    <script>
        // Function untuk toggle filter visibility
        function toggleFilter() {
            const filterSection = document.getElementById('filterSection');
            const toggleBtn = document.getElementById('toggleFilterBtn');
            const icon = toggleBtn.querySelector('i');

            if (filterSection.style.display === 'none' || filterSection.style.display === '') {
                // Show filter
                filterSection.style.display = 'block';
                toggleBtn.classList.remove('btn-outline-secondary');
                toggleBtn.classList.add('btn-secondary');
                icon.classList.remove('fa-filter');
                icon.classList.add('fa-filter-circle-xmark');

                // Smooth scroll to filter
                filterSection.scrollIntoView({
                    behavior: 'smooth',
                    block: 'nearest'
                });
            } else {
                // Hide filter
                filterSection.style.display = 'none';
                toggleBtn.classList.remove('btn-secondary');
                toggleBtn.classList.add('btn-outline-secondary');
                icon.classList.remove('fa-filter-circle-xmark');
                icon.classList.add('fa-filter');
            }
        }

        // Function untuk export data dengan mempertahankan filter
        function exportData(status) {
            // Get current URL parameters (filters)
            const urlParams = new URLSearchParams(window.location.search);

            // Add status parameter
            urlParams.set('status', status);

            // Build export URL
            const exportUrl = "{{ route('alumni.export') }}" + '?' + urlParams.toString();

            // Show loading indication
            Swal.fire({
                title: 'Memproses Export...',
                text: 'Mohon tunggu, sedang mempersiapkan file Excel',
                allowOutsideClick: false,
                allowEscapeKey: false,
                showConfirmButton: false,
                willOpen: () => {
                    Swal.showLoading();
                }
            });

            // Create hidden link and trigger download
            const link = document.createElement('a');
            link.href = exportUrl;
            link.style.display = 'none';
            document.body.appendChild(link);
            link.click();
            document.body.removeChild(link);

            // Close loading after delay
            setTimeout(() => {
                Swal.close();

                // Show success message
                Swal.fire({
                    icon: 'success',
                    title: 'Export Berhasil!',
                    text: 'File Excel telah diunduh',
                    timer: 2000,
                    showConfirmButton: false
                });
            }, 2000);
        }

        $(document).ready(function() {
            // Check if there are any active filters and show filter section
            const hasActiveFilters =
                {{ request()->anyFilled(['search', 'filter_prodi', 'filter_jurusan', 'filter_tahun_masuk', 'filter_profesi', 'filter_status', 'filter_tahun_lulus']) ? 'true' : 'false' }};

            if (hasActiveFilters) {
                // Auto-show filter if there are active filters
                const filterSection = document.getElementById('filterSection');
                const toggleBtn = document.getElementById('toggleFilterBtn');
                const icon = toggleBtn.querySelector('i');

                filterSection.style.display = 'block';
                toggleBtn.classList.remove('btn-outline-secondary');
                toggleBtn.classList.add('btn-secondary');
                icon.classList.remove('fa-filter');
                icon.classList.add('fa-filter-circle-xmark');
            }

            // DataTable initialization dengan pengaturan sorting yang benar
            var table = $('#alumniTable').DataTable({
                "pageLength": 25,
                "order": [], // Hapus default sorting agar urut sesuai data asli
                "columnDefs": [{
                        "orderable": false,
                        "targets": [0, 12] // No dan Actions column tidak bisa di-sort
                    },
                    {
                        "type": "string", // Treat NIM sebagai string untuk sorting yang konsisten
                        "targets": [1] // NIM column
                    }
                ],
                "searching": false,
                "scrollX": true,
                "scrollCollapse": true,
                "processing": true,
                "drawCallback": function(settings) {
                    // Re-number kolom No setelah sorting/paging
                    var api = this.api();
                    var start = api.page.info().start;
                    api.column(0, {
                        page: 'current'
                    }).nodes().each(function(cell, i) {
                        cell.innerHTML = start + i + 1;
                    });
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

            // Initialize tooltips untuk cell yang terpotong
            $('[title]').tooltip();

            // SweetAlert handling
            function getQueryParam(param) {
                const urlParams = new URLSearchParams(window.location.search);
                return urlParams.get(param);
            }

            const status = getQueryParam('status');
            const message = getQueryParam('message');

            if (status && message) {
                Swal.fire({
                    icon: status === 'success' ? 'success' : 'error',
                    title: status === 'success' ? 'Berhasil' : 'Gagal',
                    text: decodeURIComponent(message),
                    timer: 4000,
                    timerProgressBar: true,
                    willClose: () => {
                        const url = window.location.origin + window.location.pathname;
                        window.history.replaceState({}, document.title, url);
                    }
                });
            }
        });
    </script>

    {{-- Custom CSS untuk dropdown --}}
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
    </style>
@endsection
