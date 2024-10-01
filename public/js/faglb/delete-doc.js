$("#faglb-table").on("click", ".delete-btn", function () {
    var id = $(this).data("id"); // Ambil ID dari data-id

    // Tampilkan SweetAlert untuk konfirmasi penghapusan
    Swal.fire({
        title: "Konfirmasi Hapus",
        text: "Apakah Anda yakin ingin menghapus item ini?",
        icon: "warning", // Tampilkan ikon peringatan
        showCancelButton: true, // Tampilkan tombol batal
        confirmButtonColor: "#3085d6", // Warna tombol konfirmasi
        cancelButtonColor: "#d33", // Warna tombol batal
        confirmButtonText: "Ya, hapus!",
        cancelButtonText: "Batal",
    }).then((result) => {
        if (result.isConfirmed) {
            // Jika pengguna mengkonfirmasi penghapusan
            $.ajax({
                url: "/faglb/" + id,
                type: "POST",
                data: {
                    _method: "DELETE", // Laravel membutuhkan ini untuk metode DELETE
                    _token: $('meta[name="csrf-token"]').attr("content"), // Sertakan CSRF token
                },
                success: function (result) {
                    // Menampilkan notifikasi sukses
                    Swal.fire(
                        "Terhapus!",
                        "Item telah berhasil dihapus.",
                        "success"
                    );
                    // Reload DataTable
                    $("#faglb-table").DataTable().ajax.reload();
                },
                error: function (xhr) {
                    // Menampilkan notifikasi jika terjadi error
                    Swal.fire(
                        "Gagal!",
                        "Terjadi kesalahan saat menghapus item.",
                        "error"
                    );
                },
            });
        }
    });
});
