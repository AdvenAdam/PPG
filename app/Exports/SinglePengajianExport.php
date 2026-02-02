<?php

namespace App\Exports;

use App\Models\Pengajian;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Events\AfterSheet;
use PhpOffice\PhpSpreadsheet\Style\Alignment;

class SinglePengajianExport implements FromCollection, WithEvents, ShouldAutoSize
{
    protected $pengajian;
    protected array $kelasRows = [];
    protected int $currentRow = 0;

    public function __construct(Pengajian $pengajian)
    {
        $this->pengajian = $pengajian;
    }

    public function collection(): Collection
    {
        $rows = collect();

        /* ===== TITLE ===== */
        $this->pushRow($rows, [
            'ABSENSI ' . strtoupper($this->pengajian->nama),
            '',
            '',
            '',
            '',
            ''
        ]);

        /* ===== INFO ===== */
        $this->pushRow($rows, [
            'Tanggal: ' . $this->pengajian->waktu_tanggal_mulai .
                ' | Kelompok: ' . $this->pengajian->Kelompok()->first()->nama,
            '',
            '',
            '',
            '',
            ''
        ]);

        /* ===== EMPTY ===== */
        $this->pushRow($rows, ['', '', '', '', '', '']);

        foreach ($this->pengajian->Absens as $absen) {

            /* ===== KELAS TITLE ===== */
            $this->pushRow($rows, [
                'KELAS : ' . $absen->kelas->nama,
                '',
                '',
                '',
                '',
                ''
            ], true); // mark as kelas row

            /* ===== TABLE HEADER ===== */
            $this->pushRow($rows, [
                'No',
                'Nama',
                'A',
                'S',
                'I',
                'H'
            ]);

            foreach (json_decode($absen->absen) as $i => $item) {
                $this->pushRow($rows, [
                    $i + 1,
                    $item->nama,
                    $item->absen === 'alpha' ? '✓' : '',
                    $item->absen === 'sakit' ? '✓' : '',
                    $item->absen === 'izin'  ? '✓' : '',
                    $item->absen === 'hadir' ? '✓' : '',
                ]);
            }
            /* ===== SPACE ===== */
            $this->pushRow($rows, ['', '', '', '', '', '']);
        }

        return $rows;
    }

    private function pushRow(Collection $rows, array $data, bool $isKelas = false): void
    {
        $rows->push([
            'No'   => $data[0],
            'Nama' => $data[1],
            'A'    => $data[2],
            'S'    => $data[3],
            'I'    => $data[4],
            'H'    => $data[5],
        ]);

        $this->currentRow++;

        if ($isKelas) {
            $this->kelasRows[] = $this->currentRow;
        }
    }

    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function (AfterSheet $event) {

                $sheet = $event->sheet->getDelegate();

                /* ===== TITLE MERGE ===== */
                $sheet->mergeCells('A1:F1');
                $sheet->mergeCells('A2:F2');

                $sheet->getStyle('A1:F2')->getAlignment()
                    ->setHorizontal(Alignment::HORIZONTAL_CENTER)
                    ->setVertical(Alignment::VERTICAL_CENTER);

                $sheet->getStyle('A1')->getFont()->setBold(true)->setSize(14);
                $sheet->getStyle('A2')->getFont()->setBold(true);

                /* ===== KELAS MERGE & CENTER ===== */
                foreach ($this->kelasRows as $row) {
                    $sheet->mergeCells("A{$row}:F{$row}");

                    $sheet->getStyle("A{$row}:F{$row}")
                        ->getAlignment()
                        ->setHorizontal(Alignment::HORIZONTAL_CENTER)
                        ->setVertical(Alignment::VERTICAL_CENTER);

                    $sheet->getStyle("A{$row}")
                        ->getFont()
                        ->setBold(true);
                }
            }
        ];
    }
}
