    <div class="modal fade" id="progressAPV" tabindex="-1" aria-labelledby="progressAPVLabel">
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header" style="background-color: #42bd37;">
                    <h5 class="modal-title" id="progressAPVLabel" style="color: white;">Progress Approval</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="table-responsive"> <!-- Tambahkan div ini untuk responsivitas -->
                        <table id="approvalTable" class="table table-striped">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>Project Title</th>
                                    <th>Project Type</th>
                                    <th>Assigned To</th>
                                    <th>Status Capex</th>
                                    <th>Approval Admin</th>
                                    <th>Approval Manager</th>
                                    <th>Approval User</th>
                                    <th>Approval Engineering</th>
                                    <th>Upload Date</th>
                                </tr>
                            </thead>
                            <tbody>

                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        $(document).ready(function() {
            // Setup CSRF token for AJAX requests
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
            var progressTable = $('#approvalTable').DataTable({
                processing: true,
                serverSide: true,
                responsive: true,
                ajax: {
                    url: "{{ route('approve.index') }}", // Menggunakan route resource yang sama
                    data: function(d) {
                        d.type = 'progress'; // Menambah parameter type untuk membedakan request
                    }
                },
                columns: [{
                        data: 'DT_RowIndex',
                        name: 'DT_RowIndex',
                        orderable: false,
                        searchable: false
                    },
                    {
                        data: 'project_desc',
                        name: 'project_desc'
                    },
                    {
                        data: 'wbs_capex',
                        name: 'wbs_capex'
                    },
                    {
                        data: 'upload_by',
                        name: 'upload_by'
                    },
                    {
                        data: 'status_capex',
                        name: 'status_capex',
                        render: function(data, type, row) {
                            let badgeClass = '';
                            switch (data) {
                                case 'On Progress':
                                    badgeClass = 'bg-gradient-info';
                                    break;
                                case 'Waiting Approval':
                                    badgeClass = 'bg-gradient-secondary';
                                    break;
                                default:
                                    return data;
                            }
                            return `<span class="badge ${badgeClass}">${data}</span>`;
                        }
                    },
                    {
                        data: 'status_approve_1',
                        name: 'status_approve_1',
                        render: function(data, type, row) {
                            if (type === 'display') {
                                let statusText = '';
                                let badgeClass = '';

                                // Tentukan nilai status berdasarkan data
                                switch (data) {
                                    case 0:
                                        statusText = 'Pending';
                                        badgeClass =
                                        'bg-gradient-warning'; // Ganti dengan kelas badge yang sesuai
                                        break;
                                    case 1:
                                        statusText = 'Approve';
                                        badgeClass =
                                        'bg-gradient-success'; // Ganti dengan kelas badge yang sesuai
                                        break;
                                    case 2:
                                        statusText = 'Disapprove';
                                        badgeClass =
                                        'bg-danger'; // Ganti dengan kelas badge yang sesuai
                                        break;
                                }

                                // Kembalikan HTML dengan badge
                                return `<span class="badge ${badgeClass}">${statusText}</span>`;
                            }
                            return data;
                        }
                    },

                    {
                        data: 'status_approve_4',
                        name: 'status_approve_4',
                        render: function(data, type, row) {
                            if (type === 'display') {
                                let statusText = '';
                                let badgeClass = '';

                                // Tentukan nilai status berdasarkan data
                                switch (data) {
                                    case 0:
                                        statusText = 'Pending';
                                        badgeClass =
                                        'bg-gradient-warning'; // Ganti dengan kelas badge yang sesuai
                                        break;
                                    case 1:
                                        statusText = 'Approve';
                                        badgeClass =
                                        'bg-gradient-success'; // Ganti dengan kelas badge yang sesuai
                                        break;
                                    case 2:
                                        statusText = 'Disapprove';
                                        badgeClass =
                                        'bg-danger'; // Ganti dengan kelas badge yang sesuai
                                        break;
                                }

                                // Kembalikan HTML dengan badge
                                return `<span class="badge ${badgeClass}">${statusText}</span>`;
                            }
                            return data;
                        }
                    },
                    {
                        data: 'status_approve_3',
                        name: 'status_approve_3',
                        render: function(data, type, row) {
                            if (type === 'display') {
                                let statusText = '';
                                let badgeClass = '';

                                // Tentukan nilai status berdasarkan data
                                switch (data) {
                                    case 0:
                                        statusText = 'Pending';
                                        badgeClass =
                                        'bg-gradient-warning'; // Ganti dengan kelas badge yang sesuai
                                        break;
                                    case 1:
                                        statusText = 'Approve';
                                        badgeClass =
                                        'bg-gradient-success'; // Ganti dengan kelas badge yang sesuai
                                        break;
                                    case 2:
                                        statusText = 'Disapprove';
                                        badgeClass =
                                        'bg-danger'; // Ganti dengan kelas badge yang sesuai
                                        break;
                                }

                                // Kembalikan HTML dengan badge
                                return `<span class="badge ${badgeClass}">${statusText}</span>`;
                            }
                            return data;
                        }
                    },
                    {
                        data: 'status_approve_2',
                        name: 'status_approve_2',
                        render: function(data, type, row) {
                            if (type === 'display') {
                                let statusText = '';
                                let badgeClass = '';

                                // Tentukan nilai status berdasarkan data
                                switch (data) {
                                    case 0:
                                        statusText = 'Pending';
                                        badgeClass =
                                        'bg-gradient-warning'; // Ganti dengan kelas badge yang sesuai
                                        break;
                                    case 1:
                                        statusText = 'Approve';
                                        badgeClass =
                                        'bg-gradient-success'; // Ganti dengan kelas badge yang sesuai
                                        break;
                                    case 2:
                                        statusText = 'Disapprove';
                                        badgeClass =
                                        'bg-danger'; // Ganti dengan kelas badge yang sesuai
                                        break;
                                }

                                // Kembalikan HTML dengan badge
                                return `<span class="badge ${badgeClass}">${statusText}</span>`;
                            }
                            return data;
                        }
                    },
                    {
                        data: 'upload_date',
                        name: 'upload_date',
                        render: function(data) {
                            return moment(data).format('DD/MM/YYYY');
                        }
                    },

                ],
                order: [
                    [9, 'desc']
                ]
            });
        });
    </script>


<style>

    #approvalTable thead th {
        background-color: #3cb210; /* Warna latar belakang header */
        color: #ffffff; /* Warna teks header */
        width: auto; /* Atur lebar kolom header secara otomatis */
    }

    /* Gaya untuk baris tabel */
    #approvalTable tbody tr {
        transition: background-color  0.3s ease; /* Efek transisi untuk warna latar belakang */
        color: #2c2626;
    }

    /* Gaya untuk sel tabel */
    #approvalTable tbody td {
        padding: 10px; /* Padding untuk sel */
        border-bottom: 1px solid #dee2e6; /* Garis bawah sel */
        color: #2c2626;
    }

    /* Hover effect untuk baris tabel */
    #approvalTable tbody tr:hover {
        background-color: rgba(0, 123, 255, 0.1); /* Warna latar belakang saat hover */
    }

    #approvalTable th, #approvalTable td {
        padding: 8px;
        text-align: center;
    }
</style>
