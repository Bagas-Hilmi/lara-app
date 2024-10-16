<div class="btn-group" role="group" aria-label="Basic example">
    <button type="button" class="btn bg-gradient-secondary" onclick="editBudget({{ $row->id_capex_completion }})">
        <i class="fas fa-edit"></i> Edit
    </button>
    
    <button type="button" class="btn bg-gradient-danger delete-completion-btn" data-id="{{ $row->id_capex_completion }}">
        <i class="fas fa-trash-alt"></i> Delete
    </button>
</div>