<div class="btn-group" role="group" aria-label="Basic example">
    @if(auth()->user()->hasRole(['admin','user']))
        
            <a href="#" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#uploadPDF"
                data-id="{{ $row->id_capex }}">
                <i class="fa fa-file-arrow-up" style="font-size: 1.2rem;"></i>
            </a>

    @endif

    @if (!empty($row->file_pdf))

        <a href="#" class="btn bg-gradient-info" data-bs-toggle="modal" data-bs-target="#signatureModal"
            data-id="{{ $row->id_capex }}"
            data-signature-detail-file="{{ $row->signature_detail_file }}"
            data-signature-closing-file="{{ $row->signature_closing_file }}"
            data-signature-acceptance-file="{{ $row->signature_acceptance }}"
            data-show-pdf="{{ $row->file_pdf }}"
            data-flag="show-form-detail"
            data-flag="show-form-closing"
            data-flag="show-form-acceptance"
            data-flag="show-pdf"
            data-status1="{{ $row->status_approve_1}}"
            data-status2="{{ $row->status_approve_2}}"
            data-status3="{{ $row->status_approve_3}}"
            data-status4="{{ $row->status_approve_4}}"
            data-wbs="{{ $row->wbs_capex}}"
            data-apv_admin1="{{ $row->approved_by_admin_1}}"
            data-apv_at_admin1="{{ $row->approved_at_admin_1}}"
            data-apv_admin2="{{ $row->approved_by_admin_2}}"
            data-apv_at_admin2="{{ $row->approved_at_admin_1}}"
            data-apv_user="{{ $row->approved_by_user}}"
            data-apv_at_user="{{ $row->approved_at_user}}"
            data-apv_engineer="{{ $row->approved_by_engineer}}"
            data-apv_at_engineer="{{ $row->approved_at_engineer}}"
            data-upload-date="{{ $row->upload_date}}">
            <i class="fa fa-signature" style="font-size: 1.2rem;"></i>
        </a>
    @endif

</div>
