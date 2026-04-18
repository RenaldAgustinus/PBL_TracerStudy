{{-- filepath: c:\laragon\www\PBL_TracerStudy\PBL_TracerStudy\resources\views\admin\create.blade.php --}}
@extends('layouts.template')

@section('title', 'Tambah Pertanyaan')

@section('content')
<div class="card">
    <div class="card-header">
        <h4 class="card-title">Tambah Pertanyaan</h4>
    </div>
    <div class="card-body">
        <form action="{{ route('pertanyaan.store') }}" method="POST">
            @csrf
            <div class="form-group">
                <label for="isi_pertanyaan">Isi Pertanyaan</label>
                <textarea name="isi_pertanyaan" id="isi_pertanyaan" class="form-control" required></textarea>
            </div>
            <div class="form-group">
                <label for="kategori">Kategori</label>
                <select name="kategori" id="kategori" class="form-control" required>
                    <option value="tracer">Tracer</option>
                    <option value="pengguna_lulusan">Pengguna Lulusan</option>
                    <option value="umum">Umum</option>
                </select>
            </div>
            <div class="form-group">
                <label for="created_by">Dibuat Oleh</label>
                <select name="created_by" id="created_by" class="form-control" required>
                    @foreach ($admins as $admin)
                        <option value="{{ $admin->id_admin }}">{{ $admin->nama }}</option>
                    @endforeach
                </select>
            </div>
            <button type="submit" class="btn btn-success">Simpan</button>
            <a href="{{ route('pertanyaan.index') }}" class="btn btn-secondary">Batal</a>
        </form>
    </div>
</div>
@endsection