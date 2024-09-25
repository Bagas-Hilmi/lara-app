@extends('layouts.app') <!-- Ganti dengan layout yang sesuai -->

@section('content')
<div class="container">
    <h1 class="mb-4">Data FAGLB</h1>

    @if($zlis1Data->isEmpty())
        <div class="alert alert-warning" role="alert">
            Tidak ada data yang ditemukan.
        </div>
    @else
        <div class="table-responsive">
            <table class="table table-striped table-bordered">
                <thead class="thead-dark">
                    <tr>
                        <th>WBS Element</th>
                        <th>Network</th>
                        <th>Document Number</th>
                        <th>Company Code</th>
                        <th>Fiscal Year</th>
                        <th>Item</th>
                        <!-- Tambahkan kolom lain sesuai kebutuhan -->
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
                            <!-- Tambahkan kolom lain sesuai kebutuhan -->
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    @endif
</div>
@endsection
