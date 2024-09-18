<!-- Modal Update -->
<div class="modal fade" id="update-form" tabindex="-1" aria-labelledby="updateFormLabel" aria-hidden="true">
  <div class="modal-dialog">
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

          <!-- Form fields as before -->
          <div class="mb-3">
            <label for="yearMonth" class="form-label">Year / Month</label>
            <input type="month" class="form-control" id="yearMonthUpdate" name="period_cip" required>
          </div>
          <div class="mb-3">
            <label for="balanceUSD" class="form-label">Balance (USD / RP)</label>
            <div class="input-group">
              <input type="number" step="any" class="form-control" id="balanceUSDUpdate" name="bal_usd" placeholder="USD" style="text-align: center;" required>
              <input type="number" step="any" class="form-control" id="balanceRPUpdate" name="bal_rp" placeholder="RP" style="text-align: center;" required>
            </div>
          </div>
          <div class="mb-3">
            <label for="cumulativeBalanceUSD" class="form-label">Cumulative Balance (USD / RP)</label>
            <div class="input-group">
              <input type="number" step="any" class="form-control" id="cumulativeBalanceUSDUpdate" name="cumbal_usd" placeholder="USD" style="text-align: center;" required>
              <input type="number" step="any" class="form-control" id="cumulativeBalanceRPUpdate" name="cumbal_rp" placeholder="RP" style="text-align: center;" required>
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
