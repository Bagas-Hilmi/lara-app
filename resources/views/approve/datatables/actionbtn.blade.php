<div class="btn-group" role="group" aria-label="Basic example">

    <a href="#" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#uploadPDF"
        data-id="{{ $row->id_capex }}">
        <i class="fa fa-file-arrow-up" style="font-size: 1.2rem;"></i>
    </a>
    @if (isset($row->file_pdf) &&
            $row->file_pdf &&
            file_exists(storage_path('app/public/uploads/capexFiles/' . $row->file_pdf)))
        <a href="{{ route('approve.show', $row->id_capex) }}" class="btn bg-gradient-warning">
            <i class="fa fa-eye" style="font-size: 1.2rem;"></i>
        </a>
    @else
        <!-- Jika file tidak ada, jangan tampilkan tombol -->
    @endif

</div>
