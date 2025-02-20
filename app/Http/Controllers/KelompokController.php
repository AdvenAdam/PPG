<?php

namespace App\Http\Controllers;

use App\Models\Desa;
use App\Models\Kelompok;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class KelompokController extends Controller
{
    public function index()
    {
        $kelompok = DB::table('kelompok')
            ->join('desa', 'kelompok.id_desa', '=', 'desa.id')
            ->select('kelompok.*', 'desa.nama as nama_desa')
            ->get();

        $desas = Desa::all();

        // untuk sweat alert hapus
        $title = 'Delete Kelompok!';
        $text = "Are you sure you want to delete?";
        confirmDelete($title, $text);

        return view('kelompok.index', compact('kelompok', 'desas'));
    }

    public function getByDesa($id)
    {
        $kelompoks = Kelompok::where('id_desa', $id)->get();
        $role = auth()->user()->jabatan;
        if ($role === 'kelompok') {
            $kelompoks = $kelompoks->where('id', auth()->user()->id_kelompok);
        }
        return $kelompoks;
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama' => 'required',
            'alamat' => 'required',
            'desa' => 'required'
        ]);

        $kelompok = new Kelompok();
        $kelompok->nama = $request->nama;
        $kelompok->alamat = $request->alamat;
        $kelompok->id_desa = $request->desa;
        $kelompok->save();

        // sweat alert
        toast('Kelompok ' . $kelompok->nama . ' berhasil ditambahkan', 'success');

        return redirect('/kelompok');
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'nama' => 'required',
            'alamat' => 'required',
            'desa' => 'required'
        ]);

        $kelompok = Kelompok::find($id);
        $kelompok->nama = $request->nama;
        $kelompok->alamat = $request->alamat;
        $kelompok->id_desa = $request->desa;
        $kelompok->save();

        // sweat alert
        toast('Kelompok ' . $kelompok->nama . ' berhasil diupdate', 'success');

        return redirect('/kelompok');
    }

    public function destroy($id)
    {
        $kelompok = Kelompok::find($id);
        $kelompok->delete();

        // sweat alert
        toast('Kelompok ' . $kelompok->nama . ' berhasil dihapus', 'success');

        return redirect('/kelompok');
    }
}
