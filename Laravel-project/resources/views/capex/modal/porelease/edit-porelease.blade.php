<div class="modal fade" id="edit-porelease-modal" tabindex="-1" aria-labelledby="editPoreleaseModalLabel"
    aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header" style="background-color: #42bd37">
                <h4 class="modal-title" id="editPoreleaseModalLabel" style="color: white;"> Edit PO Release </h4>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="edit-porelease-form" action="{{ route('capex.store') }}" method="POST">
                    <input type="hidden" id="edit_porelease_id" name="id" value="">
                    <input type="hidden" name="flag" value="edit-porelease">
                    <input type="hidden" id="edit_porelease_capex_id" name="id_capex" value="">
                    @csrf
                    <div class="mb-3 row">
                        <label for="edit_description_porelease" class="form-label font-weight-bold">Description</label>
                        <div class="col">
                            <input type="text" class="form-control" id="edit_description_porelease"
                                name="description_porelease" style="text-align: center;" required>
                        </div>
                    </div>
                    <div class="mb-3 row">
                        <label for="edit_porelease" class="form-label font-weight-bold">PO Release</label>
                        <div class="col">
                            <input type="text" class="form-control column-input edit-porelease" id="edit_porelease"
                                name="po_release" style="text-align: center;" required>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn bg-gradient-secondary" data-bs-dismiss="modal"> Close</button>
                        <button type="submit" class="btn bg-gradient-success">Submit</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
    function editPorelease(id) {
        $.ajax({
            url: '/capex/' + id + '/edit', // Sesuaikan dengan route edit Anda
            method: 'GET',
            success: function(data) {
                // Set nilai ke input dalam modal
                $('#edit_porelease_id').val(data.id_capex_porelease); // ID porelease
                $('#edit_porelease_capex_id').val(data.id_capex); // ID capex
                $('#edit_description_porelease').val(data.description); // Ubah ke description_porelease
                $('#edit_porelease').val(data.po_release);

                // Tampilkan modal
                $('#edit-porelease-modal').modal('show');
            },
            error: function(xhr) {
                console.log("Error: ", xhr.responseText); // Log kesalahan
                alert('Terjadi kesalahan saat mengambil data.');
            }
        });
    }

    $('#edit-porelease-form').on('submit', function(e) {
        e.preventDefault(); // Mencegah reload halaman
        var formData = $(this).serialize(); // Mengambil data dari form

        // Tampilkan konfirmasi sebelum mengirim data
        Swal.fire({
            title: 'Konfirmasi',
            text: 'Apakah Anda yakin ingin menyimpan perubahan ini?',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Ya, Perbarui!',
            cancelButtonText: 'Tidak'
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    url: $(this).attr('action'), // URL untuk mengirim request
                    method: 'POST',
                    data: formData,
                    success: function(response) {
                        $('#edit-porelease-modal').modal('hide'); // Menyembunyikan modal
                        $('#porelease-table').DataTable().ajax.reload(); // Reload DataTable
                        Swal.fire({
                            title: 'Berhasil!',
                            text: response.message, // Menampilkan pesan sukses
                            icon: 'success',
                            showConfirmButton: false,
                            timer: 1000
                        });
                        $('#capex-table').DataTable().ajax.reload(); // Reload DataTable
                    },
                    error: function(xhr) {
                        console.log("Error: ", xhr.responseText); // Log kesalahan
                        Swal.fire({
                            title: 'Error!',
                            text: 'Terjadi kesalahan: ' + xhr
                            .responseText, // Menampilkan pesan error
                            icon: 'error',
                            confirmButtonText: 'OK'
                        });
                    }
                });
            }
        });
        $('#edit-porelease-modal').on('hidden.bs.modal', function () {
            // Kosongkan semua input field
            $('#edit-porelease-form')[0].reset();
            // Hapus class is-invalid jika ada
            $('#edit-porelease-form').find('input').removeClass('is-invalid');
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
    const editPoreleaseModal = document.getElementById('edit-porelease-modal');
    editPoreleaseModal.addEventListener('show.bs.modal', function(event) {
        const button = event.relatedTarget; // Tombol yang memicu modal

        // Ambil data dari tombol
        const id = button.getAttribute('data-id');
        const capexId = button.getAttribute('data-capex-id');
        const poRelease = button.getAttribute('data-porelease');
        const descriptionPorelease = button.getAttribute('data-description-porelease');

        // Isi data ke dalam modal
        const editPoreleaseIdInput = document.getElementById('edit_porelease_id');
        const editPoreleaseCapexIdInput = document.getElementById('edit_porelease_capex_id');
        const editPoreleaseInput = document.getElementById('edit_porelease');
        const editDescriptionPoreleaseInput = document.getElementById('edit_description_porelease');

        editPoreleaseIdInput.value = id;
        editPoreleaseCapexIdInput.value = capexId;
        editPoreleaseInput.value = poRelease;
        editDescriptionPoreleaseInput.value = descriptionPorelease;
    });
</script>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        const numberInputs = document.querySelectorAll('input.edit-porelease'); // Menggunakan kelas khusus untuk input update

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
