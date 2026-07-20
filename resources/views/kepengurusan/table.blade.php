<style>
    th {
        text-align: center;
        vertical-align: middle;
    }

    @media print {
        /* Hide everything except the print area */
        body * {
            visibility: hidden;
        }

        #print-area,
        #print-area * {
            visibility: visible;
        }

        #print-area {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            padding: 20px;
        }

        /* Hide action column */
        .no-print {
            display: none !important;
        }

        /* Show print-only unit titles */
        .print-unit-title {
            display: block !important;
            font-weight: bold;
            font-size: 13px;
            padding: 6px 10px;
            border-bottom: 1px solid #000;
            margin-bottom: 4px;
        }

        /* Clean table borders for print */
        table {
            border-collapse: collapse;
            width: 100%;
            font-size: 12px;
        }

        table th,
        table td {
            border: 1px solid #000 !important;
            padding: 4px 8px !important;
        }

        .print-title {
            text-align: center;
            font-size: 16px;
            font-weight: bold;
            margin-bottom: 4px;
        }

        .print-subtitle {
            text-align: center;
            font-size: 12px;
            margin-bottom: 16px;
        }
    }
</style>

<div class="row">
    {{-- Filter bar (daerah & desa users only) --}}
    @if (Auth::user()->jabatan !== 'kelompok')
        <div class="row mb-3">
            <form action="{{ url('/kepengurusan') }}" method="GET" class="row g-3">

                @if (Auth::user()->jabatan === 'daerah')
                    <div class="col-sm-3">
                        <div class="form-group">
                            <label>Tingkat</label>
                            <select name="tingkat_filter" class="form-control">
                                <option value="">Semua Tingkat</option>
                                <option value="daerah" {{ request('tingkat_filter') === 'daerah' ? 'selected' : '' }}>
                                    Daerah</option>
                                <option value="desa" {{ request('tingkat_filter') === 'desa' ? 'selected' : '' }}>
                                    Desa</option>
                                <option value="kelompok"
                                    {{ request('tingkat_filter') === 'kelompok' ? 'selected' : '' }}>Kelompok</option>
                            </select>
                        </div>
                    </div>
                    <div class="col-sm-3">
                        <div class="form-group">
                            <label>Desa</label>
                            <select name="id_desa" id="id_desa_filter" class="form-control">
                                <option value="">Semua Desa</option>
                                @foreach ($desas as $desa)
                                    <option value="{{ $desa->id }}"
                                        {{ request('id_desa') == $desa->id ? 'selected' : '' }}>
                                        {{ $desa->nama }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="col-sm-3">
                        <div class="form-group">
                            <label>Kelompok</label>
                            <select name="id_klmpk" id="id_klmpk_filter" class="form-control">
                                <option value="">Semua Kelompok</option>
                                @foreach ($kelompoks as $k)
                                    <option value="{{ $k->id }}"
                                        {{ request('id_klmpk') == $k->id ? 'selected' : '' }}>
                                        {{ $k->nama }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                @endif

                <div class="col-sm-3 d-flex align-items-end align-items-md-center mt-lg-5 mt-3">
                    <button type="submit" class="btn btn-primary me-2">Tampilkan</button>
                    <a href="{{ url('/kepengurusan') }}" class="btn btn-secondary">Reset</a>
                </div>
            </form>
        </div>
    @endif

    {{-- Tables grouped by tingkat, then unit name --}}
    <div id="print-area" class="row">
        <div class="print-title">Data Kepengurusan</div>
        <div class="print-subtitle">Dicetak pada: {{ now()->translatedFormat('d F Y') }}</div>

        @forelse (['daerah', 'desa', 'kelompok'] as $tingkat)
            @if ($kepengurusan->has($tingkat))
                @php
                    $bgClass = match($tingkat) {
                        'daerah'   => 'bg-primary',
                        'desa'     => 'bg-success',
                        default    => 'bg-secondary',
                    };
                @endphp

                {{-- Section divider --}}
                <div class="col-12 mt-3 mb-1 no-print">
                    <h6 class="text-uppercase text-muted fw-bold border-bottom pb-1">
                        {{ ucfirst($tingkat) }}
                    </h6>
                </div>

                @foreach ($kepengurusan[$tingkat] as $unitName => $rows)
                    <div class="col-12 col-lg-6 mb-3">
                        <div class="card h-100">
                            <div class="card-header rounded-top {{ $bgClass }} d-flex justify-content-between align-items-center no-print">
                                <h5 class="card-title text-light mb-0">{{ $unitName }}</h5>
                            </div>
                            <div class="print-unit-title" style="display:none">
                                {{ ucfirst($tingkat) }}: {{ $unitName }}
                            </div>
                            <div class="table-responsive p-3">
                                <table class="display table table-striped table-hover">
                                    <thead>
                                        <tr>
                                            <th style="width:5%">No</th>
                                            <th>Nama</th>
                                            <th>No HP</th>
                                            <th>Jabatan</th>
                                            <th style="width:12%" class="no-print">Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($rows as $row)
                                            <tr>
                                                <td>{{ $loop->iteration }}</td>
                                                <td>{{ $row->nama }}</td>
                                                <td>{{ $row->no_hp ?? '-' }}</td>
                                                <td>{{ $row->nama_jabatan }}</td>
                                                <td class="no-print">
                                                    <div class="form-button-action">
                                                        <button type="button" class="btn btn-link btn-primary"
                                                            onclick="editKepengurusan({{ $row->id }})"
                                                            style="padding:10px" title="Edit">
                                                            <i class="fa fa-edit"></i>
                                                        </button>
                                                        <a href="{{ url('/kepengurusan/delete/' . $row->id) }}"
                                                            class="btn btn-link btn-danger" data-confirm-delete="true"
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
                        </div>
                    </div>
                @endforeach
            @endif
        @empty
            <div class="col-12 text-center text-muted py-4">
                Belum ada data kepengurusan.
            </div>
        @endforelse
    </div>
</div>

@if (Auth::user()->jabatan === 'daerah')
    <script>
        $(document).ready(function() {
            $('#id_desa_filter').change(function() {
                const desaId = $(this).val();
                const sel = $('#id_klmpk_filter');
                sel.prop('disabled', !desaId);
                sel.html('<option value="">Semua Kelompok</option>');

                if (desaId) {
                    $.get('/kelompok/get-by-desa/' + desaId, function(response) {
                        response.forEach(k => {
                            sel.append(`<option value="${k.id}">${k.nama}</option>`);
                        });
                    });
                }
            });
        });
    </script>
@endif
