<div class="btn-group" role="group" aria-label="Basic example">
    @if ($row->report_status == 0)
            <button class="view btn bg-gradient-secondary btn-sm action-btn" data-id="{{ $row->id_head }}"
                data-bs-toggle="modal" data-bs-target="#replaceDocFormModal"
                data-bs-toggle="tooltip"
                data-bs-placement="right" 
                title="Update Doc">
                <i class="fas fa-edit" style="font-size: 1rem;"></i>
            </button>
            <button class="view btn bg-gradient-danger btn-sm action-btn delete-btn" data-id="{{ $row->id_head }}"
                data-bs-toggle="tooltip"
                data-bs-placement="right" 
                title="Delete Doc">
                <i class="fas fa-trash-alt" style="font-size: 1rem;"></i>
            </button>
    @endif
            <button onclick="window.location.href='{{ route('faglb.show', ['faglb' => $row, 'flag' => 'show_faglb']) }}'"
                    class="btn bg-gradient-info btn-sm action-btn"
                    data-bs-toggle="tooltip"
                    data-bs-placement="right" 
                    title="View FALGB Doc">
                <i class="fas fa-eye" style="font-size: 1rem;"></i> FAGLB
            </button>

            <button onclick="window.location.href='{{ route('faglb.show', ['faglb' => $row, 'flag' => 'show_zlis1']) }}'"
                class="btn bg-gradient-info btn-sm action-btn"
                data-bs-toggle="tooltip"
                data-bs-placement="right" 
                title="View ZLIS1 Doc">
                <i class="fas fa-eye" style="font-size: 1rem;"></i> ZLIS1
            </button>
</div>