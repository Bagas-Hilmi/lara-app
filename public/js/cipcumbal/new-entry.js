document.getElementById('saveEntry').addEventListener('click', function() {
    const form = document.getElementById('entryForm');
    const formData = new FormData(form);

    // Pastikan `mode` sesuai
    formData.append('mode', 'ADD'); 

    // Tampilkan SweetAlert untuk konfirmasi sebelum menyimpan
    Swal.fire({
        title: 'Apakah Anda yakin?',
        text: "Data ini akan disimpan!",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Ya, simpan!',
        cancelButtonText: 'Batal'
    }).then((result) => {
        if (result.isConfirmed) {
            // Jika pengguna mengonfirmasi, lakukan pengiriman form
            fetch(form.action, {
                method: 'POST',
                body: formData,
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    Swal.fire(
                        'Sukses!',
                        'Entry saved successfully!',
                        'success'
                    );

                    // Reload DataTable tanpa refresh halaman
                    $('#cipCumBalTable').DataTable().ajax.reload(null, false);

                    // Tutup modal setelah sukses
                    $('#new-form').modal('hide');
                } else {
                    Swal.fire(
                        'Gagal!',
                        data.message || 'An error occurred while saving.',
                        'error'
                    );
                }
            })
            .catch(error => console.error('Error:', error));
        }
    });
});
