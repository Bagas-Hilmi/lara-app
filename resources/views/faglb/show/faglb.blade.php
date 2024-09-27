@extends('layouts.app') <!-- Ganti dengan layout yang sesuai -->

@section('content')
<div class="container" style="margin-top: 70px;">
    <h1 class="mb-4">Data FAGLB</h1>

    @if($faglbData->isEmpty())
        <div class="alert alert-warning" role="alert">
            Tidak ada data yang ditemukan.
        </div>
    @else
        <div class="table-responsive">
            <table class="table table-striped table-bordered" id="faglbTable">
                <thead class="thead-dark">
                    <tr class="text-center">
                        <th style="text-align: center; white-space: nowrap; vertical-align: middle;">ID_tail</th>
                        <th style="text-align: center; white-space: nowrap; vertical-align: middle;">ID_head</th>
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
                            <td>{{ $data->id_tail }}</td>
                            <td>{{ $data->id_head }}</td>
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
                            <td>{{ $data->amount_in_doc_curr }}</td>
                            <td>{{ $data->local_currency }}</td>
                            <td>{{ $data->amount_in_lc }}</td>
                            <td>{{ $data->local_currency_2 }}</td>
                            <td>{{ $data->amount_in_loc_curr_2 }}</td>
                            <td>{{ $data->text }}</td>
                            <td>{{ $data->assignment }}</td>
                            <td>{{ $data->profit_center }}</td>
                            <td>{{ $data->wbs_element }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    @endif
</div>
<script>
    $(document).ready(function() {
        $('#faglbTable').DataTable({
            "paging": true,
            "searching": true,
            "ordering": true,
            "info": true,
            "autoWidth": false,
            "responsive": true,
            "scrollX": true // Menambahkan fitur scroll horizontal
        });
    });
</script>
@endsection
