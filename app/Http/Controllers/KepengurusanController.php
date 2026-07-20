<?php

namespace App\Http\Controllers;

use App\Exports\KepengurusanExport;
use App\Models\Daerah;
use App\Models\Desa;
use App\Models\Jabatan;
use App\Models\Kelompok;
use App\Models\Kepengurusan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;

class KepengurusanController extends Controller
{
    public function index(Request $request)
    {
        $user = Auth::user();

        $desas     = Desa::all();
        $kelompoks = Kelompok::all();
        $daerahs   = Daerah::all();
        $jabatans  = Jabatan::all();

        $query = DB::table('kepengurusan')
            ->leftJoin('desa', 'kepengurusan.desa_id', '=', 'desa.id')
            ->leftJoin('kelompok', 'kepengurusan.kelompok_id', '=', 'kelompok.id')
            ->leftJoin('daerah', 'kepengurusan.daerah_id', '=', 'daerah.id')
            ->leftJoin('jabatan', 'kepengurusan.jabatan_id', '=', 'jabatan.id')
            ->select(
                'kepengurusan.*',
                'jabatan.nama as nama_jabatan',
                'desa.nama as nama_desa',
                'kelompok.nama as nama_kelompok',
                'daerah.nama as nama_daerah',
                DB::raw("CASE
                    WHEN kepengurusan.daerah_id IS NOT NULL THEN 'daerah'
                    WHEN kepengurusan.desa_id   IS NOT NULL THEN 'desa'
                    ELSE 'kelompok'
                END as tingkat"),
                DB::raw("COALESCE(daerah.nama, desa.nama, kelompok.nama) as nama_unit")
            )
            ->orderByRaw("CASE
                WHEN kepengurusan.daerah_id IS NOT NULL THEN 1
                WHEN kepengurusan.desa_id   IS NOT NULL THEN 2
                ELSE 3
            END")
            ->orderBy('nama_unit');

        // Role-based scope
        if ($user->jabatan === 'kelompok') {
            $query->where('kepengurusan.kelompok_id', $user->id_kelompok);
            $kelompoks = Kelompok::where('id', $user->id_kelompok)->get();
            $desas     = collect();
        } elseif ($user->jabatan === 'desa') {
            $desaKelompokIds = Kelompok::where('id_desa', $user->id_desa)->pluck('id');
            $query->where(function ($q) use ($user, $desaKelompokIds) {
                $q->where('kepengurusan.desa_id', $user->id_desa)
                    ->orWhereIn('kepengurusan.kelompok_id', $desaKelompokIds);
            });
            $desas     = Desa::where('id', $user->id_desa)->get();
            $kelompoks = Kelompok::where('id_desa', $user->id_desa)->get();
        }

        // Optional filters from search form (daerah level only)
        if ($user->jabatan === 'daerah') {
            if ($request->filled('id_desa')) {
                $kelompokIds = Kelompok::where('id_desa', $request->id_desa)->pluck('id');
                $query->where(function ($q) use ($request, $kelompokIds) {
                    $q->where('kepengurusan.desa_id', $request->id_desa)
                        ->orWhereIn('kepengurusan.kelompok_id', $kelompokIds);
                });
            }
            if ($request->filled('id_klmpk')) {
                $query->where('kepengurusan.kelompok_id', $request->id_klmpk);
            }
            if ($request->filled('tingkat_filter')) {
                $tingkat = $request->tingkat_filter;
                if ($tingkat === 'daerah') {
                    $query->whereNotNull('kepengurusan.daerah_id');
                } elseif ($tingkat === 'desa') {
                    $query->whereNotNull('kepengurusan.desa_id');
                } elseif ($tingkat === 'kelompok') {
                    $query->whereNotNull('kepengurusan.kelompok_id');
                }
            }
        }

        $kepengurusan = $query->get()->groupBy(['tingkat', 'nama_unit']);

        confirmDelete('Delete Kepengurusan!', 'Are you sure you want to delete?');

        return view('kepengurusan.index', compact(
            'kepengurusan',
            'desas',
            'kelompoks',
            'daerahs',
            'jabatans'
        ));
    }

    public function export()
    {
        $filename = 'Kepengurusan_' . now()->format('d-m-Y') . '.xlsx';
        return Excel::download(new KepengurusanExport(), $filename);
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama'       => 'required',
            'no_hp'      => 'required',
            'jabatan_id' => 'required|exists:jabatan,id',
            'tingkat'    => 'required|in:daerah,desa,kelompok',
        ]);

        $kepengurusan = new Kepengurusan();
        $kepengurusan->nama        = $request->nama;
        $kepengurusan->no_hp       = $request->no_hp;
        $kepengurusan->jabatan_id  = $request->jabatan_id;
        $kepengurusan->daerah_id   = $request->tingkat === 'daerah'   ? ($request->daerah_id   ?: null) : null;
        $kepengurusan->desa_id     = $request->tingkat === 'desa'     ? ($request->desa_id     ?: null) : null;
        $kepengurusan->kelompok_id = $request->tingkat === 'kelompok' ? ($request->kelompok_id ?: null) : null;
        $kepengurusan->save();

        toast('Kepengurusan ' . $kepengurusan->nama . ' berhasil ditambahkan', 'success');

        return redirect()->route('kepengurusan.index');
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'nama'       => 'required',
            'no_hp'      => 'required',
            'jabatan_id' => 'required|exists:jabatan,id',
            'tingkat'    => 'required|in:daerah,desa,kelompok',
        ]);

        $kepengurusan = Kepengurusan::findOrFail($id);
        $kepengurusan->nama        = $request->nama;
        $kepengurusan->no_hp       = $request->no_hp;
        $kepengurusan->jabatan_id  = $request->jabatan_id;
        $kepengurusan->daerah_id   = $request->tingkat === 'daerah'   ? ($request->daerah_id   ?: null) : null;
        $kepengurusan->desa_id     = $request->tingkat === 'desa'     ? ($request->desa_id     ?: null) : null;
        $kepengurusan->kelompok_id = $request->tingkat === 'kelompok' ? ($request->kelompok_id ?: null) : null;
        $kepengurusan->save();

        toast('Kepengurusan ' . $kepengurusan->nama . ' berhasil diupdate', 'success');

        return redirect()->route('kepengurusan.index');
    }

    public function destroy($id)
    {
        $kepengurusan = Kepengurusan::findOrFail($id);
        $nama = $kepengurusan->nama;
        $kepengurusan->delete();

        toast('Kepengurusan ' . $nama . ' berhasil dihapus', 'success');

        return redirect()->route('kepengurusan.index');
    }
}
