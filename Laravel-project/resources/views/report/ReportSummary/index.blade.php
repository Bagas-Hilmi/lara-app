@section('title', 'Report Summary')


<x-layout bodyClass="g-sidenav-show  bg-gray-200">

    <x-navbars.sidebar activePage="reportSummary"></x-navbars.sidebar>
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
                                <h3 class="text-white text-capitalize ps-3">Report Summary</h3>
                            </div>
                            <div class="card-body p-3">
                                <div class="filter-wrapper">
                                    <div class="filter-top-row">
                                        <!-- Filter Type dan Year Select dalam satu baris -->
                                        <div class="filter-dropdown">
                                            <select id="filterTypeSelect" class="form-control">
                                                <option value="">Pilih Jenis Filter</option>
                                                <option value="category">Filter Category</option>
                                                <option value="status">Filter Status</option>
                                                <option value="budget">Filter Budget Type</option>
                                            </select>
                                        </div>
                                        
                                        <div class="year-select-container">
                                            <select id="yearSelect" class="form-control">
                                                <option value="" selected>Pilih Tahun</option>
                                                @foreach ($years as $year)
                                                    <option value="{{ $year }}">{{ $year }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                    
                                    <div class="filter-options-row">
                                        <!-- Filter options dibawah -->
                                        <div class="filter-select-container">
                                            <select id="categorySelect" class="filter-select form-control hidden">
                                                <option value="" selected>Pilih Category</option>
                                                @foreach ($categories as $category)
                                                    <option value="{{ $category }}">{{ $category }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                
                                        <div class="filter-select-container">
                                            <select id="statusSelect" class="filter-select form-control hidden">
                                                <option value="" selected>Pilih Status</option>
                                                @foreach ($status as $stat)
                                                    <option value="{{ $stat }}">{{ $stat }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                
                                        <div class="filter-select-container">
                                            <select id="budgetSelect" class="filter-select form-control hidden">
                                                <option value="" selected>Pilih Budget</option>
                                                @foreach ($budgets as $budget)
                                                    <option value="{{ $budget }}">{{ $budget }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                </div>
                                <div class="table-responsive p-0">
                                    <table id="summary-table" class="table table-bordered nowrap rounded-table p-0" style="width:100%">
                                        <thead style="background-color: #3cb210; color: white;">
                                            <tr>
                                                <th class="text-center">No</th>
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
                                                <th class="text-center">Realization Cost (RP)</th>
                                                <th class="text-center">Realization Cost (USD)</th>
                                                <th class="text-center">PO Release</th>
                                                <th class="text-center">Realized</th>
                                                <th class="text-center">Status Capex</th>
                                                <th class="text-center">Budget Type</th>
                                                <th class="text-center">Start Up</th>
                                                <th class="text-center">Exp Completed</th>
                                                <th class="text-center">Rev Completion Date</th>
                                                <th class="text-center">Days Remaining</th>
                                                <th class="text-center">Days Late</th>
                                                <th class="text-center">Interest</th>
                                                <th class="text-center">WBS Number</th>
                                                <th class="text-center">CIP Number</th>
                                            </tr>
                                        </thead>
                                        <tfoot style="background-color: #294822; color: #ffffff; font-weight: bold;">
                                            <tr>
                                                <th></th>
                                                <th></th>
                                                <th></th>
                                                <th></th>
                                                <th></th>
                                                <th></th>
                                                <th></th>
                                                <th></th>
                                                <th></th>
                                                <th>Total All Capex : </th>
                                                <th style="text-align: right;" id="total-budget-footer"></th>
                                                <th style="text-align: right;" id="recost-rp-footer"></th>
                                                <th style="text-align: right;" id="recost-usd-footer"></th>
                                                <th style="text-align: right;" id="po-release-footer"></th>
                                                <th></th>
                                                <th></th>
                                                <th></th>
                                                <th></th>
                                                <th></th>
                                                <th></th>
                                                <th></th>
                                                <th></th>
                                                <th></th>
                                                <th></th>
                                                <th></th>
                                            </tr>
                                        </tfoot>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <x-footers.auth></x-footers.auth>
            </div>


            <link href="{{ asset('assets') }}/css/select2.min.css" rel="stylesheet" />
            @push('js')
                <script src="{{ asset('assets/js/jquery.min.js') }}"></script>
                <script src="assets/js/plugins/sweetalert.min.js"></script>
                <script src="{{ asset('assets/datatables/dataTables.min.js') }}"></script>
                <script src="assets/js/moment.min.js"></script>
                <script src="{{ asset('assets/js/select2.min.js') }}"></script>

                <script>
                    $(document).ready(function() {
                        const currentYear = new Date().getFullYear();

                        // Initialize DataTable
                        var table = $('#summary-table').DataTable({
                            processing: true,
                            serverSide: true,
                            ordering: true,
                            order: [[7,'asc']],
                            ajax: {
                                url: '{!! route('report.index') !!}',
                                type: 'GET',
                                data: function(d) {
                                // Menambahkan flag ke data
                                d.flag = 'summary';

                                var categoryValue = $('#categorySelect').val();
                                if (categoryValue) {
                                    d.category = categoryValue;
                                }

                                var statusValue = $('#statusSelect').val();
                                if (statusValue) {
                                    d.status_capex = statusValue;  
                                }

                                var budgetValue = $('#budgetSelect').val();
                                if (budgetValue) {
                                    d.budget_type = budgetValue;  
                                }

                                var yearValue = $('#yearSelect').val();
                                if (yearValue){
                                    d.year = yearValue;
                                }
                            }},
                            columns: [
                                { data: 'DT_RowIndex', name: 'DT_RowIndex' },  // Untuk nomor urut
                                {data: 'project_desc', name: 'project_desc', 
                                    createdCell: function(td, cellData, rowData, rowIndex, colIndex) {
                                        $(td).css('text-align', 'left');
                                    }
                                }, 
                                {data: 'category', name: 'category', 
                                        createdCell: function(td, cellData, rowData, rowIndex, colIndex){
                                            $(td).css('text-align','left');
                                        }, 
                                        render: function(data, type, row) {
                                        if (type === 'display') {
                                            if (data === 'General Operation') {
                                                return '<span class="badge bg-gradient-secondary">General Operation</span>';
                                            } else if (data === 'IT') {
                                                return '<span class="badge bg-gradient-secondary">IT</span>';
                                            } else if (data === 'Environment') {
                                                return '<span class="badge bg-gradient-secondary">Environment</span>';
                                            } else if (data === 'Safety') {
                                                return '<span class="badge bg-gradient-secondary">Safety</span>';
                                            } else if (data === 'Improvement Plant efficiency') {
                                                return '<span class="badge bg-gradient-secondary">Improvement Plant efficiency</span>';
                                            } else if (data === 'Invesment') {
                                                return '<span class="badge bg-gradient-secondary">Invesment</span>';
                                            }
                                            return data; 
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
                                                return '<span class="badge bg-gradient-info">Project</span>';
                                            } else if (data === 'Non Project') {
                                                return '<span class="badge bg-gradient-warning">Non Project</span>';
                                            }
                                            return data; 
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

                                    {data: 'recost_rp', name: 'recost_rp',
                                    createdCell: function(td, rowData, rowIndex, cellData, colIndex){
                                        $(td).css('text-align', 'right');
                                    },
                                    render: function(data, type, row) {
                                        if (data === '' ) {
                                            data = null;
                                        }
                                        if (data !== null) {
                                            return new Intl.NumberFormat('id-ID', { 
                                                style: 'decimal', 
                                                minimumFractionDigits: 2, 
                                                maximumFractionDigits: 2 
                                            }).format(data);
                                        }

                                        // Jika data null, return null
                                        return data;
                                    }},

                                    {data: 'recost_usd', name: 'recost_usd', 
                                    createdCell: function(td, rowData, rowIndex, cellData, colIndex){
                                        $(td).css('text-align', 'right');
                                    },
                                    render: function(data, type, row) {
                                        if (data === '') {
                                            data = null;
                                        }
                                        if (data !== null) {
                                            return new Intl.NumberFormat('id-ID', { 
                                                style: 'decimal', 
                                                minimumFractionDigits: 2, 
                                                maximumFractionDigits: 2 
                                            }).format(data);
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
                                    {data: 'realized', name: 'realized', render: function(data, type, row) {
                                        return data ? data + '%' : '0%'; // Tambahkan '%' ke nilai realized
                                    }},                                    
                                    {data: 'status_capex', name: 'status_capex', className: 'text-start',
                                        render: function(data, type, row) {
                                            if (type === 'display') {
                                                let badgeClass = '';
                                                switch(data) {
                                                    case 'Canceled':
                                                        badgeClass = 'bg-gradient-danger';
                                                        break;
                                                    case 'Close':
                                                        badgeClass = 'bg-gradient-secondary';
                                                        break;
                                                    case 'On Progress':
                                                        badgeClass = 'bg-gradient-success';
                                                        break;
                                                    case 'To Opex':
                                                        badgeClass = 'bg-gradient-info';
                                                        break;
                                                    case 'Waiting Approval':
                                                        badgeClass = 'bg-gradient-warning';
                                                        break;
                                                    default:
                                                        return data;
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
                                    {data: 'interest', name: 'interest', className: 'text-right',
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
                            ],
                            footerCallback: function(row, data, start, end, display) {
                                var api = this.api();
                                var columns = [10,11,12,13];

                                columns.forEach(function(colIndex) {
                                    var total = api
                                        .column(colIndex, { page: 'current' })
                                        .data()
                                        .reduce(function(acc, val) {
                                            return acc + (parseFloat(val) || 0);
                                        }, 0);

                                    $(api.column(colIndex).footer()).html(total.toLocaleString());
                                });
                            }
                        });
                        var categorySelect = $('#categorySelect');
                        var statusSelect = $('#statusSelect');
                        var budgetSelect = $('#budgetSelect');
                        var filterTypeSelect = $('#filterTypeSelect');
                        var yearSelect = $('#yearSelect');

                        // Select2 Initialization
                        if (categorySelect.length) {
                            categorySelect.select2({
                                placeholder: 'Cari Kategori',
                                allowClear: true,
                            });
                        }

                        if (statusSelect.length) {
                            statusSelect.select2({
                                placeholder: 'Cari Status',
                                allowClear: true,
                            });
                        }

                        if (budgetSelect.length) {
                            budgetSelect.select2({
                                placeholder: 'Cari Tipe Budget',
                                allowClear: true,
                            });
                        }

                        // Sembunyikan semua filter awal
                        [categorySelect, statusSelect, budgetSelect].forEach(select => {
                            $(select).closest('.form-control').addClass('hidden');
                        });

                        // Event listener untuk memilih jenis filter
                        filterTypeSelect.on('change', function() {
                            // Sembunyikan semua filter
                            [categorySelect, statusSelect, budgetSelect].forEach(select => {
                                $(select).closest('.form-control').addClass('hidden');
                            });

                            // Tampilkan filter sesuai pilihan
                            switch($(this).val()) {
                                case 'category':
                                    categorySelect.closest('.form-control').removeClass('hidden');
                                    break;
                                case 'status':
                                    statusSelect.closest('.form-control').removeClass('hidden');
                                    break;
                                case 'budget':
                                    budgetSelect.closest('.form-control').removeClass('hidden');
                                    break;
                            }
                        });

                        // Event handling untuk masing-masing filter (sesuai kode sebelumnya)
                        categorySelect.on('select2:select', function(e) {
                            const categoryValue = $(this).val();
                            if (categoryValue) {
                                table.ajax.url('{{ route('report.index') }}?category=' + categoryValue).load();
                            } else {
                                table.ajax.url('{{ route('report.index') }}').load();
                            }
                        });

                        categorySelect.on('select2:clear', function (e) {
                            table.ajax.url('{{ route('report.index') }}').load(); // Reload semua data
                        });

                        statusSelect.on('select2:select', function(e) {
                            const statusValue = $(this).val();
                            if (statusValue) {
                                table.ajax.url('{{ route('report.index') }}?status=' + statusValue);
                            } else {
                                table.ajax.url('{{ route('report.index') }}');
                            }
                            table.ajax.reload();
                        });

                        statusSelect.on('select2:clear', function (e) {
                            table.ajax.url('{{ route('report.index') }}').load(); // Reload semua data
                        });

                        budgetSelect.on('select2:select', function(e) {
                            const budgetValue = $(this).val();
                            if (budgetValue) {
                                table.ajax.url('{{ route('report.index') }}?budget=' + budgetValue);
                            } else {
                                table.ajax.url('{{ route('report.index') }}');
                            }
                            table.ajax.reload();
                        });

                        budgetSelect.on('select2:clear', function (e) {
                            table.ajax.url('{{ route('report.index') }}').load(); // Reload semua data
                        });

                        if (yearSelect.length) {
                            // Inisialisasi Select2
                            yearSelect.select2({
                                placeholder: 'Cari Tahun',
                                allowClear: true,
                                closeOnSelect: true,
                                dropdownCssClass: 'select2-results-limited-year', // Tambahan class untuk styling
                                templateResult: function(data) {
                                    if (!data.element) {
                                        return data.text;
                                    }
                                    return $('<div class="select2-result-item">' + data.text + '</div>');
                                }
                            });

                            yearSelect.val(currentYear).trigger('change');

                            // Muat tabel dengan tahun default setelah inisialisasi
                            table.ajax.url('{{ route('report.index') }}?year=' + currentYear).load();

                        // Event ketika pengguna memilih tahun
                        yearSelect.on('select2:select', function (e) {
                            const selectedOption = $(this).find(':selected');
                            const yearValue = selectedOption.val();

                            console.log('Tahun dipilih:', yearValue);

                            if (yearValue) {
                                table.ajax.url('{{ route('report.index') }}?year=' + yearValue).load();
                            } else {
                                table.ajax.url('{{ route('report.index') }}').load();
                            }
                        });

                        // Event ketika pengguna menghapus pilihan
                        yearSelect.on('select2:unselect', function (e) {
                            table.ajax.url('{{ route('report.index') }}').load();
                        });
                    }
                    });

                    document.addEventListener('DOMContentLoaded', function() {
                        const filterTypeSelect = document.getElementById('filterTypeSelect');
                        const filterSelectContainers = document.querySelectorAll('.filter-select-container');

                        filterSelectContainers.forEach(container => {
                            container.classList.add('hidden');
                        });

                        filterTypeSelect.addEventListener('change', function() {
                            // Hide all filters first
                            filterSelectContainers.forEach(container => {
                                container.classList.add('hidden');
                            });

                            switch(this.value) {
                                case 'category':
                                    document.querySelector('.filter-select-container:has(#categorySelect)').classList.remove('hidden');
                                    break;
                                case 'status':
                                    document.querySelector('.filter-select-container:has(#statusSelect)').classList.remove('hidden');
                                    break;
                                case 'budget':
                                    document.querySelector('.filter-select-container:has(#budgetSelect)').classList.remove('hidden');
                                    break;
                                default:
                                    // If no selection, all remain hidden
                                    break;
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
        #summary-table {
            width: 100%;
            /* Memastikan tabel mengambil 100% lebar */
        }
    }

    .rounded-table {
        border-radius: 12px;
        /* Adjust the radius as needed */
        overflow: hidden;
        /* Ensures child elements respect the border radius */
    }

    .rounded-table th,
    .rounded-table td {
        border: none;
        /* Remove default borders to maintain rounded appearance */
    }

    #summary-table thead th {
        background-color: #3cb210;
        /* Warna latar belakang header */
        color: #ffffff;
        /* Warna teks header */
    }

    /* Gaya untuk baris tabel */
    #summary-table tbody tr {
        transition: background-color 0.3s ease;
        /* Efek transisi untuk warna latar belakang */
        color: #2c2626;
    }

    /* Gaya untuk sel tabel */
    #summary-table tbody td {
        padding: 8px;
        /* Padding untuk sel */
        border-bottom: 1px solid #dee2e6;
        /* Garis bawah sel */
        color: #2c2626;
    }

    /* Hover effect untuk baris tabel */
    #summary-table tbody tr:hover {
        background-color: rgba(0, 123, 255, 0.1);
        /* Warna latar belakang saat hover */
    }

    #summary-table th,
    #summary-table td {
        padding: 8px;
        text-align: center;
    }

    .form-select {
        width: auto;
        /* Ubah sesuai kebutuhan */
        border-radius: 4px;
        /* Tambahkan sudut melengkung */
        border: 1px solid #ccc;
        /* Warna border */
        box-shadow: none;
        /* Hilangkan shadow default */
        background-color: #f9f9f9;
        /* Warna latar belakang */
        font-size: 15px;
        /* Ukuran teks */
    }

    /* Fokus pada dropdown */
    .form-select:focus {
        border-color: #42bd37;
        /* Warna border saat fokus */
        box-shadow: 0 0 5px rgba(66, 189, 55, 0.5);
        /* Efek shadow saat fokus */
    }

    .form-control {
        border: 1px solid #ccc;
        /* Customize the border */
        box-shadow: none;
        /* Remove shadow */
        border-radius: 4px;
        /* Tambahkan sudut melengkung */
    }

    .form-control:focus {
        border-color: #42bd37;
        /* Warna border saat fokus */
        box-shadow: 0 0 5px rgba(66, 189, 55, 0.5);
        /* Menambah efek shadow saat fokus */
    }

    .filter-wrapper {
        display: flex;
        flex-direction: column;
        gap: 15px;
        width: 100%;
    }

    /* Baris atas untuk filter type dan year select */
    .filter-top-row {
        display: flex;
        gap: 15px;
        align-items: center;
    }

    /* Baris bawah untuk opsi filter */
    .filter-options-row {
        display: flex;
        flex-direction: column;
        gap: 10px;
    }

    /* Container untuk filter dropdown */
    .filter-dropdown {
        width: 300px;
    }

    /* Container untuk year select */
    .year-select-container {
        width: 200px;
    }

    /* Base styling untuk semua select */
    .filter-dropdown select,
    .filter-select,
    #filterTypeSelect,
    #yearSelect {
        width: 100%;
        height: 50px;
        padding: 10px 15px;
        font-size: 11pt;
        border-radius: 8px;
        border: 1px solid #ccc;
        background-color: #ffffff;
        box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
        transition: all 0.3s ease;
    }

    /* Container untuk filter-select options */
    .filter-select-container {
        width: 300px;
    }

    /* Hover state untuk select */
    .filter-dropdown select:hover,
    .filter-select:hover,
    #filterTypeSelect:hover,
    #yearSelect:hover {
        border-color: #66afe9;
    }

    /* Focus state untuk select */
    .filter-dropdown select:focus,
    .filter-select:focus,
    #filterTypeSelect:focus,
    #yearSelect:focus {
        outline: none;
        border-color: #3cff00;
        box-shadow: 0 0 8px rgba(60, 255, 0, 0.3);
    }

    /* Select2 styling */
    .select2-container {
        width: 100% !important;
    }

    .select2-container .select2-selection--single {
        height: 50px;
        padding: 10px 15px;
        font-size: 11pt;
        border-radius: 8px;
        border: 1px solid #ccc;
        background-color: #ffffff;
        box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
        transition: all 0.3s ease;
    }

    .select2-container .select2-selection--single .select2-selection__rendered {
        line-height: 30px;
        padding-left: 0;
        color: #000000;
    }

    .select2-container .select2-selection--single .select2-selection__clear {
        position: absolute;
        right: 30px;
        top: 50%;
        transform: translateY(-50%);
        margin-right: 0;
        font-size: 16px;
        color: #999;
    }

    .select2-container .select2-selection--single .select2-selection__arrow {
        height: 48px;
        right: 10px;
    }

    /* Utility class */
    .hidden {
        display: none !important;
    }
    /* Styling khusus untuk dropdown year select */
    .select2-results-limited-year .select2-results__options {
        max-height: 150px; /* Tinggi yang lebih pendek karena hanya 3 item */
        overflow-y: auto;
    }

    /* Batasi tampilan menjadi 3 item */
    .select2-results-limited-year .select2-results__options .select2-results__option:nth-child(n+4) {
        margin-top: 0;
    }

    /* Pastikan hanya 3 item yang terlihat tanpa scroll */
    .select2-container .select2-results-limited-year .select2-results__options {
        max-height: 150px;
    }

    /* Optional: styling untuk item di dalam dropdown */
    .select2-results-limited-year .select2-results__option {
        padding: 8px 12px;
        font-size: 13pt;
    }
</style>
