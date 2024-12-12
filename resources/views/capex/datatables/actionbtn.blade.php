<div class="dropdown dropdown-1">
    <button class="btn btn-sm btn-primary dropdown-toggle" type="button" id="actionDropdown_{{ $row->id }}" data-bs-toggle="dropdown" aria-expanded="false">
        <i class="fas fa-cog me-1"></i> Actions
    </button>
    <ul class="dropdown-menu" aria-labelledby="actionDropdown_{{ $row->id }}">
        @if ($row->status_capex == 'Canceled' || $row->status_capex == 'To Opex' || $row->status_capex == 'On Progress')
        <li>
            <a class="dropdown-item d-flex align-items-center" href="#" id="edit-button" 
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
                data-wbs_number="{{ $row->wbs_number}}" 
                data-cip_number="{{ $row->cip_number}}" 
                data-category="{{ $row->category}}" 
                data-bs-toggle="modal" 
                data-bs-target="#edit-form">
                <i class="fas fa-edit text-primary me-2 icon-large"></i> Edit Capex
            </a>
        </li>
        <li>
            <a class="dropdown-item d-flex align-items-center text-danger delete-capex" href="#" data-id="{{ $row->id_capex }}">
                <i class="fas fa-trash-alt me-2 icon-large"></i> Delete Capex
            </a>            
        </li>
        @endif

        <li>
            <a class="dropdown-item d-flex align-items-center" href="#" id="view-budget" 
                data-id="{{ $row->id_capex }}" 
                data-bs-toggle="modal" 
                data-bs-target="#budget-modal">
                <i class="fas fa-dollar-sign text-success me-2 icon-large"></i> Budget COS
            </a>
        </li>
        <li>
            <a class="dropdown-item d-flex align-items-center" href="#" id="view-progress" 
                data-id="{{ $row->id_capex }}" 
                data-bs-toggle="modal" 
                data-bs-target="#progress-modal">
                <i class="fas fa-tasks text-info me-2 icon-large"></i> Progress
            </a>
        </li>
        <li>
            <a class="dropdown-item d-flex align-items-center" href="#" id="view-porelease" 
                data-id="{{ $row->id_capex }}" 
                data-bs-toggle="modal" 
                data-bs-target="#porelease-modal">
                <i class="fas fa-file-invoice text-warning me-2 icon-large"></i> PO Release
            </a>
        </li>
        <li>
            <a class="dropdown-item d-flex align-items-center" href="#" id="view-completion" 
                data-id="{{ $row->id_capex }}" 
                data-bs-toggle="modal" 
                data-bs-target="#completion-modal">
                <i class="fas fa-calendar-check text-success me-2 icon-large"></i> Completion Date
            </a>
        </li>
        <li>
            <a class="dropdown-item d-flex align-items-center" href="#" id="view-status" 
                data-id="{{ $row->id_capex }}" 
                data-bs-toggle="modal" 
                data-bs-target="#status-modal">
                <i class="fas fa-info-circle text-primary me-2 icon-large"></i> Status Log
            </a>
        </li>
    </ul>
</div>