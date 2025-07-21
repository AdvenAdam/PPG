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
    protected $role;

    public function __construct()
    {
        $this->desa = Desa::all();
        $this->role = auth()->user()->jabatan;
    }

    /**
     * @return array
     */
    public function sheets(): array
    {
        $sheets = [];
        switch ($this->role) {
            case 'daerah':
                foreach ($this->desa as $value) {
                    $sheets[] = new GenerusPerDesaSheet($value->id, $value->nama);
                }
                break;
            default:
                $desa = $this->desa->firstWhere('id', auth()->user()->id_desa);
                if ($desa) {
                    $sheets[] = new GenerusPerDesaSheet($desa->id, $desa->nama);
                }
                break;
        }


        return $sheets;
    }
}
