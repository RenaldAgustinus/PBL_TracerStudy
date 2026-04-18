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
                <select name="kategori" id="kategori" class="form-control" required onchange="toggleMetodeJawaban()">
                    <option value="tracer">Tracer</option>
                    <option value="pengguna_lulusan">Pengguna Lulusan</option>
                    <option value="umum">Umum</option>
                </select>
            </div>
            <div class="form-group" id="metodejawabanGroup">
                <label>Pertanyaan Penilaian ?</label>
                <div class="form-check">
                    <input class="form-check-input" type="radio" name="metodejawaban" id="metodejawaban1" value="1">
                    <label class="form-check-label" for="metodejawaban1">Ya</label>
                </div>
                <div class="form-check">
                    <input class="form-check-input" type="radio" name="metodejawaban" id="metodejawaban2" value="2" checked>
                    <label class="form-check-label" for="metodejawaban2">Tidak</label>
                </div>
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

<script>
    function toggleMetodeJawaban() {
        const kategori = document.getElementById('kategori').value;
        const metodejawabanGroup = document.getElementById('metodejawabanGroup');
        
        if (kategori === 'pengguna_lulusan') {
            metodejawabanGroup.style.display = 'block';
        } else {
            metodejawabanGroup.style.display = 'none';
            // Set default value for non-pengguna_lulusan
            document.getElementById('metodejawaban2').checked = true;
        }
    }

    // Run on page load
    document.addEventListener('DOMContentLoaded', function() {
        toggleMetodeJawaban();
    });
</script>
@endsection