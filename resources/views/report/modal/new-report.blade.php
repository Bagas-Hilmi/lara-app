<div class="modal fade" id="new-report-modal" tabindex="-1" aria-labelledby="newReportModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-sm modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header" style="background-color: #42bd37;">
                <h4 class="modal-title" id="newReportModalLabel" style="color: white;">Add New Completion</h4>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="new-report-modal-form" action="{{ route('capex.store') }}" method="POST">
                    <input type="hidden" name="flag" value="add-completion">
                    <input type="hidden" id="new_completion_capex_id" name="id_capex" value="">
                    @csrf
                    <div class="mb-3 row">
                        <div class="col-12">
                            <label for="description" class="form-label font-weight-bold">Description</label>
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

<button id="newReportButton" class="btn btn-sm btn-primary" 
    style="background-color: #09170a; border-color: #09170a;"  
    data-bs-toggle="modal" 
    data-bs-target="#new-report-modal">
    New Report
</button>
