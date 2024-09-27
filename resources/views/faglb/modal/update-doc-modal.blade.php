<div class="modal fade" id="replaceDocFormModal" tabindex="-1" aria-labelledby="replaceDocFormModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header" style="background-color: #42bd37;">
                <h5 class="modal-title" id="replaceDocFormModalLabel" style="color: white;">Update File</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="updateForm" action="{{ route('faglb.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <input type="hidden" name="flag" value="update_file">
                    <input type="hidden" name="id_head" id="modal-id-head">
                    <input type="hidden" id="id_ccb" name="id_ccb" value="">
                    <input type="hidden" id="period" name="period" value="">
                    
                    <div class="mb-3">
                        <label for="faglb" class="form-label">Upload FAGLB File</label>
                        <input type="file" class="form-control" name="faglb" id="faglb">
                    </div>
                    <div class="mb-3">
                        <label for="zlis1" class="form-label">Upload ZLIS1 File</label>
                        <input type="file" class="form-control" name="zlis1" id="zlis1" >
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn bg-gradient-secondary" data-bs-dismiss="modal">Close</button>
                <button type="button" id="updateButton" class="btn bg-gradient-success">Update</button>
            </div>
        </div>
    </div>
</div>



<script>
    var updateUrl = "{{ route('faglb.store') }}"; // URL endpoint
</script>
