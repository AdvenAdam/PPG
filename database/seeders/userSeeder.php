<?php

namespace Database\Seeders;

use App\Models\Desa;
use App\Models\Kelompok;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class userSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $kelompok = Kelompok::all();
        foreach ($kelompok as $k) {
            DB::table('users')->insert([
                [
                    'nama' => $k->nama,
                    'email' => Str::slug($k->nama) . '-kel' . '@boybar.com',
                    'password' => Hash::make('password123'), // Pastikan untuk mengenkripsi password
                    'id_daerah' => 1, // Sesuaikan dengan data yang ada
                    'id_desa' => $k->id_desa,   // Sesuaikan dengan data yang ada
                    'id_kelompok' => $k->id, // Sesuaikan dengan data yang ada
                    'jabatan' => 'kelompok',
                    'foto' => 'logo.png',
                    'created_at' => now(),
                    'updated_at' => now(),
                ],
            ]);
        }
        $desa = Desa::all();
        foreach ($desa as $d) {
            DB::table('users')->insert([
                [
                    'nama' => $d->nama,
                    'email' => Str::slug($d->nama) . '-desa' . '@boybar.com',
                    'password' => Hash::make('password123'), // Pastikan untuk mengenkripsi password
                    'id_daerah' => 1, // Sesuaikan dengan data yang ada
                    'id_desa' => $d->id,   // Sesuaikan dengan data yang ada
                    'id_kelompok' => 1, // Sesuaikan dengan data yang ada
                    'jabatan' => 'desa',
                    'foto' => 'logo.png',
                    'created_at' => now(),
                    'updated_at' => now(),
                ],
            ]);
        }
        DB::table('users')->insert([
            [
                'nama' => 'Administrator',
                'email' => 'admin@gmail.com',
                'password' => Hash::make('password123'), // Pastikan untuk mengenkripsi password
                'id_daerah' => 1, // Sesuaikan dengan data yang ada
                'id_desa' => 1,   // Sesuaikan dengan data yang ada
                'id_kelompok' => 1, // Sesuaikan dengan data yang ada
                'jabatan' => 'daerah',
                'foto' => 'logo.png',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}
