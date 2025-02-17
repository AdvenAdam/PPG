<?php

namespace App\Http\Controllers;

use App\Exports\GenerusExports;
use App\Imports\GenerusImport;
use App\Models\Desa;
use App\Models\Generus;
use App\Models\kelas;
use App\Models\Kelompok;
use App\Models\Ortu;
use App\Models\Pekerjaan;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Redirect;
use Maatwebsite\Excel\Facades\Excel;

class GenerusController extends Controller
{

    public function export()
    {
        return Excel::download((new GenerusExports), 'generus.xlsx');
    }

    function import()
    {
        $import = new GenerusImport();
        $import->import('users.xlsx');

        foreach ($import->failures() as $failure) {
            $failure->row(); // row that went wrong
            $failure->attribute(); // either heading key (if using heading row concern) or column index
            $failure->errors(); // Actual error messages from Laravel validator
            $failure->values(); // The values of the row that has failed.
        }
    }

    public function index()
    {
        $jamaah = DB::table('jamaah')
            ->join('pekerjaan', 'jamaah.id_pekerjaan', '=', 'pekerjaan.id')
            ->join('kelompok', 'jamaah.id_kelompok', '=', 'kelompok.id')
            ->select('jamaah.*', 'pekerjaan.nama as pekerjaan', 'kelompok.nama as kelompok')
            ->get();

        $orangtua = Ortu::all();
        $pekerjaan = Pekerjaan::all();

        // untuk sweat alert hapus
        $title = 'Delete Data!';
        $text = "Are you sure you want to delete?";

        $kelas = kelas::all();
        $kelompok = Kelompok::all();
        $desa = Desa::all();

        confirmDelete($title, $text);
        $generus = DB::table('generus')
            ->join('kelas', 'generus.id_kelas', '=', 'kelas.id')
            ->join('kelompok', 'generus.id_kelompok', '=', 'kelompok.id')
            ->join('desa', 'generus.id_desa', '=', 'desa.id')
            ->select('generus.*', 'kelas.nama as kelas', 'kelompok.nama as kelompok', 'desa.nama as desa')
            ->get();
        return view('generus.index', compact('generus', 'jamaah', 'orangtua', 'pekerjaan', 'kelas', 'kelompok', 'desa'));
    }

    public function store(Request $request): RedirectResponse
    {
        $this->GenerusValidate($request);
        try {
            DB::beginTransaction();
            $file = $request->file('foto_url');
            if ($file) {
                // Buat nama file unik
                $fileName = time() . '_' . $file->hashName();
                // Tentukan path penyimpanan
                $filePath = public_path('assets/img/foto');
                // Pindahkan file ke folder yang ditentukan
                $file->move($filePath, $fileName);
            }
            Generus::create([
                'nama' => $request->input('nama'),
                'tgllahir' => $request->input('tgllahir'),
                'gender' => $request->input('gender'),
                'id_desa' => $request->input('id_desa'),
                'id_kelompok' => $request->input('id_kelompok'),
                'id_kelas' => $request->input('id_kelas'),
                'pendidikan_terakhir' => $request->input('pendidikan_terakhir'),
                'status_pekerjaan' => $request->input('status_pekerjaan'),
                'detail_pekerjaan' => strtolower($request->input('detail_pekerjaan')),
                'nama_ibu' => $request->input('nama_ibu'),
                'hum_ibu' => $request->input('hum_ibu') ?? 0,
                'nama_bapak' => $request->input('nama_bapak'),
                'hum_bapak' => $request->input('hum_bapak') ?? 0,
                'status' => $request->input('status'),
                'foto_url' => $file ? $fileName : null,
                'keterangan' => $request->input('keterangan'),
            ]);

            DB::commit();
            toast('Data generus berhasil diupdate', 'success');
        } catch (\Exception $e) {
            // sweat alert
            dd($e);
            Log::error('Error saat menambahkan Generus: ' . $e->getMessage());

            toast('Data generus gagal ditambahkan', 'error');
            if (isset($fileName)) {
                // Hapus file jika sudah ada
                if (file_exists($filePath . '/' . $fileName)) {
                    unlink($filePath . '/' . $fileName);
                }
            }
            // Log kesalahan
        }
        return Redirect::back()->withInput();
    }

    function GenerusValidate(Request $request)
    {
        $rules = [
            'nama' => 'required',
            'tgllahir' => 'required',
            'gender' => 'required',
            'id_kelas' => 'required',
            'pendidikan_terakhir' => 'required',
            'status_pekerjaan' => 'required',
            'foto_url' => 'image|mimes:jpeg,png,jpg|max:3072',
        ];
        $messages = [
            'nama.required' => 'Nama Lengkap harus diisi!',
            'tgllahir.required' => 'Tanggal Lahir harus diisi!',
            'gender.required' => 'Jenis Kelamin harus diisi!',
            'id_kelas.required' => 'Kelas harus diisi!',
            'pendidikan_terakhir.required' => 'Pendidikan Terakhir harus diisi!',
            'status_pekerjaan.required' => 'Status Pekerjaan harus diisi!',
            'foto_url.image' => 'Format gambar harus berupa jpeg, png, jpg',
            'foto_url.max' => 'Ukuran gambar maksimal 3MB',
        ];

        $request->validate($rules, $messages);
    }

    function update(Request $request, $id): RedirectResponse
    {
        try {
            $this->GenerusValidate($request);
            DB::beginTransaction();
            $Generus = Generus::find($id);
            $file = $request->file('foto_url');
            if ($file) {
                // Hapus file lama
                if ($Generus->foto_url) {
                    $filePath = public_path('assets/img/foto');
                    $fileName = basename($Generus->foto_url);
                    if (file_exists($filePath . '/' . $fileName)) {
                        unlink($filePath . '/' . $fileName);
                    }
                }
                // Buat nama file unik
                $fileName = time() . '_' . $file->hashName();
                // Tentukan path penyimpanan
                $filePath = public_path('assets/img/foto');
                // Pindahkan file ke folder yang ditentukan
                $file->move($filePath, $fileName);
            }
            $Generus->update([
                'nama' => $request->input('nama'),
                'tgllahir' => $request->input('tgllahir'),
                'gender' => $request->input('gender'),
                'id_desa' => $request->input('id_desa'),
                'id_kelompok' => $request->input('id_kelompok'),
                'id_kelas' => $request->input('id_kelas'),
                'pendidikan_terakhir' => $request->input('pendidikan_terakhir'),
                'status_pekerjaan' => $request->input('status_pekerjaan'),
                'detail_pekerjaan' => strtolower($request->input('detail_pekerjaan')),
                'nama_ibu' => $request->input('nama_ibu'),
                'hum_ibu' => $request->input('hum_ibu') ?? 0,
                'nama_bapak' => $request->input('nama_bapak'),
                'hum_bapak' => $request->input('hum_bapak') ?? 0,
                'status' => $request->input('status'),
                'foto_url' => $file ? $fileName : null,
                'keterangan' => $request->input('keterangan'),
            ]);

            $userChangePekerjaan = $Generus->detail_pekerjaan != strtolower($request->input('detail_pekerjaan'));

            if ($userChangePekerjaan) {
                $pekerjaanExist = Pekerjaan::where('nama', strtolower($request->input('detail_pekerjaan')))->first();
                if (!$pekerjaanExist) {
                    Pekerjaan::create([
                        'nama' => strtolower($request->input('detail_pekerjaan')),
                        'count' => 1
                    ]);
                } else {
                    $pekerjaanExist->count = $pekerjaanExist->count + 1;
                    $pekerjaanExist->save();
                }
            }

            DB::commit();
        } catch (\Exception $e) {
            // sweat alert
            toast('Generus ' . $Generus->nama . ' gagal diupdate', 'error');

            if (isset($fileName)) {
                // Hapus file jika sudah ada
                if (file_exists($filePath . '/' . $fileName)) {
                    unlink($filePath . '/' . $fileName);
                }
            }

            // Log kesalahan
            Log::error('Error saat mengupdate Generus: ' . $e->getMessage());
        } finally {
            toast('Generus ' . $Generus->nama . ' berhasil diupdate', 'success');
            return Redirect::back()->withInput();
        }
    }

    function destroy(Request $request): RedirectResponse
    {
        try {
            DB::beginTransaction();
            $Generus = Generus::find($request->id);
            if ($Generus->detail_pekerjaan != null && $Generus->detail_pekerjaan != '') {
                $pekerjaanExist = Pekerjaan::where('nama', $Generus->detail_pekerjaan)->first();
                if ($pekerjaanExist) {
                    $pekerjaanExist->count = $pekerjaanExist->count - 1;
                    $pekerjaanExist->save();
                }
            }
            $Generus->delete();
            DB::commit();
        } catch (\Exception $e) {
            // sweat alert
            toast('Generus ' . $Generus->nama . ' gagal dihapus', 'error');

            // Log kesalahan
            Log::error('Error saat menghapus Generus: ' . $e->getMessage());
        } finally {
            toast('Generus ' . $Generus->nama . ' berhasil dihapus', 'success');
            return Redirect::back()->withInput();
        }
    }
}
