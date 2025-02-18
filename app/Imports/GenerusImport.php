<?php

namespace App\Imports;

use App\Exports\GenerusErrorImport;
use App\Models\Desa;
use App\Models\Generus;
use App\Models\kelas;
use App\Models\Kelompok;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\Rule;
use Maatwebsite\Excel\Concerns\Importable;
use Maatwebsite\Excel\Concerns\SkipsEmptyRows;
use Maatwebsite\Excel\Concerns\SkipsErrors;
use Maatwebsite\Excel\Concerns\SkipsFailures;
use Maatwebsite\Excel\Concerns\SkipsOnError;
use Maatwebsite\Excel\Concerns\SkipsOnFailure;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithStartRow;
use Maatwebsite\Excel\Concerns\WithValidation;
use Maatwebsite\Excel\Facades\Excel;
use Maatwebsite\Excel\Validators\Failure;
use Maatwebsite\Excel\Exports\CollectionExport;

class GenerusImport implements ToModel, WithValidation, SkipsEmptyRows, SkipsOnFailure, WithStartRow, SkipsOnError
{
    /**
     * @param array $row
     *
     * @return \Illuminate\Database\Eloquent\Model|null
     */
    use Importable, SkipsFailures, SkipsErrors;

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
        $formatedDate = \PhpOffice\PhpSpreadsheet\Shared\Date::excelToDateTimeObject($row[1]);
        $selectedKelompok = explode(' - ', $row[4])[0];
        return new Generus([
            'nama' => $row[0],
            'tgllahir' => $formatedDate,
            'gender' => $row[2],
            'id_desa' => optional($this->desa->firstWhere('nama', $row[3]))->id,
            'id_kelompok' => optional($this->kelompok->firstWhere('nama', $selectedKelompok))->id,
            'id_kelas' => optional($this->kelas->firstWhere('nama', $row[5]))->id,
            'pendidikan_terakhir' => $row[6],
            'status_pekerjaan' => $row[7],
            'detail_pekerjaan' => $row[8],
            'nama_ibu' => $row[9],
            'hum_ibu' => $row[10] == 'Ya' ? 1 : 0,
            'nama_bapak' => $row[11],
            'hum_bapak' => $row[12] == 'Ya' ? 1 : 0,
            'status' => 'aktif',
            'keterangan' => $row[13],
            'created_at' => now(),
            'updated_at' => now(),
            'mubalight' => $row[14],
        ]);
    }

    public function startRow(): int
    {
        return 2;
    }

    // FIXME : this validate using same as store function in laravel
    function rules(): array
    {
        return [
            '*.tgllahir' => function ($attribute, $value, $onFailure) {
                try {
                } catch (\Exception $e) {
                    $onFailure("Tanggal lahir harus berupa format d-m-Y");
                }
            },
            '*.id_desa' => function ($attribute, $value, $onFailure) {
                $desa = $this->desa->firstWhere('nama', $value);
                if (!$desa) {
                    $onFailure("Desa with name $value does not exist");
                }
            },
            '*.id_kelompok' => function ($attribute, $value, $onFailure) {
                $desaId = $this->desa->firstWhere('nama', $attribute['Desa'])->id ?? null;
                $kelompok = $this->kelompok->firstWhere('nama', $value)->where('id_desa', $desaId);
                if (!$kelompok) {
                    $onFailure("Kelompok with name $value does not exist");
                }
            },
            '*.id_kelas' => function ($attribute, $value, $onFailure) {
                $kelas = $this->kelas->firstWhere('nama', $value);
                if (!$kelas) {
                    $onFailure("Kelas with name $value does not exist");
                } else {
                    $value = $kelas->id;
                }
            },
        ];
    }
    public function onFailure(Failure ...$failures)
    {
        $data = [];
        foreach ($failures as $failure) {
            $data[] = [
                'row' => $failure->row(),
                'attribute' => $failure->attribute(),
                'errors' => $failure->errors(),
                'values' => $failure->values(),
            ];
        }
        Excel::download(new GenerusErrorImport(collect($data)), 'generus-error-import.xlsx');
    }
    /**
     * @param \Throwable $e
     */
    public function onError(\Throwable $e)
    {
        // Log the exception for debugging
        Log::error('Exception occurred during import: ' . $e->getMessage());

        // Provide feedback to the user
        toast('An error occurred during the import process.', 'error');
    }
}
