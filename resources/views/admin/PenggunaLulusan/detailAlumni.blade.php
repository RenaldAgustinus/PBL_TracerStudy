@extends('layouts.template')

@section('title', 'Alumni - ' . $penggunaLulusan->nama_atasan)

@section('content')
{{-- Header Card --}}
<div class="card">
    <div class="card-header">
        <div class="d-flex justify-content-between align-items-center">
            <div>
                <h4 class="card-title mb-0">Alumni Terkait</h4>
                <small class="text-muted">Pengguna Lulusan: {{ $penggunaLulusan->nama_atasan }}</small>
            </div>
            <a href="{{ route('penggunaLulusan.index') }}" class="btn btn-secondary">
                <i class="fas fa-arrow-left"></i> Kembali
            </a>
        </div>
    </div>

    <div class="card-body">
        {{-- Info Pengguna Lulusan --}}
        <div class="row mb-4">
            <div class="col-md-12">
                <div class="card border-left-primary shadow-sm">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-4">
                                <h6 class="text-primary mb-1">Nama Atasan</h6>
                                <p class="mb-0"><strong>{{ $penggunaLulusan->nama_atasan }}</strong></p>
                            </div>
                            <div class="col-md-4">
                                <h6 class="text-primary mb-1">Jabatan</h6>
                                <p class="mb-0">{{ $penggunaLulusan->jabatan_atasan }}</p>
                            </div>
                            <div class="col-md-4">
                                <h6 class="text-primary mb-1">Email</h6>
                                <p class="mb-0">
                                    <a href="mailto:{{ $penggunaLulusan->email_atasan }}" class="text-decoration-none">
                                        {{ $penggunaLulusan->email_atasan }}
                                    </a>
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Statistics Cards --}}
        <div class="row mb-4">
            <div class="col-md-3">
                <div class="card border-0 shadow-sm">
                    <div class="card-body text-center">
                        <h5 class="text-primary">{{ $alumni->count() }}</h5>
                        <p class="mb-0 text-muted">Total Alumni</p>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card border-0 shadow-sm">
                    <div class="card-body text-center">
                        <h5 class="text-success">
                            {{ $alumni->filter(function($item) {
                                return !is_null($item->no_hp) && 
                                       !is_null($item->email) && 
                                       !is_null($item->tanggal_kerja_pertama) &&
                                       !is_null($item->id_profesi) &&
                                       !is_null($item->id_instansi);
                            })->count() }}
                        </h5>
                        <p class="mb-0 text-muted">Data Lengkap</p>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card border-0 shadow-sm">
                    <div class="card-body text-center">
                        <h5 class="text-info">
                            {{ $alumni->where('id_profesi', '!=', null)->count() }}
                        </h5>
                        <p class="mb-0 text-muted">Sudah Bekerja</p>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card border-0 shadow-sm">
                    <div class="card-body text-center">
                        <h5 class="text-warning">
                            @php
                                $avgMasaTunggu = $alumni->whereNotNull('masa_tunggu')->avg('masa_tunggu');
                            @endphp
                            {{ $avgMasaTunggu ? round($avgMasaTunggu, 1) : 0 }}
                        </h5>
                        <p class="mb-0 text-muted">Rata-rata Masa Tunggu (bulan)</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- Data Table Card --}}
<div class="card">
    <div class="card-header">
        <h5 class="card-title mb-0">Daftar Alumni ({{ $alumni->count() }} orang)</h5>
    </div>
    
    <div class="card-body">
        <div class="table-responsive">
            <table class="table align-items-center mb-0" id="alumniTable">
                <thead class="thead-light">
                    <tr>
                        <th>No</th>
                        <th>NIM</th>
                        <th>Nama Alumni</th>
                        <th>Prodi</th>
                        <th>Tahun Lulus</th>
                        <th>Profesi</th>
                        <th>Instansi</th>
                        <th>Masa Tunggu</th>
                        <th>Contact</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($alumni as $item)
                    <tr>
                        <td>{{ $loop->iteration }}</td>
                        <td><strong>{{ $item->nim }}</strong></td>
                        <td class="text-truncate-tooltip" title="{{ $item->nama_alumni }}">
                            {{ $item->nama_alumni }}
                        </td>
                        <td class="text-truncate-tooltip" title="{{ $item->prodi->nama_prodi ?? '-' }}">
                            {{ $item->prodi->nama_prodi ?? '-' }}
                        </td>
                        <td>
                            {{ $item->tgl_lulus ? \Carbon\Carbon::parse($item->tgl_lulus)->format('d/m/Y') : '-' }}
                        </td>
                        <td class="text-truncate-tooltip" title="{{ $item->profesi->nama_profesi ?? '-' }}">
                            {{ $item->profesi->nama_profesi ?? '-' }}
                        </td>
                        <td class="text-truncate-tooltip" title="{{ $item->instansi->nama_instansi ?? '-' }}">
                            {{ $item->instansi->nama_instansi ?? '-' }}
                        </td>
                        <td>
                            @if ($item->masa_tunggu !== null)
                                <span class="badge bg-{{ $item->masa_tunggu <= 6 ? 'success' : ($item->masa_tunggu <= 12 ? 'warning' : 'danger') }}">
                                    {{ $item->masa_tunggu }} bulan
                                </span>
                            @else
                                -
                            @endif
                        </td>
                        <td>
                            <div class="d-flex gap-1">
                                @if($item->no_hp)
                                    <a href="https://wa.me/{{ preg_replace('/[^0-9]/', '', $item->no_hp) }}" 
                                       target="_blank" 
                                       class="btn btn-sm btn-success" 
                                       title="WhatsApp: {{ $item->no_hp }}">
                                        <i class="fab fa-whatsapp"></i>
                                    </a>
                                @endif
                                @if($item->email)
                                    <a href="mailto:{{ $item->email }}" 
                                       class="btn btn-sm btn-info" 
                                       title="Email: {{ $item->email }}">
                                        <i class="fas fa-envelope"></i>
                                    </a>
                                @endif
                            </div>
                        </td>
                        <td>
                            <div class="btn-group" role="group">
                                <a href="{{ route('alumni.edit', $item->nim) }}" 
                                   class="btn btn-sm btn-primary" 
                                   title="Edit Alumni">
                                    <i class="fas fa-edit"></i>
                                </a>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="10" class="text-center py-4">
                            <div class="d-flex flex-column align-items-center">
                                <i class="fas fa-users fa-3x text-muted mb-3"></i>
                                <h5 class="text-muted">Belum ada alumni terkait</h5>
                                <p class="text-muted">Pengguna lulusan ini belum memiliki alumni yang terkait</p>
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
        $('#alumniTable').DataTable({
            "pageLength": 25,
            "order": [[2, 'asc']], // Default sort by nama alumni
            "columnDefs": [{
                "orderable": false,
                "targets": [0, 8, 9] // No, Contact, dan Actions column tidak bisa di-sort
            }],
            "searching": true, // Enable built-in search untuk tabel alumni
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
                "emptyTable": "Tidak ada data alumni",
                "zeroRecords": "Tidak ada alumni yang cocok",
                "lengthMenu": "Tampilkan _MENU_ alumni per halaman",
                "info": "Menampilkan _START_ sampai _END_ dari _TOTAL_ alumni",
                "infoEmpty": "Menampilkan 0 sampai 0 dari 0 alumni",
                "infoFiltered": "(difilter dari _MAX_ total alumni)",
                "search": "Cari alumni:",
                "paginate": {
                    "first": "Pertama",
                    "last": "Terakhir",
                    "next": "Berikutnya",
                    "previous": "Sebelumnya"
                }
            }
        });

        // Initialize tooltips
        $('[title]').tooltip();
    });
</script>

{{-- Custom CSS --}}
<style>
    .text-truncate-tooltip {
        max-width: 200px;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
    }
    
    .card.border-left-primary {
        border-left: 4px solid #007bff;
    }
</style>
@endsection