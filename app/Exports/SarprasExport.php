<?php

namespace App\Exports;

use App\Models\Kelompok;
use Maatwebsite\Excel\Concerns\WithMultipleSheets;

class SarprasExport implements WithMultipleSheets
{
    protected $kelompoks;

    public function __construct()
    {
        $user = auth()->user();

        if ($user->jabatan === 'kelompok') {
            $this->kelompoks = Kelompok::where('id', $user->id_kelompok)->get();
        } elseif ($user->jabatan === 'desa') {
            $this->kelompoks = Kelompok::where('id_desa', $user->id_desa)->get();
        } else {
            $this->kelompoks = Kelompok::all();
        }
    }

    public function sheets(): array
    {
        $sheets = [];

        foreach ($this->kelompoks as $kelompok) {
            $sheets[] = new SarprasPerKelompokSheet($kelompok->id, $kelompok->nama);
        }

        return $sheets;
    }
}
