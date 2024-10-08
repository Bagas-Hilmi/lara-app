<!-- Modal -->
<div class="modal fade" id="new-form" tabindex="-1" aria-labelledby="newFormLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered"> <!-- Ganti 'modal-lg' dengan 'modal-sm' atau 'modal-fullscreen' sesuai kebutuhan -->
        <div class="modal-content">
            <div class="modal-header" style="background-color: #42bd37;">
                <h5 class="modal-title" id="newFormLabel" style="color: white;">New Master Capex</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="capex-form" action="{{ route('capex.store') }}" method="POST"> <!-- Ganti dengan route yang sesuai -->
                    <input type="hidden" name="flag" value="add"> 
                    @csrf 

                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label for="project_desc" class="form-label">Project Desc</label>
                            <input type="text" class="form-control" id="project_desc" name="project_desc" style="text-align: center;" required>
                        </div>
                        <div class="col-md-6">
                            <label for="wbs_capex" class="form-label">WBS Capex</label>
                            <div class="dropdown">
                                <button class="btn btn-secondary dropdown-toggle" type="button" id="wbsCapexDropdown" data-bs-toggle="dropdown" aria-expanded="false">
                                    Select WBS Type
                                </button>
                                <ul class="dropdown-menu" aria-labelledby="wbsCapexDropdown">
                                    <li><a class="dropdown-item" href="#" data-value="project">Project</a></li>
                                    <li><a class="dropdown-item" href="#" data-value="non_project">Non-Project</a></li>
                                </ul>
                                <input type="hidden" id="wbs_capex" name="wbs_capex" required>
                            </div>
                        </div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label for="remark" class="form-label">Remark</label>
                            <input type="text" class="form-control" id="remark" name="remark" style="text-align: center;" required>
                        </div>
                        <div class="col-md-6">
                            <label for="request_number" class="form-label">Request Number</label>
                            <input type="text" class="form-control" id="request_number" name="request_number" style="text-align: center;" required>
                        </div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label for="requester" class="form-label">Requester</label>
                            <input type="text" class="form-control" id="requester" name="requester" style="text-align: center;" required>
                        </div>
                        <div class="col-md-6">
                            <label for="capex_number" class="form-label">Capex Number</label>
                            <input type="text" class="form-control" id="capex_number" name="capex_number" style="text-align: center;" required>
                        </div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label for="amount_budget" class="form-label">Amount Budget (USD)</label>
                            <input type="text" class="form-control" id="amount_budget" name="amount_budget" style="text-align: center;" required>
                        </div>
                        <div class="col-md-6">
                            <label for="status_capex" class="form-label">Status Capex</label>
                            <div class="dropdown">
                                <button class="btn btn-secondary dropdown-toggle" type="button" id="statusDropdown" data-bs-toggle="dropdown" aria-expanded="false">
                                    Select Status
                                </button>
                                <ul class="dropdown-menu" aria-labelledby="statusDropdown">
                                    <li><a class="dropdown-item" href="#" data-value="canceled">Canceled</a></li>
                                    <li><a class="dropdown-item" href="#" data-value="close">Close</a></li>
                                    <li><a class="dropdown-item" href="#" data-value="on_progress">On Progress</a></li>
                                    <li><a class="dropdown-item" href="#" data-value="to_opex">To Opex</a></li>
                                </ul>
                                <input type="hidden" id="status_capex" name="status_capex" style="text-align: center;" required>
                            </div>
                        </div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label for="budget_type" class="form-label">Budget Type</label>
                            <div class="dropdown">
                                <button class="btn btn-secondary dropdown-toggle" type="button" id="budgetTypeDropdown" data-bs-toggle="dropdown" aria-expanded="false">
                                    Select Budget Type
                                </button>
                                <ul class="dropdown-menu" aria-labelledby="budgetTypeDropdown">
                                    <li><a class="dropdown-item" href="#" data-value="budgeted">Budgeted</a></li>
                                    <li><a class="dropdown-item" href="#" data-value="unbudgeted">Unbudgeted</a></li>
                                </ul>
                                <input type="hidden" id="budget_type" name="budget_type" style="text-align: center;" required>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn bg-gradient-secondary" data-bs-dismiss="modal">Close</button>
                <button type="button" class="btn bg-gradient-success" id="save-capex">Save</button>
            </div>
        </div>
    </div>
</div>


<script>
    $(document).ready(function () {
        // Mengatur event listener untuk tombol simpan
        $('#save-capex').on('click', function (e) {
            e.preventDefault();

            // Ambil data dari form
            var formData = {
                project_desc: $('#project_desc').val(),
                wbs_capex: $('#wbs_capex').val(), // Pastikan ini ID yang benar
                remark: $('#remark').val(),
                request_number: $('#request_number').val(),
                requester: $('#requester').val(),
                capex_number: $('#capex_number').val(),
                amount_budget: $('#amount_budget').val(),
                status_capex: $('#status_capex').val(),
                budget_type: $('#budget_type').val(),
                flag: $('input[name="flag"]').val() // Ambil flag dari input hidden
            };

            // Kirim data melalui AJAX
            $.ajax({
                url: "{{ route('capex.store') }}",  // Route store capex
                method: 'POST',
                data: formData,
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') // Tambahkan token CSRF
                },
                success: function (response) {
                    // Tampilkan pesan sukses
                    alert(response.success);
                    $('#new-form').modal('hide'); // Menutup modal setelah berhasil
                    location.reload(); // Reload halaman setelah menutup modal
                },
                error: function (xhr) {
                    // Tampilkan error jika ada
                    if (xhr.responseJSON && xhr.responseJSON.errors) {
                        var errors = xhr.responseJSON.errors;
                        var errorMessage = '';
                        for (var key in errors) {
                            if (errors.hasOwnProperty(key)) {
                                errorMessage += errors[key].join(', ') + '\n'; // Menggabungkan pesan error
                            }
                        }
                        alert('Error: \n' + errorMessage);
                    } else {
                        alert('Error: ' + xhr.responseText);
                    }
                }
            });
        });

        // Mengatur nilai dropdown ke input tersembunyi
        $('.dropdown-item').on('click', function () {
            var value = $(this).data('value');
            var text = $(this).text();
            $(this).closest('.dropdown').find('input[type=hidden]').val(value);
            $(this).closest('.dropdown').find('.dropdown-toggle').text(text);
        });
    });

    // Mengatur event listener untuk dropdown
    $(document).ready(function() {
        // Handle dropdown item click for WBS Capex
        $('#wbsCapexDropdown').on('click', function() {
            $('.dropdown-menu[aria-labelledby="wbsCapexDropdown"] .dropdown-item').on('click', function() {
                var value = $(this).data('value');
                var text = $(this).text();
                $('#wbsCapexDropdown').text(text); // Ubah teks tombol menjadi item yang dipilih
                $('#wbs_capex').val(value); // Atur nilai input tersembunyi
            });
        });

        // Handle dropdown item click for status
        $('#statusDropdown').on('click', function() {
            $('.dropdown-menu[aria-labelledby="statusDropdown"] .dropdown-item').on('click', function() {
                var value = $(this).data('value');
                var text = $(this).text();
                $('#statusDropdown').text(text); // Ubah teks tombol menjadi item yang dipilih
                $('#status_capex').val(value); // Atur nilai input tersembunyi
            });
        });

        // Handle dropdown item click for budget type
        $('#budgetTypeDropdown').on('click', function() {
            $('.dropdown-menu[aria-labelledby="budgetTypeDropdown"] .dropdown-item').on('click', function() {
                var value = $(this).data('value');
                var text = $(this).text();
                $('#budgetTypeDropdown').text(text); // Ubah teks tombol menjadi item yang dipilih
                $('#budget_type').val(value); // Atur nilai input tersembunyi
            });
        });
    });
</script>


