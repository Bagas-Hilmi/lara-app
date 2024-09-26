<x-layout bodyClass="g-sidenav-show  bg-gray-200">
    <x-navbars.sidebar activePage="UPDOC"></x-navbars.sidebar>
    <main class="main-content position-relative max-height-vh-100 h-100 border-radius-lg ">
        <!-- Navbar -->
        <x-navbars.navs.auth titlePage="UP-Doc FGLB + ZLIS1"></x-navbars.navs.auth>
        <!-- End Navbar -->
        <div class="container-fluid py-4">
            <div class="row">
                <div class="col-12">
                    <div class="card my-4">
                        <div class="card-header p-0 position-relative mt-n4 mx-3 z-index-2">
                            <div class="bg-gradient-primary shadow-primary border-radius-lg pt-4 pb-3">
                                <h3 class="text-white text-capitalize ps-3">Upload Document FAGLB + ZLIS1</h3>
                            </div>
                            <div class="card-body p-3">
                                <div class="d-flex justify-content-between mb-2">
                                    <!-- Tombol Add Doc Upload -->
                                    <a href="#" class="btn btn-sm btn-primary" data-bs-toggle="modal" data-bs-target="#addDocFormModal" data-url="{{ route('faglb.create') }}">Add Doc Upload</a>
                                   
                                </div>

                                <div class="table-responsive p-0">
                                    <table id="faglb-table" class="table table-striped nowrap table-responsive p-0" style="width:100%">
                                        <thead>
                                            <tr>
                                                <th align="center">Action</th>
                                                <th align="center">ID Head</th>
                                                <th align="center">ID CipCumBal</th>
                                                <th align="center">Period </th>
                                                <th align="center">Report Status</th>
                                                <th align="center">Created At</th>
                                                <th align="center">Updated At</th>
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

            @include('faglb.modal.add-doc-modal')
            @include('faglb.modal.update-doc-modal')

            @push('css')
            <link href="{{ asset('assets/datatables/dataTables.min.css') }}" rel="stylesheet">
            @endpush

            @push('js')
            <script src="{{ asset('assets/js/jquery.min.js') }}"></script>
            <script src="{{ asset('assets/datatables/dataTables.min.js') }}"></script>
            <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.29.1/moment.min.js"></script>

            
            <script src="{{ asset('js/add-doc.js') }}"></script>
            <script src="{{ asset('js/update-doc.js') }}"></script>

            <script>
                $(document).ready(function() {
                    $.ajaxSetup({
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        }
                    });
            
                    var table = $('#faglb-table').DataTable({
                        responsive: true,
                        processing: true,
                        serverSide: true,
                        ajax: {
                            url: "{{ route('faglb.index') }}",
                            type: "GET",
                        },
                        columns: [
                            {data: 'action', name: 'action', orderable: false, searchable: false},
                            {data: 'id_head', name: 'id_head', className: 'text-center'},
                            {data: 'id_ccb', name: 'id_ccb', className: 'text-center'},
                            {data: 'period', name: 'period', className: 'text-center'},
                            {data: 'report_status', name: 'report_status', className: 'text-center',                         },
                            {
                                data: 'created_at', 
                                name: 'created_at', 
                                className: 'text-center', 
                                render: function(data) {
                                    return moment(data).format('YYYY-MM-DD HH:mm:ss'); // jika menggunakan moment.js
                                }
                            },
                            {
                                data: 'updated_at', 
                                name: 'updated_at', 
                                className: 'text-center', 
                                render: function(data) {
                                    return moment(data).format('YYYY-MM-DD HH:mm:ss'); // jika menggunakan moment.js
                                }
                            }
                        ],
                    });   
                        // Event listener for delete button
                        $('#faglb-table').on('click', '.delete-btn', function() {
                            var id = $(this).data('id');  // Ambil ID dari data-id
                            if (confirm('Apakah Anda yakin ingin menghapus item ini?')) {
                                $.ajax({
                                    url: '/faglb/' + id,  // Ganti dengan URL yang sesuai untuk penghapusan
                                    type: 'POST',  // Menggunakan POST
                                    data: {
                                        _method: 'DELETE'  // Mengindikasikan metode DELETE
                                    },
                                    success: function(result) {
                                        table.ajax.reload();  // Reload tabel
                                    },
                                    error: function(xhr) {
                                        alert('Terjadi kesalahan saat menghapus item: ' + xhr.responseText);
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