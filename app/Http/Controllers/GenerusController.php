<?php

namespace App\Http\Controllers;

use App\Exports\GenerusErrorImport;
use App\Exports\GenerusExports;
use App\Exports\GenerusTemplate;
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
use PhpOffice\PhpSpreadsheet\Style\NumberFormat;

class GenerusController extends Controller
{
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
        confirmDelete($title, $text);

        $kelas = kelas::all();
        $kelompok = Kelompok::all();
        $desa = Desa::all();

        $user = auth()->user();
        $role = $user->jabatan;

        if ($role === 'desa' || $role === 'kelompok') {
            $desa = Desa::where('id', '=', $user->id_desa)->get();
        }

        $generus = DB::table('generus')
            ->join('kelas', 'generus.id_kelas', '=', 'kelas.id')
            ->join('kelompok', 'generus.id_kelompok', '=', 'kelompok.id')
            ->join('desa', 'generus.id_desa', '=', 'desa.id')
            ->select('generus.*', 'kelas.nama as kelas', 'kelompok.nama as kelompok', 'desa.nama as desa')
            ->when($role === 'kelompok', function ($query) use ($user) {
                $query->where('generus.id_kelompok', '=', $user->id_kelompok);
            })
            ->when($role === 'desa', function ($query) use ($user) {
                $query->where('generus.id_desa', '=', $user->id_desa);
            })
            ->orderBy('generus.updated_at', 'desc')
            ->get();


        return view('generus.index', compact('generus', 'kelas', 'kelompok', 'desa', 'role'));
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
                'foto_url' => $file ? $fileName : 'user.png',
                'keterangan' => $request->input('keterangan'),
            ]);

            DB::commit();
            toast('Data generus berhasil diupdate', 'success');
        } catch (\Exception $e) {
            // sweat alert
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
        $this->GenerusValidate($request);
        try {
            $filePath = public_path('assets/img/foto');
            DB::beginTransaction();
            $Generus = Generus::find($id);
            $file = $request->file('foto_url');
            if ($file) {
                // Hapus file lama
                if ($Generus->foto_url) {
                    $fileName = basename($Generus->foto_url);
                    if (file_exists($filePath . '/' . $fileName)) {
                        unlink($filePath . '/' . $fileName);
                    }
                }
                // Buat nama file unik
                $fileName = time() . '_' . $file->hashName();

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
                'foto_url' => $file ? $fileName : $Generus->foto_url,
                'keterangan' => $request->input('keterangan'),
            ]);
            DB::commit();
            toast('Data generus berhasil diupdate', 'success');
        } catch (\Exception $e) {
            // sweat alert
            Log::error('Error saat menambahkan Generus: ' . $e->getMessage());

            toast('Data generus gagal ditambahkan', 'error');
            if (isset($fileName)) {
                // Hapus file jika sudah ada
                if (file_exists($filePath . '/' . $fileName)) {
                    unlink($filePath . '/' . $fileName);
                }
            }
        }

        return Redirect::back()->withInput();
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


    public function export()
    {
        return Excel::download((new GenerusExports), 'generus.xlsx');
    }
    public function exportTemplate()
    {
        $role = auth()->user()->jabatan;
        // dd($role);
        if ($role == 'daerah') {
            $path = storage_path('Excel/generusTemplate_daerah.xlsx');
            return response()->download($path);
        } elseif ($role == 'desa') {
            $deskel = Desa::where('id', auth()->user()->id_desa)->first()->nama;
            return Excel::download(new GenerusTemplate($deskel), "generusTemplate_{$role}_{$deskel}.xlsx");
        } else {
            $deskel = Kelompok::where('id', auth()->user()->id_kelompok)->first()->nama;
            return Excel::download(new GenerusTemplate($deskel), "generusTemplate_{$role}_{$deskel}.xlsx");
        }
    }

    public function import(Request $request)
    {
        try {
            $request->validate([
                'file' => 'required|mimes:csv,xlsx'
            ]);

            Excel::import(new GenerusImport, $request->file('file')); // Pass the file to the import

        } catch (\Maatwebsite\Excel\Exceptions\NoTypeDetectedException $e) {
            toast('File yang diupload tidak sesuai format.', 'error');
        } catch (\Exception $e) {
            // Log the exception for debugging
            toast('Terjadi kesalahan saat mengimport file. ' . $e->getMessage(), 'error');
        }
        return redirect()->back();
    }
}
