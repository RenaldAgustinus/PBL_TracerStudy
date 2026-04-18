<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Alumni;
use App\Models\Prodi;
use App\Models\Profesi;
use App\Models\Instansi;
use App\Models\PenggunaLulusan;

class AlumniSeeder extends Seeder
{
    public function run(): void
    {
        // Ambil ID prodi yang sudah ada (sesuaikan dengan ProdiSeeder)
        $prodiTI = Prodi::where('nama_prodi', 'Teknik Informatika')->first();
        $prodiSIB = Prodi::where('nama_prodi', 'Sistem Informasi Bisnis')->first();
        $prodiTL = Prodi::where('nama_prodi', 'Teknik Listrik')->first(); // Ganti dari Teknik Elektro ke Teknik Listrik
        
        // Jika prodi belum ada, buat default prodi (ini seharusnya tidak terjadi jika ProdiSeeder jalan dulu)
        if (!$prodiTI) {
            $prodiTI = Prodi::updateOrCreate(
                ['nama_prodi' => 'Teknik Informatika'], 
                ['nama_prodi' => 'Teknik Informatika', 'jurusan' => 'Teknologi Informasi']
            );
        }
        if (!$prodiSIB) {
            $prodiSIB = Prodi::updateOrCreate(
                ['nama_prodi' => 'Sistem Informasi Bisnis'], 
                ['nama_prodi' => 'Sistem Informasi Bisnis', 'jurusan' => 'Teknologi Informasi']
            );
        }
        if (!$prodiTL) {
            $prodiTL = Prodi::updateOrCreate(
                ['nama_prodi' => 'Teknik Listrik'], 
                ['nama_prodi' => 'Teknik Listrik', 'jurusan' => 'Teknik Elektro']
            );
        }

        // Buat data instansi contoh (dengan updateOrCreate untuk avoid duplicate)
        $instansi1 = Instansi::updateOrCreate(
            ['nama_instansi' => 'PT. Tech Solution Indonesia'],
            [
                'nama_instansi' => 'PT. Tech Solution Indonesia',
                'jenis_instansi' => 'Perusahaan Swasta',
                'skala_instansi' => 'Nasional',
                'lokasi_instansi' => 'Jakarta Selatan',
                'no_hp_instansi' => '02112345678'
            ]
        );

        $instansi2 = Instansi::updateOrCreate(
            ['nama_instansi' => 'CV. Digital Creative'],
            [
                'nama_instansi' => 'CV. Digital Creative',
                'jenis_instansi' => 'Perusahaan Swasta',
                'skala_instansi' => 'Wirausaha',
                'lokasi_instansi' => 'Malang',
                'no_hp_instansi' => '034187654321'
            ]
        );

        // Buat data pengguna lulusan (atasan) contoh (dengan updateOrCreate untuk avoid duplicate)
        $atasan1 = PenggunaLulusan::updateOrCreate(
            ['email_atasan' => 'budi.santoso@techsolution.co.id'],
            [
                'nama_atasan' => 'Budi Santoso',
                'jabatan_atasan' => 'Senior Manager IT',
                'email_atasan' => 'budi.santoso@techsolution.co.id'
            ]
        );

        $atasan2 = PenggunaLulusan::updateOrCreate(
            ['email_atasan' => 'sari.dewi@digitalcreative.co.id'],
            [
                'nama_atasan' => 'Sari Dewi',
                'jabatan_atasan' => 'Lead Developer',
                'email_atasan' => 'sari.dewi@digitalcreative.co.id'
            ]
        );

        // Ambil profesi yang sudah ada
        $profesiDev = Profesi::where('nama_profesi', 'Software Developer')->first();
        $profesiWeb = Profesi::where('nama_profesi', 'Web Developer')->first();
        $profesiData = Profesi::where('nama_profesi', 'Data Analyst')->first();

        // Insert data alumni dengan updateOrCreate untuk avoid duplicate
        Alumni::updateOrCreate(
            ['nim' => '20230001'],
            [
                'nim' => '20230001',
                'nama_alumni' => 'Sufyan',
                'id_prodi' => $prodiTI->id_prodi,
                'no_hp' => '081234567890',
                'email' => 'sufyan@example.com',
                'tahun_masuk' => 2023,
                'tgl_lulus' => '2027-06-15',
                'tanggal_kerja_pertama' => '2027-08-01',
                'tanggal_mulai_instansi' => '2027-08-01',
                'masa_tunggu' => 2, // 2 bulan
                'id_profesi' => $profesiDev ? $profesiDev->id_profesi : null,
                'id_pengguna_lulusan' => $atasan1->id_pengguna_lulusan,
                'id_instansi' => $instansi1->id_instansi,
            ]
        );

        Alumni::updateOrCreate(
            ['nim' => '20230002'],
            [
                'nim' => '20230002',
                'nama_alumni' => 'Renald',
                'id_prodi' => $prodiSIB->id_prodi,
                'no_hp' => '081234567891',
                'email' => 'renald@example.com',
                'tahun_masuk' => 2023,
                'tgl_lulus' => '2027-06-15',
                'tanggal_kerja_pertama' => '2027-07-15',
                'tanggal_mulai_instansi' => '2027-07-15',
                'masa_tunggu' => 1, // 1 bulan
                'id_profesi' => $profesiWeb ? $profesiWeb->id_profesi : null,
                'id_pengguna_lulusan' => $atasan2->id_pengguna_lulusan,
                'id_instansi' => $instansi2->id_instansi,
            ]
        );

        Alumni::updateOrCreate(
            ['nim' => '20230003'],
            [
                'nim' => '20230003',
                'nama_alumni' => 'Daffa',
                'id_prodi' => $prodiTL->id_prodi, // Gunakan Teknik Listrik yang ada
                'no_hp' => '081234567892',
                'email' => 'daffa@example.com',
                'tahun_masuk' => 2023,
                'tgl_lulus' => '2027-06-15',
                'tanggal_kerja_pertama' => null, // Belum bekerja
                'tanggal_mulai_instansi' => null,
                'masa_tunggu' => null,
                'id_profesi' => null, // Belum ada profesi
                'id_pengguna_lulusan' => null,
                'id_instansi' => null,
            ]
        );

        Alumni::updateOrCreate(
            ['nim' => '20230004'],
            [
                'nim' => '20230004',
                'nama_alumni' => 'Maharani',
                'id_prodi' => $prodiTI->id_prodi,
                'no_hp' => '081234567893',
                'email' => 'maharani@example.com',
                'tahun_masuk' => 2023,
                'tgl_lulus' => '2027-06-15',
                'tanggal_kerja_pertama' => '2027-09-01',
                'tanggal_mulai_instansi' => '2027-09-01',
                'masa_tunggu' => 3, // 3 bulan
                'id_profesi' => $profesiData ? $profesiData->id_profesi : null,
                'id_pengguna_lulusan' => null, // Tidak ada data atasan
                'id_instansi' => $instansi1->id_instansi,
            ]
        );

        Alumni::updateOrCreate(
            ['nim' => '20230005'],
            [
                'nim' => '20230005',
                'nama_alumni' => 'Ahmad Rizki',
                'id_prodi' => $prodiTI->id_prodi,
                'no_hp' => '081234567894',
                'email' => 'ahmad.rizki@example.com',
                'tahun_masuk' => 2023,
                'tgl_lulus' => '2027-06-15',
                'tanggal_kerja_pertama' => null,
                'tanggal_mulai_instansi' => null,
                'masa_tunggu' => null,
                'id_profesi' => null,
                'id_pengguna_lulusan' => null,
                'id_instansi' => null,
            ]
        );
    }
}