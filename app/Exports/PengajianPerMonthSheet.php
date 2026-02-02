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
    protected $tingkat;

    public function __construct($year = null, $month = null, $role = null)
    {
        $this->year = $year ?? date('Y');
        $this->month = $month ?? date('m');
        $this->role = Auth::user()->jabatan;
        $this->tingkat = ['desa', 'daerah', 'kelompok'];
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
                'pengajians.tingkat',
                'absens.keterangan'
            )
            ->whereYear('pengajians.waktu_tanggal_mulai', $this->year)
            ->whereMonth('pengajians.waktu_tanggal_mulai', $this->month);

        // Role-based filtering (before get)
        if (auth()->user()->jabatan === 'kelompok') {
            $query->where('pengajians.id_kelompok', auth()->user()->id_kelompok);
        }

        if (auth()->user()->jabatan === 'desa') {
            $kelompokIds = Kelompok::where('id_desa', auth()->user()->id_desa)->pluck('id');
            $query->whereIn('pengajians.id_kelompok', $kelompokIds);
        }

        $pengajian = $query
            ->orderBy('pengajians.id_kelompok')
            ->orderBy('absens.id_kelas')
            ->orderBy('pengajians.tingkat')
            ->get();

        $grouped = $pengajian
            ->groupBy(fn($item) => $item->id_kelompok . '-' . $item->id_kelas)
            ->sortKeys() // order kelompok → kelas
            ->map(function ($items) {
                // enforce tingkat order INSIDE each group
                return $items->sortBy(fn($i) => match ($i->tingkat) {
                    'daerah' => 1,
                    'desa' => 2,
                    'kelompok' => 3,
                    default => 99,
                });
            });

        return $grouped;
    }

    public function map($pengajian): array
    {
        $rows = [];

        // Guaranteed by collection(): one kelas + one kelompok
        $first = $pengajian->first();

        $kelas = kelas::find($first->id_kelas);
        $kelompok = Kelompok::find($first->id_kelompok);
        $desa = Desa::find($kelompok->id_desa);

        /**
         * Now split THIS kelompok into its tingkat rows
         * Result:
         * - kelompok × tingkat
         */
        $byTingkat = $pengajian->groupBy('tingkat');

        foreach ($byTingkat as $tingkat => $items) {
            $stat = $this->calculate($items);

            // Name depends on tingkat, but kelompok context stays
            $nama = match ($tingkat) {
                'kelompok' => $kelompok->nama,
                'desa'     => $desa->nama,
                'daerah'   => 'Boyolali Barat',
                'asrama'   => 'Asrama',
                default    => '-',
            };

            $rows[] = [
                ucfirst($tingkat),   // tingkat
                $kelompok->nama,     // ALWAYS show kelompok
                $nama,               // entity name by tingkat
                $kelas->nama,
                $stat['hadir'],
                $stat['alpha'],
                $stat['izin'],
                $stat['sakit'],
                $stat['jumlah'],
                $stat['persen'] . '%',
            ];
        }

        return $rows;
    }


    private function calculate($rows)
    {
        $hadir = $alpha = $izin = $sakit = 0;

        foreach ($rows as $row) {
            $ket = json_decode($row->keterangan, true);
            $hadir += $ket['hadir'] ?? 0;
            $alpha += $ket['alpha'] ?? 0;
            $izin  += $ket['izin'] ?? 0;
            $sakit += $ket['sakit'] ?? 0;
        }

        $total = $hadir + $alpha + $izin + $sakit;

        return [
            'hadir' => $hadir,
            'alpha' => $alpha,
            'izin'  => $izin,
            'sakit' => $sakit,
            'jumlah' => $rows->count(),
            'persen' => $total > 0 ? round(($hadir / $total) * 100, 2) : 0,
        ];
    }

    public function headings(): array
    {
        return [
            'Tingkat',
            'Kelompok',
            'Nama Tingkat',
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
