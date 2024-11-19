@section('title', 'Report Category')


<x-layout bodyClass="g-sidenav-show  bg-gray-200">

    <x-navbars.sidebar activePage="reportCategory"></x-navbars.sidebar>
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
                                <h3 class="text-white text-capitalize ps-3">Report Category</h3>
                            </div>
                            <div class="card-body p-3">
                                <div class="mb-2">
                                    
                                </div>
                                <div class="table-responsive p-0">
                                    <table id="cate-table" class="table table-striped nowrap rounded-table p-0" style="width:100%">
                                        <thead style="background-color: #3cb210; color: white;">
                                            <tr>
                                                <th align="center">No</th>
                                                <th align="center">Category</th>
                                                <th align="center">Project</th>
                                                <th align="center">Number</th>
                                                <th align="center">Budget 2024</th>
                                                <th align="center">Unbudget 2024</th>
                                                <th align="center">Carried Over</th>
                                                <th align="center">Amount</th>
                                                <th align="center">Actual YTD</th>
                                                <th align="center">Balance</th>
                                                
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <th align="center">IT</th>                                            
                                        </tbody>
                                        <tfoot>
                                            <tr>
                                                <th colspan="4">Sub Total</th>
                                                <th></th>
                                                <th></th>
                                                <th></th>
                                                <th></th>
                                                <th></th>
                                                <th></th>
                                            </tr>
                                        </tfoot>
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
                <script src="assets/js/plugins/sweetalert.min.js"></script>
                <script src="{{ asset('assets/datatables/dataTables.min.js') }}"></script>
                <script src="assets/js/moment.min.js"></script>

                {{-- <script src="assets/js/select2.min.js"></script>
                <script src="assets/js/select2.min.css"></script> --}}
                <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
                <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

                <script>
                $(document).ready(function() {
                    var table = $('#cate-table').DataTable({
                        processing: true,
                        serverSide: true,
                        ajax: {
                            url: '{!! route('report.index') !!}',
                            type: 'GET',
                            data: {
                                flag: 'category'
                            }
                        },
                        columns: [
                            { 
                                data: null,
                                render: function (data, type, row, meta) {
                                    return meta.row + meta.settings._iDisplayStart + 1;
                                },
                                className: 'text-center'
                            },
                            { data: 'category', name: 'category', className: 'text-start' },
                            { data: 'project', name: 'project', className: 'text-start' },
                            { data: 'number', name: 'number', className: 'text-start' },
                            { 
                                data: 'budget', 
                                name: 'budget', 
                                className: 'text-right',
                                render: function(data) {
                                    return data ? data.toLocaleString() : '';
                                }
                            },
                            { 
                                data: 'unbudget', 
                                name: 'unbudget', 
                                className: 'text-right',
                                render: function(data) {
                                    return data ? data.toLocaleString() : '';
                                }
                            },
                            { 
                                data: 'carried_over', 
                                name: 'carried_over', 
                                className: 'text-right',
                                render: function(data) {
                                    return data ? data.toLocaleString() : '';
                                }
                            },
                            { 
                                data: 'amount', 
                                name: 'amount', 
                                className: 'text-right',
                                render: function(data) {
                                    return data ? data.toLocaleString() : '';
                                }
                            },
                            { 
                                data: 'actual_ytd', 
                                name: 'actual_ytd', 
                                className: 'text-right',
                                render: function(data) {
                                    return data ? data.toLocaleString() : '';
                                }
                            },
                            { 
                                data: 'balance', 
                                name: 'balance', 
                                className: 'text-right',
                                render: function(data) {
                                    return data ? data.toLocaleString() : '';
                                }
                            }
                        ],
                        rowGroup: {
                            dataSrc: 'category',
                            className: 'category-row',
                            startRender: function (rows, group) {
                                console.log('Rows:', rows);  // Debug: cek data baris
                                console.log('Group:', group);  // Debug: cek nilai group
                                return $('<tr>')
                                    .append('<td colspan="9" class="font-weight-bold bg-light">' + group + '</td>');
                            }
                        },
                        order: [[0, 'asc']],
                        footerCallback: function(row, data, start, end, display) {
                            var api = this.api();

                            // Kolom yang akan dijumlahkan
                            var columns = [4, 5, 6, 7, 8, 9]; // Tambahkan kolom ke-9 di sini
                            columns.forEach(function(colIndex) {
                                var total = api
                                    .column(colIndex, { page: 'current' })
                                    .data()
                                    .reduce(function(acc, val) {
                                        return acc + (parseFloat(val) || 0);
                                    }, 0);

                                $(api.column(colIndex).footer()).html(total.toLocaleString());
                            });
                        }
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
        #cate-table {
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

    #cate-table thead th {
        background-color: #3cb210;
        /* Warna latar belakang header */
        color: #ffffff;
        /* Warna teks header */
    }

    /* Gaya untuk baris tabel */
    #cate-table tbody tr {
        transition: background-color 0.3s ease;
        /* Efek transisi untuk warna latar belakang */
        color: #2c2626;
    }

    /* Gaya untuk sel tabel */
    #cate-table tbody td {
        padding: 10px;
        /* Padding untuk sel */
        border-bottom: 1px solid #dee2e6;
        /* Garis bawah sel */
        color: #2c2626;
    }

    /* Hover effect untuk baris tabel */
    #cate-table tbody tr:hover {
        background-color: rgba(0, 123, 255, 0.1);
        /* Warna latar belakang saat hover */
    }

    #cate-table th,
    #cate-table td {
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

    .form-control {
        border: 1px solid #ccc;
        /* Customize the border */
        box-shadow: none;
        /* Remove shadow */
        border-radius: 4px;
        /* Tambahkan sudut melengkung */
    }

    .form-control:focus {
        border-color: #42bd37;
        /* Warna border saat fokus */
        box-shadow: 0 0 5px rgba(66, 189, 55, 0.5);
        /* Menambah efek shadow saat fokus */
    }

</style>
