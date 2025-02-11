<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash; // Pastikan ini ada
use Illuminate\Support\Str;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Menyisipkan data untuk tabel daerah
        DB::table('daerah')->insert([
            [
                'nama' => 'Boyolali Barat',
                'alamat' => 'Boyolali, Jawa Tengah',
                'created_at' => now(),
                'updated_at' => now()
            ],
        ]);

        $this->call([
            desaSeeder::class,
            kelompokSeeder::class,
            kelasSeeder::class,
            userSeeder::class
        ]);
    }
}
