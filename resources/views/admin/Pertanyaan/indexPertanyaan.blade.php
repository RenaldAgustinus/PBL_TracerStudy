@extends('layouts.template')

@section('title', 'Pertanyaan')

@section('content')
<div class="card">
    <div class="card-header d-flex justify-content-between align-items-center">
        <h4 class="card-title">Daftar Pertanyaan</h4>
        <a href="{{ route('pertanyaan.create') }}" class="btn btn-primary">Tambah Data</a>
    </div>
    <div class="card-body">
        <table class="table align-items-center mb-0">
            <thead>
                <tr>
                    <th>No</th>
                    <th>Isi Pertanyaan</th>
                    <th>Kategori</th>
                    <th>Metode Jawaban</th>
                    <th>Created By</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($pertanyaan as $item)
                <tr>
                    <td>{{ $loop->iteration }}</td>
                    <td>{{ $item->isi_pertanyaan }}</td>
                    <td>{{ $item->kategori }}</td>
                    <td>{{ $item->metodejawaban == '1' ? 'Penilaian' : 'Bukan Penilaian' }}</td>
                    <td>{{ $item->admin->nama ?? 'Tidak Diketahui' }}</td>
                    <td>
                        <a href="{{ route('pertanyaan.edit', $item->id_pertanyaan) }}" class="btn btn-sm btn-primary">Edit</a>
                        <form action="{{ route('pertanyaan.destroy', $item->id_pertanyaan) }}" method="POST" style="display:inline;">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-sm btn-danger">Delete</button>
                        </form>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@endsection