<div class="modal fade" id="update-form" tabindex="-1" aria-labelledby="updateFormLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header" style="background-color: #42bd37;">
        <h5 class="modal-title" id="updateFormLabel" style="color: white;">Update Entry Cip Cumulative Balance</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <form id="updateEntryForm" action="{{ route('cipcumbal.store') }}" method="POST">
          @csrf
          <input type="hidden" name="flag" value="update">
          <input type="hidden" id="updateId" name="id">
          <input type="hidden" name="updated_by" value="{{ auth()->user()->id }}">

          <div class="container-fluid">
            <div class="row mb-3">
              <div class="col-md-12">
                <label for="yearMonthUpdate" class="form-label">Year / Month</label>
                <div class="month-input-container">
                    <input type="month" class="form-control" id="yearMonthUpdate" name="period_cip" required>
                </div>
              </div>
            </div>

            <div class="row mb-3 balance-container">
              <div class="col-md-6">
                <label for="balanceUSDUpdate" class="form-label">Balance (USD)</label>
                <div class="input-box">
                    <input type="text" class="form-control column-input update-input" id="balanceUSDUpdate" name="bal_usd" placeholder="USD" style="text-align: center;" required>
                </div>
              </div>
              <div class="col-md-6">
                <label for="balanceRPUpdate" class="form-label">Balance (RP)</label>
                <div class="input-box">
                    <input type="text" class="form-control column-input update-input" id="balanceRPUpdate" name="bal_rp" placeholder="RP" style="text-align: center;" required>
                </div>
              </div>
            </div>

            <div class="row mb-3 cumulative-container">
              <div class="col-md-6">
                <label for="cumulativeBalanceUSDUpdate" class="form-label">Cumulative Balance (USD)</label>
                <div class="input-box">
                    <input type="text" class="form-control column-input update-input" id="cumulativeBalanceUSDUpdate" name="cumbal_usd" placeholder="USD" style="text-align: center;" required>
                </div>
              </div>
              <div class="col-md-6">
                <label for="cumulativeBalanceRPUpdate" class="form-label">Cumulative Balance (RP)</label>
                <div class="input-box">
                    <input type="text" class="form-control column-input update-input" id="cumulativeBalanceRPUpdate" name="cumbal_rp" placeholder="RP" style="text-align: center;" required>
                </div>
              </div>
            </div>
          </div>
        </form>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn bg-gradient-secondary" data-bs-dismiss="modal">Cancel</button>
        <button type="button" class="btn bg-gradient-success" id="updateEntry">Update</button>
      </div>
    </div>
  </div>
</div>

<script>
  document.addEventListener('DOMContentLoaded', function () {
      const numberInputs = document.querySelectorAll('input.update-input'); // Menggunakan kelas khusus untuk input update

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
