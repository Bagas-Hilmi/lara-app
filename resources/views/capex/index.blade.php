@section('title', 'Master Capex')

<x-layout bodyClass="g-sidenav-show  bg-gray-200">
    <x-navbars.sidebar activePage='capex'></x-navbars.sidebar>
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
                                <h3 class="text-white text-capitalize ps-3">Master Capex</h3>
                            </div>
                            <div class="card-body p-3">
                                <div class="d-flex mb-2">
                                    <!-- Tombol New Capex -->
                                    <button href="#" class="btn btn-sm btn-primary" 
                                        style="background-color: #09170a; border-color: #09170a;"  
                                        data-bs-toggle="modal" 
                                        data-bs-target="#new-form">
                                        New capex
                                    </button>

                                    <button href="#" class="btn btn-sm btn-primary ms-2" 
                                        style="background-color: #09170a; border-color: #09170a;"  
                                        data-bs-toggle="modal" 
                                        data-bs-target="#engineer-form">
                                        Engineering
                                    </button>
                                    
                                    
                                    <div class="dropdown ms-2">
                                        <button class="btn btn-secondary dropdown-toggle" style="background-color: #09170a; border-color: #09170a;" type="button" id="yearDropdown" data-bs-toggle="dropdown" aria-expanded="false">
                                            <span id="yearText"></span> <!-- Placeholder -->
                                        </button>
                                        <ul class="dropdown-menu" aria-labelledby="yearDropdown">
                                            @foreach ($availableYears as $year)
                                                <li><a class="dropdown-item" href="#" data-value="{{ $year }}">{{ $year }}</a></li>
                                            @endforeach
                                        </ul>
                                    </div>
                                    
                                </div>
                                <input type="hidden" id="yearFilter" name="year" value="">

                                <div class="table-responsive p-0">
                                    <table id="capex-table" class="table table-striped nowrap rounded-table p-0" style="width:100%">
                                        <thead>
                                            <tr>
                                                <th class="text-center">Action</th>
                                                <th class="text-center">ID</th>
                                                <th class="text-center">Project Desc</th>
                                                <th class="text-center">WBS Capex </th>
                                                <th class="text-center">Remark</th>
                                                <th class="text-center">Req Number</th>
                                                <th class="text-center">Requester</th>
                                                <th class="text-center">Capex Number</th>
                                                <th class="text-center">Amount Budget (USD)</th>
                                                <th class="text-center">Budget Cos (USD)</th>
                                                <th class="text-center">Total Budget</th>
                                                <th class="text-center">PO Release</th>
                                                <th class="text-center">Status Capex</th>
                                                <th class="text-center">Budget Type</th>
                                                <th class="text-center">Start Up</th>
                                                <th class="text-center">Exp Completed</th>
                                                <th class="text-center">Rev Completion Date</th>
                                                <th class="text-center">Days Remaining</th>
                                                <th class="text-center">Days Late</th>
                                                <th class="text-center">WBS Number</th>
                                                <th class="text-center">CIP Number</th>
                                                <th class="text-center">Created_at</th>
                                                <th class="text-center">Updated_at</th>
                                            </tr>
                                        </thead>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <x-footers.auth></x-footers.auth>
            
            @include('capex.modal.new-capex')
            @include('capex.modal.edit-capex')
            @include('capex.budget-capex')
            @include('capex.progress-capex')
            @include('capex.porelease-capex')
            @include('capex.completion-capex')
            @include('capex.status-capex')
            @include('capex.modal.view-commitment')
            @include('capex.modal.engineering-capex')

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
                
                            // Initialize DataTable
                            var table = $('#capex-table').DataTable({
                                responsive: false,
                                info: true,
                                paging: true,
                                searching: true,
                                ordering: true,
                                processing: true,
                                serverSide: true,
                                order: [[1, 'desc']],
                                ajax: {
                                    url: "{{ route('capex.index') }}",
                                    type: "GET",
                                    data: function (d) {
                                        d.status = 1; // Filter status tetap
                                        d.year = $('#yearFilter').val(); // Tambahkan filter tahun dari dropdown
                                    }
                                },
                
                                columns: [
                                    {data: 'action', name: 'action', orderable: false, searchable: false, className: 'text-center'},
                                    {data: 'id_capex', name: 'id_capex', className: 'text-center'},
                                    {data: 'project_desc', name: 'project_desc', className: 'text-start'},
                                    {data: 'wbs_capex', name: 'wbs_capex', className: 'text-start',
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
                                    {data: 'remark', name: 'remark', className: 'text-start'},
                                    {data: 'request_number', name: 'request_number', className: 'text-right', render: function(data, type, row) {
                                        return '<div style="text-align: right;">' + data + '</div>';
                                    }},
                                    {data: 'requester', name: 'requester', className: 'text-start'},
                                    {data: 'capex_number', name: 'capex_number', className: 'text-right'},
                                    {data: 'amount_budget', name: 'amount_budget', className: 'text-right', 
                                    render: function(data, type) {
                                        if (type === 'display') {
                                            return '<div style="text-align: right;">' + parseFloat(data).toString().replace(/\B(?=(\d{3})+(?!\d))/g, ".") + '</div>';
                                        }
                                        return data;
                                    }},
                                    {data: 'budget_cos', name: 'budget_cos', className: 'text-right', 
                                    render: function(data, type) {
                                        if (type === 'display') {
                                            // Cek jika data kosong
                                            if (data === null || data === "" || isNaN(data)) {
                                                return '-'; 
                                            }
                                            return '<span>' + parseFloat(data).toString().replace(/\B(?=(\d{3})+(?!\d))/g, ".") + '</span>';
                                        }
                                        return data;
                                    }},
                                    {data: 'total_budget', name: 'total_budget', className: 'text-right', 
                                    render: function(data, type) {
                                        if (type === 'display') {
                                            return '<div style="text-align: right;">' + parseFloat(data).toString().replace(/\B(?=(\d{3})+(?!\d))/g, ".") + '</div>';
                                        }
                                        return data;
                                    }},
                                    {data: 'PO_release', name: 'PO_release', className: 'text-right',   render: function(data, type) {
                                        if (type === 'display') {
                                            if(data === null || data ==="" || isNaN(data)){
                                                return '-';
                                            }
                                            return '<div style="text-align: right;">' + parseFloat(data).toString().replace(/\B(?=(\d{3})+(?!\d))/g, ".") + '</div>';
                                        }
                                        return data;
                                    }},
                                    {data: 'status_capex', name: 'status_capex', className: 'text-start',
                                    render: function(data, type, row) {
                                        if (type === 'display') {
                                            if (data === 'Canceled') {
                                                return '<span class="badge bg-danger">Canceled</span>';
                                            } else if (data === 'Close') {
                                                return '<span class="badge bg-secondary">Close</span>';
                                            } else if (data === 'On Progress') {
                                                return '<span class="badge bg-success">On Progress</span>';
                                            } else if (data === 'To Opex') {
                                                return '<span class="badge bg-info">To Opex</span>';
                                            }
                                            return data; // Untuk nilai lain tampilkan apa adanya
                                        }
                                        return data;
                                    }},
                                    {data: 'budget_type', name: 'budget_type', className: 'text-center'},
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
                                    {data: 'days_remaining', name: 'days_remaining', className: 'text-center',
                                    render: function(data) {
                                            if (data === null || data === undefined) {
                                                return '-';}
                                            return data;
                                    }},
                                    {data: 'days_late', name: 'days_late', className: 'text-center',
                                    render: function(data) {
                                            if (data === null || data === undefined) {
                                                return '-';}
                                            return data;
                                    }},
                                    {data: 'wbs_number', name: 'wbs_number', className: 'text-center'},
                                    {data: 'cip_number', name: 'cip_number', className: 'text-center'},
                                    {
                                        data: 'created_at',
                                        name: 'created_at',
                                        className: 'text-center',
                                        render: function(data) {
                                            return moment(data).format('YYYY-MM-DD'); 
                                        }
                                    },
                                    {
                                        data: 'updated_at',
                                        name: 'updated_at',
                                        className: 'text-center',
                                        render: function(data) {
                                            return moment(data).format('YYYY-MM-DD'); 
                                        }
                                    }
                                ]
                            });

                            $('#yearDropdown').next('.dropdown-menu').find('.dropdown-item').on('click', function () {
                                var year = $(this).data('value'); // Ambil nilai tahun
                                $('#yearFilter').val(year); // Set nilai tahun ke input tersembunyi
                                
                                // Ubah teks tombol untuk menampilkan tahun yang dipilih
                                if (year) {
                                    $('#yearText').text(year); // Jika ada tahun yang dipilih
                                } else {
                                    $('#yearText').text('Pilih Tahun'); // Jika semua tahun dipilih
                                }

                                table.ajax.reload(); // Reload DataTable
                            });
                
                            $(document).on('click', '.delete-capex', function() {
                                var capexId = $(this).data('id');

                                // SweetAlert confirmation
                                Swal.fire({
                                    title: 'Konfirmasi Hapus?',
                                    text: "Apakah Anda yakin ingin menghapus item ini?",
                                    icon: 'warning',
                                    showCancelButton: true,
                                    confirmButtonColor: '#3085d6',
                                    cancelButtonColor: '#d33',
                                    confirmButtonText: 'Ya, hapus!',
                                    cancelButtonText: 'Batal'
                                }).then((result) => {
                                    if (result.isConfirmed) {
                                        $.ajax({
                                            url: '/capex/' + capexId,
                                            type: 'DELETE',
                                            data: {
                                                _token: '{{ csrf_token() }}',
                                                flag: 'capex'
                                            },
                                            success: function(response) {
                                                if (response.success) {
                                                    // SweetAlert success notification
                                                    Swal.fire(
                                                        'Deleted!',
                                                        'Capex has been deleted.',
                                                        'success'
                                                    );

                                                    // Reload the DataTable
                                                    $('#capex-table').DataTable().ajax.reload();
                                                } else {
                                                    Swal.fire(
                                                        'Failed!',
                                                        'Failed to delete capex!',
                                                        'error'
                                                    );
                                                }
                                            },
                                            error: function(xhr) {
                                                Swal.fire(
                                                    'Error!',
                                                    'Something went wrong! Please try again.',
                                                    'error'
                                                );
                                            }
                                        });
                                    }
                                });
                            });
                            let pocommitmentTable;

                            $(document).ready(function() {
                                // Inisialisasi variabel pocommitmentTable
                                pocommitmentTable = null;

                                // Event handler untuk menampilkan modal
                                $('#viewPocommitmentModal').on('show.bs.modal', function (event) {
                                    // Ambil elemen tombol yang diklik
                                    var button = $(event.relatedTarget);

                                    // Ambil nilai data-porelease-id dari tombol
                                    var porelease_id = button.data('porelease-id');

                                    // Hancurkan DataTable jika sudah ada
                                    if (pocommitmentTable) {
                                        pocommitmentTable.destroy();
                                    }

                                    // Inisialisasi DataTable baru
                                    pocommitmentTable = $('#pocommitment-table').DataTable({
                                        processing: true,
                                        serverSide: true,
                                        ajax: {
                                            url: "{{ route('capex.show', ':id') }}".replace(':id', porelease_id), // Gunakan porelease_id di URL
                                            data: function(d) {
                                                d.flag = 'pocommitment';  // Flag untuk menentukan jenis data
                                                d.id_porelease = porelease_id;  // Kirimkan porelease_id ke server
                                                d.status = 1;  // Status yang diinginkan
                                            }
                                        },
                                        columnDefs: [
                                            {
                                                targets: 0,
                                                searchable: false,
                                                orderable: false
                                            }
                                        ],
                                        order: [[1, 'asc']],
                                        columns: [
                                            { data: 'doc_date', name: 'doc_date', className: 'text-center' , className: 'text-center'},
                                            { data: 'wbs_object', name: 'wbs_object' , className: 'text-center'},
                                            { data: 'cost_element', name: 'cost_element' , className: 'text-center'},
                                            { data: 'purchasing_doc', name: 'purchasing_doc' , className: 'text-center'},
                                            { data: 'reference_item', name: 'reference_item' , className: 'text-center'},
                                            { data: 'no_material', name: 'no_material' , className: 'text-center'},
                                            { data: 'material_desc', name: 'material_desc' , className: 'text-center'},
                                            { data: 'qty', name: 'qty' , className: 'text-center'},
                                            { data: 'uom', name: 'uom' , className: 'text-center'},
                                            { data: 'valuein_obj', name: 'valuein_obj' , className: 'text-center'},
                                            { data: 'obj_currency', name: 'obj_currency' , className: 'text-center'},
                                            { data: 'value_trancurr', name: 'value_trancurr' , className: 'text-center'},
                                            { data: 'tcurr', name: 'tcurr' }
                                        ]
                                    });
                                });

                                // Event handler untuk menutup modal
                                $('#viewPocommitmentModal').on('hidden.bs.modal', function() {
                                    // Hancurkan DataTable saat modal ditutup untuk membersihkan DataTable sebelumnya
                                    if (pocommitmentTable) {
                                        pocommitmentTable.destroy();
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
        #capex-table {
            width: 100%; /* Memastikan tabel mengambil 100% lebar */
        }
    }
    
    .rounded-table { 
        border-radius: 12px; 
        overflow: visible ; /* Untuk dropdown */
    }

    .table-responsive {
        overflow-y: hidden !important; /* Menghilangkan scrollbar vertikal */
        overflow-x: auto !important; /* Tetap bisa scroll horizontal jika perlu */
    }
    .rounded-table th,
    .rounded-table td {
        border: none; /* Remove default borders to maintain rounded appearance */
        overflow: visible !important; /* Diubah dari hidden ke visible */

    }

    #capex-table thead th {
    background-color: #3cb210; /* Warna latar belakang header */
    color: #ffffff; /* Warna teks header */
    }
    
    #budget-table thead th {
    background-color: #3cb210; /* Warna latar belakang header */
    color: #ffffff; /* Warna teks header */
    }

    /* Gaya untuk baris tabel */
    #capex-table tbody tr {
        transition: background-color 0.3s ease; /* Efek transisi untuk warna latar belakang */
        color: #2c2626;
    }   

    /* Gaya untuk sel tabel */
    #capex-table tbody td {
        padding: 10px; /* Padding untuk sel */
        border-bottom: 1px solid #dee2e6; /* Garis bawah sel */
        color: #2c2626;
    }

    /* Hover effect untuk baris tabel */
    #capex-table tbody tr:hover {
        background-color: rgba(0, 123, 255, 0.1); /* Warna latar belakang saat hover */
    }
    #capex-table th, #capex-table td {
        padding: 8px;
        text-align: center;
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
    .form-control {
        border: 1px solid #ccc; /* Customize the border */
        box-shadow: none; /* Remove shadow */
        border-radius: 4px; /* Tambahkan sudut melengkung */
    }
    .form-control:focus {
        border-color: #42bd37; /* Warna border saat fokus */
        box-shadow: 0 0 5px rgba(66, 189, 55, 0.5); /* Menambah efek shadow saat fokus */
    }

    .dropdown {
        position: relative;
    }
    .badge {
        font-size: 0.875rem;
        
    }
    /* Memastikan alignment badge tetap rapi */
    .text-start {
        text-align: left !important;
        padding: 8px;
    }

</style>