<div class="modal fade" id="edit-form" tabindex="-1" aria-labelledby="editFormLabel" aria-hidden="true">
    <div class="modal-dialog custom-modal modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header" style="background-color: #42bd37;">
                <h5 class="modal-title" id="editFormLabel" style="color: white;">Edit Master Capex</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="capex-edit" action="{{ route('capex.store') }}" method="POST" enctype="multipart/form-data">
                    <!-- Tetap menggunakan route yang sama -->
                    <input type="hidden" name="flag" value="update">
                    <input type="hidden" id="id_capex_edit" name="id_capex" required>
                    @csrf

                    <div class="row mb-3">
                        <div class="col-md-3">
                            <label for="project_desc_edit" class="form-label font-weight-bold">Project Desc</label>
                            <input type="text" class="form-control" id="project_desc_edit" name="project_desc"
                                style="text-align: center;" required>
                        </div>
                        <div class="col-md-3">
                            <label for="wbs_capex_edit" class="form-label font-weight-bold">WBS Capex</label>
                            <div class="dropdown">
                                <button class="btn bg-gradient-secondary dropdown-toggle" type="button"
                                    id="wbsCapexDropdownEdit" data-bs-toggle="dropdown" aria-expanded="false">
                                    Select WBS Type
                                </button>
                                <ul class="dropdown-menu" aria-labelledby="wbsCapexDropdownEdit">
                                    <li><a class="dropdown-item" href="#" data-value="Project">Project</a></li>
                                    <li><a class="dropdown-item" href="#" data-value="Non Project">Non-Project</a>
                                    </li>
                                </ul>
                                <input type="hidden" id="wbs_capex_edit" name="wbs_capex" required>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <label for="wbs_number_edit" class="form-label font-weight-bold">WBS Number</label>
                            <input type="text" class="form-control" id="wbs_number_edit" name="wbs_number"
                                style="text-align: center;" required>
                        </div>
                        <div class="col-md-3">
                            <label for="cip_number_edit" class="form-label font-weight-bold">CIP Number</label>
                            <input type="text" class="form-control" id="cip_number_edit" name="cip_number"
                                style="text-align: center;" required>
                        </div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-md-3">
                            <label for="remark_edit" class="form-label font-weight-bold">Remark</label>
                            <input type="text" class="form-control" id="remark_edit" name="remark"
                                style="text-align: center;" required>
                        </div>
                        <div class="col-md-3">
                            <label for="request_number_edit" class="form-label font-weight-bold">Request Number</label>
                            <input type="text" class="form-control" id="request_number_edit" name="request_number"
                                style="text-align: center;" required>
                        </div>
                        <div class="col-md-3">
                            <label for="requester_edit" class="form-label font-weight-bold">Requester</label>
                            <input type="text" class="form-control" id="requester_edit" name="requester"
                                style="text-align: center;" required>
                        </div>
                        <div class="col-md-3">
                            <label for="capex_number_edit" class="form-label font-weight-bold">Capex Number</label>
                            <input type="text" class="form-control" id="capex_number_edit" name="capex_number"
                                style="text-align: center;" required>
                        </div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-md-3">
                            <label for="amount_budget_edit" class="form-label font-weight-bold">Amount Budget
                                (USD)</label>
                            <input type="text" class="form-control column-input edit-capex"
                                id="amount_budget_edit" name="amount_budget" style="text-align: center;" required>
                            <input type="hidden" id="amount_budget_edit" name="amount_budget">
                        </div>

                        <div class="col-md-3">
                            <label for="status_capex_edit" class="form-label font-weight-bold">Type Capex</label>
                            <div class="dropdown">
                                <button class="btn bg-gradient-secondary dropdown-toggle" type="button"
                                    id="statusDropdownEdit" data-bs-toggle="dropdown" aria-expanded="false">
                                    Select Status
                                </button>
                                <ul class="dropdown-menu" aria-labelledby="statusDropdownEdit">
                                    <li><a class="dropdown-item" href="#" data-value="Canceled">Canceled</a></li>
                                    <li><a class="dropdown-item" href="#" data-value="Close">Close</a></li>
                                    <li><a class="dropdown-item" href="#" data-value="On Progress">OnProgress</a></li>
                                    <li><a class="dropdown-item" href="#" data-value="To Opex">To Opex</a></li>
                                    <li><a class="dropdown-item" href="#" data-value="Waiting Approval">WaitingApproval</a></li>
                                </ul>
                                <input type="hidden" id="status_capex_edit" name="status_capex"
                                    style="text-align: center;" required>
                            </div>
                        </div>

                        <div class="col-md-3 ">
                            <label for="budget_type_edit" class="form-label font-weight-bold">Status Budget</label>
                            <div class="dropdown">
                                <button class="btn bg-gradient-secondary dropdown-toggle" type="button"
                                    id="budgetTypeDropdownEdit" data-bs-toggle="dropdown" aria-expanded="false">
                                    Select Status Budget
                                </button>
                                <ul class="dropdown-menu" aria-labelledby="budgetTypeDropdownEdit">
                                    <li><a class="dropdown-item" href="#" data-value="Budgeted">Budgeted</a>
                                    </li>
                                    <li><a class="dropdown-item" href="#"
                                            data-value="Unbudgeted">Unbudgeted</a></li>
                                </ul>
                                <input type="hidden" id="budget_type_edit" name="budget_type" required>
                            </div>
                        </div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-md-4">
                            <label for="startup_edit" class="form-label font-weight-bold">STARTUP</label>
                            <input type="date" class="form-control" id="startup_edit" name="startup" required>
                        </div>
                        <div class="col-md-4">
                            <label for="expected_completed_edit" class="form-label font-weight-bold">EXPECTED
                                COMPLETED</label>
                            <input type="date" class="form-control" id="expected_completed_edit"
                                name="expected_completed" required>
                        </div>
                        <div class="col-md-4">
                            <label for="category_edit" class="form-label font-weight-bold">Category</label>
                            <div class="dropdown">
                                <button class="btn bg-gradient-secondary dropdown-toggle" type="button"
                                    id="categoryDropdownEdit" data-bs-toggle="dropdown" aria-expanded="false">
                                    Select Category
                                </button>
                                <ul class="dropdown-menu" aria-labelledby="categoryDropdownEdit">
                                    <li><a class="dropdown-item" href="#"
                                            data-value="General Operation">General Operation</a></li>
                                    <li><a class="dropdown-item" href="#" data-value="IT">IT</a></li>
                                    <li><a class="dropdown-item" href="#"
                                            data-value="Environment">Environment</a></li>
                                    <li><a class="dropdown-item" href="#" data-value="Safety">Safety</a></li>
                                    <li><a class="dropdown-item" href="#"
                                            data-value="Improvement Plant efficiency">Improvement Plant efficiency</a>
                                    </li>
                                    <li><a class="dropdown-item" href="#" data-value="Invesment">Invesment</a>
                                    </li>
                                </ul>
                                <input type="hidden" id="category_edit" name="category" style="text-align: center;"
                                    required>
                            </div>
                        </div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-md-3" id="capdateContainer">
                            <label for="capdate_edit" class="form-label font-weight-bold">Capitalized Date</label>
                            <input type="date" class="form-control" id="capdate_edit" name="capdate"
                                style="text-align: center;" required>
                        </div>
                        <div class="col-md-3" id="capdocContainer">
                            <label for="capdate_edit" class="form-label font-weight-bold">Capitalized Doc</label>
                            <input type="text" class="form-control" id="capdoc_edit" name="capdoc"
                                style="text-align: center;" required>
                        </div>

                        <div class="col-md-3" id="fileUploadContainer" style="display: none;">
                            <label class="form-label font-weight-bold">Upload PDF</label>
                            <input type="file" class="form-control" name="file_pdf" accept="application/pdf"
                                required>
                            <label class="form-label">Allowed file : PDF</label>
                        </div>

                        <div class="col-md-12" id="noassetContainer">
                            <div class="row mt-3">
                                <div class="col-md-3">
                                <!-- Dropdown Class Name -->
                                    <label for="class_name" class="form-label font-weight-bold">Pilih Class Name</label>
                                    <select class="form-control" id="class_name" style="width: 100%;">
                                        <option value="" disabled selected>Pilih Class Name</option>
                                        <option value="10100">Z10100 - Land Rights</option>
                                        <option value="10200">Z10200 - Land Improvements</option>
                                        <option value="10300">Z10300 - Building - Office</option>
                                        <option value="10301">Z10301 - Building - Mess</option>
                                        <option value="10302">Z10302 - Building - Tank Farm</option>
                                        <option value="10303">Z10303 - Building - Plant</option>
                                        <option value="10400">Z10400 - Equipment - F.Acid</option>
                                        <option value="10401">Z10401 - Equipment - F.Alcohol</option>
                                        <option value="10402">Z10402 - Equipment - Utility</option>
                                        <option value="10403">Z10403 - Equipment - Lab</option>
                                        <option value="10404">Z10404 - Equipment - Office</option>
                                        <option value="10405">Z10405 - Equipment - Others</option>
                                        <option value="10406">Z10406 - Equipment - MPR</option>
                                        <option value="10407">Z10407 - Equipment - UFA</option>
                                        <option value="10500">Z10500 - Car & MC (100%)</option>
                                        <option value="10501">Z10501 - Car & MC (50%)</option>
                                    </select>
                                </div>

                                <!-- Rentang Angka -->
                            
                                <div class="col-md-3">
                                    <label for="start_number" class="form-label font-weight-bold">Nomor Awal</label>
                                    <input type="number" class="form-control" id="start_number">
                                </div>
                                <div class="col-md-3">
                                    <label for="end_number" class="form-label font-weight-bold">Nomor Akhir</label>
                                    <input type="number" class="form-control" id="end_number">
                                </div>
                                <div class="col-md-3">
                                    <button type="button" class="btn btn-primary mt-3" id="generateAssets">Generate</button>
                                </div>
                            </div>
                        
                            <div class="mt-4">
                                <label for="generated_assets" class="form-label font-weight-bold">Generated No Asset</label>
                                <textarea class="form-control" id="generated_assets"></textarea>
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
            var startup = $(this).data('startup');
            var expected_completed = $(this).data('expected_completed');
            var wbs_number = $(this).data('wbs_number');
            var cip_number = $(this).data('cip_number');
            var category = $(this).data('category');
            var capdate = $(this).data('capdate'); // Ambil capdate
            var capdoc = $(this).data('capdoc'); // Ambil capdoc
            var noasset = $(this).data('noasset'); // Ambil noasset

            // Isi data ke dalam modal
            $('#project_desc_edit').val(project_desc);
            $('#wbs_capex_edit').val(wbs_capex)
        .change(); // Pastikan dropdown menampilkan pilihan yang benar
            $('#remark_edit').val(remark);
            $('#request_number_edit').val(request_number);
            $('#requester_edit').val(requester);
            $('#capex_number_edit').val(capex_number);
            $('#amount_budget_edit').val(amount_budget);
            $('#status_capex_edit').val(status_capex)
        .change(); // Pastikan dropdown menampilkan pilihan yang benar
            $('#budget_type_edit').val(budget_type)
        .change(); // Pa// Anda mungkin perlu memformat dropdown
            $('#startup_edit').val(startup);
            $('#expected_completed_edit').val(expected_completed);
            $('#wbs_number_edit').val(wbs_number);
            $('#cip_number_edit').val(cip_number);
            $('#category_edit').val(category);
            $('#capdate_edit').val($(this).data('capdate'));
            $('#capdoc_edit').val($(this).data('capdoc'));
            $('#noasset_edit').val($(this).data('noasset'));


            $('#id_capex_edit').val(id_capex); // Pastikan Anda memiliki input tersembunyi di modal Anda


            $('#wbsCapexDropdownEdit').text(wbs_capex.charAt(0).toUpperCase() + wbs_capex.slice(1)
                .replace(/_/g, ' '));
            $('#statusDropdownEdit').text(status_capex.charAt(0).toUpperCase() + status_capex.slice(1)
                .replace(/_/g, ' '));
            $('#budgetTypeDropdownEdit').text(budget_type === 'budgeted' ? 'Budgeted' : 'Unbudgeted');
            $('#categoryDropdownEdit').text(
                category === 'General Operation' ? 'General Operation' :
                category === 'IT' ? 'IT' :
                category === 'Environment' ? 'Environment' :
                category === 'Safety' ? 'Safety' :
                category === 'Improvement Plant efficiency' ? 'Improvement Plant efficiency' :
                category === 'Invesment' ? 'Invesment' : 'Unknown'
            );
        });

        // Event handler untuk menyimpan perubahan pada modal
        $('#save-edit-capex').click(function() {
            // Ambil status capex
            var status_capex = $('#status_capex_edit').val();

            // Cek jika status Close
            if (status_capex === 'Close') {
                var fileInput = $('input[name="file_pdf"]');

                // Validasi file
                if (fileInput.get(0).files.length === 0) {
                    Swal.fire({
                        title: 'Error!',
                        text: 'Harap unggah file PDF untuk status Close',
                        icon: 'error',
                        confirmButtonText: 'OK'
                    });
                    return; // Hentikan proses
                }
                // Validasi tipe file
                var file = fileInput.get(0).files[0];
                var allowedTypes = ['application/pdf'];

                if (!allowedTypes.includes(file.type)) {
                    Swal.fire({
                        title: 'Error!',
                        text: 'Hanya file PDF',
                        icon: 'error',
                        confirmButtonText: 'OK'
                    });
                    return; // Hentikan proses
                }
            }
            // Buat FormData untuk mendukung upload file
            var formData = new FormData();

            // Tambahkan data form
            formData.append('id_capex', $('#id_capex_edit').val());
            formData.append('project_desc', $('#project_desc_edit').val());
            formData.append('wbs_capex', $('#wbs_capex_edit').val());
            formData.append('remark', $('#remark_edit').val());
            formData.append('request_number', $('#request_number_edit').val());
            formData.append('requester', $('#requester_edit').val());
            formData.append('capex_number', $('#capex_number_edit').val());
            formData.append('amount_budget', $('#amount_budget_edit').val());
            formData.append('status_capex', status_capex);
            formData.append('budget_type', $('#budget_type_edit').val());
            formData.append('startup', $('#startup_edit').val());
            formData.append('expected_completed', $('#expected_completed_edit').val());
            formData.append('wbs_number', $('#wbs_number_edit').val());
            formData.append('cip_number', $('#cip_number_edit').val());
            formData.append('category', $('#category_edit').val());
            formData.append('flag', 'update');

            // Tambahkan file jika ada
            if (status_capex === 'Close') {
                formData.append('file_pdf', fileInput.get(0).files[0]);
                formData.append('capdate', $('#capdate_edit').val());
                formData.append('capdoc', $('#capdoc_edit').val());
                formData.append('noasset', $('#noasset_edit').val());
            }

            // Tampilkan konfirmasi
            Swal.fire({
                title: 'Konfirmasi',
                text: 'Apakah Anda yakin ingin menyimpan perubahan ini?',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Ya, Perbarui!',
                cancelButtonText: 'Tidak'
            }).then((result) => {
                if (result.isConfirmed) {
                    // Kirim data ke server menggunakan AJAX
                    $.ajax({
                        url: "{{ route('capex.store') }}",
                        type: 'POST',
                        data: formData,
                        processData: false, // Penting untuk FormData
                        contentType: false, // Penting untuk FormData
                        success: function(response) {
                            $('#edit-form').modal('hide');
                            $('#capex-table').DataTable().ajax.reload();
                            Swal.fire({
                                title: 'Berhasil!',
                                text: 'Capex berhasil di update!',
                                icon: 'success',
                                showConfirmButton: false,
                                timer: 1000
                            });
                        },
                        error: function(xhr) {
                            console.error('Error Details:', xhr.responseJSON);

                            var errorMessage = 'Terjadi kesalahan';
                            if (xhr.responseJSON) {
                                if (xhr.responseJSON.errors) {
                                    // Tampilkan detail error validasi
                                    errorMessage = Object.values(xhr.responseJSON
                                        .errors).flat().join('\n');
                                } else {
                                    errorMessage = xhr.responseJSON.message || xhr
                                        .responseJSON.error;
                                }
                            }
                            Swal.fire({
                                title: 'Error!',
                                text: errorMessage,
                                icon: 'error',
                                confirmButtonText: 'OK'
                            });
                        }
                    });
                }
            });
        });
    });
</script>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const numberInputs = document.querySelectorAll(
        'input.edit-capex'); // Menggunakan kelas khusus untuk input update

        numberInputs.forEach(input => {
            input.addEventListener('input', function() {
                // Menghapus semua karakter yang bukan angka, koma, dan titik
                let value = this.value.replace(/[^0-9.,]/g, '');

                // Memisahkan bagian integer dan desimal
                let parts = value.split(',');
                let integerPart = parts[0].replace(/\./g,
                ''); // Menghapus titik dari bagian integer
                let decimalPart = parts[1] ? ',' + parts[1].slice(0, 2) :
                ''; // Menyimpan bagian desimal maksimum 2 digit

                // Memformat bagian integer dengan pemisah ribuan
                let formattedInteger = parseInt(integerPart).toLocaleString('id-ID');

                // Menggabungkan bagian integer dan desimal
                this.value = formattedInteger + decimalPart;
            });

            input.addEventListener('blur', function() {
                // Format saat fokus hilang (blur)
                let value = this.value.replace(/\./g, '').replace(/,/g,
                '.'); // Menghapus titik dan mengubah koma menjadi titik
                if (value) {
                    this.value = parseFloat(value).toString();
                }
            });
        });
    });
</script>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const categoryDropdownItems = document.querySelectorAll('#statusDropdownEdit + .dropdown-menu .dropdown-item');
        const fileUploadContainer = document.getElementById('fileUploadContainer');
        const capdateContainer = document.getElementById('capdateContainer');
        const capdocContainer = document.getElementById('capdocContainer');
        const noassetContainer = document.getElementById('noassetContainer');
        const fileUploadInput = fileUploadContainer.querySelector('input[type="file"]');
        const saveEditButton = document.getElementById('save-edit-capex');
        const statusCapexInput = document.getElementById('status_capex_edit');
        const statusDropdownButton = document.getElementById('statusDropdownEdit');
        // Fungsi untuk toggle semua container
        function toggleContainers(status) {
            if (status === 'Close') {
                // Tampilkan semua container
                fileUploadContainer.style.display = 'block';
                capdateContainer.style.display = 'block';
                capdocContainer.style.display = 'block';
                noassetContainer.style.display = 'block';

                // Set required attributes
                fileUploadInput.setAttribute('required', 'required');
                document.getElementById('capdate_edit').setAttribute('required', 'required');
                document.getElementById('capdoc_edit').setAttribute('required', 'required');
                document.getElementById('noasset_edit').setAttribute('required', 'required');
            } else {
                // Sembunyikan semua container
                fileUploadContainer.style.display = 'none';
                capdateContainer.style.display = 'none';
                capdocContainer.style.display = 'none';
                noassetContainer.style.display = 'none';

                // Hapus required attributes
                fileUploadInput.removeAttribute('required');
                document.getElementById('capdate_edit').removeAttribute('required');
                document.getElementById('capdoc_edit').removeAttribute('required');
                document.getElementById('noasset_edit').removeAttribute('required');

                // Reset nilai
                fileUploadInput.value = '';
                document.getElementById('capdate_edit').value = '';
                document.getElementById('capdoc_edit').value = '';
                document.getElementById('noasset_edit').value = '';
            }
        }
        // Fungsi validasi semua input required
        function validateInputs() {
            if (statusCapexInput.value === 'Close') {
                const capdate = document.getElementById('capdate_edit').value;
                const capdoc = document.getElementById('capdoc_edit').value;
                const noasset = document.getElementById('noasset_edit').value;

                if (!capdate || !capdoc || !noasset) {
                    Swal.fire({
                        title: 'Error!',
                        text: 'Harap isi Capitalized Date, Capitalized Doc, dan No Asset untuk status Close',
                        icon: 'error',
                        confirmButtonText: 'OK'
                    });
                    return false;
                }
                if (!validatePDFInput(fileUploadInput)) {
                    return false;
                }
            }
            return true;
        }
        // Fungsi validasi input file PDF
        function validatePDFInput(fileInput) {
            const allowedTypes = ['application/pdf'];
            const file = fileInput.files[0];
            if (!file) {
                Swal.fire({
                    title: 'Error!',
                    text: 'Harap unggah file PDF untuk status Close',
                    icon: 'error',
                    confirmButtonText: 'OK'
                });
                return false;
            }
            if (!allowedTypes.includes(file.type)) {
                Swal.fire({
                    title: 'Error!',
                    text: 'Hanya file PDF yang diperbolehkan',
                    icon: 'error',
                    confirmButtonText: 'OK'
                });
                return false;
            }
            return true;
        }
        // Event listener untuk dropdown status
        categoryDropdownItems.forEach(item => {
            item.addEventListener('click', function() {
                const selectedValue = this.getAttribute('data-value');
                statusCapexInput.value = selectedValue;
                statusDropdownButton.textContent = selectedValue;
                toggleContainers(selectedValue);
            });
        });
        // Event listener untuk perubahan langsung pada select status
        statusCapexInput.addEventListener('change', function() {
            toggleContainers(this.value);
        });
        // Event listener untuk tombol save
        saveEditButton.addEventListener('click', function(event) {
            if (!validateInputs()) {
                event.preventDefault();
                return false;
            }
        });
        // Inisialisasi awal - check status saat halaman dimuat
        toggleContainers(statusCapexInput.value);
    });

    $('#generateAssets').on('click', function () {
        const className = $('#class_name').val().replace(/^Z/, ''); // Hilangkan huruf "Z" di awal
        const startNumber = parseInt($('#start_number').val());
        const endNumber = parseInt($('#end_number').val());
        const suffix = '-0'; // Suffix tetap di-hardcode di sini
        const result = [];

        if (!className) {
            alert('Pilih Class Name terlebih dahulu!');
            return;
        }
        if (isNaN(startNumber) || isNaN(endNumber) || startNumber > endNumber) {
            alert('Masukkan rentang nomor yang valid!');
            return;
        }

        // Generate daftar nomor aset
        for (let i = startNumber; i <= endNumber; i++) {
            // Tambahkan angka 0 di depan sehingga panjangnya 7 digit
            const paddedNumber = i.toString().padStart(7, '0');
            result.push(`${className}${paddedNumber}${suffix}`);
        }

        // Gabungkan hasil dengan koma
        $('#generated_assets').val(result.join(', '));
    });


    $(document).ready(function() {
        $('#class_name').select2({
            placeholder: "Pilih Class Name",
            allowClear: true,
            width: '100%',
            dropdownCssClass: 'select2-dropdown'
        });
    });


</script>
