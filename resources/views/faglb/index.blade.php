@section('title', 'UP-Doc FAGLB + ZLIS1')


<x-layout bodyClass="g-sidenav-show  bg-gray-200">
    <x-navbars.sidebar activePage="UPDOC"></x-navbars.sidebar>
    <main class="main-content position-relative max-height-vh-100 h-100 border-radius-lg ">
        <!-- Navbar -->
        <x-navbars.navs.auth titlePage=""></x-navbars.navs.auth>
        <!-- End Navbar -->
        <div class="container-fluid py-4">
            <div class="row">
                <div class="col-12">
                    <div class="card my-4">
                        <div class="card-header p-0 position-relative mt-n4 mx-3 z-index-2">
                            <div style="background-color: #3cb210;" class="shadow-primary border-radius-lg pt-4 pb-3">
                                <h3 class="text-white text-capitalize ps-3">Upload Document FAGLB + ZLIS1</h3>
                            </div>
                            <div class="card-body p-3">
                                <div class="d-flex mb-2">
                                    <!-- Tombol Add Doc Upload -->
                                    <button href="#" class="btn btn-primary"
                                        style="background-color: #09170a; border-color: #09170a;" data-bs-toggle="modal"
                                        data-bs-target="#addDocFormModal" data-url="{{ route('faglb.create') }}"> <i class="fa-solid fa-upload"></i> Upload Doc
                                    </button>

                                    <!-- Dropdown untuk filter status -->
                                    <div class="dropdown">
                                        <button class="btn btn-secondary dropdown-toggle ms-2" type="button"
                                            id="statusSelect" data-bs-toggle="dropdown" aria-expanded="false"
                                            style="background-color: #09170a;" data-value="">
                                            <!-- Default value kosong -->
                                            Semua Status
                                        </button>
                                        <ul class="dropdown-menu" aria-labelledby="statusSelect">
                                            <li><a class="dropdown-item text-center" href="#" data-value="">Semua
                                                    Status</a></li>
                                            <li><a class="dropdown-item text-center" href="#"
                                                    data-value="1">Released</a></li>
                                            <li><a class="dropdown-item text-center" href="#"
                                                    data-value="0">Unreleased</a></li>
                                        </ul>
                                    </div>
                                </div>

                                <div class="table-responsive p-0">
                                    <table id="faglb-table" class="table table-striped rounded-table p-0 mx-auto"
                                        style="width: 100%;">
                                        <thead>
                                            <tr>
                                                <th class="text-center">Action</th>
                                                <th class="text-center">ID Head</th>
                                                <th class="text-center">Period </th>
                                                <th class="text-center">Report Status</th>
                                                <th class="text-center">Created At</th>
                                                <th class="text-center">Updated At</th>
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

            @include('faglb.modal.add-doc-modal')
            @include('faglb.modal.update-doc-modal')

            @push('css')
                <link href="{{ asset('assets/datatables/dataTables.min.css') }}" rel="stylesheet">
            @endpush

            @push('js')
                <script src="{{ asset('assets/js/jquery.min.js') }}"></script>
                <script src="assets/js/moment.min.js"></script>
                <script src="assets/js/plugins/sweetalert.min.js"></script>
                <script src="{{ asset('assets/datatables/dataTables.min.js') }}"></script>
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
                        var table = $('#faglb-table').DataTable({
                            responsive: true,
                            processing: true,
                            serverSide: true,
                            order: [
                                [1, 'desc']
                            ],
                            ajax: {
                                url: "{{ route('faglb.index') }}",
                                type: "GET",
                                data: function(d) {
                                    var status = $('#statusSelect').attr(
                                    'data-value'); // Ambil status dari atribut data-value
                                    if (status) {
                                        d.status = status; // Kirimkan status ke server sebagai parameter
                                    }
                                }
                            },
                            columns: [{
                                    data: 'action',
                                    name: 'action',
                                    orderable: false,
                                    searchable: false,
                                    className: 'text-center',
                                    width: '15%'
                                },
                                {
                                    data: 'id_head',
                                    name: 'id_head'
                                },
                                {
                                    data: 'period',
                                    name: 'period',
                                    className: 'text-center',
                                    width: '15%'
                                },
                                {
                                    data: 'report_status',
                                    name: 'report_status',
                                    orderable: false,
                                    searchable: false,
                                    className: 'text-center',
                                    width: '20%'
                                },
                                {
                                    data: 'created_at',
                                    name: 'created_at',
                                    className: 'text-center',
                                    width: '25%',
                                    render: function(data) {
                                        return moment(data).format('YYYY-MM-DD ');
                                    }
                                },
                                {
                                    data: 'updated_at',
                                    name: 'updated_at',
                                    className: 'text-center',
                                    width: '25%',
                                    render: function(data) {
                                        return moment(data).format('YYYY-MM-DD ');
                                    }
                                }
                            ]
                        });
                        $(document).on("click", "#statusSelect + .dropdown-menu .dropdown-item", function(e) {
                            e.preventDefault();
                            const value = $(this).data("value");
                            const text = $(this).text();

                            // Update button dropdown
                            $("#statusSelect")
                                .text(text)
                                .attr("data-value", value);

                            table.ajax.reload(); // Reload table dengan filter baru
                        });



                        $("#faglb-table").on("click", ".delete-btn", function() {
                            var id = $(this).data("id"); // Ambil ID dari data-id


                            Swal.fire({
                                title: "Konfirmasi Hapus",
                                text: "Apakah Anda yakin ingin menghapus item ini?",
                                icon: "warning",
                                showCancelButton: true,
                                confirmButtonColor: "#3085d6",
                                cancelButtonColor: "#d33",
                                confirmButtonText: "OK",
                                cancelButtonText: 'Batal'
                            }).then((result) => {
                                if (result.isConfirmed) {
                                    // Jika pengguna mengkonfirmasi penghapusan
                                    $.ajax({
                                        url: "/faglb/" + id,
                                        type: "POST",
                                        data: {
                                            _method: "DELETE", // Laravel membutuhkan ini untuk metode DELETE
                                            _token: $('meta[name="csrf-token"]').attr(
                                            "content"), // Sertakan CSRF token
                                        },
                                        success: function(result) {
                                            // Menampilkan notifikasi sukses
                                            Swal.fire(
                                                "Terhapus!",
                                                "Item telah berhasil dihapus.",
                                                "success"
                                            );
                                            // Reload DataTable
                                            $("#faglb-table").DataTable().ajax.reload();
                                        },
                                        error: function(xhr) {
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
        margin-left: 250px;
        /* Memberikan ruang untuk sidebar */
        transition: margin-left 0.3s ease;
        /* Transisi saat sidebar dibuka/tutup */
    }

    .sidenav.closed~.main-content {
        margin-left: 0;
        /* Menghapus margin saat sidebar ditutup */
    }

    /* Responsif untuk tablet */
    @media (max-width: 991px) {
        .main-content {
            margin-left: 200px;
            /* Mengurangi margin untuk tablet */
        }
    }

    /* Responsif untuk mobile */
    @media (max-width: 767px) {
        .main-content {
            margin-left: 0;
            /* Hapus margin di mobile */
            padding: 10px;
            /* Mengurangi padding di mobile */
        }

        .sidenav.closed~.main-content {
            margin-left: 0;
            /* Pastikan konten utama tidak overlap saat sidebar ditutup */
        }

        /* Mengatur lebar tabel agar responsif */
        #faglb-table {
            width: 100%;
            /* Memastikan tabel mengambil 100% lebar */
        }
    }

    .rounded-table {
        border-radius: 12px;
        /* Adjust the radius as needed */
        overflow: hidden;
        /* Ensures child elements respect the border radius */
    }

    .rounded-table th,
    .rounded-table td {
        border: none;
        /* Remove default borders to maintain rounded appearance */
    }

    #faglb-table thead th {
        background-color: #3cb210;
        /* Warna latar belakang header */
        color: #ffffff;
        /* Warna teks header */
        width: auto;
        /* Atur lebar kolom header secara otomatis */
    }

    /* Gaya untuk baris tabel */
    #faglb-table tbody tr {
        transition: background-color 0.3s ease;
        /* Efek transisi untuk warna latar belakang */
        color: #2c2626;
    }

    /* Gaya untuk sel tabel */
    #faglb-table tbody td {
        padding: 10px;
        /* Padding untuk sel */
        border-bottom: 1px solid #dee2e6;
        /* Garis bawah sel */
        color: #2c2626;
    }

    /* Hover effect untuk baris tabel */
    #faglb-table tbody tr:hover {
        background-color: rgba(0, 123, 255, 0.1);
        /* Warna latar belakang saat hover */
    }

    #faglb-table th,
    #faglb-table td {
        padding: 8px;
        text-align: center;
    }

    .form-select {
        width: auto;
        /* Ubah sesuai kebutuhan */
        border-radius: 4px;
        /* Tambahkan sudut melengkung */
        border: 1px solid #ccc;
        /* Warna border */
        box-shadow: none;
        /* Hilangkan shadow default */
        background-color: #f9f9f9;
        /* Warna latar belakang */
        font-size: 15px;
        /* Ukuran teks */
    }

    /* Fokus pada dropdown */
    .form-select:focus {
        border-color: #42bd37;
        /* Warna border saat fokus */
        box-shadow: 0 0 5px rgba(66, 189, 55, 0.5);
        /* Efek shadow saat fokus */
    }

    /* Indikator urutan di header DataTables */
    th.sorting::after,
    th.sorting_asc::after,
    th.sorting_desc::after {
        content: '▼';
        /* Default panah ke bawah */
        font-size: 0.8em;
        margin-left: 0.5em;
        color: #007bff;
        /* Warna panah */
    }

    th.sorting_asc::after {
        content: '▲';
        /* Panah ke atas untuk urutan naik */
    }

    th.sorting_desc::after {
        content: '▼';
        /* Panah ke bawah untuk urutan turun */
    }
</style>
