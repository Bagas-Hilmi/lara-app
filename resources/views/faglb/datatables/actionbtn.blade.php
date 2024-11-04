<div>
    @if ($row->report_status == 0)
        <div style="display: flex; align-items: center;">
            <a class="view btn bg-gradient-secondary btn-sm" data-id="{{ $row->id_head }}"
                data-bs-toggle="modal" data-bs-target="#replaceDocFormModal">
                <i class="fas fa-edit" style="font-size: 1rem;"></i> Edit
            </a>
            <a class="view btn bg-gradient-danger btn-sm delete-btn" data-id="{{ $row->id_head }}"
                data-bs-toggle="tooltip" data-bs-placement="right">
                <i class="fas fa-trash-alt" style="font-size: 1rem;"></i> Delete
            </a>
        </div>
    @endif
    <div style="display: flex; align-items: center;"">
        <a href="{{ route('faglb.show', ['faglb' => $row, 'flag' => 'show_faglb']) }}"
            class="btn bg-gradient-info btn-sm">
            <i class="fas fa-eye" style="font-size: 1rem;"></i> FAGLB
        </a>
        <a href="{{ route('faglb.show', ['faglb' => $row, 'flag' => 'show_zlis1']) }}"
            class="btn bg-gradient-info btn-sm">
            <i class="fas fa-eye" style="font-size: 1rem;"></i> ZLIS1
        </a>
    </div>
</div>

<style>
    .action-btn {
        flex: 1;
        display: flex;
        align-items: center;
        justify-content: center;
        padding: 6px 12px;
    }

    .action-btn i {
        margin-right: 5px;
    }

    .btn-group {
        width: 100%;
    }
</style>
