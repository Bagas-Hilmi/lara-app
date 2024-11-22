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
                                    <table id="cate-table" class="table table-bordered nowrap rounded-table p-0" style="width:100%">
                                        <thead style="background-color: #3cb210; color: white;">
                                            <tr>
                                                <th class="text-center">No</th>
                                                <th class="text-center">Category</th>
                                                <th class="text-center">Project</th>
                                                <th class="text-center">Number</th>
                                                <th class="text-center">Budget 2024</th>
                                                <th class="text-center">Unbudget 2024</th>
                                                <th class="text-center">Carried Over</th>
                                                <th class="text-center">Amount</th>
                                                <th class="text-center">Actual YTD</th>
                                                <th class="text-center">Balance</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                           
                                        </tbody>
                                        <tfoot>
                                            <tr style="background-color: #294822; color: #ffffff; font-weight: bold;">
                                                <th colspan="4">Total</th>
                                                <th class="text-right"></th>
                                                <th class="text-right"></th>
                                                <th class="text-right"></th>
                                                <th class="text-right"></th>
                                                <th class="text-right"></th>
                                                <th class="text-right"></th>
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
                        var table = $('#cate-table').DataTable({
                            processing: true,
                            serverSide: true,
                            ordering: false,
                            ajax: {
                                url: '{!! route('report.index') !!}',
                                type: 'GET',
                                data: {
                                    flag: 'category'
                                }
                            },
                            columns: [
                                { data: 'DT_RowIndex', name: 'DT_RowIndex' },  // Untuk nomor urut

                                { data: 'category', visible: false }, // Hide kolom category
                                { 
                                    data: 'project',
                                    createdCell: function(td, cellData, rowData, rowIndex, colIndex) {
                                        $(td).css('text-align', 'left');
                                    }
                                },
                                { data: 'number', className: 'text-center' },
                                { 
                                    data: 'budget',
                                    createdCell: function(td, rowData, rowIndex, cellData, colIndex){
                                        $(td).css('text-align', 'right');
                                    },
                                    render: function(data) {
                                        return data ? data.toLocaleString() : '';
                                    }
                                },
                                { 
                                    data: 'unbudget',
                                    className: 'text-right',
                                    render: function(data) {
                                        return data ? data.toLocaleString() : '';
                                    }
                                },
                                { 
                                    data: 'carried_over',
                                    className: 'text-right',
                                    render: function(data) {
                                        return data ? data.toLocaleString() : '';
                                    }
                                },
                                { 
                                    data: 'amount',
                                    className: 'text-right',
                                    render: function(data) {
                                        return data ? data.toLocaleString() : '';
                                    }
                                },
                                { 
                                    data: 'actual_ytd',
                                    className: 'text-right',
                                    render: function(data) {
                                        return data ? data.toLocaleString() : '';
                                    }
                                },
                                { 
                                    data: 'balance',
                                    className: 'text-right',
                                    render: function(data) {
                                        return data ? data.toLocaleString() : '';
                                    }
                                }
                            ],
                            drawCallback: function(settings) {
                                var api = this.api();
                                var rows = api.rows({ page: 'current' }).nodes();
                                var last = null;
                                var groupHeader = null;
                                var counter = 1;

                                api.rows({ page: 'current' }).data().each(function(group, i) {
                                    if (last !== group.category) {
                                        counter = 1;
                                        // Insert category header before the first row of the group
                                        groupHeader = $('<tr class="group">' +
                                            '<td colspan="1" style="font-weight: bold; padding: 10px;"></td>' +  // Kolom pertama (kosong atau bisa ditambahkan konten lain)
                                            '<td colspan="9" style="font-weight: bold; padding: 10px; text-align: left;">' +  // Kolom kedua untuk teks
                                            group.category + 
                                            '</td>' +
                                        '</tr>');

                                        $(rows[i]).before(groupHeader);

                                        // Calculate subtotal for this group
                                        var subtotal = {
                                            budget: 0,
                                            unbudget: 0,
                                            carried_over: 0,
                                            amount: 0,
                                            actual_ytd: 0,
                                            balance: 0
                                        };

                                        // Find all rows for current category
                                        var categoryRows = [];
                                        api.rows({ page: 'current' }).data().each(function(data, index) {
                                            if (data.category === group.category) {
                                                categoryRows.push(index);
                                                subtotal.budget += parseFloat(data.budget || 0);
                                                subtotal.unbudget += parseFloat(data.unbudget || 0);
                                                subtotal.carried_over += parseFloat(data.carried_over || 0);
                                                subtotal.amount += parseFloat(data.amount || 0);
                                                subtotal.actual_ytd += parseFloat(data.actual_ytd || 0);
                                                subtotal.balance += parseFloat(data.balance || 0);
                                            }
                                        });

                                        categoryRows.forEach(function(rowIndex, index) {
                                            $(rows[rowIndex]).find('td:first').html(index + 1);
                                        });

                                        // Find last row of current category
                                        var lastRowIndex = categoryRows[categoryRows.length - 1];
                                        $(rows[lastRowIndex]).after(
                                            '<tr class="group-subtotal">' +
                                            '<td colspan="3" style="padding: 7px;">Sub Total</td>' +
                                            '<td class="text-right" style="padding: 8px;">' + 
                                            (subtotal.budget ? subtotal.budget.toLocaleString() : '') + '</td>' +
                                            '<td class="text-right" style="padding: 8px;">' + 
                                            (subtotal.unbudget ? subtotal.unbudget.toLocaleString() : '') + '</td>' +
                                            '<td class="text-right" style="padding: 8px;">' + 
                                            (subtotal.carried_over ? subtotal.carried_over.toLocaleString() : '') + '</td>' +
                                            '<td class="text-right" style="padding: 8px;">' + 
                                            (subtotal.amount ? subtotal.amount.toLocaleString() : '') + '</td>' +
                                            '<td class="text-right" style="padding: 8px;">' + 
                                            (subtotal.actual_ytd ? subtotal.actual_ytd.toLocaleString() : '') + '</td>' +
                                            '<td class="text-right" style="padding: 8px;">' + 
                                            (subtotal.balance ? subtotal.balance.toLocaleString() : '') + '</td>' +
                                            '</tr>'
                                        );

                                        last = group.category;
                                    }
                                });
                            },
                            footerCallback: function(row, data, start, end, display) {
                                var api = this.api();
                                var columns = [4, 5, 6, 7, 8, 9];

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
        padding: 8px;
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
    .group td {
        font-weight: bold !important;
        background-color: #000000 !important;
        color: white !important;
    }

    .group-subtotal td {
        background-color: #e0e8e0 !important;
        font-weight: bold;
        
    }

    tr.group,
    tr.group-subtotal {
        cursor: default !important;
    }

</style>
