@if ($row->report_status == 0)
<div style="display: flex; align-items: center;">
    <a href="{{ route('faglb.edit', $row->id_head) }}" 
       class="update-btn"
       data-bs-toggle="tooltip" 
       data-bs-placement="top"
       title="Edit This Entry"
       style="width: 40px; height: 40px; padding: 0; display: flex; align-items: center; justify-content: center; margin-right: 5px; background-color: #778594; border-radius: 5px; color: white;">
        <i class="fa fa-edit" style="font-size: 1.4rem;"></i>
    </a>

    <a class="delete-btn" 
    data-id="{{ $row->id_head }}"
    data-bs-toggle="tooltip" 
    data-bs-placement="right"
    style="width: 40px; height: 40px; padding: 0; display: flex; align-items: center; justify-content: center; background-color: #dc3545; border-radius: 5px; color: white;">
     <i class="fas fa-trash" style="font-size: 1.4rem;"></i>
 </a>
 </a>
 
</div>
@endif

<a href="{{ route('faglb.show', $row->id_head) }}" 
   class="view btn bg-gradient-info btn-sm"
   style="margin-top: 5px; width: 40%; display: inline-block;">View FAGLB</a>


<a href="{{ route('faglb.zlis1', $row->id_head) }}" 
   class="btn bg-gradient-warning btn-sm"
   style="margin-top: 5px; width: 40%; display: inline-block;">View ZLIS1</a>
   