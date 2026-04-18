<!-- Search & Filter Component -->
<div class="card mb-3">
    <div class="card-body">
        <form method="GET" action="{{ route('alumni.index') }}" id="searchFilterForm">
            <div class="row g-3">

                {{-- Search Bar --}}
                <div class="col-md-4">
                    <label for="search" class="form-label">
                        <i class="fas fa-search"></i> Pencarian
                    </label>
                    <input type="text" class="form-control" id="search" name="search"
                        placeholder="Cari NIM, Nama, atau Instansi..." value="{{ request('search') }}">
                </div>

                {{-- Filter Prodi --}}
                <div class="col-md-2">
                    <label for="filter_prodi" class="form-label">Program Studi</label>
                    <select class="form-select" id="filter_prodi" name="filter_prodi">
                        <option value="">Semua Prodi</option>
                        @foreach ($prodis as $prodi)
                            <option value="{{ $prodi->id_prodi }}"
                                {{ request('filter_prodi') == $prodi->id_prodi ? 'selected' : '' }}>
                                {{ $prodi->nama_prodi }}
                            </option>
                        @endforeach
                    </select>
                </div>

                {{-- Filter Jurusan --}}
                <div class="col-md-2">
                    <label for="filter_jurusan" class="form-label">Jurusan</label>
                    <select class="form-select" id="filter_jurusan" name="filter_jurusan">
                        <option value="">Semua Jurusan</option>
                        @foreach ($jurusans as $jurusan)
                            <option value="{{ $jurusan }}"
                                {{ request('filter_jurusan') == $jurusan ? 'selected' : '' }}>
                                {{ $jurusan }}
                            </option>
                        @endforeach
                    </select>
                </div>

                {{-- Filter Tahun Masuk --}}
                <div class="col-md-2">
                    <label for="filter_tahun_masuk" class="form-label">Tahun Masuk</label>
                    <select class="form-select" id="filter_tahun_masuk" name="filter_tahun_masuk">
                        <option value="">Semua Tahun</option>
                        @foreach ($tahunMasuks as $tahun)
                            <option value="{{ $tahun }}"
                                {{ request('filter_tahun_masuk') == $tahun ? 'selected' : '' }}>
                                {{ $tahun }}
                            </option>
                        @endforeach
                    </select>
                </div>

                {{-- Filter Profesi --}}
                <div class="col-md-2">
                    <label for="filter_profesi" class="form-label">Profesi</label>
                    <select class="form-select" id="filter_profesi" name="filter_profesi">
                        <option value="">Semua Profesi</option>
                        @foreach ($profesis as $profesi)
                            <option value="{{ $profesi->id_profesi }}"
                                {{ request('filter_profesi') == $profesi->id_profesi ? 'selected' : '' }}>
                                {{ $profesi->nama_profesi }}
                            </option>
                        @endforeach
                    </select>
                </div>

            </div>

            <div class="row g-3 mt-2">

                {{-- Filter Status Pengisian --}}
                <div class="col-md-2">
                    <label for="filter_status" class="form-label">Status Pengisian</label>
                    <select class="form-select" id="filter_status" name="filter_status">
                        <option value="">Semua Status</option>
                        <option value="sudah" {{ request('filter_status') == 'sudah' ? 'selected' : '' }}>
                            <i class="fas fa-check-circle"></i> Sudah Lengkap (100%)
                        </option>
                        <option value="belum" {{ request('filter_status') == 'belum' ? 'selected' : '' }}>
                            <i class="fas fa-exclamation-triangle"></i> Belum Lengkap (<100%) </option>
                    </select>
                </div>

                {{-- Filter Tahun Lulus --}}
                <div class="col-md-2">
                    <label for="filter_tahun_lulus" class="form-label">Tahun Lulus</label>
                    <select class="form-select" id="filter_tahun_lulus" name="filter_tahun_lulus">
                        <option value="">Semua Tahun</option>
                        @foreach ($tahunLulus as $tahun)
                            <option value="{{ $tahun }}"
                                {{ request('filter_tahun_lulus') == $tahun ? 'selected' : '' }}>
                                {{ $tahun }}
                            </option>
                        @endforeach
                    </select>
                </div>

                {{-- Action Buttons --}}
                <div class="col-md-8 d-flex align-items-end gap-2">
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-search"></i> Cari & Filter
                    </button>
                    <a href="{{ route('alumni.index') }}" class="btn btn-secondary">
                        <i class="fas fa-undo"></i> Reset
                    </a>
                    <button type="button" class="btn btn-info" id="toggleAdvanced">
                        <i class="fas fa-filter"></i> Filter Lanjutan
                    </button>
                </div>

            </div>

            {{-- Advanced Filter (Hidden by default) --}}
            <div id="advancedFilter" class="row g-3 mt-3" style="display: none;">
                <div class="col-12">
                    <h6 class="text-muted">Filter Lanjutan</h6>
                    <hr>
                </div>

                <div class="col-md-3">
                    <label for="filter_masa_tunggu_min" class="form-label">Masa Tunggu Min (bulan)</label>
                    <input type="number" class="form-control" id="filter_masa_tunggu_min" name="filter_masa_tunggu_min"
                        value="{{ request('filter_masa_tunggu_min') }}" placeholder="0">
                </div>

                <div class="col-md-3">
                    <label for="filter_masa_tunggu_max" class="form-label">Masa Tunggu Max (bulan)</label>
                    <input type="number" class="form-control" id="filter_masa_tunggu_max" name="filter_masa_tunggu_max"
                        value="{{ request('filter_masa_tunggu_max') }}" placeholder="12">
                </div>

                <div class="col-md-3">
                    <label for="filter_jenis_instansi" class="form-label">Jenis Instansi</label>
                    <select class="form-select" id="filter_jenis_instansi" name="filter_jenis_instansi">
                        <option value="">Semua Jenis</option>
                        <option value="Pendidikan Tinggi"
                            {{ request('filter_jenis_instansi') == 'Pendidikan Tinggi' ? 'selected' : '' }}>
                            Pendidikan Tinggi
                        </option>
                        <option value="Instansi Pemerintah"
                            {{ request('filter_jenis_instansi') == 'Instansi Pemerintah' ? 'selected' : '' }}>
                            Instansi Pemerintah
                        </option>
                        <option value="BUMN" {{ request('filter_jenis_instansi') == 'BUMN' ? 'selected' : '' }}>
                            BUMN
                        </option>
                        <option value="Perusahaan Swasta"
                            {{ request('filter_jenis_instansi') == 'Perusahaan Swasta' ? 'selected' : '' }}>
                            Perusahaan Swasta
                        </option>
                    </select>
                </div>

                <div class="col-md-3">
                    <label for="filter_skala_instansi" class="form-label">Skala Instansi</label>
                    <select class="form-select" id="filter_skala_instansi" name="filter_skala_instansi">
                        <option value="">Semua Skala</option>
                        <option value="Wirausaha"
                            {{ request('filter_skala_instansi') == 'Wirausaha' ? 'selected' : '' }}>
                            Wirausaha
                        </option>
                        <option value="Nasional"
                            {{ request('filter_skala_instansi') == 'Nasional' ? 'selected' : '' }}>
                            Nasional
                        </option>
                        <option value="Multinasional"
                            {{ request('filter_skala_instansi') == 'Multinasional' ? 'selected' : '' }}>
                            Multinasional
                        </option>
                    </select>
                </div>
            </div>

        </form>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Toggle advanced filter
        document.getElementById('toggleAdvanced').addEventListener('click', function() {
            const advancedFilter = document.getElementById('advancedFilter');
            const button = this;

            if (advancedFilter.style.display === 'none') {
                advancedFilter.style.display = 'flex';
                button.innerHTML = '<i class="fas fa-filter"></i> Sembunyikan Filter';
                button.classList.remove('btn-info');
                button.classList.add('btn-warning');
            } else {
                advancedFilter.style.display = 'none';
                button.innerHTML = '<i class="fas fa-filter"></i> Filter Lanjutan';
                button.classList.remove('btn-warning');
                button.classList.add('btn-info');
            }
        });

        // Auto-update prodi when jurusan changes
        document.getElementById('filter_jurusan').addEventListener('change', function() {
            const jurusan = this.value;
            const prodiSelect = document.getElementById('filter_prodi');

            if (jurusan) {
                fetch(`{{ route('alumni.prodi-by-jurusan') }}?jurusan=${jurusan}`)
                    .then(response => response.json())
                    .then(data => {
                        prodiSelect.innerHTML = '<option value="">Semua Prodi</option>';
                        data.forEach(prodi => {
                            prodiSelect.innerHTML +=
                                `<option value="${prodi.id_prodi}">${prodi.nama_prodi}</option>`;
                        });
                    });
            } else {
                // Reset to show all prodi
                location.reload();
            }
        });

        // Auto submit on filter change (optional)
        const filterElements = document.querySelectorAll(
            '#searchFilterForm select, #searchFilterForm input[type="number"]');
        filterElements.forEach(element => {
            element.addEventListener('change', function() {
                // Optional: Auto-submit form when filter changes
                // document.getElementById('searchFilterForm').submit();
            });
        });

        // Search on Enter key
        document.getElementById('search').addEventListener('keypress', function(e) {
            if (e.key === 'Enter') {
                e.preventDefault();
                document.getElementById('searchFilterForm').submit();
            }
        });
    });
</script>
