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
                            <div class="col-12">
                                <h6>Persetujuan Engineer</h6>
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
                                <label class="form-label">Tanda Tangan Anda</label>
                                <input type="text" class="form-control" id="currentSignature"
                                    value="{{ auth()->user()->name }} - {{ now()->format('Y-m-d H:i:s') }}" readonly>
                            </div>
                            <div class="col-md-4">
                                <button type="submit" class="btn bg-gradient-warning" id="saveSignature">Menunggu
                                    Persetujuan</button>
                            </div>
                        </div>
                    </div>

                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
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
            var statusApprove1 = button.data('status1');
            var statusApprove2 = button.data('status2');
            var statusApprove3 = button.data('status3');
            var userRole = "{{ auth()->user()->roles->first()->name }}";

            $('#signature-id-capex').val(idCapex);

            let currentStatus;
            let canSign = false;

            // Update status untuk semua approval yang ada
            // Status Admin
            if (statusApprove1 == 1) {
                $('#adminSignature').text('Ditandatangani');
                $('#adminStatus').text('Disetujui').removeClass('bg-secondary').addClass('bg-success');
            } else if (statusApprove1 == 2) {
                $('#adminSignature').text('Ditandatangani');
                $('#adminStatus').text('Ditolak').removeClass('bg-secondary').addClass('bg-danger');
            }

            // Status User
            if (statusApprove2 == 1) {
                $('#userSignature').text('Ditandatangani');
                $('#userStatus').text('Disetujui').removeClass('bg-secondary').addClass('bg-success');
            } else if (statusApprove2 == 2) {
                $('#userSignature').text('Ditandatangani');
                $('#userStatus').text('Ditolak').removeClass('bg-secondary').addClass('bg-danger');
            }

            // Status Engineer
            if (statusApprove3 == 1) {
                $('#engineerSignature').text('Ditandatangani');
                $('#engineerStatus').text('Disetujui').removeClass('bg-secondary').addClass(
                    'bg-success');
            } else if (statusApprove3 == 2) {
                $('#engineerSignature').text('Ditandatangani');
                $('#engineerStatus').text('Ditolak').removeClass('bg-secondary').addClass('bg-danger');
            }

            // Tentukan status untuk user yang sedang login
            if (userRole === 'admin') {
                currentStatus = statusApprove1;
                canSign = true;
            } else if (userRole === 'user') {
                currentStatus = statusApprove2;
                canSign = statusApprove1 == 1;
            } else if (userRole === 'engineer') {
                currentStatus = statusApprove3;
                canSign = statusApprove1 == 1 && statusApprove2 == 1;
            }

            if (!canSign) {
                $('#saveSignature')
                    .text('Menunggu Persetujuan Sebelumnya')
                    .prop('disabled', true);
                return;
            }

            // Mengatur tombol berdasarkan status current user
            if (currentStatus == 0) {
                $('#saveSignature')
                    .text('Menunggu Persetujuan')
                    .removeClass('btn-success btn-danger')
                    .addClass('bg-gradient-warning')
                    .prop('disabled', false);
            } else if (currentStatus == 1) {
                $('#saveSignature')
                    .text('Disetujui')
                    .removeClass('bg-gradient-warning btn-danger')
                    .addClass('btn-success')
                    .prop('disabled', true);
            } else if (currentStatus == 2) {
                $('#saveSignature')
                    .text('Ditolak')
                    .removeClass('bg-gradient-warning btn-success')
                    .addClass('btn-danger')
                    .prop('disabled', true);
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
