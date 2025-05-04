<div class="table-responsive">
    <table id="add-row-table" class="display table table-datatable table-striped table-hover">
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
        <tbody>
            @foreach ($generus as $data)
                @php
                    $fotoUrl =
                        $data->foto_url === 'user.png'
                            ? ($data->gender === 'L'
                                ? 'user-boy.png'
                                : 'user-girl.png')
                            : $data->foto_url;
                @endphp
                <tr>
                    <td style="max-width: 30px;">{{ $loop->iteration }}</td>
                    <td style="text-align: center"><img src="{{ asset('assets/img/foto/' . $fotoUrl) }}" alt=""
                            width="100" style="max-height: 150px"></td>
                    <td>{{ $data->nama }}</td>
                    <td>{{ \Carbon\Carbon::parse($data->tgllahir)->age }} tahun</td>
                    <td>{{ $data->kelompok }}</td>
                    <td>{{ $data->kelas }}</td>
                    <td class="{{ $data->status == 'aktif' ? 'text-success' : 'text-danger' }}">{{ $data->status }}</td>
                    <td>
                        <div class="form-button-action">
                            <!-- Button trigger modal -->
                            <button type="button" class="btn btn-link btn-primary" data-bs-toggle="modal"
                                onclick="updateAct({{ $data->id }})" data-bs-target="#updateRowModal"
                                style="padding: 10px" title="Edit">
                                <i class="fa fa-edit"></i>
                            </button>

                            <a href="{{ url('/generus/delete/' . $data->id) }}" type="button" title="Hapus"
                                class="btn btn-link btn-danger" data-original-title="Remove" data-confirm-delete="true"
                                style="padding: 10px">
                                <i class="fa fa-trash"></i>
                            </a>
                        </div>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
    @include('generus.modalEdit', ['data' => $generus])
</div>
