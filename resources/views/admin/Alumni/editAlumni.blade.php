{{-- filepath: c:\laragon\www\PBL_TracerStudy\PBL_TracerStudy\resources\views\admin\Alumni\editAlumni.blade.php --}}
@extends('layouts.template')

@section('title', 'Edit Alumni')

@section('content')
    <div class="card">
        <div class="card-header">
            <h4 class="card-title">Edit Alumni</h4>
        </div>
        <div class="card-body">
            <form action="{{ route('alumni.update', $alumni->nim) }}" method="POST">
                @csrf
                @method('PUT')
                {{-- Data Alumni --}}
                <h5 class="mb-3">Detail Pribadi</h5>
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="nim">NIM</label>
                            <input type="text" name="nim" id="nim"
                                class="form-control @error('nim') is-invalid @enderror" value="{{ $alumni->nim }}"
                                pattern="[0-9]+" title="Hanya boleh memasukkan angka" placeholder="contoh: 2141720001"
                                oninput="this.value = this.value.replace(/[^0-9]/g, '')" readonly>
                            @error('nim')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="nama_alumni">Nama</label>
                            <input type="text" name="nama_alumni" id="nama_alumni" class="form-control"
                                value="{{ $alumni->nama_alumni }}" placeholder="Masukkan nama lengkap alumni" required>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="id_prodi">Program Studi</label>
                            <select name="id_prodi" id="id_prodi"
                                class="form-control @error('id_prodi') is-invalid @enderror" required>
                                <option value="">Pilih Program Studi</option>
                                @foreach ($prodis as $prodi)
                                    <option value="{{ $prodi->id_prodi }}"
                                        {{ $alumni->id_prodi == $prodi->id_prodi ? 'selected' : '' }}>
                                        {{ $prodi->nama_prodi }} ({{ $prodi->jurusan }})
                                    </option>
                                @endforeach
                            </select>
                            @error('id_prodi')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="tgl_lulus">Tanggal Lulus</label>
                            <input type="date" name="tgl_lulus" id="tgl_lulus" class="form-control"
                                value="{{ $alumni->tgl_lulus }}" placeholder="Pilih tanggal lulus">
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="tahun_masuk">Tahun Angkatan</label>
                            <input type="text" name="tahun_masuk" id="tahun_masuk"
                                class="form-control @error('tahun_masuk') is-invalid @enderror"
                                value="{{ $alumni->tahun_masuk }}" pattern="[0-9]{4}"
                                title="Hanya boleh memasukkan 4 digit angka tahun (contoh: 2020)" maxlength="4"
                                minlength="4" placeholder="Masukkan tahun angkatan, contoh: 2020"
                                oninput="this.value = this.value.replace(/[^0-9]/g, '').slice(0, 4)">
                            @error('tahun_masuk')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="email">Email</label>
                            <input type="email" name="email" id="email" class="form-control"
                                value="{{ $alumni->email }}" placeholder="Masukkan email, contoh: alumni@email.com">
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="no_hp">No. HP</label>
                            <input type="text" name="no_hp" id="no_hp"
                                class="form-control @error('no_hp') is-invalid @enderror" value="{{ $alumni->no_hp }}"
                                pattern="[0-9]+" title="Hanya boleh memasukkan angka"
                                placeholder="Masukkan nomor HP, contoh: 081234567890"
                                oninput="this.value = this.value.replace(/[^0-9]/g, '')">
                            @error('no_hp')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>

                <h5 class="mt-4 mb-3">Detail Pekerjaan</h5>
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="tanggal_kerja_pertama">Tanggal Kerja Pertama</label>
                            <input type="date" name="tanggal_kerja_pertama" id="tanggal_kerja_pertama"
                                class="form-control" value="{{ $alumni->tanggal_kerja_pertama }}"
                                placeholder="Pilih tanggal kerja pertama">
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="tanggal_mulai_instansi">Tanggal Mulai Instansi</label>
                            <input type="date" name="tanggal_mulai_instansi" id="tanggal_mulai_instansi"
                                class="form-control" value="{{ $alumni->tanggal_mulai_instansi }}"
                                placeholder="Pilih tanggal mulai di instansi">
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="id_profesi">Profesi</label>
                            <select name="id_profesi" id="id_profesi" class="form-control">
                                <option value="">Pilih Profesi</option>
                                @foreach ($profesis->groupBy('kategori_profesi') as $kategori => $profesiGroup)
                                    <optgroup label="{{ $kategori }}">
                                        @foreach ($profesiGroup as $profesi)
                                            <option value="{{ $profesi->id_profesi }}"
                                                {{ $alumni->id_profesi == $profesi->id_profesi ? 'selected' : '' }}>
                                                {{ $profesi->nama_profesi }}
                                            </option>
                                        @endforeach
                                    </optgroup>
                                @endforeach
                            </select>
                        </div>
                    </div>
                </div>

                <h5 class="mt-4 mb-3">Detail Pengguna Lulusan <span class="text-muted">(Opsional - Jika diisi salah satu,
                        harus diisi semua)</span></h5>
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="nama_atasan">Nama Atasan <span class="conditional-required"
                                    style="display: none; color: red;">*</span></label>
                            <input type="text" name="nama_atasan" id="nama_atasan"
                                class="form-control atasan-field @error('nama_atasan') is-invalid @enderror"
                                value="{{ $alumni->penggunaLulusan->nama_atasan ?? '' }}"
                                placeholder="Masukkan nama atasan langsung">
                            @error('nama_atasan')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="jabatan_atasan">Jabatan Atasan <span class="conditional-required"
                                    style="display: none; color: red;">*</span></label>
                            <input type="text" name="jabatan_atasan" id="jabatan_atasan"
                                class="form-control atasan-field @error('jabatan_atasan') is-invalid @enderror"
                                value="{{ $alumni->penggunaLulusan->jabatan_atasan ?? '' }}"
                                placeholder="Masukkan jabatan atasan, contoh: Manager IT">
                            @error('jabatan_atasan')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="email_atasan">Email Atasan <span class="conditional-required"
                                    style="display: none; color: red;">*</span></label>
                            <input type="email" name="email_atasan" id="email_atasan"
                                class="form-control atasan-field @error('email_atasan') is-invalid @enderror"
                                value="{{ $alumni->penggunaLulusan->email_atasan ?? '' }}"
                                placeholder="Masukkan email atasan, contoh: manager@perusahaan.com">
                            @error('email_atasan')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>

                {{-- Alert untuk informasi validasi --}}
                <div class="alert alert-warning atasan-warning" style="display: none;">
                    <i class="fas fa-exclamation-triangle"></i>
                    <strong>Perhatian:</strong> Jika Anda mengisi salah satu field atasan, maka semua field atasan (Nama,
                    Jabatan, dan Email) harus diisi lengkap.
                </div>

                <h5 class="mt-4 mb-3">Detail Instansi</h5>
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="nama_instansi">Nama Instansi</label>
                            <input type="text" name="nama_instansi" id="nama_instansi" class="form-control"
                                value="{{ $alumni->instansi->nama_instansi ?? '' }}"
                                placeholder="Masukkan nama perusahaan/instansi">
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="jenis_instansi">Jenis Instansi</label>
                            <select name="jenis_instansi" id="jenis_instansi" class="form-control">
                                <option value="">Pilih jenis instansi</option>
                                <option value="Pendidikan Tinggi"
                                    {{ $alumni?->instansi?->jenis_instansi == 'Pendidikan Tinggi' ? 'selected' : '' }}>
                                    Pendidikan Tinggi</option>
                                <option value="Instansi Pemerintah"
                                    {{ $alumni?->instansi?->jenis_instansi == 'Instansi Pemerintah' ? 'selected' : '' }}>
                                    Instansi Pemerintah</option>
                                <option value="BUMN"
                                    {{ $alumni?->instansi?->jenis_instansi == 'BUMN' ? 'selected' : '' }}>BUMN</option>
                                <option value="Perusahaan Swasta"
                                    {{ $alumni?->instansi?->jenis_instansi == 'Perusahaan Swasta' ? 'selected' : '' }}>
                                    Perusahaan Swasta</option>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="skala_instansi">Skala Instansi</label>
                            <select name="skala_instansi" id="skala_instansi" class="form-control">
                                <option value="">Pilih skala instansi</option>
                                <option value="Wirausaha"
                                    {{ $alumni?->instansi?->skala_instansi == 'Wirausaha' ? 'selected' : '' }}>Wirausaha
                                </option>
                                <option value="Nasional"
                                    {{ $alumni?->instansi?->skala_instansi == 'Nasional' ? 'selected' : '' }}>Nasional
                                </option>
                                <option value="Multinasional"
                                    {{ $alumni?->instansi?->skala_instansi == 'Multinasional' ? 'selected' : '' }}>
                                    Multinasional</option>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="lokasi_instansi">Lokasi Instansi</label>
                            <input type="text" name="lokasi_instansi" id="lokasi_instansi" class="form-control"
                                value="{{ $alumni?->instansi?->lokasi_instansi ?? '' }}"
                                placeholder="Masukkan lokasi instansi, contoh: Jakarta Pusat">
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="no_hp_instansi">No HP Instansi</label>
                            <input type="text" name="no_hp_instansi" id="no_hp_instansi"
                                class="form-control @error('no_hp_instansi') is-invalid @enderror"
                                value="{{ $alumni?->instansi?->no_hp_instansi ?? '' }}" pattern="[0-9]+"
                                title="Hanya boleh memasukkan angka" placeholder="Masukkan nomor telepon instansi"
                                oninput="this.value = this.value.replace(/[^0-9]/g, '')">
                            @error('no_hp_instansi')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>

                <button type="submit" class="btn btn-success mt-3">Update</button>
                <a href="{{ route('alumni.index') }}" class="btn btn-secondary mt-3">Batal</a>
            </form>
        </div>
    </div>

    {{-- JavaScript untuk validasi conditional --}}
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const atasanFields = document.querySelectorAll('.atasan-field');
            const conditionalRequired = document.querySelectorAll('.conditional-required');
            const atasanWarning = document.querySelector('.atasan-warning');

            function checkAtasanFields() {
                let hasValue = false;

                // Cek apakah ada field yang terisi
                atasanFields.forEach(field => {
                    if (field.value.trim() !== '') {
                        hasValue = true;
                    }
                });

                // Show/hide required indicator dan warning
                conditionalRequired.forEach(indicator => {
                    indicator.style.display = hasValue ? 'inline' : 'none';
                });

                if (atasanWarning) {
                    atasanWarning.style.display = hasValue ? 'block' : 'none';
                }

                // Add/remove required attribute
                atasanFields.forEach(field => {
                    if (hasValue) {
                        field.setAttribute('required', 'required');
                        field.classList.add('required-conditional');
                    } else {
                        field.removeAttribute('required');
                        field.classList.remove('required-conditional');
                    }
                });
            }

            // Event listeners untuk semua field atasan
            atasanFields.forEach(field => {
                field.addEventListener('input', checkAtasanFields);
                field.addEventListener('blur', checkAtasanFields);
            });

            // Initial check saat halaman load
            checkAtasanFields();

            // Form validation sebelum submit
            document.querySelector('form').addEventListener('submit', function(e) {
                // Check if any atasan field is filled but not all
                const filledFields = Array.from(atasanFields).filter(field => field.value.trim() !== '');
                if (filledFields.length > 0 && filledFields.length < atasanFields.length) {
                    e.preventDefault();
                    alert(
                        'Jika mengisi data atasan, semua field atasan (Nama, Jabatan, dan Email) harus diisi lengkap.');
                    return false;
                }
            });
        });
    </script>

    {{-- CSS untuk styling conditional required --}}
    <style>
        .required-conditional {
            border-left: 3px solid #ffc107 !important;
        }

        .atasan-warning {
            border-left: 4px solid #ffc107;
        }
    </style>
@endsection
