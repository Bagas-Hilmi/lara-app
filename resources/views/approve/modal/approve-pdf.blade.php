<div class="modal fade" id="signatureModal" tabindex="-1" aria-labelledby="signatureModalLabel">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header" style="background-color: #17a2b8;">
                <h5 class="modal-title" id="signatureModalLabel" style="color: white;">Digital Signature</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="signatureForm" action="{{ route('approve.store') }}" method="POST">
                    @csrf
                    <input type="hidden" id="signature-id-capex" name="id_capex" value="">
                    <input type="hidden" name="flag" value="signature">

                    <div class="container-fluid">
                        <div class="row mb-3 align-items-end">
                            <div class="col-md-4">
                                <label class="form-label">User Name</label>
                                <input type="text" class="form-control" value="{{ auth()->user()->name }}" readonly>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label">Timestamp</label>
                                <input type="text" class="form-control" value="{{ now()->format('Y-m-d H:i:s') }}" readonly>
                            </div>
                            <div class="col-md-4">
                                <button type="submit" class="btn bg-gradient-success" id="saveSignature">Approve</button>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
    $(document).ready(function() {
        // Saat modal signature dibuka, set value id_capex
        $('#signatureModal').on('show.bs.modal', function(event) {
            var button = $(event.relatedTarget);
            var idCapex = button.data('id');
            $('#signature-id-capex').val(idCapex);
        });

        // Handle form submit untuk signature
        $('#signatureForm').on('submit', function(e) {
            e.preventDefault();

            let formData = new FormData(this);

            $.ajax({
                url: "{{ route('approve.store') }}",
                type: "POST",
                data: formData,
                contentType: false,
                processData: false,
                beforeSend: function() {
                    Swal.fire({
                        title: 'Saving...',
                        text: 'Please wait...',
                        allowOutsideClick: false,
                        didOpen: () => {
                            Swal.showLoading();
                        }
                    });
                },
                success: function(response) {
                    Swal.fire({
                        icon: 'success',
                        title: 'Success!',
                        text: response.success,
                        timer: 2000,
                        timerProgressBar: true
                    }).then(() => {
                        location.reload();
                    });
                },
                error: function(xhr) {
                    let errorMessage = xhr.responseJSON.error || 'Failed to save signature';
                    Swal.fire({
                        icon: 'error',
                        title: 'Error!',
                        text: errorMessage
                    });
                }
            });
        });
    });
</script>
