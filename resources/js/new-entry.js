// Menambahkan event listener untuk tombol Save
document.getElementById('saveEntry').addEventListener('click', function() {
    var yearMonth = document.getElementById('yearMonth').value;
    var balanceUSD = document.getElementById('balanceUSD').value;
    var balanceRP = document.getElementById('balanceRP').value;
    var cumulativeBalanceUSD = document.getElementById('cumulativeBalanceUSD').value;
    var cumulativeBalanceRP = document.getElementById('cumulativeBalanceRP').value;

    // Logika penyimpanan data
    console.log({
        yearMonth: yearMonth,
        balanceUSD: balanceUSD,
        balanceRP: balanceRP,
        cumulativeBalanceUSD: cumulativeBalanceUSD,
        cumulativeBalanceRP: cumulativeBalanceRP
    });

    // Setelah berhasil simpan data, tutup modal
    $('#new-form').modal('hide');
});



