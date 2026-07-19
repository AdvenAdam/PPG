@extends('layout.app')

@section('content')
<div class="row">
    <div class="col-md-12">
        <div class="card">
            <div class="card-header">
                <div class="d-flex align-items-center">
                    <div class="col">
                        <h4 class="card-title">Data Jabatan</h4>
                        <span>Pengelolaan Data Jabatan</span>
                    </div>
                    <button class="btn btn-primary btn-round ms-auto" data-bs-toggle="modal"
                        data-bs-target="#addJabatanModal">
                        <i class="fa fa-plus"></i>
                        Tambah
                    </button>
                </div>
            </div>
            <div class="card-body">

                {{-- Add / Edit Modal --}}
                <div class="modal fade" id="addJabatanModal" tabindex="-1" role="dialog" aria-hidden="true">
                    <div class="modal-dialog" role="document">
                        <div class="modal-content">
                            <div class="modal-header border-0">
                                <h5 class="modal-title fw-mediumbold">Input Data Jabatan</h5>
                            </div>
                            <div class="modal-body">
                                <form action="{{ url('/jabatan') }}" method="POST" id="jabatanForm"
                                    class="row g-3">
                                    @csrf
                                    <div class="col-sm-12">
                                        <div class="form-group">
                                            <label for="nama_jabatan">Nama Jabatan</label>
                                            <input type="text" name="nama" id="nama_jabatan"
                                                class="form-control" placeholder="* isi Nama Jabatan" required />
                                        </div>
                                    </div>
                                    <div class="col-sm-12">
                                        <div class="form-group">
                                            <label for="tingkat_jabatan">Tingkat</label>
                                            <select name="tingkat" id="tingkat_jabatan" class="form-control"
                                                required>
                                                <option value="" selected disabled>Pilih Tingkat</option>
                                                <option value="daerah">Daerah</option>
                                                <option value="desa">Desa</option>
                                                <option value="kelompok">Kelompok</option>
                                            </select>
                                        </div>
                                    </div>
                                </form>
                            </div>
                            <div class="modal-footer border-0">
                                <button type="button" id="jabatanSubmitBtn" class="btn btn-primary"
                                    onclick="submitJabatan()">
                                    Simpan
                                </button>
                                <button type="button" class="btn btn-danger"
                                    onclick="$('.modal').modal('hide')">
                                    Tutup
                                </button>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Table grouped by tingkat --}}
                @foreach (['daerah', 'desa', 'kelompok'] as $tingkat)
                @php $grouped = $jabatan->where('tingkat', $tingkat); @endphp
                @if ($grouped->count())
                <hr class="my-3" />
                <h5 class="text-muted text-center">Tingkat {{ ucfirst($tingkat) }}</h5>
                <div class="table-responsive">
                    <table class="display table table-striped table-hover">
                        <thead>
                            <tr>
                                <th style="width:5%">No</th>
                                <th>Nama Jabatan</th>
                                <th>Tingkat</th>
                                <th style="width:12%">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($grouped as $j)
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td>{{ $j->nama }}</td>
                                <td>
                                    <span class="badge bg-{{ $tingkat === 'daerah' ? 'primary' : ($tingkat === 'desa' ? 'success' : 'warning') }}">
                                        {{ ucfirst($j->tingkat) }}
                                    </span>
                                </td>
                                <td>
                                    <div class="form-button-action">
                                        <button type="button"
                                            class="btn btn-link btn-primary"
                                            onclick="editJabatan({{ $j->id }}, '{{ addslashes($j->nama) }}', '{{ $j->tingkat }}')"
                                            style="padding:10px" title="Edit">
                                            <i class="fa fa-edit"></i>
                                        </button>
                                        <a href="{{ url('/jabatan/delete/' . $j->id) }}"
                                            class="btn btn-link btn-danger"
                                            data-confirm-delete="true"
                                            style="padding:10px" title="Hapus">
                                            <i class="fa fa-trash"></i>
                                        </a>
                                    </div>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                @endif
                @endforeach

            </div>
        </div>
    </div>
</div>

<script>
    function submitJabatan() {
        document.getElementById('jabatanSubmitBtn').disabled = true;
        document.getElementById('jabatanForm').submit();
    }

    function editJabatan(id, nama, tingkat) {
        const modal = $('#addJabatanModal');
        modal.find('.modal-title').text('Edit Jabatan: ' + nama);
        modal.find('#jabatanForm').attr('action', `/jabatan/edit/${id}`);
        modal.find('input[name="_method"]').remove();
        modal.find('#jabatanForm').append('<input type="hidden" name="_method" value="POST">');
        modal.find('#nama_jabatan').val(nama);
        modal.find('#tingkat_jabatan').val(tingkat);
        modal.modal('show');
    }

    $('#addJabatanModal').on('hidden.bs.modal', function() {
        const form = $(this).find('#jabatanForm');
        form.trigger('reset');
        form.find('input[name="_method"]').remove();
        form.attr('action', '{{ url(' / jabatan ') }}');
        $(this).find('.modal-title').text('Input Data Jabatan');
        $(this).find('#jabatanSubmitBtn').prop('disabled', false);
    });
</script>
@endsection