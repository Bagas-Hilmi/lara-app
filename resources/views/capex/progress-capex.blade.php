<div class="modal fade" id="progress-modal" tabindex="-1" aria-labelledby="progressModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header" style="background-color: #42bd37;">
                <h5 class="modal-title" id="progressModalLabel" style="color: white;">Progress Details</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">

                <div class="mb-3">
                    <!-- Tombol untuk New Progress -->   
                    <button type="button" class="btn btn-primary view-progress-btn"
                        style="background-color: #09170a; border-color: #09170a;"
                        id="new-progress-btn"
                        data-bs-toggle="modal"
                        data-bs-target="#new-progress-modal">
                        <i class="fas fa-plus"></i> NEW PROGRESS
                    </button>
                </div>
                <div class="table-responsive p-0">
                    <table id="progress-table" class="table table-striped nowrap rounded-table p-0" style="width:100%">
                        <thead>
                            <tr>
                                <th align="center">Action</th>
                                <th align="center">ID Capex Progress</th>
                                <th align="center">ID Capex</th>
                                <th align="center">Tanggal</th>
                                <th align="center">Description</th>
                                <th align="center">Created_at</th>
                                <th align="center">Updated_at</th>
                            </tr>
                        </thead>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
        @include('capex.modal.progress.new-progress')
        @include('capex.modal.progress.edit-progress')

<script>
    $(document).ready(function() {
        // Event saat modal dibuka
        $('#progress-modal').on('shown.bs.modal', function (e) {
            var idCapex = $(e.relatedTarget).data('id'); // Ambil ID Capex dari data-id
            $('#new-progress-btn').data('id', idCapex); // Set data-id untuk tombol NEW BUDGET

            // Inisialisasi DataTables
            $('#progress-table').DataTable({
                processing: true,
                serverSide: true,
                destroy: true, // Agar inisialisasi ulang setiap kali modal dibuka
                order: [[1, 'desc']],
                ajax: {
                    url: '/capex/' + idCapex, // Menggunakan metode show dari Route::resource
                    type: 'GET',
                    data: {
                        flag: 'progress' // Kirim flag untuk identifikasi request
                    },
                },
                columns: [
                    { data: 'action', name: 'action', orderable: false, searchable: false, className: 'text-center' },
                    { data: 'id_capex_progress', name: 'id_capex_progress', className: 'text-center' },
                    { data: 'id_capex', name: 'id_capex', className: 'text-center' },
                    { data: 'tanggal', name: 'tanggal', className: 'text-center' },
                    { data: 'description', name: 'description', className: 'text-center' },
                    {
                        data: 'created_at',
                        name: 'created_at',
                        className: 'text-center',
                        render: function(data) {
                            return moment(data).format('YYYY-MM-DD HH:mm:ss'); // Format tanggal
                        }
                    },
                    {
                        data: 'updated_at',
                        name: 'updated_at',
                        className: 'text-center',
                        render: function(data) {
                            return moment(data).format('YYYY-MM-DD HH:mm:ss'); // Format tanggal
                        }
                    }
                ]
            });
        });

        $('#new-progress-modal').on('show.bs.modal', function () {
            var idCapex = $('#new-progress-btn').data('id'); // Ambil id_capex dari tombol NEW BUDGET
            console.log("ID Capex: ", idCapex); // Log ID capex
            $('#new_progress_capex_id').val(idCapex); // Set nilai ke input tersembunyi
        });
        
        $('#new-progress-form').on('submit', function (e) {
            e.preventDefault();
            var formData = $(this).serialize();
            console.log("Form Data: ", formData); // Log form data sebelum dikirim
            
            $.ajax({
                url: $(this).attr('action'), // Sesuaikan dengan route Anda
                method: 'POST',
                data: formData,
                success: function (response) {
                    $('#new-progress-modal').modal('hide');
                    $('#progress-table').DataTable().ajax.reload();
                    alert('Progress berhasil ditambahkan!');
                     // Refresh halaman
                    location.reload(); // Melakukan refresh halaman
                },
                error: function (xhr) {
                    console.log("Error: ", xhr.responseText); // Log kesalahan
                    alert('Terjadi kesalahan: ' + xhr.responseText);
                }
            });
        });

        $(document).on('click', '.delete-progress-btn', function() {
            // Ambil nilai id dari atribut data-id
            var progressId = $(this).data('id');
            
            // Tampilkan konfirmasi sebelum menghapus
            if (confirm('Are you sure you want to delete this progress?')) {
                // Kirim permintaan AJAX untuk menghapus data
                $.ajax({
                    url: '/capex/' + progressId, // Sesuaikan URL endpoint dengan id
                    type: 'DELETE',
                    data: {
                        _token: '{{ csrf_token() }}', // Ambil token CSRF dari meta tag
                        flag: 'progress' // Kirim flag untuk menentukan apakah yang dihapus progress
                    },
                    success: function(response) {
                        alert(response.message); // Menampilkan pesan sukses
                        $('#progress-table').DataTable().ajax.reload(); // Reload DataTable
                    },
                    error: function(xhr) {
                        console.log("Error: ", xhr.responseText);
                        alert('Terjadi kesalahan saat menghapus data.');
                    }
                });
            }
        });
    });
</script>

    