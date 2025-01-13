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
                            <button type="button" class="btn btn-primary w-100" id="getSAPData" name="getSAPData"
                                style="text-align: center;">
                                Get SAP Data
                            </button>
                        </div>
                        <div class="col-md-6">
                            <label for="porelease" class="form-label font-weight-bold">PO Release (USD)</label>
                            <input type="text" class="form-control column-input new-porelease" id="po_release"
                                name="po_release" style="text-align: center;" required>
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
    $(document).ready(function() {
        // Fungsi untuk mendapatkan data SAP
        $(document).on('click', '#getSAPData', function() {
            let capexId = $('#new_porelease_capex_id').val();

            if (capexId) {
                $.ajax({
                    url: '/capex',
                    type: 'POST',
                    data: {
                        _token: $('meta[name="csrf-token"]').attr('content'),
                        id_capex: capexId,
                        flag: 'get-sap-data',
                    },
                    beforeSend: function() {
                        $('#getSAPData').text('Processing...').attr('disabled', true);
                    },
                    success: function(response) {
                        Swal.fire('Berhasil!', response.message, 'success');
                        $('#getSAPData').text('Get SAP Data').attr('disabled', true);
                        // Reload tabel t_capex_pocommitment_tail
                        $('#pocommitment-tail-table').DataTable().ajax.reload();
                    },
                    error: function(xhr) {
                        Swal.fire('Error!', xhr.responseJSON.error || 'Terjadi kesalahan',
                            'error');
                        $('#getSAPData').text('Get SAP Data').attr('disabled', false);
                    },
                });
            } else {
                Swal.fire('Error!', 'ID Capex tidak ditemukan!', 'error');
            }
        });

        // Fungsi untuk submit new-porelease form
        $('#new-porelease-form').on('submit', function(e) {
            e.preventDefault(); // Mencegah tindakan default dari form submit

            // Ambil data dari form
            var formData = $(this).serialize();

            // Validasi input yang diperlukan
            var isValid = true;
            $(this).find('input[required], select[required]').each(function() {
                if ($(this).val() === '') {
                    isValid = false;
                    $(this).addClass('is-invalid'); // Tambahkan kelas invalid jika kosong
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
                return;
            }

            // Konfirmasi sebelum submit
            Swal.fire({
                title: 'Konfirmasi',
                text: 'Apakah Anda yakin ingin menambahkan PO Release ini?',
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
                    $.ajax({
                        url: $(this).attr('action'),
                        method: 'POST',
                        data: formData,
                        success: function(response) {
                            $('#new-porelease-modal').modal('hide');
                            $('#porelease-table').DataTable().ajax.reload();

                            Swal.fire({
                                title: 'Berhasil!',
                                text: 'PO Release berhasil ditambahkan!',
                                icon: 'success',
                                showConfirmButton: false,
                                timer: 1000
                            }).then(() => {
                                $('#capex-table').DataTable().ajax.reload();
                            });
                        },
                        error: function(xhr) {
                            console.error("Error: ", xhr
                            .responseText); // Log kesalahan
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
            $('#new-porelease-modal').on('hidden.bs.modal', function () {
            // Kosongkan semua input field
            $('#new-porelease-form')[0].reset();
            $('#getSAPData').text('Get SAP Data').attr('disabled', false); // Reset tombol
            // Hapus class is-invalid jika ada
            $('#new-porelease-form').find('input').removeClass('is-invalid');
        });

        $(document).on('keydown', function(e) {
            if ($('.swal2-container').length > 0 && e.key === 'Enter') {
                e.preventDefault();
                $('.swal2-confirm').click(); // Simulasikan klik tombol konfirmasi
            }
        });
    });
</script>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        const numberInputs = document.querySelectorAll('input.new-porelease'); // Menggunakan kelas khusus untuk input update

        numberInputs.forEach(input => {
            input.addEventListener('input', function() {
                // Menghapus semua karakter yang bukan angka, koma, dan titik
                let value = this.value.replace(/[^0-9.,]/g, '');

                // Memisahkan bagian integer dan desimal
                let parts = value.split(',');
                let integerPart = parts[0].replace(/\./g, ''); 
                let decimalPart = parts[1] ? ',' + parts[1].slice(0, 2) : ''; 

                // Memformat bagian integer dengan pemisah ribuan
                let formattedInteger = parseInt(integerPart).toLocaleString('id-ID');

                // Menggabungkan bagian integer dan desimal
                this.value = formattedInteger + decimalPart;
            });

            input.addEventListener('blur', function() {
                // Format saat fokus hilang (blur)
                let value = this.value.replace(/\./g, '').replace(/,/g, '.'); 
                if (value) {
                    this.value = parseFloat(value).toString(); 
                }
            });
        });
    });
</script>