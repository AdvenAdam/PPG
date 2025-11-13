<style>
    .text-truncate-container {
        width: 100%;
    }

    .text-truncate-container p {
        -webkit-line-clamp: 1;
        display: -webkit-box;
        -webkit-box-orient: vertical;
        overflow: hidden;
    }
</style>
<style>
    th {
        text-align: center;
        vertical-align: middle;
    }
</style>

<div class="row">
    @foreach ($timProker as $tim)
        <div class="col-lg-4 col-md-6 col-12">
            <div class="table-wrapper card">
                <div class="card-header rounded-top bg-secondary d-flex justify-content-between">
                    <h5 class="card-title text-light">Tim {{ $tim->nama }}</h5>
                    <div class="d-flex form-button-action" style="gap:.5rem">
                        <button type="button" id="addRowButton" class="btn btn-info" data-bs-toggle="modal"
                            data-bs-target="#addRowModal" onclick="updateAct({{ $tim->id }})">
                            Edit
                        </button>
                        <a href="{{ url('/tim-proker/delete/' . $tim->id) }}" type="button" title="Hapus"
                            data-original-title="Remove" data-confirm-delete="true" class="btn btn-danger">
                            Delete
                        </a>
                    </div>
                </div>
                <div class="table-responsive p-4">
                    <hr class="my-4" />
                    <table id="add-row-table" class="display table table-datatable table-hover">
                        <thead>
                            <tr>
                                <th style="width: 7%">No</th>
                                <th>Nama</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($tim->anggota as $value)
                                <tr>
                                    <td>{{ $loop->iteration }}</td>
                                    <td>{{ $value }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    @endforeach
</div>
