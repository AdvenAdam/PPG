<?php

namespace App\Http\Controllers;

use App\Models\TimProker;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class TimProkerController extends Controller
{
    public function index()
    {
        $timProker = TimProker::all();
        confirmDelete('Delete Tim Proker!', 'Are you sure you want to delete?');
        return view("tim-proker.index", compact("timProker"));
    }

    public function store(Request $request)
    {
        DB::beginTransaction();
        try {
            $validated = $request->validate([
                'nama' => 'required|string|unique:tim_proker',
                'anggota' => 'required|array'
            ]);

            TimProker::create($validated);
            DB::commit();

            toast('Berhasil menambahkan data', 'success');
            return redirect()
                ->route('tim-proker.index', [], 303)
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
                'nama' => 'required|string|unique:tim_proker,nama,' . $id,
                'anggota' => 'required|array'
            ]);

            $tim = TimProker::findOrFail($id);
            $tim->update($validated);

            DB::commit();

            toast('Berhasil memperbarui data', 'success');
            return redirect()
                ->route('tim-proker.index', [], 303)
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
            $tim = TimProker::findOrFail($id);
            $tim->delete();

            DB::commit();

            toast('Berhasil menghapus data', 'success');
            return redirect()
                ->route('tim-proker.index', [], 303);
        } catch (\Throwable $th) {
            DB::rollBack();
            throw $th;
        }
    }
}
