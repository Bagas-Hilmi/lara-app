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
                    <input type="hidden" id="id_capex" name="id_capex">
                    @csrf 
                    <div class="mb-3 row">
                        <label for="description" class="form-label">Description</label>
                        <div class="col">
                            <input type="text" class="form-control" id="description" name="description" style="text-align: center;" required>
                        </div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label for="amount-budget" class="form-label">Amount Budget</label>
                            <input type="number" class="form-control" id="amount-budget" name="amount-budget" style="text-align: center;" required>
                        </div>
                        <div class="col-md-6">
                            <label for="budget-cos" class="form-label">Budget Cos</label>
                            <input type="number" class="form-control" id="budget-cos" name="budget-cos" style="text-align: center;" required>
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
         
    $('#new-budget-btn').on('click', function() {
    var idCapex = $(this).data('id'); // Ambil ID Capex dari tombol
    $('#id_capex').val(idCapex); // Isi input tersembunyi dengan ID Capex
    console.log('ID Capex:', idCapex); // Tambahkan ini untuk melihat ID Capex
    });

        // Event saat form baru untuk menambahkan anggaran disubmit
        $('#new-budget-form').on('submit', function(event) {
            event.preventDefault(); // Mencegah reload halaman

            let formData = $(this).serialize(); // Mengambil data dari form

            // Kirim data melalui AJAX
            $.ajax({
                url: $(this).attr('action'), // Mengambil URL dari atribut action form
                method: 'POST',
                data: formData,
                success: function(response) {
                    // Tutup modal setelah berhasil menyimpan
                    $('#new-budget-modal').modal('hide');

                    // Tampilkan notifikasi sukses
                    alert(response.message); // Anda bisa mengganti ini dengan notifikasi yang lebih baik

                    // Lakukan refresh DataTable untuk memperbarui tampilan
                    $('#budget-table').DataTable().ajax.reload(); // Asumsi Anda memiliki DataTable di halaman ini
                },
                error: function(xhr, status, error) {
                    // Tangani error jika gagal
                    alert('Failed to save budget. Please try again.'); // Tampilkan pesan kesalahan
                }
            });
        });
    });

</script>