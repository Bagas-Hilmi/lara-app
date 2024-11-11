<div class="modal fade" id="engineer-form" tabindex="-1" aria-labelledby="engineerFormLabel" aria-hidden="true">
    <div class="modal-dialog modal-md modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header" style="background-color: #42bd37;">
                <h5 class="modal-title" id="engineerFormLabel" style="color: white;">Engineering Management</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="mb-3">
                    <button class="btn btn-primary mb-3" style="background-color: #09170a; border-color: #09170a;"
                        data-bs-toggle="modal" data-bs-target="#newEngineerModal">
                        New Engineer
                    </button>
                </div>

                <div class="table-responsive p-0">
                    <table id="engineerTable" class="table table-striped">
                        <thead>
                            <tr>
                                <th class="text-center">No</th>
                                <th class="text-center">Name</th>
                            </tr>
                        </thead>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="newEngineerModal" tabindex="-1" aria-labelledby="newEngineerModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-md modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header" style="background-color: #42bd37;">
                <h5 class="modal-title" id="newEngineerModalLabel" style="color: white;">Add New Engineer</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="new-engineer" action="{{ route('capex.store') }}" method="POST">
                    <input type="hidden" name="flag" value="add-engineer">
                    <input type="hidden" id="new_engineer" name="id_capex" value="">
                    @csrf
                    <div class="mb-3 row">
                        <label for="nama" class="form-label">Engineer Name</label>
                        <div class="col">
                            <input type="text" class="form-control" id="nama" name="nama" required>
                        </div>
                    </div>
                    <button type="submit" class="btn btn-primary">Save</button>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
    $('#new-engineer').on('submit', function(e) {
        e.preventDefault(); // Mencegah tindakan default dari formulir

        // Ambil data dari form
        var formData = $(this).serialize();
        console.log("Form Data: ", formData); // Log form data sebelum dikirim

        // Cek apakah semua field yang required terisi
        var isValid = true;
        $(this).find('input[required], select[required]').each(function() {
            if ($(this).val() === '') {
                isValid = false;
                $(this).addClass(
                    'is-invalid'); // Tambahkan kelas invalid untuk menandai field yang kosong
            } else {
                $(this).removeClass('is-invalid'); // Hapus kelas invalid jika terisi
            }
        });

        if (!isValid) {
            Swal.fire({
                title: 'Error!',
                text: 'Silakan lengkapi semua input yang diperlukan.',
                icon: 'error',
                confirmButtonText: 'OK'
            });
            return; // Hentikan eksekusi jika ada field yang kosong
        }

        // Tampilkan konfirmasi sebelum mengirim data
        Swal.fire({
            title: 'Konfirmasi',
            text: 'Apakah Anda yakin ingin menambahkan Completion Date ini?',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Ya, tambahkan!',
            cancelButtonText: 'Tidak'
        }).then((result) => {
            if (result.isConfirmed) {
                // Kirim data melalui AJAX jika pengguna menekan "Ya"
                $.ajax({
                    url: $(this).attr('action'), // Sesuaikan dengan route Anda
                    method: 'POST',
                    data: formData,
                    success: function(response) {
                        $('#engineer-form').modal('hide');
                        $('#engineerTable').DataTable().ajax.reload();

                        // Tampilkan pesan sukses dengan SweetAlert
                        Swal.fire({
                            title: 'Berhasil!',
                            text: ' berhasil ditambahkan!',
                            icon: 'success',
                            confirmButtonText: 'OK'
                        }).then(() => {
                            // Refresh halaman setelah menutup pesan sukses
                            $('#capex-table').DataTable().ajax
                                .reload(); // Reload DataTable
                        });
                    },
                    error: function(xhr) {
                        console.log("Error: ", xhr.responseText); // Log kesalahan
                        Swal.fire({
                            title: 'Terjadi kesalahan!',
                            text: 'Error: ' + xhr.responseText,
                            icon: 'error',
                            confirmButtonText: 'OK'
                        });
                    }
                });
            }
        });
    });
</script>

<script>
   $(document).ready(function() {
    var capexId = {{ $capexId ?? 'null' }}; // Ambil id_capex dari server

    // Jika capexId tidak ada, langsung load DataTable untuk semua data
    if (!capexId) {
        loadDataTable(); // Load tanpa filter
    } else {
        loadDataTable(capexId); // Load berdasarkan capexId yang diberikan
    }

});

// Fungsi untuk load DataTable
function loadDataTable(capexId = null) {
    $('#engineerTable').DataTable({
        processing: true,
        serverSide: true,
        order: [[0, 'desc']],
        ajax: {
            url: '/capex/engineers', // URL untuk mendapatkan data engineer
            type: 'GET',
            data: function(d) {
                if (capexId) {
                    d.id_capex = capexId;  // Kirim id_capex ke server jika ada
                }
                d.flag = 'engineer';   // Kirim flag untuk filter data engineer
            }
        },
        columns: [
            { data: 'id_engineer', className: 'text-center' },
            { data: 'nama', className: 'text-center' }
        ]
    });
}

    
</script>