<x-layout bodyClass="g-sidenav-show  bg-gray-200">
    <x-navbars.sidebar activePage="Cip Cumulative Balance"></x-navbars.sidebar>
    <main class="main-content position-relative max-height-vh-100 h-100 border-radius-lg ">
        <!-- Navbar -->
        <x-navbars.navs.auth titlePage="Cip Cumulative Balance"></x-navbars.navs.auth>
        <!-- End Navbar -->
        <div class="container-fluid py-4">
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
                            <a href="#" class="btn btn-sm btn-primary mb-2" data-bs-toggle="modal" data-bs-target="#new-form">New Entry</a>                    
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
        <link href="https://cdn.jsdelivr.net/npm/simple-datatables@3.0.2/dist/style.css" rel="stylesheet" type="text/css">
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
        
                $('#cipCumBalTable').DataTable({
                    processing: true,
                    serverSide: true,
                    ajax:  {
                                url: "{{ route('cipcumbal.index') }}",
                                type: 'GET',
                                data: function (d) {
                                    d.status = 1; // Menambahkan filter status jika diperlukan
                                }
                            },
                    columns: [
                        {data: 'action', name: 'action', orderable: false, searchable: false, className: 'text-center'},
                        {data: 'id_ccb', name: 'id_ccb', className: 'text-center'},
                        {data: 'period_cip', name: 'period_cip', className: 'text-center'},
                        {data: 'bal_usd', name: 'bal_usd', className: 'text-center'},
                        {data: 'bal_rp', name: 'bal_rp', className: 'text-center'},
                        {data: 'cumbal_usd', name: 'cumbal_usd', className: 'text-center'},
                        {data: 'cumbal_rp', name: 'cumbal_rp', className: 'text-center'},
                        {data: 'report_status', name: 'report_status', className: 'text-center'}
                    ]
                });
        
                $('#cipCumBalTable').on('click', '.delete-btn', function() {
                    var id = $(this).data('id');
                    if(confirm('Are you sure you want to delete this item?')) {
                        $.ajax({
                            url: '/cipcumbal/' + id,
                            type: 'DELETE',
                            success: function(result) {
                                $('#cipCumBalTable').DataTable().ajax.reload();
                            }
                        });
                    }
                });
            });
        </script>
        
        @endpush
    </x-layout>
