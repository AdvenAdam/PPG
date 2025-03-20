<?php

namespace App\Imports;

use App\Exports\GenerusErrorImport;
use App\Models\Desa;
use App\Models\Generus;
use App\Models\kelas;
use App\Models\Kelompok;
use Illuminate\Support\Facades\Log;
use Maatwebsite\Excel\Concerns\SkipsEmptyRows;
use Maatwebsite\Excel\Concerns\SkipsErrors;
use Maatwebsite\Excel\Concerns\SkipsFailures;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithStartRow;


class GenerusImport implements ToModel, WithStartRow, SkipsEmptyRows
{
    /**
     * @param array $row
     *
     * @return \Illuminate\Database\Eloquent\Model|null
     */
    use  SkipsFailures, SkipsErrors;

    protected $desa;
    protected $kelompok;
    protected $kelas;

    public function __construct()
    {
        $this->desa = Desa::all();
        $this->kelompok = Kelompok::all();
        $this->kelas = kelas::all();
    }

    public function model(array $row)
    {
        try {
            // Skip rows with missing fields (expecting 15 columns: 0 to 14)
            if (count($row) < 15) {
                // Optionally log or handle the incomplete row
                return null;
            }

            // Handle 'Kelompok' column (index 4)
            $selectedKelompok = is_string($row[4]) && strpos($row[4], '-') !== false
                ? explode(' - ', $row[4])[0]
                : $row[4];

            // Safe fetch kelas ID
            $kelas = kelas::firstWhere('nama', $row[5]);
            if (!$kelas) {
                throw new \Exception("Kelas not found: " . $row[5]);
            }

            // Safe fetch desa ID
            $desa = $this->desa->firstWhere('nama', $row[3]);
            if (!$desa) {
                throw new \Exception("Desa not found: " . $row[3]);
            }

            // Safe fetch kelompok ID
            $kelompok = $this->kelompok->firstWhere('nama', $selectedKelompok);
            if (!$kelompok) {
                throw new \Exception("Kelompok not found: " . $selectedKelompok);
            }

            return new Generus([
                'nama' => $row[0],
                'tgllahir' => \PhpOffice\PhpSpreadsheet\Shared\Date::excelToDateTimeObject($row[1])->format('Y-m-d'),
                'gender' => $row[2],
                'id_desa' => $desa->id,
                'id_kelompok' => $kelompok->id,
                'id_kelas' => $kelas->id,
                'pendidikan_terakhir' => $row[6],
                'status_pekerjaan' => $row[7],
                'detail_pekerjaan' => $row[8],
                'nama_ibu' => $row[9],
                'hum_ibu' => $row[10] === 'Ya' ? 1 : 0,
                'nama_bapak' => $row[11],
                'hum_bapak' => $row[12] === 'Ya' ? 1 : 0,
                'status' => 'aktif',
                'keterangan' => $row[13],
                'created_at' => now(),
                'updated_at' => now(),
                'mubalight' => $row[14],
            ]);
        } catch (\Throwable $th) {
            // Optionally log the row causing the problem
            Log::error("Row error: " . json_encode($row) . " | Error: " . $th->getMessage());
            // You can also rethrow if you want to stop the import
            throw $th;
        }
    }


    public function startRow(): int
    {
        return 2;
    }
}
