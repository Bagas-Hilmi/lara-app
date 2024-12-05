@section('title', 'Report Tax')


<x-layout bodyClass="g-sidenav-show  bg-gray-200">

    <x-navbars.sidebar activePage="reportTax"></x-navbars.sidebar>
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
                                <h3 class="text-white text-capitalize ps-3">Report Tax</h3>
                            </div>
                            <div class="card-body p-3">
                                <div class="table-responsive p-0">
                                    <table id="tax-table" class="table table-bordered nowrap rounded-table p-0" style="width:100%">
                                        <thead style="background-color: #3cb210; color: white;">
                                            <tr>
                                                <th class="text-center">No</th>
                                                <th class="text-center">Project Desc</th>
                                                <th class="text-center">Capex Number</th>
                                                <th class="text-center">CIP Number</th>
                                                <th class="text-center">WBS Number</th>
                                                <th class="text-center">Material</th>
                                                <th class="text-center">Description</th>
                                                <th class="text-center">Qty</th>
                                                <th class="text-center">Uom</th>
                                                <th class="text-center">Amount (RP)</th>
                                                <th class="text-center">Amount (US)</th>
                                                <th class="text-center">Date</th>
                                                <th class="text-center">Settle Doc</th>
                                                <th class="text-center">Fa Doc</th>
                                            </tr>
                                        </thead>
                                        <tfoot style="background-color: #294822; color: #ffffff; font-weight: bold;">
                                            <tr>
                                                <th colspan="9" class="text-center">Total All Capex</th>
                                                <th id="total-rp" class="text-center"></th>
                                                <th id="total-us" class="text-center"></th>
                                                <th colspan="3" class="text-center"></th>
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

                <script>
                    $(document).ready(function() {
                        // Initialize DataTable
                        var table = $('#tax-table').DataTable({
                            processing: true,
                            serverSide: true,
                            ordering: true,
                            order: [[2, 'asc']],
                            ajax: {
                                url: '{!! route('report.index') !!}',
                                type: 'GET',
                                data: {
                                    flag: 'tax'
                                },
                                error: function(xhr, error, thrown) {
                                    console.log('Ajax Error:', thrown);
                                }
                            },
                            columns: [
                                    {data: 'DT_RowIndex', name: 'DT_RowIndex' },  // Untuk nomor urut
                                    {data: 'project_desc', name: 'project_desc', 
                                        createdCell: function(td, cellData, rowData, rowIndex, colIndex) {
                                            $(td).css('text-align', 'left');
                                        }
                                    }, 
                                    {data: 'capex_number', name: 'capex_number', createdCell: function(td, cellData, rowData, rowIndex, colIndex) {
                                            $(td).css('text-align', 'left');
                                    }},
                                    {data: 'cip_number', name: 'cip_number',
                                        createdCell: function(td, cellData, rowData, rowIndex, colIndex) {
                                            $(td).css('text-align', 'left'); 
                                    }},
                                    {data: 'wbs_number', name: 'wbs_number',
                                        createdCell: function(td, cellData, rowData, rowIndex, colIndex) {
                                            $(td).css('text-align', 'left'); 
                                    }}, 
                                    {data: 'material', name: 'material'},
                                    {data: 'description', name: 'description'},
                                  
                                    {data: 'qty', name: 'qty'},
                                    {data: 'uom', name: 'uom'},
                                    {data: 'amount_rp', name: 'amount_rp',
                                    createdCell: function(td, rowData, rowIndex, cellData, colIndex){
                                        $(td).css('text-align', 'right');
                                    },
                                    render: function(data, type, row) {
                                        if (data === '' || data === null || data === undefined) {
                                            data = null;
                                        }
                                        if (data !== null) {
                                            return new Intl.NumberFormat('id-ID', { 
                                                style: 'decimal', 
                                                minimumFractionDigits: 2, 
                                                maximumFractionDigits: 2 
                                            }).format(data);
                                        }

                                        // Jika data null, return null
                                        return data;
                                    }},

                                    {data: 'amount_us', name: 'amount_us', 
                                    createdCell: function(td, rowData, rowIndex, cellData, colIndex){
                                        $(td).css('text-align', 'right');
                                    },
                                    render: function(data, type, row) {
                                        if (data === '' || data === null || data === undefined) {
                                            data = null;
                                        }
                                        if (data !== null) {
                                            return new Intl.NumberFormat('id-ID', { 
                                                style: 'decimal', 
                                                minimumFractionDigits: 2, 
                                                maximumFractionDigits: 2 
                                            }).format(data);
                                        }

                                        // Jika data null, return null
                                        return data;
                                    }},
                                    {data: 'date', name: 'date'},
                                    {data: 'settle_doc', name:'settle_doc'},
                                    {data: 'fa_doc', name:'fa_doc'},
                            ],
                            drawCallback: function(settings) {
                                var api = this.api();
                                var rows = api.rows({ page: 'current' }).nodes();
                                var lastCapex = null;
                                var subtotal = {
                                    amount_rp: 0,
                                    amount_us: 0
                                };

                                api.rows({ page: 'current' }).data().each(function(row, i) {
                                    if (lastCapex !== row.capex_number) {
                                        // Insert subtotal row for the previous group
                                        if (lastCapex !== null) {
                                            $(rows[i - 1]).after(
                                                '<tr class="group-subtotal" style="background-color: #FDCA00; color: black;">' +  
                                                '<td colspan="9" style="text-align: right; font-weight: bold;">Subtotal</td>' +
                                                '<td style="text-align: right; font-weight: bold;">' + 
                                                subtotal.amount_rp.toLocaleString('id-ID', { minimumFractionDigits: 2, maximumFractionDigits: 2 }) + '</td>' +
                                                '<td style="text-align: right; font-weight: bold;">' + 
                                                subtotal.amount_us.toLocaleString('id-ID', { minimumFractionDigits: 2, maximumFractionDigits: 2 }) + '</td>' +
                                                '<td colspan="3"></td>' +
                                                '</tr>'
                                            );
                                        }

                                        // Reset subtotal
                                        subtotal = { amount_rp: 0, amount_us: 0 };
                                        lastCapex = row.capex_number;
                                    }

                                    // Add current row values to subtotal
                                    subtotal.amount_rp += parseFloat(row.amount_rp || 0);
                                    subtotal.amount_us += parseFloat(row.amount_us || 0);
                                });

                                // Insert final subtotal for the last group
                                if (lastCapex !== null) {
                                    $(rows[rows.length - 1]).after(
                                        '<tr class="group-subtotal" style="background-color: #FDCA00; color: black;">' +  
                                        '<td colspan="9" style="text-align: right; font-weight: bold;">Subtotal</td>' +
                                        '<td style="text-align: right; font-weight: bold;">' + 
                                        subtotal.amount_rp.toLocaleString('id-ID', { minimumFractionDigits: 2, maximumFractionDigits: 2 }) + '</td>' +
                                        '<td style="text-align: right; font-weight: bold;">' + 
                                        subtotal.amount_us.toLocaleString('id-ID', { minimumFractionDigits: 2, maximumFractionDigits: 2 }) + '</td>' +
                                        '<td colspan="3"></td>' +
                                        '</tr>'
                                    );
                                }
                            },
                            footerCallback: function(row, data, start, end, display) {
                                var api = this.api();
                                var total = {
                                    amount_rp: 0,
                                    amount_us: 0
                                };

                                // Calculate total for the entire table
                                api.column(9, { page: 'current' }).data().each(function(value) {
                                    total.amount_rp += parseFloat(value || 0);
                                });
                                api.column(10, { page: 'current' }).data().each(function(value) {
                                    total.amount_us += parseFloat(value || 0);
                                });

                                // Update footer
                                $(api.column(9).footer()).html(
                                    total.amount_rp.toLocaleString('id-ID', { minimumFractionDigits: 2, maximumFractionDigits: 2 })
                                );
                                $(api.column(10).footer()).html(
                                    total.amount_us.toLocaleString('id-ID', { minimumFractionDigits: 2, maximumFractionDigits: 2 })
                                );
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
        #tax-table {
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

    #tax-table thead th {
        background-color: #3cb210;
        /* Warna latar belakang header */
        color: #ffffff;
        /* Warna teks header */
    }

    /* Gaya untuk baris tabel */
    #tax-table tbody tr {
        transition: background-color 0.3s ease;
        /* Efek transisi untuk warna latar belakang */
        color: #2c2626;
    }

    /* Gaya untuk sel tabel */
    #tax-table tbody td {
        padding: 8px;
        /* Padding untuk sel */
        border-bottom: 1px solid #dee2e6;
        /* Garis bawah sel */
        color: #2c2626;
    }

    /* Hover effect untuk baris tabel */
    #tax-table tbody tr:hover {
        background-color: rgba(0, 123, 255, 0.1);
        /* Warna latar belakang saat hover */
    }

    #tax-table th,
    #tax-table td {
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
