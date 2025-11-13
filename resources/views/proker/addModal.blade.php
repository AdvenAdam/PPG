@php
    use App\Models\Proker;

    $months = Proker::MONTHS;
@endphp
<!-- Modal -->
<div class="modal fade" id="inputProkerModal" tabindex="-1" aria-labelledby="inputProkerLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            {{-- Modal Header --}}
            <div class="modal-header border-0">
                <h5 class="modal-title fw-mediumbold" id="inputProkerLabel">Input Program Kerja</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>

            {{-- Modal Body --}}
            <div class="modal-body">
                <form action="{{ url('/proker') }}" method="POST" id="prokerForm" enctype="multipart/form-data">
                    @csrf
                    <div class="row g-3">

                        <div class="col-12">
                            <label for="program" class="form-label">Nama Program</label>
                            <input type="text" id="program" name="program" class="form-control"
                                value="{{ old('program') }}" placeholder="* Nama Program Kerja" required
                                autocomplete="off">
                        </div>

                        <div class="col-sm-6">
                            <label for="id_tim" class="form-label">Tim Pelaksana</label>
                            <select name="id_tim" id="id_tim" class="form-select">
                                @foreach ($tims as $tim)
                                    <option value="{{ $tim->id }}"
                                        {{ old('id_tim') == $tim->id ? 'selected' : '' }}>
                                        {{ $tim->nama }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="col-sm-6">
                            <label for="penanggung_jawab" class="form-label">Penanggung Jawab</label>
                            <input type="text" id="penanggung_jawab" name="penanggung_jawab" class="form-control"
                                value="{{ old('penanggung_jawab') }}" placeholder="Nama Penanggung Jawab" required
                                autocomplete="off">
                        </div>

                        <div class="col-sm-6">
                            <label for="anggaran" class="form-label">Anggaran</label>
                            <input type="number" id="anggaran" name="anggaran" min="0" step="100"
                                value="{{ old('anggaran', 0) }}" class="form-control" placeholder="Anggaran" required>
                        </div>

                        <div class="col-sm-6">
                            <label for="tahun" class="form-label">Tahun Anggaran</label>
                            <input type="number" id="tahun" name="tahun" min="2000" step="1"
                                value="{{ old('tahun', date('Y')) }}" class="form-control" placeholder="Tahun Anggaran">
                        </div>
                        <div class="col-sm-6">
                            <label for="sasaran" class="form-label">Sasaran</label>
                            <input type="text" id="sasaran" name="sasaran" value="{{ old('sasaran') }}"
                                class="form-control" placeholder="Sasaran">
                        </div>

                        <div class="col-12">
                            <label class="form-label">Waktu Pelaksanaan</label>
                            <div class="selectgroup selectgroup-pills flex-wrap">
                                @foreach ($months as $month)
                                    <label class="selectgroup-item me-1 mb-1">
                                        <input type="checkbox" name="waktu_pelaksanaan[]" value="{{ $month }}"
                                            class="selectgroup-input"
                                            {{ is_array(old('waktu_pelaksanaan')) && in_array($month, old('waktu_pelaksanaan')) ? 'checked' : '' }}>
                                        <span class="selectgroup-button">{{ $month }}</span>
                                    </label>
                                @endforeach
                            </div>
                        </div>

                        @php
                            $fields = [
                                'latar_belakang' => 'Latar Belakang',
                                'tujuan' => 'Tujuan',
                                'target' => 'Target',
                                'indikator_keberhasilan' => 'Indikator Keberhasilan',
                                'keterangan' => 'Keterangan',
                            ];
                        @endphp

                        @foreach ($fields as $name => $label)
                            <div class="{{ $loop->last ? 'col-12' : 'col-sm-6' }}">
                                <label for="{{ $name }}" class="form-label">{{ $label }}</label>
                                <textarea class="form-control" name="{{ $name }}" id="{{ $name }}"
                                    placeholder="{{ $label }} Program" rows="3">{{ old($name) }}</textarea>
                            </div>
                        @endforeach
                    </div>
                </form>
            </div>

            {{-- Modal Footer --}}
            <div class="modal-footer border-0">
                <button type="submit" form="prokerForm" id="addRowButton" class="btn btn-primary">
                    Add
                </button>
                <button type="button" class="btn btn-danger" data-bs-dismiss="modal">
                    Close
                </button>
            </div>
        </div>
    </div>
</div>

<script>
    function updateAct(id) {

        const form = $('#prokerForm');

        form.attr('action', `/proker/${id}`);

        form.find('input[name="_method"]').remove();
        form.append('<input type="hidden" name="_method" value="PUT">');

        const data = @json($prokers);
        const selectedData = Object.values(data)
            .flat()
            .find(item => item.id === id);
        console.log(selectedData);
        if (!selectedData) return;

        const modal = $("#inputProkerModal");

        modal.find('.modal-title').text(`Update Data Program Kerja: ${selectedData.program}`);

        // Fill input fields
        modal.find('#program').val(selectedData.program);
        modal.find('#id_tim').val(selectedData.id_tim);
        modal.find('#penanggung_jawab').val(selectedData.penanggung_jawab);
        modal.find('#anggaran').val(selectedData.anggaran);
        modal.find('#tahun').val(selectedData.tahun);
        modal.find('#sasaran').val(selectedData.sasaran);
        modal.find('#latar_belakang').val(selectedData.latar_belakang);
        modal.find('#tujuan').val(selectedData.tujuan);
        modal.find('#target').val(selectedData.target);
        modal.find('#indikator_keberhasilan').val(selectedData.indikator_keberhasilan);
        modal.find('#keterangan').val(selectedData.keterangan);

        // Handle waktu_pelaksanaan checkboxes
        const months = selectedData.waktu_pelaksanaan || {};
        modal.find('input[name="waktu_pelaksanaan[]"]').each(function() {
            const monthName = $(this).val();
            $(this).prop('checked', months.hasOwnProperty(monthName));
        });

        // Enable submit button
        modal.find('#addRowButton').prop('disabled', false);
    }
</script>
