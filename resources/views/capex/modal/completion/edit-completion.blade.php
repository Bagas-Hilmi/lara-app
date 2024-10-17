<div class="modal fade" id="edit-completion-modal" tabindex="-1" aria-labelledby="editCompletionModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-md modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header" style="background-color: #42bd37;">
                <h4 class="modal-title" id="editCompletionModalLabel" style="color: white;">Edit Completion</h4>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="edit-completion-form" action="{{ route('capex.store') }}" method="POST">
                    <input type="hidden" id="edit_completion_id" name="id" value="">
                    <input type="hidden" name="flag" value="edit-completion">
                    <input type="hidden" id="edit_completion_capex_id" name="id_capex" value="">
                    @csrf
                    <div class="mb-3 row">
                        <label for="edit_date" class="form-label font-weight-bold">Date</label>
                        <div class="col">
                            <input type="month" class="form-control" id="edit_date" name="date" style="text-align: center;" required>
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
    function editCompletion(id) {
        $.ajax({
            url: '/capex/' + id + '/edit', // Sesuaikan dengan route edit Anda
            method: 'GET',
            success: function(data) {
                // Set nilai ke input dalam modal
                $('#edit_completion_id').val(data.id_capex_completion); // ID completion
                $('#edit_completion_capex_id').val(data.id_capex); // ID capex
                $('#edit_date').val(data.date); // Ubah ke description_porelease
                // Tampilkan modal
                $('#edit-completion-modal').modal('show');
                location.reload(); // Melakukan refresh halaman
            },
            error: function(xhr) {
                console.log("Error: ", xhr.responseText); // Log kesalahan
                alert('Terjadi kesalahan saat mengambil data.');
            }
        });
    }

        $('#edit-completion-form').on('submit', function(e) {
        e.preventDefault(); // Mencegah reload halaman
        var formData = $(this).serialize(); // Mengambil data dari form

        $.ajax({
            url: $(this).attr('action'), // URL untuk mengirim request
            method: 'POST',
            data: formData,
            success: function(response) {
                $('#edit-completion-modal').modal('hide'); // Menyembunyikan modal
                $('#completion-table').DataTable().ajax.reload(); // Reload DataTable
                alert(response.message); // Menampilkan pesan sukses
            },
            error: function(xhr) {
                console.log("Error: ", xhr.responseText); // Log kesalahan
                alert('Terjadi kesalahan: ' + xhr.responseText); // Menampilkan pesan error
            }
        });
    });

</script>

<script>
   const editCompletionModal = document.getElementById('edit-completion-modal');
    editCompletionModal.addEventListener('show.bs.modal', function (event) {
        const button = event.relatedTarget; // Tombol yang memicu modal

        // Ambil data dari tombol
        const id = button.getAttribute('data-id');
        const capexId = button.getAttribute('data-capex-id');
        const date = button.getAttribute('data-date');

        // Isi data ke dalam modal
        const editCompletionIdInput = document.getElementById('edit_completion_id');
        const editCompletionCapexIdInput = document.getElementById('edit_completion_capex_id');
        const editDateInput = document.getElementById('edit_date');

        editCompletionIdInput.value = id;
        editCompletionCapexIdInput.value = capexId;
        editDateInput.value = date;
    });

</script>

