<!-- Modal -->
<div class="modal fade" id="addRowModal" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header border-0">
                <h5 class="modal-title fw-mediumbold"> Input Tim Proker </h5>
            </div>
            <div class="modal-body">
                <form action="{{ url('/tim-proker') }}" method="POST" id="addRowForm" class="row g-3"
                    enctype="multipart/form-data">
                    @csrf
                    <div class="row">
                        <div class="col-sm-12">
                            <div class="form-group">
                                <label for="nama">Nama Tim</label>
                                <input type="text" id="nama" name="nama" class="form-control"
                                    placeholder="* isi nama tim" required />
                            </div>
                        </div>

                        <div class="col-sm-12">
                            <div class="form-group">
                                <label for="kondisi">Anggota Tim</label>
                                <div class="row">
                                    <div class="col-10">
                                        <input type="text" id="anggota" name="anggota[]"
                                            placeholder="* isi nama anggota tim" class="form-control" />
                                    </div>
                                    <div class="col-2">
                                        <button type="button" id="btn_add_anggota_row"
                                            class="btn btn-icon btn-round btn-success">
                                            <i class="fa fa-plus"></i>
                                        </button>
                                    </div>
                                </div>
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
    $('#addRowModal').on('hidden.bs.modal', function() {
        const modal = $(this);
        const form = modal.find('#addRowForm');

        form.trigger('reset');
        form.find('input[name="_method"]').remove();
        form.attr('action', '{{ url('/tim-proker') }}');
        modal.find('.modal-title').text('Input Tim Proker');
        form.find('.anggota-row').remove();
        modal.find('#anggota').val('');
        modal.find('#addRowButton').prop('disabled', false);
    });

    function updateAct(id) {
        // update the form action
        $('#addRowForm').attr('action', `/tim-proker/update/${id}`);
        $('#addRowForm input[name="_method"]').remove();
        $('#addRowForm').append('<input type="hidden" name="_method" value="PUT">');

        const data = @json($timProker);
        const selectedData = Object.values(data)
            .flat()
            .find(item => item.id === id);

        if (!selectedData) return;

        const modal = $("#addRowModal");

        modal.find('.modal-title').text(`Update Data Tim: ${selectedData.nama}`);

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
