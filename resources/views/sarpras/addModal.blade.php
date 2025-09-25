<!-- Modal -->
<div class="modal fade" id="addRowModal" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header border-0">
                <h5 class="modal-title fw-mediumbold"> Input Sarana & Prasarana </h5>
            </div>
            <div class="modal-body">
                <form action="{{ url('/sarpras') }}" method="POST" id="addRowForm" class="row g-3"
                    enctype="multipart/form-data">
                    @csrf
                    <div class="row">
                        <div class="col-sm-12">
                            <div class="form-group">
                                <label for="kelompok">Kelompok</label>
                                <select name="id_kelompok" id="id_kelompok" class="form-control">
                                    @foreach ($kelompoks as $kelompok)
                                        <option value="{{ $kelompok->id }}">{{ $kelompok->nama }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-sm-12">
                            <div class="form-group">
                                <label for="nama">Nama sarpras</label>
                                <input type="text" id="nama" name="nama" class="form-control form-control"
                                    placeholder="* isi Nama sarpras" required />
                            </div>
                        </div>
                        <div class="col-sm-12">
                            <div class="form-group">
                                <label for="nama">Jumlah sarpras</label>
                                <input type="number" step="1" min="0" id="jumlah" name="jumlah"
                                    class="form-control form-control" placeholder="* isi Jumlah sarpras" required />
                            </div>
                        </div>
                        <div class="col-sm-12">
                            <label for="kondisi"><b>Kondisi Sarpras</b></label>
                        </div>
                        <div class="col-4 pe-1">
                            <div class="form-group">
                                <label for="nama">Baik</label>
                                <input type="number" step="1" min="0" id="baik" name="baik"
                                    class="form-control form-control" />
                            </div>
                        </div>
                        <div class="col-4 pe-1">
                            <div class="form-group">
                                <label for="nama">Sedang</label>
                                <input type="number" step="1" min="0" id="sedang" name="sedang"
                                    class="form-control form-control" />
                            </div>
                        </div>
                        <div class="col-4 pe-1">
                            <div class="form-group">
                                <label for="nama">Rusak</label>
                                <input type="number" step="1" min="0" id="rusak" name="rusak"
                                    class="form-control form-control" />
                            </div>
                        </div>

                    </div>
                </form>
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
    function updateAct(id) {
        // update the form action
        $('#addRowForm').attr('action', `/sarpras/update/${id}`);

        // remove old _method if exists
        $('#addRowForm input[name="_method"]').remove();

        // add method spoofing
        $('#addRowForm').append('<input type="hidden" name="_method" value="PUT">');

        const data = @json($sarpras);
        const selectedData = Object.values(data)
            .flat() // gabungkan jadi 1 array
            .find(item => item.id === id);

        if (!selectedData) return;

        const modal = $("#addRowModal");

        // update modal title
        modal.find('.modal-title').text(`Update Data Sarana & Prasarana: ${selectedData.nama}`);

        // fill form fields
        modal.find('#nama').val(selectedData.nama);
        modal.find('#id_kelompok').val(selectedData.id_kelompok);
        modal.find('#jumlah').val(selectedData.jumlah);
        modal.find('#baik').val(selectedData.kondisi ? selectedData.kondisi.baik : 0);
        modal.find('#sedang').val(selectedData.kondisi ? selectedData.kondisi.sedang : 0);
        modal.find('#rusak').val(selectedData.kondisi ? selectedData.kondisi.rusak : 0);

    }
</script>
