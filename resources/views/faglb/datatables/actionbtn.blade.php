@if ($row->report_status == 0)
<div style="display: flex; align-items: center; justify-content: space-between; gap: 5px;">

   <a class="view btn bg-gradient-secondary btn-sm"
      data-id="{{ $row->id_head }}" 
      data-bs-toggle="modal"
      data-bs-target="#replaceDocFormModal"
      title="Edit This Entry"
      style="flex: 1; display: flex; align-items: center; justify-content: center; background-color: #778594; color: white;">
        <i class="fa fa-edit" style="font-size: 1.2rem;"></i>
    </a>

    <a class="view btn bg-gradient-danger btn-sm delete-btn" 
       data-id="{{ $row->id_head }}"
       data-bs-toggle="tooltip" 
       data-bs-placement="right" 
       title="Delete This Entry"
       style="flex: 1; display: flex; align-items: center; justify-content: center; background-color: #dc3545; color: white;">
        <i class="fas fa-trash" style="font-size: 1.2rem;"></i>
    </a>
</div>
@endif
<div style="display: flex; align-items: center; justify-content: space-between; gap: 5px;;">
    <!-- Tombol View FAGLB -->
    <a href="{{ route('faglb.show', $row->id_head) }}" 
       class="btn bg-gradient-info btn-sm"
       data-bs-toggle="tooltip" 
       data-bs-placement="top"
       title="View FAGLB File"
       style="flex: 1; display: flex; align-items: center; justify-content: center;">
       <i class="fas fa-eye" style="font-size: 1.2rem; margin-right: 5px;"></i> FAGLB
    </a>
    <a href="{{ route('faglb.zlis1', $row->id_head) }}" 
       class="btn bg-gradient-warning btn-sm"
       data-bs-toggle="tooltip" 
       data-bs-placement="top"
       title="View ZLIS1 File"
       style="flex: 1; display: flex; align-items: center; justify-content: center;">
       <i class="fas fa-eye" style="font-size: 1.2rem; margin-right: 5px;"></i> ZLIS1
    </a>
</div>

