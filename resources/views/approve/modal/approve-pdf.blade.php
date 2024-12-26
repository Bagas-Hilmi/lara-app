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
                        <div class="row mb-3" id="currentSignatureBlock">
                            <div class="col-md-8">
                                <button type="submit" class="btn bg-gradient-warning" id="saveSignature">Menunggu
                                    Persetujuan</button>
                            </div>
                            <div class="col-md-4">

                            </div>
                        </div>

                        <a id="viewPdfButton" href="#" class="btn btn-primary" target="_blank">
                            <i class="fa fa-file-pdf"></i> Lihat PDF
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
            var userRole = "{{ auth()->user()->roles->first()->name }}"; // Role Pengguna
            var userId = "{{ auth()->user()->id }}"; // Get current user ID
            var id_admin_1 = 3; // ID Admin 1
            var id_admin_2 = 4; // ID Admin 2  

            $('#signature-id-capex').val(idCapex);

            let currentStatus;
            let canSign = false;

            // Tentukan status berdasarkan role pengguna
            if (userRole === 'admin') {
                if (userId == id_admin_1) {
                    currentStatus = statusApprove1;
                    canSign = true; // Admin 1 bisa sign kapan saja
                } else if (userId == id_admin_2) {
                    currentStatus = statusApprove4;
                    canSign = statusApprove1 == 1; // Admin 2 hanya bisa sign jika Admin 1 sudah approve
                }
            } else if (userRole === 'user') {
                currentStatus = statusApprove2;
                canSign = statusApprove1 == 1 && statusApprove4 ==
                    1; // User bisa sign jika Admin 1 dan Admin 2 sudah approve
            } else if (userRole === 'engineer') {
                currentStatus = statusApprove3;
                canSign = statusApprove1 == 1 && statusApprove4 == 1 && statusApprove2 ==
                    1; // Engineer bisa sign jika Admin 1, Admin 2 dan User sudah approve
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


            if (userRole === 'admin' && userId == id_admin_2 && !canSign) {
                $('#saveSignature')
                    .text('Menunggu Persetujuan Admin 1')
                    .prop('disabled', true)
                    .show();
                return;
            } else if (userRole === 'user' && !canSign) {
                $('#saveSignature')
                    .text('Menunggu Persetujuan Admin')
                    .prop('disabled', true)
                    .show();
                return;
            } else if (userRole === 'engineer' && !canSign) {
                $('#saveSignature')
                    .text('Menunggu Persetujuan User')
                    .prop('disabled', true)
                    .show();
                return;
            }


            // Mengatur teks tombol dan status berdasarkan status saat ini
            if (currentStatus == 0) {
                $('#saveSignature')
                    .text('Menunggu Persetujuan Anda')
                    .removeClass('btn-success btn-danger')
                    .addClass('bg-gradient-warning')
                    .prop('disabled', false)
                    .show();

            } else if (currentStatus == 1) {
                if (userRole === 'admin') {
                    if (userId == id_admin_1) {
                        // Admin 1 menandatangani
                        $('#saveSignature').hide();
                        $('#admin1_Signature').text('Prepared By ' + apv_admin1 + ' at ' +
                            apv_at_admin1);
                        $('#admin1_Status').text('Disetujui').removeClass('bg-secondary').addClass(
                            'bg-success');
                    } else if (userId == id_admin_2) {
                        // Admin 2 menandatangani
                        $('#saveSignature').hide();
                        $('#admin2_Signature').text('Approved By ' + apv_admin2 + ' at ' +
                            apv_at_admin2);
                        $('#admin2_Status').text('Disetujui').removeClass('bg-secondary').addClass(
                            'bg-success');
                    }
                } else if (userRole === 'user') {
                    $('#saveSignature').hide();
                } else if (userRole === 'engineer') {
                    $('#saveSignature').hide();
                }
            } else if (currentStatus == 2) {
                if (userRole === 'admin') {
                    if (userId == id_admin_1) {
                        // Admin 1 menolak
                        $('#saveSignature').hide();

                        $('#admin1_Signature').text('Ditolak');
                        $('#admin1_Status').text('Ditolak').removeClass('bg-secondary').addClass(
                            'bg-danger');
                    } else if (userId == id_admin_2) {
                        // Admin 2 menolak
                        $('#saveSignature').hide();

                        $('#admin2_Signature').text('Ditolak');
                        $('#admin2_Status').text('Ditolak').removeClass('bg-secondary').addClass(
                            'bg-danger');
                    }
                } else if (userRole === 'user') {
                    // User menolak
                    $('#saveSignature').hide();

                    $('#userSignature').text('Ditolak');
                    $('#userStatus').text('Ditolak').removeClass('bg-secondary').addClass('bg-danger');
                } else if (userRole === 'engineer') {
                    // Engineer menolak
                    $('#saveSignature').hide();

                    $('#engineerSignature').text('Ditolak');
                    $('#engineerStatus').text('Ditolak').removeClass('bg-secondary').addClass(
                        'bg-danger');
                }

            }
        });

        // Menangani klik tombol untuk pending
        $('#saveSignature').on('click', function(e) {
            e.preventDefault();

            // Hapus pengecekan teks tombol
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
                if (result.isConfirmed || result.isDenied) {
                    let formData = new FormData($('#signatureForm')[0]);
                    let userRole = "{{ auth()->user()->roles->first()->name }}";
                    var userId = "{{ auth()->user()->id }}";
                    var id_admin_1 = 3;
                    var id_admin_2 = 4;

                    // Tentukan status berdasarkan role
                    if (userRole === 'admin') {
                        if (userId == id_admin_1) {
                            formData.append('status_approve_1', result.isConfirmed ? 1 : 2);
                        } else if (userId == id_admin_2) {
                            formData.append('status_approve_4', result.isConfirmed ? 1 : 2);
                        }
                    } else if (userRole === 'user') {
                        formData.append('status_approve_2', result.isConfirmed ? 1 : 2);
                    } else if (userRole === 'engineer') {
                        formData.append('status_approve_3', result.isConfirmed ? 1 : 2);
                    }

                    submitForm(formData);
                }
            });
        });

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
            var signatureFile = button.data('signature-file');

            if (signatureFile) {
                // Tambahkan timestamp ke URL untuk mencegah caching
                var timestamp = new Date().getTime();
                var viewPdfUrl = "{{ route('approve.show', ':id') }}"
                    .replace(':id', idCapex) +
                    "?flag=show-pdf&t=" + timestamp;

                // Update URL PDF viewer
                $('#viewPdfButton').attr('href', viewPdfUrl).show();
            } else {
                $('#viewPdfButton').hide();
            }
        });

    });
</script>
