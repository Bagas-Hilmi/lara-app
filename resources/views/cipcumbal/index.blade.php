<x-layout bodyClass="g-sidenav-show  bg-gray-200">
    <x-navbars.sidebar activePage="Cip Cumulative Balance"></x-navbars.sidebar>
    <main class="main-content position-relative max-height-vh-100 h-100 border-radius-lg ">
        <!-- Navbar -->
        <x-navbars.navs.auth titlePage="Cip Cumulative Balance"></x-navbars.navs.auth>
        <!-- End Navbar -->
        <div class="container-fluid py-4">
            <div class="row">
                <div class="col-12">
                    <div class="card my-4">
                        <div class="card-header p-0 position-relative mt-n4 mx-3 z-index-2">
                            <div class="bg-gradient-primary shadow-primary border-radius-lg pt-4 pb-3">
                                <h6 class="text-white text-capitalize ps-3">Cip Cumulative Balance</h6>
                            </div>
                            <div class="card-body p-3">
                                <div class="d-flex justify-content-between mb-2">
                                    <!-- Tombol New Entry -->
                                    <a href="#" class="btn btn-sm btn-primary" data-bs-toggle="modal" data-bs-target="#new-form">New Entry</a>
                                    <select id="yearFilter" class="form-select btn-year-filter w-auto" aria-label="Filter by Year and Month">
                                        <option value="">Semua Tahun</option>
                                            <!-- Tahun-tahun yang diambil dari database -->
                                            @foreach ($availableYears as $year)
                                                <option value="{{ $year }}">{{ $year }}</option>
                                            @endforeach
                                    </select>
                                </div>

                                <div class="table-responsive p-0">
                                    <table id="cipCumBalTable" class="table table-striped nowrap table-responsive p-0" style="width:100%">
                                        <thead>
                                            <tr>
                                                <th align="center">Action</th>
                                                <th align="center">ID</th>
                                                <th align="center">Period CIP</th>
                                                <th align="center">Bal USD</th>
                                                <th align="center">Bal RP</th>
                                                <th align="center">Cumbal USD</th>
                                                <th align="center">Cumbal RP</th>
                                                <th align="center">Report Status</th>
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
            <x-plugins></x-plugins>

            @include('modal.new-entry')
            @include('modal.update-entry')

            @push('css')
            <link rel="stylesheet" href="https://cdn.datatables.net/2.1.6/css/dataTables.dataTables.css" />           
            @endpush

            @push('js')
            <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
            <script src="https://cdn.datatables.net/2.1.5/js/dataTables.min.js"></script>
            
            <script src="{{ asset('js/new-entry.js') }}"></script>
            <script src="{{ asset('js/update-entry.js') }}"></script>

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
                        ajax: {
                            url: "{{ route('cipcumbal.index') }}",
                            type: 'GET',
                            data: function (d) {
                                console.log('Selected Year:', $('#yearFilter').val()); // Log untuk memeriksa nilai tahun
                                d.status = 1; // Filter status tetap
                                d.year = $('#yearFilter').val(); // Tambahkan filter tahun dari dropdown
                            }
                        },
                        columns: [
                            {data: 'action', name: 'action', orderable: false, searchable: false, className: 'text-center'},
                            {data: 'id_ccb', name: 'id_ccb', className: 'text-center'},
                            {data: 'period_cip', name: 'period_cip', className: 'text-center'},

                            {
                                data: 'bal_usd', 
                                name: 'bal_usd', 
                                className: 'text-center',
                                render: function(data, type, row) {
                                    if (type === 'display') {
                                        return parseFloat(data).toFixed(2).replace(/\B(?=(\d{3})+(?!\d))/g, ",");
                                    }
                                    return data;
                                }
                            },
                            {
                                data: 'bal_rp', 
                                name: 'bal_rp', 
                                className: 'text-center',
                                render: function(data, type, row) {
                                    if (type === 'display') {
                                        return parseFloat(data).toFixed(2).replace(/\B(?=(\d{3})+(?!\d))/g, ",");
                                    }
                                    return data;
                                }
                            },
                            {
                                data: 'cumbal_usd', 
                                name: 'cumbal_usd', 
                                className: 'text-center',
                                render: function(data, type, row) {
                                    if (type === 'display') {
                                        return parseFloat(data).toFixed(2).replace(/\B(?=(\d{3})+(?!\d))/g, ",");
                                    }
                                    return data;
                                }
                            },
                            {
                                data: 'cumbal_rp', 
                                name: 'cumbal_rp', 
                                className: 'text-center',
                                render: function(data, type, row) {
                                    if (type === 'display') {
                                        return parseFloat(data).toFixed(2).replace(/\B(?=(\d{3})+(?!\d))/g, ",");
                                    }
                                    return data;
                                }
                            },

                            {data: 'report_status', name: 'report_status', className: 'text-center'}
                        ]
                    });

                    // Event listener untuk filter tahun
                    $('#yearFilter').change(function() {
                        table.ajax.reload();
                    });
                                    
                    // Event klik tombol delete
                    $('#cipCumBalTable').on('click', '.delete-btn', function() {
                        var id = $(this).data('id');
                        if(confirm('Apakah Anda yakin ingin menghapus item ini?')) {
                            $.ajax({
                                url: '/cipcumbal/' + id,
                                type: 'DELETE',
                                success: function(result) {
                                    table.ajax.reload();
                                }
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
    .btn-year-filter {
        background-color: #f0f0f0;
        border: 1px solid #ccc;
        color: #333;
        border-radius: 7px;
        padding: 5px 10px;
        cursor: pointer;
        transition: all 0.3s ease;
    }
    
    .btn-year-filter:hover {
        background-color: #df3f3f;
        color: #ffffff;
        border-color: #ffffff;
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
        background-color: #19da16;
        color: #ffffff;
    }

    #cipCumBalTable tbody tr:nth-child(even) {
        background-color: #f9f9f9;
    }

    .delete-btn {
        color: rgb(255, 255, 255);
        cursor: pointer;
    }
    
</style>

