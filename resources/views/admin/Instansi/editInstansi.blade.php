@extends('layouts.template')

@section('title', 'Edit Instansi')

@section('content')
    <div class="card">
        <div class="card-header">
            <h4 class="card-title">Edit Instansi</h4>
        </div>
        <div class="card-body">
            @if($errors->any())
                <div class="alert alert-danger">
                    <ul>
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif
            
            <form action="{{ route('instansi.update', $instansi->id_instansi) }}" method="POST">
                @csrf
                @method('PUT')
                <div class="form-group">
                    <label for="nama_instansi">Nama Instansi</label>
                    <input type="text" name="nama_instansi" id="nama_instansi"
                        class="form-control @error('nama_instansi') is-invalid @enderror"
                        value="{{ old('nama_instansi', $instansi->nama_instansi) }}" required>
                    @error('nama_instansi')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="jenis_instansi">Jenis Instansi</label>
                    <select name="jenis_instansi" id="jenis_instansi"
                        class="form-control @error('jenis_instansi') is-invalid @enderror" required>
                        <option value="" disabled>Pilih Jenis Instansi</option>
                        <option value="Pendidikan Tinggi"
                            {{ old('jenis_instansi', $instansi->jenis_instansi) == 'Pendidikan Tinggi' ? 'selected' : '' }}>
                            Pendidikan Tinggi</option>
                        <option value="Instansi Pemerintah"
                            {{ old('jenis_instansi', $instansi->jenis_instansi) == 'Instansi Pemerintah' ? 'selected' : '' }}>
                            Instansi Pemerintah</option>
                        <option value="BUMN"
                            {{ old('jenis_instansi', $instansi->jenis_instansi) == 'BUMN' ? 'selected' : '' }}>BUMN
                        </option>
                        <option value="Perusahaan Swasta"
                            {{ old('jenis_instansi', $instansi->jenis_instansi) == 'Perusahaan Swasta' ? 'selected' : '' }}>Perusahaan Swasta</option>
                    </select>
                    @error('jenis_instansi')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="skala_instansi">Skala Instansi</label>
                    <select name="skala_instansi" id="skala_instansi"
                        class="form-control @error('skala_instansi') is-invalid @enderror" required>
                        <option value="" disabled>Pilih Skala Instansi</option>
                        <option value="Wirausaha"
                            {{ old('skala_instansi', $instansi->skala_instansi) == 'Wirausaha' ? 'selected' : '' }}>
                            Wirausaha</option>
                        <option value="Nasional"
                            {{ old('skala_instansi', $instansi->skala_instansi) == 'Nasional' ? 'selected' : '' }}>Nasional
                        </option>
                        <option value="Multinasional"
                            {{ old('skala_instansi', $instansi->skala_instansi) == 'Multinasional' ? 'selected' : '' }}>
                            Multinasional</option>
                    </select>
                    @error('skala_instansi')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="lokasi_instansi">Lokasi Instansi</label>
                    <input type="text" name="lokasi_instansi" id="lokasi_instansi"
                        class="form-control @error('lokasi_instansi') is-invalid @enderror"
                        value="{{ old('lokasi_instansi', $instansi->lokasi_instansi) }}" required>
                    @error('lokasi_instansi')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="no_hp_instansi">No HP Instansi</label>
                    <input type="text" name="no_hp_instansi" id="no_hp_instansi"
                        class="form-control @error('no_hp_instansi') is-invalid @enderror"
                        value="{{ old('no_hp_instansi', $instansi->no_hp_instansi) }}" required pattern="[0-9]+" title="Hanya boleh memasukkan angka"
                        oninput="this.value = this.value.replace(/[^0-9]/g, '')">
                    @error('no_hp_instansi')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <button type="submit" class="btn btn-success">Update</button>
                <a href="{{ route('instansi.index') }}" class="btn btn-secondary">Batal</a>
            </form>
        </div>
    </div>
@endsection