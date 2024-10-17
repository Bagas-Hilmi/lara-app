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
                            return moment(data).format('YYYY-MM-DD');
                        }
                    },
                    {
                        data: 'updated_at',
                        name: 'updated_at',
                        className: 'text-center',
                        render: function(data) {
                            return moment(data).format('YYYY-MM-DD');
                        }
                    }
                ]
            });
        });

        $('#new-progress-modal').on('show.bs.modal', function () {
            var idCapex = $('#new-progress-btn').data('id'); 
            console.log("ID Capex: ", idCapex); 
            $('#new_progress_capex_id').val(idCapex); 
        });
        
        $(document).on('click', '.delete-progress-btn', function() {
            // Ambil nilai id dari atribut data-id
            var progressId = $(this).data('id');
            
            // Tampilkan konfirmasi sebelum menghapus
            Swal.fire({
                title: 'Konfirmasi Hapus?',
                text: "Apakah Anda yakin ingin menghapus item ini?",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Ya, hapus!',
                cancelButtonText: 'Batal'
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        url: '/capex/' + progressId,
                        type: 'DELETE',
                        data: {
                            _token: '{{ csrf_token() }}',
                            flag: 'progress'
                        },
                        success: function(response) {
                            if (response.success) {
                                // SweetAlert success notification
                                Swal.fire(
                                    'Deleted!',
                                    'Progress has been deleted.',
                                    'success'
                                );

                                // Reload the DataTable
                                $('#progress-table').DataTable().ajax.reload();
                            } else {
                                Swal.fire(
                                    'Failed!',
                                    'Failed to delete progress!',
                                    'error'
                                );
                            }
                        },
                        error: function(xhr) {
                            Swal.fire(
                                'Error!',
                                'Something went wrong! Please try again.',
                                'error'
                            );
                        }
                    });
                }
            });
        });
    });
</script>



