<!-- Modal -->
<div class="modal fade" id="new-form" tabindex="-1" aria-labelledby="newFormLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header" style="background-color: #42bd37;">
                <h5 class="modal-title" id="newFormLabel" style="color: white;">New Entry Cip Cumulative Balance</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="entryForm" action="{{ route('cipcumbal.store') }}" method="POST">
                    <input type="hidden" name="flag" value="add">
                    @csrf

                    <div class="container-fluid">
                        <!-- Year / Month Input -->
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label for="yearMonth" class="form-label">Year / Month</label>
                                <input type="month" class="form-control" id="yearMonth" name="period_cip" required>
                            </div>
                        </div>

                        <!-- Balance (USD / RP) -->
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label class="form-label">Balance (USD)</label>
                                <input type="text" class="form-control column-input new-input" id="balanceUSD"
                                    name="bal_usd" placeholder="USD" style="text-align: center;" required>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Balance (RP)</label>
                                <input type="text" class="form-control column-input new-input" id="balanceRP"
                                    name="bal_rp" placeholder="RP" style="text-align: center;" required>
                            </div>
                        </div>

                        <!-- Cumulative Balance (USD / RP) -->
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label class="form-label">Cumulative Balance (USD)</label>
                                <input type="text" class="form-control column-input new-input"
                                    id="cumulativeBalanceUSD" name="cumbal_usd" placeholder="USD"
                                    style="text-align: center;" required>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Cumulative Balance (RP)</label>
                                <input type="text" class="form-control column-input new-input"
                                    id="cumulativeBalanceRP" name="cumbal_rp" placeholder="RP"
                                    style="text-align: center;" required>
                            </div>
                        </div>

                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn bg-gradient-secondary" data-bs-dismiss="modal">Cancel</button>
                <button type="submit" class="btn bg-gradient-success" id="saveEntry">Save</button>
            </div>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const numberInputs = document.querySelectorAll(
        'input.new-input'); // Menggunakan kelas khusus untuk input update

        numberInputs.forEach(input => {
            input.addEventListener('input', function() {
                // Menghapus semua karakter yang bukan angka dan koma
                let value = this.value.replace(/[^0-9,]/g, '');

                // Memformat value agar tetap terlihat baik
                this.value = value;
            });

            input.addEventListener('blur', function() {
                // Format saat fokus hilang (blur)
                let value = this.value.replace(/,/g, ''); // Menghapus koma
                if (value) {
                    this.value = parseFloat(value).toString(); // Format menjadi 2 desimal
                }
            });
        });
    });
</script>

<script>
    document.getElementById('saveEntry').addEventListener('click', function() {
        const form = document.getElementById('entryForm');

        // Cek apakah form valid sebelum lanjut
        if (form.checkValidity()) {
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
                                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')
                                    .getAttribute('content')
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
                                $('#cipCumBalTable').DataTable().ajax.reload();

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
        } else {
            // Jika form tidak valid, tampilkan pesan kesalahan
            Swal.fire({
                title: 'Error!',
                text: 'Silakan lengkapi semua input yang diperlukan.',
                icon: 'error',
                confirmButtonText: 'OK'
            });
        }
    });
</script>


<style>
    /* Container for month input */
    .month-input-container {
        display: inline-block;
        border-radius: 4px;
        padding: 5px;
        /* Kurangi padding */
        background-color: #f9f9f9;
        box-shadow: 0 2px 4px rgba(255, 255, 255, 0.1);
    }

    .form-control {
        border: 1px solid #ccc;
        /* Customize the border */
        box-shadow: none;
        /* Remove shadow */
    }

    .form-control:focus {
        border-color: #42bd37;
        /* Warna border saat fokus */
        box-shadow: 0 0 5px rgba(66, 189, 55, 0.5);
        /* Menambah efek shadow saat fokus */
        border-radius: 4px;
        /* Tambahkan sudut melengkung */

    }

    .modal-body .form-label {
        font-weight: bold;
        /* Make labels bold */
    }

    .modal-body .input-group {
        margin-bottom: 1rem;
        /* Space between input groups */
    }
</style>
