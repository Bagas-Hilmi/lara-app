document.addEventListener('DOMContentLoaded', function() {
    document.querySelectorAll('.update-btn').forEach(button => {
        button.addEventListener('click', function() {
            const id = this.getAttribute('data-id');
            const yearMonth = this.getAttribute('data-year-month');
            const balanceUSD = this.getAttribute('data-balance-usd');
            const balanceRP = this.getAttribute('data-balance-rp');
            const cumulativeBalanceUSD = this.getAttribute('data-cumulative-balance-usd');
            const cumulativeBalanceRP = this.getAttribute('data-cumulative-balance-rp');

            document.getElementById('yearMonth').value = yearMonth;
            document.getElementById('balanceUSD').value = balanceUSD;
            document.getElementById('balanceRP').value = balanceRP;
            document.getElementById('cumulativeBalanceUSD').value = cumulativeBalanceUSD;
            document.getElementById('cumulativeBalanceRP').value = cumulativeBalanceRP;

            document.getElementById('updateForm').setAttribute('data-id', id);

            const updateModal = new bootstrap.Modal(document.getElementById('update-form'));
            updateModal.show();
        });
    });
});
