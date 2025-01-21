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
                    </div>

                    <div class="modal-footer">
                        <button type="button" class="btn bg-gradient-secondary" data-bs-dismiss="modal">Close</button>
                        <button type="submit" class="btn bg-gradient-success" id="uploadFile">Upload</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>


<script>
    $(document).on('click', '[data-bs-target="#uploadPDF"]', function() {
        // Ambil nilai data-id dari tombol yang diklik
        const idCapex = $(this).data('id');

        // Set nilai idCapex ke input hidden di modal
        $('#hidden-id-capex').val(idCapex);
    });

    $('#uploadFormPDF').on('submit', function(e) {
        e.preventDefault();

        let formData = new FormData(this);

        formData.append('flag', 'upload-pdf');

        Swal.fire({
            title: 'Konfirmasi Upload',
            text: 'Apakah Anda yakin ingin mengupload file PDF ini?',
            icon: 'question',
            showCancelButton: true,
            confirmButtonText: 'Ya',
            cancelButtonText: 'Tidak',
            reverseButtons: true
        }).then((result) => {
            if (result.isConfirmed) {
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
                            errorMessage = Object.values(xhr.responseJSON.errors)
                                .flat()
                                .join('\n');
                        } else {
                            errorMessage = xhr.responseJSON.error ||
                                'File PDF gagal diunggah';
                        }
                        Swal.fire({
                            icon: 'error',
                            title: 'Error!',
                            text: errorMessage
                        });
                    }
                });
            } else {
                Swal.fire({
                    icon: 'info',
                    title: 'Upload Dibatalkan',
                    text: 'Proses upload dibatalkan.',
                });
            }
        });
    });
</script>