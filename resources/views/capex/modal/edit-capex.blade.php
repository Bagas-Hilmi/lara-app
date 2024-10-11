<div class="modal fade" id="edit-form" tabindex="-1" aria-labelledby="editFormLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header" style="background-color: #42bd37;">
                <h5 class="modal-title" id="editFormLabel" style="color: white;">Edit Master Capex</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="capex-edit" action="{{ route('capex.store') }}" method="POST"> <!-- Tetap menggunakan route yang sama -->
                    <input type="hidden" name="flag" value="update"> 
                    <input type="hidden" id="id_capex_edit" name="id_capex" required>
                    @csrf 

                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label for="project_desc_edit" class="form-label">Project Desc</label>
                            <input type="text" class="form-control" id="project_desc_edit" name="project_desc" style="text-align: center;" required>
                        </div>
                        <div class="col-md-6">
                            <label for="wbs_capex_edit" class="form-label">WBS Capex</label>
                            <div class="dropdown">
                                <button class="btn btn-secondary dropdown-toggle" type="button" id="wbsCapexDropdownEdit" data-bs-toggle="dropdown" aria-expanded="false">
                                    Select WBS Type
                                </button>
                                <ul class="dropdown-menu" aria-labelledby="wbsCapexDropdownEdit">
                                    <li><a class="dropdown-item" href="#" data-value="project">Project</a></li>
                                    <li><a class="dropdown-item" href="#" data-value="non_project">Non-Project</a></li>
                                </ul>
                                <input type="hidden" id="wbs_capex_edit" name="wbs_capex" required>
                            </div>
                        </div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label for="remark_edit" class="form-label">Remark</label>
                            <input type="text" class="form-control" id="remark_edit" name="remark" style="text-align: center;" required>
                        </div>
                        <div class="col-md-6">
                            <label for="request_number_edit" class="form-label">Request Number</label>
                            <input type="text" class="form-control" id="request_number_edit" name="request_number" style="text-align: center;" required>
                        </div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label for="requester_edit" class="form-label">Requester</label>
                            <input type="text" class="form-control" id="requester_edit" name="requester" style="text-align: center;" required>
                        </div>
                        <div class="col-md-6">
                            <label for="capex_number_edit" class="form-label">Capex Number</label>
                            <input type="text" class="form-control" id="capex_number_edit" name="capex_number" style="text-align: center;" required>
                        </div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label for="amount_budget_edit" class="form-label">Amount Budget (USD)</label>
                            <input type="text" class="form-control" id="amount_budget_edit" name="amount_budget" style="text-align: center;" required>
                        </div>
                        <div class="col-md-6">
                            <label for="status_capex_edit" class="form-label">Status Capex</label>
                            <div class="dropdown">
                                <button class="btn btn-secondary dropdown-toggle" type="button" id="statusDropdownEdit" data-bs-toggle="dropdown" aria-expanded="false">
                                    Select Status
                                </button>
                                <ul class="dropdown-menu" aria-labelledby="statusDropdownEdit">
                                    <li><a class="dropdown-item" href="#" data-value="canceled">Canceled</a></li>
                                    <li><a class="dropdown-item" href="#" data-value="close">Close</a></li>
                                    <li><a class="dropdown-item" href="#" data-value="on_progress">On Progress</a></li>
                                    <li><a class="dropdown-item" href="#" data-value="to_opex">To Opex</a></li>
                                </ul>
                                <input type="hidden" id="status_capex_edit" name="status_capex" style="text-align: center;" required>
                            </div>
                        </div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label for="budget_type_edit" class="form-label">Budget Type</label>
                            <div class="dropdown">
                                <button class="btn btn-secondary dropdown-toggle" type="button" id="budgetTypeDropdownEdit" data-bs-toggle="dropdown" aria-expanded="false">
                                    Select Budget Type
                                </button>
                                <ul class="dropdown-menu" aria-labelledby="budgetTypeDropdownEdit">
                                    <li><a class="dropdown-item" href="#" data-value="budgeted">Budgeted</a></li>
                                    <li><a class="dropdown-item" href="#" data-value="unbudgeted">Unbudgeted</a></li>
                                </ul>
                                <input type="hidden" id="budget_type_edit" name="budget_type" required>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn bg-gradient-secondary" data-bs-dismiss="modal">Close</button>
                <button type="button" class="btn bg-gradient-success" id="save-edit-capex">Save Changes</button>
            </div>
        </div>
    </div>
</div>

<script>
    $(document).ready(function() {
        // Event handler untuk mengisi modal edit dengan data dari elemen yang diklik
        $(document).on('click', '#edit-button', function() {
            // Ambil data dari elemen yang diklik
            var id_capex = $(this).data('id');
            var project_desc = $(this).data('project_desc');
            var wbs_capex = $(this).data('wbs_capex');
            var remark = $(this).data('remark');
            var request_number = $(this).data('request_number');
            var requester = $(this).data('requester');
            var capex_number = $(this).data('capex_number');
            var amount_budget = $(this).data('amount_budget');
            var status_capex = $(this).data('status_capex');
            var budget_type = $(this).data('budget_type');

            // Isi data ke dalam modal
            $('#project_desc_edit').val(project_desc);
            $('#wbs_capex_edit').val(wbs_capex).change(); // Pastikan dropdown menampilkan pilihan yang benar
            $('#remark_edit').val(remark);
            $('#request_number_edit').val(request_number);
            $('#requester_edit').val(requester);
            $('#capex_number_edit').val(capex_number);
            $('#amount_budget_edit').val(amount_budget);
            $('#status_capex_edit').val(status_capex).change(); // Pastikan dropdown menampilkan pilihan yang benar
            $('#budget_type_edit').val(budget_type).change(); // Pa// Anda mungkin perlu memformat dropdown

            $('#id_capex_edit').val(id_capex); // Pastikan Anda memiliki input tersembunyi di modal Anda

            // Jika Anda menggunakan dropdown untuk wbs_capex, status_capex, dan budget_type, Anda bisa menambahkan logika untuk menandai pilihan yang tepat
            $('#wbsCapexDropdownEdit').text(wbs_capex.charAt(0).toUpperCase() + wbs_capex.slice(1).replace(/_/g, ' ')); // Ubah _ menjadi spasi dan huruf pertama menjadi kapital
            $('#statusDropdownEdit').text(status_capex.charAt(0).toUpperCase() + status_capex.slice(1).replace(/_/g, ' ')); // Ubah _ menjadi spasi dan huruf pertama menjadi kapital
            $('#budgetTypeDropdownEdit').text(budget_type === 'budgeted' ? 'Budgeted' : 'Unbudgeted');
        });

            // Event handler untuk menyimpan perubahan pada modal
            $('#save-edit-capex').click(function() {
            // Ambil data dari form
            var formData = {
            id_capex: $('#id_capex_edit').val(), // Mengambil id_capex dari input hidden
            project_desc: $('#project_desc_edit').val(),
            wbs_capex: $('#wbs_capex_edit').val(),
            remark: $('#remark_edit').val(),
            request_number: $('#request_number_edit').val(),
            requester: $('#requester_edit').val(),
            capex_number: $('#capex_number_edit').val(),
            amount_budget: $('#amount_budget_edit').val(),
            status_capex: $('#status_capex_edit').val(),
            budget_type: $('#budget_type_edit').val(),
            flag: 'update' // Menyertakan flag update
            }; // Mengambil semua data dalam form
            
            // Kirim data ke server menggunakan AJAX
            $.ajax({
                url: "{{ route('capex.store') }}", // Ganti dengan route yang sesuai
                type: 'POST',
                data: formData,
                success: function(response) {
                    // Tindakan setelah berhasil menyimpan data
                    $('#edit-form').modal('hide'); // Sembunyikan modal
                    location.reload(); // Reload halaman
                    // Atau Anda bisa menambahkan logika untuk menampilkan pesan sukses
                    alert('Data berhasil disimpan!');
                },
                error: function(xhr) {
                    // Tindakan jika terjadi kesalahan
                    var errors = xhr.responseJSON.errors;
                    // Menampilkan kesalahan ke pengguna
                    $.each(errors, function(key, value) {
                        alert(value[0]); // Tampilkan pesan kesalahan pertama
                    });
                }
            });
        });
    });

</script>