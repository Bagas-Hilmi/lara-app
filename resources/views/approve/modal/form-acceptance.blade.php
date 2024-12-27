<div class="modal fade" id="formModal" tabindex="-1" aria-labelledby="formModalLabel">
    <div class="modal-dialog modal-l modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header" style="background-color: #17a2b8;">
                <h5 class="modal-title" id="formModalLabel" style="color: white;">FORM PROJECT ACCEPTANCE CHECKLIST</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Tutup"></button>
            </div>
            <div class="modal-body">
                <div class="container-fluid">
                    <!-- Tanda Tangan Admin -->
                    <div class="row mb-3">

                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    $(document).ready(function() {
        $('#formModal').on('show.bs.modal', function(event) {
            var button = $(event.relatedTarget);
            var idCapex = button.data('id'); // Ambil id_capex dari tombol yang diklik

            // Lakukan Ajax request untuk mendapatkan data berdasarkan id_capex
            $.ajax({
                url: '/approve/' + idCapex, // Ganti dengan rute yang   sesuai
                method: 'GET',
                data: {
                    flag: 'show-form' // Menambahkan flag untuk menunjukkan ini adalah permintaan untuk menampilkan form
                },
                success: function(response) {
                    if (response.error) {
                        Swal.fire({
                            icon: 'error',
                            title: 'Error!',
                            text: response.error
                        });
                        return;
                    }

                    // Mengisi modal dengan data dari respons
                    var modalContent = `
                    <div class="container-fluid">
                        <div class="row mb-3">
                            <div class="col-12">
                                <div class="form-group">
                                    <h5><span>WBS Type: </span>${response.wbs_type}</h5>
                                </div>

                                <div class="info-row">
                                        <div class="label">CAPEX</div>
                                        <div class="colon">:</div>
                                        <div class="value">${response.capex_number}</div>
                                    </div>
                                    <div class="info-row">
                                        <div class="label">Project Title</div>
                                        <div class="colon">:</div>
                                        <div class="value">${response.project_desc}</div>
                                    </div>
                                    <div class="info-row">
                                        <div class="label">SAP Asset Number</div>
                                        <div class="colon">:</div>
                                        <div class="value">${response.cip_number}</div>
                                    </div>
                                    <div class="info-row">
                                        <div class="label">WBS Number</div>
                                        <div class="colon">:</div>
                                        <div class="value">${response.wbs_number}</div>
                                    </div>
                                    <div class="info-row">
                                        <div class="label">Date</div>
                                        <div class="colon">:</div>
                                        <div class="value">${response.startup}</div>
                                    </div>
                                    <div class="info-row">
                                        <div class="label">Expected Completed</div>
                                        <div class="colon">:</div>
                                        <div class="value">${response.expected_completed}</div>
                                    </div>
                                    <div class="info-row">
                                        <div class="label">Actual Completed</div>
                                        <div class="colon">:</div>
                                        <div class="value">${response.date}</div>
                                    </div>

                                <!-- BERITA ACARA PENYELESAIAN CAPEX -->
                                <div class="row mb-3">
                                    <div class="col-md-12">
                                        <h5 class="mb-3">BERITA ACARA PENYELESAIAN CAPEX</h5>

                                        <!-- WBS-P Section -->
                                        <div class="mb-2">
                                            <strong>WBS-P</strong>
                                            <div class="ms-4">
                                                <div class="form-check d-flex justify-content-between">
                                                    <label for="engineering">Engineering & Production</label>
                                                    <input type="checkbox" id="engineering" ${response.engineering_production ? 'checked' : ''} disabled>
                                                </div>
                                                <div class="form-check d-flex justify-content-between">
                                                    <label for="maintenance">Maintenance & ............</label>
                                                    <input type="checkbox" id="maintenance" ${response.maintenance ? 'checked' : ''} disabled>
                                                </div>
                                            </div>
                                        </div>

                                        <!-- Outstanding Section -->
                                        <div class="mb-2">
                                            <div class="form-check d-flex justify-content-between">
                                                <label for="outstandingInventory">Outstanding Inventory</label>
                                                <input type="checkbox" id="outstandingInventory" ${response.outstanding_inventory ? 'checked' : ''} disabled>
                                            </div>
                                        </div>

                                        <!-- Outstanding PO Section -->
                                        <div class="mb-2">
                                            <strong>Outstanding PO</strong>
                                            <div class="ms-4">
                                                <div class="form-check d-flex justify-content-between">
                                                    <label for="material">Material</label>
                                                    <input type="checkbox" id="material" ${response.material ? 'checked' : ''} disabled>
                                                </div>
                                                <div class="form-check d-flex justify-content-between">
                                                    <label for="jasa">Jasa</label>
                                                    <input type="checkbox" id="jasa" ${response.jasa ? 'checked' : ''} disabled>
                                                </div>
                                                <div class="form-check d-flex justify-content-between">
                                                    <label for="etc">Etc</label>
                                                    <input type="checkbox" id="etc" ${response.etc ? 'checked' : ''} disabled>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- BERITA ACARA PENERIMAAN BARANG -->
                                <div class="row mb-3">
                                    <div class="col-md-12">
                                        <h5 class="mb-3">BERITA ACARA PENERIMAAN BARANG</h5>

                                        <!-- WBS-A Section -->
                                        <div class="mb-2">
                                            <strong>WBS-A</strong>
                                            <div class="ms-4">
                                                <div class="form-check d-flex justify-content-between">
                                                    <label for="warehouse">Barang diterima oleh Warehouse</label>
                                                    <input type="checkbox" id="warehouse" ${response.warehouse_received ? 'checked' : ''} disabled>
                                                </div>
                                                <div class="form-check d-flex justify-content-between">
                                                    <label for="user">Barang diterima oleh User</label>
                                                    <input type="checkbox" id="user" ${response.user_received ? 'checked' : ''} disabled>
                                                </div>
                                                <div class="form-check d-flex justify-content-between">
                                                    <label for="beritaAcara">Berita Acara</label>
                                                    <input type="checkbox" id="beritaAcara" ${response.berita_acara ? 'checked' : ''} disabled>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                            </div>
                        </div>
                    </div>

                `;
                    // Masukkan konten ke dalam modal-body
                    $('#formModal .modal-body').html(modalContent);
                },
                error: function(xhr) {
                    Swal.fire({
                        icon: 'error',
                        title: 'Error!',
                        text: 'Terjadi kesalahan saat mengambil data.'
                    });
                }
            });
        });
    });
</script>

<style>
    /* Ganti warna kotak checkbox */
    .form-check input[type="checkbox"] {
        width: 20px;
        height: 20px;
        border-radius: 3px;
        border: 2px solid #007bff;
        /* Warna border default */
        background-color: #fff;
    }

    /* Ketika checkbox dicentang (checked) */
    .form-check input[type="checkbox"]:checked {
        background-color: #28a745;
        /* Ganti warna latar belakang ketika dicentang */
        border-color: #28a745;
        /* Ganti warna border ketika dicentang */
    }

    /* Ganti warna ketika hover (untuk interaksi) */
    .form-check input[type="checkbox"]:hover {
        border-color: #007bff;
    }

    /* Ganti warna kotak ketika checkbox fokus */
    .form-check input[type="checkbox"]:focus {
        outline: none;
        box-shadow: 0 0 0 2px rgba(0, 123, 255, 0.5);
    }
</style>

<style>
    .info-container {
        font-family: Arial, sans-serif;
        width: 100%;
        max-width: 500px;
        padding: 15px;
        border: 1px solid #ccc;
        margin: 20px auto;
    }

    .info-row {
        display: flex;
        margin-bottom: 8px;
        line-height: 1.5;
    }

    .label {
        min-width: 160px;
        font-weight: bold;
    }

    .value {
        flex: 1;
    }

    .colon {
        margin: 0 10px;
    }
</style>
