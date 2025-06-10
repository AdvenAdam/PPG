<?php

namespace App\Exports;

use App\Models\Desa;
use App\Models\Generus;
use App\Models\kelas;
use App\Models\Kelompok;
use App\Models\Pengajian;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithTitle;

class PengajianPerMonthSheet implements FromCollection, WithHeadings, WithMapping, ShouldAutoSize,  WithTitle
{
    /**
     * @return \Illuminate\Support\Collection
     */

    protected $year;
    protected $month;
    protected $role;

    public function __construct($year = null, $month = null, $role = null)
    {
        $this->year = $year ?? date('Y');
        $this->month = $month ?? date('m');
        $this->role = Auth::user()->jabatan;
    }
    // TODO : MAKE THIS EXPORT SUITABLE FOR USER LEVEL (DAERAH, DESA, KELOMPOK)
    public function collection()
    {
        $query = Pengajian::join('absens', 'pengajians.id', '=', 'absens.id_pengajian')
            ->join('kelas', 'absens.id_kelas', '=', 'kelas.id')
            ->join('kelompok', 'pengajians.id_kelompok', '=', 'kelompok.id')
            ->select(
                'absens.id_kelas',
                'pengajians.id_kelompok',
                'absens.keterangan'
            )
            ->whereYear('pengajians.waktu_tanggal_mulai', $this->year)
            ->whereMonth('pengajians.waktu_tanggal_mulai', $this->month)
            ->orderBy('absens.id_kelas');

        // Role-based filtering (before get)
        if (auth()->user()->jabatan === 'kelompok') {
            $query->where('pengajians.id_kelompok', auth()->user()->id_kelompok);
        }

        if (auth()->user()->jabatan === 'desa') {
            $kelompokIds = Kelompok::where('id_desa', auth()->user()->id_desa)->pluck('id');
            $query->whereIn('pengajians.id_kelompok', $kelompokIds);
        }

        $pengajian = $query->get();

        // Group the result
        return $pengajian->groupBy(function ($item) {
            return $item->id_kelas . '-' . $item->id_kelompok;
        });
    }


    public function map($pengajian): array
    {
        // $pengajian here is a collection grouped by id_kelas
        $first = $pengajian->first();
        $idKelas = $first->id_kelas;
        $kelas = kelas::find($idKelas);
        $idKelompok = $first->id_kelompok;
        $kelompok = Kelompok::find($idKelompok);

        $totalHadir = 0;
        $totalAlpha = 0;
        $totalIzin = 0;
        $totalSakit = 0;

        foreach ($pengajian as $row) {
            $keterangan = json_decode($row->keterangan, true);

            $totalHadir += $keterangan['hadir'] ?? 0;
            $totalAlpha += $keterangan['alpha'] ?? 0;
            $totalIzin  += $keterangan['izin'] ?? 0;
            $totalSakit += $keterangan['sakit'] ?? 0;
        }

        $jumlahNgaji = $pengajian->count();
        $totalAll = $totalHadir + $totalAlpha + $totalIzin + $totalSakit;
        $presentaseHadir = $totalAll > 0 ? round(($totalHadir / $totalAll) * 100, 2) : 0;

        return [
            $kelompok->nama,
            $kelas->nama,
            $totalHadir,
            $totalAlpha,
            $totalIzin,
            $totalSakit,
            $jumlahNgaji,
            $presentaseHadir . '%',
        ];
    }


    public function headings(): array
    {
        return [
            'Kelompok',
            'Kelas',
            'Hadir',
            'Alpha',
            'Izin',
            'Sakit',
            'Jumlah Ngaji',
            'Presentase Hadir (%)',

        ];
    }


    public function title(): string
    {
        if ($this->role == 'daerah') {
            $deskel = 'Boyolali Barat';
        } elseif ($this->role == 'desa') {
            $deskel = Desa::where('id', auth()->user()->id_desa)->first()->nama;
        } else {
            $deskel = Kelompok::where('id', auth()->user()->id_kelompok)->first()->nama;
        }

        if (!$deskel) {
            return 'Absensi ' . $this->year . '-' . str_pad($this->month, 2, '0', STR_PAD_LEFT) . $this->role . ' Tidak Ditemukan';
        }
        return 'Absensi ' . $this->year . '-' . str_pad($this->month, 2, '0', STR_PAD_LEFT) . '-'  . $deskel;
    }
}
