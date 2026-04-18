{{-- filepath: c:\laragon\www\PBL_TracerStudy\PBL_TracerStudy\resources\views\admin\tables.blade.php --}}
@extends('layouts.template')

@section('title', 'Dashboard')

@section('content')
    <div class="card">
        <div class="card-header">
            <h3 class="card-title">Daftar Alumni</h3>
            <div class="card-tools">
                <button onclick="modalAction('{{ url('/import-form') }}')" class="btn btn-info">Import Alumni</button>
                <a href="{{ url('/export_excel') }}" class="btn btn-primary"><i class="fa fa-file-excel"></i> Export
                    Excel</a>
            </div>
        </div>
        <div class="card-body">

            @if(session('success'))
                <div class="alert alert-success">{{ session('success') }}</div>
            @endif
            @if(session('error'))
                <div class="alert alert-danger">{{ session('error') }}</div>
            @endif

            <table class="table table-bordered table-sm table-striped table-hover" id="table-alumni">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>NIM</th>
                        <th>Nama Alumni</th>
                        <th>Prodi</th>
                        <th>No HP</th>
                        <th>Email</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody></tbody>
            </table>
        </div>
    </div>

    <div id="myModal" class="modal fade animate shake" tabindex="-1" data-backdrop="static" data-keyboard="false"
        data-width="75%"></div>
@endsection

@push('js')
<script>
    function modalAction(url = '') {
        $('#myModal').load(url, function () {
            $('#myModal').modal('show');
        });
    }

    var tableAlumni;
    $(document).ready(function () {
        tableAlumni = $('#table-alumni').DataTable({
            processing: true,
            serverSide: true,
            ajax: {
                url: "{{ url('/list') }}",
                type: "GET",
            },
            columns: [
                {
                    data: "DT_RowIndex",
                    className: "text-center",
                    width: "5%",
                    orderable: false,
                    searchable: false
                },
                { data: "nim", width: "15%" },
                { data: "nama_alumni", width: "25%" },
                { data: "prodi", width: "20%" },
                { data: "no_hp", width: "15%" },
                { data: "email", width: "20%" },
                {
                    data: null,
                    className: "text-center",
                    width: "15%",
                    orderable: false,
                    searchable: false,
                    render: function (data, type, row) {
                        return `
                            <button onclick="modalAction('/import/${row.nim}/edit_ajax')" class="btn btn-sm btn-warning">
                                Edit
                            </button>
                            <button onclick="deleteAlumni('${row.nim}')" class="btn btn-sm btn-danger">
                                Hapus
                            </button>
                        `;
                    }
                }
            ]
        });

        $('#table-alumni_filter input').unbind().bind().on('keyup', function (e) {
            if (e.keyCode == 13) {
                tableAlumni.search(this.value).draw();
            }
        });
    });

    function deleteAlumni(nim) {
        Swal.fire({
            title: 'Apakah Anda yakin?',
            text: "Data yang dihapus tidak dapat dikembalikan!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#aaa',
            confirmButtonText: 'Ya, hapus!',
            cancelButtonText: 'Batal'
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    url: `/import/${nim}/delete_ajax`,
                    type: 'DELETE',
                    data: {
                        _token: '{{ csrf_token() }}'
                    },
                    success: function (response) {
                        if (response.status) {
                            Swal.fire('Berhasil!', response.message, 'success');
                            tableAlumni.ajax.reload();
                        } else {
                            Swal.fire('Gagal!', response.message, 'error');
                        }
                    },
                    error: function () {
                        Swal.fire('Gagal!', 'Terjadi kesalahan saat menghapus data.', 'error');
                    }
                });
            }
        });
    }
</script>
@endpush
