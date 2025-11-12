@php
    $months = [
        'Januari',
        'Februari',
        'Maret',
        'April',
        'Mei',
        'Juni',
        'Juli',
        'Agustus',
        'September',
        'Oktober',
        'November',
        'Desember',
    ];
@endphp
<!-- Modal -->
<div class="modal fade" id="addRowModal" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header border-0">
                <h5 class="modal-title fw-mediumbold"> Input Program Kerja </h5>
            </div>
            <div class="modal-body">
                <div class="w-100">
                    <form action="{{ url('/tim-proker') }}" method="POST" id="addRowForm"
                        enctype="multipart/form-data">
                        @csrf
                        <div class="row">
                            <div class="col-sm-12">
                                <div class="form-group px-0">
                                    <label for="nama">Nama Program</label>
                                    <input type="text" id="nama" name="nama" class="form-control"
                                        placeholder="*  Nama Program Kerja" required />
                                </div>
                            </div>

                            <div class="col-sm-6">
                                <div class="form-group px-0">
                                    <label for="tim">Tim Pelaksana</label>
                                    <select name="id_tim" id="id_tim" class="form-control">
                                        @foreach ($tims as $tim)
                                            <option value="{{ $tim->id }}">
                                                {{ $tim->nama }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <div class="form-group px-0">
                                    <label for="penanggung_jawab">Penanggung Jawab</label>
                                    <input type="text" id="penanggung_jawab" name="penanggung_jawab"
                                        class="form-control" placeholder="Nama Penanggung Jawab" required />
                                </div>
                            </div>

                            <div class="col-sm-6">
                                <div class="form-group px-0">
                                    <label for="anggaran">Anggaran</label>
                                    <input type="number" id="anggaran" name="anggaran" min="0" step="100"
                                        value="0" class="form-control" placeholder="Anggaran" required />
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <div class="form-group px-0">
                                    <label for="tahun">Tahun Anggaran</label>
                                    <input type="number" id="tahun" value="{{ date('Y') }}" min="2000"
                                        step="1" class="form-control" name="tahun"
                                        placeholder="Tahun Anggaran" />
                                </div>
                            </div>

                            <div class="col-12">
                                <div class="form-group px-0">
                                    <label class="form-label">Waktu Pelaksanaan</label>
                                    <div class="selectgroup selectgroup-pills">
                                        @foreach ($months as $month)
                                            <label class="selectgroup-item">
                                                <input type="checkbox" name="value" value="{{ $month }}"
                                                    class="selectgroup-input">
                                                <span class="selectgroup-button">{{ $month }}</span>
                                            </label>
                                        @endforeach
                                    </div>
                                </div>
                            </div>

                            <div class="col-sm-6">
                                <div class="form-group px-0">
                                    <label for="latar_belakang">Latar Belakang</label>
                                    <textarea class="form-control form-control" name="latar_belakang" id="latar_belakang"
                                        placeholder="Latar Belakang Program" rows="3"></textarea>
                                </div>
                            </div>

                            <div class="col-sm-6">
                                <div class="form-group px-0">
                                    <label for="tujuan">Tujuan</label>
                                    <textarea class="form-control form-control" name="tujuan" id="tujuan" placeholder="Tujuan Program" rows="3"></textarea>
                                </div>
                            </div>

                            <div class="col-sm-6">
                                <div class="form-group px-0">
                                    <label for="sasaran">Sasaran</label>
                                    <textarea class="form-control form-control" name="sasaran" id="sasaran" placeholder="Sasaran Program" rows="3"></textarea>
                                </div>
                            </div>

                            <div class="col-sm-6">
                                <div class="form-group px-0">
                                    <label for="target">Target</label>
                                    <textarea class="form-control form-control" name="target" id="target" placeholder="Target Program"
                                        rows="3"></textarea>
                                </div>
                            </div>

                            <div class="col-12">
                                <div class="form-group px-0">
                                    <label for="indikator_keberhasilan">Indikator Keberhasilan</label>
                                    <textarea class="form-control form-control" name="indikator_keberhasilan" id="indikator_keberhasilan"
                                        placeholder="Indikator Keberhasilan Program" rows="3"></textarea>
                                </div>
                            </div>
                            <div class="col-12">
                                <div class="form-group px-0">
                                    <label for="keterangan">Keterangan</label>
                                    <textarea class="form-control form-control" name="keterangan" id="keterangan" placeholder="Keterangan"
                                        rows="3"></textarea>
                                </div>
                            </div>

                        </div>
                    </form>
                </div>
            </div>
            <div class="modal-footer border-0">
                <button type="button" id="addRowButton" class="btn btn-primary"
                    onclick="this.disabled=true; this.form.submit();">
                    Add
                </button>
                <button type="button" class="btn btn-danger" onclick="closemodal()">
                    Close
                </button>
            </div>
        </div>
    </div>
</div>
<script>
    $('#addRowModal').on('hidden.bs.modal', function() {
        const modal = $(this);
        const form = modal.find('#addRowForm');

        form.trigger('reset');
        form.find('input[name="_method"]').remove();
        form.attr('action', '{{ url('/tim-proker') }}');
        modal.find('.modal-title').text('Input Program Kerja');
        form.find('.anggota-row').remove();
        modal.find('#anggota').val('');
        modal.find('#addRowButton').prop('disabled', false);
    });

    function updateAct(id) {
        // update the form action
        $('#addRowForm').attr('action', `/tim-proker/update/${id}`);
        $('#addRowForm input[name="_method"]').remove();
        $('#addRowForm').append('<input type="hidden" name="_method" value="PUT">');

        const data = @json($tims);
        const selectedData = Object.values(data)
            .flat()
            .find(item => item.id === id);

        if (!selectedData) return;

        const modal = $("#addRowModal");

        modal.find('.modal-title').text(`Update Data Program Kerja: ${selectedData.nama}`);

        modal.find('#nama').val(selectedData.nama);
        if (selectedData.anggota && selectedData.anggota.length) {
            selectedData.anggota.forEach((anggota, idx) => {
                if (idx === 0) {
                    $('#anggota').val(anggota);
                } else {
                    $('#btn_add_anggota_row').trigger('click');
                    $('.anggota-row').last().find('input[name="anggota[]"]').val(anggota);
                }
            });
        }

    }
</script>
<script>
    (function($, window) {
        $('#btn_add_anggota_row').on('click', function() {
            const el = this;
            let newRow = /*html*/ `
                <div class="form-group px-0 row anggota-row">
                    <div class="col-10">
                        <input type="text" id="anggota" name="anggota[]"  placeholder="* isi nama anggota tim" class="form-control" />
                    </div>
                    <div class="col-2">
                        <button type="button" class="btn btn-icon btn-round btn-danger btn_remove_anggota_row">
                            <i class="fas fa-minus mx-0"></i>
                        </button>
                    </div>
                </div>
            `;
            const container = $(this).closest('.col-md-12');
            const lastAliasRow = container.find('.anggota-row').last();
            if (lastAliasRow.length) {
                lastAliasRow.after(newRow);
            } else {
                $(this).closest('.form-group').after(newRow);
            }
        });
        $('#addRowModal').on('click', '.btn_remove_anggota_row', function() {
            const el = this;
            $(el).closest('.anggota-row').remove();
        });
    })(jQuery, window)
</script>
