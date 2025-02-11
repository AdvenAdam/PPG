<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class desaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Menyisipkan data untuk tabel desa
        DB::table('desa')->insert([
            [
                'nama' => 'Sambi',
                'alamat' => 'Sambi, Boyolali',
                'id_daerah' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nama' => 'Mangu',
                'alamat' => 'Ngemplak, Boyolali',
                'id_daerah' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nama' => 'Desa Timur',
                'alamat' => 'Nogosari, Boyolali',
                'id_daerah' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nama' => 'Desa Barat',
                'alamat' => 'Sambi/Ngemplak, Boyolali',
                'id_daerah' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nama' => 'Desa Tengah',
                'alamat' => 'Ngemplak, Boyolali',
                'id_daerah' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ]
        ]);
    }
}
