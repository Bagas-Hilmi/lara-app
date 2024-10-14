<div class="modal fade" id="new-budget-modal" tabindex="-1" aria-labelledby="newBudgetModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header" style="background-color: #42bd37;">
                <h4 class="modal-title" id="newBudgetModalLabel" style="color: white;">Add New Budget</h4>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="new-budget-form" action="{{ route('capex.store') }}" method="POST"> <!-- Ganti dengan route yang sesuai -->
                    <input type="hidden" name="flag" value="add-budget"> 
                    <input type="hidden" id="id_capex" name="id_capex" value=""> <!-- Input tersembunyi untuk id_capex -->
                    @csrf 
                    <div class="mb-3 row">
                        <label for="description" class="form-label">Description</label>
                        <div class="col">
                            <input type="text" class="form-control" id="description" name="description" style="text-align: center;" required>
                        </div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label for="budget-cos" class="form-label">Budget Cos</label>
                            <input type="number" class="form-control" id="budget_cos" name="budget_cos" style="text-align: center;" required>
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
    $(document).ready(function() {
        // Event saat modal dibuka
        $('#new-budget-modal').on('shown.bs.modal', function(e) {
            var idCapex = $(e.relatedTarget).data('id'); // Ambil ID Capex dari data-id
            $('#id_capex').val(idCapex); // Isi input tersembunyi dengan ID Capex
        });
    
        $('#new-budget-form').on('submit', function(event) {
            event.preventDefault(); // Mencegah reload halaman
    
            let formData = $(this).serialize(); // Mengambil data dari form
            console.log(formData); // Debugging: lihat data yang dikirim
    
            // Kirim data melalui AJAX
            $.ajax({
                url: $(this).attr('action'), // Mengambil URL dari atribut action form
                method: 'POST',
                data: formData,
                success: function(response) {
                    // Tutup modal setelah berhasil menyimpan
                    $('#new-budget-modal').modal('hide');
    
                    // Tampilkan notifikasi sukses
                    alert(response.message);
    
                    // Refresh DataTable untuk memperbarui tampilan
                    $('#budget-table').DataTable().ajax.reload();
                },
                error: function(xhr, status, error) {
                    console.error(xhr.responseText); // Debugging: lihat detail error
                    if (xhr.responseJSON && xhr.responseJSON.errors) {
                        let errors = xhr.responseJSON.errors;
                        let errorMessage = '';
                        for (let key in errors) {
                            errorMessage += errors[key].join(', ') + '\n'; // Menggabungkan pesan kesalahan
                        }
                        alert('Validation failed:\n' + errorMessage); // Tampilkan kesalahan
                    } else {
                        alert('Failed to save budget. Please try again.'); // Tampilkan pesan kesalahan default
                    }
                }
            });
        });
    });
    </script>
    