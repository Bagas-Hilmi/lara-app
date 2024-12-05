<div class="modal fade" id="budget-modal" tabindex="-1" aria-labelledby="budgetModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header" style="background-color: #42bd37;">
                <h5 class="modal-title" id="budgetModalLabel" style="color: white;">Budget Details</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
        
                <div class="mb-3">
                   <!-- Tombol NEW BUDGET -->
                    <button type="button" class="btn btn-primary view-budget-btn"
                        style="background-color: #09170a; border-color: #09170a;"
                        id="new-budget-btn"
                        data-bs-toggle="modal"
                        data-bs-target="#new-budget-modal">
                        <i class="fas fa-plus"></i> NEW BUDGET
                    </button>
                </div>
                <div class="table-responsive p-0">
                    <table id="budget-table" class="table table-striped nowrap rounded-table p-0" style="width:100%">
                        <thead>
                            <tr>
                                <th align="center">Action</th>
                                <th align="center">Description</th>
                                <th align="center">Budget Cos</th>
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

        @include('capex.modal.budget.new-budget')
        @include('capex.modal.budget.edit-budget')

<script>
    $(document).ready(function() {
        // Event saat modal budget-modal dibuka
        $('#budget-modal').on('shown.bs.modal', function (e) {
            var idCapex = $(e.relatedTarget).data('id'); // Ambil ID Capex dari data-id
            $('#new-budget-btn').data('id', idCapex); // Set data-id untuk tombol NEW BUDGET
    
            // Inisialisasi DataTables
            $('#budget-table').DataTable({
                processing: true,
                serverSide: true,
                destroy: true, // Agar inisialisasi ulang setiap kali modal dibuka
                order: [[1, 'desc']],
                ajax: {
                    url: '/capex/' + idCapex, // Mengarah ke function show dengan id_capex
                    type: 'GET',
                    data: { flag: 'budget' }
                },
                columns: [
                    { data: 'action', name: 'action', orderable: false, searchable: false, className: 'text-center'},
                    { data: 'description', name: 'description', className: 'text-center'},
                    { data: 'budget_cos', name: 'budget_cos', className: 'text-center',
                    render: function(data) {
                        return data 
                            ? '<div style="text-align: right;">' + data.toLocaleString() + '</div>' 
                            : '';
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
        $('#new-budget-modal').on('show.bs.modal', function () {
            var idCapex = $('#new-budget-btn').data('id'); // Ambil id_capex dari tombol NEW BUDGET
            console.log("ID Capex: ", idCapex); // Log ID capex
            $('#new_budget_capex_id').val(idCapex); // Set nilai ke input tersembunyi
        });

        $(document).on('click', '.delete-budget-btn', function() {
            var budgetId = $(this).data('id');
            
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
                    url: '/capex/' + budgetId, // Sesuaikan URL endpoint dengan id
                    type: 'DELETE',
                    data: {
                        _token: '{{ csrf_token() }}', // Ambil token CSRF dari meta tag
                        flag: 'budget' // Kirim flag untuk menentukan apakah yang dihapus budget
                    },
                    success: function(response) {
                     if (response.success) {
                             // SweetAlert success notification
                                Swal.fire(
                                'Deleted!',
                                'Budget has been deleted.',
                                'success'
                             );

                            // Reload the DataTable
                            $('#capex-table').DataTable().ajax.reload();
                            $('#budget-table').DataTable().ajax.reload();
                        } else {
                                Swal.fire(
                                'Failed!',
                                'Failed to delete budget!',
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
    #budget-table tbody td {
       padding: 8px; /* Padding untuk sel */
       border-bottom: 1px solid #dee2e6; /* Garis bawah sel */
       color: #2c2626;
   }

   /* Hover effect untuk baris tabel */
   #budget-table tbody tr:hover {
       background-color: rgba(0, 123, 255, 0.1); /* Warna latar belakang saat hover */
   }
   #budget-table th, #budget-table td {
       padding: 8px;
       text-align: center;
   }
</style>