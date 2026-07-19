<?php

namespace App\Http\Controllers;

use App\Exports\SarprasExport;
use App\Models\Desa;
use App\Models\kelas;
use App\Models\Kelompok;
use App\Models\Sarpras;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;

class SarprasController extends Controller
{
    public function index(Request $request)
    {
        $user = Auth::user();

        $desa = Desa::all();
        $kelompoks = Kelompok::all();

        $sarprasQuery = Sarpras::with('kelompok');
        // Role-based filters
        if ($user->jabatan === 'kelompok') {
            $sarprasQuery->where('id_kelompok', $user->id_kelompok);
        }

        if ($user->jabatan === 'desa') {
            $kelompoks = Kelompok::where('id_desa', $user->id_desa)->get();
            $sarprasQuery->whereIn('id_kelompok', $kelompoks->pluck('id'));
            $desa = Desa::where('id', $user->id_desa)->get();
        }

        // Filters from search form
        if ($request->filled('id_desa')) {
            $kelompoks = Kelompok::where('id_desa', $request->id_desa)->get();
            $sarprasQuery->whereIn('id_kelompok', $kelompoks->pluck('id'));
        }

        if ($request->filled('id_klmpk')) {
            $sarprasQuery->where('id_kelompok', $request->id_klmpk);
        }

        // Get filtered results
        $sarpras = $sarprasQuery->get()->groupBy('kelompok.nama');
        // SweetAlert confirmation
        confirmDelete('Delete Sarpras!', 'Are you sure you want to delete?');

        return view("sarpras.index", compact("sarpras", "desa", "kelompoks"));
    }

    public function export()
    {
        $filename = 'Sarpras_' . now()->format('d-m-Y') . '.xlsx';
        return Excel::download(new SarprasExport(), $filename);
    }

    public function store(Request $request)
    {
        try {
            $this->validateSarpras($request);
            DB::beginTransaction();
            Sarpras::create([
                'nama' => $request->input('nama'),
                'jumlah' => $request->input('jumlah'),
                'id_kelompok' => $request->input('id_kelompok'),
                'kondisi' => [
                    'baik' => $request->input('baik'),
                    'sedang' => $request->input('sedang'),
                    'rusak' => $request->input('rusak'),
                ],
            ]);
            DB::commit();
            toast('Berhasil menambahkan data', 'success');
        } catch (\Throwable $th) {
            DB::rollBack();
            toast('Error saat menambahkan data <br/>' . $th->getMessage(), 'error');
            //throw $th;
        } finally {
            return redirect()
                ->route('sarpras.index', [], 303)
                ->withInput();
        }
    }
    public function update(Request $request, $id)
    {
        try {
            $this->validateSarpras($request);
            DB::beginTransaction();
            $sarpras = Sarpras::findOrFail($id);

            $sarpras->update([
                'nama' => $request->input('nama'),
                'jumlah' => $request->input('jumlah'),
                'id_kelompok' => $request->input('id_kelompok'),
                'kondisi' => [
                    'baik' => $request->input('baik'),
                    'sedang' => $request->input('sedang'),
                    'rusak' => $request->input('rusak'),
                ],
            ]);

            DB::commit();
            toast('Berhasil memperbarui data', 'success');
        } catch (\Throwable $th) {
            DB::rollBack();
            toast('Error saat memperbarui data <br/>' . $th->getMessage(), 'error');
            // throw $th; // bisa aktifkan kalau perlu debug
        } finally {
            return redirect()
                ->route('sarpras.index', [], 303)
                ->withInput();
        }
    }

    public function destroy($id)
    {
        try {
            DB::beginTransaction();

            $sarpras = Sarpras::findOrFail($id);
            $sarpras->delete();

            DB::commit();
            toast('Berhasil menghapus data', 'success');
        } catch (\Throwable $th) {
            DB::rollBack();
            toast('Error saat menghapus data <br/>' . $th->getMessage(), 'error');
            // throw $th; // Uncomment for debugging
        } finally {
            return redirect()
                ->route('sarpras.index', [], 303);
        }
    }

    private function validateSarpras(request $request): void
    {
        $rules = [
            'nama' => 'required',
            'jumlah' => 'required',
        ];
        $messages = [
            'nama.required' => 'Nama Sarpras harus diisi!',
            'jumlah.required' => 'Jumlah harus diisi!',

        ];
        $request->validate($rules, $messages);
    }
}
