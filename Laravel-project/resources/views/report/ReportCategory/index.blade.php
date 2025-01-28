@section('title', 'Report Category')


<x-layout bodyClass="g-sidenav-show  bg-gray-200">

    <x-navbars.sidebar activePage="reportCategory"></x-navbars.sidebar>
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
                                <h3 class="text-white text-capitalize ps-3">Report Category</h3>
                            </div>
                            <div class="card-body p-3">
                                <div class="d-flex mb-2" style="gap: 10px;">
                                    <select id="categorySelect" class="form-control" style="width: 15%;">
                                        <option value="" selected>Pilih Category</option>
                                        @foreach ($categories as $category)
                                            <option value="{{ $category }}">{{ $category }}</option>
                                        @endforeach
                                    </select>
                                    <select id="yearSelect" class="form-control" style="width: 15%;">
                                        <option value="" selected>Pilih Tahun</option>
                                        @foreach ($years as $year)
                                            <option value="{{ $year }}">{{ $year }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="table-responsive p-0">
                                    <table id="cate-table" class="table table-bordered nowrap rounded-table p-0" style="width:100%">
                                        <thead style="background-color: #3cb210; color: white;">
                                            <tr>
                                                <th class="text-center">No</th>
                                                <th class="text-center">Category</th>
                                                <th class="text-center">Project</th>
                                                <th class="text-center">Number</th>
                                                <th class="text-center">Budget {{ date('Y') }}</th>
                                                <th class="text-center">Unbudget {{ date('Y') }}</th>
                                                <th class="text-center">Carried Over</th>
                                                <th class="text-center">Amount</th>
                                                <th class="text-center">Actual YTD</th>
                                                <th class="text-center">Balance</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                           
                                        </tbody>
                                        <tfoot>
                                            <tr style="background-color: #294822; color: #ffffff; font-weight: bold;">
                                                <th colspan="4">Total</th>
                                                <th class="text-right"></th>
                                                <th class="text-right"></th>
                                                <th class="text-right"></th>
                                                <th class="text-right"></th>
                                                <th class="text-right"></th>
                                                <th class="text-right"></th>
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
                        // Inisialisasi DataTable
                        var table = $('#cate-table').DataTable({
                            processing: true,
                            serverSide: true,
                            ordering: false,
                            ajax: {
                                url: '{!! route('report.index') !!}',
                                type: 'GET',
                                data: function(d) {
                                // Menambahkan flag ke data
                                d.flag = 'category';

                                // Menambahkan kategori yang dipilih ke data
                                var categoryValue = $('#categorySelect').val();
                                if (categoryValue) {
                                    d.category = categoryValue;
                                }
                                var yearValue = $('#yearSelect').val();
                                if (yearValue){
                                    d.year = yearValue;
                                }
                            }
                            },
                            columns: [
                                { data: 'DT_RowIndex', name: 'DT_RowIndex' },  // Untuk nomor urut
                                { data: 'category', visible: false }, // Hide kolom category
                                { 
                                    data: 'project',
                                    createdCell: function(td, cellData, rowData, rowIndex, colIndex) {
                                        $(td).css('text-align', 'left');
                                    }
                                },
                                { data: 'number', className: 'text-center' },
                                { 
                                    data: 'budget',
                                    createdCell: function(td, rowData, rowIndex, cellData, colIndex){
                                        $(td).css('text-align', 'right');
                                    },
                                    render: function (data, type, row) {
                                        if(data ===''){
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
                                    }
                                },
                                { 
                                    data: 'unbudget',
                                    createdCell: function(td, rowData, rowIndex, cellData, colIndex){
                                        $(td).css('text-align', 'right');
                                    },
                                    render: function (data, type, row) {
                                        if(data==''){
                                            data = null;
                                        }
                                        if(data !== null){
                                            return new Intl.NumberFormat('id-ID', { 
                                                style: 'decimal', 
                                                minimumFractionDigits: 2, 
                                                maximumFractionDigits: 2 
                                            }).format(data);
                                        }
                                        return data;
                                    }
                                },
                                { 
                                    data: 'carried_over',
                                    createdCell: function(td, rowData, rowIndex, cellData, colIndex){
                                        $(td).css('text-align', 'right');
                                    },
                                    render: function (data, type, row) {
                                        if(data == ''){
                                            data = null;
                                        }
                                        if(data !== null){
                                            return new Intl.NumberFormat('id-ID', { 
                                                style: 'decimal', 
                                                minimumFractionDigits: 2, 
                                                maximumFractionDigits: 2 
                                            }).format(data);
                                        }
                                        return data;
                                    }
                                },
                                { 
                                    data: 'amount',
                                    createdCell: function(td, rowData, rowIndex, cellData, colIndex){
                                        $(td).css('text-align', 'right');
                                    },
                                    render: function (data, type, row) {
                                        return new Intl.NumberFormat('id-ID', { 
                                            style: 'decimal', 
                                            minimumFractionDigits: 2, 
                                            maximumFractionDigits: 2 
                                        }).format(data);
                                    }
                                },
                                { 
                                    data: 'actual_ytd',
                                    createdCell: function(td, rowData, rowIndex, cellData, colIndex){
                                        $(td).css('text-align', 'right');
                                    },
                                    render: function (data, type, row) {
                                        if (data === '' || data === null || data === undefined) {
                                            data == null;
                                        }
                                        if (data !== null){
                                            return new Intl.NumberFormat('id-ID', { 
                                                style: 'decimal', 
                                                minimumFractionDigits: 2, 
                                                maximumFractionDigits: 2 
                                            }).format(data);
                                        }
                                        return data;
                                    }
                                },
                                { 
                                    data: 'balance',
                                    createdCell: function(td, rowData, rowIndex, cellData, colIndex){
                                        $(td).css('text-align', 'right');
                                    },
                                    render: function (data, type, row) {
                                        return new Intl.NumberFormat('id-ID', { 
                                            style: 'decimal', 
                                            minimumFractionDigits: 2, 
                                            maximumFractionDigits: 2 
                                        }).format(data);
                                    }
                                }
                            ],
                            drawCallback: function(settings) {
                                var api = this.api();
                                var rows = api.rows({ page: 'current' }).nodes();
                                var last = null;
                                var groupHeader = null;
                                var counter = 1;

                                api.rows({ page: 'current' }).data().each(function(group, i) {
                                    if (last !== group.category) {
                                        counter = 1;
                                        // Insert category header before the first row of the group
                                        groupHeader = $('<tr class="group">' +
                                            '<td colspan="1" style="font-weight: bold; padding: 10px;"></td>' +  // Kolom pertama (kosong atau bisa ditambahkan konten lain)
                                            '<td colspan="9" style="font-weight: bold; padding: 10px; text-align: left;">' +  // Kolom kedua untuk teks
                                            group.category + 
                                            '</td>' +
                                        '</tr>');

                                        $(rows[i]).before(groupHeader);

                                        // Calculate subtotal for this group
                                        var subtotal = {
                                            budget: 0,
                                            unbudget: 0,
                                            carried_over: 0,
                                            amount: 0,
                                            actual_ytd: 0,
                                            balance: 0
                                        };

                                        // Find all rows for current category
                                        var categoryRows = [];
                                        api.rows({ page: 'current' }).data().each(function(data, index) {
                                            if (data.category === group.category) {
                                                categoryRows.push(index);
                                                subtotal.budget += parseFloat(data.budget || 0);
                                                subtotal.unbudget += parseFloat(data.unbudget || 0);
                                                subtotal.carried_over += parseFloat(data.carried_over || 0);
                                                subtotal.amount += parseFloat(data.amount || 0);
                                                subtotal.actual_ytd += parseFloat(data.actual_ytd || 0);
                                                subtotal.balance += parseFloat(data.balance || 0);
                                            }
                                        });

                                        categoryRows.forEach(function(rowIndex, index) {
                                            $(rows[rowIndex]).find('td:first').html(index + 1);
                                        });

                                        // Find last row of current category
                                        var lastRowIndex = categoryRows[categoryRows.length - 1];
                                        $(rows[lastRowIndex]).after(
                                            '<tr class="group-subtotal">' +
                                            '<td colspan="3" style="padding: 7px;">Sub Total</td>' +
                                            '<td class="text-right" style="padding: 8px;">' + 
                                            (subtotal.budget ? subtotal.budget.toLocaleString() : '') + '</td>' +
                                            '<td class="text-right" style="padding: 8px;">' + 
                                            (subtotal.unbudget ? subtotal.unbudget.toLocaleString() : '') + '</td>' +
                                            '<td class="text-right" style="padding: 8px;">' + 
                                            (subtotal.carried_over ? subtotal.carried_over.toLocaleString() : '') + '</td>' +
                                            '<td class="text-right" style="padding: 8px;">' + 
                                            (subtotal.amount ? subtotal.amount.toLocaleString() : '') + '</td>' +
                                            '<td class="text-right" style="padding: 8px;">' + 
                                            (subtotal.actual_ytd ? subtotal.actual_ytd.toLocaleString() : '') + '</td>' +
                                            '<td class="text-right" style="padding: 8px;">' + 
                                            (subtotal.balance ? subtotal.balance.toLocaleString() : '') + '</td>' +
                                            '</tr>'
                                        );

                                        last = group.category;
                                    }
                                });
                            },
                            footerCallback: function(row, data, start, end, display) {
                                var api = this.api();
                                var columns = [4, 5, 6, 7, 8, 9];

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

                        // Inisialisasi Select2 untuk kategori
                            var categorySelect = $('#categorySelect');
                            if (categorySelect.length) {
                                categorySelect.select2({
                                    placeholder: 'Cari Kategori',
                                    allowClear: true,
                                });
                            }
                            categorySelect.on('select2:select', function (e) {
                                const selectedOption = $(this).find(':selected'); 
                                const categoryValue = selectedOption.val(); 

                                console.log('Kategori dipilih:', categoryValue);

                                if (categoryValue) {
                                    table.ajax.url('{{ route('report.index') }}?category=' + categoryValue).load();
                                } else {
                                    table.ajax.url('{{ route('report.index') }}').load();
                                }
                            });
                            categorySelect.on('select2:unselect', function (e) {
                                table.ajax.url('{{ route('report.index') }}').load();
                            });

                            var yearSelect = $('#yearSelect');
                            if (yearSelect.length) {
                                yearSelect.select2({
                                    placeholder: 'Cari Tahun',
                                    allowClear: true,
                                    dropdownAutoWidth: true,
                                    closeOnSelect: true,
                                    dropdownCssClass: 'select2-results-limited-year',
                                    templateResult: function(data) {
                                        if (!data.element) {
                                            return data.text;
                                        }
                                        return $('<div class="select2-result-item">' + data.text + '</div>');
                                    }
                                });
                            }

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

                            yearSelect.on('select2:unselect', function (e) {
                                table.ajax.url('{{ route('report.index') }}').load();
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
        #cate-table {
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

    #cate-table thead th {
        background-color: #3cb210;
        /* Warna latar belakang header */
        color: #ffffff;
        /* Warna teks header */
    }

    /* Gaya untuk baris tabel */
    #cate-table tbody tr {
        transition: background-color 0.3s ease;
        /* Efek transisi untuk warna latar belakang */
        color: #2c2626;
    }

    /* Gaya untuk sel tabel */
    #cate-table tbody td {
        padding: 8px;
        /* Padding untuk sel */
        border-bottom: 1px solid #dee2e6;
        /* Garis bawah sel */
        color: #2c2626;
    }

    /* Hover effect untuk baris tabel */
    #cate-table tbody tr:hover {
        background-color: rgba(0, 123, 255, 0.1);
        /* Warna latar belakang saat hover */
    }

    #cate-table th,
    #cate-table td {
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
    .group td {
        font-weight: bold !important;
        background-color: #000000 !important;
        color: white !important;
    }

    .group-subtotal td {
        background-color: #e0e8e0 !important;
        font-weight: bold;
        
    }

    tr.group,
    tr.group-subtotal {
        cursor: default !important;
    }
    .select2-container .select2-selection--single {
        height: 50px; /* Menyesuaikan tinggi agar lebih proporsional */
        padding-inline: 10px; /* Padding kiri dan kanan otomatis menyesuaikan dengan teks */
        font-size: 11pt; /* Ukuran font lebih besar untuk keterbacaan */
        border-radius: 8px; /* Membuat sudut lebih halus */
        border: 1px solid #ccc; /* Border abu-abu muda untuk kesan elegan */
        background-color: #ffffff; /* Latar belakang putih agar bersih */
        color: #000000; /* Warna teks abu-abu gelap untuk kontras yang baik */
        box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1); /* Menambahkan bayangan halus di sekitar dropdown */
        transition: all 0.3s ease; /* Menambahkan transisi halus saat berinteraksi */
        display: flex; /* Menjadikan container flex */
        align-items: center; /* Menyelaraskan teks di tengah secara vertikal */
        justify-content: space-between; /* Memastikan tombol x berada di sisi kanan */
    }

    /* Efek fokus pada select2 */
    .select2-container .select2-selection--single:focus {
        border-color: #3cff00; /* Mengubah border menjadi biru saat fokus */
        box-shadow: 0 0 5px rgba(0, 123, 255, 0.5); /* Menambahkan bayangan biru saat fokus */
        outline: none; /* Menghilangkan outline default */
    }
        .select2-container .select2-selection__clear {
        position: absolute;
        right: 10px; /* Menempatkan tombol "x" di sisi kanan */
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
