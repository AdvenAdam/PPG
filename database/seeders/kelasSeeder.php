<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class kelasSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('kelas')->insert([
            ['nama' => 'Balita'],
            ['nama' => 'Caberawit'],
            ['nama' => 'Pra Remaja'],
            ['nama' => 'Remaja'],
            ['nama' => 'Usia Mandiri'],
        ]);
    }
}
