<div class="modal fade" id="addRowModal" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header border-0">
                <h5 class="modal-title">
                    <span class="fw-mediumbold">Input Data Generus</span>
                </h5>
            </div>
            <div class="modal-body">
                <form action="{{ url('/generus') }}" method="POST" id="addRowForm" class="addRowForm row g-3"
                    enctype="multipart/form-data">
                    @csrf
                    <div class="row">
                        <div class="col-sm-6">
                            <div class="form-group">
                                <label for="nama">Nama Lengkap</label>
                                <input type="text" class="form-control" id="nama" name="nama"
                                    placeholder="* isi Nama Lengkap" />
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="form-group">
                                <label for="tgllahir">Tanggal Lahir</label>
                                <input type="text" class="form-control" id="datepicker" name="tgllahir"
                                    placeholder="* isi Tanggal Lahir" />
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="form-group">
                                <label for="gender">Jenis Kelamin</label>
                                <select name="gender" id="gender" class="form-control">
                                    <option value="" selected disabled>Pilih Jenis Kelamin
                                    </option>
                                    <option value="L">Laki-Laki</option>
                                    <option value="P">Perempuan</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="form-group">
                                <label for="kelas">Kelas</label>
                                <select name="id_kelas" id="id_kelas" class="form-control">
                                    <option value="" selected disabled>Pilih Kelas</option>
                                    @foreach ($kelas as $kls)
                                        <option value="{{ $kls->id }}">{{ $kls->nama }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-sm-6">
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
                        <div class="col-sm-6">
                            <div class="form-group">
                                <label for="kelompok">Kelompok</label>
                                <select name="id_kelompok" id="id_klmpk" class="form-control" disabled="true">
                                    <option value="" selected disabled>Pilih Kelompok</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="form-group">
                                <label for="status_pekerjaan">Status Pekerjaan</label>
                                <select name="status_pekerjaan" id="status_pekerjaan" class="form-control">
                                    <option value="" selected disabled>Pilih Status Pekerjaan</option>
                                    <option value="PELAJAR/MAHASISWA">PELAJAR/MAHASISWA</option>
                                    <option value="BEKERJA">BEKERJA</option>
                                    <option value="BELUM BEKERJA">BELUM BEKERJA</option>
                                    <option value="MONDOK">MONDOK</option>
                                </select>
                            </div>
                            <div class="form-group" id="detail_pekerjaan_input_container" hidden='true'>
                                <label for="detail_pekerjaan">Detail Pekerjaan</label>
                                <input type="text" class="form-control" id="detail_pekerjaan" name="detail_pekerjaan"
                                    required placeholder="* Detail Pekerjaan" />
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="form-group">
                                <label for="pendidikan_terakhir">Pendidikan Terakhir</label>
                                <select name="pendidikan_terakhir" id="pendidikan_terakhir" class="form-control">
                                    <option value="" selected disabled>Pilih Pendidikan Terakhir</option>
                                    <option value="SD">SD</option>
                                    <option value="SMP">SMP</option>
                                    <option value="SMA">SMA</option>
                                    <option value="D3">Diploma III/IV</option>
                                    <option value="S1">Sarjana</option>
                                    <option value="S2">Magister</option>
                                    <option value="S3">Doktor</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="row">
                                <div class="col-9 pe-0">
                                    <div class="form-group">
                                        <label for="nama_bapak">Nama Bapak</label>
                                        <input type="text" name="nama_bapak" id="nama_bapak"
                                            class="form-control form-control" placeholder="* isi Nama Bapak"
                                            required />
                                    </div>
                                </div>
                                <div class="col-3 p-0">
                                    <div class="form-group">
                                        <label for="hum_bapak">Hum</label>
                                        <div class="p-3 ">
                                            <input class="form-check-input-lg mt-0" name="hum_bapak" type="checkbox"
                                                value="1" aria-label="Checkbox for following text input"
                                                style="width: 20px; height: 20px;">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="row">
                                <div class="col-9 pe-0">
                                    <div class="form-group">
                                        <label for="nama_ibu">Nama Ibu</label>
                                        <input type="text" class="form-control form-control" name="nama_ibu"
                                            id="nama_ibu" placeholder="* isi Nama Ibu" required />
                                    </div>
                                </div>
                                <div class="col-3 p-0">
                                    <div class="form-group">
                                        <label for="hum_ibu">Hum</label>
                                        <div class="p-3 ">
                                            <input class="form-check-input-lg mt-0" name="hum_ibu" type="checkbox"
                                                value="1" aria-label="Checkbox for following text input"
                                                style="width: 20px; height: 20px;">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="form-group">
                                <label for="status">Status Keaktifan</label>
                                <select name="status" id="status" class="form-control">
                                    <option value="aktif" selected>Aktif</option>
                                    <option value="tidak aktif">Tidak Aktif</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="form-group">
                                <label for="foto_url">Foto</label>
                                <input type="file" class="form-control form-control" name="foto_url"
                                    id="foto_url" accept="image/jpeg, image/png" required />
                            </div>
                            <img id="preview" class="img-fluid ms-3 mt-1"
                                style="display: none; max-width: 100px; max-height: 100px;" alt="Preview Foto">
                        </div>
                        <div class="col-12">
                            <div class="form-group">
                                <label>Pernah Menjadi Mubalight/Mubalighot ?</label>
                                <div class="d-flex">
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" name="mubalight"
                                            id="mubalight1" value="1">
                                        <label class="form-check-label" for="mubalight1">
                                            Pernah
                                        </label>
                                    </div>
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" name="mubalight"
                                            id="mubalight2" value="0" checked="">
                                        <label class="form-check-label" for="mubalight2">
                                            Tidak Pernah
                                        </label>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-12">
                            <div class="form-group">
                                <label for="keterangan">Keterangan</label>
                                <textarea class="form-control form-control" name="keterangan" id="keterangan" placeholder="Keterangan"
                                    rows="3"></textarea>
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
                <button type="button" class="btn btn-danger" data-dismiss="modal" onclick="closemodal()">
                    Close
                </button>
            </div>
        </div>
    </div>
</div>
<div class="modal fade" id="importModal" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-md modal-dialog-centered  p-3" role="document">
        <div class="modal-content">
            <div class="modal-header border-0">
                <h5 class="modal-title">
                    <span class="fw-mediumbold">Input Data Generus</span>
                </h5>
            </div>
            <div class="modal-body">
                <form action="{{ route('generus.import') }}" method="POST" id="importForm"
                    class="addRowForm row g-3" enctype="multipart/form-data">
                    @csrf
                    <div class="row">
                        <div class="form-group p-3">
                            <label for="file">File Excel</label>
                            <input type="file" class="form-control form-control" name="file" id="file"
                                accept=".xlsx" required />
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer border-0">
                <button type="button" id="importButton" class="btn btn-primary"
                    onclick="document.getElementById('importForm').submit();">
                    Add Batch
                </button>
                <button type="button" class="btn btn-danger" data-dismiss="modal" onclick="closemodal()">
                    Close
                </button>
            </div>
        </div>
    </div>
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
        $('#status_pekerjaan').change(function() {
            const status_pekerjaan = $(this).val()
            if (status_pekerjaan === 'BEKERJA') {
                $('#detail_pekerjaan_input_container').prop('hidden', false);
            } else {
                $('#detail_pekerjaan_input_container').prop('hidden', true);
            }

        })

    });
</script>
