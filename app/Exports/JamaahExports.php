<?php

namespace App\Exports;

use App\Models\jamaah;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class JamaahExports implements FromCollection, WithHeadings
{
    /**
     * @return \Illuminate\Support\Collection
     */
    public function collection()
    {
        return jamaah::all();
    }

    public function headings(): array
    {
        return [
            '#',
            'Nama',
            'Panggilan',
            'TanggalLahir',

        ];
    }
}
