<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class GenerusSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $faker = \Faker\Factory::create('id_ID');
        // Menyisipkan data untuk tabel Generus
        for ($i = 0; $i < 50; $i++) {
            $idDesa = $faker->randomElement(DB::table('desa')->pluck('id')->toArray());
            $gender = $faker->randomElement(['L', 'P']);
            $birthDate = $faker->date();
            $age = \Carbon\Carbon::parse($birthDate)->age;
            switch ($age) {
                case $age < 5:
                    $kelas = DB::table('kelas')->where('nama', 'Balita')->value('id');
                    break;
                case $age < 12:
                    $kelas = DB::table('kelas')->where('nama', 'Caberawit')->value('id');
                    break;
                case $age < 15:
                    $kelas = DB::table('kelas')->where('nama', 'Pra Remaja')->value('id');
                    break;
                case $age < 19:
                    $kelas = DB::table('kelas')->where('nama', 'Remaja')->value('id');
                    break;
                default:
                    $kelas = DB::table('kelas')->where('nama', 'Usia Mandiri')->value('id');
                    break;
            }
            DB::table('generus')->insert([
                [
                    'nama' => $gender === 'L' ? $faker->firstNameMale . ' ' . $faker->lastNameMale : $faker->firstNameFemale . ' ' . $faker->lastNameFemale,
                    'tgllahir' => $birthDate,
                    'gender' => $gender,
                    'id_desa' => $idDesa,
                    'id_kelompok' => $faker->randomElement(DB::table('kelompok')->where('id_desa', $idDesa)->pluck('id')->toArray()),
                    'id_kelas' => $kelas,
                    'pendidikan_terakhir' => $faker->randomElement(['SD', 'SMP', 'SMA', 'D3', 'S1', 'S2', 'S3']),
                    'status_pekerjaan' => $faker->randomElement(['PELAJAR/MAHASISWA', 'BEKERJA', 'BELUM BEKERJA', 'MONDOK']),
                    'detail_pekerjaan' => '',
                    'nama_ibu' => $faker->firstNameFemale,
                    'hum_ibu' => $faker->boolean,
                    'nama_bapak' => $faker->firstNameMale,
                    'hum_bapak' => $faker->boolean,
                    'status' => 'aktif',
                    'keterangan' => substr($faker->text, 0, 50),
                    'foto_url' => 'user.png',
                ],
            ]);
        }
    }
}
