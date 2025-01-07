<!-- Modal Upload PDF -->
<div class="modal fade" id="uploadPDF" tabindex="-1" aria-labelledby="uploadPDFLabel">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header" style="background-color: #42bd37;">
                <h5 class="modal-title" id="uploadPDFLabel" style="color: white;">Upload PDF</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="uploadFormPDF" action="{{ route('approve.store') }}" method="POST"
                    enctype="multipart/form-data">
                    @csrf
                    <!-- Input Hidden untuk id_capex -->
                    <input type="hidden" id="hidden-id-capex" name="id_capex" value="">
                    <input type="hidden" name="flag" id="flag" value="upload-pdf">

                    <div class="container-fluid">
                        <div class="row mb-3">
                            <div class="col-md-12">
                                <label class="form-label" for="bast">Upload PDF</label>
                                <input type="file" class="form-control" id="bast" name="file_pdf"
                                    accept="application/pdf" required>
                                    <label>Allowed file : PDF</label>
                            </div>
                        </div>

                        <div class="dropdown">
                            <button class="btn btn-secondary dropdown-toggle"
                                style="background-color: #09170a; border-color: #09170a;" type="button"
                                data-bs-toggle="dropdown" aria-expanded="false">
                                Select
                            </button>
                            <ul class="dropdown-menu" aria-labelledby="dropdownMenu2">
                                <button class="dropdown-item" type="button">WBS-P</button>
                                <button class="dropdown-item" type="button">WBS-A</button>

                            </ul>
                        </div>
                        <div class="row mb-3">
                            <label for="date" class="form-label font-weight-bold">Date
                                Upload</label>
                            <input type="date" style="text-align: center;" class="form-control" id="date" name="date"
                                required>
                        </div>
                        <div class="row mb-3">
                            <label for="remark" class="form-label font-weight-bold">Remark</label>
                            <input type="text" style="text-align: center;" class="form-control" id="date" name="remark" placeholder="Jika kosong isi -">
                        </div>

                        <!-- BERITA ACARA PENYELESAIAN CAPEX -->
                        <div class="row mb-3">
                            <div class="col-md-12">
                                <h5 class="mb-3">BERITA ACARA PENYELESAIAN CAPEX</h5>

                                <!-- WBS-P Section -->
                                <div class="mb-2">
                                    <strong>WBS-P</strong>
                                    <div class="ms-4">
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" id="engineering">
                                            <label class="form-check-label" for="engineering">Engineering &
                                                Production</label>
                                        </div>
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" id="maintenance">
                                            <label class="form-check-label" for="maintenance">Maintenance &
                                                ............</label>
                                        </div>
                                    </div>
                                </div>

                                <!-- Outstanding Section -->
                                <div class="mb-2">
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" id="outstandingInventory">
                                        <label class="form-check-label" for="outstandingInventory">Outstanding
                                            Inventory</label>
                                    </div>
                                </div>

                                <!-- Outstanding PO Section -->
                                <div class="mb-2">
                                    <strong>Outstanding PO</strong>
                                    <div class="ms-4">
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" id="material">
                                            <label class="form-check-label" for="material">Material</label>
                                        </div>
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" id="jasa">
                                            <label class="form-check-label" for="jasa">Jasa</label>
                                        </div>
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" id="etc">
                                            <label class="form-check-label" for="etc">Etc</label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- BERITA ACARA PENERIMAAN BARANG -->
                        <div class="row">
                            <div class="col-md-12">
                                <h5 class="mb-3">BERITA ACARA PENERIMAAN BARANG</h5>

                                <!-- WBS-A Section -->
                                <div class="mb-2">
                                    <strong>WBS-A</strong>
                                    <div class="ms-4">
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" id="warehouse">
                                            <label class="form-check-label" for="warehouse">Barang diterima oleh
                                                Warehouse</label>
                                        </div>
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" id="user">
                                            <label class="form-check-label" for="user">Barang diterima oleh
                                                User</label>
                                        </div>
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" id="beritaAcara">
                                            <label class="form-check-label" for="beritaAcara">Berita Acara</label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="modal-footer">
                        <button type="submit" class="btn bg-gradient-success" id="uploadFile">Save</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>


<script>
    $(document).ready(function() {
        // Variable untuk menyimpan WBS yang dipilih
        let selectedWBS = 'Select';

        // Handle klik item dropdown
        $('.dropdown-item').click(function() {
            selectedWBS = $(this).text().trim(); // Pastikan tidak ada spasi ekstra
            $('.dropdown-toggle').text(selectedWBS);
        });

        // Saat modal upload dibuka
        $('#uploadPDF').on('show.bs.modal', function(event) {
            var button = $(event.relatedTarget);
            var idCapex = button.data('id');
            $('#hidden-id-capex').val(idCapex);

            // Reset dropdown ke default
            $('.dropdown-toggle').text('Select');
            selectedWBS = 'Select';
        });

        // Handle submit form
        $('#uploadFormPDF').on('submit', function(e) {
            e.preventDefault();

            // Validasi WBS
            if (selectedWBS === 'Select') {
                Swal.fire({
                    icon: 'error',
                    title: 'Error!',
                    text: 'Silakan pilih WBS type terlebih dahulu'
                });
                return;
            }

            let formData = new FormData(this);

            formData.append('flag', 'upload-pdf');
            formData.append('wbs_type', selectedWBS);
            formData.append('date', $('#date').val());

            // Mengubah nilai checkbox menjadi string "true" atau "false"
            const checkboxes = {
                'engineering': $('#engineering'),
                'maintenance': $('#maintenance'),
                'outstanding_inventory': $('#outstandingInventory'),
                'material': $('#material'),
                'jasa': $('#jasa'),
                'etc': $('#etc'),
                'warehouse': $('#warehouse'),
                'user': $('#user'),
                'berita_acara': $('#beritaAcara')
            };

            // Append setiap nilai checkbox
            Object.entries(checkboxes).forEach(([key, element]) => {
                formData.append(key, element.is(':checked') ? "1" : "0");
            });


            $.ajax({
                url: "{{ route('approve.store') }}",
                type: "POST",
                data: formData,
                contentType: false,
                processData: false,
                beforeSend: function() {
                    Swal.fire({
                        title: 'Uploading...',
                        text: 'Silakan tunggu...',
                        allowOutsideClick: false,
                        didOpen: () => {
                            Swal.showLoading();
                        }
                    });
                },
                success: function(response) {
                    Swal.fire({
                        icon: 'success',
                        title: 'Sukses!',
                        text: response.success,
                        timer: 2000,
                        timerProgressBar: true
                    }).then(() => {
                        location.reload();
                    });
                },
                error: function(xhr) {
                    let errorMessage = '';
                    if (xhr.responseJSON && xhr.responseJSON.errors) {
                        // Gabungkan semua pesan error
                        errorMessage = Object.values(xhr.responseJSON.errors)
                            .flat()
                            .join('\n');
                    } else {
                        errorMessage = xhr.responseJSON.error || 'File PDF gagal diunggah';
                    }
                    Swal.fire({
                        icon: 'error',
                        title: 'Error!',
                        text: errorMessage
                    });
                }
            });
        });
    });
</script>

<style>
    .form-check {
        display: flex;
        justify-content: space-between;
    }

    .form-check-input {
        order: 1;
        /* Memastikan checkbox muncul di sebelah kanan */
    }

    .form-check-label {
        order: 0;
        /* Memastikan label muncul di sebelah kiri */
    }
</style>
