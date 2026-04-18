{{-- filepath: c:\laragon\www\PBL_TracerStudy\PBL_TracerStudy\resources\views\admin\edit.blade.php --}}
@extends('layouts.template')

@section('title', 'Edit Pertanyaan')

@section('content')
<div class="card">
    <div class="card-header">
        <h4 class="card-title">Edit Pertanyaan</h4>
    </div>
    <div class="card-body">
        <form action="{{ route('pertanyaan.update', $pertanyaan->id_pertanyaan) }}" method="POST">
            @csrf
            @method('PUT')
            <div class="form-group">
                <label for="isi_pertanyaan">Isi Pertanyaan</label>
                <textarea name="isi_pertanyaan" id="isi_pertanyaan" class="form-control" required>{{ $pertanyaan->isi_pertanyaan }}</textarea>
            </div>
            <div class="form-group">
                <label for="kategori">Kategori</label>
                <select name="kategori" id="kategori" class="form-control" required>
                    <option value="tracer" {{ $pertanyaan->kategori == 'tracer' ? 'selected' : '' }}>Tracer</option>
                    <option value="pengguna_lulusan" {{ $pertanyaan->kategori == 'pengguna_lulusan' ? 'selected' : '' }}>Pengguna Lulusan</option>
                    <option value="umum" {{ $pertanyaan->kategori == 'umum' ? 'selected' : '' }}>Umum</option>
                </select>
            </div>
            <button type="submit" class="btn btn-success">Update</button>
        </form>
    </div>
</div>
@endsection