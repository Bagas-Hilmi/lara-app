@if ($row->report_status == 0)
    <div class="btn-group" role="group" aria-label="Basic example">

        <button type="button" class="btn bg-gradient-secondary  update-btn" 
            data-id="{{ $row->id_ccb }}"
            data-period="{{ $row->period_cip }}" 
            data-bal-usd="{{ $row->bal_usd }}" 
            data-bal-rp="{{ $row->bal_rp }}"
            data-cumbal-usd="{{ $row->cumbal_usd }}" 
            data-cumbal-rp="{{ $row->cumbal_rp }}" 
            data-bs-toggle="tooltip"
            data-bs-placement="top" title="Edit This Entry">
            <i class="fa fa-edit " style="font-size: 1rem;"></i>
        </button>

        <button type="button" class="btn bg-gradient-danger delete-btn" 
            data-id="{{ $row->id_ccb }}" 
            data-bs-toggle="tooltip"
            data-bs-placement="right" 
            title="Delete This Entry">
            <i class="fas fa-trash" style="font-size: 1rem;"></i>
        </button>
    </div>
@endif
