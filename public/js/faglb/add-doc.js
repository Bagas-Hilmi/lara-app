$(document).on("click", '[data-bs-target="#addDocFormModal"]', function () {
    var url = $(this).data("url");
    $.get(url, function (data) {
        var periodList = $("#periodList");
        periodList.empty();
        periodList.append(
            '<li><a class="dropdown-item" href="#" data-id="" data-period="">Pilih Period</a></li>'
        );

        $.each(data.periods, function (index, period) {
            periodList.append(
                '<li><a class="dropdown-item" href="#" data-id="' +
                    period.id_ccb +
                    '" data-period="' +
                    period.period_cip +
                    '">' +
                    period.period_cip +
                    " / " +
                    period.id_ccb +
                    "</a></li>"
            );
        });
    });
});

$(document).on("click", ".dropdown-item", function () {
    var selectedPeriod = $(this).data("period");
    var selectedIdCcb = $(this).data("id");

    $("#selectedPeriod").val(selectedPeriod);
    $("#selectedIdCcb").val(selectedIdCcb);

    $("#periodDropdown").text(selectedPeriod + " / " + selectedIdCcb);
});

// Jika Anda ingin menampilkan modal untuk mengganti file
$(document).on("click", '[data-bs-target="#replaceDocFormModal"]', function () {
    var existingFile = $(this).data("existing-file");
    $("#existingFileName").text(existingFile);
});
