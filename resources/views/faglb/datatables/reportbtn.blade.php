@if ($row->report_status == 0)
    <button class="btn bg-gradient-warning release-btn" data-id="{{ $row->id_head }}" data-status="0">Unreleased</button>
@else
    <button class="btn bg-gradient-success release-btn" data-id="{{ $row->id_head }}" data-status="1">Released</button>
@endif


<script>
    $(document).on('click', '.release-btn', function() {
        var button = $(this);
        var id = button.data('id'); // ID dari report
        var currentStatus = button.data('status'); // Status saat ini (0 = Unreleased, 1 = Released)

        // Pastikan tombol hanya bisa ditekan sekali (hanya untuk status Unreleased)
        if (currentStatus == 1) return; // Jika sudah Released, jangan lakukan apa-apa

        // SweetAlert Confirmation before updating
        Swal.fire({
            title: 'Apa Anda Yakin?',
            text: "Apakah Anda ingin merilis report ini?",
            icon: 'warning',
            showCancelButton: true, // Show cancel button
            confirmButtonText: 'Ya, Rilis!',
            cancelButtonText: 'Tidak, Batalkan',
            reverseButtons: true // Reverse button positions
        }).then((result) => {
            if (result.isConfirmed) {
                // Jika user memilih 'Ya, Rilis!', kirim permintaan AJAX untuk memperbarui status
                $.ajax({
                    url: '/faglb', // Mengarah ke route store, karena kita pakai store untuk update
                    method: 'POST',
                    data: {
                        _token: '{{ csrf_token() }}', // CSRF token
                        id_head: id, // ID report yang akan diupdate
                        status: 1, // Status baru (Released)
                        flag: 'update-btn', // Menandakan ini update status
                    },
                    success: function(response) {
                        if (response.success) {
                            // Ubah status tombol dan tampilkan sebagai Released
                            button.removeClass('bg-gradient-warning').addClass(
                                'bg-gradient-success');
                            button.text('Released');
                            button.data('status', 1); // Update status menjadi Released

                            // SweetAlert for success
                            Swal.fire({
                                icon: 'success',
                                title: 'Status Berhasil Diperbarui',
                                text: 'Report berhasil dirilis sebagai Released.',
                                showConfirmButton: false,
                                timer: 900
                            });

                            $('#faglb-table').DataTable().ajax.reload();

                        } else {
                            // SweetAlert for error
                            Swal.fire({
                                icon: 'error',
                                title: 'Gagal Mengubah Status',
                                text: response.message,
                                confirmButtonText: 'Tutup'
                            });
                        }
                    },
                    error: function() {
                        // SweetAlert for error
                        Swal.fire({
                            icon: 'error',
                            title: 'Terjadi Kesalahan',
                            text: 'Coba lagi nanti.',
                            confirmButtonText: 'Tutup'
                        });
                    }
                });
            } else {
                // Jika user memilih 'Tidak, Batalkan', do nothing
                Swal.fire({
                    icon: 'info',
                    title: 'Pembatalan',
                    text: 'Proses rilis dibatalkan.',
                    showConfirmButton: false,
                    timer: 1000
                });
            }
        });
    });
</script>

<style>
    /* From Uiverse.io by suda-code */
    .release-btn {
        padding: 12.5px 30px;
        border: 0;
        border-radius: 100px;
        background-color: #2ba8fb;
        color: #ffffff;
        font-weight: Bold;
        transition: all 0.5s;
        -webkit-transition: all 0.5s;
    }

    .release-btn:hover {
        background-color: #6fc5ff;
        box-shadow: 0 0 20px #6fc5ff50;
        transform: scale(1.1);
    }

    .release-btn:active {
        background-color: #3d94cf;
        transition: all 0.25s;
        -webkit-transition: all 0.25s;
        box-shadow: none;
        transform: scale(0.98);
    }
</style>
