// Handle view button click
$(document).on("click", ".view", function () {
    var id = $(this).data("id");
    $("#modal-id-head").val(id);
});

// Handle form submission for updating file
$("#updateForm").on("submit", function (e) {
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
                success: function (response) {
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
                error: function (xhr) {
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
$("#replaceDocFormModal").on("hidden.bs.modal", function () {
    $("#updateForm")[0].reset();
});
