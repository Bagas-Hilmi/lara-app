<!-- Modal -->
<div class="modal fade" id="addDocFormModal" tabindex="-1" aria-labelledby="addDocFormLabel">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header" style="background-color: #42bd37;">
                <h5 class="modal-title" id="addDocFormLabel" style="color: white;">Upload Document</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                
                <form id="addDocForm" action="{{ route('faglb.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <input type="hidden" name="period" id="selectedPeriod" value="">
                    <input type="hidden" name="id_ccb" id="selectedIdCcb" value="">
                    <input type="hidden" name="flag" value="upload_documents">

                    <div class="container-fluid">
                        <div class="dropdown mb-3">
                            <button class="btn btn-secondary dropdown-toggle" type="button" id="periodDropdown" data-bs-toggle="dropdown" aria-expanded="false" required>
                                Pilih Period
                            </button>
                            <ul class="dropdown-menu" id="periodList" aria-labelledby="periodDropdown" >
                                <!-- pake AJAX -->
                            </ul>
                        </div>
                        
                        <div class="row mb-3">
                            <div class="col-md-12">
                                <label class="form-label" for="faglb">FAGLB</label>
                                <input type="file" class="form-control custom-file-input" id="faglb" name="faglb" accept=".xlsx,.xls,.csv" required>
                            </div>
                        </div>
                        <div class="row mb-3">
                            <div class="col-md-12">
                                <label class="form-label" for="zlis1">ZLIS1</label>
                                <input type="file" class="form-control custom-file-input" id="zlis1" name="zlis1" accept=".xlsx,.xls,.csv" required>
                            </div>
                        </div>
                    </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn bg-gradient-secondary" data-bs-dismiss="modal">Cancel</button>
                <button type="submit" class="btn bg-gradient-success" id="uploadDoc">Upload</button>
            </div>
                </form>
        </div>
    </div>
</div>

<script>
    document.getElementById('addDocForm').addEventListener('submit', function (event) {
        event.preventDefault(); // Mencegah pengiriman form default

        // Cek apakah pengguna telah memilih periode
        var selectedPeriod = document.getElementById('selectedPeriod').value;
        if (!selectedPeriod) {
            Swal.fire({
                icon: 'warning',
                title: 'Peringatan!',
                text: 'Silakan pilih periode sebelum melanjutkan.',
                confirmButtonText: 'OK'
            });
            return; // Hentikan pengiriman form jika periode tidak dipilih
        }
        
        // Tampilkan konfirmasi SweetAlert
        Swal.fire({
            title: 'Konfirmasi',
            text: 'Apakah Anda ingin mengunggah dokumen ini?',
            type: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Ya, unggah!',
            cancelButtonText: 'Batal'
        }).then((result) => {
            if (result.isConfirmed) {
                // Menggunakan Fetch API untuk mengirim data
                const formData = new FormData(this);

                fetch(this.action, {
                    method: 'POST',
                    body: formData,
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('input[name="_token"]').value // Tambahkan token CSRF
                    }
                })
                .then(response => response.json())
                .then(data => {
                    // Cek status respon
                    if (data.success) {
                        Swal.fire({
                            icon: 'success',
                            title: 'Sukses!',
                            text: data.message, // Tampilkan pesan sukses
                            confirmButtonText: 'OK'
                        }).then(() => {
                            // Menyegarkan atau menutup modal jika diperlukan
                            location.reload(); // Segarkan halaman untuk melihat perubahan
                        });
                    } else {
                        Swal.fire({
                            icon: 'error',
                            title: 'Oops...',
                            text: data.message, // Tampilkan pesan kesalahan
                            confirmButtonText: 'Coba Lagi'
                        });
                    }
                })
                .catch(error => {
                    Swal.fire({
                        icon: 'error',
                        title: 'Oops...',
                        text: 'Terjadi kesalahan! Silakan coba lagi.', // Tampilkan pesan kesalahan umum
                        confirmButtonText: 'OK'
                    });
                });
            }
        });
    });
</script>


<style>
    /* Container for month input */
    .month-input-container {
        display: inline-block;
        border-radius: 4px;
        padding: 5px; /* Kurangi padding */
        background-color: #f9f9f9;
        box-shadow: 0 2px 4px rgba(255, 255, 255, 0.1);
    }

    .custom-file-input {
        border: 1px solid #ccc; /* Customize the border */
        box-shadow: none; /* Remove shadow */
        border-radius: 4px; /* Tambahkan sudut melengkung */
        padding: 10px; /* Menambah padding untuk input */
    }

    .custom-file-input:focus {
        border-color: #42bd37; /* Warna border saat fokus */
        box-shadow: 0 0 5px rgba(66, 189, 55, 0.5); /* Menambah efek shadow saat fokus */
    }

    .modal-body .form-label {
        font-weight: bold; /* Make labels bold */
        margin-bottom: 0.5rem; /* Jarak antara label dan input */
    }

    .modal-body .input-group {
        margin-bottom: 1rem; /* Space between input groups */
    }

    /* Styling untuk input file */
    .custom-file-input {
        cursor: pointer; /* Mengubah pointer saat hover */
    }
    .form-control {
        border: 1px solid #ccc; /* Customize the border */
        box-shadow: none; /* Remove shadow */
        border-radius: 4px; /* Tambahkan sudut melengkung */
    }
    .form-control:focus {
        border-color: #42bd37; /* Warna border saat fokus */
        box-shadow: 0 0 5px rgba(66, 189, 55, 0.5); /* Menambah efek shadow saat fokus */
    }
</style>


    
    
