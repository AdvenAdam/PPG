<!-- Modal -->
<div class="modal fade" id="addRowModal" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header border-0">
                <h5 class="modal-title fw-semibold">
                    Input Data musyawarah
                </h5>
            </div>
            <div class="modal-body">
                <form action="{{ url('/musyawarah') }}" method="POST" id="addRowForm" class="row g-3"
                    enctype="multipart/form-data">
                    @csrf
                    <div class="row">
                        <div class="col-sm-12">
                            <div class="form-group">
                                <label for="deskripsi">Deskripsi musyawarah</label>
                                <input type="text" id="deskripsi" name="deskripsi" class="form-control form-control"
                                    placeholder="* isi Deskripsi musyawarah" required />
                            </div>
                        </div>
                        <div class="col-sm-12">
                            <div class="form-group">
                                <label for="waktu_tanggal_mulai">Tanggal Waktu musyawarah</label>
                                <input type="text" id="waktu_tanggal_mulai" class="form-control datetimepicker"
                                    name="waktu_tanggal_mulai" placeholder="* isi Tanggal Waktu musyawarah" />
                            </div>
                        </div>
                        <div class="col-sm-12">
                            <div class="form-group p-3">
                                <label for="file">File </label>
                                <input type="file" class="form-control form-control" name="file" id="file"
                                    required />
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
        $('#addRowForm').attr('action', `/musyawarah/update/${id}`);

        // remove old _method if exists
        $('#addRowForm input[name="_method"]').remove();

        // add method spoofing
        $('#addRowForm').append('<input type="hidden" name="_method" value="PUT">');

        const data = @json($musyawarahs);
        const selectedData = data.find(g => g.id === id);

        if (!selectedData) return;

        const modal = $("#addRowModal");

        // update modal title
        modal.find('.modal-title').text(`Update Data musyawarah: ${selectedData.desc}`);

        // fill form fields
        modal.find('#deskripsi').val(selectedData.desc);
        modal.find('#waktu_tanggal_mulai').val(selectedData.waktu_tanggal_mulai);

        // cannot set value of file input, instead show file name
        if (selectedData.file_path) {
            modal.find('#file').siblings('.file-info').remove();
            modal.find('#file').after(
                `<small class="file-info text-muted">Current file: ${selectedData.file_path}</small>`
            );
        }
    }
</script>
