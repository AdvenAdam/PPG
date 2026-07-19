@extends('layout.app')

@section('content')
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <div class="d-flex align-items-center">
                        <div class="col">
                            <h4 class="card-title">Data Sarpras</h4>
                            <span>Input Data Sarpras</span>
                        </div>
                    <div class="ms-auto d-flex gap-2">
                        <a href="{{ route('sarpras.export') }}" class="btn btn-success btn-round">
                            <i class="fa fa-file-excel"></i>
                            Unduh Excel
                        </a>
                        <button class="btn btn-primary btn-round" data-bs-toggle="modal" data-bs-target="#addRowModal">
                            <i class="fa fa-plus"></i>
                            Tambah
                        </button>
                    </div>
                    </div>
                </div>
                <div class="card-body">
                    @include('sarpras.addModal')
                    @include('sarpras.table')

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
