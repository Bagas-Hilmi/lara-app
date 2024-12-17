<div class="modal fade" id="approveModal" tabindex="-1" aria-labelledby="approveModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header" style="background-color: #42bd37;">
                <h5 class="modal-title" id="approveModalLabel" style="color: white;">Approve dan Tanda Tangan</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="approveForm" action="{{ route('capex.store') }}" method="POST"
                    enctype="multipart/form-data">
                    @csrf
                    @method('PUT')
                    <input type="hidden" id="capexId" name="id_capex" value="">
                    <input type="hidden" name="flag" value="upload-pdf"> 

                    <div class="form-group">
                        <label for="signature">Tanda Tangan:</label>
                        <input type="text" id="signature" name="signature" class="form-control" required
                            placeholder="Masukkan tanda tangan Anda">
                    </div>
                    <div class="form-group mt-3">
                        <label for="comment">Komentar:</label>
                        <textarea id="comment" name="comment" class="form-control" placeholder="Masukkan komentar"></textarea>
                    </div>

                    <!-- Form untuk mengunggah PDF -->
                    <div class="form-group mt-3">
                        <label for="file_pdf">File PDF:</label>
                        <input type="file" name="file_pdf" id="file_pdf" class="form-control" accept=".pdf">
                    </div>

                    <div class="mt-3">
                        <button type="submit" class="btn btn-success">Approve dan Tandatangani</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>


<script>
    $(document).ready(function() {
        // Set ID capex untuk modal approve
        $('#approveModal').on('show.bs.modal', function(event) {
            var button = $(event.relatedTarget); // Tombol yang memicu modal
            var capexId = button.data('id'); // Ambil ID capex dari tombol
            $('#capexId').val(capexId); // Set ID capex ke input tersembunyi
        });

        // Mengirim form approve
        $('#approveForm').on('submit', function(e) {
            e.preventDefault();
            var formData = new FormData(this);

            $.ajax({
                url: $(this).attr('action'),
                type: 'POST',
                data: formData,
                contentType: false,
                processData: false,
                beforeSend: function() {
                    Swal.fire({
                        title: 'Menandatangani...',
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
                        title: 'Berhasil!',
                        text: 'File telah disetujui dan ditandatangani.',
                        timer: 2000,
                        timerProgressBar: true
                    }).then(() => {
                        location.reload(); // Refresh halaman
                    });
                },
                error: function(xhr) {
                    var errorMessage = xhr.responseJSON.error ||
                    'Gagal menandatangani file';
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
