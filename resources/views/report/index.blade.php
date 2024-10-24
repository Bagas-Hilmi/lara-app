@section('title', 'Report CIP')


<x-layout bodyClass="g-sidenav-show  bg-gray-200">
    <x-navbars.sidebar activePage="report"></x-navbars.sidebar>
    <main class="main-content position-relative max-height-vh-100 h-100 border-radius-lg ">
        <!-- Navbar -->
        <x-navbars.navs.auth titlePage=""></x-navbars.navs.auth>
        <!-- End Navbar -->
        <div class="container-fluid py-4">
            <div class="row">
                <div class="col-15">
                    <div class="card my-4">
                        <div class="card-header p-0 position-relative mt-n4 mx-3 z-index-2">
                            <div style="background-color: #3cb210;" class="shadow-primary border-radius-lg pt-4 pb-3"> 
                                <h3 class="text-white text-capitalize ps-3">Report CIP</h3>
                            </div>
                            <div class="card-body p-3">
                                <div class="mb-2">
                                    <div class="dropdown">
                                        <button class="btn btn-secondary dropdown-toggle" style="background-color: #09170a; border-color: #09170a;" type="button" id="descriptionDropdown" data-bs-toggle="dropdown" aria-expanded="false">
                                            <span id="descriptionText">Pilih Capex </span> <!-- Placeholder -->
                                        </button>
                                        <ul class="dropdown-menu" aria-labelledby="descriptionDropdown">
                                            <li><a class="dropdown-item" href="#" data-value="">Pilih Capex </a></li>
                                            @foreach($descriptions as $desc)
                                                <li>
                                                    <a class="dropdown-item" href="#" 
                                                       data-value="{{ $desc->id_capex }}" 
                                                       data-cip="{{ $desc->cip_number }}" 
                                                       data-wbs="{{ $desc->wbs_number }}"
                                                       data-project_desc="{{ $desc->project_desc }}"
                                                       data-budget_type="{{ $desc->budget_type }}"
                                                       data-amount_budget="{{ $desc->amount_budget }}">
                                                       {{ $desc->capex_number }} <!-- Kolom capex_number yang ditampilkan -->
                                                    </a>
                                                </li>
                                            @endforeach
                                        </ul>
                                    
                                        <button class="btn btn-secondary" id="aButton" style="background-color: #09170a; border-color: #09170a;" disabled>
                                            <span id="cipText">CIP Number</span> 
                                        </button>
                                        
                                        <button class="btn btn-secondary" id="aButton" style="background-color: #09170a; border-color: #09170a;" disabled>
                                            <span id="wbsText">WBS Number</span> 
                                        </button>

                                        <button class="btn btn-secondary" id="aButton" style="background-color: #09170a; border-color: #09170a;"disabled >
                                            <span id="projectText">project desc</span> 
                                        </button>

                                        <button class="btn btn-secondary" id="aButton" style="background-color: #09170a; border-color: #09170a;" disabled>
                                            <span id="budgetText">budget type</span> 
                                        </button>

                                        <button class="btn btn-secondary" id="aButton" style="background-color: #09170a; border-color: #09170a;" disabled>
                                            <span id="amountText">Amount Budget</span>
                                        </button>

                                    </div>
                                </div>
                                <div class="table-responsive p-0">
                                    <table id="report-table" class="table table-striped nowrap rounded-table p-0" style="width:100%">
                                        <thead>
                                            <tr>
                                                <th align="center">ID Report CIP</th>
                                                <th align="center">ID Capex</th>
                                                <th align="center">ID Head</th>
                                                <th align="center">FA Doc </th>
                                                <th align="center">Date </th>
                                                <th align="center">Settle Doc</th>
                                                <th align="center">Material</th>
                                                <th align="center">DESCRIPTION</th>
                                                <th align="center">QTY</th>
                                                <th align="center">UOM</th>
                                                <th align="center">AMOUNT (RP)</th>
                                                <th align="center">AMOUNT (US$)</th>
                                                <th align="center">Created At</th>
                                                <th align="center">Updated At</th>
                                            </tr>
                                        </thead>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <x-footers.auth></x-footers.auth>
            </div>


            @push('js')
            <script src="{{ asset('assets/js/jquery.min.js') }}"></script>
            <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.all.min.js"></script>
            <script src="{{ asset('assets/datatables/dataTables.min.js') }}"></script>
            <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.29.1/moment.min.js"></script>
            <script src="{{ asset('/js/tooltip.js') }}"></script>

            <script>
                $(document).ready(function() {
                    // Setup CSRF token for AJAX requests
                    $.ajaxSetup({
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        }
                    });
            
                    // Initialize DataTable
                    var table = $('#report-table').DataTable({
                        responsive: false,
                        processing: true,
                        serverSide: true,
                        order: [[1, 'desc']],
                        ajax: {
                            url: "{{ route('report.index') }}",
                            type: "GET",
                            data: function (d) {
                            // Mengatur parameter pencarian berdasarkan dropdown yang dipilih
                            d.searchValue = document.getElementById('descriptionText').textContent; // Ganti dengan ID yang sesuai
                        }
                        },
                        columns: [
                            {data: 'id_report_cip', name: 'id_report_cip', className: 'text-center'},
                            {data: 'id_capex', name: 'id_capex', className: 'text-center'},
                            {data: 'id_head', name: 'id_head', className: 'text-center'},
                            {data: 'fa_doc', name: 'fa_doc', className: 'text-right'},
                            {data: 'date', name: 'date', className: 'text-right'},
                            {data: 'settle_doc', name: 'settle_doc', className: 'text-right'},
                            {data: 'material', name: 'material', className: 'text-right'},
                            {data: 'description', name: 'description', className: 'text-center'},
                            {data: 'qty', name: 'qty', className: 'text-right'},
                            {data: 'uom', name: 'uom', className: 'text-center'},
                            {data: 'amount_rp', name: 'amount_rp', className: 'text-right'},
                            {data: 'amount_us', name: 'amount_us', className: 'text-right'},
                            {
                                data: 'created_at', 
                                name: 'created_at', 
                                className: 'text-center', 
                                render: function(data) {
                                    return moment(data).format('YYYY-MM-DD');
                                }
                            },
                            {
                                data: 'updated_at', 
                                name: 'updated_at', 
                                className: 'text-center', 
                                render: function(data) {
                                    return moment(data).format('YYYY-MM-DD');
                                }
                            }
                        ]
                    });
                    
                      // Fungsi untuk memformat angka dengan tanda pemisah ribuan
                        function formatNumber(num) {
                            return num.toString().replace(/\B(?=(\d{3})+(?!\d))/g, "."); // Memformat angka dengan titik
                        }
                    // Ambil semua item dropdown
                    const dropdownItems = document.querySelectorAll('.dropdown-item');
                    dropdownItems.forEach(item => {
                        item.addEventListener('click', function(event) {
                            event.preventDefault(); // Mencegah link melakukan refresh halaman
                            
                            // Ambil nilai dari data-value, data-cip, dan data-wbs
                            const value = this.getAttribute('data-value');
                            const text = this.textContent; // Ambil teks dari item dropdown
                            const cipNumber = this.getAttribute('data-cip');
                            const wbsNumber = this.getAttribute('data-wbs');
                            const project = this.getAttribute('data-project_desc');
                            const budget = this.getAttribute('data-budget_type');
                            const amount = this.getAttribute('data-amount_budget');
                            
                            // Perbarui teks pada tombol dropdown
                            document.getElementById('descriptionText').textContent = text;

                            // Mengubah teks pada tombol cip dan wbs
                            document.getElementById('cipText').innerText = cipNumber ? cipNumber : 'CIP Number';
                            document.getElementById('wbsText').innerText = wbsNumber ? wbsNumber : 'WBS Number';
                            document.getElementById('projectText').innerText = project ? project : 'Project';
                            document.getElementById('budgetText').innerText = budget ? budget : 'Budget';
                            document.getElementById('amountText').innerText = amount ? formatNumber(amount) : 'Amount'; // Format amount budget

                             // Mengatur filter DataTable berdasarkan value
                            table.column(1).search(value).draw(); // Ganti 0 dengan indeks kolom yang sesuai

                            // Reload DataTable
                            table.ajax.reload(); // Memanggil reload untuk mendapatkan data terbaru
                        });
                    });

                    

                    $("#report-table").on("click", ".delete-btn", function () {
                        var id = $(this).data("id"); // Ambil ID dari data-id

                        // Tampilkan SweetAlert untuk konfirmasi penghapusan
                        Swal.fire({
                            title: "Konfirmasi Hapus",
                            text: "Apakah Anda yakin ingin menghapus item ini?",
                            icon: "warning", // Tampilkan ikon peringatan
                            showCancelButton: true, // Tampilkan tombol batal
                            confirmButtonColor: "#3085d6", // Warna tombol konfirmasi
                            cancelButtonColor: "#d33", // Warna tombol batal
                            confirmButtonText: "Ya, hapus!",
                            cancelButtonText: "Batal",
                        }).then((result) => {
                            if (result.isConfirmed) {
                                // Jika pengguna mengkonfirmasi penghapusan
                                $.ajax({
                                    url: "/report/" + id,
                                    type: "POST",
                                    data: {
                                        _method: "DELETE", // Laravel membutuhkan ini untuk metode DELETE
                                        _token: $('meta[name="csrf-token"]').attr("content"), // Sertakan CSRF token
                                    },
                                    success: function (result) {
                                        // Menampilkan notifikasi sukses
                                        Swal.fire(
                                            "Terhapus!",
                                            "Item telah berhasil dihapus.",
                                            "success"
                                        );
                                        // Reload DataTable
                                        $("#report-table").DataTable().ajax.reload();
                                    },
                                    error: function (xhr) {
                                        // Menampilkan notifikasi jika terjadi error
                                        Swal.fire(
                                            "Gagal!",
                                            "Terjadi kesalahan saat menghapus item.",
                                            "error"
                                        );
                                    },
                                });
                            }
                        });
                    });

                });
            </script>
            @endpush     
        </div>
    </main>
</x-layout>


<style>
    .main-content {
        margin-left: 250px; /* Memberikan ruang untuk sidebar */
        transition: margin-left 0.3s ease; /* Transisi saat sidebar dibuka/tutup */
    }

    .sidenav.closed ~ .main-content {
        margin-left: 0; /* Menghapus margin saat sidebar ditutup */
    }

    /* Responsif untuk tablet */
    @media (max-width: 991px) {
        .main-content {
            margin-left: 200px; /* Mengurangi margin untuk tablet */
        }
    }

    /* Responsif untuk mobile */
    @media (max-width: 767px) {
        .main-content {
            margin-left: 0; /* Hapus margin di mobile */
            padding: 10px; /* Mengurangi padding di mobile */
        }

        .sidenav.closed ~ .main-content {
            margin-left: 0; /* Pastikan konten utama tidak overlap saat sidebar ditutup */
        }

        /* Mengatur lebar tabel agar responsif */
        #report-table {
            width: 100%; /* Memastikan tabel mengambil 100% lebar */
        }
    }
    
    .rounded-table {
        border-radius: 12px; /* Adjust the radius as needed */
        overflow: hidden; /* Ensures child elements respect the border radius */
    }
    .rounded-table th,
    .rounded-table td {
        border: none; /* Remove default borders to maintain rounded appearance */
    }

    #report-table thead th {
    background-color: #3cb210; /* Warna latar belakang header */
    color: #ffffff; /* Warna teks header */
    }
    
    #budget-table thead th {
    background-color: #3cb210; /* Warna latar belakang header */
    color: #ffffff; /* Warna teks header */
    }

    /* Gaya untuk baris tabel */
    #report-table tbody tr {
        transition: background-color 0.3s ease; /* Efek transisi untuk warna latar belakang */
    }   

    /* Gaya untuk sel tabel */
    #report-table tbody td {
        padding: 10px; /* Padding untuk sel */
        border-bottom: 1px solid #dee2e6; /* Garis bawah sel */
    }

    /* Hover effect untuk baris tabel */
    #report-table tbody tr:hover {
        background-color: rgba(0, 123, 255, 0.1); /* Warna latar belakang saat hover */
    }
    #report-table th, #report-table td {
        padding: 8px;
        text-align: center;
    }
    .form-select {
        width: auto; /* Ubah sesuai kebutuhan */
        border-radius: 4px; /* Tambahkan sudut melengkung */
        border: 1px solid #ccc; /* Warna border */
        box-shadow: none; /* Hilangkan shadow default */
        background-color: #f9f9f9; /* Warna latar belakang */
        font-size: 15px; /* Ukuran teks */
    }

    /* Fokus pada dropdown */
    .form-select:focus {
        border-color: #42bd37; /* Warna border saat fokus */
        box-shadow: 0 0 5px rgba(66, 189, 55, 0.5); /* Efek shadow saat fokus */
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

    #aButton[disabled] {
        background-color: #09170a; /* Warna latar belakang yang sama */
        border-color: #09170a; /* Warna border yang sama */
        color: rgb(255, 255, 255); /* Warna teks yang sama */
        opacity: 1; /* Opasitas penuh */
        cursor: not-allowed; /* Kursor tidak diperbolehkan */
    }

</style>
