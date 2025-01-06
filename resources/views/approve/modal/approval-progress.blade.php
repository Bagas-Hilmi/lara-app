    <div class="modal fade" id="progressAPV" tabindex="-1" aria-labelledby="progressAPVLabel">
        <div class="modal-dialog modal-dialog-centered custom-modal">
            <div class="modal-content">
                <div class="modal-header" style="background-color: #42bd37;">
                    <h5 class="modal-title" id="progressAPVLabel" style="color: white;">Progress Approval</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="row mb-4" style="justify-content: center;">
                        
                        <div class="col-md-3">
                            <div class="card bg-gradient-light">
                                <div class="card-body text-center">
                                    <h6 class="card-title">Total Capex</h6>
                                    <h3 class="mb-0" id="totalProjects">0</h3>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="card bg-gradient-success text-white">
                                <div class="card-body text-center">
                                    <h6 class="card-title">Completed</h6>
                                    <h3 class="mb-0" id="completedProjects">0</h3>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="card bg-gradient-warning">
                                <div class="card-body text-center">
                                    <h6 class="card-title">In Progress</h6>
                                    <h3 class="mb-0" id="inProgressProjects">0</h3>
                                </div>
                            </div>
                        </div>
                    </div>

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
                        render: function(data, type, row) {
                            let statusesToCheck;
                            if (row.wbs_capex === "Project") {
                                statusesToCheck = [row.status_approve_1, row.status_approve_2, row.status_approve_3, row.status_approve_4];
                            } else {
                                statusesToCheck = [row.status_approve_1, row.status_approve_2, row.status_approve_4];
                            }

                            let approvedCount = statusesToCheck.filter(status => status == 1).length;
                            let percentage = (approvedCount / statusesToCheck.length) * 100;
                            
                            // Progress bar dengan warna berdasarkan persentase
                            let color = percentage === 100 ? '#42bd37' : 
                                    percentage >= 75 ? '#17a2b8' :
                                    percentage >= 50 ? '#ffc107' : '#6c757d';
                                    
                            return `
                                    <div class="d-flex align-items-center">
                                        <div class="progress flex-grow-1" style="height: 8px;">
                                            <div class="progress-bar" role="progressbar" 
                                                style="width: ${percentage}%; background-color: ${color}" 
                                                aria-valuenow="${percentage}" aria-valuemin="0" aria-valuemax="100">
                                            </div>
                                        </div>
                                        <span class="ms-2 badge" style="background-color: ${color}">${percentage.toFixed(0)}%</span>
                                    </div>
                            `;
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

            function updateSummary() {
                $.get("{{ route('approve.index') }}", { type: 'summary' }, function(data) {
                    $('#totalProjects').text(data.total);
                    $('#completedProjects').text(data.completed);
                    $('#inProgressProjects').text(data.in_progress);
                });
            }

            // Panggil saat modal dibuka
            $('#progressAPV').on('shown.bs.modal', function() {
                updateSummary();
            });
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

                /* Tambahkan di style yang sudah ada */
        .approval-timeline .step-number {
            width: 30px;
            height: 30px;
            border-radius: 50%;
            background-color: #f8f9fa;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: bold;
        }

        .progress {
            background-color: #e9ecef;
            border-radius: 10px;
            overflow: hidden;
        }

        .card {
            border-radius: 10px;
            box-shadow: 0 0.125rem 0.25rem rgba(0,0,0,0.075);
            transition: transform 0.2s;
        }

        .card:hover {
            transform: translateY(-2px);
        }

        #approvalTable tbody tr {
            cursor: pointer;
        }

        .modal-content {
            border-radius: 15px;
        }

        .custom-modal {
            max-width:50%; /* Mengatur lebar modal menjadi 90% dari lebar viewport */
        }

    </style>
