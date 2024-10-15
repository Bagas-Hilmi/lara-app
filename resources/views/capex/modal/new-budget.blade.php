<div class="modal fade" id="new-budget-modal" tabindex="-1" aria-labelledby="newBudgetModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header" style="background-color: #42bd37;">
                <h4 class="modal-title" id="newBudgetModalLabel" style="color: white;">Add New Budget</h4>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="new-budget-form" action="{{ route('capex.store') }}" method="POST">
                    <input type="hidden" name="flag" value="add-budget">
                    <input type="hidden" id="new_budget_capex_id" name="id_capex" value="">
                    @csrf
                    <div class="mb-3 row">
                        <label for="description" class="form-label">Description</label>
                        <div class="col">
                            <input type="text" class="form-control" id="description" name="description" style="text-align: center;" required>
                        </div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label for="budget-cos" class="form-label">Budget Cos (USD)</label>
                            <input type="number" class="form-control column-input new-budget" id="budget_cos" name="budget_cos" style="text-align: center;" required>
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
    document.addEventListener('DOMContentLoaded', function () {
        const numberInputs = document.querySelectorAll('input.new-budget'); // Menggunakan kelas khusus untuk input update
  
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
