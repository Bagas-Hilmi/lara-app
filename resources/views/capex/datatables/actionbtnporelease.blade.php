<div class="btn-group" role="group" aria-label="Basic example">
    <button type="button" class="btn bg-gradient-secondary" 
        data-bs-toggle="modal" 
        data-bs-target="#edit-porelease-modal" 
        data-id="{{ $row->id_capex_porelease }}" 
        data-capex-id="{{ $row->id_capex }}" 
        data-description-porelease="{{ $row->description }}" 
        data-porelease="{{ $row->PO_release }}"> 
        <i class="fas fa-edit"></i> Edit
    </button>

    
    <button type="button" class="btn bg-gradient-danger delete-porelease-btn" data-id="{{ $row->id_capex_porelease }}">
        <i class="fas fa-trash-alt"></i> Delete
    </button>
</div>