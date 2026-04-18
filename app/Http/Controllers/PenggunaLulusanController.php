<?php

namespace App\Http\Controllers;

use App\Models\PenggunaLulusan;
use App\Models\Alumni;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Style\Border;

class PenggunaLulusanController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = PenggunaLulusan::query();

        // Search functionality
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('nama_atasan', 'LIKE', "%{$search}%")
                    ->orWhere('jabatan_atasan', 'LIKE', "%{$search}%")
                    ->orWhere('email_atasan', 'LIKE', "%{$search}%");
            });
        }

        $penggunaLulusan = $query->orderBy('nama_atasan', 'asc')->get();

        return view('admin.PenggunaLulusan.indexPenggunaLulusan', compact('penggunaLulusan'));
    }

    // Menampilkan detail alumni dari pengguna lulusan tertentu
    public function showAlumni($id)
    {
        $penggunaLulusan = PenggunaLulusan::findOrFail($id);

        // Get alumni yang terkait dengan pengguna lulusan ini
        $alumni = Alumni::with(['prodi', 'profesi', 'instansi'])
            ->where('id_pengguna_lulusan', $id)
            ->orderBy('nama_alumni', 'asc')
            ->get();

        return view('admin.PenggunaLulusan.detailAlumni', compact('penggunaLulusan', 'alumni'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.PenggunaLulusan.createPenggunaLulusan');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'nama_atasan' => 'required|max:100',
            'jabatan_atasan' => 'required|max:100',
            'email_atasan' => 'nullable|email|max:100',
        ]);

        PenggunaLulusan::create($request->all());

        return redirect()->route('penggunaLulusan.index')
            ->with('success', 'Pengguna Lulusan berhasil ditambahkan.');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $penggunaLulusan = PenggunaLulusan::findOrFail($id);
        return view('admin.PenggunaLulusan.editPenggunaLulusan', compact('penggunaLulusan'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'nama_atasan' => 'required|max:100',
            'jabatan_atasan' => 'required|max:100',
            'email_atasan' => 'nullable|email|max:100',
        ]);

        $penggunaLulusan = PenggunaLulusan::findOrFail($id);
        $penggunaLulusan->update($request->all());

        return redirect()->route('penggunaLulusan.index')
            ->with('success', 'Pengguna Lulusan berhasil diperbarui.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $penggunaLulusan = PenggunaLulusan::findOrFail($id);
        $penggunaLulusan->delete();

        return redirect()->route('penggunaLulusan.index')
            ->with('success', 'Pengguna Lulusan berhasil dihapus.');
    }

    /**
     * Export pengguna lulusan yang belum mengisi survey
     */
    public function export()
    {
        // Ambil pengguna lulusan yang belum ada di tabel jawaban
        $penggunaLulusan = PenggunaLulusan::with(['alumni' => function ($query) {
            $query->whereNotNull('nama_alumni')
                ->whereNotNull('id_prodi')  // Sekarang menggunakan id_prodi (foreign key)
                ->where('nama_alumni', '!=', '')
                ->with(['prodi', 'instansi']);  // Load relasi prodi dan instansi
        }])
            ->whereNotIn('id_pengguna_lulusan', function ($subQuery) {
                $subQuery->select('id_pengguna_lulusan')
                    ->from('jawaban')
                    ->whereNotNull('id_pengguna_lulusan');
            })
            ->orderBy('nama_atasan', 'asc')
            ->get();

        // Filter hanya yang punya alumni
        $penggunaLulusan = $penggunaLulusan->filter(function ($pengguna) {
            return $pengguna->alumni->isNotEmpty();
        });

        // Buat spreadsheet baru
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();

        // Set header sesuai permintaan
        $headers = ['Nama', 'Instansi', 'Jabatan', 'Nama Alumni', 'Program Studi', 'Tahun Lulus'];
        $sheet->fromArray($headers, null, 'A1');

        // Style untuk header
        $headerStyle = [
            'font' => [
                'bold' => true,
                'size' => 12,
            ],
            'fill' => [
                'fillType' => Fill::FILL_SOLID,
                'startColor' => [
                    'argb' => 'FFCCCCCC',
                ],
            ],
            'borders' => [
                'allBorders' => [
                    'borderStyle' => Border::BORDER_THIN,
                ],
            ],
        ];
        $sheet->getStyle('A1:F1')->applyFromArray($headerStyle);

        // Auto width untuk semua kolom
        foreach (range('A', 'F') as $column) {
            $sheet->getColumnDimension($column)->setAutoSize(true);
        }

        // Isi data
        $row = 2;
        foreach ($penggunaLulusan as $pengguna) {
            foreach ($pengguna->alumni as $alumni) {
                // Ambil tahun dari tgl_lulus
                $tahunLulus = '-';
                if ($alumni->tgl_lulus) {
                    $tahunLulus = date('Y', strtotime($alumni->tgl_lulus));
                }

                $data = [
                    $pengguna->nama_atasan ?? '-',
                    $alumni->instansi->nama_instansi ?? '-',
                    $pengguna->jabatan_atasan ?? '-',
                    $alumni->nama_alumni ?? '-',
                    $alumni->prodi->nama_prodi ?? '-',  // Menggunakan relasi prodi
                    $tahunLulus
                ];
                $sheet->fromArray($data, null, 'A' . $row);
                $row++;
            }
        }

        // Jika tidak ada data
        if ($row == 2) {
            $sheet->setCellValue('A2', 'Tidak ada pengguna lulusan yang belum mengisi survey');
            $sheet->mergeCells('A2:F2');
            $row = 3;
        }

        // Set border untuk semua data
        if ($row > 2) {
            $dataRange = 'A1:F' . ($row - 1);
            $sheet->getStyle($dataRange)->applyFromArray([
                'borders' => [
                    'allBorders' => [
                        'borderStyle' => Border::BORDER_THIN,
                    ],
                ],
            ]);
        }

        // Buat writer dan download
        $writer = new Xlsx($spreadsheet);
        $filename = 'pengguna_lulusan_belum_survey_' . date('Y-m-d_H-i-s') . '.xlsx';

        // Set headers untuk download
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="' . $filename . '"');
        header('Cache-Control: max-age=0');

        $writer->save('php://output');
        exit;
    }

    /**
     * Export pengguna lulusan yang sudah mengisi survey
     */
    public function exportSudahIsiSurvey()
    {
        // Ambil pengguna lulusan yang sudah ada di tabel jawaban
        $penggunaLulusan = PenggunaLulusan::with(['alumni' => function ($query) {
            $query->whereNotNull('nama_alumni')
                ->whereNotNull('id_prodi')
                ->where('nama_alumni', '!=', '')
                ->with(['prodi', 'instansi']);
        }])
        ->whereIn('id_pengguna_lulusan', function ($subQuery) {
            $subQuery->select('id_pengguna_lulusan')
                ->from('jawaban')
                ->whereNotNull('id_pengguna_lulusan');
        })
        ->orderBy('nama_atasan', 'asc')
        ->get();

        // Filter hanya yang punya alumni dan sudah ada jawaban untuk alumni tersebut
        $penggunaLulusan = $penggunaLulusan->filter(function ($pengguna) {
            return $pengguna->alumni->isNotEmpty();
        });

        // Ambil semua pertanyaan yang berkategori pengguna_lulusan
        $pertanyaanPenilaian = DB::table('pertanyaan')
            ->where('kategori', 'pengguna_lulusan')
            ->where('metodejawaban', 1)
            ->orderBy('id_pertanyaan')
            ->get();

        $pertanyaanMasukan = DB::table('pertanyaan')
            ->where('kategori', 'pengguna_lulusan')
            ->where('metodejawaban', 2)
            ->orderBy('id_pertanyaan')
            ->get();

        // Buat spreadsheet baru
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();

        // Set header dinamis
        $headers = [
            'Nama',
            'Instansi',
            'Jabatan',
            'Nama Alumni',
            'Program Studi',
            'Tahun Lulus'
        ];

        // Tambahkan header pertanyaan penilaian secara dinamis
        foreach ($pertanyaanPenilaian as $itemPenilaian) {
            $headers[] = $itemPenilaian->isi_pertanyaan;
        }

        // Tambahkan header pertanyaan masukan secara dinamis
        foreach ($pertanyaanMasukan as $itemMasukan) {
            $headers[] = $itemMasukan->isi_pertanyaan;
        }

        $sheet->fromArray($headers, null, 'A1');

        // Style untuk header
        $headerStyle = [
            'font' => [
                'bold' => true,
                'size' => 12,
            ],
            'fill' => [
                'fillType' => Fill::FILL_SOLID,
                'startColor' => [
                    'argb' => 'FFCCCCCC',
                ],
            ],
            'borders' => [
                'allBorders' => [
                    'borderStyle' => Border::BORDER_THIN,
                ],
            ],
        ];
        $lastColumn = chr(65 + count($headers) - 1);
        $sheet->getStyle('A1:' . $lastColumn . '1')->applyFromArray($headerStyle);

        // Auto width untuk semua kolom
        for ($i = 0; $i < count($headers); $i++) {
            $column = chr(65 + $i);
            $sheet->getColumnDimension($column)->setAutoSize(true);
        }

        // Function untuk convert angka ke text penilaian
        $convertRating = function ($rating) {
            switch ($rating) {
                case 1:
                    return 'Sangat Kurang';
                case 2:
                    return 'Kurang';
                case 3:
                    return 'Cukup';
                case 4:
                    return 'Baik';
                case 5:
                    return 'Sangat Baik';
                default:
                    return '-';
            }
        };

        // Isi data
        $row = 2;
        foreach ($penggunaLulusan as $pengguna) {
            foreach ($pengguna->alumni as $alumni) {
                // **PENTING**: Cek apakah ada jawaban untuk alumni ini (berdasarkan nim_alumni)
                $adaJawaban = DB::table('jawaban')
                    ->where('nim_alumni', $alumni->nim)
                    ->where('id_pengguna_lulusan', $pengguna->id_pengguna_lulusan)
                    ->exists();

                // Hanya export jika ada jawaban untuk alumni ini
                if (!$adaJawaban) {
                    continue;
                }

                // Ambil tahun dari tgl_lulus
                $tahunLulus = '-';
                if ($alumni->tgl_lulus) {
                    $tahunLulus = date('Y', strtotime($alumni->tgl_lulus));
                }

                // Ambil semua jawaban untuk pengguna lulusan ini DAN alumni ini (berdasarkan nim_alumni)
                $jawabanPenilaian = DB::table('jawaban')
                    ->join('pertanyaan', 'jawaban.id_pertanyaan', '=', 'pertanyaan.id_pertanyaan')
                    ->where('jawaban.id_pengguna_lulusan', $pengguna->id_pengguna_lulusan)
                    ->where('jawaban.nim_alumni', $alumni->nim)  // Filter berdasarkan nim alumni
                    ->where('pertanyaan.kategori', 'pengguna_lulusan')
                    ->where('pertanyaan.metodejawaban', 1)
                    ->select('pertanyaan.id_pertanyaan', 'jawaban.jawaban')
                    ->get()
                    ->keyBy('id_pertanyaan');

                $jawabanMasukan = DB::table('jawaban')
                    ->join('pertanyaan', 'jawaban.id_pertanyaan', '=', 'pertanyaan.id_pertanyaan')
                    ->where('jawaban.id_pengguna_lulusan', $pengguna->id_pengguna_lulusan)
                    ->where('jawaban.nim_alumni', $alumni->nim)  // Filter berdasarkan nim alumni
                    ->where('pertanyaan.kategori', 'pengguna_lulusan')
                    ->where('pertanyaan.metodejawaban', 2)
                    ->select('pertanyaan.id_pertanyaan', 'jawaban.jawaban')
                    ->get()
                    ->keyBy('id_pertanyaan');

                // Data dasar
                $data = [
                    $pengguna->nama_atasan ?? '-',
                    $alumni->instansi->nama_instansi ?? '-',
                    $pengguna->jabatan_atasan ?? '-',
                    $alumni->nama_alumni ?? '-',
                    $alumni->prodi->nama_prodi ?? '-',
                    $tahunLulus,
                ];

                // Tambahkan jawaban penilaian secara dinamis
                foreach ($pertanyaanPenilaian as $penilaianItem) {
                    $jawaban = null;
                    if (isset($jawabanPenilaian[$penilaianItem->id_pertanyaan])) {
                        $jawaban = $jawabanPenilaian[$penilaianItem->id_pertanyaan]->jawaban;
                    }
                    $data[] = $convertRating($jawaban);
                }

                // Tambahkan jawaban masukan secara dinamis
                foreach ($pertanyaanMasukan as $masukanItem) {
                    $jawaban = '-';
                    if (isset($jawabanMasukan[$masukanItem->id_pertanyaan])) {
                        $jawaban = $jawabanMasukan[$masukanItem->id_pertanyaan]->jawaban;
                    }
                    $data[] = $jawaban;
                }

                $sheet->fromArray($data, null, 'A' . $row);
                $row++;
            }
        }

        // Jika tidak ada data
        if ($row == 2) {
            $sheet->setCellValue('A2', 'Tidak ada pengguna lulusan yang sudah mengisi survey');
            $sheet->mergeCells('A2:' . $lastColumn . '2');
            $row = 3;
        }

        // Set border untuk semua data
        if ($row > 2) {
            $dataRange = 'A1:' . $lastColumn . ($row - 1);
            $sheet->getStyle($dataRange)->applyFromArray([
                'borders' => [
                    'allBorders' => [
                        'borderStyle' => Border::BORDER_THIN,
                    ],
                ],
            ]);
        }

        // Buat writer dan download
        $writer = new Xlsx($spreadsheet);
        $filename = 'pengguna_lulusan_sudah_survey_' . date('Y-m-d_H-i-s') . '.xlsx';

        // Set headers untuk download
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="' . $filename . '"');
        header('Cache-Control: max-age=0');

        $writer->save('php://output');
        exit;
    }
}
