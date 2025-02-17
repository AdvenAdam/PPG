<?php

namespace App\Imports;

use App\Exports\GenerusErrorImport;
use App\Models\Desa;
use App\Models\Generus;
use App\Models\kelas;
use App\Models\Kelompok;
use Illuminate\Validation\Rule;
use Maatwebsite\Excel\Concerns\Importable;
use Maatwebsite\Excel\Concerns\SkipsEmptyRows;
use Maatwebsite\Excel\Concerns\SkipsErrors;
use Maatwebsite\Excel\Concerns\SkipsFailures;
use Maatwebsite\Excel\Concerns\SkipsOnFailure;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithValidation;
use Maatwebsite\Excel\Facades\Excel;
use Maatwebsite\Excel\Validators\Failure;
use Maatwebsite\Excel\Exports\CollectionExport;

class GenerusImport implements ToModel, WithValidation, SkipsEmptyRows, SkipsOnFailure
{
    /**
     * @param array $row
     *
     * @return \Illuminate\Database\Eloquent\Model|null
     */
    use Importable, SkipsFailures;

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
        return new Generus([
            'nama' => $row['Nama'],
            'tgllahir' => $row['Tanggal Lahir'],
            'gender' => $row['Jenis Kelamin'],
            'id_desa' => $this->desa->firstWhere('nama', $row['Desa'])->id,
            'id_kelompok' => $this->kelompok->firstWhere('nama', $row['Kelompok'])->id,
            'id_kelas' => $this->kelas->firstWhere('nama', $row['Kelas'])->id,
            'pendidikan' => $row['Pendidikan Terakhir'],
            'status_pekerjaan' => $row['Status Pekerjaan'],
            'detail_pekerjaan' => $row['Detail Pekerjaan'],
            'nama_ibu' => $row['Nama Ibu'],
            'hum_ibu' => $row['Hum Ibu'],
            'nama_bapak' => $row['Nama Bapak'],
            'hum_bapak' => $row['Hum Bapak'],
            'status' => $row['status'],
            'keterangan' => $row['Keterangan'],
            'created_at' => now(),
            'updated_at' => now(),
            'mubalight' => $row['Mubalight/Mubalighot'],
        ]);
    }

    // FIXME : this validate using same as store function in laravel
    function rules(): array
    {
        return [

            // Can also use callback validation rules
            '*.tgllahir' => function ($attribute, $value, $onFailure) {
                if (! \DateTime::createFromFormat('d-m-Y', $value)) {
                    $onFailure('Tanggal Lahir is not a valid date');
                } else {
                    $date = \DateTime::createFromFormat('d-m-Y', $value);
                    $value = $date->format('dmy');
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
        Excel::store(new GenerusErrorImport(collect($data)), 'generus-error-import.xlsx', 'public', null, true);
    }
}
