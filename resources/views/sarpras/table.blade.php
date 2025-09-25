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
    <div class="row mb-3">
        @if (Auth::user()->jabatan !== 'kelompok')
            <form action="{{ url('/sarpras') }}" method="GET" id="addRowForm" class="addRowForm row g-3">
                <div class="col-sm-4 ">
                    <div class="form-group">
                        <label for="desa">Desa</label>
                        <select name="id_desa" id="id_desa" class="form-control">
                            <option value="" selected disabled>Pilih Desa</option>
                            @foreach ($desa as $value)
                                <option value="{{ $value->id }}">{{ $value->nama }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="col-sm-4">
                    <div class="form-group">
                        <label for="kelompok">Kelompok</label>
                        <select name="id_klmpk" id="id_klmpk" class="form-control" disabled="true">
                            <option value="" selected disabled>Pilih Kelompok</option>
                        </select>
                    </div>
                </div>
                <div class="col-sm-12">
                    <button type="submit" class="btn btn-primary ">Tampilkan</button>
                </div>
            </form>
        @endif
    </div>
    @foreach ($sarpras as $kelompok => $data)
        <div class="table-wrapper">
            <hr class="my-4" />
            <h5 class="text-muted text-center">Kelompok {{ $kelompok }}</h5>
            <div class="table-responsive">
                <table id="add-row-table" class="display table table-datatable table-hover">
                    <thead>
                        <tr>
                            <th rowspan="2" style="width: 7%">No</th>
                            <th rowspan="2">Nama</th>
                            <th rowspan="2">Jumlah</th>
                            <th colspan="3" class="text-center">Kondisi</th>
                            <th rowspan="2">Action</th>
                        </tr>
                        <tr>
                            <th style="width: 10%">Baik</th>
                            <th style="width: 10%">Sedang</th>
                            <th style="width: 10%">Rusak</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($data as $value)
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td>{{ $value->nama }}</td>
                                <td>{{ $value->jumlah }}</td>
                                <td>{{ $value->kondisi['baik'] ?? 0 }}</td>
                                <td>{{ $value->kondisi['sedang'] ?? 0 }}</td>
                                <td>{{ $value->kondisi['rusak'] ?? 0 }}</td>
                                <td>
                                    <div class="form-button-action">
                                        <!-- Button trigger modal -->
                                        <button type="button" class="btn btn-link btn-primary" data-bs-toggle="modal"
                                            onclick="updateAct({{ $value->id }})" data-bs-target="#addRowModal"
                                            style="padding: 10px" title="Edit">
                                            <i class="fa fa-edit"></i>
                                        </button>

                                        <a href="{{ url('/sarpras/delete/' . $value->id) }}" type="button"
                                            title="Hapus" class="btn btn-link btn-danger" data-original-title="Remove"
                                            data-confirm-delete="true" style="padding: 10px">
                                            <i class="fa fa-trash"></i>
                                        </a>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    @endforeach
</div>
<script>
    $(document).ready(function() {
        $('#id_desa').change(function() {
            const id_desa = $(this).val()
            $('#id_klmpk').prop('disabled', false);

            $.ajax({
                url: '/kelompok/get-by-desa/' + id_desa,
                type: 'GET',
                success: function(response) {
                    $('#id_klmpk').prop('disabled', false);
                    $('#id_klmpk').empty();
                    $('#id_klmpk').append('<option value="">Pilih Kelompok</option>');
                    response.forEach(function(data) {
                        $('#id_klmpk').append('<option value="' + data.id + '">' +
                            data.nama + '</option>');
                    });
                }
            });


        });

    });
</script>
