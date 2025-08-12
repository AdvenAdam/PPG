<?php

namespace App\Exports;

use App\Models\Generus;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithColumnFormatting;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithTitle;
use PhpOffice\PhpSpreadsheet\Style\NumberFormat as StyleNumberFormat;

class GenerusPerDesaSheet implements FromQuery, WithHeadings, WithMapping, ShouldAutoSize, WithColumnFormatting, WithTitle
{
    /**
     * @return \Illuminate\Support\Collection
     */

    protected $idDesa;
    protected $desa;
    protected $role;
    protected $rowNumber = 0;

    public function __construct($idDesa = null, $desa = null)
    {
        $this->idDesa = $idDesa;
        $this->desa = $desa;
        $this->role = auth()->user()->jabatan;
    }

    public function query()
    {
        $query = Generus::select('generus.*', 'kelas.nama as kelas', 'kelompok.nama as kelompok', 'desa.nama as desa')
            ->join('kelas', 'generus.id_kelas', '=', 'kelas.id')
            ->join('kelompok', 'generus.id_kelompok', '=', 'kelompok.id')
            ->join('desa', 'generus.id_desa', '=', 'desa.id')
            ->where('generus.id_desa', '=', $this->idDesa);

        if ($this->role === 'kelompok') {
            $query->where('generus.id_kelompok', auth()->user()->id_kelompok);
        }

        return $query->orderBy('kelompok', 'asc')->latest();
    }

    function map($generus): array
    {
        $this->rowNumber++;
        return [
            $this->rowNumber,
            $generus->nama,
            \Carbon\Carbon::parse($generus->tgllahir)->format('d/m/Y'),
            $generus->gender,
            $generus->desa,
            $generus->kelompok,
            $generus->kelas,
            $generus->pendidikan_terakhir,
            $generus->status_pekerjaan,
            $generus->detail_pekerjaan,
            $generus->nama_ibu,
            $generus->hum_ibu == 1 ? 'Ya' : 'Tidak',
            $generus->nama_bapak,
            $generus->hum_bapak == 1 ? 'Ya' : 'Tidak',
            $generus->status,
            $generus->keterangan,
            $generus->mubalight == 1 ? 'Pernah' : 'Tidak Pernah',
        ];
    }

    public function headings(): array
    {
        return [
            'No',
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
            'status',
            'Keterangan',
            'Mubalight/Mubalighot',
        ];
    }
    public function columnFormats(): array
    {
        return [
            'C' => StyleNumberFormat::FORMAT_DATE_DDMMYYYY,
        ];
    }
    public function title(): string
    {
        return 'Generus ' . $this->desa;
    }
}
