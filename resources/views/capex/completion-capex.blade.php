<div class="modal fade" id="completion-modal" tabindex="-1" aria-labelledby="completionModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-md modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header" style="background-color: #42bd37;">
                <h5 class="modal-title" id="completionModalLabel" style="color: white;">Revise Completion Date</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">

                <div class="mb-3">
                    <!-- Tombol untuk New Progress -->   
                    <button type="button" class="btn btn-primary view-completion-btn"
                        style="background-color: #09170a; border-color: #09170a;"
                        id="new-completion-btn"
                        data-bs-toggle="modal"
                        data-bs-target="#new-completion-modal">
                        <i class="fas fa-plus"></i> NEW REV COMPLETION DATE
                    </button>
                </div>
                <div class="table-responsive p-0">
                    <table id="completion-table" class="table table-striped nowrap rounded-table p-0" style="width:100%">
                        <thead style="background-color: #3cb210; color: white;">
                            <tr>
                                <th align="center">Action</th>
                                <th align="center">Date</th>
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
    @include('capex.modal.completion.new-completion')
    @include('capex.modal.completion.edit-completion')

<script>
     $(document).ready(function() {
        // Event saat modal dibuka
        $('#completion-modal').on('shown.bs.modal', function (e) {
            var idCapex = $(e.relatedTarget).data('id'); // Ambil ID Capex dari data-id
            $('#new-completion-btn').data('id', idCapex); // Set data-id untuk tombol NEW BUDGET

            // Inisialisasi DataTables
            $('#completion-table').DataTable({
                processing: true,
                serverSide: true,
                destroy: true, // Agar inisialisasi ulang setiap kali modal dibuka
                order: [[1, 'desc']],
                ajax: {
                    url: '/capex/' + idCapex, // Menggunakan metode show dari Route::resource
                    type: 'GET',
                    data: {
                        flag: 'completion' // Kirim flag untuk identifikasi request
                    },
                },
                columns: [
                    { data: 'action', name: 'action', orderable: false, searchable: false, className: 'text-center' },
                    { data: 'date', name: 'date', className: 'text-center' },
                    {
                        data: 'created_at',
                        name: 'created_at',
                        className: 'text-center',
                        render: function(data) {
                            return moment(data).format('YYYY-MM-DD'); // Format tanggal
                        }
                    },
                    {
                        data: 'updated_at',
                        name: 'updated_at',
                        className: 'text-center',
                        render: function(data) {
                            return moment(data).format('YYYY-MM-DD'); // Format tanggal
                        }
                    }
                ]
            });
        });

        $('#new-completion-modal').on('show.bs.modal', function () {
            var idCapex = $('#new-completion-btn').data('id'); 
            console.log("ID Capex: ", idCapex); 
            $('#new_completion_capex_id').val(idCapex); 
        });

        $(document).on('click', '.delete-completion-btn', function() {
            // Ambil nilai id dari atribut data-id
            var completionId = $(this).data('id');
            
            // Tampilkan konfirmasi sebelum menghapus
            Swal.fire({
                title: 'Konfirmasi Hapus?',
                text: "Apakah Anda yakin ingin menghapus item ini?",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'OK',
                cancelButtonText: 'Batal'
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        url: '/capex/' + completionId,
                        type: 'DELETE',
                        data: {
                            _token: '{{ csrf_token() }}',
                            flag: 'completion'
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
                                $('#completion-table').DataTable().ajax.reload();
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