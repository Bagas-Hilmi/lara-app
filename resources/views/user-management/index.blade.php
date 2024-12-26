<x-layout bodyClass="g-sidenav-show  bg-gray-200">

    <x-navbars.sidebar activePage="user-management"></x-navbars.sidebar>
    <main class="main-content position-relative max-height-vh-100 h-100 border-radius-lg ">
        <!-- Navbar -->
        <x-navbars.navs.auth titlePage=""></x-navbars.navs.auth>
        <!-- End Navbar -->
        <div class="container-fluid py-4">
            <div class="row">
                <div class="col-12">
                    <div class="card my-4">
                        <div class="card-header p-0 position-relative mt-n4 mx-3 z-index-2">
                            <div style="background-color: #31B005;" class="shadow-primary border-radius-lg pt-4 pb-3">
                                <h2 class="text-white text-capitalize ps-3">User Management</h2>
                            </div>
                            <div class="card-body p-3">
                                <div class="d-flex mb-2">
                                    <button href="#" class="btn btn-sm btn-primary"
                                        style="background-color: #09170a; border-color: #09170a;" data-bs-toggle="modal"
                                        data-bs-target="#new-form">New User
                                    </button>
                                </div>
                                <div class="table-responsive p-0">
                                    <table id="user-table" class="table table-striped nowrap rounded-table p-0"
                                        style="width:100%">
                                        <thead>
                                            <tr>
                                                <th class="text-center">Action</th>
                                                <th class="text-center">Name</th>
                                                <th class="text-center">Email</th>
                                                <th class="text-center">Roles</th>
                                                <th class="text-center">Created At</th>
                                                <th class="text-center">Updated At</th>
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
            <script src="{{ asset('assets/js/jquery.min.js') }}"></script>
            <script src="{{ asset('assets/js/sweetalert2.all.min.js') }}"></script>
            <script src="{{ asset('assets/datatables/dataTables.min.js') }}"></script>
            <script src="assets/js/moment.min.js"></script>
            <script src="{{ asset('/js/tooltip.js') }}"></script>

            <script>
                $(document).ready(function() {
                    $.ajaxSetup({
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        }
                    });

                    // Inisialisasi DataTable
                    var table = $('#user-table').DataTable({
                        responsive: true,
                        processing: true,
                        serverSide: true,
                        order: [
                            [1, 'desc']
                        ],
                        ajax: {
                            url: "{{ route('user-management.index') }}",
                            type: 'GET',
                        },
                        columns: [{
                                data: 'action',
                                name: 'action',
                                orderable: false,
                                searchable: false,
                                className: 'text-center'
                            },
                            {
                                data: 'id',
                                name: 'id'
                            },
                            {
                                data: 'name',
                                name: 'name',
                                className: 'text-center'
                            },

                            {
                                data: 'email',
                                name: 'email',
                                className: 'text-right',
                            },
                            {
                                data: 'created_at',
                                name: 'created_at',
                                className: 'text-center',
                                render: function(data) {
                                    const date = new Date(data);
                                    const year = date.getFullYear();
                                    const month = String(date.getMonth() + 1).padStart(2, '0');
                                    const day = String(date.getDate()).padStart(2, '0');
                                    return `${year}-${month}-${day}`;
                                }
                            },
                            {
                                data: 'updated_at',
                                name: 'updated_at',
                                className: 'text-center',
                                render: function(data) {
                                    return moment(data).format(
                                        'YYYY-MM-DD'); // jika menggunakan moment.js
                                }
                            }
                        ]

                    });
                });
            </script>

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
        #faglb-table {
            width: 100%;
            /* Memastikan tabel mengambil 100% lebar */
        }
    }
</style>
