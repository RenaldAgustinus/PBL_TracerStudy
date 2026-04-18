<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Profesi;

class ProfesiSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $profesis = [
            // Infokom
            ['nama_profesi' => 'Software Developer', 'kategori_profesi' => 'Infokom'],
            ['nama_profesi' => 'Web Developer', 'kategori_profesi' => 'Infokom'],
            ['nama_profesi' => 'Mobile Developer', 'kategori_profesi' => 'Infokom'],
            ['nama_profesi' => 'Data Analyst', 'kategori_profesi' => 'Infokom'],
            ['nama_profesi' => 'System Administrator', 'kategori_profesi' => 'Infokom'],
            ['nama_profesi' => 'Network Engineer', 'kategori_profesi' => 'Infokom'],
            ['nama_profesi' => 'Database Administrator', 'kategori_profesi' => 'Infokom'],
            ['nama_profesi' => 'UI/UX Designer', 'kategori_profesi' => 'Infokom'],
            
            // Non Infokom
            ['nama_profesi' => 'Manager', 'kategori_profesi' => 'Non Infokom'],
            ['nama_profesi' => 'Marketing', 'kategori_profesi' => 'Non Infokom'],
            ['nama_profesi' => 'Sales', 'kategori_profesi' => 'Non Infokom'],
            ['nama_profesi' => 'Human Resources', 'kategori_profesi' => 'Non Infokom'],
            ['nama_profesi' => 'Finance', 'kategori_profesi' => 'Non Infokom'],
            ['nama_profesi' => 'Operations', 'kategori_profesi' => 'Non Infokom'],
            ['nama_profesi' => 'Entrepreneur', 'kategori_profesi' => 'Non Infokom'],
        ];

        foreach ($profesis as $profesi) {
            Profesi::create($profesi);
        }
    }
}
