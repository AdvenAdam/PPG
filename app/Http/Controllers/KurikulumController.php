<?php

namespace App\Http\Controllers;

use App\Models\Kurikulum;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Storage;

class KurikulumController extends Controller
{
    function index()
    {
        $kurikulums = Kurikulum::all();

        // untuk sweat alert hapus
        $title = 'Delete Kurikulum!';
        $text = "Are you sure you want to delete?";
        confirmDelete($title, $text);

        return view('kurikulum.index', compact('kurikulums'));
    }

    function store(Request $request)
    {
        $this->validateKurikulum($request);

        try {
            // Generate a unique filename with original name + timestamp
            $originalName = $request->file->getClientOriginalName();
            $timestamp = time();
            $extension = $request->file->extension();
            $fileName = pathinfo($originalName, PATHINFO_FILENAME) . '-' . $timestamp . '.' . $extension;

            // Store file in 'kurikulum' folder within 'storage/app/public'
            $filePath = $request->file->storeAs('public/kurikulum', $fileName);

            // Prepare the DB record
            $kurikulums = new Kurikulum();
            $kurikulums->desc = $request->deskripsi;
            $kurikulums->file_name = $fileName;
            $kurikulums->file_path = Storage::url($filePath);
            $kurikulums->file_ext = $extension;
            $kurikulums->save();

            // Sweet alert
            toast('Kurikulum ' . $kurikulums->desc . ' berhasil ditambahkan', 'success');

            return redirect('/kurikulum');
        } catch (\Throwable $th) {
            Log::error($th);
            toast('Error ' . $th->getMessage(), 'error');
            return back();
        }
    }

    function validateKurikulum(Request $request)
    {
        try {
            $validatedData = $request->validate([
                'deskripsi' => 'required|string|max:255',
                'file' => 'required|file|max:6144', // Max size = 6144 KB = 6MB
            ], [
                'deskripsi.required' => 'Deskripsi harus diisi',
                'deskripsi.max' => 'Deskripsi maksimal 255 karakter',
                'file.required' => 'File harus diisi',
                'file.max' => 'Ukuran file maksimal 6MB',
            ]);
            if (!$validatedData) {
                throw new \Illuminate\Validation\ValidationException($validatedData);
            }
        } catch (\Illuminate\Validation\ValidationException $e) {
            toast('Data kurikulum gagal ditambahkan <br/>' . $e->getMessage(), 'error');
            throw $e;
        }
    }

    function destroy(Request $request): RedirectResponse
    {
        try {
            DB::beginTransaction();
            $kurikulum = Kurikulum::find($request->id);
            $storagePath = str_replace('/storage/', 'public/', $kurikulum->file_path);
            if (Storage::exists($storagePath)) {
                Storage::delete($storagePath);
            }
            $kurikulum->delete();
            DB::commit();
        } catch (\Exception $e) {
            // sweat alert
            toast('Kurikulum gagal dihapus', 'error');

            // Log kesalahan
            Log::error('Error saat menghapus kurikulum: ' . $e->getMessage());
        } finally {
            toast('Kurikulum berhasil dihapus', 'success');
            return Redirect::back()->withInput();
        }
    }
    function download(Request $request)
    {
        try {
            $kurikulum = Kurikulum::find($request->id);
            $storagePath = str_replace('/storage/', 'public/', $kurikulum->file_path);
            if (Storage::exists($storagePath)) {
                return Storage::download($storagePath);
            } else {
                toast('Kurikulum gagal diunduh', 'error');
            }
        } catch (\Exception $e) {
            // sweat alert
            toast('Kurikulum gagal diunduh', 'error');

            // Log kesalahan
            Log::error('Error saat mengunduh kurikulum: ' . $e->getMessage());
        }
    }
}
