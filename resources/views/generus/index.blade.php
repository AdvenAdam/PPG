@extends('layout.app')

@section('content')

    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <div class="d-flex flex-column flex-md-row align-items-center gap-3">
                        <div class="col">
                            <h4 class="card-title">Data Generus</h4>
                            <span>Penginputan Data Generus</span>
                        </div>
                        <div class="d-flex flex-column flex-md-row gap-3 align-items-center">
                            <div class="btn-group ms-auto" role="group" aria-label="Basic example">
                                <a class="btn btn-info" data-bs-toggle="modal" data-bs-target="#importModal">
                                    <i class="far fa-file-excel"></i>
                                    Upload Excel
                                </a>
                                <a class="btn btn-info" href="{{ route('generus.exportTemplate') }}">
                                    <i class="far fa-file-excel"></i>
                                    Download Template
                                </a>
                                <a class="btn btn-info" href="{{ route('generus.export') }}">
                                    <i class="far fa-file-excel"></i>
                                    Download Excel
                                </a>

                            </div>
                            <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addRowModal">
                                <i class="fa fa-plus"></i>
                                Tambah
                            </button>
                        </div>
                    </div>
                </div>
                @if ($errors->any())
                    <div class="alert alert-danger">
                        <ul>
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif
                <div class="card-body">
                    <!-- Modal -->
                    @include('generus.modal')

                    {{-- Table --}}
                    @include('generus.table')
                </div>
            </div>
        </div>
    </div>
    <script>
        // fungsi untuk preview gambar
        $(document).ready(function() {
            $('#foto').on('change', function() {
                const file = this.files[0];
                if (file) {
                    const reader = new FileReader();
                    reader.onload = function(e) {
                        $('#preview').attr('src', e.target.result).show();
                    };
                    reader.readAsDataURL(file);
                } else {
                    $('#preview').hide();
                }
            });

            $('#fotoedit').on('change', function() {
                const file = this.files[0];
                if (file) {
                    const reader = new FileReader();
                    reader.onload = function(e) {
                        $('#previewedit').attr('src', e.target.result).show();
                    };
                    reader.readAsDataURL(file);
                } else {
                    $('#previewedit').hide();
                }
            });
        });

        // fungsi untuk edit berdasarkan id
        function editRow(id) {
            $("#editRowForm" + id).submit(); // Pastikan form ada
        }

        // fungsi untuk close modal
        function closemodal() {
            $(".modal").modal("hide");
        }
    </script>
@endsection
