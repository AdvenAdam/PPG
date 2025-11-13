<div class="row">
    <div class="row mb-3">
        <form action="{{ url('/proker') }}" method="GET" id="filterForm" class="addRowForm row g-3">
            <div class="col-sm-4 ">
                <div class="form-group">
                    <label for="desa">Tim</label>
                    <select name="id_tim" id="id_tim" class="form-control">
                        <option value="" selected disabled>Pilih Tim</option>
                        @foreach ($tims as $value)
                            <option value="{{ $value->id }}">{{ $value->nama }}
                            </option>
                        @endforeach
                    </select>
                </div>
            </div>
            <div class="col-sm-4">
                <div class="form-group">
                    <label for="kelompok">Tahun</label>
                    <select name="tahun" id="tahun" class="form-control">
                        <option value="" selected disabled>Pilih Tahun</option>
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
    </div>
    @foreach ($prokers as $idTim => $proker)
        <div class="card mt-4">
            <div class="card-header mt-4">
                <div class="d-flex justify-between">
                    <div class="title"> <span>Tim Program Kerja</span>
                        <h4 class="text-muted">{{ $tims->where('id', $idTim)->first()->nama ?? '' }}</h4>
                    </div>

                </div>

            </div>
            @foreach ($proker as $item)
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-8 col-lg-10">
                            <div class="row">
                                {{-- Program Kerja --}}
                                <div class="col-6 mb-2">
                                    <div class="row">
                                        <div class="col-4">
                                            <span class="text-muted">Program Kerja</span>
                                        </div>
                                        <div class="col-1 text-center px-0">
                                            <span class="text-muted">:</span>
                                        </div>
                                        <div class="col-7">
                                            <h6 class="text-dark m-0">{{ $item->program }}</h6>
                                        </div>
                                    </div>
                                </div>

                                {{-- Penanggung Jawab --}}
                                <div class="col-6 mb-2">
                                    <div class="row">
                                        <div class="col-4">
                                            <span class="text-muted">Penanggung Jawab</span>
                                        </div>
                                        <div class="col-1 text-center px-0">
                                            <span class="text-muted">:</span>
                                        </div>
                                        <div class="col-7">
                                            <h6 class="text-dark m-0">{{ $item->penanggung_jawab }}</h6>
                                        </div>
                                    </div>
                                </div>

                                {{-- Anggaran --}}
                                <div class="col-6 mb-2">
                                    <div class="row">
                                        <div class="col-4">
                                            <span class="text-muted">Anggaran</span>
                                        </div>
                                        <div class="col-1 text-center px-0">
                                            <span class="text-muted">:</span>
                                        </div>
                                        <div class="col-7">
                                            <h6 class="text-dark m-0">Rp. {{ number_format($item->anggaran) }}</h6>
                                        </div>
                                    </div>
                                </div>

                                {{-- Tahun Anggaran --}}
                                <div class="col-6 mb-2">
                                    <div class="row">
                                        <div class="col-4">
                                            <span class="text-muted">Tahun Anggaran</span>
                                        </div>
                                        <div class="col-1 text-center px-0">
                                            <span class="text-muted">:</span>
                                        </div>
                                        <div class="col-7">
                                            <h6 class="text-dark m-0">{{ $item->tahun }}</h6>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4 col-lg-2">
                            <div class="d-flex form-button-action" style="gap:.5rem">
                                <button type="button" id="editRowButton" class="btn btn-info" data-bs-toggle="modal"
                                    data-bs-target="#inputProkerModal" onclick="updateAct({{ $item->id }})">
                                    Edit
                                </button>
                                <a href="{{ url('/proker/delete/' . $item->id) }}" type="button" title="Hapus"
                                    data-original-title="Remove" data-confirm-delete="true" class="btn btn-danger">
                                    Delete
                                </a>
                            </div>
                        </div>
                    </div>

                    <div class="table-responsive mt-4">
                        <table id="add-row-table" class="display table table-head-bg-secondary table-hover">
                            <thead>
                                <tr>
                                    <th>Latar Belakang</th>
                                    <th>Tujuan</th>
                                    <th>Target</th>
                                    <th>Indikator Keberhasilan</th>
                                    <th>Keterangan</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>{{ $item->latar_belakang }}</td>
                                    <td>{{ $item->tujuan }}</td>
                                    <td>{{ $item->target }}</td>
                                    <td>{{ $item->indikator_keberhasilan }}</td>
                                    <td>{{ $item->keterangan }}</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>

                    <span class="text-muted fw-bold">Waktu Pelaksanaan</span>
                    <div class="table-responsive mt-4">
                        <table id="add-row-table" class="display table table-head-bg-info table-hover">
                            <thead>
                                <tr>
                                    @foreach ((array) $item->waktu_pelaksanaan as $month => $value)
                                        <th>{{ $month }}</th>
                                    @endforeach
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    @foreach ((array) $item->waktu_pelaksanaan as $month => $value)
                                        <td>
                                            <div class="form-group">
                                                <div class="selectgroup w-100">
                                                    <label class="selectgroup-item">
                                                        <input type="radio"
                                                            name="waktu_pelaksanaan[{{ $item->program }}][{{ $month }}]"
                                                            value="true"
                                                            class="selectgroup-input update-waktu-pelaksanaan"
                                                            data-program="{{ $item->program }}"
                                                            data-month="{{ $month }}"
                                                            data-id="{{ $item->id }}"
                                                            {{ $value === true ? 'checked' : '' }}>
                                                        <span class="selectgroup-button">Terlaksana</span>
                                                    </label>

                                                    <label class="selectgroup-item">
                                                        <input type="radio"
                                                            name="waktu_pelaksanaan[{{ $item->program }}][{{ $month }}]"
                                                            value="false"
                                                            class="selectgroup-input update-waktu-pelaksanaan"
                                                            data-program="{{ $item->program }}"
                                                            data-month="{{ $month }}"
                                                            data-id="{{ $item->id }}"
                                                            {{ $value === false ? 'checked' : '' }}>
                                                        <span class="selectgroup-button">Belum Terlaksana</span>
                                                    </label>
                                                </div>
                                            </div>
                                        </td>
                                    @endforeach
                                </tr>
                            </tbody>
                        </table>
                    </div>

                </div>
            @endforeach
        </div>
    @endforeach
</div>
<script>
    $(document).ready(function() {
        $('.update-waktu-pelaksanaan').on('change', function() {
            let id = $(this).data('id');
            let month = $(this).data('month');
            let value = $(this).val() === 'true';
            $.ajax({
                url: `/proker/${id}/update-waktu`,
                type: 'POST',
                data: {
                    _token: '{{ csrf_token() }}',
                    month: month,
                    value: value
                },
                success: function(response) {
                    if (response.success) {
                        Swal.fire({
                            toast: true,
                            position: 'top-end',
                            showConfirmButton: false,
                            timer: 3000,
                            timerProgressBar: true,
                            icon: 'success', // check icon
                            title: 'Berhasil memperbarui waktu program kerja',
                            customClass: {
                                popup: 'custom-toast'
                            }
                        });
                    } else {
                        alert('Gagal memperbarui data.');
                    }
                },
                error: function(xhr) {
                    console.error(xhr.responseText);
                    alert('Terjadi kesalahan saat mengirim data.');
                }
            });
        });
    });
</script>
