@extends('layout.app')

@section('content')
    <div class="page-inner" style="background: linear-gradient(-45deg,#06418e,#1572e8)!important">
        <div class="d-flex align-items-left align-items-md-center flex-column flex-md-row mb-3">
            <div class="text-white py-5">
                <h3 class="fw-bold mb-2">Dashboard</h3>
                <h6 class="op-7 mb-2">Penggerak Pembina Generus (PPG)</h6>
            </div>
        </div>
    </div>
    <div class="allChart mb-5">
        {{-- NOTE : General Data  --}}
        <div class="px-3" style="margin-top: -50px">
            <div class="row">
                <div class="col-sm-6 col-md-4">
                    <div class="card card-stats card-round">
                        <div class="card-body">
                            <div class="row align-items-center">
                                <div class="col-icon">
                                    <div class="icon-big text-center icon-primary bubble-shadow-small">
                                        <i class="fas fa-users"></i>
                                    </div>
                                </div>
                                <div class="col col-stats ms-3 ms-sm-0">
                                    <div class="numbers">
                                        <p class="card-category">Generus</p>
                                        <h4 class="card-title">{{ $generalData['generus'] }}</h4>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-sm-6 col-md-4">
                    <div class="card card-stats card-round">
                        <div class="card-body">
                            <div class="row align-items-center">
                                <div class="col-icon">
                                    <div class="icon-big text-center icon-info bubble-shadow-small">
                                        <i class="fas fa-user-check"></i>
                                    </div>
                                </div>
                                <div class="col col-stats ms-3 ms-sm-0">
                                    <div class="numbers">
                                        <p class="card-category">Admin</p>
                                        <h4 class="card-title">{{ $generalData['admin'] }}</h4>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-sm-6 col-md-4">
                    <div class="card card-stats card-round">
                        <div class="card-body">
                            <div class="row align-items-center">
                                <div class="col-icon">
                                    <div class="icon-big text-center icon-success bubble-shadow-small">
                                        <i class="fas fa-compass"></i>
                                    </div>
                                </div>
                                <div class="col col-stats ms-3 ms-sm-0">
                                    <div class="numbers">
                                        <p class="card-category">Kelompok | Desa</p>
                                        <h4 class="card-title">
                                            {{ $generalData['kelompok'] }}
                                            <span class="card-category"> Kelompok </span> |
                                            {{ $generalData['desa'] }}
                                            <span class="card-category"> Desa</span>
                                        </h4>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </div>
        {{-- NOTE : Generus By Class --}}
        <div class="px-3" style="margin-bottom: 30px ">
            <h3 class="fw-bold mb-2">Data Generus Berdasarkan Kelas</h3>
            <div class="row gap-md-0 gap-3">
                <div class="col-12 col-md-5" style="max-height: 45vh; min-height: 250px;">
                    <div class="card card-stats card-round h-100 mx-auto">
                        <div class="card-body h-100 d-flex align-items-center justify-content-center">
                            <canvas id="GenerusByClassOverall"></canvas>
                        </div>
                    </div>
                </div>
                <div class="col-12 col-md-7" style="max-height: 45vh; min-height: 250px;">
                    <div class="card card-stats card-round h-100">
                        <div class="card-body h-100 d-flex flex-column justify-content-center">
                            <canvas id="GenerusByClass"></canvas>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        {{-- NOTE : Generus By Job --}}
        <div class="px-3 pb-3">
            <div class="row gap-lg-0 gap-5 pb-3 ">
                <div class="col-12 col-lg-8" style="max-height: 50vh; min-height: 250px;">
                    <h3 class="fw-bold mb-2">Generus Berdasarkan Pendidikan</h3>
                    <div class="card card-stats card-round h-100 mx-auto">
                        <div class="card-body h-100 d-flex align-items-center justify-content-center">
                            <canvas id="GenerusByEducation"></canvas>
                        </div>
                    </div>
                </div>
                <div class="col-12 col-lg-4" style="max-height: 50vh; min-height: 250px;">
                    <h3 class="fw-bold my-2">Generus Berdasarkan Pekerjaan</h3>
                    <div class="card card-stats card-round h-100">
                        <div class="card-body h-100 d-flex flex-column align-items-center justify-content-center">
                            <canvas id="GenerusByJob"></canvas>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row gap-md-0 gap-2 mt-5">
                <h3 class="fw-bold my-2">Generus Yang Mubaligh / Mubalighot</h3>
                <div class="col-12 col-md-4">
                    <div class="card card-info">
                        <div class="card-body skew-shadow">
                            <h1>{{ $generusTugas['mubalight'] }}<span class="card-category"> Generus </span></h1>
                            <h5 class="op-8">Mubalight</h5>
                            <div class="pull-right">
                                <h3 class="fw-bold op-8">
                                    {{ $generalData['generus'] ? number_format(($generusTugas['mubalight'] / $generalData['generus']) * 100, 0) : 0 }}
                                    %
                                </h3>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-12 col-md-4">
                    <div class="card card-secondary">
                        <div class="card-body bubble-shadow">
                            <h1>{{ $generusTugas['mubalighot'] }}<span class="card-category"> Generus </span></h1>
                            <h5 class="op-8">Mubalighot</h5>
                            <div class="pull-right">
                                <h3 class="fw-bold op-8">
                                    {{ $generalData['generus'] ? number_format(($generusTugas['mubalighot'] / $generalData['generus']) * 100, 0) : 0 }}
                                    %
                                </h3>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-12 col-md-4">

                    <div class="card card-primary">
                        <div class="card-body curves-shadow">
                            <h1>{{ $generusTugas['blm-tidak-tugas'] }}<span class="card-category"> Generus </span>
                            </h1>
                            <h5 class="op-8">Belum / Tidak Tugas</h5>
                            <div class="pull-right">
                                <h3 class="fw-bold op-8">
                                    {{ $generalData['generus'] ? number_format(($generusTugas['blm-tidak-tugas'] / $generalData['generus']) * 100, 0) : 0 }}
                                    %
                                </h3>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        {{-- NOTE : Generus By Desa --}}
        <div class="px-3">
            <div class="row gap-md-0 gap-3">
                <div class="col-md-12">
                    <div class="card card-round">
                        <div class="card-header">
                            <div class="card-head-row card-tools-still-right">
                                <div class="card-title">
                                    <h3 class="fw-bold mb-2">Data Generus Berdasarkan Persebaran</h3>
                                </div>
                            </div>
                        </div>
                        <div class="card-body p-0">
                            <div class="table-responsive">
                                <!-- Projects table -->
                                <table class="table align-items-center table-bordered mb-0">
                                    <thead class="thead-light">
                                        <tr>
                                            <th scope="col" rowspan="2">Desa</th>
                                            @foreach ($kelas as $value)
                                                <th scope="col" colspan="2" style="width: 12%">{{ $value->nama }}
                                                </th>
                                            @endforeach
                                            <th scope="col" rowspan="2">Total</th>
                                        </tr>
                                        <tr>
                                            @foreach ($kelas as $value)
                                                <th style="width: 6%">L</th>
                                                <th style="width: 6%">P</th>
                                            @endforeach
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($desa as $value)
                                            <tr>
                                                <th scope="row">
                                                    {{ $value['nama'] }}
                                                </th>
                                                @foreach ($kelas as $class)
                                                    <td>{{ $generusByDesa[$value->nama][$class->nama]['L'] }}
                                                    </td>
                                                    <td>{{ $generusByDesa[$value->nama][$class->nama]['P'] }}
                                                    </td>
                                                @endforeach
                                                <td>{{ $generusByDesa[$value->nama]['total'] }}</td>
                                            </tr>
                                        @endforeach

                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>

    <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.8/dist/chart.umd.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chartjs-plugin-datalabels@2.2.0"></script>
    <script src="{{ asset('assets/js/dashboard-charts.js') }}"></script>
    <script>
        GenerusByClassChart(@json($generusByClass));
        GenerusByClassOverallChart(@json($generusByClass));
        GenerusByEducationChart(@json($generusByEdu));
        GenerusByJobChart(@json($generusByJob));
    </script>
@endsection
