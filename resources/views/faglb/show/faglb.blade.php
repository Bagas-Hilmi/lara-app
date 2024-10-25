@extends('layouts.app') <!-- Ganti dengan layout yang sesuai -->
@section('title', 'DATA FAGLB')
@section('content')
    <div class="container" style="margin-top: 70px;">
        <div class="row">
            <div class="col-12">
                <div class="card my-4">
                    <div class="card-header p-0 position-relative mt-n4 mx-3 z-index-2">
                        <div class="bg-gradient-primary shadow-primary border-radius-lg pt-4 pb-3">
                            <h1 class="text-capitalize ps-3">Data FAGLB</h1>
                            <button onclick="javascript:history.back()" class="btn btn-secondary mt-3 ms-3">Back</button>
                        </div>
                        <div class="card-body p-3">
                            <div class="table-responsive">
                                <table class="table table-striped table-bordered" id="faglbTable">
                                    <thead class="thead-dark">
                                        <tr class="text-center">
                                            <th style="text-align: center; white-space: nowrap; vertical-align: middle;">No</th>
                                            <th style="text-align: center; white-space: nowrap; vertical-align: middle;">Asset</th>
                                            <th style="text-align: center; white-space: nowrap; vertical-align: middle;">Sub-number</th>
                                            <th style="text-align: center; white-space: nowrap; vertical-align: middle;">Posting Date</th>
                                            <th style="text-align: center; white-space: nowrap; vertical-align: middle;">Document Number</th>
                                            <th style="text-align: center; white-space: nowrap; vertical-align: middle;">Reference Key</th>
                                            <th style="text-align: center; white-space: nowrap; vertical-align: middle;">Material</th>
                                            <th style="text-align: center; white-space: nowrap; vertical-align: middle;">Business Area</th>
                                            <th style="text-align: center; white-space: nowrap; vertical-align: middle;">Quantity</th>
                                            <th style="text-align: center; white-space: nowrap; vertical-align: middle;">Base Unit of Measure</th>
                                            <th style="text-align: center; white-space: nowrap; vertical-align: middle;">Document Type</th>
                                            <th style="text-align: center; white-space: nowrap; vertical-align: middle;">Posting Key</th>
                                            <th style="text-align: center; white-space: nowrap; vertical-align: middle;">Document Currency</th>
                                            <th style="text-align: center; white-space: nowrap; vertical-align: middle;">Amount in Doc. Curr.</th>
                                            <th style="text-align: center; white-space: nowrap; vertical-align: middle;">Local Currency</th>
                                            <th style="text-align: center; white-space: nowrap; vertical-align: middle;">Amount in LC</th>
                                            <th style="text-align: center; white-space: nowrap; vertical-align: middle;">Local Currency 2</th>
                                            <th style="text-align: center; white-space: nowrap; vertical-align: middle;">Amount in Loc. Curr. 2</th>
                                            <th style="text-align: center; white-space: nowrap; vertical-align: middle;">Text</th>
                                            <th style="text-align: center; white-space: nowrap; vertical-align: middle;">Assignment</th>
                                            <th style="text-align: center; white-space: nowrap; vertical-align: middle;">Profit Center</th>
                                            <th style="text-align: center; white-space: nowrap; vertical-align: middle;">WBS Element</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($faglbData as $data)
                                            <tr>
                                                <td style="text-align: center;"></td>
                                                <td>{{ $data->asset }}</td>
                                                <td>{{ $data->sub_number }}</td>
                                                <td>{{ $data->posting_date }}</td>
                                                <td>{{ $data->document_number }}</td>
                                                <td>{{ $data->reference_key }}</td>
                                                <td>{{ $data->material }}</td>
                                                <td>{{ $data->business_area }}</td>
                                                <td>{{ $data->quantity }}</td>
                                                <td>{{ $data->base_unit_of_measure }}</td>
                                                <td>{{ $data->document_type }}</td>
                                                <td>{{ $data->posting_key }}</td>
                                                <td>{{ $data->document_currency }}</td>
                                                <td>{{ number_format($data->amount_in_doc_curr, 2 ,',', '.') }}</td>
                                                <td>{{ $data->local_currency }}</td>
                                                <td>{{ number_format($data->amount_in_lc, 2, ',', '.') }}</td>
                                                <td>{{ $data->local_currency_2 }}</td>
                                                <td>{{ number_format($data->amount_in_loc_curr_2, 0,',', '.') }}</td>  
                                                <td>{{ $data->text }}</td>
                                                <td>{{ $data->assignment }}</td>
                                                <td>{{ $data->profit_center }}</td>
                                                <td>{{ $data->wbs_element }}</td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            </div>
    </div>

    @push('js')
        
    @endpush
    <script>
        $(document).ready(function() {
            $('#faglbTable').DataTable({
                "paging": true,
                "searching": true,
                "ordering": true,
                "info": true,
                "autoWidth": false,
                "responsive": false,
                "scrollX": true, // Menambahkan fitur scroll horizontal
                "columnDefs": [
                    { 
                        "targets": 0, // Menargetkan kolom pertama untuk nomor urut
                        "data": null,
                        "orderable": false,
                        "searchable": false,
                        "render": function (data, type, row, meta) {
                            return meta.row + 1; // Menghitung nomor urut
                        }
                    }
                ]
            });
        });
    </script>
@endsection

<style>
    table {
        table-layout: auto; /* Kolom menyesuaikan dengan isi */
        width: 100%; /* Tabel menggunakan seluruh lebar kontainer */
    }

    table td, table th {
        height: 50px; /* Mengatur tinggi baris */
        vertical-align: middle; /* Konten vertikal di tengah */
        white-space: nowrap; /* Mencegah teks turun ke baris berikutnya */
        overflow: hidden; /* Memotong teks yang terlalu panjang */
        text-overflow: ellipsis; /* Menambahkan elipsis (...) jika teks terlalu panjang */
    }
</style>
