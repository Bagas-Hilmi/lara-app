<div class="modal fade" id="new-porelease-modal" tabindex="-1" aria-labelledby="newPoreleaseModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header" style="background-color: #42bd37;">
                <h4 class="modal-title" id="newPoreleaseModalLabel" style="color: white;">Add New PO Release</h4>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="new-porelease-form" action="{{ route('capex.store') }}" method="POST">
                    <input type="hidden" name="flag" value="add-porelease">
                    <input type="hidden" id="new_porelease_capex_id" name="id_capex" value="">
                    @csrf
                    <div class="mb-3 row">
                        <label for="description" class="form-label font-weight-bold">Description</label>
                        <div class="col">
                            <input type="text" class="form-control" id="description" name="description"
                                style="text-align: center;" required>
                        </div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label for="getSAPData" class="form-label font-weight-bold">Get SAP Data</label>
                            <button type="button" class="btn btn-primary w-100" id="getSAPData" name="getSAPData" style="text-align: center;">
                                Get SAP Data
                            </button>
                        </div>
                        <div class="col-md-6">
                            <div class="alert alert-success d-none" role="alert" id="successAlert">
                                <strong>Success!</strong> Data sudah berhasil diambil.
                            </div>
                        </div>
                    </div>
                    
                    <div class="modal-footer">
                        <button type="button" class="btn bg-gradient-secondary" data-bs-dismiss="modal">Close</button>
                        <button type="submit" class="btn bg-gradient-success">Submit</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        const numberInputs = document.querySelectorAll('input.new-porelease'); // Menggunakan kelas khusus untuk input update

        numberInputs.forEach(input => {
            input.addEventListener('input', function() {
                // Menghapus semua karakter yang bukan angka, koma, dan titik
                let value = this.value.replace(/[^0-9.,]/g, '');

                // Memisahkan bagian integer dan desimal
                let parts = value.split(',');
                let integerPart = parts[0].replace(/\./g, ''); // Menghapus titik dari bagian integer
                let decimalPart = parts[1] ? ',' + parts[1].slice(0, 2) : ''; // Menyimpan bagian desimal maksimum 2 digit

                // Memformat bagian integer dengan pemisah ribuan
                let formattedInteger = parseInt(integerPart).toLocaleString('id-ID');

                // Menggabungkan bagian integer dan desimal
                this.value = formattedInteger + decimalPart;
            });

            input.addEventListener('blur', function() {
                // Format saat fokus hilang (blur)
                let value = this.value.replace(/\./g, '').replace(/,/g, '.'); // Menghapus titik dan mengubah koma menjadi titik
                if (value) {
                    this.value = parseFloat(value).toString(); 
                }
            });
        });
    });
</script>

<script>
    $('#new-porelease-form').on('submit', function(e) {
        e.preventDefault(); // Mencegah tindakan default dari formulir

        // Ambil data dari form
        var formData = $(this).serialize();

        // Cek apakah semua field yang required terisi
        var isValid = true;
        $(this).find('input[required], select[required]').each(function() {
            if ($(this).val() === '') {
                isValid = false;
                $(this).addClass(
                'is-invalid'); // Tambahkan kelas invalid untuk menandai field yang kosong
            } else {
                $(this).removeClass('is-invalid'); // Hapus kelas invalid jika terisi
            }
        });

        if (!isValid) {
            Swal.fire({
                title: 'Error!',
                text: 'Silakan lengkapi semua input yang diperlukan.',
                icon: 'error',
                confirmButtonText: 'OK'
            });
            return; // Hentikan eksekusi jika ada field yang kosong
        }

        // Tampilkan konfirmasi sebelum mengirim data
        Swal.fire({
            title: 'Konfirmasi',
            text: 'Apakah Anda yakin ingin menambahkan PO Release ini?',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Ya, tambahkan!',
            cancelButtonText: 'Tidak'
        }).then((result) => {
            if (result.isConfirmed) {
                // Kirim data melalui AJAX jika pengguna menekan "Ya"
                $.ajax({
                    url: $(this).attr('action'), // Sesuaikan dengan route Anda
                    method: 'POST',
                    data: formData,
                    success: function(response) {
                        $('#new-porelease-modal').modal('hide');
                        $('#porelease-table').DataTable().ajax.reload();

                        // Tampilkan pesan sukses dengan SweetAlert
                        Swal.fire({
                            title: 'Berhasil!',
                            text: 'PO Release berhasil ditambahkan!',
                            icon: 'success',
                            confirmButtonText: 'OK'
                        }).then(() => {
                            // Refresh halaman setelah menutup pesan sukses
                            $('#capex-table').DataTable().ajax
                        .reload(); // Reload DataTable
                        });
                    },
                    error: function(xhr) {
                        console.log("Error: ", xhr.responseText); // Log kesalahan
                        Swal.fire({
                            title: 'Terjadi kesalahan!',
                            text: 'Error: ' + xhr.responseText,
                            icon: 'error',
                            confirmButtonText: 'OK'
                        });
                    }
                });
            }
        });
    });
</script>

<script>
    $(document).ready(function() {
    $('#getSAPData').on('click', function() {
        // Logika untuk mengambil data SAP bisa ditambahkan di sini

        // Tampilkan alert
        $('#successAlert').removeClass('d-none'); // Menampilkan alert setelah data berhasil diambil
    });
});
</script>
