@extends('layouts.app') <!-- Ganti dengan layout yang sesuai -->

@section('content')
<div class="container" style="margin-top: 70px;">
    <h1 class="mb-4">Data ZLIS1</h1>

    @if($zlis1Data->isEmpty())
        <div class="alert alert-warning" role="alert">
            Tidak ada data yang ditemukan.
        </div>
    @else
    <div class="table-responsive">
        <table class="table table-striped table-bordered" id="zlis1Table">
                <thead class="thead-dark">
                    <tr class="text-center">
                        <th style="text-align: center; white-space: nowrap; vertical-align: middle;">ID</th>
                        <th style="text-align: center; white-space: nowrap; vertical-align: middle;">ID_head</th>
                        <th style="text-align: center; white-space: nowrap; vertical-align: middle;">WBS Element</th>
                        <th style="text-align: center; white-space: nowrap; vertical-align: middle;">Network</th>
                        <th style="text-align: center; white-space: nowrap; vertical-align: middle;">Document Number</th>
                        <th style="text-align: center; white-space: nowrap; vertical-align: middle;">Company Code</th>
                        <th style="text-align: center; white-space: nowrap; vertical-align: middle;">Fiscal Year</th>
                        <th style="text-align: center; white-space: nowrap; vertical-align: middle;">Item</th>
                        <th style="text-align: center; white-space: nowrap; vertical-align: middle;">Material Document</th>
                        <th style="text-align: center; white-space: nowrap; vertical-align: middle;">Material Doc. Year</th>
                        <th style="text-align: center; white-space: nowrap; vertical-align: middle;">Material</th>
                        <th style="text-align: center; white-space: nowrap; vertical-align: middle;">Description</th>
                        <th style="text-align: center; white-space: nowrap; vertical-align: middle;">Quantity</th>
                        <th style="text-align: center; white-space: nowrap; vertical-align: middle;">Base Unit of Measure</th>
                        <th style="text-align: center; white-space: nowrap; vertical-align: middle;">Value TranCurr</th>
                        <th style="text-align: center; white-space: nowrap; vertical-align: middle;">Currency</th>
                        <th style="text-align: center; white-space: nowrap; vertical-align: middle;">Value TranCurr</th>
                        <th style="text-align: center; white-space: nowrap; vertical-align: middle;">Currency</th>
                        <th style="text-align: center; white-space: nowrap; vertical-align: middle;">Value TranCurr</th>
                        <th style="text-align: center; white-space: nowrap; vertical-align: middle;">Currency</th>
                        <th style="text-align: center; white-space: nowrap; vertical-align: middle;">Document Date</th>
                        <th style="text-align: center; white-space: nowrap; vertical-align: middle;">Posting Date</th>
                        <th style="text-align: center; white-space: nowrap; vertical-align: middle;">Purchasing Document</th>
                        <th style="text-align: center; white-space: nowrap; vertical-align: middle;">Supplier</th>
                        <th style="text-align: center; white-space: nowrap; vertical-align: middle;">Name 1</th>
                        <th style="text-align: center; white-space: nowrap; vertical-align: middle;">Asset</th>
                        <th style="text-align: center; white-space: nowrap; vertical-align: middle;">Sub-number</th>
                        <th style="text-align: center; white-space: nowrap; vertical-align: middle;">Cost Center</th>
                        <th style="text-align: center; white-space: nowrap; vertical-align: middle;">G/L Account</th>
                        <th style="text-align: center; white-space: nowrap; vertical-align: middle;">Document Number</th>
                        <th style="text-align: center; white-space: nowrap; vertical-align: middle;">Company Code</th>
                        <th style="text-align: center; white-space: nowrap; vertical-align: middle;">Fiscal Year</th>
                        <th style="text-align: center; white-space: nowrap; vertical-align: middle;">Document Date</th>
                        <th style="text-align: center; white-space: nowrap; vertical-align: middle;">Posting Date</th>
                        <th style="text-align: center; white-space: nowrap; vertical-align: middle;">User Name</th>
                        <th style="text-align: center; white-space: nowrap; vertical-align: middle;">Reversed with</th>
                        <th style="text-align: center; white-space: nowrap; vertical-align: middle;">WBS level 2</th>
                        <th style="text-align: center; white-space: nowrap; vertical-align: middle;">WBS Element</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($zlis1Data as $data)
                        <tr>
                            <td>{{ $data->id }}</td>
                            <td>{{ $data->id_head }}</td>
                            <td>{{ $data->wbs_element }}</td>
                            <td>{{ $data->network }}</td>
                            <td>{{ $data->document_number }}</td>
                            <td>{{ $data->company_code }}</td>
                            <td>{{ $data->fiscal_year }}</td>
                            <td>{{ $data->item }}</td>
                            <td>{{ $data->material_document }}</td>
                            <td>{{ $data->material_doc_year }}</td>
                            <td>{{ $data->material }}</td>
                            <td>{{ $data->description }}</td>
                            <td>{{ $data->quantity }}</td>
                            <td>{{ $data->base_unit_of_measure }}</td>
                            <td>{{ $data->value_tran_curr_1 }}</td>
                            <td>{{ $data->currency }}</td>
                            <td>{{ $data->value_tran_curr_2 }}</td>
                            <td>{{ $data->currency_2 }}</td>
                            <td>{{ $data->value_tran_curr_3 }}</td>
                            <td>{{ $data->currency_3 }}</td>
                            <td>{{ $data->document_date }}</td>
                            <td>{{ $data->posting_date }}</td>
                            <td>{{ $data->purchasing_document }}</td>
                            <td>{{ $data->supplier }}</td>
                            <td>{{ $data->name_1 }}</td>
                            <td>{{ $data->asset }}</td>
                            <td>{{ $data->sub_number }}</td>
                            <td>{{ $data->cost_center }}</td>
                            <td>{{ $data->gl_account }}</td>
                            <td>{{ $data->document_number_2 }}</td>
                            <td>{{ $data->company_code_2 }}</td>
                            <td>{{ $data->fiscal_year_2 }}</td>
                            <td>{{ $data->document_date_2 }}</td>
                            <td>{{ $data->posting_date_2 }}</td>
                            <td>{{ $data->user_name }}</td>
                            <td>{{ $data->reversed_with }}</td>
                            <td>{{ $data->wbs_level_2 }}</td>
                            <td>{{ $data->wbs_elemet_2 }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    @endif
</div>

<script>
    $(document).ready(function() {
        $('#zlis1Table').DataTable({
            "paging": true,
            "searching": true,
            "ordering": true,
            "info": true,
            "autoWidth": false,
            "responsive": true
            // "scrollX": true // Menambahkan fitur scroll horizontal
        });
    });
</script>
@endsection
