<?php

namespace App\Exports;

use App\Models\Sarpras;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithTitle;

class SarprasPerKelompokSheet implements FromQuery, WithHeadings, WithMapping, ShouldAutoSize, WithTitle
{
    protected $kelompokId;
    protected $kelompokNama;
    protected int $rowNumber = 0;

    public function __construct(int $kelompokId, string $kelompokNama)
    {
        $this->kelompokId   = $kelompokId;
        $this->kelompokNama = $kelompokNama;
    }

    public function title(): string
    {
        // Sheet names max 31 chars, strip invalid characters
        return mb_substr($this->kelompokNama, 0, 31);
    }

    public function query()
    {
        return Sarpras::where('id_kelompok', $this->kelompokId)
            ->orderBy('nama');
    }

    public function headings(): array
    {
        return [
            'No',
            'Nama Sarpras',
            'Jumlah',
            'Kondisi Baik',
            'Kondisi Sedang',
            'Kondisi Rusak',
            'Keterangan',
        ];
    }

    public function map($sarpras): array
    {
        $this->rowNumber++;

        return [
            $this->rowNumber,
            $sarpras->nama,
            $sarpras->jumlah,
            $sarpras->kondisi['baik']   ?? 0,
            $sarpras->kondisi['sedang'] ?? 0,
            $sarpras->kondisi['rusak']  ?? 0,
            $sarpras->keterangan ?? '',
        ];
    }
}
