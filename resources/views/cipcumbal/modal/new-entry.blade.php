<div class="modal fade" id="new-form" tabindex="-1" aria-labelledby="newFormLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header" style="background-color: #42bd37;">
                <h5 class="modal-title" id="newFormLabel" style="color: white;">New Entry Cip Cumulative Balance</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="entryForm" action="{{ route('cipcumbal.store') }}" method="POST">
                    @csrf
                    <input type="hidden" name="flag" value="add">

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
    document.getElementById('saveEntry').addEventListener('click', function() {
        const form = document.getElementById('entryForm');

        // Fungsi untuk membersihkan format angka
        function cleanNumber(value) {
            // Hapus semua koma dan convert ke number
            return value.replace(/,/g, '');
        }

        if (form.checkValidity()) {
            const formData = new FormData(form);
            formData.append('mode', 'ADD');

            // Bersihkan format number sebelum mengirim
            const numberFields = ['bal_usd', 'bal_rp', 'cumbal_usd', 'cumbal_rp'];
            numberFields.forEach(field => {
                const input = document.getElementsByName(field)[0];
                const cleanedValue = cleanNumber(input.value);
                formData.set(field, cleanedValue);
            });

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
                                Swal.fire({
                                    title: "Sukses!",
                                    text: "Entry berhasil di upload!",
                                    icon: "success",
                                    showConfirmButton: false,
                                    timer: 1000
                                });
                                $('#cipCumBalTable').DataTable().ajax.reload();
                                $('#new-form').modal('hide');

                                // Reset form setelah sukses
                                form.reset();
                            } else {
                                Swal.fire(
                                    'Gagal!',
                                    data.message || 'An error occurred while saving.',
                                    'error'
                                );
                            }
                        },
                        error: function(xhr, status, error) {
                            Swal.fire(
                                'Gagal!',
                                'An error occurred: ' + error,
                                'error'
                            );
                            console.error('Error:', error);
                        }
                    });
                }
            });
        } else {
            Swal.fire({
                title: 'Error!',
                text: 'Silakan lengkapi semua input yang diperlukan.',
                icon: 'error',
                confirmButtonText: 'OK'
            });
        }
    });
</script>

<script>
    // Function to format input numbers with commas and periods
    function formatNumber(input) {
        // Remove non-numeric characters, except for the period
        let value = input.value.replace(/[^0-9.]/g, '');

        // Format the value to include commas as thousand separators
        value = value.replace(/\B(?=(\d{3})+(?!\d))/g, ',');

        // Display the formatted value in the input field
        input.value = value;
    }

    // Function to handle form submission (remove commas and periods for storage)
    function handleFormSubmit(event) {
        const inputs = document.querySelectorAll('input[type="text"]');
        inputs.forEach(input => {
            // Remove commas and periods before submitting
            input.value = input.value.replace(/,/g, '').replace(/\./g, '');
        });
    }

    // Attach format function to the inputs
    document.querySelectorAll('input[type="text"]').forEach(input => {
        input.addEventListener('input', function() {
            formatNumber(input);
        });
    });

    // Attach form submit handler
    const form = document.querySelector('form');
    if (form) {
        form.addEventListener('submit', handleFormSubmit);
    }
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
