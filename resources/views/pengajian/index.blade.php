@extends('layout.app')

@section('content')
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <div class="d-flex flex-column flex-md-row align-items-md-center gap-2">
                        <div class="col">
                            <h4 class="card-title">Data Pengajian</h4>
                            <span>Input Data Pengajian</span>
                        </div>
                        <div class="col text-end">
                            <div class="btn-group dropdown">
                                <button class="btn btn-success  ms-auto me-2 dropdown-toggle show" type="button"
                                    data-bs-toggle="dropdown" aria-expanded="true">
                                    <i class="fa fa-file-excel"></i>
                                    Unduh Rekap
                                </button>
                                <ul class="dropdown-menu " role="menu" data-popper-placement="bottom-start"
                                    style="position: absolute; inset: 0px auto auto 0px; margin: 0px; transform: translate3d(0px, 42.6667px, 0px);">
                                    @foreach ($tahun as $value)
                                        <li>
                                            <a class="dropdown-item"
                                                href="{{ url('/pengajian/export/' . $value) }}">{{ $value }}</a>
                                        </li>
                                    @endforeach
                                </ul>
                            </div>
                            <button class="btn btn-primary btn-round ms-auto" data-bs-toggle="modal"
                                data-bs-target="#addRowModal">
                                <i class="fa fa-plus"></i>
                                Tambah
                            </button>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    @include('pengajian.addModal')
                    @include('pengajian.table')

                </div>
            </div>
        </div>
    </div>
    <script>
        // fungsi untuk close modal
        function closemodal() {
            $(".modal").modal("hide");
        }
    </script>
@endsection
