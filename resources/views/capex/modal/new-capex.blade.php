<!-- Modal -->
<div class="modal fade" id="new-form" tabindex="-1" aria-labelledby="newFormLabel" aria-hidden="true">
    <div class="modal-dialog custom-modal modal-dialog-centered"> <!-- Ganti 'modal-lg' dengan 'modal-sm' atau 'modal-fullscreen' sesuai kebutuhan -->
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
                        <div class="col-md-3">
                            <label for="project_desc" class="form-label font-weight-bold">Project Desc</label>
                            <input type="text" class="form-control" id="project_desc" name="project_desc" style="text-align: center;" required>
                        </div>
                    
                        <div class="col-md-3">
                            <label for="wbs_capex" class="form-label font-weight-bold">WBS Capex</label>
                            <div class="dropdown">
                                <button class="btn bg-gradient-secondary dropdown-toggle" type="button" id="wbsCapexDropdown" data-bs-toggle="dropdown" aria-expanded="false">
                                    Select WBS Type
                                </button>
                                <ul class="dropdown-menu" aria-labelledby="wbsCapexDropdown">
                                    <li><a class="dropdown-item" href="#" data-value="Project">Project</a></li>
                                    <li><a class="dropdown-item" href="#" data-value="Non Project">Non-Project</a></li>
                                </ul>
                                <input type="hidden" id="wbs_capex" name="wbs_capex" required>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <label for="wbs_number" class="form-label font-weight-bold">WBS Number</label>
                            <input type="text" class="form-control" id="wbs_number" name="wbs_number" style="text-align: center;" required>
                        </div>
                        <div class="col-md-3">
                            <label for="cip_number" class="form-label font-weight-bold">CIP Number</label>
                            <input type="text" class="form-control" id="cip_number" name="cip_number" style="text-align: center;" required>
                        </div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-md-3">
                            <label for="remark" class="form-label font-weight-bold">Remark</label>
                            <input type="text" class="form-control" id="remark" name="remark" style="text-align: center;" required>
                        </div>
                        <div class="col-md-3">
                            <label for="request_number" class="form-label font-weight-bold">Request Number</label>
                            <input type="text" class="form-control" id="request_number" name="request_number" style="text-align: center;" required>
                        </div>
                        <div class="col-md-3">
                            <label for="requester" class="form-label font-weight-bold">Requester</label>
                            <input type="text" class="form-control" id="requester" name="requester" style="text-align: center;" required>
                        </div>
                        <div class="col-md-3">
                            <label for="capex_number" class="form-label font-weight-bold">Capex Number</label>
                            <input type="text" class="form-control" id="capex_number" name="capex_number" style="text-align: center;" required>
                        </div>
                    </div>
                    <div class="row mb-4">
                        <div class="col-md-4">
                            <label for="amount_budget" class="form-label font-weight-bold">Amount Budget (USD)</label>
                            <input type="text" class="form-control column-input new-capex" id="amount_budget" name="amount_budget" style="text-align: center;" required>
                        </div>
                        <div class="col-md-4 d-flex flex-column align-items-center">
                            <label for="status_capex" class="form-label font-weight-bold">Type Capex</label>
                            <div class="dropdown">
                                <button class="btn bg-gradient-secondary dropdown-toggle" type="button" id="statusDropdown" data-bs-toggle="dropdown" aria-expanded="false">
                                    Select Status
                                </button>
                                <ul class="dropdown-menu" aria-labelledby="statusDropdown">
                                    <li><a class="dropdown-item" href="#" data-value="Canceled">Canceled</a></li>
                                    <li><a class="dropdown-item" href="#" data-value="Close">Closed</a></li>
                                    <li><a class="dropdown-item" href="#" data-value="On Progress">On Progress</a></li>
                                    <li><a class="dropdown-item" href="#" data-value="To Opex">To Opex</a></li>
                                </ul>
                                <input type="hidden" id="status_capex" name="status_capex" style="text-align: center;" required>
                            </div>
                        </div>
                        <div class="col-md-4 d-flex flex-column align-items-center">
                            <label for="budget_type" class="form-label font-weight-bold">Status Budget</label>
                            <div class="dropdown">
                                <button class="btn bg-gradient-secondary dropdown-toggle" type="button" id="budgetTypeDropdown" data-bs-toggle="dropdown" aria-expanded="false">
                                    Select Status Budget
                                </button>
                                <ul class="dropdown-menu" aria-labelledby="budgetTypeDropdown">
                                    <li><a class="dropdown-item" href="#" data-value="Budgeted">Budgeted</a></li>
                                    <li><a class="dropdown-item" href="#" data-value="Unbudgeted">Unbudgeted</a></li>
                                </ul>
                                <input type="hidden" id="budget_type" name="budget_type" style="text-align: center;" required>
                            </div>
                        </div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-md-4">
                            <label for="startup" class="form-label font-weight-bold">Startup</label>
                            <input type="date" class="form-control" id="startup" name="startup" required>
                        </div>
                    
                        <div class="col-md-4">
                            <label for="expected_completed" class="form-label font-weight-bold">Expected Completed</label>
                            <input type="date" class="form-control" id="expected_completed" name="expected_completed" required>
                        </div>
                        <div class="col-md-4">
                            <label for="category" class="form-label font-weight-bold">Category</label>
                            <div class="dropdown">
                                <button class="btn bg-gradient-secondary dropdown-toggle" type="button" id="categoryDropdown" data-bs-toggle="dropdown" aria-expanded="false">
                                    Select Category
                                </button>
                                <ul class="dropdown-menu" aria-labelledby="categoryDropdown">
                                    <li><a class="dropdown-item" href="#" data-value="General Operation">General Operation</a></li>
                                    <li><a class="dropdown-item" href="#" data-value="IT">IT</a></li>
                                    <li><a class="dropdown-item" href="#" data-value="Environment">Environment</a></li>
                                    <li><a class="dropdown-item" href="#" data-value="Safety">Safety</a></li>
                                    <li><a class="dropdown-item" href="#" data-value="Improvement Plant efficiency">Improvement Plant efficiency</a></li>
                                    <li><a class="dropdown-item" href="#" data-value="Invesment">Invesment</a></li>
                                </ul>
                                <input type="hidden" id="category" name="category" style="text-align: center;" required>
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
            e.preventDefault(); // Mencegah tindakan default dari tombol

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
                startup: $('#startup').val(),
                expected_completed: $('#expected_completed').val(),
                wbs_number: $('#wbs_number').val(),
                cip_number: $('#cip_number').val(),
                category: $('#category').val(),
                flag: $('input[name="flag"]').val() // Ambil flag dari input hidden
            };

            // Cek apakah semua field yang required terisi
            if (!formData.project_desc || !formData.wbs_capex || !formData.request_number || !formData.requester || !formData.capex_number || !formData.amount_budget || !formData.status_capex || !formData.budget_type || !formData.startup || !formData.expected_completed || !formData.wbs_number || !formData.cip_number || !formData.category ) {
                Swal.fire({
                    title: 'Error!',
                    text: 'Silakan lengkapi semua input yang diperlukan.',
                    icon: 'error',
                    confirmButtonText: 'OK'
                });
                return; // Hentikan eksekusi jika ada field yang kosong
            }

            // Tampilkan konfirmasi sebelum mengirim data
            Swal.fire({
                title: 'Konfirmasi',
                text: 'Apakah Anda yakin ingin menambahkan CAPEX ini?',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Ya, tambahkan!',
                cancelButtonText: 'Tidak'
            }).then((result) => {
                if (result.isConfirmed) {
                    // Kirim data melalui AJAX jika pengguna menekan "Ya"
                    $.ajax({
                        url: "{{ route('capex.store') }}",  // Route store capex
                        method: 'POST',
                        data: formData,
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') // Tambahkan token CSRF
                        },
                        success: function (response) {
                            // Tampilkan pesan sukses dengan SweetAlert
                            Swal.fire({
                                title: 'Berhasil!',
                                text: 'Data Capex berhasil di upload. ',
                                icon: 'success',
                                showConfirmButton: false,
                                timer: 1000
                            }).then(() => {
                                $('#new-form').modal('hide'); // Menutup modal setelah berhasil
                                $('#capex-table').DataTable().ajax.reload(); // Reload DataTable
                            });
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
                                Swal.fire({
                                    title: 'Error!',
                                    text: 'Error: \n' + errorMessage,
                                    icon: 'error',
                                    confirmButtonText: 'OK'
                                });
                            } else {
                                Swal.fire({
                                    title: 'Error!',
                                    text: 'Error: ' + xhr.responseText,
                                    icon: 'error',
                                    confirmButtonText: 'OK'
                                });
                            }
                        }
                    });
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
        $('#categoryDropdown').on('click', function() {
            $('.dropdown-menu[aria-labelledby="categoryDropdown"] .dropdown-item').on('click', function() {
                var value = $(this).data('value');
                var text = $(this).text();
                $('#categoryDropdown').text(text); // Ubah teks tombol menjadi item yang dipilih
                $('#category').val(value); // Atur nilai input tersembunyi
            });
        });

        $('#new-form').on('show.bs.modal', function () {
            $(this).find('form')[0].reset(); // Mereset form di dalam modal
        });

    });
</script>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        const numberInputs = document.querySelectorAll('input.new-capex'); // Menggunakan kelas khusus untuk input update

        numberInputs.forEach(input => {
            input.addEventListener('input', function() {
                // Menghapus semua karakter yang bukan angka, koma, dan titik
                let value = this.value.replace(/[^0-9.,]/g, '');

                // Memisahkan bagian integer dan desimal
                let parts = value.split(',');
                let integerPart = parts[0].replace(/\./g, ''); 
                let decimalPart = parts[1] ? ',' + parts[1].slice(0, 2) : ''; 

                // Memformat bagian integer dengan pemisah ribuan
                let formattedInteger = parseInt(integerPart).toLocaleString('id-ID');

                // Menggabungkan bagian integer dan desimal
                this.value = formattedInteger + decimalPart;
            });

            input.addEventListener('blur', function() {
                // Format saat fokus hilang (blur)
                let value = this.value.replace(/\./g, '').replace(/,/g, '.'); 
                if (value) {
                    this.value = parseFloat(value).toString(); 
                }
            });
        });
    });
</script>


