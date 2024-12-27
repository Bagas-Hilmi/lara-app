<div class="modal fade" id="new-user" tabindex="-1" aria-labelledby="new-userLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header" style="background-color: #42bd37;">
                <h4 class="modal-title" id="new-userLabel" style="color: white;">Create New User</h4>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="userForm" action="{{ route('user-management.store') }}" method="POST">
                    @csrf
                    <input type="hidden" name="flag" value="new-user">

                    <div class="mb-3">
                        <label for="name" class="form-label"><strong>Name:</strong></label>
                        <input type="text" name="name" id="name" placeholder="Name" class="form-control" required>
                    </div>
                    
                    <div class="mb-3">
                        <label for="email" class="form-label"><strong>Email:</strong></label>
                        <input type="email" name="email" id="email" placeholder="Email" class="form-control" required>
                    </div>
                    
                    <div class="mb-3">
                        <label for="password" class="form-label"><strong>Password:</strong></label>
                        <input type="password" name="password" id="password" placeholder="Password" class="form-control" required>
                    </div>
                    
                    <div class="mb-3">
                        <label for="confirm-password" class="form-label"><strong>Confirm Password:</strong></label>
                        <input type="password" name="confirm-password" id="confirm-password" placeholder="Confirm Password" class="form-control" required>
                    </div>
                    
                    <div class="mb-3">
                        <label for="role" class="form-label"><strong>Role:</strong></label>
                        <select name="role" id="role" class="form-control" required>
                            <option value="">Pilih Role</option>
                            @foreach ($roles as $role)
                                <option value="{{ $role->id }}">{{ $role->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-success" id="saveUser">Submit</button>
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
