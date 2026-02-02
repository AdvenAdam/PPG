<?php

namespace Database\Seeders;

use App\Models\Absen;
use App\Models\Generus;
use App\Models\kelas;
use App\Models\Kelompok;
use App\Models\Pengajian;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class PengajianSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $faker = \Faker\Factory::create('id_ID');
        // Menyisipkan data untuk tabel Generus
        try {
            DB::beginTransaction();
            $kelompoks = Kelompok::all();
            $kelas = Kelas::all();
            for ($i = 0; $i < 50; $i++) {
                $randKelompok = $faker->randomElement($kelompoks->pluck('id')->toArray());
                $randJumlah = $faker->numberBetween(1, $kelas->count());
                $randKelas = $faker->randomElements($kelas->pluck('id')->toArray(), $randJumlah);
                $randDate = $faker->dateTime();
                $pengajian = Pengajian::create([
                    'id_kelompok' => $randKelompok,
                    'nama' => 'Pengajian ' . '-' . $kelompoks->find($randKelompok)->nama . '-' . $randDate->format('Y-m-d'),
                    'waktu_tanggal_mulai' => $randDate->format('Y-m-d H:i:s'),
                ]);
                foreach ($randKelas as $id_kelas) {
                    $generus = Generus::where('id_kelas', $id_kelas)->where('id_kelompok', $randKelompok)->get();
                    if ($generus->count()) {
                        $absenFormated = $generus->map(function ($gen) {
                            $faker = \Faker\Factory::create('id_ID');
                            return [
                                'id_generus' => $gen->id,
                                'nama' => $gen->nama,
                                'absen' => $faker->randomElement(['alpha', 'hadir', 'sakit', 'izin']),
                            ];
                        })->toArray();
                        $keterangan = [
                            'alpha' => 0,
                            'hadir' => 0,
                            'sakit' => 0,
                            'izin' => 0
                        ];
                        // Count each absen status
                        foreach ($absenFormated as $absen) {
                            $status = $absen['absen'];
                            if (isset($keterangan[$status])) {
                                $keterangan[$status]++;
                            }
                        }
                        Absen::create([
                            'id_pengajian' => $pengajian->id,
                            'absen' => json_encode($absenFormated),
                            'keterangan' => json_encode($keterangan),
                            'id_kelas' => $id_kelas
                        ]);
                    }
                }
            }
            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error($e->getMessage());
        }
    }
}
