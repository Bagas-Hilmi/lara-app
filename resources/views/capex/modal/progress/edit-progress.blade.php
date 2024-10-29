<div class="modal fade" id="edit-progress-modal" tabindex="-1" aria-labelledby="editProgressModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header" style="background-color: #42bd37;">
                <h4 class="modal-title" id="editProgressModalLabel" style="color: white;">Edit Progress</h4>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="edit-progress-form" action="{{ route('capex.store') }}" method="POST">
                    <input type="hidden" id="edit_progress_id" name="id" value="">
                    <input type="hidden" name="flag" value="edit-progress">
                    <input type="hidden" id="edit_progress_capex_id" name="id_capex" value="">
                    @csrf
                    <div class="mb-3 row">
                        <label for="edit_tanggal" class="form-label font-weight-bold">Date</label>
                        <div class="col">
                            <input type="date" class="form-control" id="edit_tanggal" name="tanggal"
                                style="text-align: center;" required>
                        </div>
                    </div>
                    <div class="mb-3 row">
                        <label for="edit_description" class="form-label font-weight-bold">Description</label>
                        <div class="col">
                            <input type="text" class="form-control" id="edit_description" name="description"
                                style="text-align: center;" required>
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
    function editProgress(id) {
        $.ajax({
            url: '/capex/' + id + '/edit', // Sesuaikan dengan route edit Anda
            method: 'GET',
            success: function(data) {
                // Set nilai ke input dalam modal
                $('#edit_progress_id').val(data.id_capex_progress); // ID progress
                $('#edit_progress_capex_id').val(data.id_capex); // ID capex
                $('#edit_tanggal').val(data.tanggal);
                $('#edit_description').val(data.description);

                // Tampilkan modal
                $('#edit-progress-modal').modal('show');
            },
            error: function(xhr) {
                console.log("Error: ", xhr.responseText); // Log kesalahan
                alert('Terjadi kesalahan saat mengambil data.');
            }
        });
    }

    $('#edit-progress-form').on('submit', function(e) {
        e.preventDefault(); // Mencegah reload halaman
        var formData = $(this).serialize(); // Mengambil data dari form

        // Tampilkan konfirmasi sebelum mengirim data
        Swal.fire({
            title: 'Konfirmasi',
            text: 'Apakah Anda yakin ingin menyimpan perubahan ini?',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Ya, simpan!',
            cancelButtonText: 'Tidak'
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    url: $(this).attr('action'), // URL untuk mengirim request
                    method: 'POST',
                    data: formData,
                    success: function(response) {
                        $('#edit-progress-modal').modal('hide'); // Menyembunyikan modal
                        $('#progress-table').DataTable().ajax.reload(); // Reload DataTable
                        Swal.fire({
                            title: 'Berhasil!',
                            text: response.message, // Menampilkan pesan sukses
                            icon: 'success',
                            confirmButtonText: 'OK'
                        });
                        $('#capex-table').DataTable().ajax.reload(); // Reload DataTable
                    },
                    error: function(xhr) {
                        console.log("Error: ", xhr.responseText); // Log kesalahan
                        Swal.fire({
                            title: 'Error!',
                            text: 'Terjadi kesalahan: ' + xhr
                            .responseText, // Menampilkan pesan error
                            icon: 'error',
                            confirmButtonText: 'OK'
                        });
                    }
                });
            }
        });
    });
</script>


<script>
    // Ketika modal ditampilkan, ambil data dari tombol
    const editProgressModal = document.getElementById('edit-progress-modal');
    editProgressModal.addEventListener('show.bs.modal', function(event) {
        const button = event.relatedTarget; // Tombol yang mengaktifkan modal

        // Ambil data dari tombol
        const id = button.getAttribute('data-id');
        const capexId = button.getAttribute('data-capex-id');
        const tanggal = button.getAttribute('data-tanggal');
        const description = button.getAttribute('data-description');

        // Isi data ke dalam modal
        const editProgressIdInput = document.getElementById('edit_progress_id');
        const editProgressCapexIdInput = document.getElementById('edit_progress_capex_id');
        const editTanggalInput = document.getElementById('edit_tanggal');
        const editDescriptionInput = document.getElementById('edit_description');

        editProgressIdInput.value = id;
        editProgressCapexIdInput.value = capexId;
        editTanggalInput.value = tanggal;
        editDescriptionInput.value = description;
    });
</script>
