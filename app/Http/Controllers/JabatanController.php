<?php

namespace App\Http\Controllers;

use App\Models\Jabatan;
use Illuminate\Http\Request;

class JabatanController extends Controller
{
    public function index()
    {
        $jabatan = Jabatan::all();

        $title = 'Delete Jabatan!';
        $text = "Are you sure you want to delete?";
        confirmDelete($title, $text);

        return view('jabatan.index', compact('jabatan'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama'    => 'required',
            'tingkat' => 'required|in:desa,kelompok,daerah',
        ]);

        $jabatan = new Jabatan();
        $jabatan->nama    = $request->nama;
        $jabatan->tingkat = $request->tingkat;
        $jabatan->save();

        toast('Jabatan ' . $jabatan->nama . ' berhasil ditambahkan', 'success');

        return redirect('/jabatan');
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'nama'    => 'required',
            'tingkat' => 'required|in:desa,kelompok,daerah',
        ]);

        $jabatan = Jabatan::find($id);
        $jabatan->nama    = $request->nama;
        $jabatan->tingkat = $request->tingkat;
        $jabatan->save();

        toast('Jabatan ' . $jabatan->nama . ' berhasil diupdate', 'success');

        return redirect('/jabatan');
    }

    public function destroy($id)
    {
        $jabatan = Jabatan::find($id);
        $jabatan->delete();

        toast('Jabatan ' . $jabatan->nama . ' berhasil dihapus', 'success');

        return redirect('/jabatan');
    }
}
