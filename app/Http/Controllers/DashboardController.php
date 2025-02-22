<?php

namespace App\Http\Controllers;

use App\Models\Desa;
use App\Models\Generus;
use App\Models\kelas;
use App\Models\Kelompok;
use App\Models\User;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        $generalData = [
            'desa' => Desa::count(),
            'generus' => Generus::count(),
            'kelompok' => Kelompok::count(),
            'admin' => User::count(),
        ];
        $generusByClass = [];
        $kelas = kelas::all();
        foreach ($kelas as $class) {
            $generusByClass[$class->nama]['L'] = Generus::where('id_kelas', '=', $class->id)->where('gender', '=', 'L')->count();
            $generusByClass[$class->nama]['P'] = Generus::where('id_kelas', '=', $class->id)->where('gender', '=', 'P')->count();
            $generusByClass[$class->nama]['total'] = Generus::where('id_kelas', '=', $class->id)->count();
        }

        $generusByDesa = [];
        $desa = Desa::all();
        foreach ($desa as $value) {
            foreach ($kelas as $class) {
                $generusByDesa[$value->nama][$class->nama]['L'] = Generus::where('id_desa', '=', $value->id)->where('id_kelas', '=', $class->id)->where('gender', '=', 'L')->count();
                $generusByDesa[$value->nama][$class->nama]['P'] = Generus::where('id_desa', '=', $value->id)->where('id_kelas', '=', $class->id)->where('gender', '=', 'P')->count();
            }
            $generusByDesa[$value->nama]['total'] = Generus::where('id_desa', '=', $value->id)->count();
        }
        $generusByEdu = Generus::all()->groupBy('pendidikan_terakhir')->map(function ($group) {
            return $group->count();
        })->toArray();
        $generusByJob = Generus::all()->groupBy('status_pekerjaan')->map(function ($group) {
            return $group->count();
        });
        $generusTugas = [];
        $generusTugas['mubalight'] = Generus::where('gender', '=', 'L')->where('mubalight', '=', 1)->count();
        $generusTugas['mubalighot'] = Generus::where('gender', '=', 'P')->where('mubalight', '=', 1)->count();
        $generusTugas['blm-tidak-tugas'] = Generus::where('mubalight', '!=', 1)->count();
        return view(
            'dashboard',
            compact(
                'generalData',
                'generusByClass',
                'desa',
                'kelas',
                'generusByDesa',
                'generusByEdu',
                'generusByJob',
                'generusTugas'
            )
        );
    }
}
