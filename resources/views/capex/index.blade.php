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
                                <div class="d-flex justify-content-between mb-2">
                                    <!-- Tombol New Capex -->
                                    <button href="#" class="btn btn-sm btn-primary" 
                                        style="background-color: #09170a; border-color: #09170a;"  
                                        data-bs-toggle="modal" 
                                        data-bs-target="#new-form">
                                        New capex
                                    </button>
                                    <div class="dropdown">
                                        <button class="btn btn-secondary dropdown-toggle" style="background-color: #09170a; border-color: #09170a;" type="button" id="yearDropdown" data-bs-toggle="dropdown" aria-expanded="false">
                                            <span id="yearText">Pilih Tahun</span> <!-- Placeholder -->
                                        </button>
                                        <ul class="dropdown-menu" aria-labelledby="yearDropdown">
                                            <li><a class="dropdown-item" href="#" data-value="">Semua Tahun</a></li>
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
                                                <th class="text-center">ID Capex</th>
                                                <th class="text-center">Project Desc</th>
                                                <th class="text-center">WBS Capex </th>
                                                <th class="text-center">Remark</th>
                                                <th class="text-center">Request Number</th>
                                                <th class="text-center">Requester</th>
                                                <th class="text-center">Capex Number</th>
                                                <th class="text-center">Amount Budget (USD)</th>
                                                <th class="text-center">Budget Cos (USD)</th>
                                                <th class="text-center">PO Release</th>
                                                <th class="text-center">Status Capex</th>
                                                <th class="text-center">Budget Type</th>
                                                <th class="text-center">Start Up</th>
                                                <th class="text-center">Expected Completed</th>
                                                <th class="text-center">Revise Completion Date</th>
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

                    @push('js')
                    <script src="{{ asset('assets/js/jquery.min.js') }}"></script>
                    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.all.min.js"></script>
                    <script src="{{ asset('assets/datatables/dataTables.min.js') }}"></script>
                    <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.29.1/moment.min.js"></script>
                    <script src="{{ asset('/js/tooltip.js') }}"></script>
                    
                    <script>
                        $(document).ready(function() {
                            const currentYear = new Date().getFullYear();
                            $('#yearFilter').val(currentYear);
                            $('#yearText').val(currentYear);
                            
                            $.ajaxSetup({
                                headers: {
                                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                                }
                            });
                
                            // Initialize DataTable
                            var table = $('#capex-table').DataTable({
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
                                        d.status = 1; p
                                        d.year = $('#yearFilter').val();
                                    }
                                },
                                columnDefs: [
                                    {
                                        targets: '_all',
                                        className: 'text-nowrap'
                                    }
                                ],
                
                                columns: [
                                    {data: 'action', name: 'action', orderable: false, searchable: false, className: 'text-center'},
                                    {data: 'id_capex', name: 'id_capex', className: 'text-center'},
                                    {data: 'project_desc', name: 'project_desc', className: 'text-start'},
                                    {data: 'wbs_capex', name: 'wbs_capex', className: 'text-start'},
                                    {data: 'remark', name: 'remark', className: 'text-start'},
                                    {data: 'request_number', name: 'request_number', className: 'text-right', render: function(data, type, row) {
                                        return '<div style="text-align: right;">' + data + '</div>';
                                    }},
                                    {data: 'requester', name: 'requester', className: 'text-start'},
                                    {data: 'capex_number', name: 'capex_number', className: 'text-right'},
                                    {data: 'amount_budget', name: 'amount_budget', className: 'text-right', 
                                    render: function(data, type) {
                                        if (type === 'display') {
                                            return '<div style="text-align: right;">' + parseFloat(data).toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",") + '</div>';
                                        }
                                        return data;
                                    }},
                                    {data: 'budget_cos', name: 'budget_cos', className: 'text-right', 
                                    render: function(data, type) {
                                        if (type === 'display') {
                                            return '<div style="text-align: right;">' + parseFloat(data).toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",") + '</div>';
                                        }
                                        return data;
                                    }},
                                    {data: 'PO_release', name: 'PO_release', className: 'text-right',   render: function(data, type) {
                                        if (type === 'display') {
                                            return '<div style="text-align: right;">' + parseFloat(data).toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",") + '</div>';
                                        }
                                        return data;
                                    }},
                                    {data: 'status_capex', name: 'status_capex', className: 'text-start'},
                                    {data: 'budget_type', name: 'budget_type', className: 'text-center'},
                                    {data: 'startup', name: 'startup', className: 'text-center'},
                                    {data: 'expected_completed', name: 'expected_completed', className: 'text-center'},
                                    {data: 'revise_completion_date', name: 'revise_completion_date', className: 'text-center'},
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
        border-radius: 12px; /* Adjust the radius as needed */
        overflow: hidden; /* Ensures child elements respect the border radius */
    }
    .rounded-table th,
    .rounded-table td {
        border: none; /* Remove default borders to maintain rounded appearance */
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
    }   

    /* Gaya untuk sel tabel */
    #capex-table tbody td {
        padding: 10px; /* Padding untuk sel */
        border-bottom: 1px solid #dee2e6; /* Garis bawah sel */
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

</style>