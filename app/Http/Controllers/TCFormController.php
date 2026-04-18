<?php

namespace App\Http\Controllers;

use App\Models\Alumni;
use App\Models\Instansi;
use App\Models\Jawaban;
use App\Models\PenggunaLulusan;
use App\Models\Pertanyaan;
use App\Models\Profesi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class TCFormController extends Controller
{
    public function index()
    { // landingpage 
        return view("FormTracerStudy.index");
    }

    public function opsi()
    { //opsi form
        return view("FormTracerStudy.opsiform");
    }


    //Alumni----------------------------------------Alumni----------------------------------Alumni

    public function kusionerA()
    {
        // Ambil semua NIM dari model Alumni
        $nims = Alumni::pluck('nim'); // mengambil data 'nim' saja

        // Kirim data $nims ke view 'tracerstudy'
        return view("FormTracerStudy.tracerstudy", compact('nims'));
    }

    public function getAlumniData($keyword)
    {
        $alumni = Alumni::with('prodi')
            ->where('nim', 'LIKE', '%' . $keyword . '%')
            ->orWhere('nama_alumni', 'LIKE', '%' . $keyword . '%')
            ->first();

        if ($alumni) {
            return response()->json([
                'nama_alumni' => $alumni->nama_alumni,
                'nim' => $alumni->nim,
                'prodi' => $alumni->prodi ? $alumni->prodi->nama_prodi : null,
                'tgl_lulus' => $alumni->tgl_lulus,
            ]);
        }

        return response()->json(null);
    }


    public function create_form(Request $request, $nim)
    {
        // Update data alumni
        $alumni = Alumni::find($nim);
        if (!$alumni) {
            return redirect()->back()->withErrors('Alumni tidak ditemukan.');
        }

        if (!$request->tanggal_kerja_pertama || !$alumni->tgl_lulus) {
            return redirect()->back()->withErrors('Tanggal kerja pertama atau tanggal lulus tidak tersedia.');
        }
        $tanggal_kerja_pertama = Carbon::parse($request->tanggal_kerja_pertama);
        $tanggal_lulus = Carbon::parse($alumni->tgl_lulus);
        $masa_tunggu = $tanggal_lulus->diffInDays($tanggal_kerja_pertama);
        $masa_tunggu = $tanggal_lulus->diffInDays($tanggal_kerja_pertama, false); // false → hasil negatif

        // Cek apakah instansi sudah ada (berdasarkan nama & lokasi)
        $instansi = Instansi::where('nama_instansi', $request->nama_instansi)
            ->where('lokasi_instansi', $request->lokasi_instansi)
            ->first();

        // Jika tidak ada, buat baru
        if (!$instansi) {
            $instansi = Instansi::create([
                'nama_instansi' => $request->nama_instansi,
                'jenis_instansi' => $request->jenis_instansi,
                'skala_instansi' => $request->skala_instansi,
                'lokasi_instansi' => $request->lokasi_instansi,
                'no_hp_instansi' => $request->no_hp_instansi,
            ]);
        }

        // Cek apakah atasan sudah ada (berdasarkan email atasan)
        $atasan = PenggunaLulusan::where('email_atasan', $request->email_atasan)->first();
        // Jika tidak ada, buat baru
        if (!$atasan) {
            PenggunaLulusan::create([
                'nama_atasan' => $request->nama_atasan,
                'jabatan_atasan' => $request->jabatan_atasan,
                'email_atasan' => $request->email_atasan,
                'otp' => $request->otp,
            ]);
        } else {
            $atasan->update([
                'otp' => $request->otp,
            ]);
        }

        $profesi = Profesi::firstOrCreate([
            'kategori_profesi' => $request->kategori_profesi,
            'nama_profesi' => $request->profesi,
        ]);

        $alumni->update([
            'no_hp' => $request->no_hp,
            'email' => $request->email,
            'tahun_masuk' => $request->tahun_masuk,
            'tanggal_kerja_pertama' => $request->tanggal_kerja_pertama,
            'tanggal_mulai_instansi' => $request->tanggal_mulai_instansi,
            'id_instansi' =>  $instansi->id_instansi,
            'id_pengguna_lulusan' =>  $atasan->id_pengguna_lulusan,
            'id_profesi' =>  $profesi->id_profesi,
            'masa_tunggu' =>  $masa_tunggu,
        ]);

        return response()->json([
            'message' => 'Terimakasih, data berhasil disimpan',
            'redirect' => url('/tracerstudy/formopsi'),
        ]);
    }

    //PenggunaLulusan----------------------------------------PenggunaLulusan----------------------------------PenggunaLulusan

    public function surveiPL()
    {
        // Ambil daftar nama atasan unik
        $namaAtasan = PenggunaLulusan::select('nama_atasan')->distinct()->get();
        $pertanyaan = Pertanyaan::all();

        return view("FormTracerStudy.surveiPL", compact('namaAtasan', 'pertanyaan'));
    }


    public function getPL($pl)
    {
        $penggunalulusan = PenggunaLulusan::where('nama_atasan', 'LIKE', '%' . $pl . '%')->first();

        if ($penggunalulusan) {
            return response()->json([
                'jabatan_atasan' => $penggunalulusan->jabatan_atasan,
                'email_atasan' => $penggunalulusan->email_atasan,
            ]);
        }

        return response()->json(null);
    }

    public function create_PL(Request $request)
    {
        DB::beginTransaction();
        try {
            $pengguna = PenggunaLulusan::where('nama_atasan', $request->nama_atasan)->first();
            if (!$pengguna) {
                return response()->json(['error' => 'Data pengguna lulusan tidak ditemukan.'], 404);
            }

            $alumni = Alumni::where('nama_alumni', $request->nama_alumni)->first();
            if (!$alumni) {
                return response()->json(['error' => 'Data alumni tidak ditemukan.'], 404);
            }

            //Cek apakah alumni sudah pernah dinilai sebelumnya
            $existingAnswers = Jawaban::where('nim_alumni', $alumni->nim)->exists();
            if ($existingAnswers) {
                return response()->json([
                    'error' => 'Alumni ini sudah pernah dinilai sebelumnya.'
                ], 409); // 409 = Conflict
            }

            // Ambil semua pertanyaan dengan metodejawaban = 1 (radio)
            $requiredQuestions = Pertanyaan::where('metodejawaban', 1)->pluck('id_pertanyaan');

            // Validasi apakah semua pertanyaan wajib diisi ada dalam input
            foreach ($requiredQuestions as $id) {
                if (!isset($request->jawaban[$id])) {
                    return response()->json([
                        'error' => 'Semua pertanyaan pilihan wajib diisi.'
                    ], 422); // 422 = Unprocessable Entity
                }
            }

            // Simpan jawaban
            foreach ($request->jawaban as $id_pertanyaan => $isi_jawaban) {
                $pertanyaan = Pertanyaan::find($id_pertanyaan);

                if (!$pertanyaan) {
                    continue; // Lewati jika pertanyaan tidak ditemukan
                }

                // Jika metodejawaban 2 (textarea), dan jawaban kosong, lewati
                if ($pertanyaan->metodejawaban == 2 && (is_null($isi_jawaban) || trim($isi_jawaban) === '')) {
                    continue;
                }

                Jawaban::create([
                    'id_pertanyaan'       => $id_pertanyaan,
                    'jawaban'             => $isi_jawaban,
                    'nim_alumni'          => $alumni->nim,
                    'id_pengguna_lulusan' => $pengguna->id_pengguna_lulusan,
                ]);
            }

            DB::commit();

            return response()->json([
                'message' => 'Terimakasih, data berhasil disimpan',
                'redirect' => route('form.opsi')
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'error' => 'Terjadi kesalahan: ' . $e->getMessage()
            ], 500);
        }
    }

    public function otpcheck()
    {
        return view('FormTracerStudy.otpcheck');
    }

    public function otpvalidation(Request $request)
    {
        // Validasi input
        $request->validate([
            'email' => 'required|email',
            'otp' => 'required|string'
        ]);

        $email = $request->input('email');
        $inputOtp = $request->input('otp');

        // Cek data dari tabel berdasarkan email atasan
        $data = PenggunaLulusan::where('email_atasan', $email)->first();

        if (!$data) {
            return redirect()->back()->with('error', 'Email atasan tidak ditemukan.');
        }

        if ($inputOtp === $data->otp) {

            return redirect()->route('form.penggunalulusan')->with('success', 'OTP berhasil diverifikasi.');
        } else {
            return redirect()->back()->with('error', 'Kode OTP salah. Silakan coba lagi.');
        }
    }
}
