$(document).ready(function() {
    // Mengatur nilai input hidden saat memilih
    $('#periodSelect').on('change', function() {
        $('#period').val($(this).val());
    });

    $('#idCcbSelect').on('change', function() {
        $('#id_ccb').val($(this).val());
    });

    // Event listener untuk tombol update
    $('#updateButton').on('click', function(e) {
        e.preventDefault(); // Mencegah pengiriman form

        // Ambil file jika ada
        var faglbFile = document.getElementById('faglb').files[0];
        var zlis1File = document.getElementById('zlis1').files[0];
        var idHead = $('#id_head').val(); // Ambil id_head dari input yang relevan
        var idCcb = $('#id_ccb').val();
        var period = $('#period').val();

        // Buat objek FormData untuk mengirimkan file dan data
        var formData = new FormData();
        formData.append('flag', 'update_file');
        formData.append('id_head', idHead);
        formData.append('id_ccb', idCcb);
        formData.append('period', period);
        formData.append('_token', $('meta[name="csrf-token"]').attr('content')); // Tambahkan CSRF token

        // Cek apakah file ada sebelum mengappend
        if (faglbFile) {
            formData.append('faglb', faglbFile);
        }
        if (zlis1File) {
            formData.append('zlis1', zlis1File);
        }

        // Menggunakan Fetch API untuk mengirim data
        fetch($('#updateForm').attr('action'), {
            method: 'POST',
            body: formData,
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        })
        .then(response => {
            if (!response.ok) {
                throw new Error('Network response was not ok: ' + response.statusText);
            }
            return response.json();
        })
        .then(data => {
            alert(data.message);
            $('#updateModal').modal('hide');
            // Refresh DataTables or do other UI updates if necessary
        })
        .catch(error => {
            alert('Error: ' + error.message);
        });
    });
});
