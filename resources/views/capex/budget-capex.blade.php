<div class="modal fade" id="budget-modal" tabindex="-1" aria-labelledby="budgetModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl modal-dialog-centered">
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
                                <th align="center">ID Capex Budget</th>
                                <th align="center">ID Capex</th>
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

        @include('capex.modal.new-budget')
        @include('capex.modal.edit-budget')

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
                    { data: 'id_capex_budget', name: 'id_capex_budget', className: 'text-center'},
                    { data: 'id_capex', name: 'id_capex', className: 'text-center'},
                    { data: 'description', name: 'description', className: 'text-center'},
                    { data: 'budget_cos', name: 'budget_cos', className: 'text-center'},
                    {
                        data: 'created_at',
                        name: 'created_at',
                        className: 'text-center',
                        render: function(data) {
                            return moment(data).format('YYYY-MM-DD HH:mm:ss'); 
                        }
                    },
                    {
                        data: 'updated_at',
                        name: 'updated_at',
                        className: 'text-center',
                        render: function(data) {
                            return moment(data).format('YYYY-MM-DD HH:mm:ss'); 
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
        // Tangani pengiriman form untuk menambahkan budget
        $('#new-budget-form').on('submit', function (e) {
            e.preventDefault();
            var formData = $(this).serialize();
            console.log("Form Data: ", formData); // Log form data sebelum dikirim
            
            $.ajax({
                url: $(this).attr('action'), // Sesuaikan dengan route Anda
                method: 'POST',
                data: formData,
                success: function (response) {
                    $('#new-budget-modal').modal('hide');
                    $('#budget-table').DataTable().ajax.reload();
                    alert('Budget berhasil ditambahkan!');
                     // Refresh halaman
                    location.reload(); // Melakukan refresh halaman
                },
                error: function (xhr) {
                    console.log("Error: ", xhr.responseText); // Log kesalahan
                    alert('Terjadi kesalahan: ' + xhr.responseText);
                }
            });
        });
        $(document).on('click', '.delete-budget-btn', function() {
            // Ambil nilai id dari atribut data-id
            var budgetId = $(this).data('id');
            
            // Tampilkan konfirmasi sebelum menghapus
            if (confirm('Are you sure you want to delete this budget?')) {
                // Kirim permintaan AJAX untuk menghapus data
                $.ajax({
                    url: '/capex/' + budgetId, // Sesuaikan URL endpoint dengan id
                    type: 'DELETE',
                    data: {
                        _token: '{{ csrf_token() }}', // Ambil token CSRF dari meta tag
                        flag: 'budget' // Kirim flag untuk menentukan apakah yang dihapus budget
                    },
                    success: function(response) {
                        alert(response.message); // Menampilkan pesan sukses
                        $('#budget-table').DataTable().ajax.reload(); // Reload DataTable
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
