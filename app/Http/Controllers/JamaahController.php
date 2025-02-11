<?php

namespace App\Http\Controllers;

use App\Exports\JamaahExports;
use App\Models\jamaah;
use App\Models\kelas;
use App\Models\Ortu;
use App\Models\Pekerjaan;
use Carbon\Carbon;
use Illuminate\Auth\Events\Validated;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Maatwebsite\Excel\Facades\Excel;

class JamaahController extends Controller
{

    public function export()
    {
        return Excel::download(new JamaahExports, 'jamaah.xlsx');
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
        $kelas = kelas::all();

        // untuk sweat alert hapus
        $title = 'Delete Kelompok!';
        $text = "Are you sure you want to delete?";
        confirmDelete($title, $text);

        return view('jamaah.index', compact('jamaah', 'orangtua', 'pekerjaan', 'kelas'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama' => 'required',
            'username' => 'required',
            'tgllahir' => 'required',
            'gender' => 'required',
            'kelas' => 'required',
            'ortu' => 'required',
            'status' => 'required',
        ], [
            'nama.required' => 'Nama Lengkap harus diisi!',
            'username.required' => 'Nama Panggilan harus diisi!',
            'tgllahir.required' => 'Tanggal Lahir harus diisi!',
            'gender.required' => 'Jenis Kelamin harus diisi!',
            'ortu.required' => 'Orang tua harus diisi!',
            'status.required' => 'Status harus diisi!'
        ]);

        try {
            // menyimpan foto
            // Ambil file yang diunggah
            $file = $request->file('foto');
            // Buat nama file unik
            $fileName = time() . '_' . $file->getClientOriginalName();
            // Tentukan path penyimpanan
            $filePath = public_path('assets/img/foto');
            // Pindahkan file ke folder yang ditentukan
            $file->move($filePath, $fileName);

            $jamaah = new jamaah();
            $jamaah->nama = $request->nama;
            $jamaah->username = $request->username;
            $jamaah->tgllahir = $request->tgllahir;
            $jamaah->gender = $request->gender;
            $jamaah->id_pekerjaan = $request->pekerjaan;
            $jamaah->status = $request->status;
            $jamaah->mubalight = $request->input('mubalight') ?? 0;
            $jamaah->haji = $request->input('haji') ?? 0;
            $jamaah->agniya = $request->input('agniya') ?? 0;
            $jamaah->duafa = $request->input('duafa') ?? 0;
            $jamaah->yatim = $request->input('yatim') ?? 0;
            $jamaah->mahasiswa = $request->input('mahasiswa') ?? 0;
            $jamaah->sarjana = $request->input('sarjana') ?? 0;
            $jamaah->foto = $fileName;
            $jamaah->id_kelas = $request->kelas;
            $jamaah->id_orangtua = $request->ortu;
            $jamaah->id_kelompok = Auth()->user()->id_kelompok;
            $jamaah->save();

            // sweat alert
            toast('Jamaah ' . $jamaah->username . ' berhasil ditambahkan', 'success');

            return redirect('/jamaah');
        } catch (\Exception $e) {
            // sweat alert
            toast('Jamaah ' . $jamaah->username . ' gagal ditambahkan', 'error');

            // Log kesalahan
            Log::error('Error saat menambahkan Jamaah: ' . $e->getMessage());

            // Hapus file jika sudah ada
            if (file_exists($filePath . '/' . $fileName)) {
                unlink($filePath . '/' . $fileName);
            }

            return redirect('/jamaah');
        }
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'nama' => 'required',
            'username' => 'required',
            'tgllahir' => 'required',
            'gender' => 'required',
            'ortu' => 'required',
            'status' => 'required',
        ], [
            'nama.required' => 'Nama Lengkap harus diisi!',
            'username.required' => 'Nama Panggilan harus diisi!',
            'tgllahir.required' => 'Tanggal Lahir harus diisi!',
            'gender.required' => 'Jenis Kelamin harus diisi!',
            'ortu.required' => 'Orang tua harus diisi!',
            'status.required' => 'Status harus diisi!'
        ]);

        try {
            // menyimpan foto
            // Ambil file yang diunggah
            $file = $request->file('foto');
            if ($file) {
                // Buat nama file unik
                $fileName = time() . '_' . $file->getClientOriginalName();
                // Tentukan path penyimpanan
                $filePath = public_path('assets/img/foto');
                // Pindahkan file ke folder yang ditentukan
                $file->move($filePath, $fileName);
            }

            $jamaah = jamaah::find($id);
            $jamaah->nama = $request->nama;
            $jamaah->username = $request->username;
            $jamaah->tgllahir = $request->tgllahir;
            $jamaah->gender = $request->gender;
            $jamaah->id_pekerjaan = $request->pekerjaan;
            $jamaah->status = $request->status;
            $jamaah->mubalight = $request->input('mubalight') ?? 0;
            $jamaah->haji = $request->input('haji') ?? 0;
            $jamaah->agniya = $request->input('agniya') ?? 0;
            $jamaah->duafa = $request->input('duafa') ?? 0;
            $jamaah->yatim = $request->input('yatim') ?? 0;
            $jamaah->mahasiswa = $request->input('mahasiswa') ?? 0;
            $jamaah->sarjana = $request->input('sarjana') ?? 0;
            if ($file) {
                $jamaah->foto = $fileName;
            }
            $jamaah->id_kelas = $request->kelas;
            $jamaah->id_orangtua = $request->ortu;
            $jamaah->save();

            // sweat alert
            toast('Jamaah ' . $jamaah->username . ' berhasil diupdate', 'success');

            return redirect('/jamaah');
        } catch (\Exception $e) {
            // sweat alert
            toast('Jamaah ' . $jamaah->username . ' gagal diupdate', 'error');

            // Log kesalahan
            Log::error('Error saat mengupdate jamaah: ' . $e->getMessage());

            if (isset($fileName)) {
                // Hapus file jika sudah ada
                if (file_exists($filePath . '/' . $fileName)) {
                    unlink($filePath . '/' . $fileName);
                }
            }

            return redirect('/jamaah');
        }
    }

    public function destroy($id)
    {
        $jamaah = Jamaah::find($id);

        // Ambil nama file dari database
        $fileName = $jamaah->foto;

        // Tentukan path penyimpanan (sesuaikan dengan path penyimpanan Anda)
        $filePath = public_path('assets/img/foto');

        // Hapus file jika sudah ada
        if ($fileName && file_exists($filePath . '/' . $fileName)) {
            unlink($filePath . '/' . $fileName);
        }

        $jamaah->delete();

        // Sweet alert
        toast('Jamaah ' . $jamaah->username . ' berhasil dihapus', 'success');

        return redirect('/jamaah');
    }
}
