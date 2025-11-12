<?php

namespace App\Http\Controllers;

use App\Models\Proker;
use App\Models\TimProker;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ProkerController extends Controller
{
    public function index()
    {
        $prokers = Proker::with('tim')->get();
        $tims = TimProker::all();
        confirmDelete('Delete Program Kerja!', 'Apakah Anda yakin ingin menghapus data ini?');

        return view('proker.index', compact('prokers', 'tims'));
    }

    public function store(Request $request)
    {
        DB::beginTransaction();
        try {
            $validated = $request->validate([
                'program' => 'required|string',
                'id_tim' => 'required|exists:tim_proker,id',
                'latar_belakang' => 'required|string',
                'tujuan' => 'required|string',
                'sasaran' => 'required|string',
                'target' => 'required|string',
                'waktu_pelaksanaan' => 'required|array',
                'penanggung_jawab' => 'required|string',
                'indikator_keberhasilan' => 'required|string',
                'anggaran' => 'required|string',
                'keterangan' => 'required|string',
            ]);

            Proker::create($validated);
            DB::commit();

            toast('Berhasil menambahkan program kerja', 'success');
            return redirect()
                ->route('proker.index', [], 303)
                ->withInput();
        } catch (\Throwable $th) {
            DB::rollBack();
            throw $th;
        }
    }

    public function update(Request $request, $id)
    {
        DB::beginTransaction();
        try {
            $validated = $request->validate([
                'program' => 'required|string',
                'id_tim' => 'required|exists:tim_proker,id',
                'latar_belakang' => 'required|string',
                'tujuan' => 'required|string',
                'sasaran' => 'required|string',
                'target' => 'required|string',
                'waktu_pelaksanaan' => 'required|array',
                'penanggung_jawab' => 'required|string',
                'indikator_keberhasilan' => 'required|string',
                'anggaran' => 'required|string',
                'keterangan' => 'required|string',
            ]);

            $proker = Proker::findOrFail($id);
            $proker->update($validated);

            DB::commit();

            toast('Berhasil memperbarui program kerja', 'success');
            return redirect()
                ->route('proker.index', [], 303)
                ->withInput();
        } catch (\Throwable $th) {
            DB::rollBack();
            throw $th;
        }
    }

    public function destroy($id)
    {
        DB::beginTransaction();
        try {
            $proker = Proker::findOrFail($id);
            $proker->delete();

            DB::commit();

            toast('Berhasil menghapus program kerja', 'success');
            return redirect()
                ->route('proker.index', [], 303);
        } catch (\Throwable $th) {
            DB::rollBack();
            throw $th;
        }
    }
}
