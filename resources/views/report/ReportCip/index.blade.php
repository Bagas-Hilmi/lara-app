@section('title', 'Report CIP')


<x-layout bodyClass="g-sidenav-show  bg-gray-200">

    <x-navbars.sidebar activePage="reportCip"></x-navbars.sidebar>
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
                                <h3 class="text-white text-capitalize ps-3">Report CIP</h3>
                            </div>
                            <div class="card-body p-3">
                                <div class="mb-2">
                                    <div>
                                        <select id="descriptionSelect" class="form-control" style="width: 20%;">
                                            <option value="" selected>Pilih Capex</option>
                                            @foreach ($descriptions as $desc)
                                                <option value="{{ $desc->id_capex }}" data-cip="{{ $desc->cip_number }}"
                                                    data-wbs="{{ $desc->wbs_number }}"
                                                    data-project_desc="{{ $desc->project_desc }}"
                                                    data-budget_type="{{ $desc->budget_type }}"
                                                    data-amount_budget="{{ $desc->amount_budget }}"
                                                    data-wbs_capex="{{ $desc->wbs_capex }}"
                                                    data-requester="{{ $desc->requester }}"
                                                    data-status_capex="{{ $desc->status_capex }}">
                                                    {{ $desc->capex_number }}
                                                </option>
                                            @endforeach
                                        </select>

                                        <!-- Dropdown untuk memilih Status Capex -->
                                        <select id="statusSelect" class="form-control" style="width: 20%;">
                                            <option value="" selected>Pilih Status Capex</option>
                                            @php
                                                // Mengambil nilai status_capex yang unik dari $descriptions
                                                $uniqueStatuses = $descriptions
                                                    ->pluck('status_capex')
                                                    ->unique()
                                                    ->values();
                                            @endphp

                                            @foreach ($uniqueStatuses as $status)
                                                <option value="{{ $status }}">{{ $status }}</option>
                                            @endforeach
                                        </select>


                                        <button class="btn btn-secondary"
                                            style="background-color: #09170a; border-color: #09170a;">
                                            <span id="wbscapexText">WBS Type</span>
                                        </button>

                                        <!-- Button to open the modal -->
                                        <button onclick="downloadFilteredPDF()" class="btn btn-secondary"
                                            style="background-color: #bd1f20;">
                                            <i class="fas fa-file-pdf"></i> Download PDF
                                        </button>

                                        {{-- isi dari box container --}}
                                        <div class="info-box-container">
                                            <div class="info-box">
                                                <div class="info-box-label">CIP Number</div>
                                                <div class="info-box-value" id="cipText">-</div>
                                            </div>

                                            <div class="info-box">
                                                <div class="info-box-label">WBS Number</div>
                                                <div class="info-box-value" id="wbsText">-</div>
                                            </div>

                                            <div class="info-box">
                                                <div class="info-box-label">Project Desc</div>
                                                <div class="info-box-value" id="projectText">-</div>
                                            </div>

                                            <div class="info-box">
                                                <div class="info-box-label">Budget Type</div>
                                                <div class="info-box-value" id="budgetText">-</div>
                                            </div>

                                            <div class="info-box">
                                                <div class="info-box-label">Amount Budget</div>
                                                <div class="info-box-value" id="amountText">-</div>
                                            </div>

                                            <div class="info-box">
                                                <div class="info-box-label">Status Capex</div>
                                                <div class="info-box-value" id="statusText">-</div>
                                            </div>


                                        </div>
                                        <input type="hidden" id="requesterText">
                                    </div>
                                </div>
                                <div class="table-responsive p-0">
                                    <table id="report-table" class="table table-striped nowrap rounded-table p-0"
                                        style="width:100%">
                                        <thead>
                                            <tr>
                                                <th align="center">ID Report CIP</th>
                                                <th align="center">ID Capex</th>
                                                <th align="center">ID Head</th>
                                                <th align="center">FA Doc </th>
                                                <th align="center">Date </th>
                                                <th align="center">Settle Doc</th>
                                                <th align="center">Material</th>
                                                <th align="center">DESCRIPTION</th>
                                                <th align="center">QTY</th>
                                                <th align="center">UOM</th>
                                                <th align="center">AMOUNT (RP)</th>
                                                <th align="center">AMOUNT (US$)</th>
                                                <th align="center">Created At</th>
                                                <th align="center">Updated At</th>
                                            </tr>
                                        </thead>
                                        <tfoot>
                                            <tr style="background-color: #294822; color: #ffffff; font-weight: bold;">
                                                <td colspan="9"></td>
                                                <td align="center">Total :</td>
                                                <td align="center" id="total-amount-rp">Total (RP)</td>
                                                <td align="center" id="total-amount-us">Total (US$)</td>
                                                <td colspan="2"></td>
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

            @push('js')
                <script src="{{ asset('assets/js/jquery.min.js') }}"></script>
                <script src="assets/js/plugins/sweetalert.min.js"></script>
                <script src="{{ asset('assets/datatables/dataTables.min.js') }}"></script>
                <script src="assets/js/moment.min.js"></script>
                <script src="{{ asset('/js/tooltip.js') }}"></script>

                {{-- <script src="assets/js/select2.min.js"></script>
                <script src="assets/js/select2.min.css"></script> --}}
                <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
                <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

                <script>
                    $(document).ready(function() {
                        // Setup CSRF token for AJAX requests
                        $.ajaxSetup({
                            headers: {
                                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                            }
                        });

                        // Initialize DataTable
                        var table = $('#report-table').DataTable({
                            responsive: false,
                            processing: true,
                            serverSide: true,
                            order: [
                                [4, 'asc']
                            ],
                            ajax: {
                                url: '',
                                type: "GET",
                                data: function(d) {
                                    // Jika sudah ada pilihan di dropdown, kirim `capex_id`, jika tidak biarkan kosong
                                    const selectedCapexId = $('#descriptionSelect').val();
                                    d.capex_id = selectedCapexId || '';
                                }
                            },
                            columns: [{
                                    data: 'id_report_cip',
                                    name: 'id_report_cip',
                                    className: 'text-center'
                                },
                                {
                                    data: 'id_capex',
                                    name: 'id_capex',
                                    className: 'text-center'
                                },
                                {
                                    data: 'id_head',
                                    name: 'id_head',
                                    className: 'text-center'
                                },
                                {
                                    data: 'fa_doc',
                                    name: 'fa_doc',
                                    className: 'text-right'
                                },
                                {
                                    data: 'date',
                                    name: 'date',
                                    className: 'text-right',
                                    render: function(data, type, row) {
                                        if (type === 'sort' || type === 'filter') {
                                            return data;
                                        }
                                        return moment(data).format('DD/MM/YYYY');
                                    }
                                },
                                {
                                    data: 'settle_doc',
                                    name: 'settle_doc',
                                    className: 'text-right'
                                },
                                {
                                    data: 'material',
                                    name: 'material',
                                    className: 'text-right'
                                },
                                {
                                    data: 'description',
                                    name: 'description',
                                    createdCell: function(td, cellData, rowData, rowIndex, colIndex){
                                        $(td).css('text-align','left');
                                    }
                                   
                                },
                                {
                                    data: 'qty',
                                    name: 'qty',
                                    className: 'text-right',
                                    render: function(data, type, row) {
                                        return '<div style="text-align: right;">' + data + '</div>';
                                    }
                                },
                                {
                                    data: 'uom',
                                    name: 'uom',
                                    className: 'text-center'
                                },
                                {
                                    data: 'amount_rp',
                                    name: 'amount_rp',
                                    className: 'text-right',
                                    render: function(data, type, row) {
                                        return '<div style="text-align: right;">' + (data ? number_format(data,
                                            0, ',', '.') : '-') + '</div>';
                                    }
                                },

                                {
                                    data: 'amount_us',
                                    name: 'amount_us',
                                    className: 'text-right',
                                    render: function(data, type, row) {
                                        return '<div style="text-align: right;">' + (data ? number_format(data,
                                            0, ',', '.') : '-') + '</div>';
                                    }
                                },
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
                            ],
                            // Tambahkan drawCallback untuk menghitung total
                            drawCallback: function(settings) {
                                var api = this.api();

                                // Dapatkan nilai filter aktif dari dropdown
                                var activeCapex = document.querySelector('#descriptionSelect').textContent.trim();

                                // Reset total di footer
                                $('#total-amount-rp').text('Total (RP)');
                                $('#total-amount-us').text('Total (US$)');

                                // Jika masih 'Pilih Capex' atau kosong, return tanpa melakukan perhitungan
                                if (activeCapex === 'Pilih Capex' || !activeCapex) {
                                    return;
                                }

                                var totalsByHead = {};
                                var data = api.rows({
                                    page: 'current'
                                }).data();

                                // Iterasi melalui data untuk menghitung total
                                data.each(function(row) {
                                    var idHead = row.id_head;

                                    // Konversi string ke number dan handle format angka
                                    var amountRp = parseFloat(row.amount_rp) || 0;
                                    var amountUs = parseFloat(row.amount_us) || 0;

                                    // Inisialisasi atau update total untuk id_head ini
                                    if (!totalsByHead[idHead]) {
                                        totalsByHead[idHead] = {
                                            rp: 0,
                                            us: 0
                                        };
                                    }
                                    totalsByHead[idHead].rp += amountRp;
                                    totalsByHead[idHead].us += amountUs;
                                });

                                // Variabel untuk total keseluruhan
                                var totalRp = 0;
                                var totalUs = 0;

                                // Jika ada data yang dihitung, akumulasi total
                                if (Object.keys(totalsByHead).length > 0) {
                                    Object.entries(totalsByHead).forEach(function([idHead, totals]) {
                                        totalRp += totals.rp; // Akumulasi total RP
                                        totalUs += totals.us; // Akumulasi total US$
                                    });

                                    // Update total di footer
                                    $('#total-amount-rp').text(number_format(totalRp, 0, ',',
                                        '.')); // Format untuk RP
                                    $('#total-amount-us').text(number_format(totalUs, 2, ',',
                                        '.')); // Format untuk US$
                                }
                            }
                        });

                        // Fungsi untuk memformat angka dengan tanda pemisah ribuan
                        function formatNumber(num) {
                            return num.toString().replace(/\B(?=(\d{3})+(?!\d))/g, "."); // Memformat angka dengan titik
                        }

                        // Ambil semua item dropdown
                        $(document).ready(function() {
                            // Inisialisasi Select2 tanpa AJAX
                            var descriptionSelect = $('#descriptionSelect');
                            if (descriptionSelect.length) {
                                descriptionSelect.select2({
                                    placeholder: 'Cari Capex',
                                    allowClear: true,
                                    minimumInputLength: 2 // Aktifkan pencarian setelah mengetik 2 karakter
                                });
                            }

                            // Event handler ketika pengguna memilih opsi di Select2
                            descriptionSelect.on('select2:select', function(e) {
                                // Ambil elemen terpilih
                                const selectedOption = $(this).find(':selected');

                                // Ambil data dari atribut data-* pada opsi yang dipilih
                                const value = selectedOption.val();
                                const text = selectedOption.text();
                                const cipNumber = selectedOption.data('cip');
                                const wbsNumber = selectedOption.data('wbs');
                                const project = selectedOption.data('project_desc');
                                const budget = selectedOption.data('budget_type');
                                const amount = selectedOption.data('amount_budget');
                                const wbsCapex = selectedOption.data('wbs_capex');
                                const requester = selectedOption.data('requester');
                                const statusCapex = selectedOption.data('status_capex');

                                // Perbarui tampilan sesuai dengan pilihan yang diambil
                                $('#descriptionText').text(text).attr('data-capex-id', value);
                                $('#wbscapexText').text(wbsCapex || 'WBS Capex');
                                $('#cipText').text(cipNumber || 'CIP Number');
                                $('#wbsText').text(wbsNumber || 'WBS Number');
                                $('#projectText').text(project || 'Project');
                                $('#budgetText').text(budget || 'Budget');
                                $('#requesterText').val(requester || 'Requester');
                                $('#statusText').text(statusCapex || 'Status Capex');
                                $('#amountText').text(amount ? formatNumber(amount) : 'Amount');
                                $('#statusSelect').val(statusCapex).trigger('change'); // Set nilai dropdown

                                if (value) {
                                    // Jika ada value (data dipilih), update URL dengan parameter capex_id
                                    table.ajax.url('{{ route('report.index') }}?capex_id=' + value).load();
                                } else {
                                    // Jika tidak ada pilihan (clear), reset pencarian dan kembali ke URL default
                                    table.ajax.url('{{ route('report.index') }}').load();
                                }
                            });
                        });


                        $('#descriptionSelect').on('select2:unselect', function(e) {
                            table.ajax.url('').load();
                        });

                        function number_format(number, decimals, decPoint, thousandsSep) {
                            number = (number + '').replace(',', '').replace(' ', '');
                            var n = !isFinite(+number) ? 0 : +number,
                                prec = !isFinite(+decimals) ? 0 : Math.abs(decimals),
                                sep = (typeof thousandsSep === 'undefined') ? '.' : thousandsSep,
                                dec = (typeof decPoint === 'undefined') ? ',' : decPoint,
                                toFixedFix = function(n, prec) {
                                    var k = Math.pow(10, prec);
                                    return '' + Math.round(n * k) / k;
                                };

                            // Format number
                            var result = (prec ? toFixedFix(n, prec) : '' + Math.round(n)).split('.');
                            if (result[0].length > 3) {
                                result[0] = result[0].replace(/\B(?=(\d{3})+(?!\d))/g, sep);
                            }
                            if ((result[1] || '').length < prec) {
                                result[1] = result[1] || '';
                                result[1] += new Array(prec - result[1].length + 1).join('0');
                            }
                            return result.join(dec);
                        }

                        var statusSelect = $('#statusSelect');
                        if (statusSelect.length) {
                            statusSelect.select2({
                                placeholder: 'Cari Status',
                                allowClear: true,
                                minimumResultsForSearch: Infinity // Menonaktifkan pencarian

                            });
                        }

                        // Event handler untuk perubahan status
                        $('#statusSelect').on('select2:select', function(e) {
                            const selectedStatus = $(this).val();

                            if (selectedStatus) {
                                // Reset field text ke default karena kita menampilkan multiple data
                                $('#descriptionText').text('Description').attr('data-capex-id', '');
                                $('#wbscapexText').text('WBS Capex');
                                $('#cipText').text('CIP Number');
                                $('#wbsText').text('WBS Number');
                                $('#projectText').text('Project');
                                $('#budgetText').text('Budget');
                                $('#requesterText').val('Requester');
                                $('#statusText').text(selectedStatus); // Tetap tampilkan status yang dipilih
                                $('#amountText').text('Amount');

                                // Reset descriptionSelect karena kita menampilkan multiple data
                                $('#descriptionSelect').val('').trigger('change');

                                // Update table dengan filter status_capex
                                table.ajax.url('{{ route('report.index') }}?status_capex=' + selectedStatus).load();
                            } else {
                                // Jika tidak ada status yang dipilih, reset semua ke default
                                $('#descriptionText').text('Description').attr('data-capex-id', '');
                                $('#wbscapexText').text('WBS Capex');
                                $('#cipText').text('CIP Number');
                                $('#wbsText').text('WBS Number');
                                $('#projectText').text('Project');
                                $('#budgetText').text('Budget');
                                $('#requesterText').val('Requester');
                                $('#statusText').text('Status Capex');
                                $('#amountText').text('Amount');

                                // Reset descriptionSelect
                                $('#descriptionSelect').val('').trigger('change');

                                if (selectedStatus) {
                                    table.ajax.url('{{ route('report.index') }}?status_capex=' + selectedStatus)
                                .load();
                                } else {
                                    table.ajax.url('{{ route('report.index') }}').load();
                                }
                            }
                        });

                        $('#statusSelect').on('select2:unselect', function(e){
                            table.ajax.url('').load();
                        });
                        

                        //download pdf
                        $(document).ready(function() {
                            window.downloadFilteredPDF = function() {
                                // Ambil capex_id langsung dari Select2
                                const selectedCapexId = $('#descriptionSelect').val();

                                // Cek apakah capex_id sudah dipilih
                                if (!selectedCapexId) {
                                    Swal.fire({
                                        icon: 'warning',
                                        title: 'Capex Belum Dipilih',
                                        text: 'Silakan pilih Capex terlebih dahulu sebelum mengunduh PDF.',
                                        confirmButtonText: 'OK'
                                    });
                                    return;
                                }

                                // Tampilkan SweetAlert sebelum membuka PDF
                                Swal.fire({
                                    icon: 'info',
                                    title: 'Sedang Mengunduh...',
                                    text: 'Tunggu sebentar, PDF sedang diproses.',
                                    allowOutsideClick: false, // Menghindari klik di luar modal Swal
                                    didOpen: () => {
                                        Swal.showLoading(); // Menampilkan loading spinner
                                    }
                                });

                                // Jika sudah ada yang dipilih, lanjutkan dengan membuka PDF
                                const url =
                                    `{{ route('report.show', ':id') }}?pdf=filtered&capex_id=${selectedCapexId}`
                                    .replace(':id', selectedCapexId);

                                // Setelah URL dibuka, menutup Swal dan membuka PDF
                                window.open(url, '_blank');

                                // Menutup SweetAlert loading setelah URL dibuka
                                Swal.close();
                            };
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
        #report-table {
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

    #report-table thead th {
        background-color: #3cb210;
        /* Warna latar belakang header */
        color: #ffffff;
        /* Warna teks header */
    }

    #budget-table thead th {
        background-color: #3cb210;
        /* Warna latar belakang header */
        color: #ffffff;
        /* Warna teks header */
    }

    /* Gaya untuk baris tabel */
    #report-table tbody tr {
        transition: background-color 0.3s ease;
        /* Efek transisi untuk warna latar belakang */
        color: #2c2626;
    }

    /* Gaya untuk sel tabel */
    #report-table tbody td {
        padding: 10px;
        /* Padding untuk sel */
        border-bottom: 1px solid #dee2e6;
        /* Garis bawah sel */
        color: #2c2626;
    }

    /* Hover effect untuk baris tabel */
    #report-table tbody tr:hover {
        background-color: rgba(0, 123, 255, 0.1);
        /* Warna latar belakang saat hover */
    }

    #report-table th,
    #report-table td {
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

    .info-box-container {
        display: flex;
        flex-wrap: wrap;
        gap: 15px;
        margin-bottom: 20px;
    }

    .info-box {
        background: #040404;
        /* Tetap menggunakan warna latar belakang hitam */
        border-radius: 12px;
        /* Membuat sudut lebih melengkung untuk tampilan card */
        padding: 16px 20px;
        /* Sedikit menambah padding atas bawah */
        min-width: 200px;
        flex: 1;
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.3),
            /* Shadow lebih dalam */
            0 1px 3px rgba(0, 0, 0, 0.2);
        /* Shadow tambahan untuk efek layer */
        transition: all 0.2s ease;
        /* Transisi untuk semua perubahan */
        color: #fff;
        border: 1px solid rgba(255, 255, 255, 0.1);
        /* Tambah border subtle */
    }

    .info-box:hover {
        transform: translateY(-3px);
        /* Sedikit lebih tinggi saat hover */
        box-shadow: 0 6px 15px rgba(0, 0, 0, 0.4),
            /* Shadow lebih besar saat hover */
            0 2px 4px rgba(0, 0, 0, 0.3);
        border: 1px solid rgba(255, 255, 255, 0.15);
        /* Border lebih terang saat hover */
    }

    .info-box-label {
        color: #f1f5f0;
        font-size: 0.875rem;
        margin-bottom: 8px;
        /* Sedikit lebih besar margin bottom */
        text-transform: uppercase;
        letter-spacing: 0.75px;
        /* Sedikit lebih besar letter spacing */
        font-weight: bold;
        opacity: 0.9;
        /* Sedikit transparent untuk kontras */
    }

    .info-box-value {
        color: white;
        font-size: 1.125rem;
        /* Sedikit lebih besar ukuran font */
        font-weight: 600;
        margin: 0;
        letter-spacing: 0.25px;
        /* Tambah sedikit letter spacing */
    }

    .select2-container .select2-selection--single {
        height: 45px; /* Menyesuaikan tinggi agar lebih proporsional */
        padding-left: 15px; /* Memberikan ruang lebih pada sisi kiri */
        padding-right: 15px; /* Memberikan ruang lebih pada sisi kanan */
        font-size: 15px; /* Ukuran font lebih besar untuk keterbacaan */
        border-radius: 8px; /* Membuat sudut lebih halus */
        border: 1px solid #ccc; /* Border abu-abu muda untuk kesan elegan */
        background-color: #ffffff; /* Latar belakang putih agar bersih */
        color: #000000; /* Warna teks abu-abu gelap untuk kontras yang baik */
        box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1); /* Menambahkan bayangan halus di sekitar dropdown */
        transition: all 0.3s ease; /* Menambahkan transisi halus saat berinteraksi */
    }

    /* Efek fokus pada select2 */
    .select2-container .select2-selection--single:focus {
        border-color: #3cff00; /* Mengubah border menjadi biru saat fokus */
        box-shadow: 0 0 5px rgba(0, 123, 255, 0.5); /* Menambahkan bayangan biru saat fokus */
        outline: none; /* Menghilangkan outline default */
    }
    
</style>
