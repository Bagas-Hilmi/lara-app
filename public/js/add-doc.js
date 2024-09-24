$(document).on('click', '[data-bs-target="#addDocFormModal"]', function() {
    var url = $(this).data('url');
    $.get(url, function(data) {
        var periodList = $('#periodList');
        periodList.empty(); // Kosongkan dropdown sebelum menambah item baru
        periodList.append('<li><a class="dropdown-item" href="#" data-id="">Pilih Period</a></li>'); // Tambahkan pilihan default

        $.each(data.periods, function(index, period) {
            periodList.append('<li><a class="dropdown-item" href="#" data-id="' + period.id_ccb + '">' + period.period_cip + ' / ' + period.id_ccb + '</a></li>');
        });
    });
});

$(document).on('click', '.dropdown-item', function() {
    var selectedPeriod = $(this).data('period');
    var selectedIdCcb = $(this).data('idccb');

    $('#selectedPeriod').val(selectedPeriod);
    $('#selectedIdCcb').val(selectedIdCcb);

    // Mengubah tampilan button dropdown
    $('#periodDropdown').text(selectedPeriod);
});

$(document).on('click', '#periodList .dropdown-item', function() {
    var selectedPeriod = $(this).text(); 
    $('#periodDropdown').text(selectedPeriod);
});