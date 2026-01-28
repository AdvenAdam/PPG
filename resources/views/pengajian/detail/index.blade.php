@extends('layout.app')

@section('content')
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <div class="d-flex align-items-center justify-content-between">
                        <div>
                            <h4 class="card-title">Edit Data Absensi Pengajian <span class="fw-bold text-secondary">
                                    {{ $pengajian->nama }} </span></h4>
                            <span>Perbarui Data Pengajian dan Absensi</span><br />
                            <a href="{{ url('/pengajian') }}"> <span class="text-secondary">Kembali</span></a>
                        </div>
                    </div>
                </div>

                <div class="card-body mx-3">
                    <div class="row gx-2 ">
                        @include('pengajian.detail.tableAbsens', ['pengajian' => $pengajian])
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
