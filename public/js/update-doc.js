$(document).on('click', '.edit-btn', function() {
    const id = $(this).data('id'); // Ambil ID dari tombol
    $('#edit-id').val(id); // Set ID di input hidden

    // Kosongkan input file jika sebelumnya ada file
    $('#faglb').val('');
    $('#zlis1').val('');

    $('#editModal').modal('show'); // Tampilkan modal
});

// Menangani submit form
$('#editForm').on('submit', function(e) {
    e.preventDefault(); // Mencegah submit default

    const id = $('#edit-id').val(); // Ambil ID dari input hidden
    const formData = new FormData(this); // Ambil data dari form

    $.ajax({
        url: `/faglb/${id}`, // URL untuk update
        type: 'PUT', // Method PUT untuk update
        data: formData,
        contentType: false,
        processData: false,
        success: function(response) {
            alert(response.success); // Tampilkan pesan sukses
            $('#editModal').modal('hide'); // Sembunyikan modal
            // Refresh DataTable jika perlu
            $('#yourDataTableId').DataTable().ajax.reload();
        },
        error: function(xhr) {
            console.error(xhr.responseText); // Untuk debugging
        }
    });
});
