<!-- Add / Edit Modal -->
<div class="modal fade" id="addRowModal" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header border-0">
                <h5 class="modal-title fw-mediumbold">Input Data Kepengurusan</h5>
            </div>
            <div class="modal-body">
                <form action="{{ url('/kepengurusan') }}" method="POST" id="addRowForm" class="row g-3"
                    enctype="multipart/form-data">
                    @csrf

                    <div class="row">
                        {{-- Tingkat (hidden for kelompok role, auto-set) --}}
                        @if (Auth::user()->jabatan === 'kelompok')
                            <input type="hidden" name="tingkat" value="kelompok">
                            <input type="hidden" name="kelompok_id" value="{{ Auth::user()->id_kelompok }}">
                        @else
                            <div class="col-sm-12">
                                <div class="form-group">
                                    <label for="tingkat">Tingkat</label>
                                    <select name="tingkat" id="tingkat" class="form-control" required>
                                        <option value="" selected disabled>Pilih Tingkat</option>
                                        @if (Auth::user()->jabatan === 'daerah')
                                            <option value="daerah">Daerah</option>
                                        @endif
                                        <option value="desa">Desa</option>
                                        <option value="kelompok">Kelompok</option>
                                    </select>
                                </div>
                            </div>

                            {{-- Daerah selector (daerah level only) --}}
                            @if (Auth::user()->jabatan === 'daerah')
                                <div class="col-sm-12" id="wrap_daerah_id" style="display:none">
                                    <div class="form-group">
                                        <label for="daerah_id">Daerah</label>
                                        <select name="daerah_id" id="daerah_id" class="form-control">
                                            <option value="" selected disabled>Pilih Daerah</option>
                                            @foreach ($daerahs as $d)
                                                <option value="{{ $d->id }}">{{ $d->nama }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                            @endif

                            {{-- Desa selector --}}
                            <div class="col-sm-12" id="wrap_desa_id" style="display:none">
                                <div class="form-group">
                                    <label for="desa_id">Desa</label>
                                    <select name="desa_id" id="desa_id" class="form-control">
                                        <option value="" selected disabled>Pilih Desa</option>
                                        @foreach ($desas as $d)
                                            <option value="{{ $d->id }}">{{ $d->nama }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>

                            {{-- Kelompok selector --}}
                            <div class="col-sm-12" id="wrap_kelompok_id" style="display:none">
                                <div class="form-group">
                                    <label for="kelompok_id">Kelompok</label>
                                    <select name="kelompok_id" id="kelompok_id" class="form-control">
                                        <option value="" selected disabled>Pilih Kelompok</option>
                                        @foreach ($kelompoks as $k)
                                            <option value="{{ $k->id }}">{{ $k->nama }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        @endif

                        {{-- Nama --}}
                        <div class="col-sm-12">
                            <div class="form-group">
                                <label for="nama">Nama</label>
                                <input type="text" name="nama" id="nama" class="form-control"
                                    placeholder="* isi Nama" required />
                            </div>
                        </div>

                        {{-- Jabatan (filtered by tingkat) --}}
                        <div class="col-sm-12">
                            <div class="form-group">
                                <label for="jabatan_id">Jabatan</label>
                                <select name="jabatan_id" id="jabatan_id" class="form-control" required>
                                    <option value="" selected disabled>Pilih Jabatan</option>
                                    @foreach ($jabatans as $j)
                                        <option value="{{ $j->id }}" data-tingkat="{{ $j->tingkat }}">
                                            {{ $j->nama }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer border-0">
                <button type="button" id="addRowButton" class="btn btn-primary" onclick="submitKepengurusan()">
                    Simpan
                </button>
                <button type="button" class="btn btn-danger" onclick="closemodal()">
                    Tutup
                </button>
            </div>
        </div>
    </div>
</div>

<script>
    const jabatans = @json($jabatans);

    // Filter jabatan dropdown based on selected tingkat
    function filterJabatan(tingkat) {
        const select = document.getElementById('jabatan_id');
        const currentVal = select.value;
        select.innerHTML = '<option value="" disabled>Pilih Jabatan</option>';

        jabatans
            .filter(j => !tingkat || j.tingkat === tingkat)
            .forEach(j => {
                const opt = document.createElement('option');
                opt.value = j.id;
                opt.text = j.nama;
                opt.dataset.tingkat = j.tingkat;
                select.appendChild(opt);
            });

        // Restore value if still available
        select.value = currentVal || '';
    }

    // Show/hide unit selectors based on tingkat
    @if (Auth::user()->jabatan !== 'kelompok')
        document.getElementById('tingkat').addEventListener('change', function() {
            const val = this.value;
            document.getElementById('wrap_desa_id').style.display = val === 'desa' ? '' : 'none';
            document.getElementById('wrap_kelompok_id').style.display = val === 'kelompok' ? '' : 'none';
            @if (Auth::user()->jabatan === 'daerah')
                document.getElementById('wrap_daerah_id').style.display = val === 'daerah' ? '' : 'none';

                // For desa level: load kelompoks filtered by desa when desa is selected
                if (val === 'kelompok' && document.getElementById('desa_id').value) {
                    loadKelompokByDesa(document.getElementById('desa_id').value);
                }
            @endif
            filterJabatan(val);
        });

        @if (Auth::user()->jabatan === 'daerah')
            document.getElementById('desa_id').addEventListener('change', function() {
                if (document.getElementById('tingkat').value === 'kelompok') {
                    loadKelompokByDesa(this.value);
                }
            });
        @endif

        function loadKelompokByDesa(desaId) {
            $.ajax({
                url: '/kelompok/get-by-desa/' + desaId,
                type: 'GET',
                success: function(response) {
                    const sel = document.getElementById('kelompok_id');
                    sel.innerHTML = '<option value="" disabled selected>Pilih Kelompok</option>';
                    response.forEach(k => {
                        sel.innerHTML += `<option value="${k.id}">${k.nama}</option>`;
                    });
                }
            });
        }
    @endif

    function submitKepengurusan() {
        document.getElementById('addRowButton').disabled = true;
        document.getElementById('addRowForm').submit();
    }

    // Reset modal on close
    $('#addRowModal').on('hidden.bs.modal', function() {
        const form = $(this).find('#addRowForm');
        form.trigger('reset');
        form.find('input[name="_method"]').remove();
        form.attr('action', '{{ url(' / kepengurusan ') }}');
        $(this).find('.modal-title').text('Input Data Kepengurusan');
        $(this).find('#addRowButton').prop('disabled', false);

        @if (Auth::user()->jabatan !== 'kelompok')
            document.getElementById('wrap_desa_id').style.display = 'none';
            document.getElementById('wrap_kelompok_id').style.display = 'none';
            @if (Auth::user()->jabatan === 'daerah')
                document.getElementById('wrap_daerah_id').style.display = 'none';
            @endif
            filterJabatan(null);
        @endif
    });

    // Populate modal for edit
    function editKepengurusan(id) {
        const data = @json($kepengurusan->flatten()->values());
        const row = data.find(r => r.id === id);
        if (!row) return;

        const modal = $('#addRowModal');
        modal.find('.modal-title').text('Edit Kepengurusan: ' + row.nama);

        const form = modal.find('#addRowForm');
        form.attr('action', `/kepengurusan/edit/${id}`);
        form.find('input[name="_method"]').remove();
        form.append('<input type="hidden" name="_method" value="POST">');

        modal.find('#nama').val(row.nama);
        modal.find('#jabatan_id').val(row.jabatan_id);

        @if (Auth::user()->jabatan !== 'kelompok')
            const tingkat = row.tingkat;
            modal.find('#tingkat').val(tingkat).trigger('change');

            setTimeout(() => {
                if (tingkat === 'daerah' && row.daerah_id) {
                    modal.find('#daerah_id').val(row.daerah_id);
                } else if (tingkat === 'desa' && row.desa_id) {
                    modal.find('#desa_id').val(row.desa_id);
                } else if (tingkat === 'kelompok' && row.kelompok_id) {
                    modal.find('#kelompok_id').val(row.kelompok_id);
                }
                modal.find('#jabatan_id').val(row.jabatan_id);
            }, 100);
        @endif

        modal.modal('show');
    }
</script>
