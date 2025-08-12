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

<div class="row">
    <div class="row mb-3">
        @if (Auth::user()->jabatan !== 'kelompok')
            <form action="{{ url('/pengajian') }}" method="GET" id="addRowForm" class="addRowForm row g-3">
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
                <div class="col-sm-4">
                    <div class="form-group">
                        <label for="kelompok">Tahun</label>
                        <select name="tahun" id="tahun" class="form-control">
                            <option value="" selected>Pilih Tahun</option>
                            @foreach ($tahun as $value)
                                <option value="{{ $value }}">{{ $value }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="col-sm-12">
                    <button type="submit" class="btn btn-primary ">Tampilkan</button>
                </div>
            </form>
        @endif
    </div>
    @foreach ($pengajians as $id => $data)
        <div class="col-sm-6 col-lg-4 mb-2 mb-lg-5">
            <div class="card card-stats card-round h-100">
                <div class="card-body">
                    <div class="row align-items-center">
                        <div class="col-icon w-100 m-0 ">
                            <div
                                class="icon-big text-center {{ $id == 0 ? 'icon-success' : 'icon-primary' }} bubble-shadow-small">
                                <div class="text-light">
                                    <h6 class="mb-0 ">{{ date('d M Y', strtotime($data->waktu_tanggal_mulai)) }}</h6>
                                    <p class="mb-0">{{ date('H:i', strtotime($data->waktu_tanggal_mulai)) }}</p>
                                </div>
                            </div>
                        </div>
                        <div class="col col-stats mt-3 ms-3 ms-sm-0">
                            <div class="numbers">
                                <div class="text-truncate-container">
                                    <p class="card-category mb-0">
                                        {{ number_format(($overallKehadiran[$data->id]['hadir'] / $overallKehadiran[$data->id]['total']) * 100, 2) }}%
                                    </p>
                                </div>
                                <div class="card-title d-flex mb-0">
                                    <p>{{ $data->nama }}</p>
                                </div>
                                <div class="kelas d-flex flex-wrap gap-2 h-100">
                                    @foreach ($data->Absens as $absen)
                                        <button class="btn btn-primary btn-xs btn-border btn-round">
                                            {{ $absen->kelas->nama }}
                                        </button>
                                    @endforeach
                                </div>

                            </div>
                        </div>
                        <div class="col-auto d-flex align-items-center ps-0">
                            <a href="{{ url('/pengajian/delete/' . $data->id) }}" type="button" title="Hapus"
                                class="btn btn-link btn-danger" data-original-title="Remove" data-confirm-delete="true"
                                style="padding: .5rem">
                                <i class="fa fa-trash"></i>
                            </a>
                            <a href="{{ url('/pengajian/' . $data->id . '/edit') }}" type="button" title="Edit"
                                class="btn btn-link btn-primary" style="padding: .5rem">
                                <i class="fa fa-edit"></i>
                            </a>
                        </div>
                        <div class="col-12 mt-2">
                            <div class="col-auto">
                                @if ($data->materi)
                                    <p class="text-muted m-0">{{ $data->materi }}</p>
                                @endif

                            </div>
                        </div>
                    </div>
                </div>
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
