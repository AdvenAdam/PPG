<?php

namespace App\Http\Controllers;

use App\Exports\PengajianExports;
use App\Models\Absen;
use App\Models\Desa;
use App\Models\Generus;
use App\Models\kelas;
use App\Models\Kelompok;
use App\Models\Pengajian;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;

class PengajianController extends Controller
{

    public function index(Request $request)
    {
        $user = Auth::user();

        $kelas = Kelas::all();
        $desa = Desa::all();
        $kelompoks = Kelompok::all();

        $pengajiansQuery = Pengajian::with('Absens', 'Absens.Kelas');

        // Role-based filters
        if ($user->jabatan === 'kelompok') {
            $pengajiansQuery->where('id_kelompok', $user->id_kelompok);
        }

        if ($user->jabatan === 'desa') {
            $kelompoks = Kelompok::where('id_desa', $user->id_desa)->get();
            $pengajiansQuery->whereIn('id_kelompok', $kelompoks->pluck('id'));
            $desa = Desa::where('id', $user->id_desa)->get();
        }

        // Filters from search form
        if ($request->filled('id_desa')) {
            $kelompoks = Kelompok::where('id_desa', $request->id_desa)->get();
            $pengajiansQuery->whereIn('id_kelompok', $kelompoks->pluck('id'));
        }

        if ($request->filled('id_klmpk')) {
            $pengajiansQuery->where('id_kelompok', $request->id_klmpk);
        }

        if ($request->filled('tahun')) {
            $year = $request->tahun;
            $pengajiansQuery->whereBetween('waktu_tanggal_mulai', [
                "$year-01-01",
                "$year-12-31"
            ]);
        }

        // Get filtered results
        $pengajians = $pengajiansQuery->orderBy('waktu_tanggal_mulai')->get();

        // Generate available years
        $tahun = $pengajians->pluck('waktu_tanggal_mulai')->map(fn($d) => date('Y', strtotime($d)))
            ->unique()
            ->sort()
            ->values();

        // Attendance calculation
        $overallKehadiran = [];
        foreach ($pengajians as $pengajian) {
            foreach ($pengajian->Absens as $absen) {
                foreach (json_decode($absen->keterangan) as $key => $val) {
                    $overallKehadiran[$pengajian->id][$key] = ($overallKehadiran[$pengajian->id][$key] ?? 0) + $val;
                    $overallKehadiran[$pengajian->id]['total'] = ($overallKehadiran[$pengajian->id]['total'] ?? 0) + $val;
                }
            }
        }

        // SweetAlert confirmation
        confirmDelete('Delete Pengajian!', 'Are you sure you want to delete?');

        return view("pengajian.index", compact("kelas", "pengajians", "overallKehadiran", "desa", "tahun"));
    }


    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        try {
            $this->validatePengajian($request);
            DB::beginTransaction();
            $pengajian = Pengajian::create([
                'nama' => $request->input('nama'),
                'waktu_tanggal_mulai' => $request->input('waktu_tanggal_mulai'),
                'id_kelompok' => Auth::user()->id_kelompok,
            ]);
            foreach ($request->input('kelas') as $id_kelas) {
                $generus = Generus::where('id_kelas', $id_kelas)->where('id_kelompok', Auth::user()->id_kelompok)->get();
                $absenFormated = $generus->map(function ($gen) {
                    return [
                        'id_generus' => $gen->id,
                        'nama' => $gen->nama,
                        'absen' => 'alpha',
                    ];
                })->toArray();

                $keterangan = [
                    'alpha' => $generus->count(),
                    'hadir' => 0,
                    'sakit' => 0,
                    'izin' => 0
                ];

                Absen::create([
                    'id_kelas' => $id_kelas,
                    'id_pengajian' => $pengajian->id,
                    'absen' => json_encode($absenFormated),
                    'keterangan' => json_encode($keterangan),
                ]);
            }
            DB::commit();
            toast('Berhasil menambahkan data', 'success');
        } catch (\Throwable $th) {
            DB::rollBack();
            toast('Error saat menambahkan data <br/>' . $th->getMessage(), 'error');
            //throw $th;
        } finally {
            return redirect()->back()->withInput();
        }
    }

    private function validatePengajian(request $request): void
    {
        $rules = [
            'nama' => 'required',
            'waktu_tanggal_mulai' => 'required',
        ];
        $messages = [
            'nama.required' => 'Nama Pengajian harus diisi!',
            'waktu_tanggal_mulai.required' => 'Tanggal dan Waktu Mulai harus diisi!',

        ];
        $request->validate($rules, $messages);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(pengajian $pengajian)
    {
        $kelas = Kelas::all(); // Get all classes
        $pengajian = Pengajian::where('id', $pengajian->id)
            ->with('Absens', 'Absens.Kelas')
            ->orderBy('waktu_tanggal_mulai')
            ->get()->first();
        // untuk sweat alert hapus
        $title = 'Delete Pengajian!';
        $text = "Are you sure you want to delete?";
        confirmDelete($title, $text);

        return view('pengajian.detail.index', compact('pengajian', 'kelas'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Absen $absen)
    {
        try {
            DB::beginTransaction();
            $oldAbsen = json_decode($absen->absen);
            $newAbsen = $request->input('absensi');
            $formattedAbsen = [];
            $formattedKeterangan = [
                'alpha' => 0,
                'hadir' => 0,
                'sakit' => 0,
                'izin' => 0,
            ];
            foreach ($newAbsen as $key => $value) {
                $absensiLabel = match ($value) {
                    'I' => 'izin',
                    'H' => 'hadir',
                    'S' => 'sakit',
                    default => 'alpha',
                };
                // Find the matching item in the oldAbsen array
                $matched = null;
                foreach ($oldAbsen as $item) {
                    if ($item->id_generus == $key) {
                        $matched = $item;
                        break;
                    }
                }
                // If a matching item is found, update its absen value
                if ($matched) {
                    $formattedAbsen[] = [
                        'id_generus' => $key,
                        'nama' => $matched->nama,
                        'absen' => $absensiLabel,
                    ];
                    $formattedKeterangan[$absensiLabel]++;
                }
            }
            $absen->absen = json_encode($formattedAbsen);
            $absen->keterangan = json_encode($formattedKeterangan);
            $absen->save();

            DB::commit();
            toast('Berhasil mengupdate data', 'success');
        } catch (\Throwable $th) {
            DB::rollBack();
            toast('Error saat mengupdate data <br/>' . $th->getMessage(), 'error');
        } finally {
            return redirect()->back()->withInput();
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(pengajian $pengajian)
    {
        try {
            DB::beginTransaction();
            $pengajian->Absens()->delete();
            $pengajian->delete();
            DB::commit();
            toast('Berhasil menghapus data', 'success');
        } catch (\Throwable $th) {
            DB::rollBack();
            dd($th);
            toast('Error saat menghapus data <br/>' . $th->getMessage(), 'error');
        } finally {
            return redirect()->back();
        }
    }

    public function export($tahun)
    {
        $role = auth()->user()->jabatan;
        // dd($role);
        $name = '';
        if ($role == 'daerah') {
            $name = 'Absensi Generus Daerah ' . $tahun . '.xlsx';
        } elseif ($role == 'desa') {
            $deskel = Desa::where('id', auth()->user()->id_desa)->first()->nama;
            $name = "Absensi Generus {$deskel} {$tahun}.xlsx";
        } else {
            $deskel = Kelompok::where('id', auth()->user()->id_kelompok)->first()->nama;
            $name = "Absensi Generus {$deskel} {$tahun}.xlsx";
        }

        return Excel::download((new PengajianExports($tahun)),  $name . '.xlsx');
    }
}
