<?php

namespace App\Exports;

use App\Models\Desa;
use App\Models\kelas;
use App\Models\Kelompok;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithColumnFormatting;
use Maatwebsite\Excel\Concerns\WithColumnWidths;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Events\AfterSheet;
use PhpOffice\PhpSpreadsheet\Cell\Coordinate;
use PhpOffice\PhpSpreadsheet\Cell\DataValidation;
use PhpOffice\PhpSpreadsheet\Style\NumberFormat;

class GenerusTemplate implements FromCollection, WithHeadings, WithEvents, ShouldAutoSize, WithColumnFormatting, WithColumnWidths
{
    protected  $users;
    protected  $selects;
    protected  $row_count;
    protected  $column_count;

    public function __construct()
    {
        $role =  auth()->user()->jabatan;
        $desa = Desa::pluck('nama')->toArray();
        if ($role === 'desa' || $role === 'kelompok') {
            $desa = Desa::where('id', '=', auth()->user()->id_desa)->pluck('nama')->toArray();
        }

        $kelompokQuery = Kelompok::join('desa', 'desa.id', '=', 'kelompok.id_desa')
            ->select('kelompok.nama as kelompok', 'desa.nama as desa')
            ->orderBy('desa.id');
        if ($role === 'desa') {
            $kelompokQuery->where('desa.id', '=', auth()->user()->id_desa);
        } elseif ($role === 'kelompok') {
            $kelompokQuery->where('kelompok.id', '=', auth()->user()->id_kelompok);
        }

        $kelompok = $kelompokQuery->get()
            ->map(function ($item) {
                return $item->desa . "-" . $item->kelompok;
            })
            ->unique()
            ->values()
            ->toArray();
        // dd($kelompok);
        $kelas = kelas::pluck('nama')->toArray();
        $pendidikanTerakhir = ['SD', 'SMP', 'SMA', 'D3', 'S1', 'S2', 'S3'];
        $statusPekerjaan = ['PELAJAR/MAHASISWA', 'BEKERJA', 'BELUM BEKERJA', 'MONDOK'];
        $statusMubalight = ['Pernah', 'Tidak Pernah'];
        $hum = ['Tidak', 'Ya'];
        $gender = ['L', 'P'];

        $selects = [  //selects should have column_name and options
            ['columns_name' => 'C', 'options' => $gender],
            ['columns_name' => 'D', 'options' => $desa],
            ['columns_name' => 'E', 'options' => $kelompok],
            ['columns_name' => 'F', 'options' => $kelas],
            ['columns_name' => 'G', 'options' => $pendidikanTerakhir],
            ['columns_name' => 'H', 'options' => $statusPekerjaan],
            ['columns_name' => 'O', 'options' => $statusMubalight],
            ['columns_name' => 'K', 'options' => $hum],
            ['columns_name' => 'M', 'options' => $hum],
        ];
        $this->selects = $selects;
        $this->row_count = 99; //number of rows that will have the dropdown
    }

    public function collection()
    {
        return collect([]);
    }


    public function headings(): array
    {
        return [
            'Nama',
            'Tanggal Lahir',
            'Jenis Kelamin',
            'Desa',
            'Kelompok',
            'Kelas',
            'Pendidikan Terakhir',
            'Status Pekerjaan',
            'Detail Pekerjaan',
            'Nama Ibu',
            'Hum Ibu',
            'Nama Bapak',
            'Hum Bapak',
            'Keterangan',
            'Mubalight/Mubalighot',
        ];
    }

    public function columnFormats(): array
    {
        return [
            'B' => NumberFormat::FORMAT_DATE_DDMMYYYY,
        ];
    }
    public function columnWidths(): array
    {
        return [
            'A' => 25,
            'D' => 10,
            'E' => 25,
            'F' => 15,
        ];
    }

    /**
     * @return array
     */
    public function registerEvents(): array
    {
        return [
            // handle by a closure.
            AfterSheet::class => function (AfterSheet $event) {
                $row_count = $this->row_count;
                foreach ($this->selects as $select) {
                    $drop_column = $select['columns_name'];
                    $options = $select['options'];

                    // set dropdown list for first data row
                    $validation = $event->sheet->getCell("{$drop_column}2")->getDataValidation();
                    $validation->setType(DataValidation::TYPE_LIST);
                    $validation->setErrorStyle(DataValidation::STYLE_INFORMATION);
                    $validation->setAllowBlank(false);
                    $validation->setShowInputMessage(true);
                    $validation->setShowErrorMessage(true);
                    $validation->setShowDropDown(true);
                    $validation->setErrorTitle('Input error');
                    $validation->setError('Value is not in list.');
                    $validation->setPromptTitle('Pick from list');
                    $validation->setPrompt('Please pick a value from the drop-down list.');
                    $validation->setFormula1(sprintf('"%s"', implode(',', $options)));
                    // clone validation to remaining rows
                    for ($i = 2; $i <= $row_count; $i++) {
                        $event->sheet->getCell("{$drop_column}{$i}")->setDataValidation(clone $validation);
                    }
                }
            },
        ];
    }
}
