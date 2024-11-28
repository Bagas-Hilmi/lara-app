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
                                                <th class="text-center">Category</th>
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
            @include('capex.engineering-capex')

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
                                    {data: 'project_desc', name: 'project_desc', 
                                        createdCell: function(td, cellData, rowData, rowIndex, colIndex){
                                            $(td).css('text-align','left');
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
                                    {data: 'PO_release', name: 'PO_release', className: 'text-right',   render: function(data, type) {
                                        if (type === 'display') {
                                            if(data === null || data ==="" || isNaN(Number(data))){
                                                return '-';
                                            }
                                            return '<div style="text-align: right;">' + data.toLocaleString() + '</div>';
                                        }
                                        return data;
                                    }},
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

                                                // Jika status adalah Close, tambahkan icon mata
                                                if (data === 'Close') {
                                                    return `
                                                        <span class="badge ${badgeClass} me-2">${data}</span>
                                                        <a href="#" class="view-pdf" data-file="${row.file_pdf}" data-id="${row.id_capex}">
                                                            <i class="fas fa-eye text-info" title="View PDF"></i>
                                                        </a>
                                                    `;
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

                            $(document).on('click', '.view-pdf', function(e) {
                                e.preventDefault();
                                const filePath = $(this).data('file'); // Mengambil nama file dari data attribute
                                const id = $(this).data('id'); // Mengambil ID Capex
                                const flag = 'view-pdf'; // Flag untuk membuka file PDF

                                // Menggunakan URL publik untuk membuka file
                                const fileUrl = '/storage/' + filePath; // Akses file menggunakan /storage/
                                window.open(fileUrl, '_blank');
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
                                    confirmButtonText: 'OK',
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
                                                    Swal.fire({
                                                        title: 'Deleted!',
                                                        text: 'Capex has been deleted.',
                                                        icon: 'success',
                                                        showConfirmButton: false,
                                                        timer: 1000
                                                    });

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
                                                d.flag = 'pocommitment';  
                                                d.id_porelease = porelease_id;  // Kirimkan porelease_id ke server
                                                d.status = 1;  // 
                                            }
                                        },
                                        columnDefs: [
                                            {
                                                targets: 0,
                                                searchable: false,
                                                orderable: false
                                            }
                                        ],
                                        order: [[2, 'asc']],
                                        columns: [
                                            { data:'purchasing_doc', createdCell: function(td, rowData, rowIndex, cellData){
                                                $(td).css('text-align', 'right');
                                            }},
                                            { data: 'reference_item', createdCell: function(td){
                                                $(td).css('text-align', 'right');
                                            }},
                                            { data: 'doc_date', name: 'doc_date', className: 'text-center'},
                                            { data: 'fiscal_year', name: 'fiscal_year', className: 'text-center'},
                                            { data: 'no_material', createdCell: function(td){
                                                $(td).css('text-align', 'left');
                                            }},
                                            { data: 'material_desc',
                                                createdCell: function(td, cellData, rowData, rowIndex, colIndex) {
                                                    $(td).css('text-align', 'left');
                                            }},
                                            { data: 'qty',createdCell:function(td){
                                                $(td).css('text-align', 'right');
                                            }},
                                            { data: 'uom', name: 'uom' , className: 'text-center'},
                                            { data: 'value_trancurr', createdCell: function(td){
                                                $(td).css('text-align', 'right');
                                            }},
                                            { data: 'tcurr', createdCell:function(td){
                                                $(td).css('text-align', 'center');
                                            }},
                                            { data: 'valuein_obj',
                                                createdCell: function(td, rowData, rowIndex, cellData) {
                                                    $(td).css('text-align', 'right');
                                                }
                                            },
                                            { data: 'cost_element',createdCell:function(td){
                                                $(td).css('text-align','right')
                                            }},
                                            { data: 'wbs',
                                                createdCell: function(td, cellData, rowData, rowIndex, colIndex) {
                                                    $(td).css('text-align', 'left');
                                                }
                                            }
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
    .dropdown-menu {
    max-height: 170px; /* Sesuaikan tinggi maksimal yang diinginkan */
    overflow-y: auto; /* Menambahkan scrollbar vertikal ketika konten melebihi max-height */
    overflow-x: hidden; /* Mencegah scrollbar horizontal */
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