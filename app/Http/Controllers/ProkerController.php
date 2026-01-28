<?php

namespace App\Http\Controllers;

use App\Models\Proker;
use App\Models\TimProker;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Validation\Rule;

class ProkerController extends Controller
{
    public function index(Request $request)
    {
        $tims = TimProker::all();
        $prokerQuery = Proker::query();

        if ($request->filled('id_tim')) {
            $prokerQuery->where('id_tim', $request->id_tim);
        }

        if ($request->filled('bulan')) {
            $prokerQuery->where('waktu_pelaksanaan', 'like', '%' . $request->bulan . '%');
        }

        if ($request->filled('tahun')) {
            $year = $request->get('tahun');
            $prokerQuery->where('tahun', $year);
        } else {
            $prokerQuery->where('tahun', date('Y'));
        }

        $prokers = $prokerQuery->with('tim')->get();
        $months = Proker::MONTHS;
        $prokers->transform(function ($proker) use ($months) {
            $ordered = [];
            foreach ($months as $month) {
                if (array_key_exists($month, $proker->waktu_pelaksanaan)) {
                    $ordered[$month] = $proker->waktu_pelaksanaan[$month] ?? false;
                }
            }
            $proker['waktu_pelaksanaan'] = $ordered;
            return $proker;
        });
        $tahun = $prokers->pluck('tahun')
            ->unique()
            ->sort()
            ->values();
        $prokers = collect($prokers)->groupBy(fn($item) => $item['tim']['id']);

        confirmDelete('Delete Program Kerja!', 'Apakah Anda yakin ingin menghapus data ini?');
        return view('proker.index', compact('prokers', 'tims', 'tahun'));
    }

    public function store(Request $request)
    {
        DB::beginTransaction();
        try {
            $validated = $request->validate([
                'program' => 'required|string|unique:proker,program',
                'id_tim' => 'required|exists:tim_proker,id',
                'latar_belakang' => 'required|string',
                'tahun' => 'required|string',
                'tujuan' => 'required|string',
                'sasaran' => 'required|string',
                'target' => 'required|string',
                'waktu_pelaksanaan' => 'required|array',
                'penanggung_jawab' => 'required|string',
                'indikator_keberhasilan' => 'required|string',
                'anggaran' => 'required|integer',
                'keterangan' => 'string',
            ]);

            $waktuPelaksanaanOrdered = collect(Proker::MONTHS)
                ->filter(fn($month) => in_array($month, $validated['waktu_pelaksanaan']))
                ->mapWithKeys(fn($month) => [$month => false])
                ->toArray();

            $validated['waktu_pelaksanaan'] = $waktuPelaksanaanOrdered;

            Proker::create($validated);
            DB::commit();

            toast('Berhasil menambahkan program kerja', 'success');
            confirmDelete('Delete Program Kerja!', 'Are you sure you want to delete?');
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error saat menambahkan Proker: ' . $e->getMessage());
            toast('Data proker gagal ditambahkan pastikan semua data terisi', 'error');
        }
        return Redirect::back()->withInput();
    }

    public function update(Request $request, Proker $proker)
    {
        $validated = $request->validate([
            'program' => 'required|string|unique:proker,program,' . $proker->id,
            'id_tim' => 'required|exists:tim_proker,id',
            'latar_belakang' => 'required|string',
            'tujuan' => 'required|string',
            'sasaran' => 'required|string',
            'target' => 'required|string',
            'waktu_pelaksanaan' => 'required|array',
            'penanggung_jawab' => 'required|string',
            'indikator_keberhasilan' => 'required|string',
            'anggaran' => 'required|integer',
            'keterangan' => 'nullable|string',
        ]);
        DB::beginTransaction();
        try {

            $waktuPelaksanaanOrdered = collect(Proker::MONTHS)
                ->filter(fn($month) => in_array($month, $validated['waktu_pelaksanaan']))
                ->mapWithKeys(fn($month) => [$month => false])
                ->toArray();

            $validated['waktu_pelaksanaan'] = $waktuPelaksanaanOrdered;
            $proker = Proker::findOrFail($proker->id);
            $proker->update($validated);

            DB::commit();

            toast('Berhasil memperbarui program kerja', 'success');
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error saat memperbarui Proker: ' . $e->getMessage());
            toast('Data proker gagal diperbarui pastikan semua data terisi', 'error');
        }
        return Redirect::back()->withInput();
    }

    public function updateWaktu(Request $request, $id)
    {
        $request->validate([
            'month' => 'required|string',
            'value' => 'required',
        ]);

        $proker = Proker::find($id);

        if (!$proker) {
            return response()->json(['success' => false, 'message' => 'Data not found'], 404);
        }

        $waktu = $proker->waktu_pelaksanaan ?? [];
        $waktu[$request->month] = filter_var($request->value, FILTER_VALIDATE_BOOLEAN);
        $proker->waktu_pelaksanaan = $waktu;
        $proker->save();

        return response()->json([
            'success' => true,
            'waktu_pelaksanaan' => $proker->waktu_pelaksanaan
        ]);
    }

    public function destroy($id)
    {
        DB::beginTransaction();
        try {
            $proker = Proker::findOrFail($id);
            $proker->delete();

            DB::commit();

            toast('Berhasil menghapus program kerja', 'success');
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error saat menghapus Proker: ' . $e->getMessage());
            toast('Data proker gagal ditambahkan pastikan semua data terisi', 'error');
        }
        return Redirect::back()->withInput();
    }
}
