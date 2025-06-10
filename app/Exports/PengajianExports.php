<?php

namespace App\Exports;

use App\Models\Desa;
use Maatwebsite\Excel\Concerns\WithMultipleSheets;

class PengajianExports implements WithMultipleSheets
{
    /**
     * @return \Illuminate\Support\Collection
     */

    protected $year;

    public function __construct($year = null,)
    {
        $this->year = $year;
    }

    /**
     * @return array
     */
    public function sheets(): array
    {
        $sheets = [];
        for ($month = 1; $month <= 12; $month++) {
            $sheets[] = new PengajianPerMonthSheet($this->year, $month);
        }

        return $sheets;
    }
}
