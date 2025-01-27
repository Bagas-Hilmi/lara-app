<div class="btn-group" role="group" aria-label="Basic example">
    @if ($row->status_capex == 'Canceled' || $row->status_capex == 'To Opex' || $row->status_capex == 'On Progress')
    <button type="button" class="btn bg-gradient-secondary" 
        data-bs-toggle="modal" 
        data-bs-target="#edit-completion-modal" 
        data-id="{{ $row->id_capex_completion }}" 
        data-capex-id="{{ $row->id_capex }}" 
        data-date="{{ $row->date }}"> 
        <i class="fas fa-edit"></i>
    
    <button type="button" class="btn bg-gradient-danger delete-completion-btn" data-id="{{ $row->id_capex_completion }}">
        <i class="fas fa-trash-alt"></i>
    </button>
    @endif
</div>