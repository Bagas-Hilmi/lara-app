<div class="btn-group" role="group" aria-label="Basic example">
    @if ($row->report_status == 0)
            <button class="view btn bg-gradient-secondary btn-sm action-btn" data-id="{{ $row->id_head }}"
                data-bs-toggle="modal" data-bs-target="#replaceDocFormModal">
                <i class="fas fa-edit" style="font-size: 1rem;"></i>
            </button>
            <button class="view btn bg-gradient-danger btn-sm action-btn delete-btn" data-id="{{ $row->id_head }}"
                data-bs-toggle="tooltip" data-bs-placement="right">
                <i class="fas fa-trash-alt" style="font-size: 1rem;"></i>
            </button>
    @endif
        <button href="{{ route('faglb.show', ['faglb' => $row, 'flag' => 'show_faglb']) }}"
            class="btn bg-gradient-info btn-sm action-btn">
            <i class="fas fa-eye" style="font-size: 1rem;"></i> FAGLB
        </button>
        <button href="{{ route('faglb.show', ['faglb' => $row, 'flag' => 'show_zlis1']) }}"
            class="btn bg-gradient-info btn-sm action-btn">
            <i class="fas fa-eye" style="font-size: 1rem;"></i> ZLIS1
        </button>
</div>


