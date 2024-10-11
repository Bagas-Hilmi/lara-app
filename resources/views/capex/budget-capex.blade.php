<div class="modal fade" id="budget-modal" tabindex="-1" aria-labelledby="budgetModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header" style="background-color: #42bd37;">
                <h5 class="modal-title" id="budgetModalLabel" style="color: white;">Budget Details</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                
                <div class="mb-3">
                    <button type="button" class="btn btn-primary" style="background-color: #09170a; border-color: #09170a;" id="new-budget-btn" data-bs-toggle="modal" data-bs-target="#new-budget-modal" data-id="{{ $row->id_capex }}">
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
                                <th align="center">Amount Budget</th>
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

<script>
    $(document).ready(function() {
        // Event saat modal dibuka
        $('#budget-modal').on('shown.bs.modal', function (e) {
            var idCapex = $(e.relatedTarget).data('id'); // Ambil ID Capex dari data-id

            // Inisialisasi DataTables
            $('#budget-table').DataTable({
                processing: true,
                serverSide: true,
                destroy: true, // Agar inisialisasi ulang setiap kali modal dibuka
                ajax: {
                    url: '/capex/' + idCapex, // Menggunakan metode show dari Route::resource
                    type: 'GET'
                },
                columns: [
                    { data: 'action', name: 'action', orderable: false, searchable: false ,className: 'text-center'},
                    { data: 'id_capex_budget', name: 'id_capex_budget' ,className: 'text-center'},
                    { data: 'id_capex', name: 'id_capex' ,className: 'text-center'},
                    { data: 'description', name: 'description' ,className: 'text-center'},
                    { data: 'amount_budget', name: 'amount_budget' ,className: 'text-center'},
                    { data: 'budget_cos', name: 'budget_cos' ,className: 'text-center'},
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

            // Event saat modal ditutup, hapus tabel
            $('#budget-modal').on('hidden.bs.modal', function () {
                $('#budget-table').DataTable().clear().destroy(); // Membersihkan DataTable ketika modal ditutup
            });

    });

</script>
