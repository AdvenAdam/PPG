<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class userSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('users')->insert([
            [
                'nama' => 'Administrator',
                'email' => 'admin@gmail.com',
                'password' => Hash::make('password123'), // Pastikan untuk mengenkripsi password
                'id_daerah' => 1, // Sesuaikan dengan data yang ada
                'id_desa' => 1,   // Sesuaikan dengan data yang ada
                'id_kelompok' => 1, // Sesuaikan dengan data yang ada
                'jabatan' => 'kelompok',
                'foto' => 'logo.png',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}
