<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class AdminSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('admin')->insert([
            [
                'username' => 'admin1',
                'email' => 'maharaniwr246@gmail.com',
                'password' => Hash::make('password123'), // Gunakan Hash untuk mengenkripsi password
                'nama' => 'Admin Satu',
            ],
            [
                'username' => 'admin2',
                'email' => 'maharaniwr246@gmail.com',
                'password' => Hash::make('password123'),
                'nama' => 'Admin Dua',
            ],
            [
                'username' => 'admin3',
                'email' => 'maharaniwirawan22@gmail.com',
                'password' => Hash::make('password123'),
                'nama' => 'Admin Tiga',
            ],
        ]);
    }
}