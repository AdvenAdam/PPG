<?php

namespace App\Exports;

use App\Models\Daerah;
use App\Models\Desa;
use App\Models\Kelompok;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\WithMultipleSheets;

class KepengurusanExport implements WithMultipleSheets
{
    protected $sheets = [];

    public function __construct()
    {
        $user = auth()->user();

        // Build query to get distinct units visible to this user
        $query = DB::table('kepengurusan')
            ->leftJoin('desa', 'kepengurusan.desa_id', '=', 'desa.id')
            ->leftJoin('kelompok', 'kepengurusan.kelompok_id', '=', 'kelompok.id')
            ->leftJoin('daerah', 'kepengurusan.daerah_id', '=', 'daerah.id')
            ->select(
                DB::raw("COALESCE(daerah.nama, desa.nama, kelompok.nama) as nama_unit"),
                DB::raw("CASE
                    WHEN kepengurusan.daerah_id IS NOT NULL THEN 'daerah'
                    WHEN kepengurusan.desa_id   IS NOT NULL THEN 'desa'
                    ELSE 'kelompok'
                END as tingkat"),
                'kepengurusan.daerah_id',
                'kepengurusan.desa_id',
                'kepengurusan.kelompok_id'
            )
            ->distinct();

        if ($user->jabatan === 'kelompok') {
            $query->where('kepengurusan.kelompok_id', $user->id_kelompok);
        } elseif ($user->jabatan === 'desa') {
            $kelompokIds = Kelompok::where('id_desa', $user->id_desa)->pluck('id');
            $query->where(function ($q) use ($user, $kelompokIds) {
                $q->where('kepengurusan.desa_id', $user->id_desa)
                    ->orWhereIn('kepengurusan.kelompok_id', $kelompokIds);
            });
        }

        $units = $query->get();

        foreach ($units as $unit) {
            $this->sheets[] = new KepengurusanPerUnitSheet(
                $unit->tingkat,
                $unit->nama_unit,
                $unit->daerah_id,
                $unit->desa_id,
                $unit->kelompok_id
            );
        }
    }

    public function sheets(): array
    {
        return $this->sheets;
    }
}
