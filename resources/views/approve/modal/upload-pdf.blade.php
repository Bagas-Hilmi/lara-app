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
    $('#uploadFormPDF').on('submit', function(e) {
        e.preventDefault();
        
        let formData = new FormData(this);

        formData.append('flag', 'upload-pdf');

        // Menampilkan SweetAlert2 dengan tombol Ya dan Tidak
        Swal.fire({
            title: 'Konfirmasi Upload',
            text: 'Apakah Anda yakin ingin mengupload file PDF ini?',
            icon: 'question',
            showCancelButton: true, // Menampilkan tombol 'Tidak'
            confirmButtonText: 'Ya',
            cancelButtonText: 'Tidak',
            reverseButtons: true // Membalikkan urutan tombol
        }).then((result) => {
            if (result.isConfirmed) {
                // Jika tombol 'Ya' diklik, lanjutkan dengan upload
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
            } else {
                // Jika tombol 'Tidak' diklik, tampilkan pesan pembatalan
                Swal.fire({
                    icon: 'info',
                    title: 'Upload Dibatalkan',
                    text: 'Proses upload dibatalkan.',
                });
            }
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
