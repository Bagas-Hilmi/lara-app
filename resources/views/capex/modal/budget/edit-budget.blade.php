<!-- Modal Edit Budget -->
<div class="modal fade" id="edit-budget-modal" tabindex="-1" aria-labelledby="editBudgetModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header" style="background-color: #42bd37;">
                <h4 class="modal-title" id="editBudgetModalLabel" style="color: white;">Edit Budget</h4>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="edit-budget-form" action="{{ route('capex.store') }}" method="POST">
                    @csrf
                    <input type="hidden" id="edit_budget_id" name="id" value="">
                    <input type="hidden" name="flag" value="edit-budget">
                    <input type="hidden" id="edit_budget_capex_id" name="capex_id" value="">
                    <div class="mb-3 row">
                        <label for="edit-description" class="form-label font-weight-bold">Description</label>
                        <div class="col">
                            <input type="text" class="form-control" id="edit-description" name="description" style="text-align: center;" required>
                        </div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label for="edit-budget-cos" class="form-label font-weight-bold">Budget Cos</label>
                            <input type="number" class="form-control column-input edit-budget" id="edit_budget_cos" name="budget_cos" style="text-align: center;" required>
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
    function editBudget(id) {
        // Ambil data budget berdasarkan id
        $.ajax({
            url: '/capex/' + id + '/edit', // Sesuaikan dengan route edit Anda
            method: 'GET',
            success: function(data) {
                // Set nilai ke input dalam modal
                $('#edit_budget_id').val(data.id_capex_budget); // ID budget
                $('#edit_budget_capex_id').val(data.id_capex); // ID capex
                $('#edit-description').val(data.description);
                $('#edit_budget_cos').val(data.budget_cos);
                
                // Tampilkan modal
                $('#edit-budget-modal').modal('show');
            },
            error: function(xhr) {
                console.log("Error: ", xhr.responseText); // Log kesalahan
                alert('Terjadi kesalahan saat mengambil data.');
            }
        });
    }

        $('#edit-budget-form').on('submit', function(e) {
        e.preventDefault(); // Mencegah reload halaman
        var formData = $(this).serialize(); // Mengambil data dari form

        $.ajax({
            url: $(this).attr('action'), // URL untuk mengirim request
            method: 'POST',
            data: formData,
            success: function(response) {
                $('#edit-budget-modal').modal('hide'); // Menyembunyikan modal
                $('#budget-table').DataTable().ajax.reload(); // Reload DataTable
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
    // Ketika modal ditampilkan, ambil data dari tombol
    const editBudgetModal = document.getElementById('edit-budget-modal');
    editBudgetModal.addEventListener('show.bs.modal', function (event) {
        const button = event.relatedTarget; // Tombol yang mengaktifkan modal

        // Ambil data dari tombol
        const id = button.getAttribute('data-id');
        const capexId = button.getAttribute('data-capex-id');
        const description = button.getAttribute('data-description');
        const budgetCos = button.getAttribute('data-budget-cos');

        // Isi data ke dalam modal
        const editBudgetIdInput = document.getElementById('edit_budget_id');
        const editBudgetCapexIdInput = document.getElementById('edit_budget_capex_id');
        const editDescriptionInput = document.getElementById('edit-description');
        const editBudgetCosInput = document.getElementById('edit_budget_cos');

        editBudgetIdInput.value = id; // ID budget
        editBudgetCapexIdInput.value = capexId; // ID capex
        editDescriptionInput.value = description; // Deskripsi
        editBudgetCosInput.value = budgetCos; // Budget Cos
    });
</script>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        const numberInputs = document.querySelectorAll('input.edit-budget'); // Menggunakan kelas khusus untuk input update
  
        numberInputs.forEach(input => {
            input.addEventListener('input', function() {
                // Menghapus semua karakter yang bukan angka dan koma
                let value = this.value.replace(/[^0-9,]/g, '');
  
                // Memformat value agar tetap terlihat baik
                this.value = value;
            });
  
            input.addEventListener('blur', function() {
                // Format saat fokus hilang (blur)
                let value = this.value.replace(/,/g, ''); // Menghapus koma
                if (value) {
                    this.value = parseFloat(value).toFixed(2); // Format menjadi 2 desimal
                }
            });
        });
    });
</script>

    