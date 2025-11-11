<?php

namespace App\Http\Controllers;

use App\Models\Proker;
use Illuminate\Http\Request;

class ProkerController extends Controller
{
    public function index()
    {
        $prokers = Proker::with('tim')->get();
        return view("tim-proker.index", compact("prokers"));
    }

    public function store(Request $request)
    {
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

        $proker = Proker::create($validated);

        return response()->json($proker, 201);
    }

    public function show($id)
    {
        return response()->json(Proker::with('tim')->findOrFail($id), 200);
    }

    public function update(Request $request, $id)
    {
        $proker = Proker::findOrFail($id);

        $validated = $request->validate([
            'program' => 'string',
            'id_tim' => 'exists:tim_proker,id',
            'latar_belakang' => 'string',
            'tujuan' => 'string',
            'sasaran' => 'string',
            'target' => 'string',
            'waktu_pelaksanaan' => 'array',
            'penanggung_jawab' => 'string',
            'indikator_keberhasilan' => 'string',
            'anggaran' => 'string',
            'keterangan' => 'string',
        ]);

        $proker->update($validated);

        return response()->json($proker, 200);
    }

    public function destroy($id)
    {
        Proker::destroy($id);
        return response()->json(['message' => 'Deleted successfully'], 200);
    }
}
