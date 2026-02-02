<?php

namespace App\Http\Controllers;

use App\Models\kelas;
use Illuminate\Http\Request;

class KelasController extends Controller
{
    public function index()
    {
        $kelass = Kelas::all();

        // untuk sweat alert hapus
        $title = 'Delete Kelompok!';
        $text = "Are you sure you want to delete?";
        confirmDelete($title, $text);

        return view('kelas.index', compact('kelass'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama' => 'required',
        ], [
            'nama.required' => 'Nama harus diisi',
        ]);

        $kls = new kelas();
        $kls->nama = $request->nama;
        $kls->save();

        // Sweet alert
        toast('Kelas ' . $kls->nama . ' berhasil ditambahkan', 'success');

        return redirect('/kls');
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'nama' => 'required',
        ], [
            'nama.required' => 'Nama harus diisi',
        ]);

        $kls = Kelas::find($id);
        $kls->nama = $request->nama;
        $kls->save();

        // Sweet alert
        toast('Kelas ' . $kls->nama . ' berhasil diupdate', 'success');

        return redirect('/kls');
    }

    public function destroy($id)
    {
        $kls = Kelas::find($id);
        $kls->delete();

        // Sweet alert
        toast('Kelas ' . $kls->nama . ' berhasil dihapus', 'success');

        return redirect('/kls');
    }
}
