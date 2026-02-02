@extends('layout.app')
@push('css')
    <style>
        .masonry {
            column-count: 2;
            column-gap: 1rem;
        }

        .masonry-item {
            break-inside: avoid;
            margin-bottom: 1.25rem;
        }

        @media (max-width: 768px) {
            .masonry {
                column-count: 1;
            }
        }
    </style>
@endpush
@section('content')
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <div class="d-md-flex align-items-center justify-content-between">
                        <div>
                            <h4 class="card-title">Edit Data Absensi Pengajian <span class="fw-bold text-secondary">
                                    {{ $pengajian->nama }} </span></h4>
                            <span>Perbarui Data Pengajian dan Absensi</span><br />
                            <a href="{{ url('/pengajian') }}"> <span class="text-secondary">Kembali</span></a>
                        </div>
                        <div class="div">
                            <a class="btn btn-success  ms-auto me-2"
                                href="{{ route('pengajian.exportAbsensi', $pengajian->id) }}">
                                <i class="fa fa-file-excel"></i>
                                Unduh Rekap
                            </a>
                        </div>
                    </div>
                </div>

                <div class="card-body">
                    <div class="masonry">
                        @include('pengajian.detail.tableAbsens', ['pengajian' => $pengajian])
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
