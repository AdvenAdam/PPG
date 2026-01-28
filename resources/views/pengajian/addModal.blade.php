<!-- Modal -->
<div class="modal fade" id="addRowModal" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header border-0">
                <h5 class="modal-title">
                    <span class="fw-mediumbold"> Input Acara Pengajian</span>
                </h5>
            </div>
            <div class="modal-body">
                <form action="{{ url('/pengajian') }}" method="POST" id="addRowForm" class="row g-3"
                    enctype="multipart/form-data">
                    @csrf
                    <div class="row">
                        <div class="col-sm-12">
                            <div class="form-group">
                                <label for="nama">Nama pengajian</label>
                                <input type="text" name="nama" class="form-control form-control"
                                    placeholder="* isi Nama pengajian" required />
                            </div>
                        </div>
                        <div class="col-sm-12">
                            <div class="form-group">
                                <label for="waktu_tanggal_mulai">Tanggal Waktu Pengajian</label>
                                <input type="text" class="form-control datetimepicker" name="waktu_tanggal_mulai"
                                    placeholder="* isi Tanggal Lahir" />
                            </div>
                        </div>
                        <div class="col-sm-12">
                            <div class="form-group">
                                <label class="form-label">Pilih Kelas</label>
                                <div class="selectgroup selectgroup-pills">
                                    @foreach ($kelas as $kel)
                                        <label class="selectgroup-item">
                                            <input type="checkbox" name="kelas[]" value="{{ $kel->id }}"
                                                class="selectgroup-input">
                                            <span class="selectgroup-button">{{ $kel->nama }}</span>
                                        </label>
                                    @endforeach

                                </div>
                            </div>
                        </div>
                        <div class="col-sm-12">
                            <div class="form-group">
                                <label class="form-label">Pilih Tingkat</label>
                                <select name="tingkat" id="tingkat" class="form-control">
                                    <option value="" selected disabled>Pilih Tingkat Pengajian</option>
                                    <option value="daerah">Daerah</option>
                                    <option value="desa">Desa</option>
                                    <option value="kelompok">Kelompok</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-sm-12">
                            <div class="form-group">
                                <div class="form-group">
                                    <label for="materi">Materi pengajian</label>
                                    <textarea class="form-control" id="materi" name="materi" rows="4" placeholder=" isi Materi pengajian"></textarea>
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
