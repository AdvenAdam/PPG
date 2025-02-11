<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class kelompokSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Menyisipkan data untuk tabel kelompok
        DB::table('kelompok')->insert([
            [
                'nama' => 'Gumukrejo',
                'id_desa' => 1,
                'alamat' => 'Gumukrejo, Sambi',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'nama' => 'Mangu',
                'id_desa' => 2,
                'alamat' => 'Mangu, Ngemplak',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'nama' => 'Ngablak',
                'id_desa' => 3,
                'alamat' => 'Ngablak, Nogosari',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'nama' => 'Jatisari Tengah',
                'id_desa' => 4,
                'alamat' => 'Sobokerto, Ngemplak',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'nama' => 'Sobokerto Tengah',
                'id_desa' => 5,
                'alamat' => 'Sobokerto, Ngemplak',
                'created_at' => now(),
                'updated_at' => now()
            ],

        ]);
    }
}
