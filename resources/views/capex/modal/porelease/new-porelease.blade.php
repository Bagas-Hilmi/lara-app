<div class="modal fade" id="new-porelease-modal" tabindex="-1" aria-labelledby="newPoreleaseModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header" style="background-color: #42bd37;">
                <h4 class="modal-title" id="newPoreleaseModalLabel" style="color: white;">Add New PO Release</h4>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="new-porelease-form" action="{{ route('capex.store') }}" method="POST">
                    <input type="hidden" name="flag" value="add-porelease">
                    <input type="hidden" id="new_porelease_capex_id" name="id_capex" value="">
                    @csrf
                    <div class="mb-3 row">
                        <label for="description" class="form-label font-weight-bold">Description</label>
                        <div class="col">
                            <input type="text" class="form-control" id="description" name="description" style="text-align: center;" required>
                        </div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label for="porelease" class="form-label font-weight-bold">PO Release (USD)</label>
                            <input type="number" class="form-control column-input new-porelease" id="po_release" name="po_release" style="text-align: center;" required>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn bg-gradient-secondary" data-bs-dismiss="modal">Close</button>
                        <button type="submit" class="btn bg-gradient-success">Submit</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        const numberInputs = document.querySelectorAll('input.new-porelease'); // Menggunakan kelas khusus untuk input update
  
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
                    this.value = parseFloat(value).toFixed(2); // Format menjadi 2 desimal
                }
            });
        });
    });
</script>

<script>
    $('#new-porelease-form').on('submit', function (e) {
            e.preventDefault();
            var formData = $(this).serialize();
            console.log("Form Data: ", formData); // Log form data sebelum dikirim
            
            $.ajax({
                url: $(this).attr('action'), // Sesuaikan dengan route Anda
                method: 'POST',
                data: formData,
                success: function (response) {
                    $('#new-porelease-modal').modal('hide');
                    $('#porelease-table').DataTable().ajax.reload();
                    alert('PO Release berhasil ditambahkan!');
                     // Refresh halaman
                    location.reload(); // Melakukan refresh halaman
                },
                error: function (xhr) {
                    console.log("Error: ", xhr.responseText); // Log kesalahan
                    alert('Terjadi kesalahan: ' + xhr.responseText);
                }
            });
        });
</script>