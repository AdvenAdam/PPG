<div class="modal fade" id="addRowModal" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header border-0">
                <h5 class="modal-title">
                    <span class="fw-mediumbold">Input Data User</span>
                </h5>
            </div>
            <div class="modal-body">
                <form action="{{ url('/user') }}" method="POST" id="addRowForm" class="addRowForm row g-3"
                    enctype="multipart/form-data">
                    @csrf
                    <div class="row">
                        <div class="col-sm-12">
                            <div class="form-group">
                                <label for="nama">Nama User</label>
                                <input type="text" class="form-control" name="nama"
                                    placeholder="* isi Nama User" />
                            </div>
                        </div>
                        <div class="col-sm-12">
                            <div class="form-group">
                                <label for="email">Email</label>
                                <input type="email" class="form-control" id="email" name="email"
                                    placeholder="* isi Email User" />
                            </div>
                        </div>
                        <div class="col-sm-12">
                            <div class="form-group">
                                <label for="password">Password</label>
                                <input type="password" class="form-control" id="password" name="password"
                                    placeholder="* isi Password User" />
                            </div>
                        </div>
                        <div class="col-sm-12">
                            <div class="form-group">
                                <label for="jabatan">Jabatan</label>
                                <select name="jabatan" id="jabatan" onchange="showJabatan()"
                                    class="form-control select2">
                                    <option value="" selected disabled>Pilih Jabatan</option>
                                    <option value="daerah">Daerah</option>
                                    <option value="desa">Desa</option>
                                    <option value="kelompok">Kelompok</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-sm-12" id="desaSelect" hidden>
                            <div class="form-group">
                                <label for="desa">Desa</label>
                                <select name="desa" class="form-control">
                                    <option value="" selected disabled>Pilih Desa</option>
                                    @foreach ($desas as $desa)
                                        <option value="{{ $desa->id }}">{{ $desa->nama }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-sm-12" id="kelompokSelect" hidden>
                            <div class="form-group">
                                <label for="kelompok">Kelompok</label>
                                <select name="kelompok" class="form-control select2">
                                    <option value="" selected disabled>Pilih Kelompok</option>
                                    @foreach ($kelompoks as $kelompok)
                                        <option value="{{ $kelompok->id }}">{{ $kelompok->nama }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer border-0">
                <button type="button" id="addRowButton" class="btn btn-primary">
                    Add
                </button>
                <button type="button" class="btn btn-danger" data-dismiss="modal" onclick="closemodal()">
                    Close
                </button>
            </div>
        </div>
    </div>
</div>
