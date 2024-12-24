@section('title', 'Approve')


<x-layout bodyClass="g-sidenav-show  bg-gray-200">
    <x-navbars.sidebar activePage="Approve"></x-navbars.sidebar>
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
                                <h3 class="text-white text-capitalize ps-3">Approval Report</h3>
                            </div>
                            <div class="card-body p-3">
                                <div class="d-flex mb-2">
                                                        
                                </div>

                                <div class="table-responsive p-0">
                                    <table id="approve-table" class="table table-striped rounded-table p-0 mx-auto" style="width: 100%;">
                                        <thead>
                                            <tr>
                                                <th class="text-center">Action</th>
                                                <th class="text-center">ID</th>
                                                <th class="text-center">Requester </th>
                                                <th class="text-center">Project Description </th>
                                                <th class="text-center">WBS Capex </th>
                                                <th class="text-center">Status Capex</th>
                                                <th class="text-center">Upload Date</th>
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

            @include('approve.modal.upload-pdf')
            @include('approve.modal.approve-pdf')
            @include('approve.modal.form')


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
                    var table = $('#approve-table').DataTable({
                        responsive: true,
                        processing: true,
                        serverSide: true,
                        order: [[1 , 'desc']],
                        ajax: {
                            url: "{{ route('approve.index') }}",
                            type:'GET'
                        },
                        columns: [
                            {data: 'action', name: 'action', orderable: false, searchable: false,  className: 'text-center'},
                            {data: 'id_capex', name:'id_capex' },  
                            {data: 'requester', name: 'requester', 
                                    createdCell: function(td, cellData, rowData, rowIndex, colIndex){
                                            $(td).css('text-align','left');
                                        }},
                            {data: 'project_desc', name: 'project_desc', 
                                    createdCell: function(td, cellData, rowData, rowIndex, colIndex){
                                            $(td).css('text-align','left');
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
                            {data: 'status_capex', name: 'status_capex', className: 'text-center',
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
                            {data: 'upload_date', name:'upload_date' },  
                        ],   
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
        #approve-table {
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

    #approve-table thead th {
        background-color: #3cb210; /* Warna latar belakang header */
        color: #ffffff; /* Warna teks header */
        width: auto; /* Atur lebar kolom header secara otomatis */
    }

    /* Gaya untuk baris tabel */
    #approve-table tbody tr {
        transition: background-color  0.3s ease; /* Efek transisi untuk warna latar belakang */
        color: #2c2626;
    }

    /* Gaya untuk sel tabel */
    #approve-table tbody td {
        padding: 10px; /* Padding untuk sel */
        border-bottom: 1px solid #dee2e6; /* Garis bawah sel */
        color: #2c2626;
    }

    /* Hover effect untuk baris tabel */
    #approve-table tbody tr:hover {
        background-color: rgba(0, 123, 255, 0.1); /* Warna latar belakang saat hover */
    }

    #approve-table th, #approve-table td {
        padding: 8px;
        text-align: center;
    }
    /* Styling khusus untuk dropdown "Entries per page" */
    /* Target class .form-select untuk dropdown DataTables */
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
    /* Indikator urutan di header DataTables */
    th.sorting::after, th.sorting_asc::after, th.sorting_desc::after {
        content: '▼'; /* Default panah ke bawah */
        font-size: 0.8em;
        margin-left: 0.5em;
        color: #007bff; /* Warna panah */
    }

    th.sorting_asc::after {
        content: '▲'; /* Panah ke atas untuk urutan naik */
    }

    th.sorting_desc::after {
        content: '▼'; /* Panah ke bawah untuk urutan turun */
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

