@section('title', 'Report Summary')


<x-layout bodyClass="g-sidenav-show  bg-gray-200">

    <x-navbars.sidebar activePage="reportSummary"></x-navbars.sidebar>
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
                                <h3 class="text-white text-capitalize ps-3">Report Summary</h3>
                            </div>
                            <div class="card-body p-3">
                                <div class="table-responsive p-0">
                                    <table id="summary-table" class="table table-bordered nowrap rounded-table p-0" style="width:100%">
                                        <thead style="background-color: #3cb210; color: white;">
                                            <tr>
                                                <th class="text-center">No</th>
                                                <th class="text-center">Project Desc</th>
                                                <th class="text-center">Category</th>
                                                <th class="text-center">WBS Capex </th>
                                                <th class="text-center">Remark</th>
                                                <th class="text-center">Req Number</th>
                                                <th class="text-center">Requester</th>
                                                <th class="text-center">Capex Number</th>
                                                <th class="text-center">Amount Budget (USD)</th>
                                                <th class="text-center">Budget Cos (USD)</th>
                                                <th class="text-center">Total Budget</th>
                                                <th class="text-center">Realization Cost (RP)</th>
                                                <th class="text-center">Realization Cost (USD)</th>
                                                <th class="text-center">PO Release</th>
                                                <th class="text-center">Realized</th>
                                                <th class="text-center">Status Capex</th>
                                                <th class="text-center">Budget Type</th>
                                                <th class="text-center">Start Up</th>
                                                <th class="text-center">Exp Completed</th>
                                                <th class="text-center">Rev Completion Date</th>
                                                <th class="text-center">Days Remaining</th>
                                                <th class="text-center">Days Late</th>
                                                <th class="text-center">WBS Number</th>
                                                <th class="text-center">CIP Number</th>
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
                <script src="assets/js/plugins/sweetalert.min.js"></script>
                <script src="{{ asset('assets/datatables/dataTables.min.js') }}"></script>
                <script src="assets/js/moment.min.js"></script>

                <script>
                    $(document).ready(function() {
                        // Initialize DataTable
                        var table = $('#summary-table').DataTable({
                            processing: true,
                            serverSide: true,
                            ordering: false,
                            ajax: {
                                url: '{!! route('report.index') !!}',
                                type: 'GET',
                                data: {
                                    flag: 'summary'
                                },
                                error: function(xhr, error, thrown) {
                                    console.log('Ajax Error:', thrown);
                                }
                            },
                            columns: [
                                { data: 'DT_RowIndex', name: 'DT_RowIndex' },  // Untuk nomor urut
                                {data: 'project_desc', name: 'project_desc', 
                                    createdCell: function(td, cellData, rowData, rowIndex, colIndex) {
                                        $(td).css('text-align', 'left');
                                    }
                                }, 
                                {data: 'category', name: 'category', 
                                        createdCell: function(td, cellData, rowData, rowIndex, colIndex){
                                            $(td).css('text-align','left');
                                        }, 
                                        render: function(data, type, row) {
                                        if (type === 'display') {
                                            if (data === 'General Operation') {
                                                return '<span class="badge bg-secondary">General Operation</span>';
                                            } else if (data === 'IT') {
                                                return '<span class="badge bg-secondary">IT</span>';
                                            } else if (data === 'Environment') {
                                                return '<span class="badge bg-secondary">Environment</span>';
                                            } else if (data === 'Safety') {
                                                return '<span class="badge bg-secondary">Safety</span>';
                                            } else if (data === 'Improvement Plant efficiency') {
                                                return '<span class="badge bg-secondary">Improvement Plant efficiency</span>';
                                            } else if (data === 'Invesment') {
                                                return '<span class="badge bg-secondary">Invesment</span>';
                                            }
                                            return data; // Untuk nilai lain tampilkan apa adanya
                                        }
                                        return data;
                                }},
                                {data: 'wbs_capex', name: 'wbs_capex', 
                                    createdCell: function(td, cellData, rowData, rowIndex, colIndex){
                                            $(td).css('text-align','left');
                                        },
                                    render: function(data, type, row) {
                                        if (type === 'display') {
                                            if (data === 'Project') {
                                                return '<span class="badge bg-info">Project</span>';
                                            } else if (data === 'Non Project') {
                                                return '<span class="badge bg-warning">Non Project</span>';
                                            }
                                            return data; // Untuk nilai lain tampilkan apa adanya
                                        }
                                        return data;
                                }},
                                {data: 'remark', name: 'remark',
                                        createdCell: function(td, cellData, rowData, rowIndex, colIndex){
                                            $(td).css('text-align', 'left');
                                    }},
                                    
                                    {data: 'request_number', name: 'request_number',
                                        createdCell: function(td, cellData, rowData, rowIndex, colIndex){
                                            $(td).css('text-align', 'right');
                                    }},
                                    {
                                        data: 'requester',
                                        name: 'requester',
                                        createdCell: function(td, cellData, rowData, rowIndex, colIndex) {
                                            $(td).css('text-align', 'left'); 
                                        }
                                    },

                                    {data: 'capex_number', name: 'capex_number', createdCell: function(td, cellData, rowData, rowIndex, colIndex) {
                                            $(td).css('text-align', 'left');
                                    }},
                                    {data: 'amount_budget', name: 'amount_budget', className: 'text-right', 
                                    render: function(data) {
                                        return data 
                                            ? '<div style="text-align: right;">' + data.toLocaleString() + '</div>' 
                                            : '';
                                    }},
                                    {data: 'budget_cos', name: 'budget_cos',
                                        render: function(data, type, row) {
                                            if (type === 'display') {
                                                // Cek jika data kosong
                                            if (data === null || data === "" || isNaN(Number(data))) {
                                                return '-'; 
                                        }
                                            return '<div style="text-align: right;">' + 
                                                '<span>' + data.toLocaleString() + '</span>' + 
                                                '</div>';
                                            }
                                            return data;
                                    }},
                                    {data: 'total_budget', name: 'total_budget', 
                                    render: function(data, type) {
                                        if (type === 'display') {
                                            return '<div style="text-align: right;">' + data.toLocaleString() + '</div>';
                                        }
                                        return data;
                                    }},

                                    {data: 'recost_rp', name: 'recost_rp',
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

                                    {data: 'recost_usd', name: 'recost_usd', 
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
                                    {data: 'PO_release', name: 'PO_release', className: 'text-right',   render: function(data, type) {
                                        if (type === 'display') {
                                            if(data === null || data ==="" || isNaN(Number(data))){
                                                return '-';
                                            }
                                            return '<div style="text-align: right;">' + data.toLocaleString() + '</div>';
                                        }
                                        return data;
                                    }},
                                    {data: 'realized', name:'realized'},
                                    {data: 'status_capex', name: 'status_capex', className: 'text-start',
                                        render: function(data, type, row) {
                                            if (type === 'display') {
                                                let badgeClass = '';
                                                switch(data) {
                                                    case 'Canceled':
                                                        badgeClass = 'bg-danger';
                                                        break;
                                                    case 'Close':
                                                        badgeClass = 'bg-secondary';
                                                        break;
                                                    case 'On Progress':
                                                        badgeClass = 'bg-success';
                                                        break;
                                                    case 'To Opex':
                                                        badgeClass = 'bg-info';
                                                        break;
                                                    default:
                                                        return data;
                                                }

                                                return `<span class="badge ${badgeClass}">${data}</span>`;
                                            }
                                            return data;
                                        }
                                    },
                                    {data: 'budget_type', name: 'budget_type', className: 'text-right', render: function(data, type, row) {
                                        return '<div style="text-align: left;">' + data + '</div>';
                                    }},
                                    {data: 'startup', name: 'startup', className: 'text-center',
                                        render: function(data) {
                                                return moment(data).format('DD-MM-YYYY'); 
                                        }},
                                    {data: 'expected_completed', name: 'expected_completed', className: 'text-center',
                                        render: function(data) {
                                                return moment(data).format('DD-MM-YYYY'); 
                                        }},
                                    {data: 'revise_completion_date', name: 'revise_completion_date', className: 'text-center',
                                    render: function(data) {
                                        if (!data) return '-';
                                        return moment(data).isValid() ? moment(data).format('DD-MM-YYYY') : '-';
                                    }},
                                    {data: 'days_remaining', name: 'days_remaining', className: 'text-right',
                                    render: function(data) {
                                            if (data === null || data === undefined) {
                                                return '-';}
                                            return data;
                                    }},
                                    {data: 'days_late', name: 'days_late', className: 'text-right',
                                    render: function(data) {
                                            if (data === null || data === undefined) {
                                                return '-';}
                                            return data;
                                    }},
                                    {data: 'wbs_number', name: 'wbs_number',
                                        createdCell: function(td, cellData, rowData, rowIndex, colIndex) {
                                            $(td).css('text-align', 'left'); 
                                    }},
                                    {data: 'cip_number', name: 'cip_number',
                                        createdCell: function(td, cellData, rowData, rowIndex, colIndex) {
                                            $(td).css('text-align', 'left'); 
                                    }},
                            ]
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
        #summary-table {
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

    #summary-table thead th {
        background-color: #3cb210;
        /* Warna latar belakang header */
        color: #ffffff;
        /* Warna teks header */
    }

    /* Gaya untuk baris tabel */
    #summary-table tbody tr {
        transition: background-color 0.3s ease;
        /* Efek transisi untuk warna latar belakang */
        color: #2c2626;
    }

    /* Gaya untuk sel tabel */
    #summary-table tbody td {
        padding: 8px;
        /* Padding untuk sel */
        border-bottom: 1px solid #dee2e6;
        /* Garis bawah sel */
        color: #2c2626;
    }

    /* Hover effect untuk baris tabel */
    #summary-table tbody tr:hover {
        background-color: rgba(0, 123, 255, 0.1);
        /* Warna latar belakang saat hover */
    }

    #summary-table th,
    #summary-table td {
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
