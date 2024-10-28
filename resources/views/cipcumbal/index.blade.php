@section('title', 'Cip Cumulative Balance')


<x-layout bodyClass="g-sidenav-show  bg-gray-200">
    <x-navbars.sidebar activePage="Cip Cumulative Balance"></x-navbars.sidebar>
    <main class="main-content position-relative max-height-vh-100 h-100 border-radius-lg ">
        <!-- Navbar -->
        <x-navbars.navs.auth titlePage=""></x-navbars.navs.auth>
        <!-- End Navbar -->
        <div class="container-fluid py-4">
            <div class="row">
                <div class="col-12">
                    <div class="card my-4">
                        <div class="card-header p-0 position-relative mt-n4 mx-3 z-index-2">
                            <div style="background-color: #31B005;" class="shadow-primary border-radius-lg pt-4 pb-3">
                                <h2 class="text-white text-capitalize ps-3">Cip Cumulative Balance</h2>
                            </div>
                            <div class="card-body p-3">
                                <div class="d-flex justify-content-between mb-2">
                                    <button href="#" class="btn btn-sm btn-primary" style="background-color: #09170a; border-color: #09170a;"  data-bs-toggle="modal" data-bs-target="#new-form">New Entry</button>
                                    <div class="dropdown">
                                        <button class="btn btn-secondary dropdown-toggle" style="background-color: #09170a; border-color: #09170a;" type="button" id="yearDropdown" data-bs-toggle="dropdown" aria-expanded="false">
                                            Pilih Tahun
                                        </button>
                                        <ul class="dropdown-menu" aria-labelledby="yearDropdown">
                                            <li><a class="dropdown-item" href="#" data-value="">Semua Tahun</a></li>
                                            @foreach ($availableYears as $year)
                                                <li><a class="dropdown-item" href="#" data-value="{{ $year }}">{{ $year }}</a></li>
                                            @endforeach
                                        </ul>
                                    </div>
                                </div>
                                <input type="hidden" id="yearFilter" name="year" value="">

                                <div class="table-responsive p-0">
                                    <table id="cipCumBalTable" class="table table-striped nowrap rounded-table p-0" style="width:100%">
                                        <thead>
                                            <tr>
                                                <th class="text-center">Action</th>
                                                <th class="text-center">ID</th>
                                                <th class="text-center">Period CIP</th>
                                                <th class="text-center">Bal USD</th>
                                                <th class="text-center">Bal RP</th>
                                                <th class="text-center">Cumbal USD</th>
                                                <th class="text-center">Cumbal RP</th>
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

            @include('cipcumbal.modal.new-entry')
            @include('cipcumbal.modal.update-entry')

            @push('css')
            <link href="{{ asset('assets/datatables/dataTables.min.css') }}" rel="stylesheet">
            <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
            @endpush

            @push('js')
            <script src="{{ asset('assets/js/jquery.min.js') }}"></script>
            <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.all.min.js"></script>
            <script src="{{ asset('assets/datatables/dataTables.min.js') }}"></script>
            <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.29.1/moment.min.js"></script>
            <script src="{{ asset('/js/tooltip.js') }}"></script>

            <script>
               $(document).ready(function() {
                    $.ajaxSetup({
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        }
                    });

                    // Inisialisasi DataTable
                    var table = $('#cipCumBalTable').DataTable({
                        responsive: true,
                        processing: true,
                        serverSide: true,
                        order: [[8, 'desc']],
                        ajax: {
                            url: "{{ route('cipcumbal.index') }}",
                            type: 'GET',
                            data: function (d) {
                                d.status = 1; 
                                d.year = $('#yearFilter').val(); 
                            }
                        },
                        columns: [
                            { data: 'action', name: 'action', orderable: false, searchable: false, className: 'text-center' },
                            { data: 'id_ccb', name: 'id_ccb', className: 'text-center' },
                            { data: 'period_cip', name: 'period_cip', className: 'text-center' },
                            
                            {
                                data: 'bal_usd', 
                                name: 'bal_usd', 
                                className: 'text-right', 
                                render: function(data, type) {
                                    if (type === 'display') {
                                        return '<div style="text-align: right;">' + parseFloat(data).toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",") + '</div>';
                                    }
                                    return data;
                                }
                            },
                            {
                                data: 'bal_rp', 
                                name: 'bal_rp', 
                                className: 'text-right', 
                                render: function(data, type) {
                                    if (type === 'display') {
                                        return '<div style="text-align: right;">' + parseFloat(data).toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",") + '</div>';
                                    }
                                    return data;
                                }
                            },
                            {
                                data: 'cumbal_usd', 
                                name: 'cumbal_usd', 
                                className: 'text-right', 
                                render: function(data, type) {
                                    if (type === 'display') {
                                        return '<div style="text-align: right;">' + parseFloat(data).toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",") + '</div>';
                                    }
                                    return data;
                                }
                            },
                            {
                                data: 'cumbal_rp', 
                                name: 'cumbal_rp', 
                                className: 'text-right', 
                                render: function(data, type) {
                                    if (type === 'display') {
                                        return '<div style="text-align: right;">' + parseFloat(data).toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",") + '</div>';
                                    }
                                    return data;
                                }
                            },
                            
                            { data: 'report_status', name: 'report_status', className: 'text-center' },
                            {
                                data: 'created_at', 
                                name: 'created_at', 
                                className: 'text-center', 
                                render: function(data) {
                                    return moment(data).format('YYYY-MM-DD HH:mm:ss'); // jika menggunakan moment.js
                                }
                            },
                            {
                                data: 'updated_at', 
                                name: 'updated_at', 
                                className: 'text-center', 
                                render: function(data) {
                                    return moment(data).format('YYYY-MM-DD HH:mm:ss'); // jika menggunakan moment.js
                                }
                            }
                        ]

                    });

                    // Event listener untuk filter tahun
                    $('.dropdown-item').click(function() {
                        var year = $(this).data('value'); // Ambil nilai tahun
                        $('#yearFilter').val(year); // Set nilai tahun ke input tersembunyi
                        
                        // Ubah teks tombol untuk menampilkan tahun yang dipilih
                        if (year) {
                            $('#yearDropdown').text(year); // Jika ada tahun yang dipilih
                        } else {
                            $('#yearDropdown').text('Pilih Tahun'); // Jika semua tahun dipilih
                        } 

                        table.ajax.reload(); // Reload DataTable
                    });

                    $('#cipCumBalTable').on('click', '.delete-btn', function() {
                        var id = $(this).data('id');

                        // Menampilkan SweetAlert untuk konfirmasi
                        Swal.fire({
                            title: 'Konfirmasi Hapus',
                            text: 'Apakah Anda yakin ingin menghapus item ini?',
                            icon: 'warning',
                            showCancelButton: true,
                            confirmButtonColor: '#3085d6',
                            cancelButtonColor: '#d33',
                            confirmButtonText: 'Ya, hapus!',                            
                            position: 'center'
                        }).then((result) => {
                            if (result.isConfirmed) {
                                
                                $.ajax({
                                    url: '/cipcumbal/' + id,
                                    type: 'DELETE',
                                    success: function(result) {
                                        // Menampilkan notifikasi sukses
                                        Swal.fire(
                                            'Terhapus!',
                                            'Item telah berhasil dihapus.',
                                            'success'
                                        );
                                        $('#cipCumBalTable').DataTable().ajax.reload();

                                    },
                                    error: function(xhr, status, error) {
                                        // Menampilkan notifikasi error jika penghapusan gagal
                                        Swal.fire(
                                            'Gagal!',
                                            'Terjadi kesalahan saat menghapus item.',
                                            'error'
                                        );
                                    }
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
        #faglb-table {
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
    #cipCumBalTable {
        border-collapse: collapse;
        width: 100%;
    }
    #cipCumBalTable th, #cipCumBalTable td {
        padding: 8px;
        text-align: center;
    }
    #cipCumBalTable thead th {
        background-color: #31B005;
        color: #ffffff;
    }
    .delete-btn {
        color: rgb(255, 255, 255);
        cursor: pointer;
    }
    #cipCumBalTable tbody tr {
    transition: background-color 0.3s ease; /* Efek transisi untuk warna latar belakang */
    }
    
    #cipCumBalTable tbody tr:hover {
    background-color: rgba(0, 123, 255, 0.1); /* Warna latar belakang saat hover */
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
</style>

