<div class="modal fade" id="update-form" tabindex="-1" aria-labelledby="updateFormLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header" style="background-color: #42bd37;">
                <h5 class="modal-title" id="updateFormLabel" style="color: white;">Update Entry Cip Cumulative Balance
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="updateEntryForm" action="{{ route('cipcumbal.store') }}" method="POST">
                    @csrf
                    <input type="hidden" name="flag" value="update">
                    <input type="hidden" id="updateId" name="id">
                    <input type="hidden" name="updated_by" value="{{ auth()->user()->id }}">

                    <div class="container-fluid">
                        <div class="row mb-3">
                            <div class="col-md-12">
                                <label for="yearMonthUpdate" class="form-label">Year / Month</label>
                                <div class="month-input-container">
                                    <input type="month" class="form-control" id="yearMonthUpdate" name="period_cip"
                                        required>
                                </div>
                            </div>
                        </div>

                        <div class="row mb-3 balance-container">
                            <div class="col-md-6">
                                <label for="balanceUSDUpdate" class="form-label">Balance (USD)</label>
                                <div class="input-box">
                                    <input type="text" class="form-control column-input update-input"
                                        id="balanceUSDUpdate" name="bal_usd" placeholder="USD"
                                        style="text-align: center;" required>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <label for="balanceRPUpdate" class="form-label">Balance (RP)</label>
                                <div class="input-box">
                                    <input type="text" class="form-control column-input update-input"
                                        id="balanceRPUpdate" name="bal_rp" placeholder="RP" style="text-align: center;"
                                        required>
                                </div>
                            </div>
                        </div>

                        <div class="row mb-3 cumulative-container">
                            <div class="col-md-6">
                                <label for="cumulativeBalanceUSDUpdate" class="form-label">Cumulative Balance
                                    (USD)</label>
                                <div class="input-box">
                                    <input type="text" class="form-control column-input update-input"
                                        id="cumulativeBalanceUSDUpdate" name="cumbal_usd" placeholder="USD"
                                        style="text-align: center;" required>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <label for="cumulativeBalanceRPUpdate" class="form-label">Cumulative Balance
                                    (RP)</label>
                                <div class="input-box">
                                    <input type="text" class="form-control column-input update-input"
                                        id="cumulativeBalanceRPUpdate" name="cumbal_rp" placeholder="RP"
                                        style="text-align: center;" required>
                                </div>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn bg-gradient-secondary" data-bs-dismiss="modal">Cancel</button>
                <button type="button" class="btn bg-gradient-success" id="updateEntry">Update</button>
            </div>
        </div>
    </div>
</div>


<script>
    document.addEventListener('DOMContentLoaded', function() {

        // Fungsi untuk menangani klik tombol update
        function handleUpdateClick(event) {

            // Ambil data dari tombol
            const button = event.currentTarget;
            const id = button.getAttribute('data-id');
            const period = button.getAttribute('data-period');
            const balUsd = button.getAttribute('data-bal-usd');
            const balRp = button.getAttribute('data-bal-rp');
            const cumbalUsd = button.getAttribute('data-cumbal-usd');
            const cumbalRp = button.getAttribute('data-cumbal-rp');

            // Isi data ke dalam form modal
            document.getElementById('updateId').value = id;
            document.getElementById('yearMonthUpdate').value = period;
            document.getElementById('balanceUSDUpdate').value = balUsd;
            document.getElementById('balanceRPUpdate').value = balRp;
            document.getElementById('cumulativeBalanceUSDUpdate').value = cumbalUsd;
            document.getElementById('cumulativeBalanceRPUpdate').value = cumbalRp;

            // Tampilkan modal
            const modal = document.getElementById('update-form');
            if (modal) {
                console.log('Modal found, attempting to show');
                const bsModal = new bootstrap.Modal(modal);
                bsModal.show();
            } else {
                console.error('Modal element not found');
            }
        }

        // Fungsi untuk menambahkan event listeners
        function addUpdateButtonListeners() {
            document.querySelectorAll('.update-btn').forEach(button => {
                button.removeEventListener('click', handleUpdateClick);
                button.addEventListener('click', handleUpdateClick);
            });
        }

        // Tambahkan listeners saat halaman dimuat
        addUpdateButtonListeners();

        // Jika menggunakan DataTables, tambahkan ini:
        if ($.fn.dataTable) {
            $('#cipCumBalTable').on('draw.dt', function() {
                addUpdateButtonListeners();
            });
        }
    });
</script>

<script>
    document.getElementById('updateEntry').addEventListener('click', function(e) {
        e.preventDefault();

        Swal.fire({
            title: 'Apakah Anda yakin ingin memperbarui entri ini?',
            text: "Data ini akan diperbarui!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Ya, perbarui!',
            cancelButtonText: 'Batal'
        }).then((result) => {
            if (result.isConfirmed) {
                const form = document.getElementById('updateEntryForm');
                const formData = new FormData(form);

                formData.append('mode', 'UPDATE');

                // Menambahkan informasi pengguna untuk `updated_by`
                const userIdMeta = document.querySelector('meta[name="user-id"]');
                const userId = userIdMeta ? userIdMeta.getAttribute('content') : null;
                formData.append('updated_by', userId);

                // Mengirim data menggunakan AJAX
                $.ajax({
                    url: form.action,
                    type: 'POST',
                    data: formData,
                    processData: false,
                    contentType: false,
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')
                            .getAttribute('content')
                    },
                    success: function(data) {
                        if (data.success) {
                            // Tampilkan pesan sukses menggunakan SweetAlert
                            Swal.fire({
                                title: "Sukses!",
                                text: "Entry berhasil di update!",
                                icon: "success",
                                showConfirmButton: false,
                                timer: 1000

                            });
                            // Tutup modal
                            bootstrap.Modal.getInstance(document.getElementById(
                                'update-form')).hide();

                            // Reload DataTable tanpa refresh halaman
                            if ($.fn.DataTable) {
                                $('#cipCumBalTable').DataTable().ajax.reload();
                            }
                        } else {
                            Swal.fire(
                                'Error!',
                                data.message || 'Error updating entry',
                                'error'
                            );
                        }
                    },
                    error: function(xhr, status, error) {
                        console.error('Error:', error);
                        Swal.fire(
                            'Error!',
                            'An error occurred while updating the entry',
                            'error'
                        );
                    }
                });
            }
        });
        $('#update-form').on('hidden.bs.modal', function () {
            // Kosongkan semua input field
            $('#updateEntryForm')[0].reset();
            // Hapus class is-invalid jika ada
            $('#updateEntryForm').find('input').removeClass('is-invalid');
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
    document.addEventListener('DOMContentLoaded', function() {
        // Fungsi untuk menambahkan pemisah ribuan (koma) dan mendukung desimal
        function formatNumber(value) {
            // Hapus semua karakter selain angka dan titik desimal
            value = value.replace(/[^0-9.]/g, '');

            // Pastikan hanya ada satu titik desimal
            let decimalParts = value.split('.');
            if (decimalParts.length > 2) {
                value = decimalParts[0] + '.' + decimalParts.slice(1).join('');
            }

            // Pisahkan angka utama dan bagian desimal
            let parts = value.split('.');
            let wholePart = parts[0]; // Angka sebelum titik
            let decimalPart = parts[1] || ''; // Angka setelah titik (jika ada)

            // Format angka utama dengan koma sebagai pemisah ribuan
            wholePart = wholePart.replace(/\B(?=(\d{3})+(?!\d))/g, ',');

            // Gabungkan angka utama dan desimal (maksimal 2 digit desimal)
            if (decimalPart) {
                decimalPart = decimalPart.slice(0, 2); // Batasi desimal menjadi 2 digit
                return `${wholePart}.${decimalPart}`;
            }

            return wholePart;
        }

        // Fungsi untuk menghapus pemisah ribuan
        function removeFormat(value) {
            return value.replace(/,/g, ''); // Hapus semua koma
        }

        // Daftar input yang akan diformat
        const numberInputs = [
            'balanceUSDUpdate',
            'balanceRPUpdate',
            'cumulativeBalanceUSDUpdate',
            'cumulativeBalanceRPUpdate',
        ];

        // Tambahkan event listener untuk memformat input saat pengguna mengetik
        numberInputs.forEach(inputId => {
            const input = document.getElementById(inputId);
            if (input) {
                input.addEventListener('input', function() {
                    // Simpan posisi kursor
                    let cursorPosition = input.selectionStart;
                    let oldValue = input.value;

                    // Format nilai input
                    input.value = formatNumber(input.value);

                    // Hitung perubahan posisi kursor akibat pemformatan
                    let newLength = input.value.length;
                    let oldLength = oldValue.length;
                    let lengthDifference = newLength - oldLength;
                    input.setSelectionRange(cursorPosition + lengthDifference, cursorPosition +
                        lengthDifference);
                });

                // Tangani input "paste" secara khusus
                input.addEventListener('paste', function(e) {
                    e.preventDefault();
                    let pastedValue = (e.clipboardData || window.clipboardData).getData('text');
                    input.value = formatNumber(pastedValue);
                });
            }
        });

        // Tambahkan event listener pada tombol "Update" untuk menghapus format sebelum pengiriman
        const updateButton = document.getElementById('updateEntry');
        if (updateButton) {
            updateButton.addEventListener('click', function() {
                numberInputs.forEach(inputId => {
                    const input = document.getElementById(inputId);
                    if (input) {
                        input.value = removeFormat(input.value); // Hapus format angka
                    }
                });
            });
        }
    });
</script>
