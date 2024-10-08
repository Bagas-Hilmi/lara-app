<x-layout bodyClass="g-sidenav-show  bg-gray-200">
    <x-navbars.sidebar activePage='dashboard'></x-navbars.sidebar>
    <main class="main-content position-relative max-height-vh-100 h-100 border-radius-lg ">
        <!-- Navbar -->
        <x-navbars.navs.auth titlePage=""></x-navbars.navs.auth>
        <!-- End Navbar -->
        <div class="container-fluid py-4">
            <div class="row">
                <div class="col-19">
                    <div class="card my-4">
                        <div class="card-header p-0 position-relative mt-n4 mx-3 z-index-2">
                            <div style="background-color: #3cb210;" class="shadow-primary border-radius-lg pt-4 pb-3"> 
                                <h3 class="text-white text-capitalize ps-3">Master Capex</h3>
                            </div>
                            <div class="card-body p-3">
                                <div class="d-flex justify-content-between mb-2">
                                    <!-- Tombol New Capex -->
                                    <button href="#" class="btn btn-sm btn-primary" style="background-color: #09170a; border-color: #09170a;"  data-bs-toggle="modal" data-bs-target="#new-form">New capex</button>
                                </div>
                                <div class="table-responsive p-0">
                                    <table id="capex-table" class="table table-striped nowrap rounded-table p-0" style="width:100%">
                                        <thead>
                                            <tr>
                                                <th align="center">Action</th>
                                                <th align="center">ID Capex</th>
                                                <th align="center">Project Desc</th>
                                                <th align="center">WBS Capex </th>
                                                <th align="center">Remark</th>
                                                <th align="center">Request Number</th>
                                                <th align="center">Requester</th>
                                                <th align="center">Capex Number</th>
                                                <th align="center">Amount Budget</th>
                                                <th align="center">Budget Cos</th>
                                                <th align="center">Status Capex</th>
                                                <th align="center">Budget Type</th>
                                                <th align="center">Created_at</th>
                                                <th align="center">Updated_at</th>
                                            </tr>
                                        </thead>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
            </div>
            <x-footers.auth></x-footers.auth>
            
            @include('capex.modal.new-capex')
            @include('capex.modal.edit-capex')

                    @push('js')
                    <script src="{{ asset('assets/js/jquery.min.js') }}"></script>
                    <script src="{{ asset('js/faglb/add-doc.js') }}"></script>
                    <script src="{{ asset('js/faglb/update-doc.js') }}"></script>
                    <script src="{{ asset('js/faglb/delete-doc.js') }}"></script>
                    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.all.min.js"></script>
                    <script src="{{ asset('assets/datatables/dataTables.min.js') }}"></script>
                    <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.29.1/moment.min.js"></script>
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
                            var table = $('#capex-table').DataTable({
                                responsive: true,
                                processing: true,
                                serverSide: true,
                                order: [[1, 'desc']],
                                ajax: {
                                    url: "{{ route('capex.index') }}",
                                    type: "GET",
                                },
                                columns: [
                                    {data: 'action', name: 'action', orderable: false, searchable: false, className: 'text-center'},
                                    {data: 'id_capex', name: 'id_capex', className: 'text-center'},
                                    {data: 'project_desc', name: 'project_desc', className: 'text-center'},
                                    {data: 'wbs_capex', name: 'wbs_capex', className: 'text-center'},
                                    {data: 'remark', name: 'remark', className: 'text-center'},
                                    {data: 'request_number', name: 'request_number', className: 'text-center'},
                                    {data: 'requester', name: 'requester', className: 'text-center'},
                                    {data: 'capex_number', name: 'capex_number', className: 'text-center'},
                                    {data: 'amount_budget', name: 'amount_budget', className: 'text-center'},
                                    {data: 'budget_cos', name: 'budget_cos', className: 'text-center'},
                                    {data: 'status_capex', name: 'status_capex', className: 'text-center'},
                                    {data: 'budget_type', name: 'budget_type', className: 'text-center'},
                                    {
                                        data: 'created_at',
                                        name: 'created_at',
                                        className: 'text-center',
                                        render: function(data) {
                                            return moment(data).format('YYYY-MM-DD HH:mm:ss'); 
                                        }
                                    },
                                    {
                                        data: 'updated_at',
                                        name: 'updated_at',
                                        className: 'text-center',
                                        render: function(data) {
                                            return moment(data).format('YYYY-MM-DD HH:mm:ss'); 
                                        }
                                    }
                                ]
                            });
                
                            $(document).on('click', '.delete-capex', function() {
                                var capexId = $(this).data('id');
                                if (confirm('Are you sure you want to delete this item?')) {
                                    $.ajax({
                                        url: '/capex/' + capexId,
                                        type: 'DELETE',
                                        data: {
                                            _token: '{{ csrf_token() }}'
                                        },
                                        success: function(response) {
                                            if (response.success) {
                                                alert('Capex deleted successfully!');
                                                $('#capex-table').DataTable().ajax.reload();
                                            } else {
                                                alert('Failed to delete capex!');
                                            }
                                        },
                                        error: function(xhr) {
                                            alert('Something went wrong! Please try again.');
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

</style>