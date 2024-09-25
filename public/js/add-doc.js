$(document).on('click', '[data-bs-target="#addDocFormModal"]', function() {
    var url = $(this).data('url'); // Ambil URL untuk mendapatkan data periode
    $.get(url, function(data) {
        var periodList = $('#periodList');
        periodList.empty(); // Kosongkan dropdown sebelum menambah item baru
        periodList.append('<li><a class="dropdown-item" href="#" data-id="" data-period="">Pilih Period</a></li>'); // Tambahkan pilihan default

        $.each(data.periods, function(index, period) {
            periodList.append('<li><a class="dropdown-item" href="#" data-id="' + period.id_ccb + '" data-period="' + period.period_cip + '">' + period.period_cip + ' / ' + period.id_ccb + '</a></li>');
        });
    });
});

$(document).on('click', '.dropdown-item', function() {
    var selectedPeriod = $(this).data('period'); // Mengambil data 'period' dari dropdown item
    var selectedIdCcb = $(this).data('id'); // Mengambil data 'id' dari dropdown item

    // Set nilai period dan id_ccb ke input hidden
    $('#selectedPeriod').val(selectedPeriod);
    $('#selectedIdCcb').val(selectedIdCcb);

    // Mengubah teks pada tombol dropdown untuk menunjukkan pilihan saat ini
    $('#periodDropdown').text(selectedPeriod + ' / ' + selectedIdCcb);
});
