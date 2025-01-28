<div class="modal fade" id="new-user" tabindex="-1" aria-labelledby="new-userLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header" style="background-color: #42bd37; color: white; border-bottom: 2px solid #ddd;">
                <h4 class="modal-title" id="new-userLabel" style="color: white;"><strong>Create New User</strong></h4>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="userForm" action="{{ route('user-management.store') }}" method="POST">
                    @csrf
                    <input type="hidden" name="flag" value="new-user">

                    <!-- Name Input -->
                    <div class="mb-3">
                        <label for="name" class="form-label"><strong>Name :</strong></label>
                        <input type="text" name="name" id="name" placeholder="Enter name" class="form-control" required style="border-radius: 10px;">
                    </div>
                    
                    <!-- Email Input -->
                    <div class="mb-3">
                        <label for="email" class="form-label"><strong>Email :</strong></label>
                        <input type="email" name="email" id="email" placeholder="Enter email" class="form-control" required style="border-radius: 10px;">
                    </div>
                    
                    <!-- Password Input -->
                    <div class="mb-3">
                        <label for="password" class="form-label"><strong>Password :</strong></label>
                        <input type="password" name="password" id="password" placeholder="Enter password" class="form-control" required style="border-radius: 10px;">
                    </div>
                    
                    <!-- Confirm Password Input -->
                    <div class="mb-3">
                        <label for="confirm-password" class="form-label"><strong>Confirm Password :</strong></label>
                        <input type="password" name="confirm-password" id="confirm-password" placeholder="Confirm password" class="form-control" required style="border-radius: 10px;">
                    </div>
                    
                    <!-- Role Dropdown -->
                    <div class="mb-3">
                        <label for="role" class="form-label"><strong>Role :</strong></label>
                        <select name="role" id="role" class="form-control" style="width: 35%;" required>
                            <option value="">Select Role</option>
                            @foreach ($roles as $role)
                                <option value="{{ $role->id }}">{{ $role->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Modal Footer -->
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal" style="border-radius: 10px;">Close</button>
                        <button type="submit" class="btn bg-gradient-success" id="saveUser" style="border-radius: 10px;">Submit</button>
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

<script>
    $(document).ready(function() {
        // Inisialisasi Select2 pada elemen dengan class 'form-control'
        $('select.form-control').select2();
    });

    $(document).ready(function() {
        $('#role').select2({
            placeholder: "Pilih Role",
            allowClear: true
        });
    });

    $(document).ready(function() {
        $('#new-user').on('show.bs.modal', function () {
            $(this).find('form')[0].reset(); // Mereset form di dalam modal
        });
    });

</script>

<style>
    /* Styling untuk modal */
    .modal-content {
        border-radius: 12px;
    }

    /* Modal Footer */
    .modal-footer {
        padding-top: 15px;
    }

    .select2-container .select2-selection--single {
        height: 45px; /* Menyesuaikan tinggi agar lebih proporsional */
        padding-inline: 10px; /* Padding kiri dan kanan otomatis menyesuaikan dengan teks */
        font-size: 11pt; /* Ukuran font lebih besar untuk keterbacaan */
        border-radius: 8px; /* Membuat sudut lebih halus */
        border: 1px solid #ccc; /* Border abu-abu muda untuk kesan elegan */
        background-color: #ffffff; /* Latar belakang putih agar bersih */
        color: #000000; /* Warna teks abu-abu gelap untuk kontras yang baik */
        box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1); /* Menambahkan bayangan halus di sekitar dropdown */
        transition: all 0.3s ease; /* Menambahkan transisi halus saat berinteraksi */
        display: flex; /* Menjadikan container flex */
        align-items: center; /* Menyelaraskan teks di tengah secara vertikal */
        justify-content: space-between; /* Memastikan tombol x berada di sisi kanan */
    }

    /* Efek fokus pada select2 */
    .select2-container .select2-selection--single:focus {
        border-color: #3cff00; /* Mengubah border menjadi biru saat fokus */
        box-shadow: 0 0 5px rgba(0, 123, 255, 0.5); /* Menambahkan bayangan biru saat fokus */
        outline: none; /* Menghilangkan outline default */
    }
        .select2-container .select2-selection__clear {
        position: absolute;
        right: 10px; /* Menempatkan tombol "x" di sisi kanan */
    } 

</style>
