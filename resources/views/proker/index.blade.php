@extends('layout.app')

@section('content')
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <div class="d-flex align-items-center">
                        <div class="col">
                            <h4 class="card-title">Data Program Kerja</h4>
                            <span>Input Data Program Kerja</span>
                        </div>
                        <button class="btn btn-primary btn-round ms-auto" data-bs-toggle="modal"
                            data-bs-target="#inputProkerModal">
                            <i class="fa fa-plus"></i>
                            Tambah
                        </button>
                    </div>
                </div>
                <div class="card-body">
                    @include('proker.addModal')
                    @include('proker.table')
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
