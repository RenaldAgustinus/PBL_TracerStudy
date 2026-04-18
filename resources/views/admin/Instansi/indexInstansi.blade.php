@extends('layouts.template')

@section('title', 'Instansi')

@section('content')
<div class="card">
    <div class="card-header d-flex justify-content-between align-items-center">
        <h4 class="card-title">Daftar Instansi</h4>
        <a href="{{ route('instansi.create') }}" class="btn btn-primary">Tambah Data</a>
    </div>
    <div class="card-body">
        @if(session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
        @endif
        
        <table class="table align-items-center mb-0">
            <thead>
                <tr>
                    <th>No</th>
                    <th>Nama Instansi</th>
                    <th>Jenis Instansi</th>
                    <th>Skala Instansi</th>
                    <th>Lokasi</th>
                    <th>No HP</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($instansi as $item)
                <tr>
                    <td>{{ $loop->iteration }}</td>
                    <td>{{ $item->nama_instansi }}</td>
                    <td>{{ $item->jenis_instansi }}</td>
                    <td>{{ $item->skala_instansi }}</td>
                    <td>{{ $item->lokasi_instansi }}</td>
                    <td>{{ $item->no_hp_instansi }}</td>
                    <td>
                        <a href="{{ route('instansi.edit', $item->id_instansi) }}" class="btn btn-sm btn-primary">Edit</a>
                        <form action="{{ route('instansi.destroy', $item->id_instansi) }}" method="POST" style="display:inline;">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Apakah Anda yakin ingin menghapus instansi ini?')">Delete</button>
                        </form>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@endsection