<style>
    .text-truncate-container {
        width: 100%;
    }

    .text-truncate-container p {
        -webkit-line-clamp: 1;
        display: -webkit-box;
        -webkit-box-orient: vertical;
        overflow: hidden;
    }
</style>

<div class="row">
    @foreach ($kurikulums as $data)
        <div class="col-sm-6 col-lg-4">
            <div class="card card-stats card-round">
                <div class="card-body">
                    <div class="row align-items-center">
                        <div class="col-icon">
                            @if ($data->file_ext == 'pdf')
                                <div class="icon-big text-center icon-danger bubble-shadow-small">
                                    <i class="fas fa-file-pdf"></i>
                                </div>
                            @elseif ($data->file_ext == 'docx')
                                <div class="icon-big text-center icon-info bubble-shadow-small">
                                    <i class="fas fa-file-word"></i>
                                </div>
                            @elseif ($data->file_ext == 'xlsx' || $data->file_ext == 'xls' || $data->file_ext == 'csv')
                                <div class="icon-big text-center icon-success bubble-shadow-small">
                                    <i class="fas fa-file-excel"></i>
                                </div>
                            @elseif ($data->file_ext == 'pptx')
                                <div class="icon-big text-center icon-warning bubble-shadow-small">
                                    <i class="fas fa-file-powerpoint"></i>
                                </div>
                            @else
                                <div class="icon-big text-center icon-primary bubble-shadow-small">
                                    <i class="fas fa-file"></i>
                                </div>
                            @endif
                        </div>
                        <div class="col col-stats ms-3 ms-sm-0">
                            <div class="numbers">
                                <div class="text-truncate-container">
                                    <p class="card-category">{{ $data->desc }}</p>
                                </div>
                                <p class="card-title d-flex">
                                    <a href="{{ url('/kurikulum/download/' . $data->id) }}" type="button"
                                        title="Hapus" class="btn btn-link ps-0">
                                        <i class="fa fa-download"></i>
                                    </a>
                                </p>
                            </div>
                        </div>
                        <div class="col-auto">
                            <a href="{{ url('/kurikulum/delete/' . $data->id) }}" type="button" title="Hapus"
                                class="btn btn-link btn-danger" data-original-title="Remove" data-confirm-delete="true"
                                style="padding: 10px">
                                <i class="fa fa-trash"></i>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endforeach
</div>
