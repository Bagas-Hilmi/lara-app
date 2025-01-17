<div class="modal fade" id="new-budget-modal" tabindex="-1" aria-labelledby="newBudgetModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header" style="background-color: #42bd37;">
                <h4 class="modal-title" id="newBudgetModalLabel" style="color: white;">Add New Budget</h4>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="new-budget-form" action="{{ route('capex.store') }}" method="POST">
                    <input type="hidden" name="flag" value="add-budget">
                    <input type="hidden" id="new_budget_capex_id" name="id_capex" value="">
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
                            <label for="budget-cos" class="form-label font-weight-bold">Budget Cos (USD)</label>
                            <input type="text" class="form-control column-input new-budget" id="budget_cos"
                                name="budget_cos" style="text-align: center;" required>
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
    document.addEventListener('DOMContentLoaded', function() {
        const numberInputs = document.querySelectorAll('input.new-budget'); // Menggunakan kelas khusus untuk input update

        numberInputs.forEach(input => {
            input.addEventListener('input', function() {
                // Menghapus semua karakter yang bukan angka, koma, dan titik
                let value = this.value.replace(/[^0-9.,]/g, '');

                // Memisahkan bagian integer dan desimal
                let parts = value.split(',');
                let integerPart = parts[0].replace(/\./g,''); // Menghapus titik dari bagian integer
                let decimalPart = parts[1] ? ',' + parts[1].slice(0, 2) :''; // Menyimpan bagian desimal maksimum 2 digit

                // Memformat bagian integer dengan pemisah ribuan
                let formattedInteger = parseInt(integerPart).toLocaleString('id-ID');

                // Menggabungkan bagian integer dan desimal
                this.value = formattedInteger + decimalPart;
            });

            input.addEventListener('blur', function() {
                // Format saat fokus hilang (blur)
                let value = this.value.replace(/\./g, '').replace(/,/g,
                    '.'); // Menghapus titik dan mengubah koma menjadi titik
                if (value) {
                    this.value = parseFloat(value).toString();
                }
            });
        });
    });
</script>


<script>
    $('#new-budget-form').on('submit', function(e) {
        e.preventDefault(); // Mencegah tindakan default dari formulir

        // Ambil data dari form
        var formData = $(this).serialize();
        console.log("Form Data: ", formData); // Log form data sebelum dikirim

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
            text: 'Apakah Anda yakin ingin menambahkan budget ini?',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Ya, tambahkan!',
            cancelButtonText: 'Tidak',
            allowOutsideClick: false, // Mencegah klik di luar untuk menutup SweetAlert
            allowEscapeKey: false, // Mencegah tombol Escape untuk menutup SweetAlert
            didOpen: () => {
                // Fokuskan ke tombol "Ya, tambahkan!" saat SweetAlert terbuka
                $('.swal2-confirm').focus();
            }
        }).then((result) => {
            if (result.isConfirmed) {
                // Kirim data melalui AJAX jika pengguna menekan "Ya"
                $.ajax({
                    url: $(this).attr('action'), // Sesuaikan dengan route Anda
                    method: 'POST',
                    data: formData,
                    success: function(response) {
                        $('#new-budget-modal').modal('hide');
                        $('#budget-table').DataTable().ajax.reload();

                        // Tampilkan pesan sukses dengan SweetAlert
                        Swal.fire({
                            title: 'Berhasil!',
                            text: 'Budget berhasil ditambahkan!',
                            icon: 'success',
                            showConfirmButton: false,
                            timer: 1000
                        }).then(() => {
                            // Refresh halaman setelah menutup pesan sukses
                            $('#capex-table').DataTable().ajax.reload();
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

        $('#new-budget-modal').on('hidden.bs.modal', function () {
            // Kosongkan semua input field
            $('#new-budget-form')[0].reset();
            // Hapus class is-invalid jika ada
            $('#new-budget-form').find('input').removeClass('is-invalid');
        });

        // Tangani tombol Enter di SweetAlert
        $(document).on('keydown', function(e) {
            if ($('.swal2-container').length > 0 && e.key === 'Enter') {
                e.preventDefault();
                $('.swal2-confirm').click(); // Simulasikan klik tombol konfirmasi
            }
        });
    });
</script>
