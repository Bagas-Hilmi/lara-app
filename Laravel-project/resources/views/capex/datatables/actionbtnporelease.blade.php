<div class="btn-group" role="group" aria-label="Basic example">
    @if ($row->status_capex == 'Canceled' || $row->status_capex == 'To Opex' || $row->status_capex == 'On Progress')
    <button type="button" class="btn bg-gradient-secondary" 
        data-bs-toggle="modal" 
        data-bs-target="#edit-porelease-modal" 
        data-id="{{ $row->id_capex_porelease }}" 
        data-capex-id="{{ $row->id_capex }}" 
        data-description-porelease="{{ $row->description }}" 
        data-porelease="{{ $row->PO_release }}"> 
        <i class="fas fa-edit"></i> 
    </button>

    <button type="button" class="btn bg-gradient-danger delete-porelease-btn" 
        data-id="{{ $row->id_capex_porelease }}">
        <i class="fas fa-trash-alt"></i> 
    </button>
    @endif
    
    <button type="button" class="btn bg-gradient-info view-porelease-btn"
        data-bs-toggle="modal" 
        data-bs-target="#viewPocommitmentModal"
        data-porelease-id="{{ $row->id_capex_porelease }}">
        <i class="fas fa-eye"></i> 
    </button>
</div>