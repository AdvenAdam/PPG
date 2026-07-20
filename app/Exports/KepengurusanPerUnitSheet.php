<?php

namespace App\Exports;

use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithTitle;

class KepengurusanPerUnitSheet implements FromCollection, WithHeadings, WithMapping, ShouldAutoSize, WithTitle
{
    protected string $tingkat;
    protected string $namaUnit;
    protected ?int $daerahId;
    protected ?int $desaId;
    protected ?int $kelompokId;
    protected int $rowNumber = 0;

    public function __construct(
        string $tingkat,
        string $namaUnit,
        ?int $daerahId,
        ?int $desaId,
        ?int $kelompokId
    ) {
        $this->tingkat    = $tingkat;
        $this->namaUnit   = $namaUnit;
        $this->daerahId   = $daerahId;
        $this->desaId     = $desaId;
        $this->kelompokId = $kelompokId;
    }

    public function title(): string
    {
        // Sheet names max 31 chars
        $prefix = match ($this->tingkat) {
            'daerah'   => 'D - ',
            'desa'     => 'Ds - ',
            'kelompok' => 'K - ',
            default    => '',
        };
        return mb_substr($prefix . $this->namaUnit, 0, 31);
    }

    public function collection()
    {
        $query = DB::table('kepengurusan')
            ->leftJoin('jabatan', 'kepengurusan.jabatan_id', '=', 'jabatan.id')
            ->select('kepengurusan.nama', 'kepengurusan.no_hp', 'jabatan.nama as nama_jabatan');

        if ($this->tingkat === 'daerah') {
            $query->where('kepengurusan.daerah_id', $this->daerahId);
        } elseif ($this->tingkat === 'desa') {
            $query->where('kepengurusan.desa_id', $this->desaId);
        } else {
            $query->where('kepengurusan.kelompok_id', $this->kelompokId);
        }

        return $query->orderBy('jabatan.nama')->get();
    }

    public function headings(): array
    {
        return [
            'No',
            'Nama',
            'No HP',
            'Jabatan',
        ];
    }

    public function map($row): array
    {
        $this->rowNumber++;

        return [
            $this->rowNumber,
            $row->nama,
            $row->no_hp ?? '-',
            $row->nama_jabatan ?? '-',
        ];
    }
}
