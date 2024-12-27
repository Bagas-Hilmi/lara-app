<div class="modal fade" id="new-user" tabindex="-1" aria-labelledby="new-userLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header" style="background-color: #42bd37;">
                <h4 class="modal-title" id="new-userLabel" style="color: white;">Create New User</h4>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="userForm" action="{{ route('user-management.store') }}"method="POST">
                    @csrf
                    <input type="hidden" name="flag" value="new-user">

                    <div class="row">
                        <div class="col-xs-12 col-sm-12 col-md-12">
                            <div class="form-group">
                                <strong>Name:</strong>
                                <input type="text" name="name" placeholder="Name" class="form-control">
                            </div>
                        </div>
                        <div class="col-xs-12 col-sm-12 col-md-12">
                            <div class="form-group">
                                <strong>Email:</strong>
                                <input type="email" name="email" placeholder="Email" class="form-control">
                            </div>
                        </div>
                        <div class="col-xs-12 col-sm-12 col-md-12">
                            <div class="form-group">
                                <strong>Password:</strong>
                                <input type="password" name="password" placeholder="Password" class="form-control">
                            </div>
                        </div>
                        <div class="col-xs-12 col-sm-12 col-md-12">
                            <div class="form-group">
                                <strong>Confirm Password:</strong>
                                <input type="password" name="confirm-password" placeholder="Confirm Password"
                                    class="form-control">
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button"
                                class="btn bg-gradient-secondary"data-bs-dismiss="modal">Close</button>
                            <button type="submit" class="btn bg-gradient-success" id="saveUser">Submit</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
    document.getElementById('saveUser').addEventListener('click', function(e) {
        e.preventDefault(); // Mencegah submit form default
        const form = document.getElementById('userForm');
        const formData = new FormData(form); // Buat FormData untuk mengirim data form

        formData.append('flag', 'new-user'); // Atau 'update-user' jika untuk update


        Swal.fire({
            title: 'Apakah Anda yakin?',
            text: "Data ini akan disimpan!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Ya, simpan!',
            cancelButtonText: 'Batal'
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    url: form.action,
                    type: 'POST',
                    data: formData,
                    processData: false, // Jangan memproses data (untuk FormData)
                    contentType: false, // Jangan set content type (untuk FormData)
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')
                            .getAttribute('content')
                    },
                    success: function(response) {
                        if (response.success) {
                            Swal.fire({
                                title: "Sukses!",
                                text: "Entry berhasil disimpan!",
                                icon: "success",
                                showConfirmButton: false,
                                timer: 1500
                            });
                            $('#user-table').DataTable().ajax.reload(); 
                            $('#new-user').modal('hide'); 
                            form.reset();
                        } else {
                            Swal.fire(
                                'Gagal!',
                                response.message || 'Terjadi kesalahan saat menyimpan.',
                                'error'
                            );
                        }
                    },
                    error: function(xhr) {
                        const errorMessage = xhr.responseJSON?.message ||
                            'Terjadi kesalahan saat menyimpan.';
                        Swal.fire(
                            'Gagal!',
                            errorMessage,
                            'error'
                        );
                        console.error('Error:', xhr.responseText);
                    }
                });
            }
        });
    });
</script>
