<!-- Modal -->
<div class="modal fade" id="new-form" tabindex="-1" aria-labelledby="newFormLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header" style="background-color: #42bd37;">
        <h5 class="modal-title" id="newFormLabel" style="color: white;">New Entry Cip Cumulative Balance</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <form id="entryForm">
          <!-- Year / Month Input -->
          <div class="mb-3">
            <label for="yearMonth" class="form-label">Year / Month</label>
            <input type="month" class="form-control" id="yearMonth" required>
          </div>

          <!-- Balance (USD / RP) -->
          <div class="mb-3">
            <label for="balanceUSD" class="form-label">Balance (USD / RP)</label>
            <div class="input-group">
              <input type="number" step="0.01" class="form-control" id="balanceUSD"placeholder="USD" style="text-align: center;" required>
              <span class="input-group-text"></span>
              <input type="number" step="0.01" class="form-control" id="balanceRP" placeholder="RP" style="text-align: center;" required>
            </div>
          </div>

          <!-- Cumulative Balance (USD / RP) -->
          <div class="mb-3">
            <label for="cumulativeBalanceUSD" class="form-label">Cumulative Balance (USD / RP)</label>
            <div class="input-group">
              <input type="number" step="0.01" class="form-control" id="cumulativeBalanceUSD" placeholder="USD" style="text-align: center;" required>
              <span class="input-group-text"></span>
              <input type="number" step="0.01" class="form-control" id="cumulativeBalanceRP" placeholder="RP" style="text-align: center;" required>
            </div>
          </div>

        </form>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
        <button type="button" class="btn btn-primary" id="saveEntry">Save</button>
      </div>
    </div>
  </div>
</div>

  
