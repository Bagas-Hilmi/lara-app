<div class="btn-group" role="group" aria-label="Basic example">

    <a href="#" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#uploadPDF"
        data-id="{{ $row->id_capex }}">
        <i class="fa fa-file-arrow-up" style="font-size: 1.2rem;"></i>
    </a>

    <a href="{{ route('approve.show', $row->id_capex) }}?v={{ time() }}" class="btn bg-gradient-warning">
        <i class="fa fa-eye" style="font-size: 1.2rem;"></i>
    </a>

    <a href="#" class="btn bg-gradient-info" data-bs-toggle="modal" data-bs-target="#signatureModal"
        data-id="{{ $row->id_capex }}" 
        data-status1="{{ $row->status_approve_1}}"
        data-status2="{{ $row->status_approve_2}}"
        data-status3="{{ $row->status_approve_3}}"
        data-wbs="{{ $row->wbs_capex}}"
        data-apv_admin="{{ $row->approved_by_admin}}"
        data-apv_at_admin="{{ $row->approved_at_admin}}"
        data-apv_user="{{ $row->approved_by_user}}"
        data-apv_engineer="{{ $row->approved_by_engineer}}">
        <i class="fa fa-signature" style="font-size: 1.2rem;"></i>
    </a>

</div>
