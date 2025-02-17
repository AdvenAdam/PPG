<?php

namespace App\Exports;

use App\Models\Desa;
use Maatwebsite\Excel\Concerns\WithMultipleSheets;
use PhpOffice\PhpSpreadsheet\Style\NumberFormat as StyleNumberFormat;

class GenerusExports implements WithMultipleSheets
{
    /**
     * @return \Illuminate\Support\Collection
     */

    protected $desa;

    public function __construct()
    {
        $this->desa = Desa::all();
    }

    /**
     * @return array
     */
    public function sheets(): array
    {
        $sheets = [];
        foreach ($this->desa as $value) {
            $sheets[] = new GenerusPerDesaSheet($value->id, $value->nama);
        }

        return $sheets;
    }
}
