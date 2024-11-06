@section('title', 'Report CIP')


<x-layout bodyClass="g-sidenav-show  bg-gray-200">
    <x-navbars.sidebar activePage="report"></x-navbars.sidebar>
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
                                    <div class="dropdown">
                                        <button class="btn btn-secondary dropdown-toggle"
                                            style="background-color: #040404; border-color: #09170a;" 
                                            type="button"
                                            id="descriptionDropdown" 
                                            data-bs-toggle="dropdown" 
                                            aria-expanded="false">
                                            <span id="descriptionText">Pilih Capex </span> <!-- Placeholder -->
                                        </button>

                                        <button class="btn btn-secondary" style="background-color: #09170a; border-color: #09170a;">
                                            <span id="wbscapexText">WBS Type</span> 
                                        </button>
                                        
                                       <!-- Button to open the modal -->
                                        <button onclick="downloadFilteredPDF()" class="btn btn-secondary" style="background-color: #bd1f20;">
                                            <i class="fas fa-file-pdf"></i> Download PDF
                                        </button>


                                        <ul class="dropdown-menu" aria-labelledby="descriptionDropdown">
                                            <li><a class="dropdown-item" href="#" data-value="">Pilih Capex </a></li>
                                            @foreach ($descriptions as $desc)
                                                <li>
                                                    <a class="dropdown-item" href="#"
                                                        data-value="{{ $desc->id_capex }}"
                                                        data-cip="{{ $desc->cip_number }}"
                                                        data-wbs="{{ $desc->wbs_number }}"
                                                        data-project_desc="{{ $desc->project_desc }}"
                                                        data-budget_type="{{ $desc->budget_type }}"
                                                        data-amount_budget="{{ $desc->amount_budget }}"
                                                        data-wbs_capex="{{ $desc->wbs_capex }}">
                                                        {{ $desc->capex_number }}
                                                        <!-- Kolom capex_number yang ditampilkan -->
                                                    </a>
                                                </li>
                                            @endforeach
                                        </ul>
                                        
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
                                        </div>

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

            @include('report.new-report')

            @push('js')
                <script src="{{ asset('assets/js/jquery.min.js') }}"></script>
                <script src="assets/js/plugins/sweetalert.min.js"></script>
                <script src="{{ asset('assets/datatables/dataTables.min.js') }}"></script>
                <script src="assets/js/moment.min.js"></script>
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
                        var table = $('#report-table').DataTable({
                            responsive: false,
                            processing: true,
                            serverSide: true,
                            order: [
                                [4, 'asc']
                            ],
                            ajax: {
                                url: "{{ route('report.index') }}",
                                type: "GET",
                                data: function(d) {
                                    // Mengatur parameter pencarian berdasarkan dropdown yang dipilih
                                    d.searchValue = document.getElementById('descriptionText').textContent;
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
                                }},
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
                                    className: 'text-center'
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
                                        return data ? number_format(data, 2, ',', '.') : '-';
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
                                var activeCapex = document.querySelector('#descriptionText').textContent.trim();

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
                        const dropdownItems = document.querySelectorAll('.dropdown-item');
                        dropdownItems.forEach(item => {
                            item.addEventListener('click', function(event) {
                                event.preventDefault(); // Mencegah link melakukan refresh halaman

                                // Ambil nilai dari data-value, data-cip, dan data-wbs
                                const value = this.getAttribute('data-value');
                                const text = this.textContent; // Ambil teks dari item dropdown
                                const cipNumber = this.getAttribute('data-cip');
                                const wbsNumber = this.getAttribute('data-wbs');
                                const project = this.getAttribute('data-project_desc');
                                const budget = this.getAttribute('data-budget_type');
                                const amount = this.getAttribute('data-amount_budget');
                                const wbsCapex = this.getAttribute('data-wbs_capex');

                              // Perbarui teks dan data pada tombol dropdown
                                const descriptionText = document.getElementById('descriptionText');
                                descriptionText.textContent = text;
                                descriptionText.setAttribute('data-capex-id', value); // Tambahkan ini
                                descriptionText.setAttribute('data-wbs-capex', wbsCapex); // Tambahkan atribut wbs_capex


                                // Mengubah teks pada tombol cip dan wbs
                                document.getElementById('cipText').innerText = cipNumber ? cipNumber :'CIP Number';
                                document.getElementById('wbsText').innerText = wbsNumber ? wbsNumber :'WBS Number';
                                document.getElementById('projectText').innerText = project ? project :'Project';
                                document.getElementById('budgetText').innerText = budget ? budget : 'Budget';
                                document.getElementById('wbscapexText').innerText = wbsCapex ? wbsCapex : 'WBS Capex';
                                document.getElementById('amountText').innerText = amount ? formatNumber(amount) : 'Amount'; // Format amount budget
                                // Mengatur filter DataTable berdasarkan value
                                table.column(1).search(value).draw(); // Ganti 0 dengan indeks kolom yang sesuai
                                // Reload DataTable
                                table.ajax.reload(); // Memanggil reload untuk mendapatkan data terbaru
                            });
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


                        $("#report-table").on("click", ".delete-btn", function() {
                            var id = $(this).data("id"); // Ambil ID dari data-id

                            // Tampilkan SweetAlert untuk konfirmasi penghapusan
                            Swal.fire({
                                title: "Konfirmasi Hapus",
                                text: "Apakah Anda yakin ingin menghapus item ini?",
                                icon: "warning", // Tampilkan ikon peringatan
                                showCancelButton: true, // Tampilkan tombol batal
                                confirmButtonColor: "#3085d6", // Warna tombol konfirmasi
                                cancelButtonColor: "#d33", // Warna tombol batal
                                confirmButtonText: "Ya, hapus!",
                                cancelButtonText: "Batal",
                            }).then((result) => {
                                if (result.isConfirmed) {
                                    // Jika pengguna mengkonfirmasi penghapusan
                                    $.ajax({
                                        url: "/report/" + id,
                                        type: "POST",
                                        data: {
                                            _method: "DELETE", // Laravel membutuhkan ini untuk metode DELETE
                                            _token: $('meta[name="csrf-token"]').attr(
                                                "content"), // Sertakan CSRF token
                                        },
                                        success: function(result) {
                                            // Menampilkan notifikasi sukses
                                            Swal.fire(
                                                "Terhapus!",
                                                "Item telah berhasil dihapus.",
                                                "success"
                                            );
                                            // Reload DataTable
                                            $("#report-table").DataTable().ajax.reload();
                                        },
                                        error: function(xhr) {
                                            // Menampilkan notifikasi jika terjadi error
                                            Swal.fire(
                                                "Gagal!",
                                                "Terjadi kesalahan saat menghapus item.",
                                                "error"
                                            );
                                        },
                                    });
                                }
                            });
                        });
                        
                        //download pdf
                        $(document).ready(function() {
                            window.downloadFilteredPDF = function() {
                                const selectedCapexId = $('#descriptionText').attr('data-capex-id');
                                
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
                                
                                // Cek WBS Capex sebelum menampilkan modal
                                const wbsCapex = $('#descriptionText').attr('data-wbs-capex');
                                if (wbsCapex === "Non Project") {
                                    $('#confirmedForm').hide();
                                } else {
                                    $('#confirmedForm').show();
                                }
                                
                                // Reset form sebelum menampilkan modal
                                $('#signatureForm')[0].reset();
                                $('#confirmedForm')[0].reset();
                                
                                // Tampilkan modal
                                $('#signatureModal').modal('show');
                            };

                            // Fungsi untuk memproses download
                            window.proceedWithDownload = function() {
                                const signatureName = $('#signatureName').val();
                                const wbsCapex = $('#descriptionText').attr('data-wbs-capex');
                                const selectedCapexId = $('#descriptionText').attr('data-capex-id');
                                
                                // Validasi signature name
                                if (!signatureName) {
                                    Swal.fire({
                                        icon: 'warning',
                                        title: 'Nama Tanda Tangan Kosong',
                                        text: 'Silakan masukkan nama tanda tangan sebelum melanjutkan.',
                                        confirmButtonText: 'OK'
                                    });
                                    return;
                                }

                                // Validasi confirmed name hanya jika bukan Non Project
                                const confirmedName = $('#confirmedName').val();
                                if (wbsCapex !== "Non Project" && !confirmedName) {
                                    Swal.fire({
                                        icon: 'warning',
                                        title: 'Nama Konfirmasi Kosong',
                                        text: 'Silakan masukkan nama konfirmasi sebelum melanjutkan.',
                                        confirmButtonText: 'OK'
                                    });
                                    return;
                                }

                                // Buat URL untuk download
                                let url = `{{ route('report.show', ':id') }}?pdf=filtered&capex_id=${selectedCapexId}&signature_name=${encodeURIComponent(signatureName)}`;
                                
                                // Tambahkan confirmed_name ke URL hanya jika bukan Non Project
                                if (wbsCapex !== "Non Project") {
                                    url += `&confirmed_name=${encodeURIComponent(confirmedName)}`;
                                    
                                }

                                // Tutup modal
                                $('#signatureModal').modal('hide');

                                // Reset form
                                $('#signatureForm')[0].reset();
                                $('#confirmedForm')[0].reset();

                                // Buka PDF di tab baru
                                window.open(url, '_blank');
                            };

                            // Event listener untuk modal show
                            $('#signatureModal').on('show.bs.modal', function() {
                                const wbsCapex = $('#descriptionText').attr('data-wbs-capex');
                                if (wbsCapex === "Non Project") {
                                    $('#confirmedForm').hide();
                                } else {
                                    $('#confirmedForm').show();
                                }
                            });
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
</style>
