<?php

namespace App\Http\Controllers;

use App\Models\Generus;
use App\Models\Pekerjaan;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Redirect;

class GenerusController extends Controller
{
    public function store(Request $request): RedirectResponse
    {
        try {
            $this->GenerusValidate($request);
            DB::beginTransaction();
            $Generus = Generus::create([
                'nama' => $request->input('nama'),
                'tgllahir' => $request->input('tgllahir'),
                'gender' => $request->input('gender'),
                'id_desa' => $request->input('id_desa'),
                'id_kelompok' => $request->input('id_kelompok'),
                'id_kelas' => $request->input('id_kelas'),
                'pendidikan' => $request->input('pendidikan'),
                'status_pekerjaan' => $request->input('status_pekerjaan'),
                'detail_pekerjaan' => strtolower($request->input('detail_pekerjaan')),
                'nama_ibu' => $request->input('nama_ibu'),
                'hum_ibu' => $request->input('hum_ibu'),
                'nama_ayah' => $request->input('nama_ayah'),
                'hum_ayah' => $request->input('hum_ayah'),
                'status' => $request->input('status'),
                'foto_url' => $request->input('foto_url'),
                'keterangan' => $request->input('keterangan'),
            ]);
            // Himpun Pekerjaan Berdasarkan Input
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

            DB::commit();
        } catch (\Exception $e) {
            // sweat alert
            toast('Generus ' . $Generus->nama . ' gagal ditambahkan', 'error');

            // Log kesalahan
            Log::error('Error saat menambahkan Generus: ' . $e->getMessage());
        } finally {
            toast('Generus ' . $Generus->nama . ' berhasil diupdate', 'success');
            return Redirect::back()->withInput();
        }
    }

    function GenerusValidate(Request $request): void
    {
        $rule = [
            'nama' => 'required',
            'username' => 'required',
            'tgllahir' => 'required',
            'gender' => 'required',
            'id_kelas' => 'required',
            'pendidikan_terakhir' => 'required',
            'status_pekerjaan' => 'required',

        ];
        $I18N = [
            'nama.required' => 'Nama Lengkap harus diisi!',
            'username.required' => 'Nama Panggilan harus diisi!',
            'tgllahir.required' => 'Tanggal Lahir harus diisi!',
            'gender.required' => 'Jenis Kelamin harus diisi!',
            'id_kelas.required' => 'Kelas harus diisi!',
            'pendidikan_terakhir.required' => 'Pendidikan Terakhir harus diisi!',
            'status_pekerjaan.required' => 'Status Pekerjaan harus diisi!'
        ];

        $request->validate($rule, $I18N);
    }

    function update(Request $request): RedirectResponse
    {
        try {
            $this->GenerusValidate($request);
            DB::beginTransaction();
            $Generus = Generus::find($request->id);
            $Generus->update($request->all());
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
