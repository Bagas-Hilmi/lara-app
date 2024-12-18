<!-- Modal Upload PDF -->
<div class="modal fade" id="uploadPDF" tabindex="-1" aria-labelledby="uploadPDFLabel">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header" style="background-color: #42bd37;">
                <h5 class="modal-title" id="uploadPDFLabel" style="color: white;">Upload PDF</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="uploadFormPDF" action="{{ route('approve.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <!-- Input Hidden untuk id_capex -->
                    <input type="hidden" id="hidden-id-capex" name="id_capex" value="">
                    <div class="container-fluid">
                        <div class="row mb-3">
                            <div class="col-md-12">
                                <label class="form-label" for="bast">File PDF</label>
                                <input type="file" class="form-control" id="bast" name="file_pdf" accept="application/pdf" required>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn bg-gradient-success" id="uploadFile">Upload</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>


<script>
    $(document).ready(function() {
        // Saat modal upload dibuka, set value id_capex
        $('#uploadPDF').on('show.bs.modal', function(event) {
            var button = $(event.relatedTarget); // Mengambil elemen yang memicu modal
            var idCapex = button.data('id'); // Mengambil id_capex dari tombol
            $('#hidden-id-capex').val(idCapex); // Menetapkan id_capex ke input hidden
        });

        // Handle form submit
        $('#uploadFormPDF').on('submit', function(e) {
            e.preventDefault();
            let formData = new FormData(this);

            $.ajax({
                url: "{{ route('approve.store') }}", // Pastikan route sudah sesuai
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
                        location
                    .reload(); // Reload halaman untuk menampilkan data terbaru
                    });
                },
                error: function(xhr) {
                    let errorMessage = xhr.responseJSON.error || 'File PDF gagal diunggah';
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
