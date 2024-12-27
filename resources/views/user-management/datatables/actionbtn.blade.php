<div class="btn-group" role="group" aria-label="Basic example">

    <button type="button" class="btn bg-gradient-info  update-btn" 
        data-id="{{ $row->id}}"
        data-name="{{ $row->name}}"
        data-email="{{ $row->email}}"
        data-bs-toggle="tooltip"
        data-bs-placement="top" title="Edit This Entry">
        <i class="fa fa-edit " style="font-size: 1rem;"></i>
    </button>

    <button type="button" class="btn bg-gradient-danger delete-btn" 
        data-id="{{ $row->id}}" 
        data-bs-toggle="tooltip"
        data-bs-placement="right" 
        title="Delete This Entry">
        <i class="fas fa-trash" style="font-size: 1rem;"></i>
    </button>

</div>