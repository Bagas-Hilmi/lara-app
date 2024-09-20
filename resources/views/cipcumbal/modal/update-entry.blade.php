<!-- Modal Update -->
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
          <input type="hidden" name="mode" value="UPDATE">
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
                    <input type="number" step="any" class="form-control column-input" id="balanceUSDUpdate" name="bal_usd" placeholder="USD" style="text-align: center;" required>
                </div>
              </div>
              <div class="col-md-6">
                <label for="balanceRPUpdate" class="form-label">Balance (RP)</label>
                <div class="input-box">
                    <input type="number" step="any" class="form-control column-input" id="balanceRPUpdate" name="bal_rp" placeholder="RP" style="text-align: center;" required>
                </div>
              </div>
            </div>

            <div class="row mb-3 cumulative-container">
              <div class="col-md-6">
                <label for="cumulativeBalanceUSDUpdate" class="form-label">Cumulative Balance (USD)</label>
                <div class="input-box">
                    <input type="number" step="any" class="form-control column-input" id="cumulativeBalanceUSDUpdate" name="cumbal_usd" placeholder="USD" style="text-align: center;" required>
                </div>
              </div>
              <div class="col-md-6">
                <label for="cumulativeBalanceRPUpdate" class="form-label">Cumulative Balance (RP)</label>
                <div class="input-box">
                    <input type="number" step="any" class="form-control column-input" id="cumulativeBalanceRPUpdate" name="cumbal_rp" placeholder="RP" style="text-align: center;" required>
                </div>
              </div>
            </div>
          </div>
        </form>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
        <button type="button" class="btn btn-success" id="updateEntry">Update</button>
      </div>
    </div>
  </div>
</div>
