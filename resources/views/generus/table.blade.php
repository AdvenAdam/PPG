<div class="row mb-3">
    <div class="col-md-3 col-6">
        <select id="filter-kelas" class="form-control">
            <option value="">-- Filter Kelas --</option>
            @foreach ($kelas as $k)
                <option value="{{ $k->id }}">{{ $k->nama }}</option>
            @endforeach
        </select>
    </div>
    @if (Auth::user()->jabatan != 'kelompok')
        <div class="col-md-3 col-6">
            <select id="filter-kelompok" class="form-control">
                <option value="">-- Filter Kelompok --</option>
                @foreach ($kelompok as $k)
                    <option value="{{ $k->id }}">{{ $k->nama }}</option>
                @endforeach
            </select>
        </div>
    @endif
</div>



<div class="table-responsive">
    <table id="add-row-table" class="display table table-striped table-hover">
        <thead>
            <tr>
                <th style="width: 7%">ID</th>
                <th style="width: 8%">Foto</th>
                <th>Nama</th>
                <th>Umur</th>
                <th>Kelompok</th>
                <th>Kelas</th>
                <th>Status</th>
                <th style="width: 10%">Action</th>
            </tr>
        </thead>
    </table>
    @include('generus.modalEdit', ['data' => $generus])
</div>
<script>
    $(function() {
        let table = $('#add-row-table').DataTable({
            processing: true,
            serverSide: true,
            ajax: {
                url: "{{ route('generus.data') }}",
                data: function(d) {
                    d.kelas = $('#filter-kelas').val();
                    d.kelompok = $('#filter-kelompok').val();
                    d.desa = $('#filter-desa').val();
                }
            },
            columns: [{
                    data: 'generus_id',
                    name: 'generus_id',
                    orderable: false,
                    searchable: false
                },
                {
                    data: 'foto',
                    name: 'foto',
                    orderable: false,
                    searchable: false
                },
                {
                    data: 'nama',
                    name: 'generus.nama'
                },
                {
                    data: 'umur',
                    name: 'generus.tgllahir'
                },
                {
                    data: 'kelompok',
                    name: 'kelompok.nama'
                },
                {
                    data: 'kelas',
                    name: 'kelas.nama'
                },
                {
                    data: 'status',
                    name: 'generus.status'
                },
                {
                    data: 'action',
                    name: 'action',
                    orderable: false,
                    searchable: false
                }
            ]
        });
        $('#filter-kelas, #filter-kelompok').change(function() {
            table.draw();
        });
    });
</script>
