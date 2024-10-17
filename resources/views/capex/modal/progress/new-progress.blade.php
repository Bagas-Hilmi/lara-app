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
                        <label for="tanggal" class="form-label font-weight-bold">Date</label>
                        <div class="col">
                            <input type="date" class="form-control" id="tanggal" name="tanggal" style="text-align: center;" required>
                        </div>
                    </div>
                    <div class="mb-3 row">
                        <label for="description" class="form-label font-weight-bold">Description</label>
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

<script>
    $('#new-progress-form').on('submit', function (e) {
        e.preventDefault(); // Mencegah pengiriman otomatis form
        const form = this; // Simpan referensi form
        // Cek apakah form valid sebelum lanjut
        if (form.checkValidity()) {
            var formData = $(this).serialize();
            console.log("Form Data: ", formData); // Log form data sebelum dikirim
            
            Swal.fire({
                title: 'Apakah Anda yakin?',
                text: "Progress ini akan ditambahkan!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Ya, tambahkan!',
                cancelButtonText: 'Batal'
            }).then((result) => {
                if (result.isConfirmed) {
                    // Jika pengguna menekan "Ya, tambahkan!", maka lakukan AJAX request
                    $.ajax({
                        url: $(this).attr('action'), // Sesuaikan dengan route Anda
                        method: 'POST',
                        data: formData,
                        success: function (response) {
                            $('#new-progress-modal').modal('hide');
                            $('#progress-table').DataTable().ajax.reload();
                            Swal.fire({
                                title: 'Berhasil!',
                                text: 'Progress berhasil ditambahkan!',
                                icon: 'success',
                                confirmButtonText: 'OK'
                            }).then(() => {
                                // Refresh halaman setelah menutup pesan sukses
                                $('#capex-table').DataTable().ajax.reload(); // Reload DataTable
                            });
                        },
                        error: function (xhr) {
                            console.log("Error: ", xhr.responseText); // Log kesalahan
                            Swal.fire({
                                title: 'Terjadi kesalahan!',
                                text: 'Error: ' + xhr.responseText,
                                icon: 'error',
                                confirmButtonText: 'OK'
                            });
                        }
                    });
                }
            });
        } else {
            // Jika form tidak valid, tampilkan pesan kesalahan
            Swal.fire({
                title: 'Error!',
                text: 'Silakan lengkapi semua input yang diperlukan.',
                icon: 'error',
                confirmButtonText: 'OK'
            });
        }
    });
</script>
