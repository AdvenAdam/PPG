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
        // desa tengah
        DB::table('kelompok')->insert([
            [
                'nama' => 'BENDO',
                'id_desa' => 4,
                'alamat' => 'BENDO, Sambi',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nama' => 'KEBONDUREN',
                'id_desa' => 4,
                'alamat' => 'KEBONDUREN, Sambi',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nama' => 'JAYAN',
                'id_desa' => 4,
                'alamat' => 'JAYAN, Sambi',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nama' => 'JATIREJO',
                'id_desa' => 4,
                'alamat' => 'JATIREJO, Sambi',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nama' => 'WATES TEGALGIRI',
                'id_desa' => 4,
                'alamat' => 'WATES TEGALGIRI, Sambi',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nama' => 'JATISARI BARAT',
                'id_desa' => 4,
                'alamat' => 'JATISARI BARAT, Sambi',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nama' => 'JATISARI SELATAN',
                'id_desa' => 4,
                'alamat' => 'JATISARI SELATAN, Sambi',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nama' => 'JATISARI TENGAH',
                'id_desa' => 4,
                'alamat' => 'JATISARI TENGAH, Sambi',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nama' => 'JATISARI UTARA',
                'id_desa' => 4,
                'alamat' => 'JATISARI UTARA, Sambi',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
        // desa mangu
        DB::table('kelompok')->insert([
            [
                'nama' => 'MANGU',
                'id_desa' => 2,
                'alamat' => 'MANGU, Ngemplak',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'nama' => 'TURUNAN',
                'id_desa' => 2,
                'alamat' => 'TURUNAN, Ngemplak',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'nama' => 'GUNUNG PELEM',
                'id_desa' => 2,
                'alamat' => 'GUNUNG PELEM, Ngemplak',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'nama' => 'SEMPOL',
                'id_desa' => 2,
                'alamat' => 'SEMPOL, Ngemplak',
                'created_at' => now(),
                'updated_at' => now()
            ],
        ]);
        // desa sambi
        DB::table('kelompok')->insert([
            [
                'nama' => 'BENGLE',
                'id_desa' => 1,
                'alamat' => 'BENGLE, Sambi',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'nama' => 'KARANGGEDE',
                'id_desa' => 1,
                'alamat' => 'KARANGGEDE, Sambi',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'nama' => 'BLUMBANG',
                'id_desa' => 1,
                'alamat' => 'BLUMBANG, Sambi',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'nama' => 'GONDANGLEGI',
                'id_desa' => 1,
                'alamat' => 'GONDANGLEGI, Sambi',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'nama' => 'SIMO 1',
                'id_desa' => 1,
                'alamat' => 'SIMO 1, Sambi',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'nama' => 'SIMO 2',
                'id_desa' => 1,
                'alamat' => 'SIMO 2, Sambi',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'nama' => 'GEMPOLSARI',
                'id_desa' => 1,
                'alamat' => 'GEMPOLSARI, Sambi',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'nama' => 'KLECO',
                'id_desa' => 1,
                'alamat' => 'KLECO, Sambi',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'nama' => 'LAOSAN',
                'id_desa' => 1,
                'alamat' => 'LAOSAN, Sambi',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'nama' => 'PERENGAN',
                'id_desa' => 1,
                'alamat' => 'PERENGAN, Sambi',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'nama' => 'GUMUKREJO',
                'id_desa' => 1,
                'alamat' => 'GUMUKREJO, Sambi',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'nama' => 'GESIKAN',
                'id_desa' => 1,
                'alamat' => 'GESIKAN, Sambi',
                'created_at' => now(),
                'updated_at' => now()
            ],
        ]);
        // desa tengah
        DB::table('kelompok')->insert([
            [
                'nama' => 'WATES',
                'id_desa' => 5,
                'alamat' => 'WATES, Ngemplak',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'nama' => 'SOBOKERTO UTARA',
                'id_desa' => 5,
                'alamat' => 'SOBOKERTO UTARA, Ngemplak',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'nama' => 'SOBOKERTO TENGAH',
                'id_desa' => 5,
                'alamat' => 'SOBOKERTO TENGAH, Ngemplak',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'nama' => 'SOBOKERTO SELATAN',
                'id_desa' => 5,
                'alamat' => 'SOBOKERTO SELATAN, Ngemplak',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'nama' => 'GELARAN UTARA',
                'id_desa' => 5,
                'alamat' => 'GELARAN UTARA, Ngemplak',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'nama' => 'GELARAN SELATAN',
                'id_desa' => 5,
                'alamat' => 'GELARAN SELATAN, Ngemplak',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'nama' => 'POLO',
                'id_desa' => 5,
                'alamat' => 'POLO, Ngemplak',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'nama' => 'PONDOK',
                'id_desa' => 5,
                'alamat' => 'PONDOK, Ngemplak',
                'created_at' => now(),
                'updated_at' => now()
            ],
        ]);
        // desa timur
        DB::table('kelompok')->insert([
            [
                'nama' => 'PUNDONG',
                'id_desa' => 3,
                'alamat' => 'PUNDONG, Ngemplak',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'nama' => 'MANDUNGAN SELATAN',
                'id_desa' => 3,
                'alamat' => 'MANDUNGAN SELATAN, Ngemplak',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'nama' => 'MANDUNGAN UTARA',
                'id_desa' => 3,
                'alamat' => 'MANDUNGAN UTARA, Ngemplak',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'nama' => 'MANDUNGAN BARAT',
                'id_desa' => 3,
                'alamat' => 'MANDUNGAN BARAT, Ngemplak',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'nama' => 'MANDUNGAN TIMUR',
                'id_desa' => 3,
                'alamat' => 'MANDUNGAN TIMUR, Ngemplak',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'nama' => 'NGABLAK SELATAN',
                'id_desa' => 3,
                'alamat' => 'NGABLAK SELATAN, Ngemplak',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'nama' => 'NGABLAK UTARA',
                'id_desa' => 3,
                'alamat' => 'NGABLAK UTARA, Ngemplak',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'nama' => 'GRENJENG BARAT',
                'id_desa' => 3,
                'alamat' => 'GRENJENG BARAT, Ngemplak',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'nama' => 'GRENJENG TIMUR',
                'id_desa' => 3,
                'alamat' => 'GRENJENG TIMUR, Ngemplak',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'nama' => 'GUNUNG LONDO',
                'id_desa' => 3,
                'alamat' => 'GUNUNG LONDO, Ngemplak',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'nama' => 'GUNUNGAN',
                'id_desa' => 3,
                'alamat' => 'GUNUNGAN, Ngemplak',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'nama' => 'GEMPOL NGABLAK',
                'id_desa' => 3,
                'alamat' => 'GEMPOL NGABLAK, Ngemplak',
                'created_at' => now(),
                'updated_at' => now()
            ],

        ]);
    }
}
