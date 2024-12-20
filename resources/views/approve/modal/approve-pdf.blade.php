<div class="modal fade" id="signatureModal" tabindex="-1" aria-labelledby="signatureModalLabel">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header" style="background-color: #17a2b8;">
                <h5 class="modal-title" id="signatureModalLabel" style="color: white;">Tanda Tangan Digital</h5>
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
                                        <span id="adminSignature">Belum ditandatangani</span>
                                        <span id="adminStatus" class="badge bg-secondary">Menunggu</span>
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
            var statusApprove1 = button.data('status1'); // Status Admin
            var statusApprove2 = button.data('status2'); // Status User
            var statusApprove3 = button.data('status3'); // Status Engineer
            var wbs_capex = button.data('wbs');
            var apv_admin = button.data('apv_admin');
            var apv_at_admin = button.data('apv_at_admin');
            var apv_user = button.data('apv_user');
            var apv_engineer = button.data('apv_engineer');
            var userRole = "{{ auth()->user()->roles->first()->name }}"; // Role Pengguna

            $('#signature-id-capex').val(idCapex);

            let currentStatus;
            let canSign = false;

            // Tentukan status berdasarkan role pengguna
            if (userRole === 'admin') {
                currentStatus = statusApprove1;
                canSign = true; // Admin bisa menyetujui tanpa syarat
            } else if (userRole === 'user') {
                currentStatus = statusApprove2;
                canSign = statusApprove1 == 1; // User hanya bisa sign jika Admin sudah approve
            } else if (userRole === 'engineer') {
                currentStatus = statusApprove3;
                canSign = statusApprove1 == 1 && statusApprove2 ==
                    1; // Engineer hanya bisa sign jika Admin dan User sudah approve
            }
            // Tambahkan ini setelah deklarasi variabel dan sebelum pengecekan canSign
            // Cek status Admin
            if (statusApprove1 == 1) {
                $('#adminSignature').text('Approved By ' + apv_admin + ' at' + apv_at_admin);
                $('#adminStatus').text('Disetujui').removeClass('bg-gradient-secondary').addClass(
                    'bg-success');
            } else if (statusApprove1 == 2) {
                $('#adminSignature').text('Ditolak');
                $('#adminStatus').text('Ditolak').removeClass('bg-secondary').addClass('bg-danger');
            } else {
                $('#adminSignature').text('Belum ditandatangani');
                $('#adminStatus').text('Menunggu').removeClass('bg-success bg-danger').addClass(
                    'bg-secondary');
            }

            // Cek status User
            if (statusApprove2 == 1) {
                $('#userSignature').text('Ditandatangani oleh : ' + apv_user);
                $('#userStatus').text('Disetujui').removeClass('bg-secondary').addClass('bg-success');
            } else if (statusApprove2 == 2) {
                $('#userSignature').text('Ditolak');
                $('#userStatus').text('Ditolak').removeClass('bg-secondary').addClass('bg-danger');
            } else {
                $('#userSignature').text('Belum ditandatangani');
                $('#userStatus').text('Menunggu').removeClass('bg-success bg-danger').addClass(
                    'bg-secondary');
            }

            // Cek status Engineer
            if (wbs_capex === 'Project') {
                if (statusApprove3 == 1) {
                    $('#engineerSignature').text('Ditandatangani ' + apv_engineer);
                    $('#engineerStatus').text('Disetujui').removeClass('bg-secondary').addClass(
                        'bg-success');
                    $('#engineerName').text('Persetujuan Engineer');
                } else if (statusApprove3 == 2) {
                    $('#engineerSignature').text('Ditolak');
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


            if (userRole === 'user' && !canSign) {
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
                    // Admin menandatangani
                    $('#saveSignature')
                        .hide();

                } else if (userRole === 'user') {
                    // User menandatangani
                    $('#saveSignature')
                        .hide();
                } else if (userRole === 'engineer') {
                    // Engineer menandatangani
                    $('#saveSignature')
                        .hide();
                }
            } else if (currentStatus == 2) {
                if (userRole === 'admin') {
                    // Admin menolak
                    $('#saveSignature')
                        .text('Ditolak')
                        .removeClass('bg-gradient-warning btn-success')
                        .addClass('btn-danger')
                        .prop('disabled', true);

                    // Update status untuk Admin
                    $('#adminSignature').text('Ditolak');
                    $('#adminStatus').text('Ditolak').removeClass('bg-secondary').addClass('bg-danger');
                } else if (userRole === 'user') {
                    // User menolak
                    $('#saveSignature')
                        .text('Ditolak')
                        .removeClass('bg-gradient-warning btn-success')
                        .addClass('btn-danger')
                        .prop('disabled', true);

                    // Update status untuk User
                    $('#userSignature').text('Ditolak');
                    $('#userStatus').text('Ditolak').removeClass('bg-secondary').addClass('bg-danger');
                } else if (userRole === 'engineer') {
                    // Engineer menolak
                    $('#saveSignature')
                        .text('Ditolak')
                        .removeClass('bg-gradient-warning btn-success')
                        .addClass('btn-danger')
                        .prop('disabled', true);

                    // Update status untuk Engineer
                    $('#engineerSignature').text('Ditolak');
                    $('#engineerStatus').text('Ditolak').removeClass('bg-secondary').addClass(
                        'bg-danger');
                }
            }
        });

        // Menangani klik tombol untuk pending
        $('#saveSignature').on('click', function(e) {
            e.preventDefault();

            if ($(this).text() === 'Menunggu Persetujuan') {
                Swal.fire({
                    title: 'Konfirmasi Persetujuan',
                    text: 'Apakah Anda ingin melakukan persetujuan?',
                    icon: 'question',
                    showDenyButton: true,
                    showCancelButton: true,
                    confirmButtonText: 'Persetujuan',
                    denyButtonText: 'Penolakan',
                    cancelButtonText: 'Batal'
                }).then((result) => {
                    if (result.isConfirmed || result.isDenied) {
                        let formData = new FormData($('#signatureForm')[0]);
                        let userRole = "{{ auth()->user()->roles->first()->name }}";

                        // Tentukan status berdasarkan role
                        if (userRole === 'admin') {
                            formData.append('status_approve_1', result.isConfirmed ? 1 : 2);
                        } else if (userRole === 'user') {
                            formData.append('status_approve_2', result.isConfirmed ? 1 : 2);
                        } else if (userRole === 'engineer') {
                            formData.append('status_approve_3', result.isConfirmed ? 1 : 2);
                        }

                        submitForm(formData);
                    }
                });
            }
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
                    let errorMessage = xhr.responseJSON.error || 'Gagal menyimpan tanda tangan';
                    Swal.fire({
                        icon: 'error',
                        title: 'Error!',
                        text: errorMessage
                    });
                }
            });
        }
    });
</script>
