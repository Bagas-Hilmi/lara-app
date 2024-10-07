<div class="dropdown">
    <button class="btn btn-sm btn-secondary dropdown-toggle" type="button" id="actionDropdown_{{ $row->id }}" data-bs-toggle="dropdown" aria-expanded="false">
        Actions
    </button>
    <ul class="dropdown-menu" aria-labelledby="actionDropdown_{{ $row->id }}">
        <li>
            <a class="dropdown-item" href="#" id="edit-button" 
               data-id="{{ $row->id }}" 
               data-project_desc="{{ $row->project_desc }}" 
               data-wbs_capex="{{ $row->wbs_capex }}" 
               data-remark="{{ $row->remark }}" 
               data-request_number="{{ $row->request_number }}" 
               data-requester="{{ $row->requester }}" 
               data-capex_number="{{ $row->capex_number }}" 
               data-amount_budget="{{ $row->amount_budget }}" 
               data-status_capex="{{ $row->status_capex }}" 
               data-budget_type="{{ $row->budget_type }}" 
               data-toggle="modal" 
               data-target="#edit-form">
                <i class="fas fa-edit"></i> Edit
            </a>
        </li>
        
        <li>
            <button class="dropdown-item text-danger delete-capex" data-id="{{ $row->id }}">
                <i class="fas fa-trash-alt"></i> Delete
            </button>
            
        </li>
    </ul>
</div>
