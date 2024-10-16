<div class="modal fade" id="new-completion-modal" tabindex="-1" aria-labelledby="newCompletionModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header" style="background-color: #42bd37;">
                <h4 class="modal-title" id="newCompletionModalLabel" style="color: white;">Add New Completion</h4>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="new-completion-form" action="{{ route('capex.store') }}" method="POST">
                    <input type="hidden" name="flag" value="add-completion">
                    <input type="hidden" id="new_completion_capex_id" name="id_capex" value="">
                    @csrf
                    <div class="mb-3 row">
                        <label for="date" class="form-label font-weight-bold">Date</label>
                        <div class="col">
                            <input type="month" class="form-control" id="date" name="date" style="text-align: center;" required>
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
    $('#new-completion-form').on('submit', function (e) {
            e.preventDefault();
            var formData = $(this).serialize();
            console.log("Form Data: ", formData); // Log form data sebelum dikirim
            
            $.ajax({
                url: $(this).attr('action'), // Sesuaikan dengan route Anda
                method: 'POST',
                data: formData,
                success: function (response) {
                    $('#new-completion-modal').modal('hide');
                    $('#completion-table').DataTable().ajax.reload();
                    alert('Completion Date berhasil ditambahkan!');
                     // Refresh halaman
                    location.reload(); // Melakukan refresh halaman
                },
                error: function (xhr) {
                    console.log("Error: ", xhr.responseText); // Log kesalahan
                    alert('Terjadi kesalahan: ' + xhr.responseText);
                }
            });
        });
</script>
