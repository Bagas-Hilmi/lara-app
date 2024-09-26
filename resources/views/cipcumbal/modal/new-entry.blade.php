<!-- Modal -->
<div class="modal fade" id="new-form" tabindex="-1" aria-labelledby="newFormLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
      <div class="modal-content">
          <div class="modal-header" style="background-color: #42bd37;">
              <h5 class="modal-title" id="newFormLabel" style="color: white;">New Entry Cip Cumulative Balance</h5>
              <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
          </div>
          <div class="modal-body">
              <form id="entryForm" action="{{ route('cipcumbal.store') }}" method="POST">
                  <input type="hidden" name="mode" value="ADD">
                  @csrf

                  <div class="container-fluid">
                      <!-- Year / Month Input -->
                      <div class="row mb-3">
                          <div class="col-md-6">
                              <label for="yearMonth" class="form-label">Year / Month</label>
                              <input type="month" class="form-control" id="yearMonth" name="period_cip" required>
                          </div>
                      </div>
   
                      <!-- Balance (USD / RP) -->
                      <div class="row mb-3">
                        <div class="col-md-6">
                            <label class="form-label">Balance (USD)</label>
                            <input type="text" class="form-control" id="balanceUSD" name="bal_usd" placeholder="USD" style="text-align: center;" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Balance (RP)</label>
                            <input type="text" class="form-control" id="balanceRP" name="bal_rp" placeholder="RP" style="text-align: center;" required>
                        </div>
                    </div>
                
                    <!-- Cumulative Balance (USD / RP) -->
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label class="form-label">Cumulative Balance (USD)</label>
                            <input type="text" class="form-control" id="cumulativeBalanceUSD" name="cumbal_usd" placeholder="USD" style="text-align: center;" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Cumulative Balance (RP)</label>
                            <input type="text" class="form-control" id="cumulativeBalanceRP" name="cumbal_rp" placeholder="RP" style="text-align: center;" required>
                        </div>
                    </div>

                  </div>
              </form>
          </div>
          <div class="modal-footer">
              <button type="button" class="btn bg-gradient-secondary" data-bs-dismiss="modal">Cancel</button>
              <button type="submit" class="btn bg-gradient-success" id="saveEntry">Save</button>
          </div>
      </div>
  </div>
</div>


<style>
  
/* Container for month input */
.month-input-container {
    display: inline-block;
    border-radius: 4px;
    padding: 5px; /* Kurangi padding */
    background-color: #f9f9f9;
    box-shadow: 0 2px 4px rgba(255, 255, 255, 0.1);
}

.form-control {
    border: 1px solid #ccc; /* Customize the border */
    box-shadow: none; /* Remove shadow */
}

.modal-body .form-label {
    font-weight: bold; /* Make labels bold */
}

.modal-body .input-group {
    margin-bottom: 1rem; /* Space between input groups */
}
</style>
