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
                                    <th>Percentage</th>
                                    <th>Upload By</th>
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
                        name: 'project_desc',
                        createdCell: function(td, cellData, rowData, rowIndex, colIndex) {
                            $(td).css('text-align', 'left');
                        },
                    },
                    {
                        data: 'wbs_capex',
                        name: 'wbs_capex',
                        createdCell: function(td, cellData, rowData, rowIndex, colIndex) {
                            $(td).css('text-align', 'left');
                        },
                        render: function(data, type, row) {
                            if (type === 'display') {
                                if (data === 'Project') {
                                    return '<span class="badge bg-gradient-info">Project</span>';
                                } else if (data === 'Non Project') {
                                    return '<span class="badge bg-gradient-warning">Non Project</span>';
                                }
                                return data; // Untuk nilai lain tampilkan apa adanya
                            }
                            return data;
                        }
                    },
                    {
                        data: null,
                        name: 'percentage',
                        orderable: false,
                        searchable: false,
                        render: function(data, type, row) {
                            // Tentukan jumlah status yang harus dihitung berdasarkan wbs_capex
                            let statusesToCheck;
                            if (row.wbs_capex === "Project") {
                                // Untuk Project, gunakan semua 4 status
                                statusesToCheck = [row.status_approve_1, row.status_approve_2, row
                                    .status_approve_3, row.status_approve_4
                                ];
                            } else if (row.wbs_capex === "Non Project") {
                                // Untuk Non Project, gunakan hanya 3 status
                                statusesToCheck = [row.status_approve_1, row.status_approve_2, row
                                    .status_approve_4
                                ];
                            } else {
                                // Jika tipe tidak dikenali, anggap 0%
                                statusesToCheck = [];
                            }

                            // Hitung jumlah yang disetujui
                            let approvedCount = 0;
                            statusesToCheck.forEach(status => {
                                if (status == 1) approvedCount++; // 1 berarti disetujui
                            });

                            // Hitung persentase berdasarkan jumlah status yang diperiksa
                            let percentage = (approvedCount / statusesToCheck.length) * 100;

                            // Tentukan warna badge berdasarkan persentase
                            let badgeClass = percentage === 100 ?
                                'bg-gradient-success' // Hijau untuk 100%
                                :
                                'bg-gradient-info'; // Kuning untuk yang lain

                            return `<span class="badge ${badgeClass}">${percentage.toFixed(0)}%</span>`;
                        }
                    },
                    {
                        data: 'upload_by',
                        name: 'upload_by',
                        createdCell: function(td, cellData, rowData, rowIndex, colIndex) {
                            $(td).css('text-align', 'left');
                        },
                    },

                    {
                        data: 'upload_date',
                        name: 'upload_date',
                    },
                
                ],
                order: [
                    [4, 'desc']
                ]
            });

            function statusBadge(status) {
                let statusText = '';
                let badgeClass = '';
                switch (status) {
                    case 0:
                        statusText = 'Pending';
                        badgeClass = 'bg-gradient-warning';
                        break;
                    case 1:
                        statusText = 'Approve';
                        badgeClass = 'bg-gradient-success';
                        break;
                    case 2:
                        statusText = 'Disapprove';
                        badgeClass = 'bg-gradient-danger';
                        break;
                }
                return `<span class="badge ${badgeClass}">${statusText}</span>`;
            }
        });
    </script>


    <style>
        #approvalTable thead th {
            background-color: #3cb210;
            /* Warna latar belakang header */
            color: #ffffff;
            /* Warna teks header */
            width: auto;
            /* Atur lebar kolom header secara otomatis */
        }

        /* Gaya untuk baris tabel */
        #approvalTable tbody tr {
            transition: background-color 0.3s ease;
            /* Efek transisi untuk warna latar belakang */
            color: #2c2626;
        }

        /* Gaya untuk sel tabel */
        #approvalTable tbody td {
            padding: 10px;
            /* Padding untuk sel */
            border-bottom: 1px solid #dee2e6;
            /* Garis bawah sel */
            color: #2c2626;
        }

        /* Hover effect untuk baris tabel */
        #approvalTable tbody tr:hover {
            background-color: rgba(0, 123, 255, 0.1);
            /* Warna latar belakang saat hover */
        }

        #approvalTable th,
        #approvalTable td {
            padding: 8px;
            text-align: center;
        }
    </style>
