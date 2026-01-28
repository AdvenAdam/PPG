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

    .clickable-card:hover {
        transform: translateY(-2px);
        transition: 0.2s;
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
                            <option value="" {{ request('id_desa') ? '' : 'selected' }} disabled>Pilih Desa
                            </option>
                            @foreach ($desa as $value)
                                <option value="{{ $value->id }}"
                                    {{ request('id_desa') == $value->id ? 'selected' : '' }}>{{ $value->nama }}
                                </option>
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
                            <option value=""{{ request('tahun') ? '' : 'selected' }}>Pilih Tahun</option>
                            @foreach ($tahun as $value)
                                <option value="{{ $value }}" {{ request('tahun') == $value ? 'selected' : '' }}>
                                    {{ $value }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="col-sm-4">
                    <div class="form-group">
                        <label for="kelompok">Tingkat</label>
                        <select name="tingkat" id="tingkat" class="form-control">
                            <option value="" {{ request('tingkat') ? '' : 'selected' }}>
                                Pilih Tingkat Pengajian
                            </option>
                            <option value="daerah" {{ request('tingkat') == 'daerah' ? 'selected' : '' }}>Daerah
                            </option>
                            <option value="desa" {{ request('tingkat') == 'desa' ? 'selected' : '' }}>Desa</option>
                            <option value="kelompok" {{ request('tingkat') == 'kelompok' ? 'selected' : '' }}>Kelompok
                            </option>
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
            <div class="card card-stats card-round h-100 clickable-card"
                data-edit-url="{{ url('/pengajian/' . $data->id . '/edit') }}" style="cursor: pointer;">
                <div class="card-body" style="padding-block: 6px !important">
                    <div class="row align-items-center">
                        <div class="col-icon w-100 m-0 ">
                            <div
                                class="icon-big px-3 justify-content-start text-start {{ $id == 0 ? 'icon-success' : 'icon-primary' }} bubble-shadow-small">
                                <div class="text-light">
                                    <h6 class="mb-0 ">{{ date('d M Y', strtotime($data->waktu_tanggal_mulai)) }} |
                                        {{ date('H:i', strtotime($data->waktu_tanggal_mulai)) }}</h6>
                                    <p class="mb-0">{{ Str::title($data->kelompok->nama) }}</p>
                                </div>
                            </div>
                        </div>
                        <div class="col col-stats mt-3 ms-3 ms-sm-0">
                            <div class="numbers">
                                <div class="text-truncate-container">
                                    <p class="card-category mb-0">
                                        {{ $overallKehadiran[$data->id]['total'] > 0
                                            ? number_format(($overallKehadiran[$data->id]['hadir'] / $overallKehadiran[$data->id]['total']) * 100, 2) . '%'
                                            : '0.00%' }}
                                        | Pengajian : {{ Str::title($data->tingkat) }}
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
                                class="btn btn-link btn-danger no-card-click" data-original-title="Remove"
                                data-confirm-delete="true" style="padding: .5rem">
                                <i class="fa fa-trash"></i>
                            </a>
                            <a href="{{ url('/pengajian/' . $data->id . '/edit') }}" type="button" title="Edit"
                                class="btn btn-link btn-primary no-card-click" style="padding: .5rem">
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
    @if ($pengajians->hasPages())
        <div class="d-flex justify-content-between align-items-center mb-3">
            <div class="text-muted small">
                Showing
                {{ $pengajians->firstItem() }}
                –
                {{ $pengajians->lastItem() }}
                of
                {{ $pengajians->total() }}
            </div>

            {{ $pengajians->links() }}
        </div>
    @endif


</div>
<script>
    $(document).ready(function() {

        const selectedDesa = "{{ request('id_desa') }}";
        const selectedKelompok = "{{ request('id_klmpk') }}";

        function loadKelompok(id_desa, selected = null) {
            if (!id_desa) return;

            $('#id_klmpk').prop('disabled', false);

            $.ajax({
                url: '/kelompok/get-by-desa/' + id_desa,
                type: 'GET',
                success: function(response) {
                    $('#id_klmpk').empty();
                    $('#id_klmpk').append('<option value="">Pilih Kelompok</option>');

                    response.forEach(function(data) {
                        let isSelected = selected == data.id ? 'selected' : '';
                        $('#id_klmpk').append(
                            `<option value="${data.id}" ${isSelected}>${data.nama}</option>`
                        );
                    });
                }
            });
        }

        $('#id_desa').change(function() {
            loadKelompok($(this).val());
        });

        if (selectedDesa) {
            loadKelompok(selectedDesa, selectedKelompok);
        }

        $('.clickable-card').on('click', function(e) {
            if ($(e.target).closest('.no-card-click').length) {
                return;
            }
            window.location.href = $(this).data('edit-url');
        });


    });
</script>
