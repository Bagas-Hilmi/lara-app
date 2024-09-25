@extends('layouts.app') <!-- Ganti dengan layout yang sesuai -->

@section('content')
<div class="container">
    <h1 class="mb-4">Data FAGLB</h1>

    @if($faglbData->isEmpty())
        <div class="alert alert-warning" role="alert">
            Tidak ada data yang ditemukan.
        </div>
    @else
        <div class="table-responsive">
            <table class="table table-striped table-bordered">
                <thead class="thead-dark">
                    <tr>
                        <th>ID_tail</th>
                        <th>ID_head</th>
                        <th>Asset</th>
                        <th>Sub-number</th>
                        <th>Posting Date</th>
                        <th>Document Number</th>
                        <th>Reference Key</th>
                        <th>Material</th>
                        <th>Business Area</th>
                        <th>Quantity</th>
                        <th>Base Unit of Measure</th>
                        <th>Document Type</th>
                        <th>Posting Key</th>
                        <th>Document Currency</th>
                        <th>Amount in Doc. Curr.</th>
                        <th>Local Currency</th>
                        <th>Amount in LC</th>
                        <th>Local Currency 2</th>
                        <th>Amount in Loc. Curr. 2</th>
                        <th>Text</th>
                        <th>Assignment</th>
                        <th>Profit Center</th>
                        <th>WBS Element</th>
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
@endsection
