<div class="modal fade" id="new-progress-modal" tabindex="-1" aria-labelledby="newProgressModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header" style="background-color: #42bd37;">
                <h4 class="modal-title" id="newProgressModalLabel" style="color: white;">Add New Progress</h4>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="new-progress-form" action="{{ route('capex.store') }}" method="POST">
                    <input type="hidden" name="flag" value="add-progress">
                    <input type="hidden" id="new_progress_capex_id" name="id_capex" value="">
                    @csrf
                    <div class="mb-3 row">
                        <label for="tanggal" class="form-label">Description</label>
                        <div class="col">
                            <input type="date" class="form-control" id="tanggal" name="tanggal" style="text-align: center;" required>
                        </div>
                    </div>
                    <div class="mb-3 row">
                        <label for="description" class="form-label">Description</label>
                        <div class="col">
                            <input type="text" class="form-control" id="description" name="description" style="text-align: center;" required>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn bg-gradient-secondary" data-bs-dismiss="modal">Close</button>
                        <button type="submit" class="btn bg-gradient-success">Submit</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>