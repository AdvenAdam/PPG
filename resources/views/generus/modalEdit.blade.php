<script>
    function kelompokDropdown(id_desa, id_kelompok = 0) {
        $.ajax({
            url: '/kelompok/get-by-desa/' + id_desa,
            type: 'GET',
            success: function(response) {
                $('#id_klmpkEdit').prop('disabled', false);
                $('#id_klmpkEdit').empty();
                $('#id_klmpkEdit').append('<option value="">Pilih Kelompok</option>');
                response.forEach(function(data) {
                    $('#id_klmpkEdit').append(
                        `<option value="${data.id}" ${data.id == id_kelompok ? 'selected':''} >${data.nama}</option>`
                    );
                });
            }
        });
    }

    function updateAct(id) {
        $('#updateRowForm').attr('action', `/generus/edit/${id}`);

        const generus = @json($data);
        const modal = $("#updateRowModal");
        const selectedGenerus = generus.find(g => g.id === id);

        kelompokDropdown(selectedGenerus.id_desa, selectedGenerus.id_kelompok);

        modal.find('#titleText').text(`Update Data ${selectedGenerus.nama}`);
        modal.find('#namaEdit').val(selectedGenerus.nama);
        modal.find('#datepicker1').val(selectedGenerus.tgllahir);
        modal.find('#genderEdit').val(selectedGenerus.gender).change();
        modal.find('#id_kelasEdit').val(selectedGenerus.id_kelas).change();
        modal.find('#id_desaEdit').val(selectedGenerus.id_desa).change();
        modal.find('#status_pekerjaanEdit').val(selectedGenerus.status_pekerjaan).change();

        if (selectedGenerus.status_pekerjaan === 'PELAJAR' && selectedGenerus.detail_pekerjaan) {
            try {
                const detailSekolah = JSON.parse(selectedGenerus.detail_pekerjaan);
                console.log("🚀 ~ updateAct ~ detailSekolah:", detailSekolah)
                modal.find('#sekolah_jamaah').prop('checked', detailSekolah.sekolahJamaah == 1);
                modal.find('#tingkat_sekolah').val(detailSekolah.tingkat).change();
            } catch (e) {
                console.error("Invalid detail_pekerjaan JSON:", selectedGenerus.detail_pekerjaan);
            }
        }
        modal.find('#detail_pekerjaanEdit').val(selectedGenerus.detail_pekerjaan);
        modal.find('#pendidikan_terakhirEdit').val(selectedGenerus.pendidikan_terakhir);
        modal.find('#nama_bapakEdit').val(selectedGenerus.nama_bapak);
        modal.find('#hum_bapakEdit').prop('checked', selectedGenerus.hum_bapak == 1);
        modal.find('#nama_ibuEdit').val(selectedGenerus.nama_ibu);
        modal.find('#hum_ibuEdit').prop('checked', selectedGenerus.hum_ibu == 1);
        modal.find('#statusEdit').val(selectedGenerus.status);
        modal.find('#keteranganEdit').val(selectedGenerus.keterangan).change();

        if (selectedGenerus.mubalight == 1) {
            modal.find('#mubalightEdit1').prop('checked', true);
        } else {
            modal.find('#mubalightEdit2').prop('checked', true);
        }

        if (selectedGenerus.foto_url === 'user.png') {
            const defaultFoto = selectedGenerus.gender === 'L' ? 'user-boy.png' : 'user-girl.png';
            modal.find('#previewEdit').attr('src', `/assets/img/foto/${defaultFoto}`).show();
        } else {
            modal.find('#previewEdit').attr('src', `/assets/img/foto/${selectedGenerus.foto_url}`).show();
        }
    }
</script>

<div class="modal fade" id="updateRowModal" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header border-0">
                <h5 class="modal-title">
                    <span class="fw-mediumbold" id="titleText">Update Data Generus</span>
                </h5>
            </div>
            <div class="modal-body">
                <form action="" method="POST" id="updateRowForm" class="addRowForm row g-3"
                    enctype="multipart/form-data">
                    @csrf
                    <div class="row">
                        <div class="col-sm-6">
                            <div class="form-group">
                                <label for="nama">Nama Lengkap</label>
                                <input type="text" class="form-control" id="namaEdit" name="nama"
                                    placeholder="* isi Nama Lengkap" />
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="form-group">
                                <label for="tgllahir">Tanggal Lahir</label>
                                <input type="text" class="form-control" id="datepicker1" name="tgllahir"
                                    placeholder="* isi Tanggal Lahir" />
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="form-group">
                                <label for="gender">Jenis Kelamin</label>
                                <select name="gender" id="genderEdit" class="form-control">
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
                                <select name="id_kelas" id="id_kelasEdit" class="form-control">
                                    <option value="" selected disabled>Pilih Kelas</option>
                                    @foreach ($kelas as $kls)
                                        <option value="{{ $kls->id }}">{{ $kls->nama }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        {{-- TODO : Kelompok cant edit id_kelompok --}}
                        @if (Auth::user()->jabatan !== 'kelompok')
                            <div class="col-sm-6">
                                <div class="form-group">
                                    <label for="desa">Desa</label>
                                    <select name="id_desa" id="id_desaEdit" class="form-control">
                                        <option value="" selected>Pilih Desa</option>
                                        @foreach ($desa as $value)
                                            <option value="{{ $value->id }}">{{ $value->nama }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>

                            <div class="col-sm-6">
                                <div class="form-group">
                                    <label for="kelompok">Kelompok</label>
                                    <select name="id_kelompok" id="id_klmpkEdit" class="form-control" disabled="true">
                                        <option value="" selected disabled>Pilih Kelompok</option>
                                        @foreach ($kelompok as $value)
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        @endif
                        <div class="col-sm-6">
                            <div class="form-group">
                                <label for="status_pekerjaan">Status Pekerjaan</label>
                                <select name="status_pekerjaan" id="status_pekerjaanEdit" class="form-control">
                                    <option value="" selected disabled>Pilih Status Pekerjaan</option>
                                    <option value="BELUM SEKOLAH">BELUM SEKOLAH</option>
                                    <option value="PELAJAR">PELAJAR</option>
                                    <option value="MAHASISWA">MAHASISWA</option>
                                    <option value="BEKERJA">BEKERJA</option>
                                    <option value="BELUM BEKERJA">BELUM BEKERJA</option>
                                    <option value="MONDOK">MONDOK</option>
                                </select>
                            </div>
                            <div class="form-group" id="detail_pekerjaan_input_container" hidden='true'>
                                <label for="detail_pekerjaan">Detail Pekerjaan</label>
                                <input type="text" class="form-control" id="detail_pekerjaanEdit"
                                    name="detail_pekerjaan" required placeholder="* Detail Pekerjaan" />
                            </div>
                            <div class="form-group" id="detail_sekolah_input_container" hidden='true'>
                                <div class="row">
                                    <div class="col-6">
                                        <label for="tingkat_sekolah">Tingkat Sekolah</label>
                                        <select name="tingkat_sekolah" id="tingkat_sekolah" class="form-control">
                                            <option value="" selected disabled>Pilih Kelas</option>
                                            @for ($i = 1; $i <= 12; $i++)
                                                <option value="{{ $i }}">{{ $i }}</option>
                                            @endfor
                                        </select>
                                    </div>
                                    <div class="col-6">
                                        <label for="detail_sekolah">Sekolah Jamaah</label>
                                        <div class="py-3">
                                            <input class="form-check-input-lg mt-0" name="sekolah_jamaah"
                                                id="sekolah_jamaah" type="checkbox" value="1"
                                                aria-label="Checkbox for following text input"
                                                style="width: 20px; height: 20px;">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="form-group">
                                <label for="pendidikan_terakhir">Pendidikan Terakhir</label>
                                <select name="pendidikan_terakhir" id="pendidikan_terakhirEdit" class="form-control">
                                    <option value="" selected disabled>Pilih Pendidikan Terakhir</option>
                                    <option value="BELUM SEKOLAH">BELUM SEKOLAH</option>
                                    <option value="TK">TK</option>
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
                                        <input type="text" name="nama_bapak" id="nama_bapakEdit"
                                            class="form-control form-control" placeholder="* isi Nama Bapak"
                                            required />
                                    </div>
                                </div>
                                <div class="col-3 p-0">
                                    <div class="form-group">
                                        <label for="hum_bapak">Hum</label>
                                        <div class="p-3 ">
                                            <input class="form-check-input-lg mt-0" name="hum_bapak"
                                                id="hum_bapakEdit" type="checkbox" value="1"
                                                aria-label="Checkbox for following text input"
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
                                            id="nama_ibuEdit" placeholder="* isi Nama Ibu" required />
                                    </div>
                                </div>
                                <div class="col-3 p-0">
                                    <div class="form-group">
                                        <label for="hum_ibu">Hum</label>
                                        <div class="p-3 ">
                                            <input class="form-check-input-lg mt-0" name="hum_ibu" id="hum_ibuEdit"
                                                type="checkbox" value="1"
                                                aria-label="Checkbox for following text input"
                                                style="width: 20px; height: 20px;">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="form-group">
                                <label for="status">Status Keaktifan</label>
                                <select name="status" id="statusEdit" class="form-control">
                                    <option value="aktif" selected>Aktif</option>
                                    <option value="tidak aktif">Tidak Aktif</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="form-group">
                                <label for="foto_url">Foto</label>
                                <input type="file" class="form-control form-control" name="foto_url"
                                    id="foto_url" accept="image/jpeg, image/png" />
                                <img id="previewEdit" class="img-fluid ms-3 mt-1"
                                    style="display: none; max-width: 100px; max-height: 100px;" alt="Preview Foto">
                            </div>
                        </div>
                        <div class="col-12">
                            <div class="form-group">
                                <label>Pernah Menjadi Mubalight/Mubalighot ?</label>
                                <div class="d-flex">
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" name="mubalight"
                                            id="mubalightEdit1" value="1">
                                        <label class="form-check-label" for="mubalight1">
                                            Pernah
                                        </label>
                                    </div>
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" name="mubalight"
                                            id="mubalightEdit2" value="0" checked="">
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
                                <textarea class="form-control form-control" name="keterangan" id="keteranganEdit" placeholder="Keterangan"
                                    rows="3"></textarea>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer border-0">
                        <button type="submit" id="updateRowButton" class="btn btn-primary"
                            onclick="this.disabled=true; this.form.submit();">
                            Update
                        </button>
                        <button type="button" class="btn btn-danger" data-dismiss="modal" onclick="closemodal()">
                            Close
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
<script>
    $(document).ready(function() {
        $('#id_desa').change(function() {
            const id_desa = $(this).val()
            kelompokDropdown(id_desa)
        });
        $('#status_pekerjaanEdit').change(function() {
            const status_pekerjaan = $(this).val()
            $('#updateRowModal #detail_pekerjaan_input_container').prop('hidden', true);
            $('#updateRowModal #detail_sekolah_input_container').prop('hidden', true);
            if (status_pekerjaan === 'BEKERJA') {
                $('#updateRowModal #detail_pekerjaan_input_container').prop('hidden', false);
            } else if (status_pekerjaan === 'PELAJAR') {
                $('#updateRowModal #detail_sekolah_input_container').prop('hidden', false);
            }
        }).trigger('change');

    });
</script>
