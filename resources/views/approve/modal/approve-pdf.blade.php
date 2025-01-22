<div class="modal fade" id="signatureModal" tabindex="-1" aria-labelledby="signatureModalLabel">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header" style="background-color: #17a2b8;">
                <h5 class="modal-title" id="signatureModalLabel" style="color: white;">Tanda Tangan BAST</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Tutup"></button>
            </div>
            <div class="modal-body">
                <form id="signatureForm" action="{{ route('approve.store') }}" method="POST">
                    @csrf
                    <input type="hidden" id="signature-id-capex" name="id_capex" value="">
                    <input type="hidden" name="flag" value="signature">

                    <div class="container-fluid">
                        <!-- Tanda Tangan Admin -->
                        <div class="row mb-3">
                            <div class="col-12">
                                <h6>Persetujuan Admin</h6>
                                <div class="signature-block p-2 border rounded">
                                    <div class="d-flex justify-content-between align-items-center">
                                        <span id="admin1_Signature">Belum ditandatangani</span>
                                        <span id="admin1_Status" class="badge bg-secondary">Menunggu</span>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="row mb-3">
                            <div class="col-12">
                                <h6>Persetujuan Manager OC</h6>
                                <div class="signature-block p-2 border rounded">
                                    <div class="d-flex justify-content-between align-items-center">
                                        <span id="admin2_Signature">Belum ditandatangani</span>
                                        <span id="admin2_Status" class="badge bg-secondary">Menunggu</span>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Tanda Tangan User -->
                        <div class="row mb-3">
                            <div class="col-12">
                                <h6>Persetujuan User</h6>
                                <div class="signature-block p-2 border rounded">
                                    <div class="d-flex justify-content-between align-items-center">
                                        <span id="userSignature">Belum ditandatangani</span>
                                        <span id="userStatus" class="badge bg-secondary">Menunggu</span>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Tanda Tangan Engineer -->
                        <div class="row mb-3">
                            <div class="col-12" id="engineerBox">
                                <h6 id="engineerName">Persetujuan Engineer</h6>
                                <div class="signature-block p-2 border rounded">
                                    <div class="d-flex justify-content-between align-items-center">
                                        <span id="engineerSignature">Belum ditandatangani</span>
                                        <span id="engineerStatus" class="badge bg-secondary">Menunggu</span>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Input Tanda Tangan Pengguna Saat Ini -->
                        <div class="row mb-3">

                            <div class="col-md-8">
                                <button type="submit" class="btn bg-gradient-warning" id="saveSignature">Menunggu Persetujuan</button>
                            </div>
                            
                            @if(auth()->user()->hasRole(['admin']) && auth()->id() == 3)
                            <div class="col-md-4">
                                    <button type="button" class="btn btn-primary" style="background-color: #27ad58; color: white;" 
                                    data-bs-toggle="modal" .
                                    data-bs-target="#form-check"
                                    id="openCheckForm">
                                    Form Check
                                </button>
                            </div>
                            @endif
                        </div>

                        <a id="viewUploadButton" href="#" class="btn bg-gradient-primary" target="_blank">
                            <i class="fa fa-file-pdf"></i> Lihat PDF Upload
                        </a>
                        <a id="viewDetailButton" href="#" class="btn bg-gradient-info" target="_blank">
                            <i class="fa fa-file-pdf"></i> PDF Form Detail
                        </a>
                        <a id="viewClosingButton" href="#" class="btn bg-gradient-warning" target="_blank">
                            <i class="fa fa-file-pdf"></i> PDF Form Closing
                        </a>
                        <a id="viewAcceptButton" href="#" class="btn bg-gradient-secondary" target="_blank">
                            <i class="fa fa-file-pdf"></i> PDF Form Acceptance
                        </a>
                    </div>

                    <div class="modal-footer">
                        <button type="button" class="btn bg-gradient-secondary" data-bs-dismiss="modal">Batal</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>


<script>
    $(document).ready(function() {
        // Ketika modal signature dibuka, set value id_capex dan status approval
        $('#signatureModal').on('show.bs.modal', function(event) {
            var button = $(event.relatedTarget);
            var idCapex = button.data('id');
            var statusApprove1 = button.data('status1'); // Status Admin 1
            var statusApprove2 = button.data('status2'); // Status User
            var statusApprove3 = button.data('status3'); // Status Engineer
            var statusApprove4 = button.data('status4'); // Status Admin 2
            var wbs_capex = button.data('wbs');
            var apv_admin1 = button.data('apv_admin1');
            var apv_at_admin1 = button.data('apv_at_admin1');
            var apv_admin2 = button.data('apv_admin2');
            var apv_at_admin2 = button.data('apv_at_admin2');
            var apv_user = button.data('apv_user');
            var apv_at_user = button.data('apv_at_user');
            var apv_engineer = button.data('apv_engineer');
            var apv_at_engineer = button.data('apv_at_engineer');
            var userRole = "{{ auth()->user()->roles->first()->name }}"; // Role user
            var userId = "{{ auth()->user()->id }}";
            var id_admin_1 = 3; // ID Admin 1
            var id_admin_2 = 4; // ID Admin 2  

            $('#signature-id-capex').val(idCapex);

            let currentStatus;
            let canSign = false;

            if (userRole === 'admin') {
                if (userId == id_admin_1) {
                    currentStatus = statusApprove1;
                    canSign = true;
                } else if (userId == id_admin_2) {
                    currentStatus = statusApprove4;
                    canSign = statusApprove1 == 1;
                }
            } else if (userRole === 'user') {
                currentStatus = statusApprove2;
                canSign = statusApprove1 == 1 && statusApprove4 ==
                    1;
            } else if (userRole === 'engineering') {
                currentStatus = statusApprove3;
                canSign = statusApprove1 == 1 && statusApprove4 == 1 && statusApprove2 == 1;
            }

            if (statusApprove1 == 1) {
                $('#admin1_Signature').text('Prepared by ' + apv_admin1 + ' at ' + apv_at_admin1);
                $('#admin1_Status').text('Disetujui').removeClass('bg-gradient-secondary').addClass(
                    'bg-success');
            } else if (statusApprove1 == 2) {
                $('#admin1_Signature').text('Ditolak');
                $('#admin1_Status').text('Ditolak').removeClass('bg-secondary').addClass('bg-danger');
            } else {
                $('#admin1_Signature').text('Belum ditandatangani');
                $('#admin1_Status').text('Menunggu').removeClass('bg-success bg-danger').addClass(
                    'bg-secondary');
            }

            // Display logic untuk Admin 2
            if (statusApprove4 == 1) {
                $('#admin2_Signature').text('Reviewed by ' + apv_admin2 + ' at ' + apv_at_admin2);
                $('#admin2_Status').text('Disetujui').removeClass('bg-gradient-secondary').addClass(
                    'bg-success');
            } else if (statusApprove4 == 2) {
                $('#admin2_Signature').text('Ditolak');
                $('#admin2_Status').text('Ditolak').removeClass('bg-secondary').addClass('bg-danger');
            } else {
                $('#admin2_Signature').text('Belum ditandatangani');
                $('#admin2_Status').text('Menunggu').removeClass('bg-success bg-danger').addClass(
                    'bg-secondary');
            }

            // Cek status User
            if (statusApprove2 == 1) {
                $('#userSignature').text('Approved by ' + apv_user + ' at ' + apv_at_user);
                $('#userStatus').text('Disetujui').removeClass('bg-secondary').addClass('bg-success');
            } else if (statusApprove2 == 2) {
                $('#userSignature').text('Ditolak ' + apv_user + ' at ' + apv_at_userd);
                $('#userStatus').text('Ditolak').removeClass('bg-secondary').addClass('bg-danger');
            } else {
                $('#userSignature').text('Belum ditandatangani');
                $('#userStatus').text('Menunggu').removeClass('bg-success bg-danger').addClass(
                    'bg-secondary');
            }

            // Cek status Engineer
            if (wbs_capex === 'Project') {
                if (statusApprove3 == 1) {
                    $('#engineerSignature').text('Approved by ' + apv_engineer + ' at ' +
                        apv_at_engineer);
                    $('#engineerStatus').text('Disetujui').removeClass('bg-secondary').addClass(
                        'bg-success');
                    $('#engineerName').text('Persetujuan Engineer');
                } else if (statusApprove3 == 2) {
                    $('#engineerSignature').text('Ditolak ' + apv_engineer + ' at ' + apv_at_engineer);
                    $('#engineerStatus').text('Ditolak').removeClass('bg-secondary').addClass(
                        'bg-danger');
                    $('#engineerName').text('Persetujuan Engineer');
                } else {
                    $('#engineerSignature').text('Belum ditandatangani');
                    $('#engineerStatus').text('Menunggu').removeClass('bg-success bg-danger').addClass(
                        'bg-secondary');
                    $('#engineerName').text('Persetujuan Engineer');
                }

                $('#engineerBox').show(); // Tampilkan box Engineer jika Project
            } else {
                // Jika bukan 'Project', sembunyikan status Engineer
                $('#engineerSignature').text('');
                $('#engineerStatus').text('');
                $('#engineerName').text('');
                $('#engineerBox').hide(); // Sembunyikan box Engineer jika Non Project
            }


            function updateButtonDisplay(hasWbsType) {
                const $saveButton = $('#saveSignature');

                // Cek kondisi role dan canSign terlebih dahulu
                if (userRole === 'admin' && userId == id_admin_2 && !canSign) {
                    $saveButton
                        .text('Menunggu Persetujuan Admin 1')
                        .prop('disabled', true)
                        .show();
                    return;
                } else if (userRole === 'user' && !canSign) {
                    $saveButton
                        .text('Menunggu Persetujuan Admin')
                        .prop('disabled', true)
                        .show();
                    return;
                } else if (userRole === 'engineering' && !canSign) {
                    $saveButton
                        .text('Menunggu Persetujuan User')
                        .prop('disabled', true)
                        .show();
                    return;
                }

                // Tangani logika berdasarkan currentStatus
                switch (currentStatus) {
                    case 0: // Status menunggu persetujuan user
                        $saveButton
                            .text('Menunggu Persetujuan Anda')
                            .removeClass('btn-success btn-danger')
                            .addClass('bg-gradient-warning')
                            .prop('disabled', !hasWbsType) // Tombol aktif jika WBS ada
                            .show();
                        break;

                    case 1: // Status telah disetujui
                        if (userRole === 'admin') {
                            if (userId == id_admin_1 || userId == id_admin_2) {
                                $saveButton.hide();
                            }
                        } else {
                            $saveButton.hide();
                        }
                        break;

                    case 2: // Status ditolak
                        $saveButton.hide();
                        handleRejectionStatus();
                        break;
                }
            }

            function handleRejectionStatus() {
                // Perbarui tampilan status untuk setiap peran berdasarkan penolakan
                if (userRole === 'admin') {
                    if (userId == id_admin_1) {
                        $('#admin1_Signature').text('Rejected By ' + apv_admin1 + ' at ' + apv_at_admin1);
                        $('#admin1_Status').text('Ditolak').removeClass('bg-secondary').addClass('bg-danger');
                    } else if (userId == id_admin_2) {
                        $('#admin2_Signature').text('Ditolak');
                        $('#admin2_Status').text('Ditolak').removeClass('bg-secondary').addClass('bg-danger');
                    }
                } else if (userRole === 'user') {
                    $('#userSignature').text('Ditolak');
                    $('#userStatus').text('Ditolak').removeClass('bg-secondary').addClass('bg-danger');
                } else if (userRole === 'engineering') {
                    $('#engineerSignature').text('Ditolak');
                    $('#engineerStatus').text('Ditolak').removeClass('bg-secondary').addClass('bg-danger');
                }
            }

            // Inisialisasi tombol dengan status default
            $('#saveSignature').prop('disabled', true);

            // Periksa WBS melalui AJAX
            $.ajax({
                url: `/approve/${idCapex}?flag=checkWBS`,
                method: 'GET',
                success: function(response) {
                    // Perbarui tampilan tombol berdasarkan hasil cek WBS
                    updateButtonDisplay(response.hasWbsType);
                },
                error: function() {
                    console.error('Terjadi kesalahan saat memeriksa WBS Type.');
                    // Jika terjadi error, update tampilan dengan asumsi WBS tidak ada
                    updateButtonDisplay(false);
                }
            });
        });

        // Menangani klik tombol untuk pending
        $('#saveSignature').on('click', function(e) {
            e.preventDefault();
            showConfirmationDialog();

            Swal.fire({
                title: 'Konfirmasi Persetujuan',
                text: 'Apakah Anda ingin melakukan persetujuan?',
                icon: 'question',
                showDenyButton: true,
                showCancelButton: true,
                confirmButtonText: 'Setuju',
                denyButtonText: 'Tolak',
                cancelButtonText: 'Batal'
            }).then((result) => {
                if (result.isConfirmed) {
                    // Langsung proses jika setuju
                    processForm(result.isConfirmed);
                } else if (result.isDenied) {
                    // Langsung proses jika tolak tanpa alasan
                    processForm(false);
                }
            });

            function processForm(isApproved, reason = '') {
                let formData = new FormData($('#signatureForm')[0]);
                let userRole = "{{ auth()->user()->roles->first()->name }}";
                var userId = "{{ auth()->user()->id }}";
                var id_admin_1 = 3;
                var id_admin_2 = 4;

                // Tambahkan reason ke formData jika ada
                if (reason) {
                    formData.append('reason', reason);
                }

                // Tentukan status berdasarkan role
                if (userRole === 'admin') {
                    if (userId == id_admin_1) {
                        formData.append('status_approve_1', isApproved ? 1 : 2);
                    } else if (userId == id_admin_2) {
                        formData.append('status_approve_4', isApproved ? 1 : 2);
                    }
                } else if (userRole === 'user') {
                    formData.append('status_approve_2', isApproved ? 1 : 2);
                } else if (userRole === 'engineering') {
                    formData.append('status_approve_3', isApproved ? 1 : 2);
                }

                submitForm(formData);
            }
        });

        $(document).on('keydown', function(e) {
            // Cek apakah ada SweetAlert yang terbuka
            if (Swal.isVisible()) {
                // Jika tombol Enter ditekan (keyCode 13)
                if (e.keyCode === 13) {
                    e.preventDefault();
                    // Klik tombol confirm (Setuju)
                    Swal.clickConfirm();
                }
            }
        });

        function showConfirmationDialog() {
            Swal.fire({
                title: 'Konfirmasi Persetujuan',
                text: 'Apakah Anda ingin melakukan persetujuan?',
                icon: 'question',
                showDenyButton: true,
                showCancelButton: true,
                confirmButtonText: 'Setuju',
                denyButtonText: 'Tolak',
                cancelButtonText: 'Batal',
                // Tambahkan fokus ke tombol confirm
                focusConfirm: true
            }).then((result) => {
                if (result.isConfirmed) {
                    // Langsung proses jika setuju
                    processForm(result.isConfirmed);
                } else if (result.isDenied) {
                    // Langsung proses jika tolak tanpa alasan
                    processForm(false);
                }
            });
        }

        // Fungsi untuk mengirim data form melalui AJAX
        function submitForm(formData) {
            $.ajax({
                url: "{{ route('approve.store') }}",
                type: "POST",
                data: formData,
                contentType: false,
                processData: false,
                beforeSend: function() {
                    Swal.fire({
                        title: 'Menyimpan...',
                        text: 'Mohon tunggu...',
                        allowOutsideClick: false,
                        didOpen: () => {
                            Swal.showLoading();
                        }
                    });
                },
                success: function(response) {
                    Swal.fire({
                        icon: 'success',
                        title: 'Berhasil!',
                        text: response.success,
                        timer: 2000,
                        timerProgressBar: true
                    }).then(() => {
                        // Setelah Swal ditutup, refresh halaman
                        location.reload();
                    });
                },
                error: function(xhr) {
                    // Handling errors from the AJAX request
                    Swal.fire({
                        icon: 'error',
                        title: 'Terjadi Kesalahan!',
                        text: xhr.responseJSON.message || 'Mohon coba lagi.',
                        timerProgressBar: true
                    });
                }
            });
        }

        $('#signatureModal').on('show.bs.modal', function(event) {
            var button = $(event.relatedTarget);
            var idCapex = button.data('id');
            var signatureDetailFile = button.data('signature-detail-file');
            var signatureClosingFile = button.data('signature-closing-file');
            var signatureAcceptance = button.data('signature-acceptance-file');
            var showPdf = button.data('show-pdf');

            var timestamp = new Date().getTime();

            if (signatureDetailFile) {
                var viewPdfUrl = "{{ route('approve.show', ':id') }}"
                    .replace(':id', idCapex) +
                    "?flag=show-form-detail&t=" + timestamp;

                $('#viewDetailButton').attr('href', viewPdfUrl).show();
            } else {
                $('#viewDetailButton').hide();
            }

            if (signatureClosingFile) {
                var viewPdfUrl = "{{ route('approve.show', ':id') }}"
                    .replace(':id', idCapex) +
                    "?flag=show-form-closing&t=" + timestamp;

                $('#viewClosingButton').attr('href', viewPdfUrl).show();
            } else {
                $('#viewClosingButton').hide();
            }

            if (showPdf) {
                var viewPdfUrl = "{{ route('approve.show', ':id') }}"
                    .replace(':id', idCapex) +
                    "?flag=show-pdf&t=" + timestamp;

                $('#viewUploadButton').attr('href', viewPdfUrl).show();
            } else {
                $('#viewUploadButton').hide();
            }

            if (signatureAcceptance) {
                var viewPdfUrl = "{{ route('approve.show', ':id') }}"
                    .replace(':id', idCapex) +
                    "?flag=show-form-acceptance&t=" + timestamp;

                $('#viewAcceptButton').attr('href', viewPdfUrl).show();
            } else {
                $('#viewAcceptButton').hide();
            }
        });

    });
</script>

