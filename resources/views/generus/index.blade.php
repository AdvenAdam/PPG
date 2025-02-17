@extends('layout.app')

@section('content')

    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <div class="d-flex align-items-center">
                        <div class="col">
                            <h4 class="card-title">Data Jamaah</h4>
                            <span>Penginputan Data Jamaah</span>
                        </div>
                        <div class="d-flex gap-3 align-items-center">
                            <a class="btn btn-success btn-round ms-auto" href="{{ route('generus.export') }}">
                                <i class="far fa-file-excel"></i>
                                Download Excel
                            </a>
                            <button class="btn btn-primary btn-round ms-auto" data-bs-toggle="modal"
                                data-bs-target="#addRowModal">
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
