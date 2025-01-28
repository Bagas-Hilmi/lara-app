<div class="btn-group" role="group" aria-label="Basic example">
    @if ($row->status_capex == 'Canceled' || $row->status_capex == 'To Opex' || $row->status_capex == 'On Progress')
    <button type="button" class="btn bg-gradient-secondary" 
        data-bs-toggle="modal" 
        data-bs-target="#edit-progress-modal" 
        data-id="{{ $row->id_capex_progress }}" 
        data-capex-id="{{ $row->id_capex }}" 
        data-tanggal="{{ $row->tanggal }}"
        data-description="{{ $row->description }}"> 
        <i class="fas fa-edit"></i>

    <button type="button" class="btn bg-gradient-danger delete-progress-btn" data-id="{{ $row->id_capex_progress }}">
        <i class="fas fa-trash-alt"></i>
    </button>
    @endif
</div>