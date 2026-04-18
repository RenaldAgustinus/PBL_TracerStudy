<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Prodi;

class ProdiSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $prodis = [
            ['nama_prodi' => 'Teknik Informatika', 'jurusan' => 'Teknologi Informasi'],
            ['nama_prodi' => 'Sistem Informasi Bisnis', 'jurusan' => 'Teknologi Informasi'],
            ['nama_prodi' => 'Teknik Listrik', 'jurusan' => 'Teknik Elektro'],
        ];

        foreach ($prodis as $prodi) {
            // Gunakan updateOrCreate untuk avoid duplicate
            Prodi::updateOrCreate(
                ['nama_prodi' => $prodi['nama_prodi']], // Cari berdasarkan nama_prodi
                $prodi // Data yang akan di-insert/update
            );
        }
    }
}
