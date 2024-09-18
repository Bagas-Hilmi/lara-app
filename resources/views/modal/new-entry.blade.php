<!-- Modal -->
<div class="modal fade" id="new-form" tabindex="-1" aria-labelledby="newFormLabel" aria-hidden="true">
  <div class="modal-dialog modal- modal-dialog-centered modal-">
    <div class="modal-content">
      <div class="modal-header" style="background-color: #42bd37;">
        <h5 class="modal-title" id="newFormLabel" style="color: white;">New Entry Cip Cumulative Balance</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <form id="entryForm" action="{{ route('cipcumbal.store') }}" method="POST">
          <input type="hidden" name="mode" value="ADD">

          @csrf
          <!-- Year / Month Input -->
          <div class="mb-3">
            <label for="yearMonth" class="form-label">Year / Month</label>
            <div class="month-input-container">
                <input type="month" class="form-control" id="yearMonth" name="period_cip" required>
            </div>
          </div>

          <!-- Balance (USD / RP) -->
          <div class="mb-3 balance-container">
            <label for="balanceUSD" class="form-label">Balance (USD / RP)</label>
            <div class="input-group">
                <div class="input-box">
                    <input type="number" step="any" class="form-control column-input" id="balanceUSD" name="bal_usd" placeholder="USD" style="text-align: center;" required>
                </div>
                <div class="input-box">
                    <input type="number" step="any" class="form-control column-input" id="balanceRP" name="bal_rp" placeholder="RP" style="text-align: center;" required>
                </div>
            </div>
          </div>

          <!-- Cumulative Balance (USD / RP) -->
          <div class="mb-3 cumulative-container">
              <label for="cumulativeBalanceUSD" class="form-label">Cumulative Balance (USD / RP)</label>
              <div class="input-group">
                  <div class="input-box">
                      <input type="number" step="any" class="form-control column-input" id="cumulativeBalanceUSD" name="cumbal_usd" placeholder="USD" style="text-align: center;" required>
                  </div>
                  <div class="input-box">
                      <input type="number" step="any" class="form-control column-input" id="cumulativeBalanceRP" name="cumbal_rp" placeholder="RP" style="text-align: center;" required>
                  </div>
              </div>
          </div>
        </form>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
        <button type="submit" class="btn btn-success" id="saveEntry">Save</button>
      </div>
    </div>
  </div>
</div>

<style>
/* Container for month input */
.month-input-container {
    display: inline-block;
    border: 1px solid #ccc;
    border-radius: 4px;
    padding: 10px;
    background-color: #f9f9f9;
    box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
    width: fit-content;
}

/* Style for the input fields */
.form-control {
    border: none;
    box-shadow: none;
    background-color: transparent;
}

/* Flex container for balance and cumulative balance */
.balance-container,
.cumulative-container {
    display: flex;
    align-items: center;
    gap: 15px; /* Reduced gap for a smaller appearance */
    width: fit-content;
}

/* Style for the input box */
.input-box {
    border: 1px solid #ddd;
    border-radius: 4px;
    padding: 5px;
    background-color: #f7f7f7;
    width: 175px; /* Adjust width as needed */
}

/* Style for the input elements inside the box */
.column-input {
    width: 100%;
    border: none;
    box-shadow: none;
    background-color: transparent;
}

/* Flex container for input group */
.input-group {
    display: flex;
    gap: 5px;
}

/* Specific width adjustment for cumulative balance container */
.cumulative-container .input-box {
    width: 155px; /* Adjust width for cumulative balance */
}

</style>
