
@if ($row->report_status == 0)
<div style="display: flex; align-items: center;">
    <a class="bg-gradient-secondary  update-btn" 
       data-id="{{ $row->id_ccb }}"
       data-period="{{ $row->period_cip }}"
       data-bal-usd="{{ $row->bal_usd }}"
       data-bal-rp="{{ $row->bal_rp }}"
       data-cumbal-usd="{{ $row->cumbal_usd }}"
       data-cumbal-rp="{{ $row->cumbal_rp }}"
       data-bs-toggle="tooltip" 
       data-bs-placement="top"
       title="Edit This Entry"
       style="width: 35px; height: 35px; padding: 0; display: flex; align-items: center; justify-content: center; margin-right: 5px; background-color: #778594; border-radius: 5px; color: white;">
        <i class="fa fa-edit " style="font-size: 1.2rem;"></i>
    </a>

    <a class="bg-gradient-danger delete-btn" 
       data-id="{{ $row->id_ccb }}"
       data-bs-toggle="tooltip" 
       data-bs-placement="right"
       title="Delete This Entry"
       style="width: 35px; height: 35px; padding: 0; display: flex; align-items: center; justify-content: center; background-color: #dc3545; border-radius: 5px; color: white;">
        <i class="fas fa-trash" style="font-size: 1.2rem;"></i>
    </a>
</div>
@endif