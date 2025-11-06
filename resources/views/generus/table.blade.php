<div class="table-responsive">
    <table id="add-row-table" class="display table table-striped table-hover">
        <thead>
            <tr>
                <th style="width: 7%">No</th>
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
        $('#add-row-table').DataTable({
            processing: true,
            serverSide: true,
            ajax: "{{ route('generus.data') }}",
            columns: [{
                    data: 'DT_RowIndex',
                    name: 'DT_RowIndex',
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
    });
</script>
