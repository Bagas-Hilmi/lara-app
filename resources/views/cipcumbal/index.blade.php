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
                                <div class="d-flex mb-2">
                                    <button href="#" class="btn btn-sm btn-primary"
                                        style="background-color: #09170a; border-color: #09170a;" data-bs-toggle="modal"
                                        data-bs-target="#new-form">New Entry
                                    </button>
                                    <div class="dropdown ms-2">
                                        <button class="btn btn-secondary dropdown-toggle"
                                            style="background-color: #09170a; border-color: #09170a;" type="button"
                                            id="periodDropdown" data-bs-toggle="dropdown" aria-expanded="false">
                                            <span>Pilih Periode</span>
                                        </button>

                                        <ul class="dropdown-menu" aria-labelledby="periodDropdown">
                                            @foreach ($periodRelease as $period)
                                                <li>
                                                    <a class="dropdown-item" href="#"
                                                        data-period="{{ $period->period_cip }}"
                                                        data-total="{{ $period->total_release }}">
                                                        {{ $period->period_cip }}
                                                    </a>
                                                </li>
                                            @endforeach
                                        </ul>
                                    </div>
                                    <div class="dropdown ms-2">
                                        <button class="btn btn-secondary dropdown-toggle"
                                            style="background-color: #09170a; border-color: #09170a;" type="button"
                                            id="yearDropdown" data-bs-toggle="dropdown" aria-expanded="false">
                                            <span id="yearText"></span>
                                        </button>
                                        <ul class="dropdown-menu" aria-labelledby="yearDropdown">
                                            </li>
                                            @foreach ($availableYears as $year)
                                                <li><a class="dropdown-item" href="#"
                                                        data-value="{{ $year }}">{{ $year }}</a></li>
                                            @endforeach
                                        </ul>
                                    </div>
                                </div>
                                <div class="info-box-container">        
                                    <div class="info-box">
                                        <div class="info-box-label">Jumlah Release Tahun <span id="releaseYear"></div>
                                        <div class="info-box-value" id="totalReleaseValue">-</div>
                                    </div>
                                </div>

                                <input type="hidden" id="yearFilter" name="year" value="">

                                <div class="table-responsive p-0">
                                    <table id="cipCumBalTable" class="table table-striped nowrap rounded-table p-0"
                                        style="width:100%">
                                        <thead>
                                            <tr>
                                                <th class="text-center">Action</th>
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
        </div>
    </main>
    @include('cipcumbal.modal.new-entry')
    @include('cipcumbal.modal.update-entry')

    @push('js')
        <script src="{{ asset('assets/js/jquery.min.js') }}"></script>
        <script src="assets/js/plugins/sweetalert.min.js"></script>
        <script src="{{ asset('assets/datatables/dataTables.min.js') }}"></script>
        <script src="assets/js/moment.min.js"></script>
        <script src="{{ asset('/js/tooltip.js') }}"></script>

        <script>
            $(document).ready(function() {
                const currentYear = new Date().getFullYear();
                $('#yearFilter').val(currentYear);
                $('#yearText').text(currentYear);

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
                    order: [
                        [7, 'desc']
                    ],
                    ajax: {
                        url: "{{ route('cipcumbal.index') }}",
                        type: 'GET',
                        data: function(d) {
                            d.status = 1;
                            d.year = $('#yearFilter').val();
                        }
                    },
                    columns: [{
                            data: 'action',
                            name: 'action',
                            orderable: false,
                            searchable: false,
                            className: 'text-center'
                        },
                        {
                            data: 'period_cip',
                            name: 'period_cip',
                            className: 'text-center'
                        },

                        {
                            data: 'bal_usd',
                            name: 'bal_usd',
                            className: 'text-right',
                            render: function(data, type) {
                                if (type === 'display') {
                                    return '<div style="text-align: right;">' + parseFloat(data)
                                        .toString().replace(/\B(?=(\d{3})+(?!\d))/g, ".") + '</div>';
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
                                    return '<div style="text-align: right;">' + parseFloat(data)
                                        .toString().replace(/\B(?=(\d{3})+(?!\d))/g, ".") + '</div>';
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
                                    return '<div style="text-align: right;">' + parseFloat(data)
                                        .toString().replace(/\B(?=(\d{3})+(?!\d))/g, ".") + '</div>';
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
                                    return '<div style="text-align: right;">' + parseFloat(data)
                                        .toString().replace(/\B(?=(\d{3})+(?!\d))/g, ".") + '</div>';
                                }
                                return data;
                            }
                        },

                        {
                            data: 'report_status',
                            name: 'report_status',
                            className: 'text-center'
                        },
                        {
                            data: 'created_at',
                            name: 'created_at',
                            className: 'text-center',
                            render: function(data) {
                                const date = new Date(data);
                                const year = date.getFullYear();
                                const month = String(date.getMonth() + 1).padStart(2, '0');
                                const day = String(date.getDate()).padStart(2, '0');
                                return `${year}-${month}-${day}`;
                            }
                        },
                        {
                            data: 'updated_at',
                            name: 'updated_at',
                            className: 'text-center',
                            render: function(data) {
                                return moment(data).format(
                                    'YYYY-MM-DD'); // jika menggunakan moment.js
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

                    table.ajax.reload(); 
                });

                document.querySelectorAll('.dropdown-item').forEach(item => {
                    item.addEventListener('click', function(e) {
                        e.preventDefault();
                        const period = this.dataset.period;
                        const total = this.dataset.total;

                        // Update dropdown text
                        document.querySelector('#periodDropdown span').textContent = period;

                        document.querySelector('#releaseYear').textContent = period;

                        // Update info box value
                        document.querySelector('#totalReleaseValue').textContent = total;
                    });
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
                        confirmButtonText: 'OK!',
                        cancelButtonText: 'Batal',
                        position: 'center'
                    }).then((result) => {
                        if (result.isConfirmed) {

                            $.ajax({
                                url: '/cipcumbal/' + id,
                                type: 'DELETE',
                                success: function(result) {
                                    // Menampilkan notifikasi sukses
                                    Swal.fire({
                                            title: "Terhapus!",
                                            text: "Entry has been deleted!",
                                            icon: "success",
                                            showConfirmButton: false,
                                            timer: 1000
                                    });
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

    #cipCumBalTable {
        border-collapse: collapse;
        width: 100%;
    }

    #cipCumBalTable th,
    #cipCumBalTable td {
        padding: 8px;
        text-align: center;
        color: #2c2626;

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
        transition: background-color 0.3s ease;
        /* Efek transisi untuk warna latar belakang */
                color: #2c2626;
    }

    #cipCumBalTable tbody tr:hover {
        background-color: rgba(0, 123, 255, 0.1);
        /* Warna latar belakang saat hover */
    }

    .form-select {
        width: auto;
        border-radius: 4px;
        border: 1px solid #ccc;
        box-shadow: none;
        background-color: #f9f9f9;
        font-size: 15px;
    }

    .form-select:focus {
        border-color: #42bd37;
        box-shadow: 0 0 5px rgba(66, 189, 55, 0.5);
    }

    .info-box-container {
        display: flex;
        flex-wrap: wrap;
        gap: 15px;
        margin-bottom: 20px;
        max-width: 20%; /* Adjust as needed */

    }

    .info-box {
        background: #040404;
        border-radius: 8px;
        padding: 15px 20px;
        min-width: 200px;
        flex: 1;
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
        transition: transform 0.2s ease;
        color: #fff;
    }

    .info-box:hover {
        transform: translateY(-2px);
    }

    .info-box-label {
        color: #f1f5f0;
        font-size: 0.875rem;
        margin-bottom: 5px;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        font-weight: bold;
    }

    .info-box-value {
        color: white;
        font-size: 1rem;
        font-weight: 600;
        margin: 0;
    }
</style>
