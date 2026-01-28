@foreach ($pengajian->Absens as $absen)
    <div class="col-md-6 col-12">
        <div class="card">
            <div class="card-header bg-secondary rounded-top">
                @php
                    $keterangan = json_decode($absen->keterangan);
                @endphp
                <h5 class="card-title text-light">{{ $absen->kelas->nama }}</h5>
                <span class="text-light">
                    Alpha : {{ $keterangan->alpha }} |
                    Sakit : {{ $keterangan->sakit }} |
                    Izin : {{ $keterangan->izin }} |
                    Hadir : {{ $keterangan->hadir }}
                </span>
            </div>

            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-bordered table-hover">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Nama</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach (json_decode($absen->absen) as $item)
                                <tr>
                                    <td>{{ $loop->iteration }}</td>
                                    <td>{{ $item->nama }}</td>
                                    <td>
                                        <div class="selectgroup">
                                            @foreach (['A' => 'alpha', 'S' => 'sakit', 'I' => 'izin', 'H' => 'hadir'] as $kode => $label)
                                                <label class="selectgroup-item">
                                                    <input type="radio" class="selectgroup-input absensi-radio"
                                                        name="absensi_{{ $absen->id }}_{{ $item->id_generus }}"
                                                        data-absen-id="{{ $absen->id }}"
                                                        data-generus-id="{{ $item->id_generus }}"
                                                        value="{{ $kode }}"
                                                        {{ $item->absen == $label ? 'checked' : '' }}>
                                                    <span class="selectgroup-button">{{ ucfirst($label) }}</span>
                                                </label>
                                            @endforeach
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
@endforeach
@push('scripts')
    <script>
        let debounceTimer = null;

        $(document).on('change', '.absensi-radio', function() {

            const el = $(this);
            const payload = {
                absen_id: el.data('absen-id'),
                generus_id: el.data('generus-id'),
                status: el.val(),
                _token: "{{ csrf_token() }}"
            };

            clearTimeout(debounceTimer);

            debounceTimer = setTimeout(() => {
                sendAbsensi(payload);
            }, 400); // 400ms setelah stop klik
        });

        function sendAbsensi(payload) {
            $.post("{{ route('pengajian.absensi.update') }}", payload)
                .done(() => showToast())
                .fail(() => alert('Gagal update'));
        }

        function showToast() {
            Swal.fire({
                toast: true,
                position: 'top-end',
                timer: 2000,
                showConfirmButton: false,
                icon: 'success',
                title: 'Absensi tersimpan'
            });
        }
    </script>
@endpush
