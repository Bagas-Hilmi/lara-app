<div class="modal fade" id="replaceDocFormModal" tabindex="-1" aria-labelledby="replaceDocFormModalLabel">
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

                    <div class="mb-3">
                        <label for="faglb" class="form-label">Upload FAGLB File</label>
                        <input type="file" class="form-control custom-file-input" name="faglb" id="faglb"
                            accept=".xlsx,.xls,.csv" required>
                    </div>
                    <div class="mb-3">
                        <label for="zlis1" class="form-label">Upload ZLIS1 File</label>
                        <input type="file" class="form-control custom-file-input" name="zlis1" id="zlis1"
                            accept=".xlsx,.xls,.csv" required>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn bg-gradient-secondary" data-bs-dismiss="modal">Cancel</button>
                <button type="submit" form="updateForm" id="updateButton"
                    class="btn bg-gradient-success">Update</button>
            </div>
        </div>
    </div>
</div>

<script>
    // Handle view button click
    $(document).on("click", ".view", function() {
        var id = $(this).data("id");
        $("#modal-id-head").val(id);
    });

    // Handle form submission for updating file
    $("#updateForm").on("submit", function(e) {
        e.preventDefault();
        var formData = new FormData(this);

        Swal.fire({
            title: 'Konfirmasi',
            text: 'Apakah Anda ingin memperbarui file ini?',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Ya, perbarui!',
            cancelButtonText: 'Batal'
        }).then((result) => {
            if (result.isConfirmed) {
                // Jika pengguna mengonfirmasi, lanjutkan dengan AJAX
                $.ajax({
                    url: $(this).attr("action"),
                    type: "POST",
                    data: formData,
                    processData: false,
                    contentType: false,
                    success: function(response) {
                        if (response.success) {
                            $("#replaceDocFormModal").modal("hide");
                            $("#faglb-table").DataTable().ajax.reload();
                            Swal.fire("Sukses", "File berhasil diperbarui", "success");
                        } else {
                            Swal.fire(
                                "Error",
                                response.message ||
                                "Terjadi kesalahan saat memperbarui file",
                                "error"
                            );
                        }
                    },
                    error: function(xhr) {
                        var errorMessage = "Terjadi kesalahan saat memperbarui file";
                        if (xhr.responseJSON && xhr.responseJSON.errors) {
                            errorMessage = Object.values(xhr.responseJSON.errors)
                                .flat()
                                .join("<br>");
                        } else if (xhr.responseJSON && xhr.responseJSON.message) {
                            errorMessage = xhr.responseJSON.message;
                        }
                        Swal.fire("Error", errorMessage, "error");
                    },
                });
            }
        });
    });

    // Reset form when modal is closed
    $("#replaceDocFormModal").on("hidden.bs.modal", function() {
        $("#updateForm")[0].reset();
    });
</script>
