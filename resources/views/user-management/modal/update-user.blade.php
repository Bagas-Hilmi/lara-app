<div class="modal fade" id="update-user" tabindex="-1" aria-labelledby=update-userLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header" style="background-color: #42bd37;">
                <h4 class="modal-title" id="update-userLabel" style="color: white;">Create New User</h4>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="update-form" action="{{ route('user-management.store') }}"method="POST">
                    @csrf
                    <input type="hidden" name="flag" value="update-user">
                    <input type="hidden" name="id" id="userId">


                    <div class="mb-3">
                        <label for="name" class="form-label"><strong>Name:</strong></label>
                        <input type="text" name="name" placeholder="Name" class="form-control">
                    </div>

                    <div class="mb-3">
                        <label for="email" class="form-label"><strong>Email:</strong></label>
                        <input type="email" name="email" placeholder="Email" class="form-control">
                    </div>

                    <div class="mb-3">
                        <label for="password" class="form-label"><strong>Password:</strong></label>
                        <input type="password" name="password" placeholder="Password" class="form-control">
                    </div>

                    <div class="mb-3">
                        <label for="confirm-password" class="form-label"><strong>Confirm Password:</strong></label>
                        <input type="password" name="confirm-password" placeholder="Confirm Password"
                            class="form-control">
                    </div>

                    <div class="mb-3">
                        <label for="role" class="form-label"><strong>Role:</strong></label>
                        <select name="role" class="form-control">
                            @foreach ($roles as $role)
                                <option value="{{ $role->id }}">{{ $role->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="modal-footer">
                        <button type="button" class="btn bg-gradient-secondary"data-bs-dismiss="modal">Close</button>
                        <button type="submit" class="btn bg-gradient-success">Submit</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
    $(document).on('click', '.update-btn', function() {
        const userId = $(this).data('id');
        const userName = $(this).data('name');
        const userEmail = $(this).data('email');
        const userRole = $(this).data('role'); // Ambil data role

        // Isi data ke dalam form di modal
        $('#update-form').find('input[name="name"]').val(userName);
        $('#update-form').find('input[name="email"]').val(userEmail);
        $('#update-form').find('input[name="password"]').val('');
        $('#update-form').find('input[name="confirm-password"]').val('');
        $('#update-form').append(`<input type="hidden" name="id" value="${userId}">`);
        $('#update-form').find('select[name="role"]').val(userRole); // Set role yang sudah dipilih


        // Tampilkan modal
        $('#update-user').modal('show');
        $('#update-form').on('submit', function(e) {
            e.preventDefault();

            const form = $(this);
            const formData = new FormData(this);

            Swal.fire({
                title: 'Apakah Anda yakin?',
                text: "Data akan diperbarui!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Ya, perbarui!',
                cancelButtonText: 'Batal'
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        url: form.attr('action'),
                        type: 'POST',
                        data: formData,
                        processData: false,
                        contentType: false,
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        },
                        success: function(response) {
                            if (response.success) {
                                Swal.fire({
                                    title: "Sukses!",
                                    text: response.message,
                                    icon: "success",
                                    showConfirmButton: false,
                                    timer: 1500
                                });
                                $('#user-table').DataTable().ajax
                                    .reload(); // Reload DataTable
                                $('#update-user').modal('hide'); // Tutup modal
                            } else {
                                Swal.fire('Gagal!', response.message ||
                                    'Terjadi kesalahan.', 'error');
                            }
                        },
                        error: function(xhr) {
                            Swal.fire('Gagal!', 'Terjadi kesalahan pada server.',
                                'error');
                            console.error('Error:', xhr.responseText);
                        }
                    });
                }
            });
        });

    });
</script>
