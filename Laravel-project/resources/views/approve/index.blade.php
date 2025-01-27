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
                                <div class="d-flex mb-2" style="gap: 10px;">
                                    @if(auth()->user()->hasRole('admin'))
                                        <button class="btn btn-primary" style="background-color: #2c2626" data-bs-toggle="modal" data-bs-target="#progressAPV"> 
                                            Approval Progress
                                        </button>    
                                    @endif 
                                    
                                    {{-- <select id="statusSelect" class="form-control" style="width: 20%;">
                                        <option value="" selected>Pilih Status</option>
                                        @foreach ($status as $stat)
                                            <option value="{{ $stat }}">{{ $stat }}</option>
                                        @endforeach
                                    </select> --}}
                                </div>
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
            @include('approve.modal.approval-progress')
            @include('approve.modal.check-approve')


            @push('css')
            <link href="{{ asset('assets/datatables/dataTables.min.css') }}" rel="stylesheet">
            @endpush

            @push('js')
            <script src="{{ asset('assets/js/jquery.min.js') }}"></script>
            <script src="assets/js/plugins/sweetalert.min.js"></script>
            <script src="{{ asset('assets/datatables/dataTables.min.js') }}"></script>
            <script src="{{ asset('assets/js/select2.min.js') }}"></script>

        
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
                            type:'GET',
                            data: function(d) {
                                var statusValue = $('#statusSelect').val();
                                if (statusValue) {
                                    d.status_capex = statusValue;  
                                }
                        }},
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
                                                return '<span class="badge bg-gradient-info">Project</span>';
                                            } else if (data === 'Non Project') {
                                                return '<span class="badge bg-gradient-primary">Non Project</span>';
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
                                                    case 'On Progress':
                                                        badgeClass = 'bg-gradient-success';
                                                        break;
                                                    case 'Waiting Approval':
                                                        badgeClass = 'bg-gradient-warning';
                                                        break;
                                                    case 'To Be Close':
                                                        badgeClass = 'bg-gradient-warning';
                                                        break;
                                                    case 'Close':
                                                        badgeClass = 'bg-gradient-secondary';
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
                    var statusSelect = $('#statusSelect');
                    if (statusSelect.length) {
                        statusSelect.select2({
                            placeholder: 'Cari Status',
                            allowClear: true,
                        });
                    }
                    statusSelect.on('select2:select', function(e) {
                        const statusValue = $(this).val();
                        if (statusValue) {
                            table.ajax.url('{{ route('report.index') }}?status=' + statusValue);
                        } else {
                            table.ajax.url('{{ route('report.index') }}');
                        }
                            table.ajax.reload();
                    });

                    statusSelect.on('select2:clear', function (e) {
                        table.ajax.url('{{ route('report.index') }}').load(); // Reload semua data
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

    .select2-container .select2-selection--single {
        height: 45px; /* Menyesuaikan tinggi agar lebih proporsional */
        padding-inline: 10px; /* Padding kiri dan kanan otomatis menyesuaikan dengan teks */
        font-size: 11pt; /* Ukuran font lebih besar untuk keterbacaan */
        border-radius: 8px; /* Membuat sudut lebih halus */
        border: 1px solid #ccc; /* Border abu-abu muda untuk kesan elegan */
        background-color: #ffffff; /* Latar belakang putih agar bersih */
        color: #000000; /* Warna teks abu-abu gelap untuk kontras yang baik */
        box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1); /* Menambahkan bayangan halus di sekitar dropdown */
        transition: all 0.3s ease; /* Menambahkan transisi halus saat berinteraksi */
        display: flex; /* Menjadikan container flex */
        align-items: center; /* Menyelaraskan teks di tengah secara vertikal */
        justify-content: space-between; /* Memastikan tombol x berada di sisi kanan */
    }

    /* Efek fokus pada select2 */
    .select2-container .select2-selection--single:focus {
        border-color: #3cff00; /* Mengubah border menjadi biru saat fokus */
        box-shadow: 0 0 5px rgba(0, 123, 255, 0.5); /* Menambahkan bayangan biru saat fokus */
        outline: none; /* Menghilangkan outline default */
    }
        .select2-container .select2-selection__clear {
        position: absolute;
        right: 10px; /* Menempatkan tombol "x" di sisi kanan */
    } 
    
    /* Tambahkan scroll vertikal ke dropdown Select2 */
    .custom-scroll-dropdown .select2-results__options {
        max-height: 150px; /* Tinggi maksimal dropdown */
        overflow-y: auto; /* Scroll vertikal aktif */
        border: 1px solid #ccc; /* Opsional: tambahkan border untuk dropdown */
        background-color: #ffffff; /* Opsional: latar belakang dropdown */
    }
</style>

