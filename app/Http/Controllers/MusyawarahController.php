<?php

namespace App\Http\Controllers;

use App\Models\Musyawarah;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Storage;

class MusyawarahController extends Controller
{
    function index()
    {
        $musyawarahs = Musyawarah::orderBy('waktu_tanggal_mulai')->get();

        // untuk sweat alert hapus
        $title = 'Delete Musyawarah!';
        $text = "Are you sure you want to delete?";
        confirmDelete($title, $text);

        return view('musyawarah.index', compact('musyawarahs'));
    }

    function store(Request $request)
    {
        $this->validateMusyawarah($request);

        try {
            // Generate a unique filename with original name + timestamp
            $originalName = $request->file->getClientOriginalName();
            $timestamp = time();
            $extension = $request->file->extension();
            $fileName = pathinfo($originalName, PATHINFO_FILENAME) . '-' . $timestamp . '.' . $extension;

            // Store file in 'musyawarah' folder within 'storage/app/public'
            $filePath = $request->file->storeAs('public/musyawarah', $fileName);

            // Prepare the DB record
            $musyawarahs = new Musyawarah();
            $musyawarahs->desc = $request->deskripsi;
            $musyawarahs->waktu_tanggal_mulai = $request->waktu_tanggal_mulai;
            $musyawarahs->file_name = $fileName;
            $musyawarahs->file_path = Storage::url($filePath);
            $musyawarahs->file_ext = $extension;
            $musyawarahs->save();

            // Sweet alert
            toast('Musyawarah ' . $musyawarahs->desc . ' berhasil ditambahkan', 'success');

            return redirect('/musyawarah');
        } catch (\Throwable $th) {
            Log::error($th);
            toast('Error ' . $th->getMessage(), 'error');
            return back();
        }
    }

    function validateMusyawarah(Request $request, $edit = false)
    {
        try {
            $rules = [
                'deskripsi' => 'required|string|max:255',
                'waktu_tanggal_mulai' => 'required',
            ];

            if (!$edit) {
                $rules['file'] = 'required|file|max:6144';
            }

            $validatedData = $request->validate($rules, [
                'deskripsi.required' => 'Deskripsi harus diisi',
                'deskripsi.max' => 'Deskripsi maksimal 255 karakter',
                'waktu_tanggal_mulai.required' => 'Tanggal Waktu musyawarah harus diisi',
                'file.required' => 'File harus diisi',
                'file.max' => 'Ukuran file maksimal 6MB',
            ]);
            if (!$validatedData) {
                throw new \Illuminate\Validation\ValidationException($validatedData);
            }
        } catch (\Illuminate\Validation\ValidationException $e) {
            toast('Data musyawarah gagal ditambahkan <br/>' . $e->getMessage(), 'error');
            throw $e;
        }
    }

    function destroy(Request $request): RedirectResponse
    {
        try {
            DB::beginTransaction();
            $musyawarah = Musyawarah::find($request->id);
            $storagePath = str_replace('/storage/', 'public/', $musyawarah->file_path);
            if (Storage::exists($storagePath)) {
                Storage::delete($storagePath);
            }
            $musyawarah->delete();
            DB::commit();
        } catch (\Exception $e) {
            // sweat alert
            toast('Musyawarah gagal dihapus', 'error');

            // Log kesalahan
            Log::error('Error saat menghapus musyawarah: ' . $e->getMessage());
        } finally {
            toast('Musyawarah berhasil dihapus', 'success');
            return Redirect::back()->withInput();
        }
    }
    function download(Request $request)
    {
        try {
            $musyawarah = Musyawarah::find($request->id);
            $storagePath = str_replace('/storage/', 'public/', $musyawarah->file_path);
            if (Storage::exists($storagePath)) {
                return Storage::download($storagePath);
            } else {
                toast('Musyawarah gagal diunduh', 'error');
            }
        } catch (\Exception $e) {
            // sweat alert
            toast('Musyawarah gagal diunduh', 'error');

            // Log kesalahan
            Log::error('Error saat mengunduh musyawarah: ' . $e->getMessage());
        }
    }

    public function update(Request $request, $id)
    {
        $this->validateMusyawarah($request, true);

        try {
            $musyawarah = Musyawarah::findOrFail($id);

            // update basic fields
            $musyawarah->desc = $request->deskripsi;
            $musyawarah->waktu_tanggal_mulai = $request->waktu_tanggal_mulai;

            // handle file upload if new file is provided
            if ($request->hasFile('file')) {
                // delete old file if exists
                if ($musyawarah->file_path) {
                    $storagePath = str_replace('/storage/', 'public/', $musyawarah->file_path);
                    if (Storage::exists($storagePath)) {
                        Storage::delete($storagePath);
                    }
                }

                // Generate a unique filename
                $originalName = $request->file->getClientOriginalName();
                $timestamp = time();
                $extension = $request->file->extension();
                $fileName = pathinfo($originalName, PATHINFO_FILENAME) . '-' . $timestamp . '.' . $extension;

                // Store file in 'public/musyawarah'
                $filePath = $request->file->storeAs('public/musyawarah', $fileName);

                // update file fields
                $musyawarah->file_name = $fileName;
                $musyawarah->file_path = Storage::url($filePath);
                $musyawarah->file_ext = $extension;
            }

            $musyawarah->save();

            // Sweet alert
            toast('Musyawarah ' . $musyawarah->desc . ' berhasil diperbarui', 'success');

            return redirect('/musyawarah');
        } catch (\Throwable $th) {
            Log::error($th);
            toast('Error ' . $th->getMessage(), 'error');
            return back();
        }
    }
}
