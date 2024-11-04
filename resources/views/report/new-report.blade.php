<div class="modal fade" id="signatureModal" tabindex="-1" aria-labelledby="signatureModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="signatureModalLabel">Input Signature Name</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="signatureForm">
                    <div class="form-group">
                        <label for="signatureName">Name Signature</label>
                        <input type="text" class="form-control" id="signatureName" required>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary" onclick="proceedWithDownload()">Download PDF</button>
            </div>
        </div>
    </div>
</div>