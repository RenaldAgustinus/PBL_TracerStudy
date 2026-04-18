@extends('layouts.template')

@section('title', 'Dashboard')

@section('content')
    <div class="row">
        <!-- PROFESI -->
        <div class="col-lg-6 col-md-12 mt-4 mb-4">
            <div class="card">
                <div class="card-body">
                    <h6 class="mb-0">Grafik Sebaran Profesi Lulusan</h6>
                    <p class="text-sm">10 profesi tertinggi + kategori Lainnya</p>
                    <div class="pe-2">
                        <canvas id="chart-profesi" class="chart-canvas" height="200"></canvas>
                    </div>
                </div>
            </div>
        </div>

        <!-- INSTANSI -->
        <div class="col-lg-6 col-md-12 mt-4 mb-4">
            <div class="card">
                <div class="card-body">
                    <h6 class="mb-0">Grafik Sebaran Jenis Instansi</h6>
                    <p class="text-sm">Kategori: Pendidikan Tinggi, Instansi Pemerintah, Swasta, BUMN</p>
                    <div class="pe-2">
                        <canvas id="chart-instansi" class="chart-canvas" height="200"></canvas>
                    </div>
                </div>
            </div>
        </div>

        
    <div class="container-fluid py-4">
    <div class="card">
        <div class="card-body">
            <div class="d-flex justify-content-between align-items-start mb-3">
                <div>
                    <h5 class="mb-1">Tabel Sebaran Lingkup Tempat Kerja dan Kesesuaian Profesi</h5>
                    <p class="text-sm">Persentase hasil pelacakan alumni</p>
                    <a href="{{ url('/dashboard-lingkup_kerja') }}" class="btn btn-primary"><i class="fa fa-file-excel"></i>
                        Export
                        Excel</a>
                </div>
            </div>

            <div class="table-responsive">
                <table class="table table-bordered table-striped text-center align-middle">
                    <thead class="table-light">
                        <tr>
                            <th rowspan="2">Tahun Lulus</th>
                            <th rowspan="2">Jumlah Lulusan</th>
                            <th rowspan="2">Jumlah Terlacak</th>
                            <th colspan="2">Kesesuaian Profesi</th>
                            <th colspan="3">Lingkup Tempat Kerja</th>
                        </tr>
                        <tr>
                            <th>Infokom</th>
                            <th>Non Infokom</th>
                            <th>Internasional</th>
                            <th>Nasional</th>
                            <th>Wirausaha</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($sebaranLingkup as $row)
                        <tr>
                            <td>{{ $row['tahun'] }}</td>
                            <td>{{ $row['jumlah_lulusan'] }}</td>
                            <td>{{ $row['terlacak'] }}</td>
                            <td>{{ $row['infokom'] }}</td>
                            <td>{{ $row['non_infokom'] }}</td>
                            <td>{{ $row['internasional'] }}</td>
                            <td>{{ $row['nasional'] }}</td>
                            <td>{{ $row['wirausaha'] }}</td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="8">Data belum tersedia</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

     <div class="container-fluid py-4">
    <div class="card">
        <div class="card-body">
            <h5 class="mb-1">Tabel Rata-Rata Masa Tunggu</h5>
            <p class="text-sm">Berdasarkan hasil pelacakan alumni</p>
            <a href="{{ url('/dashboard-masa_tunggu') }}" class="btn btn-primary"><i class="fa fa-file-excel"></i>
                Export
                Excel</a>
            <div class="table-responsive mt-3">
                <table class="table table-bordered table-striped text-center align-middle">
                    <thead class="table-light">
                        <tr>
                            <th>Tahun Lulus</th>
                            <th>Jumlah Lulusan</th>
                            <th>Jumlah Terlacak</th>
                            <th>Rata-rata Masa Tunggu (Bulan)</th>
                        </tr>
                    </thead>
                    <tbody>
                        @php
                            $totalLulusan = 0;
                            $totalTerlacak = 0;
                            $totalMasaTunggu = 0;
                            $totalPengisiMasaTunggu = 0;
                        @endphp

                        @if(isset($masaTunggu))
                            @foreach($masaTunggu as $row)
                                <tr>
                                    <td>{{ $row['tahun_lulus'] }}</td>
                                    <td>{{ $row['jumlah_lulusan'] }}</td>
                                    <td>{{ $row['jumlah_terlacak'] }}</td>
                                    <td>{{ number_format($row['rata_rata_masa_tunggu'], 2) }}</td>

                                    @php
                                        $totalLulusan += $row['jumlah_lulusan'];
                                        $totalTerlacak += $row['jumlah_terlacak'];
                                        $totalMasaTunggu += $row['total_masa_tunggu'];
                                        $totalPengisiMasaTunggu += $row['pengisi_masa_tunggu'];
                                    @endphp
                                </tr>
                            @endforeach
                        @endif
                    </tbody>
                    <tfoot class="table-secondary fw-semibold">
                        <tr>
                            <td>Jumlah</td>
                            <td>{{ $totalLulusan }}</td>
                            <td>{{ $totalTerlacak }}</td>
                            <td>
                                {{ $totalPengisiMasaTunggu > 0 ? number_format($totalMasaTunggu / $totalPengisiMasaTunggu, 2) : 0 }}
                            </td>
                        </tr>
                    </tfoot>
                </table>
            </div>
        </div>
    </div>
</div>


                <!-- TABEL KEPUASAN -->
        <div class="col-12 mt-4 mb-4">
            <div class="card">
                <div class="card-body">
                    <h6 class="mb-0">Tabel Penilaian Kepuasan Pengguna Lulusan</h6>
                    <p class="text-sm">Persentase hasil input pengguna lulusan</p>
                    <a href="{{ url('/dashboard-export_excel') }}" class="btn btn-primary"><i class="fa fa-file-excel"></i>
                        Export
                        Excel</a>

                    <div class="table-responsive">
                        <table class="table table-bordered table-striped">
                            <thead class="thead-dark">
                                <tr class="text-center">
                                    <th>No</th>
                                    <th>Jenis Kemampuan</th>
                                    <th>Sangat Kurang (%)</th>
                                    <th>Kurang (%)</th>
                                    <th>Cukup (%)</th>
                                    <th>Baik (%)</th>
                                    <th>Sangat Baik (%)</th>
                                </tr>
                            </thead>
                            <tbody>
                                @php
                                    $totalSK = $totalK = $totalC = $totalB = $totalSB = 0;
                                @endphp
                                @foreach ($kriteriaChartData as $index => $item)
                                    @php
                                        $data = $item['data'];
                                        $total = array_sum($data);

                                        $sk = $total ? ($data['Sangat Kurang'] / $total) * 100 : 0;
                                        $k = $total ? ($data['Kurang'] / $total) * 100 : 0;
                                        $c = $total ? ($data['Cukup'] / $total) * 100 : 0;
                                        $b = $total ? ($data['Baik'] / $total) * 100 : 0;
                                        $sb = $total ? ($data['Sangat Baik'] / $total) * 100 : 0;

                                        $totalSK += $sk;
                                        $totalK += $k;
                                        $totalC += $c;
                                        $totalB += $b;
                                        $totalSB += $sb;
                                    @endphp
                                    <tr>
                                        <td class="text-center">{{ $loop->iteration }}</td>
                                        <td>{{ $item['label'] }}</td>
                                        <td class="text-center">{{ number_format($sk, 2) }}</td>
                                        <td class="text-center">{{ number_format($k, 2) }}</td>
                                        <td class="text-center">{{ number_format($c, 2) }}</td>
                                        <td class="text-center">{{ number_format($b, 2) }}</td>
                                        <td class="text-center">{{ number_format($sb, 2) }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                            <tfoot>
                                <tr class="text-center fw-bold">
                                    <td colspan="2">Jumlah Rata-Rata</td>
                                    <td>{{ number_format($totalSK / count($kriteriaChartData), 2) }}</td>
                                    <td>{{ number_format($totalK / count($kriteriaChartData), 2) }}</td>
                                    <td>{{ number_format($totalC / count($kriteriaChartData), 2) }}</td>
                                    <td>{{ number_format($totalB / count($kriteriaChartData), 2) }}</td>
                                    <td>{{ number_format($totalSB / count($kriteriaChartData), 2) }}</td>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        <!-- Grafik Kepuasan Pengguna Lulusan -->
        <div class="col-lg-4 col-md-12 mt-4 mb-4">
            <div class="card">
                <div class="card-body">
                    <h6 class="mb-0">Grafik Kepuasan Pengguna Lulusan</h6>
                    <select id="kriteriaSelect" class="form-select mb-3">
                        @foreach (array_keys($kriteriaChartData) as $key)
                            <option value="{{ $key }}">{{ $kriteriaChartData[$key]['label'] }}</option>
                        @endforeach
                    </select>
                    <canvas id="kriteriaChart" height="200"></canvas>
                </div>
            </div>
        </div>
    </div>

@endsection

@section('scripts')
    <script>
        // PROFESI
        var ctxProfesi = document.getElementById("chart-profesi").getContext("2d");
        new Chart(ctxProfesi, {
            type: "pie",
            data: {
                labels: {!! json_encode($profesiLabels) !!},
                datasets: [{
                    label: "Jumlah",
                    backgroundColor: [
                        "#43A047", "#FF9800", "#E91E63", "#3F51B5", "#009688",
                        "#9C27B0", "#00BCD4", "#8BC34A", "#FFC107", "#795548", "#607D8B"
                    ],
                    data: {!! json_encode($profesiData) !!}
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        position: "top"
                    }
                }
            }
        });

        // INSTANSI
        var ctxInstansi = document.getElementById("chart-instansi").getContext("2d");
        new Chart(ctxInstansi, {
            type: "pie",
            data: {
                labels: {!! json_encode($instansiLabels) !!},
                datasets: [{
                    label: "Jumlah",
                    backgroundColor: ["#2196F3", "#FF5722", "#9C27B0", "#4CAF50", "#FFEB3B"],
                    data: {!! json_encode($instansiData) !!}
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        position: "top"
                    }
                }
            }
        });
        // Kepuasan per kriteria
        const kriteriaChartData = {!! json_encode($kriteriaChartData) !!};

        const ctxKriteria = document.getElementById('kriteriaChart').getContext('2d');
        let chartInstance;

        function renderChart(id) {
            const data = kriteriaChartData[id].data;
            const labels = Object.keys(data);
            const values = Object.values(data);

            if (chartInstance) chartInstance.destroy();

            chartInstance = new Chart(ctxKriteria, {
                type: 'pie',
                data: {
                    labels: labels,
                    datasets: [{
                        label: kriteriaChartData[id].label,
                        backgroundColor: ["#9E9E9E", "#F44336", "#FF9800", "#FFC107","#4CAF50"],
                        data: values
                    }]
                },
                options: {
                    responsive: true,
                    plugins: {
                        legend: {
                            position: "top"
                        }
                    }
                }
            });
        }

        // Initial chart
        const defaultId = Object.keys(kriteriaChartData)[0];
        renderChart(defaultId);

        // Dropdown listener
        document.getElementById('kriteriaSelect').addEventListener('change', function() {
            renderChart(this.value);
        });
    </script>
@endsection
