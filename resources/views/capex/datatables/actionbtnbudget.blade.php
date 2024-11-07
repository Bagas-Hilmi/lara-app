<div class="btn-group" role="group" aria-label="Basic example">
    <button type="button" class="btn bg-gradient-secondary" 
        data-bs-toggle="modal" 
        data-bs-target="#edit-budget-modal" 
        data-id="{{ $row->id_capex_budget }}" 
        data-capex-id="{{ $row->id_capex }}" 
        data-description="{{ $row->description }}" 
        data-budget-cos="{{ $row->budget_cos }}">
        <i class="fas fa-edit"></i>
    </button>

    <button type="button" class="btn bg-gradient-danger delete-budget-btn" data-id="{{ $row->id_capex_budget }}">
        <i class="fas fa-trash-alt"></i>
    </button>
    
</div>