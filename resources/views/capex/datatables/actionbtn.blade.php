<div class="dropdown">
    <button class="btn btn-sm btn-secondary dropdown-toggle" type="button" id="actionDropdown_{{ $row->id }}" data-bs-toggle="dropdown" aria-expanded="false">
        Actions
    </button>
    <ul class="dropdown-menu" aria-labelledby="actionDropdown_{{ $row->id }}">
        <li>
            <a class="dropdown-item" href="#" id="edit-button" 
               data-id="{{ $row->id_capex }}" 
               data-project_desc="{{ $row->project_desc }}" 
               data-wbs_capex="{{ $row->wbs_capex }}" 
               data-remark="{{ $row->remark }}" 
               data-request_number="{{ $row->request_number }}" 
               data-requester="{{ $row->requester }}" 
               data-capex_number="{{ $row->capex_number }}" 
               data-amount_budget="{{ $row->amount_budget }}" 
               data-status_capex="{{ $row->status_capex }}" 
               data-budget_type="{{ $row->budget_type }}" 
               data-startup="{{ $row->startup }}" 
               data-expected_completed="{{ $row->expected_completed}}" 
               data-bs-toggle="modal" 
               data-bs-target="#edit-form">
                <i class="fas fa-edit"></i> Edit Capex
            </a>
        </li>
        
        <li>
            <a class="dropdown-item text-danger delete-capex" data-id="{{ $row->id_capex }}">
                <i class="fas fa-trash-alt"></i> Delete Capex
            </a>
        </li>

        <li>
            <a class="dropdown-item" href="#" id="view-budget" 
                data-id="{{ $row->id_capex }}" 
                data-bs-toggle="modal" 
                data-bs-target="#budget-modal">
                <i class="fas fa-eye"></i> View Budget
            </a>
        </li>
       
        <li>
            <a class="dropdown-item" href="#" id="view-progress" 
                data-id="{{ $row->id_capex }}" 
                data-bs-toggle="modal" 
                data-bs-target="#progress-modal">
                <i class="fas fa-eye"></i> View Progress
            </a>
        </li>
    </ul>
</div>
