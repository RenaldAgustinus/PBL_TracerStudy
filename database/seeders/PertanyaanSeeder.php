<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PertanyaanSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('pertanyaan')->insert([
            [
                'isi_pertanyaan' => 'Bagaimana pengalaman Anda selama kuliah?',
                'kategori' => 'tracer',
                'created_by' => 1, // Sesuaikan dengan ID admin yang ada
            ],
            [
                'isi_pertanyaan' => 'Apakah lulusan kami memenuhi kebutuhan perusahaan Anda?',
                'kategori' => 'pengguna_lulusan',
                'created_by' => 1, // Sesuaikan dengan ID admin yang ada
            ],
            [
                'isi_pertanyaan' => 'Apa saran Anda untuk meningkatkan kualitas lulusan?',
                'kategori' => 'umum',
                'created_by' => 1, // Sesuaikan dengan ID admin yang ada
            ],
        ]);
    }
}