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
                        <div class="col-md-3">
                            <label for="project_desc_edit" class="form-label font-weight-bold">Project Desc</label>
                            <input type="text" class="form-control" id="project_desc_edit" name="project_desc" style="text-align: center;" required>
                        </div>
                        <div class="col-md-3">
                            <label for="wbs_capex_edit" class="form-label font-weight-bold">WBS Capex</label>
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
                        <div class="col-md-3">
                            <label for="wbs_number_edit" class="form-label font-weight-bold">WBS Number</label>
                            <input type="text" class="form-control" id="wbs_number_edit" name="wbs_number" style="text-align: center;" required>
                        </div>
                        <div class="col-md-3">
                            <label for="cip_number_edit" class="form-label font-weight-bold">CIP Number</label>
                            <input type="text" class="form-control" id="cip_number_edit" name="cip_number" style="text-align: center;" required>
                        </div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-md-3">
                            <label for="remark_edit" class="form-label font-weight-bold">Remark</label>
                            <input type="text" class="form-control" id="remark_edit" name="remark" style="text-align: center;" required>
                        </div>
                        <div class="col-md-3">
                            <label for="request_number_edit" class="form-label font-weight-bold">Request Number</label>
                            <input type="text" class="form-control" id="request_number_edit" name="request_number" style="text-align: center;" required>
                        </div>
                        <div class="col-md-3">
                            <label for="requester_edit" class="form-label font-weight-bold">Requester</label>
                            <input type="text" class="form-control" id="requester_edit" name="requester" style="text-align: center;" required>
                        </div>
                        <div class="col-md-3">
                            <label for="capex_number_edit" class="form-label font-weight-bold">Capex Number</label>
                            <input type="text" class="form-control" id="capex_number_edit" name="capex_number" style="text-align: center;" required>
                        </div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-md-4">
                            <label for="amount_budget_edit" class="form-label font-weight-bold">Amount Budget (USD)</label>
                            <input type="text" class="form-control column-input edit-capex" id="amount_budget_edit" name="amount_budget" style="text-align: center;" required>
                        </div>
                        <div class="col-md-4 d-flex flex-column align-items-center">
                            <label for="status_capex_edit" class="form-label font-weight-bold">Type Capex</label>
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
                        <div class="col-md-4 d-flex flex-column align-items-center">
                            <label for="budget_type_edit" class="form-label font-weight-bold">Status Budget</label>
                            <div class="dropdown">
                                <button class="btn btn-secondary dropdown-toggle" type="button" id="budgetTypeDropdownEdit" data-bs-toggle="dropdown" aria-expanded="false">
                                    Select Status Budget
                                </button>
                                <ul class="dropdown-menu" aria-labelledby="budgetTypeDropdownEdit">
                                    <li><a class="dropdown-item" href="#" data-value="budgeted">Budgeted</a></li>
                                    <li><a class="dropdown-item" href="#" data-value="unbudgeted">Unbudgeted</a></li>
                                </ul>
                                <input type="hidden" id="budget_type_edit" name="budget_type" required>
                            </div>
                        </div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label for="startup_edit" class="form-label font-weight-bold">STARTUP</label>
                            <input type="month" class="form-control" id="startup_edit" name="startup" required>
                        </div>
                        <div class="col-md-6">
                            <label for="expected_completed_edit" class="form-label font-weight-bold">EXPECTED COMPLETED</label>
                            <input type="month" class="form-control" id="expected_completed_edit" name="expected_completed" required>
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
            var startup = $(this).data('startup');
            var expected_completed = $(this).data('expected_completed');
            var wbs_number = $(this).data('wbs_number');
            var cip_number = $(this).data('cip_number');

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
            $('#startup_edit').val(startup);
            $('#expected_completed_edit').val(expected_completed);
            $('#wbs_number_edit').val(wbs_number);
            $('#cip_number_edit').val(cip_number);

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
                    startup: $('#startup_edit').val(),
                    expected_completed: $('#expected_completed_edit').val(),
                    wbs_number: $('#wbs_number_edit').val(),
                    cip_number: $('#cip_number_edit').val(),
                    flag: 'update' // Menyertakan flag update
                }; // Mengambil semua data dalam form
                
                // Tampilkan konfirmasi sebelum mengirim data
                Swal.fire({
                    title: 'Konfirmasi',
                    text: 'Apakah Anda yakin ingin menyimpan perubahan ini?',
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Ya, simpan!',
                    cancelButtonText: 'Tidak'
                }).then((result) => {
                    if (result.isConfirmed) {
                        // Kirim data ke server menggunakan AJAX jika pengguna menekan "Ya"
                        $.ajax({
                            url: "{{ route('capex.store') }}", // Ganti dengan route yang sesuai
                            type: 'POST',
                            data: formData,
                            success: function(response) {
                                // Tindakan setelah berhasil menyimpan data
                                $('#edit-form').modal('hide'); // Sembunyikan modal
                                $('#capex-table').DataTable().ajax.reload(); // Reload DataTable
                                // Menampilkan pesan sukses
                                Swal.fire({
                                    title: 'Berhasil!',
                                    text: 'Data berhasil disimpan!',
                                    icon: 'success',
                                    confirmButtonText: 'OK'
                                });
                            },
                            error: function(xhr) {
                                // Tindakan jika terjadi kesalahan
                                var errors = xhr.responseJSON.errors;
                                // Menampilkan kesalahan ke pengguna
                                $.each(errors, function(key, value) {
                                    Swal.fire({
                                        title: 'Error!',
                                        text: value[0], // Tampilkan pesan kesalahan pertama
                                        icon: 'error',
                                        confirmButtonText: 'OK'
                                    });
                                });
                            }
                        });
                    }
                });
            });
    });
</script>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        const numberInputs = document.querySelectorAll('input.edit-capex'); // Menggunakan kelas khusus untuk input update
  
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