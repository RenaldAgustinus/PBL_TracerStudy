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
                    <select name="kategori" id="kategori" class="form-control" required onchange="toggleMetodeJawaban()">
                        <option value="tracer" {{ $pertanyaan->kategori == 'tracer' ? 'selected' : '' }}>Tracer</option>
                        <option value="pengguna_lulusan"
                            {{ $pertanyaan->kategori == 'pengguna_lulusan' ? 'selected' : '' }}>Pengguna Lulusan</option>
                        <option value="umum" {{ $pertanyaan->kategori == 'umum' ? 'selected' : '' }}>Umum</option>
                    </select>
                </div>
                <div class="form-group" id="metodejawabanGroup">
                    <label>Pertanyaan Penilaian ?</label>
                    <div class="form-check">
                        <input class="form-check-input" type="radio" name="metodejawaban" id="metodejawaban1"
                            value="1" {{ $pertanyaan->metodejawaban == '1' ? 'checked' : '' }}>
                        <label class="form-check-label" for="metodejawaban1">Ya</label>
                    </div>
                    <div class="form-check">
                        <input class="form-check-input" type="radio" name="metodejawaban" id="metodejawaban2"
                            value="2" {{ $pertanyaan->metodejawaban == '2' ? 'checked' : '' }}>
                        <label class="form-check-label" for="metodejawaban2">Tidak</label>
                    </div>
                </div>
                <button type="submit" class="btn btn-success">Update</button>
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
            // Set default value for non-pengguna_lulusan to 2 (Tidak)
            document.getElementById('metodejawaban2').checked = true;
        }
    }

    // Run on page load
    document.addEventListener('DOMContentLoaded', function() {
        toggleMetodeJawaban();
    });
</script>
@endsection