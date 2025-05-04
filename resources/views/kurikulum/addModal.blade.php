<!-- Modal -->
<div class="modal fade" id="addRowModal" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header border-0">
                <h5 class="modal-title">
                    <span class="fw-mediumbold"> Input Data kurikulum</span>
                </h5>
            </div>
            <div class="modal-body">
                <form action="{{ url('/kurikulum') }}" method="POST" id="addRowForm" class="row g-3"
                    enctype="multipart/form-data">
                    @csrf
                    <div class="row">
                        <div class="col-sm-12">
                            <div class="form-group">
                                <label for="deskripsi">Deskripsi kurikulum</label>
                                <input type="text" name="deskripsi" class="form-control form-control"
                                    placeholder="* isi Deskripsi kurikulum" required />
                            </div>
                            <div class="col-sm-12">
                                <div class="form-group p-3">
                                    <label for="file">File </label>
                                    <input type="file" class="form-control form-control" name="file"
                                        id="file" required />
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
