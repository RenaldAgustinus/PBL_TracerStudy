<?php

namespace App\Http\Controllers;

use App\Models\Alumni;
use App\Models\PenggunaLulusan;
use App\Models\Instansi;
use App\Models\Prodi;
use App\Models\Profesi;
use Illuminate\Http\Request;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use Carbon\Carbon;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Font;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Style\Border;

class AlumniController extends Controller
{
    // Menampilkan data dalam tabel
    // Menampilkan data dalam tabel dengan search dan filter
    public function index(Request $request)
    {
        // Base query dengan relasi
        $query = Alumni::with(['prodi', 'profesi', 'instansi', 'penggunaLulusan']);

        // ===== SEARCH FUNCTIONALITY =====
        if ($request->filled('search')) {
            $searchTerm = $request->search;
            $query->where(function ($q) use ($searchTerm) {
                $q->where('nim', 'LIKE', '%' . $searchTerm . '%')
                    ->orWhere('nama_alumni', 'LIKE', '%' . $searchTerm . '%')
                    ->orWhereHas('instansi', function ($instansiQuery) use ($searchTerm) {
                        $instansiQuery->where('nama_instansi', 'LIKE', '%' . $searchTerm . '%');
                    });
            });
        }

        // ===== FILTER FUNCTIONALITY =====

        // Filter by Prodi
        if ($request->filled('filter_prodi')) {
            $query->where('id_prodi', $request->filter_prodi);
        }

        // Filter by Jurusan (melalui relasi prodi)
        if ($request->filled('filter_jurusan')) {
            $query->whereHas('prodi', function ($prodiQuery) use ($request) {
                $prodiQuery->where('jurusan', $request->filter_jurusan);
            });
        }

        // Filter by Tahun Masuk
        if ($request->filled('filter_tahun_masuk')) {
            $query->where('tahun_masuk', $request->filter_tahun_masuk);
        }

        // Filter by Profesi
        if ($request->filled('filter_profesi')) {
            $query->where('id_profesi', $request->filter_profesi);
        }

        // ===== ADVANCED FILTERS =====

        // Filter by Masa Tunggu Range
        if ($request->filled('filter_masa_tunggu_min')) {
            $query->where('masa_tunggu', '>=', $request->filter_masa_tunggu_min);
        }

        if ($request->filled('filter_masa_tunggu_max')) {
            $query->where('masa_tunggu', '<=', $request->filter_masa_tunggu_max);
        }

        // Filter by Jenis Instansi
        if ($request->filled('filter_jenis_instansi')) {
            $query->whereHas('instansi', function ($instansiQuery) use ($request) {
                $instansiQuery->where('jenis_instansi', $request->filter_jenis_instansi);
            });
        }

        // Filter by Skala Instansi
        if ($request->filled('filter_skala_instansi')) {
            $query->whereHas('instansi', function ($instansiQuery) use ($request) {
                $instansiQuery->where('skala_instansi', $request->filter_skala_instansi);
            });
        }

        // Filter by Status Pengisian
        if ($request->filled('filter_status')) {
            if ($request->filter_status === 'sudah') {
                // Alumni yang sudah mengisi SEMUA field tracer study (tidak boleh ada yang null)
                $query->where(function ($q) {
                    $q->whereNotNull('no_hp')
                        ->whereNotNull('email')
                        ->whereNotNull('tanggal_kerja_pertama')
                        ->whereNotNull('tanggal_mulai_instansi')
                        ->whereNotNull('masa_tunggu')
                        ->whereNotNull('id_profesi')
                        ->whereNotNull('id_pengguna_lulusan')
                        ->whereNotNull('id_instansi')
                        // Pastikan field string tidak kosong
                        ->where('no_hp', '!=', '')
                        ->where('email', '!=', '');
                });
            } elseif ($request->filter_status === 'belum') {
                // Alumni yang belum mengisi LENGKAP (ada minimal 1 field tracer study yang kosong)
                $query->where(function ($q) {
                    $q->whereNull('id_profesi')
                        ->orWhereNull('id_instansi')
                        ->orWhereNull('no_hp')
                        ->orWhereNull('email')
                        ->orWhereNull('tanggal_kerja_pertama')
                        ->orWhereNull('tanggal_mulai_instansi')
                        ->orWhereNull('masa_tunggu')
                        ->orWhereNull('id_pengguna_lulusan')
                        // Atau field string kosong
                        ->orWhere('no_hp', '')
                        ->orWhere('email', '');
                });
            }
        }

        // Filter by Tahun Lulus
        if ($request->filled('filter_tahun_lulus')) {
            $query->whereYear('tgl_lulus', $request->filter_tahun_lulus);
        }

        // Get filtered data
        $alumni = $query->orderBy('nim')->get();

        // Get filter options
        $prodis = Prodi::orderBy('nama_prodi')->get();
        $jurusans = Prodi::select('jurusan')->distinct()->orderBy('jurusan')->pluck('jurusan');
        $profesis = Profesi::orderBy('nama_profesi')->get();

        // Get tahun masuk yang tersedia
        $tahunMasuks = Alumni::whereNotNull('tahun_masuk')
            ->distinct()
            ->orderBy('tahun_masuk', 'desc')
            ->pluck('tahun_masuk');

        // Get tahun lulus yang tersedia
        $tahunLulus = Alumni::whereNotNull('tgl_lulus')
            ->selectRaw('YEAR(tgl_lulus) as tahun')
            ->distinct()
            ->orderBy('tahun', 'desc')
            ->pluck('tahun');

        return view('admin.Alumni.indexAlumni', compact(
            'alumni',
            'prodis',
            'jurusans',
            'profesis',
            'tahunMasuks',
            'tahunLulus'
        ));
    }

    // AJAX method untuk mendapatkan prodi berdasarkan jurusan
    public function getProdiByJurusan(Request $request)
    {
        $jurusan = $request->jurusan;
        $prodis = Prodi::where('jurusan', $jurusan)->orderBy('nama_prodi')->get(['id_prodi', 'nama_prodi']);

        return response()->json($prodis);
    }

    // Method untuk reset filter
    public function resetFilter()
    {
        return redirect()->route('alumni.index');
    }

    // Menampilkan form untuk tambah data
    public function create()
    {
        $prodis = Prodi::all();
        $profesis = Profesi::all();
        return view('admin.Alumni.createAlumni', compact('prodis', 'profesis'));
    }

    // Menyimpan data baru
    public function store(Request $request)
    {
        try {
            // Base validation rules
            $rules = [
                // Field wajib (required)
                'nim' => 'required|unique:alumni,nim|numeric|digits_between:8,15',
                'nama_alumni' => 'required|string|max:255',
                'id_prodi' => 'required|exists:prodi,id_prodi',
                'tgl_lulus' => 'required|date',

                // Field opsional (nullable)
                'tahun_masuk' => 'nullable|digits:4|integer|min:1900|max:' . (date('Y') + 1),
                'email' => 'nullable|email|max:255',
                'no_hp' => 'nullable|numeric|digits_between:10,15',
                'tanggal_kerja_pertama' => 'nullable|date',
                'tanggal_mulai_instansi' => 'nullable|date',
                'id_profesi' => 'nullable|exists:profesi,id_profesi',

                // Field instansi
                'nama_instansi' => 'nullable|string|max:255',
                'jenis_instansi' => 'nullable|in:Pendidikan Tinggi,Instansi Pemerintah,BUMN,Perusahaan Swasta',
                'skala_instansi' => 'nullable|in:Wirausaha,Nasional,Multinasional',
                'lokasi_instansi' => 'nullable|string|max:255',
                'no_hp_instansi' => 'nullable|numeric|digits_between:10,15',
            ];

            // Custom messages
            $messages = [
                'nama_atasan.required_with' => 'Nama atasan wajib diisi jika salah satu field atasan lainnya diisi.',
                'jabatan_atasan.required_with' => 'Jabatan atasan wajib diisi jika salah satu field atasan lainnya diisi.',
                'email_atasan.required_with' => 'Email atasan wajib diisi jika salah satu field atasan lainnya diisi.',
                'email_atasan.email' => 'Format email atasan tidak valid.',
            ];

            // Conditional validation untuk field atasan
            $hasAnyAtasanField = $request->filled('nama_atasan') ||
                $request->filled('jabatan_atasan') ||
                $request->filled('email_atasan');

            if ($hasAnyAtasanField) {
                // Jika salah satu field atasan diisi, maka semua field atasan harus diisi
                $rules['nama_atasan'] = 'required_with:jabatan_atasan,email_atasan|string|max:255';
                $rules['jabatan_atasan'] = 'required_with:nama_atasan,email_atasan|string|max:255';
                $rules['email_atasan'] = 'required_with:nama_atasan,jabatan_atasan|email|max:255';
            } else {
                // Jika tidak ada field atasan yang diisi, maka semua opsional
                $rules['nama_atasan'] = 'nullable|string|max:255';
                $rules['jabatan_atasan'] = 'nullable|string|max:255';
                $rules['email_atasan'] = 'nullable|email|max:255';
            }

            $request->validate($rules, $messages);

            // Inisialisasi ID untuk relasi
            $id_pengguna_lulusan = null;
            $id_instansi = null;

            // ===== HANDLE PENGGUNA LULUSAN =====
            // Cek apakah SEMUA field atasan terisi (setelah validasi, jika ada salah satu maka semua harus terisi)
            $hasCompleteAtasanData = $request->filled('nama_atasan') &&
                $request->filled('jabatan_atasan') &&
                $request->filled('email_atasan');

            if ($hasCompleteAtasanData) {
                $penggunaLulusan = PenggunaLulusan::updateOrCreate(
                    ['email_atasan' => $request->email_atasan],
                    [
                        'nama_atasan' => $request->nama_atasan,
                        'jabatan_atasan' => $request->jabatan_atasan,
                        'email_atasan' => $request->email_atasan,
                    ]
                );
                $id_pengguna_lulusan = $penggunaLulusan->id_pengguna_lulusan;
            }

            // ===== HANDLE INSTANSI =====
            $hasInstansiData = $request->filled('nama_instansi') ||
                $request->filled('jenis_instansi') ||
                $request->filled('skala_instansi') ||
                $request->filled('lokasi_instansi') ||
                $request->filled('no_hp_instansi');

            if ($hasInstansiData) {
                $namaInstansi = $request->nama_instansi ?: 'Unknown_' . time();

                $instansi = Instansi::updateOrCreate(
                    ['nama_instansi' => $namaInstansi],
                    [
                        'nama_instansi' => $request->nama_instansi,
                        'jenis_instansi' => $request->jenis_instansi,
                        'skala_instansi' => $request->skala_instansi,
                        'lokasi_instansi' => $request->lokasi_instansi,
                        'no_hp_instansi' => $request->no_hp_instansi,
                    ]
                );
                $id_instansi = $instansi->id_instansi;
            }

            // Hitung masa tunggu (dalam bulan) berdasarkan tgl_lulus dan tanggal_kerja_pertama
            $masaTunggu = null;
            if ($request->tgl_lulus && $request->tanggal_kerja_pertama) {
                $masaTunggu = \Carbon\Carbon::parse($request->tgl_lulus)
                    ->diffInMonths(\Carbon\Carbon::parse($request->tanggal_kerja_pertama), false);
            }

            // Simpan data alumni
            Alumni::create([
                'nim' => $request->nim,
                'nama_alumni' => $request->nama_alumni,
                'id_prodi' => $request->id_prodi,
                'no_hp' => $request->no_hp,
                'email' => $request->email,
                'tahun_masuk' => $request->tahun_masuk,
                'tgl_lulus' => $request->tgl_lulus,
                'tanggal_kerja_pertama' => $request->tanggal_kerja_pertama,
                'tanggal_mulai_instansi' => $request->tanggal_mulai_instansi,
                'masa_tunggu' => $masaTunggu,
                'id_profesi' => $request->id_profesi,
                'id_pengguna_lulusan' => $id_pengguna_lulusan,
                'id_instansi' => $id_instansi,
            ]);

            return redirect()->route('alumni.index')->with('success', 'Data alumni berhasil ditambahkan.');
        } catch (\Illuminate\Validation\ValidationException $e) {
            return back()->withErrors($e->errors())->withInput();
        } catch (\Exception $e) {
            return back()->withErrors(['error' => 'Error: ' . $e->getMessage()])->withInput();
        }
    }

    // Menampilkan form untuk edit data
    public function edit($nim)
    {
        $alumni = Alumni::with(['prodi', 'profesi', 'instansi', 'penggunaLulusan'])->findOrFail($nim);
        $prodis = Prodi::all();
        $profesis = Profesi::all();
        return view('admin.Alumni.editAlumni', compact('alumni', 'prodis', 'profesis'));
    }

    // Mengupdate data
    public function update(Request $request, $nim)
    {
        try {
            // Base validation rules
            $rules = [
                'nama_alumni' => 'required|max:100',
                'id_prodi' => 'required|exists:prodi,id_prodi',
                'tgl_lulus' => 'required|date',
                'tahun_masuk' => 'nullable|digits:4|integer|min:1900|max:' . (date('Y') + 1),
                'tanggal_kerja_pertama' => 'nullable|date',
                'tanggal_mulai_instansi' => 'nullable|date',
                'email' => 'nullable|email',
                'no_hp' => 'nullable|numeric|digits_between:10,15',
                'id_profesi' => 'nullable|exists:profesi,id_profesi',
                'nama_instansi' => 'nullable|max:100',
                'jenis_instansi' => 'nullable|in:Pendidikan Tinggi,Instansi Pemerintah,BUMN,Perusahaan Swasta',
                'skala_instansi' => 'nullable|in:Wirausaha,Nasional,Multinasional',
                'lokasi_instansi' => 'nullable|string|max:255',
                'no_hp_instansi' => 'nullable|numeric|digits_between:10,15',
            ];

            // Custom messages
            $messages = [
                'nama_atasan.required_with' => 'Nama atasan wajib diisi jika salah satu field atasan lainnya diisi.',
                'jabatan_atasan.required_with' => 'Jabatan atasan wajib diisi jika salah satu field atasan lainnya diisi.',
                'email_atasan.required_with' => 'Email atasan wajib diisi jika salah satu field atasan lainnya diisi.',
                'email_atasan.email' => 'Format email atasan tidak valid.',
            ];

            // Conditional validation untuk field atasan
            $hasAnyAtasanField = $request->filled('nama_atasan') || 
                                $request->filled('jabatan_atasan') || 
                                $request->filled('email_atasan');

            if ($hasAnyAtasanField) {
                // Jika salah satu field atasan diisi, maka semua field atasan harus diisi
                $rules['nama_atasan'] = 'required_with:jabatan_atasan,email_atasan|string|max:255';
                $rules['jabatan_atasan'] = 'required_with:nama_atasan,email_atasan|string|max:255';
                $rules['email_atasan'] = 'required_with:nama_atasan,jabatan_atasan|email|max:255';
            } else {
                // Jika tidak ada field atasan yang diisi, maka semua opsional
                $rules['nama_atasan'] = 'nullable|string|max:255';
                $rules['jabatan_atasan'] = 'nullable|string|max:255';
                $rules['email_atasan'] = 'nullable|email|max:255';
            }

            $request->validate($rules, $messages);

            $alumni = Alumni::findOrFail($nim);

            // Inisialisasi ID untuk relasi
            $id_pengguna_lulusan = $alumni->id_pengguna_lulusan;
            $id_instansi = $alumni->id_instansi;

            // ===== HANDLE PENGGUNA LULUSAN =====
            // Cek apakah SEMUA field atasan terisi
            $hasCompleteAtasanData = $request->filled('nama_atasan') && 
                                    $request->filled('jabatan_atasan') && 
                                    $request->filled('email_atasan');

            // Cek apakah SEMUA field atasan kosong (untuk clear data)
            $allAtasanEmpty = !$request->filled('nama_atasan') && 
                             !$request->filled('jabatan_atasan') && 
                             !$request->filled('email_atasan');

            if ($hasCompleteAtasanData) {
                $penggunaLulusan = PenggunaLulusan::updateOrCreate(
                    ['email_atasan' => $request->email_atasan],
                    [
                        'nama_atasan' => $request->nama_atasan,
                        'jabatan_atasan' => $request->jabatan_atasan,
                        'email_atasan' => $request->email_atasan,
                    ]
                );
                $id_pengguna_lulusan = $penggunaLulusan->id_pengguna_lulusan;
            } elseif ($allAtasanEmpty) {
                // Jika semua field atasan dikosongkan, hapus relasi
                $id_pengguna_lulusan = null;
            }

            // ===== HANDLE INSTANSI =====
            $hasInstansiData = $request->filled('nama_instansi') ||
                $request->filled('jenis_instansi') ||
                $request->filled('skala_instansi') ||
                $request->filled('lokasi_instansi') ||
                $request->filled('no_hp_instansi');

            $allInstansiEmpty = !$request->filled('nama_instansi') && 
                               !$request->filled('jenis_instansi') && 
                               !$request->filled('skala_instansi') && 
                               !$request->filled('lokasi_instansi') && 
                               !$request->filled('no_hp_instansi');

            if ($hasInstansiData) {
                $namaInstansi = $request->nama_instansi ?: 'Unknown_' . time();

                $instansi = Instansi::updateOrCreate(
                    ['nama_instansi' => $namaInstansi],
                    [
                        'nama_instansi' => $request->nama_instansi,
                        'jenis_instansi' => $request->jenis_instansi,
                        'skala_instansi' => $request->skala_instansi,
                        'lokasi_instansi' => $request->lokasi_instansi,
                        'no_hp_instansi' => $request->no_hp_instansi,
                    ]
                );
                $id_instansi = $instansi->id_instansi;
            } elseif ($allInstansiEmpty) {
                // Jika semua field instansi dikosongkan, hapus relasi
                $id_instansi = null;
            }

            // Hitung masa tunggu (dalam bulan) berdasarkan tgl_lulus dan tanggal_kerja_pertama
            $masaTunggu = null;
            if ($request->tgl_lulus && $request->tanggal_kerja_pertama) {
                $masaTunggu = \Carbon\Carbon::parse($request->tgl_lulus)
                    ->diffInMonths(\Carbon\Carbon::parse($request->tanggal_kerja_pertama), false);
            }

            // Update data alumni
            $alumni->update([
                'nama_alumni' => $request->nama_alumni,
                'id_prodi' => $request->id_prodi,
                'no_hp' => $request->no_hp,
                'email' => $request->email,
                'tahun_masuk' => $request->tahun_masuk,
                'tgl_lulus' => $request->tgl_lulus,
                'tanggal_kerja_pertama' => $request->tanggal_kerja_pertama,
                'tanggal_mulai_instansi' => $request->tanggal_mulai_instansi,
                'masa_tunggu' => $masaTunggu,
                'id_profesi' => $request->id_profesi,
                'id_pengguna_lulusan' => $id_pengguna_lulusan,
                'id_instansi' => $id_instansi,
            ]);

            return redirect()->route('alumni.index')->with('success', 'Data alumni berhasil diperbarui.');
        } catch (\Illuminate\Validation\ValidationException $e) {
            return back()->withErrors($e->errors())->withInput();
        } catch (\Exception $e) {
            return back()->withErrors(['error' => 'Error: ' . $e->getMessage()])->withInput();
        }
    }

    // Menghapus data
    public function destroy($nim)
    {
        try {
            $alumni = Alumni::findOrFail($nim);
            $alumni->delete();
            return redirect()->route('alumni.index')->with('success', 'Data alumni berhasil dihapus.');
        } catch (\Exception $e) {
            return redirect()->route('alumni.index')->with('error', 'Gagal menghapus data alumni.');
        }
    }

    public function import(Request $request)
    {
        try {
            $request->validate([
                'file' => 'required|mimes:xlsx,xls,csv|max:2048'
            ]);

            $file = $request->file('file');
            $path = $file->getRealPath();

            // Load spreadsheet
            $spreadsheet = IOFactory::load($path);
            $worksheet = $spreadsheet->getActiveSheet();
            $rows = $worksheet->toArray();

            // Skip header row
            $dataRows = array_slice($rows, 1);

            $importedCount = 0;
            $skippedCount = 0;
            $errors = [];

            foreach ($dataRows as $index => $row) {
                $rowNumber = $index + 2; // +2 karena array start dari 0 dan skip header

                // Skip jika row kosong
                if (empty($row[1])) { // Kolom NIM (index 1)
                    $skippedCount++;
                    continue;
                }

                try {
                    $programStudi = trim($row[0] ?? '');
                    $nim = trim($row[1] ?? '');
                    $nama = trim($row[2] ?? '');
                    $tanggalLulus = $row[3] ?? '';

                    // Validasi basic
                    if (empty($nim) || empty($nama) || empty($programStudi)) {
                        $errors[] = "Baris {$rowNumber}: Data tidak lengkap";
                        $skippedCount++;
                        continue;
                    }

                    // Cari prodi
                    $prodi = Prodi::where('nama_prodi', 'LIKE', '%' . $programStudi . '%')->first();
                    if (!$prodi) {
                        $errors[] = "Baris {$rowNumber}: Program Studi '{$programStudi}' tidak ditemukan";
                        $skippedCount++;
                        continue;
                    }

                    // Cek duplicate NIM
                    if (Alumni::where('nim', $nim)->exists()) {
                        $errors[] = "Baris {$rowNumber}: NIM {$nim} sudah ada";
                        $skippedCount++;
                        continue;
                    }

                    // Parse tanggal lulus
                    $tglLulus = null;
                    if (!empty($tanggalLulus)) {
                        try {
                            if (is_numeric($tanggalLulus)) {
                                // Excel date serial number
                                $tglLulus = \PhpOffice\PhpSpreadsheet\Shared\Date::excelToDateTimeObject($tanggalLulus)->format('Y-m-d');
                            } else {
                                // String date
                                $tglLulus = Carbon::parse($tanggalLulus)->format('Y-m-d');
                            }
                        } catch (\Exception $e) {
                            $errors[] = "Baris {$rowNumber}: Format tanggal tidak valid";
                            $skippedCount++;
                            continue;
                        }
                    }

                    // Simpan data
                    Alumni::create([
                        'nim' => $nim,
                        'nama_alumni' => $nama,
                        'id_prodi' => $prodi->id_prodi,
                        'tgl_lulus' => $tglLulus,
                        'tahun_masuk' => null,
                        'no_hp' => null,
                        'email' => null,
                        'tanggal_kerja_pertama' => null,
                        'tanggal_mulai_instansi' => null,
                        'masa_tunggu' => null,
                        'id_profesi' => null,
                        'id_pengguna_lulusan' => null,
                        'id_instansi' => null,
                    ]);

                    $importedCount++;
                } catch (\Exception $e) {
                    $errors[] = "Baris {$rowNumber}: Error - " . $e->getMessage();
                    $skippedCount++;
                }
            }

            // Response message
            $message = "Import selesai! Berhasil: {$importedCount}, Dilewati: {$skippedCount}";

            if (count($errors) > 0) {
                return redirect()->route('alumni.index')
                    ->with('warning', $message)
                    ->with('import_errors', $errors);
            }

            return redirect()->route('alumni.index')->with('success', $message);
        } catch (\Exception $e) {
            return redirect()->route('alumni.index')
                ->with('error', 'Error saat import: ' . $e->getMessage());
        }
    }

    // Download template Excel
    public function downloadTemplate()
    {
        try {
            $spreadsheet = new Spreadsheet();
            $sheet = $spreadsheet->getActiveSheet();

            // Set header
            $headers = [
                'A1' => 'Program Studi',
                'B1' => 'NIM',
                'C1' => 'Nama',
                'D1' => 'Tanggal Lulus'
            ];

            foreach ($headers as $cell => $value) {
                $sheet->setCellValue($cell, $value);
                $sheet->getStyle($cell)->getFont()->setBold(true);
                $sheet->getStyle($cell)->getFill()
                    ->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)
                    ->getStartColor()->setRGB('E2E8F0');
            }

            // Set column width
            $sheet->getColumnDimension('A')->setWidth(25);
            $sheet->getColumnDimension('B')->setWidth(15);
            $sheet->getColumnDimension('C')->setWidth(30);
            $sheet->getColumnDimension('D')->setWidth(15);

            // Add sample data
            $sampleData = [
                ['Teknik Informatika', '2341720001', 'John Doe', '2027-06-15'],
                ['Sistem Informasi Bisnis', '2341720002', 'Jane Smith', '2027-06-15'],
                ['Teknik Listrik', '2341720003', 'Bob Johnson', '2027-06-15'],
            ];

            $row = 2;
            foreach ($sampleData as $data) {
                $sheet->setCellValue('A' . $row, $data[0]);
                $sheet->setCellValue('B' . $row, $data[1]);
                $sheet->setCellValue('C' . $row, $data[2]);
                $sheet->setCellValue('D' . $row, $data[3]);
                $row++;
            }

            // Add instructions
            $sheet->setCellValue('F1', 'INSTRUKSI:');
            $sheet->setCellValue('F2', '1. Gunakan format Program Studi yang sesuai');
            $sheet->setCellValue('F3', '2. NIM harus berupa angka');
            $sheet->setCellValue('F4', '3. Format tanggal: YYYY-MM-DD atau DD/MM/YYYY');
            $sheet->setCellValue('F5', '4. Hapus data contoh sebelum import');

            $sheet->getStyle('F1')->getFont()->setBold(true);
            $sheet->getColumnDimension('F')->setWidth(40);

            // Add available prodi list
            $prodis = Prodi::all();
            $sheet->setCellValue('H1', 'PROGRAM STUDI TERSEDIA:');
            $sheet->getStyle('H1')->getFont()->setBold(true);

            $row = 2;
            foreach ($prodis as $prodi) {
                $sheet->setCellValue('H' . $row, $prodi->nama_prodi . ' (' . $prodi->jurusan . ')');
                $row++;
            }
            $sheet->getColumnDimension('H')->setWidth(35);

            // Set border for data area
            $styleArray = [
                'borders' => [
                    'allBorders' => [
                        'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                        'color' => ['argb' => '000000'],
                    ],
                ],
            ];
            $sheet->getStyle('A1:D4')->applyFromArray($styleArray);

            $writer = IOFactory::createWriter($spreadsheet, 'Xlsx');

            $filename = 'template_import_alumni_' . date('Y-m-d_H-i-s') . '.xlsx';

            header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
            header('Content-Disposition: attachment;filename="' . $filename . '"');
            header('Cache-Control: max-age=0');

            $writer->save('php://output');
            exit;
        } catch (\Exception $e) {
            return redirect()->route('alumni.index')
                ->with('error', 'Error saat download template: ' . $e->getMessage());
        }
    }

    // Export data alumni berdasarkan status pengisian tracer study
    public function export(Request $request)
    {
        try {
            $status = $request->get('status', 'semua'); // semua, sudah, belum

            // Query berdasarkan status dengan filter yang sama seperti di index
            $query = Alumni::with(['prodi', 'profesi', 'instansi', 'penggunaLulusan']);

            // ===== APPLY SAME FILTERS AS INDEX METHOD =====

            // Search functionality
            if ($request->filled('search')) {
                $searchTerm = $request->search;
                $query->where(function ($q) use ($searchTerm) {
                    $q->where('nim', 'LIKE', '%' . $searchTerm . '%')
                        ->orWhere('nama_alumni', 'LIKE', '%' . $searchTerm . '%')
                        ->orWhereHas('instansi', function ($instansiQuery) use ($searchTerm) {
                            $instansiQuery->where('nama_instansi', 'LIKE', '%' . $searchTerm . '%');
                        });
                });
            }

            // Filter by Prodi
            if ($request->filled('filter_prodi')) {
                $query->where('id_prodi', $request->filter_prodi);
            }

            // Filter by Jurusan
            if ($request->filled('filter_jurusan')) {
                $query->whereHas('prodi', function ($prodiQuery) use ($request) {
                    $prodiQuery->where('jurusan', $request->filter_jurusan);
                });
            }

            // Filter by Tahun Masuk
            if ($request->filled('filter_tahun_masuk')) {
                $query->where('tahun_masuk', $request->filter_tahun_masuk);
            }

            // Filter by Profesi
            if ($request->filled('filter_profesi')) {
                $query->where('id_profesi', $request->filter_profesi);
            }

            // Filter by Tahun Lulus
            if ($request->filled('filter_tahun_lulus')) {
                $query->whereYear('tgl_lulus', $request->filter_tahun_lulus);
            }

            // Advanced Filters
            if ($request->filled('filter_masa_tunggu_min')) {
                $query->where('masa_tunggu', '>=', $request->filter_masa_tunggu_min);
            }

            if ($request->filled('filter_masa_tunggu_max')) {
                $query->where('masa_tunggu', '<=', $request->filter_masa_tunggu_max);
            }

            if ($request->filled('filter_jenis_instansi')) {
                $query->whereHas('instansi', function ($instansiQuery) use ($request) {
                    $instansiQuery->where('jenis_instansi', $request->filter_jenis_instansi);
                });
            }

            if ($request->filled('filter_skala_instansi')) {
                $query->whereHas('instansi', function ($instansiQuery) use ($request) {
                    $instansiQuery->where('skala_instansi', $request->filter_skala_instansi);
                });
            }

            // ===== APPLY STATUS FILTER =====
            if ($status === 'sudah') {
                // Alumni yang sudah mengisi SEMUA field tracer study (LENGKAP)
                $query->where(function ($q) {
                    $q->whereNotNull('no_hp')
                        ->whereNotNull('email')
                        ->whereNotNull('tanggal_kerja_pertama')
                        ->whereNotNull('tanggal_mulai_instansi')
                        ->whereNotNull('masa_tunggu')
                        ->whereNotNull('id_profesi')
                        ->whereNotNull('id_pengguna_lulusan')
                        ->whereNotNull('id_instansi')
                        // Pastikan field string tidak kosong
                        ->where('no_hp', '!=', '')
                        ->where('email', '!=', '');
                });
                $fileName = 'alumni_sudah_mengisi_lengkap_tracer_study';
                $sheetTitle = 'Alumni Sudah Mengisi Lengkap';
            } elseif ($status === 'belum') {
                // Alumni yang belum mengisi LENGKAP (ada minimal 1 field yang kosong)
                $query->where(function ($q) {
                    $q->whereNull('id_profesi')
                        ->orWhereNull('id_instansi')
                        ->orWhereNull('no_hp')
                        ->orWhereNull('email')
                        ->orWhereNull('tanggal_kerja_pertama')
                        ->orWhereNull('tanggal_mulai_instansi')
                        ->orWhereNull('masa_tunggu')
                        ->orWhereNull('id_pengguna_lulusan')
                        // Atau field string kosong
                        ->orWhere('no_hp', '')
                        ->orWhere('email', '');
                });
                $fileName = 'alumni_belum_mengisi_lengkap_tracer_study';
                $sheetTitle = 'Alumni Belum Mengisi Lengkap';
            } else {
                // Semua alumni (tidak ada filter status tambahan)
                $fileName = 'data_alumni_lengkap';
                $sheetTitle = 'Data Alumni';
            }

            // Get filtered data
            $alumni = $query->orderBy('nim')->get();

            // Build filename with filter info
            $filterInfo = [];
            if ($request->filled('search')) {
                $filterInfo[] = 'search_' . str_replace(' ', '_', $request->search);
            }
            if ($request->filled('filter_prodi')) {
                $prodi = \App\Models\Prodi::find($request->filter_prodi);
                $filterInfo[] = 'prodi_' . str_replace(' ', '_', $prodi->nama_prodi ?? 'unknown');
            }
            if ($request->filled('filter_jurusan')) {
                $filterInfo[] = 'jurusan_' . str_replace(' ', '_', $request->filter_jurusan);
            }
            if ($request->filled('filter_tahun_masuk')) {
                $filterInfo[] = 'tahun_masuk_' . $request->filter_tahun_masuk;
            }
            if ($request->filled('filter_tahun_lulus')) {
                $filterInfo[] = 'tahun_lulus_' . $request->filter_tahun_lulus;
            }

            if (!empty($filterInfo)) {
                $fileName .= '_filtered_' . implode('_', array_slice($filterInfo, 0, 3)); // Limit to 3 filters in filename
            }

            // Buat spreadsheet
            $spreadsheet = new Spreadsheet();
            $sheet = $spreadsheet->getActiveSheet();
            $sheet->setTitle($sheetTitle);

            // Set headers berdasarkan status
            if ($status === 'belum') {
                $headers = [
                    'A1' => 'No',
                    'B1' => 'NIM',
                    'C1' => 'Nama Alumni',
                    'D1' => 'Program Studi',
                    'E1' => 'Jurusan',
                    'F1' => 'Tanggal Lulus',
                    'G1' => 'Tahun Masuk',
                    'H1' => 'Status',
                    'I1' => 'Field Kosong', // Tambahan untuk alumni belum lengkap
                ];
            } else {
                $headers = [
                    'A1' => 'No',
                    'B1' => 'NIM',
                    'C1' => 'Nama Alumni',
                    'D1' => 'Program Studi',
                    'E1' => 'Jurusan',
                    'F1' => 'No HP',
                    'G1' => 'Email',
                    'H1' => 'Tahun Masuk',
                    'I1' => 'Tanggal Lulus',
                    'J1' => 'Tanggal Kerja Pertama',
                    'K1' => 'Tanggal Mulai Instansi',
                    'L1' => 'Masa Tunggu (Bulan)',
                    'M1' => 'Profesi',
                    'N1' => 'Nama Instansi',
                    'O1' => 'Jenis Instansi',
                    'P1' => 'Skala Instansi',
                    'Q1' => 'Lokasi Instansi',
                    'R1' => 'Nama Atasan',
                    'S1' => 'Jabatan Atasan',
                    'T1' => 'Email Atasan',
                    'U1' => 'Kelengkapan (%)', // Tambahan untuk semua
                ];
            }

            // Set header styling
            foreach ($headers as $cell => $value) {
                $sheet->setCellValue($cell, $value);
                $sheet->getStyle($cell)->applyFromArray([
                    'font' => [
                        'bold' => true,
                        'color' => ['rgb' => 'FFFFFF'],
                        'size' => 11
                    ],
                    'fill' => [
                        'fillType' => Fill::FILL_SOLID,
                        'startColor' => ['rgb' => '4472C4']
                    ],
                    'alignment' => [
                        'horizontal' => Alignment::HORIZONTAL_CENTER,
                        'vertical' => Alignment::VERTICAL_CENTER
                    ],
                    'borders' => [
                        'allBorders' => [
                            'borderStyle' => Border::BORDER_THIN,
                            'color' => ['rgb' => '000000']
                        ]
                    ]
                ]);
            }

            // Set column widths
            if ($status === 'belum') {
                $sheet->getColumnDimension('A')->setWidth(5);
                $sheet->getColumnDimension('B')->setWidth(15);
                $sheet->getColumnDimension('C')->setWidth(25);
                $sheet->getColumnDimension('D')->setWidth(25);
                $sheet->getColumnDimension('E')->setWidth(20);
                $sheet->getColumnDimension('F')->setWidth(15);
                $sheet->getColumnDimension('G')->setWidth(12);
                $sheet->getColumnDimension('H')->setWidth(20);
                $sheet->getColumnDimension('I')->setWidth(30);
            } else {
                $columns = ['A', 'B', 'C', 'D', 'E', 'F', 'G', 'H', 'I', 'J', 'K', 'L', 'M', 'N', 'O', 'P', 'Q', 'R', 'S', 'T', 'U'];
                $widths = [5, 15, 25, 25, 20, 15, 25, 12, 15, 18, 18, 15, 20, 25, 20, 15, 20, 20, 20, 25, 15];

                foreach ($columns as $index => $column) {
                    $sheet->getColumnDimension($column)->setWidth($widths[$index]);
                }
            }

            // Fill data
            $row = 2;
            foreach ($alumni as $index => $item) {
                if ($status === 'belum') {
                    // Hitung field yang kosong untuk alumni belum lengkap
                    $requiredFields = ['no_hp', 'email', 'tanggal_kerja_pertama', 'tanggal_mulai_instansi', 'masa_tunggu', 'id_profesi', 'id_pengguna_lulusan', 'id_instansi'];
                    $emptyFields = [];

                    foreach ($requiredFields as $field) {
                        if (is_null($item->$field) || trim($item->$field) === '') {
                            $emptyFields[] = ucfirst(str_replace('_', ' ', $field));
                        }
                    }

                    $sheet->setCellValue('A' . $row, $index + 1);
                    $sheet->setCellValue('B' . $row, $item->nim);
                    $sheet->setCellValue('C' . $row, $item->nama_alumni);
                    $sheet->setCellValue('D' . $row, $item->prodi->nama_prodi ?? '-');
                    $sheet->setCellValue('E' . $row, $item->prodi->jurusan ?? '-');
                    $sheet->setCellValue('F' . $row, $item->tgl_lulus ? \Carbon\Carbon::parse($item->tgl_lulus)->format('d/m/Y') : '-');
                    $sheet->setCellValue('G' . $row, $item->tahun_masuk ?? '-');
                    $sheet->setCellValue('H' . $row, 'Belum Mengisi Lengkap');
                    $sheet->setCellValue('I' . $row, implode(', ', $emptyFields));
                } else {
                    // Hitung persentase kelengkapan
                    $requiredFields = ['no_hp', 'email', 'tanggal_kerja_pertama', 'tanggal_mulai_instansi', 'masa_tunggu', 'id_profesi', 'id_pengguna_lulusan', 'id_instansi'];
                    $completedFields = 0;

                    foreach ($requiredFields as $field) {
                        if (!is_null($item->$field) && trim($item->$field) !== '') {
                            $completedFields++;
                        }
                    }

                    $completionPercentage = round(($completedFields / count($requiredFields)) * 100);

                    $sheet->setCellValue('A' . $row, $index + 1);
                    $sheet->setCellValue('B' . $row, $item->nim);
                    $sheet->setCellValue('C' . $row, $item->nama_alumni);
                    $sheet->setCellValue('D' . $row, $item->prodi->nama_prodi ?? '-');
                    $sheet->setCellValue('E' . $row, $item->prodi->jurusan ?? '-');
                    $sheet->setCellValue('F' . $row, $item->no_hp ?? '-');
                    $sheet->setCellValue('G' . $row, $item->email ?? '-');
                    $sheet->setCellValue('H' . $row, $item->tahun_masuk ?? '-');
                    $sheet->setCellValue('I' . $row, $item->tgl_lulus ? \Carbon\Carbon::parse($item->tgl_lulus)->format('d/m/Y') : '-');
                    $sheet->setCellValue('J' . $row, $item->tanggal_kerja_pertama ? \Carbon\Carbon::parse($item->tanggal_kerja_pertama)->format('d/m/Y') : '-');
                    $sheet->setCellValue('K' . $row, $item->tanggal_mulai_instansi ? \Carbon\Carbon::parse($item->tanggal_mulai_instansi)->format('d/m/Y') : '-');
                    $sheet->setCellValue('L' . $row, $item->masa_tunggu ?? '-');
                    $sheet->setCellValue('M' . $row, $item->profesi->nama_profesi ?? '-');
                    $sheet->setCellValue('N' . $row, $item->instansi->nama_instansi ?? '-');
                    $sheet->setCellValue('O' . $row, $item->instansi->jenis_instansi ?? '-');
                    $sheet->setCellValue('P' . $row, $item->instansi->skala_instansi ?? '-');
                    $sheet->setCellValue('Q' . $row, $item->instansi->lokasi_instansi ?? '-');
                    $sheet->setCellValue('R' . $row, $item->penggunaLulusan->nama_atasan ?? '-');
                    $sheet->setCellValue('S' . $row, $item->penggunaLulusan->jabatan_atasan ?? '-');
                    $sheet->setCellValue('T' . $row, $item->penggunaLulusan->email_atasan ?? '-');
                    $sheet->setCellValue('U' . $row, $completionPercentage . '%');
                }
                $row++;
            }

            // Apply borders to data
            $lastColumn = $status === 'belum' ? 'I' : 'U';
            $dataRange = 'A1:' . $lastColumn . ($row - 1);
            $sheet->getStyle($dataRange)->applyFromArray([
                'borders' => [
                    'allBorders' => [
                        'borderStyle' => Border::BORDER_THIN,
                        'color' => ['rgb' => '000000']
                    ]
                ]
            ]);

            // Zebra striping for better readability
            for ($i = 2; $i < $row; $i += 2) {
                $sheet->getStyle('A' . $i . ':' . $lastColumn . $i)->applyFromArray([
                    'fill' => [
                        'fillType' => Fill::FILL_SOLID,
                        'startColor' => ['rgb' => 'F8F9FA']
                    ]
                ]);
            }

            // Add summary info
            $summaryRow = $row + 2;
            $sheet->setCellValue('A' . $summaryRow, 'RINGKASAN EXPORT:');
            $sheet->getStyle('A' . $summaryRow)->getFont()->setBold(true);

            $sheet->setCellValue('A' . ($summaryRow + 1), 'Total Data: ' . $alumni->count());
            $sheet->setCellValue('A' . ($summaryRow + 2), 'Tanggal Export: ' . \Carbon\Carbon::now()->format('d/m/Y H:i:s'));
            $sheet->setCellValue('A' . ($summaryRow + 3), 'Status Filter: ' . ucfirst($status));

            // Add applied filters info
            if ($request->anyFilled(['search', 'filter_prodi', 'filter_jurusan', 'filter_tahun_masuk', 'filter_profesi', 'filter_tahun_lulus'])) {
                $sheet->setCellValue('A' . ($summaryRow + 4), 'Filter Diterapkan:');
                $filterRow = $summaryRow + 5;

                if ($request->filled('search')) {
                    $sheet->setCellValue('A' . $filterRow, '- Pencarian: ' . $request->search);
                    $filterRow++;
                }
                if ($request->filled('filter_prodi')) {
                    $prodi = \App\Models\Prodi::find($request->filter_prodi);
                    $sheet->setCellValue('A' . $filterRow, '- Prodi: ' . ($prodi->nama_prodi ?? '-'));
                    $filterRow++;
                }
                if ($request->filled('filter_jurusan')) {
                    $sheet->setCellValue('A' . $filterRow, '- Jurusan: ' . $request->filter_jurusan);
                    $filterRow++;
                }
                if ($request->filled('filter_tahun_masuk')) {
                    $sheet->setCellValue('A' . $filterRow, '- Tahun Masuk: ' . $request->filter_tahun_masuk);
                    $filterRow++;
                }
                if ($request->filled('filter_tahun_lulus')) {
                    $sheet->setCellValue('A' . $filterRow, '- Tahun Lulus: ' . $request->filter_tahun_lulus);
                    $filterRow++;
                }
            } else {
                $sheet->setCellValue('A' . ($summaryRow + 4), 'Filter: Tidak ada filter diterapkan');
            }

            // Create writer and download
            $writer = IOFactory::createWriter($spreadsheet, 'Xlsx');

            $filename = $fileName . '_' . date('Y-m-d_H-i-s') . '.xlsx';

            header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
            header('Content-Disposition: attachment;filename="' . $filename . '"');
            header('Cache-Control: max-age=0');

            $writer->save('php://output');
            exit;
        } catch (\Exception $e) {
            return redirect()->route('alumni.index')
                ->with('error', 'Error saat export: ' . $e->getMessage());
        }
    }

    private function isCompletedTracer($alumni)
    {
        return !is_null($alumni->no_hp) &&
            !is_null($alumni->email) &&
            !is_null($alumni->tanggal_kerja_pertama) &&
            !is_null($alumni->tanggal_mulai_instansi) &&
            !is_null($alumni->masa_tunggu) &&
            !is_null($alumni->id_profesi) &&
            !is_null($alumni->id_pengguna_lulusan) &&
            !is_null($alumni->id_instansi) &&
            trim($alumni->no_hp) !== '' &&
            trim($alumni->email) !== '';
    }

    /**
     * Helper method untuk menghitung persentase kelengkapan data
     */
    private function getCompletionPercentage($alumni)
    {
        $requiredFields = [
            'no_hp',
            'email',
            'tanggal_kerja_pertama',
            'tanggal_mulai_instansi',
            'masa_tunggu',
            'id_profesi',
            'id_pengguna_lulusan',
            'id_instansi'
        ];

        $completedFields = 0;
        $totalFields = count($requiredFields);

        foreach ($requiredFields as $field) {
            if (!is_null($alumni->$field) && trim($alumni->$field) !== '') {
                $completedFields++;
            }
        }

        return round(($completedFields / $totalFields) * 100);
    }
}
