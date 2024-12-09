<div class="modal fade" id="porelease-modal" tabindex="-1" aria-labelledby="poreleaseModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header" style="background-color: #42bd37;">
                <h5 class="modal-title" id="poreleaseModalLabel" style="color: white;">PO Release Details</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
        
                <div class="mb-3">
                   <!-- Tombol NEW PO RELEASE -->
                    <button type="button" class="btn btn-primary view-porelease-btn"
                        style="background-color: #09170a; border-color: #09170a;"
                        id="new-porelease-btn"
                        data-bs-toggle="modal"
                        data-bs-target="#new-porelease-modal">
                        <i class="fas fa-plus"></i> NEW PO RELEASE
                    </button>
                </div>
                <div class="table-responsive p-0">
                    <table id="porelease-table" class="table table-striped nowrap rounded-table p-0" style="width:100%">
                        <thead style="background-color: #3cb210; color: white;">
                            <tr>
                                <th align="center">Action</th>
                                <th align="center">Description</th>
                                <th align="center">PO Release (USD)</th>
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

@include('capex.modal.porelease.new-porelease')
@include('capex.modal.porelease.edit-porelease')

<script>
     $(document).ready(function() {
        // Event saat modal porelease-modal dibuka
        $('#porelease-modal').on('shown.bs.modal', function (e) {
            var idCapex = $(e.relatedTarget).data('id'); // Ambil ID Capex dari data-id
            $('#new-porelease-btn').data('id', idCapex); // Set data-id untuk tombol NEW BUDGET


            $('#porelease-table').DataTable({
                processing: true,
                serverSide: true,
                destroy: true, // Agar inisialisasi ulang setiap kali modal dibuka
                order: [[3, 'desc']],
                ajax: {
                    url: '/capex/' + idCapex, // Mengarah ke function show dengan id_capex
                    type: 'GET',
                    data: { flag: 'porelease' }
                },
                columns: [
                    { data: 'action', name: 'action', orderable: false, searchable: false, className: 'text-center'},
                    { data: 'description', name: 'description', className: 'text-center'},
                    { data: 'PO_release', name: 'PO_release', className: 'text-center',
                    render: function(data, type) {
                                if (type === 'display') {
                                    return '<div>' + parseFloat(data)
                                        .toString().replace(/\B(?=(\d{3})+(?!\d))/g, ".") + '</div>';
                                }
                                return data;
                    }},
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

        // Event saat modal new-budget-modal dibuka
        $('#new-porelease-modal').on('show.bs.modal', function () {
            var idCapex = $('#new-porelease-btn').data('id'); // Ambil id_capex dari tombol NEW BUDGET
            console.log("ID Capex: ", idCapex); // Log ID capex
            $('#new_porelease_capex_id').val(idCapex); // Set nilai ke input tersembunyi
        });

        $(document).on('click', '.delete-porelease-btn', function() {
            // Ambil nilai id dari atribut data-id
            var poreleaseId = $(this).data('id');
            
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
                        url: '/capex/' + poreleaseId, // Sesuaikan URL endpoint dengan id
                        type: 'DELETE',
                        data: {
                            _token: '{{ csrf_token() }}',
                            flag: 'porelease' // Kirim flag untuk menentukan apakah yang dihapus porelease
                        },
                        success: function(response) {
                            if (response.success) {
                                // SweetAlert success notification
                                Swal.fire(
                                    'Deleted!',
                                    'PO Release has been deleted.',
                                    'success'
                                );

                                // Reload the DataTable
                                $('#capex-table').DataTable().ajax.reload();
                                $('#porelease-table').DataTable().ajax.reload(); // Reload DataTable

                            } else {
                                Swal.fire(
                                    'Failed!',
                                    'Failed to delete PO Release!',
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

<style>
    /* Gaya untuk sel tabel */
    #porelease-table tbody td {
       padding: 8px; /* Padding untuk sel */
       border-bottom: 1px solid #dee2e6; /* Garis bawah sel */
       color: #2c2626;
   }

   /* Hover effect untuk baris tabel */
   #porelease-table tbody tr:hover {
       background-color: rgba(0, 123, 255, 0.1); /* Warna latar belakang saat hover */
   }
   #porelease-table th, #porelease-table td {
       padding: 8px;
       text-align: center;
   }
</style>