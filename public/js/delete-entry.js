$('#cipCumBalTable').on('click', '.delete-btn', function() {
    var id = $(this).data('id');

    // Menampilkan SweetAlert untuk konfirmasi
    Swal.fire({
        title: 'Konfirmasi Hapus',
        text: 'Apakah Anda yakin ingin menghapus item ini?',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Ya, hapus!',
        // Menambahkan properti ini untuk memastikan posisi di tengah
        position: 'center'
    }).then((result) => {
        if (result.isConfirmed) {
            // Jika pengguna mengkonfirmasi penghapusan
            $.ajax({
                url: '/cipcumbal/' + id,
                type: 'DELETE',
                success: function(result) {
                    // Menampilkan notifikasi sukses
                    Swal.fire(
                        'Terhapus!',
                        'Item telah berhasil dihapus.',
                        'success'
                    );
                    setTimeout(function() {
                        location.reload(); // Reload halaman
                    }, 2000);
                },
                error: function(xhr, status, error) {
                    // Menampilkan notifikasi error jika penghapusan gagal
                    Swal.fire(
                        'Gagal!',
                        'Terjadi kesalahan saat menghapus item.',
                        'error'
                    );
                }
            });
        }
    });
});

