<?php

namespace App\Http\Controllers;

use App\Models\Alumni;
use Illuminate\Http\Request;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use Illuminate\Support\Facades\Validator;
use Yajra\DataTables\Facades\DataTables;
use Barryvdh\DomPDF\Facade\Pdf;

class ImportController extends Controller
{
    public function index()
    {
        return view('import.index', [
            'title' => 'Import Data Alumni'
        ]);
    }

    public function list(Request $request)
    {
        $alumni = Alumni::query();

        if ($request->filled('filter_prodi')) {
            $alumni->where('prodi', $request->filter_prodi);
        }

        return DataTables::of($alumni)
            ->addIndexColumn()
            ->addColumn('aksi', function ($alumni) {
                return '
                    <button onclick="modalAction(\''.url('/alumni/'.$alumni->nim.'/show_ajax').'\')" class="btn btn-info btn-sm">Detail</button>
                    <button onclick="modalAction(\''.url('/alumni/'.$alumni->nim.'/edit_ajax').'\')" class="btn btn-warning btn-sm">Edit</button>
                    <button onclick="modalAction(\''.url('/alumni/'.$alumni->nim.'/delete_ajax').'\')" class="btn btn-danger btn-sm">Hapus</button>
                ';
            })
            ->rawColumns(['aksi'])
            ->make(true);
    }
    public function edit_ajax(string $nim)
{
    $alumni = Alumni::where('nim', $nim)->first();

    if (!$alumni) {
        return view('import.edit_ajax', ['alumni' => null]);
    }

    return view('import.edit_ajax', ['alumni' => $alumni]);
}

    public function import()
    {
        return view('import.import');
    }

    public function import_ajax(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'file_barang' => ['required', 'file', 'mimes:xlsx', 'max:1024']
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'message' => 'Validasi gagal',
                'msgField' => $validator->errors()
            ]);
        }

        $file = $request->file('file_barang');
        $reader = IOFactory::createReader('Xlsx');
        $spreadsheet = $reader->load($file->getRealPath());
        $sheet = $spreadsheet->getActiveSheet();
        $data = $sheet->toArray(null, true, true, true);

        $insert = [];
        foreach ($data as $i => $row) {
            if ($i == 1) continue; // skip header
            $insert[] = [
                'nim' => $row['A'] ?? null,
                'nama_alumni' => $row['B'] ?? null,
                'prodi' => $row['C'] ?? null,
                'no_hp' => $row['D'] ?? null,
                'email' => $row['E'] ?? null,
                'tgl_lulus' => $row['F'] ?? null,
                'tahun_lulus' => $row['G'] ?? null,
            ];
        }

        if (!empty($insert)) {
            Alumni::insertOrIgnore($insert);
            return response()->json([
                'status' => true,
                'message' => 'Data berhasil diimport'
            ]);
        }

        return response()->json([
            'status' => false,
            'message' => 'Tidak ada data yang diimport'
        ]);
    }

    public function export_excel()
    {
        $alumni = Alumni::orderBy('prodi')->get();
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();

        // Header
        $sheet->fromArray([
            ['No', 'NIM', 'Nama Alumni', 'Prodi', 'No HP', 'Email']
        ], null, 'A1');

        $row = 2;
        $no = 1;
        foreach ($alumni as $item) {
            $sheet->fromArray([
                $no++, $item->nim, $item->nama_alumni, $item->prodi, $item->no_hp, $item->email
            ], null, 'A' . $row++);
        }

        foreach (range('A', 'F') as $col) {
            $sheet->getColumnDimension($col)->setAutoSize(true);
        }

        $filename = 'Data Alumni ' . now()->format('Y-m-d H-i-s') . '.xlsx';
        $writer = IOFactory::createWriter($spreadsheet, 'Xlsx');

        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header("Content-Disposition: attachment;filename=\"$filename\"");
        header('Cache-Control: max-age=0');

        $writer->save('php://output');
        exit;
    }
}
