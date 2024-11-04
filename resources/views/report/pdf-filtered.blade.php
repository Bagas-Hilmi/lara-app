<!DOCTYPE html>
<html>

<head>
    <title>Report Capex</title>
    
</head>

<body>

    <div class="page-header">
        <h2>PT. Ecogreen Oleochemicals - Batam Plant</h2>
        <h3>CAPITAL EXPENDITURE</h3>
    </div>
    {{-- Header Info --}}
    <div class="header-info">
        <div class="info-box">
            <div class="info-box-label">Project Description:</div>
            <div>{{ $capexData->project_desc ?? '-' }}</div>
        </div>
        <div class="info-box">
            <div class="info-box-label">Capex Number:</div>
            <div>{{ $capexData->capex_number ?? '-' }}</div>
        </div>
        <div class="info-box">
            <div class="info-box-label">SAP Asset No (CIP):</div>
            <div>{{ $capexData->cip_number ?? '-' }}</div>
        </div>
        <div class="info-box">
            <div class="info-box-label">WBS Number:</div>
            <div>{{ $capexData->wbs_number ?? '-' }}</div>
        </div>
        <div class="info-box">
            <div class="info-box-label">Budget Type:</div>
            <div>{{ $capexData->budget_type ?? '-' }}</div>
        </div>
        <div class="info-box">
            <div class="info-box-label">Amount Budget:</div>
            <div>{{ number_format($capexData->amount_budget ?? 0, 0, ',', '.') }}</div>
        </div>
    </div>
    

    {{-- Table --}}
    <table>
        <thead>
            <tr>
                <th>NO</th>
                <th>FA Doc</th>
                <th>Date</th>
                <th>Settle Doc</th>
                <th>Material</th>
                <th>Description</th>
                <th>QTY</th>
                <th>UOM</th>
                <th>Amount (Rp)</th>
                <th>Amount (US$)</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($reports as $index => $report)
                <tr>
                    <td class="text-right">{{ $index + 1 }}</td>
                    <td class="text-right">{{ $report->fa_doc }}</td>
                    <td class="text-center">{{ $report->date }}</td>
                    <td class="text-right">{{ $report->settle_doc }}</td>
                    <td class="text-right">{{ $report->material }}</td>
                    <td class="text-start">{{ $report->description }}</td>
                    <td class="text-right">{{ $report->qty }}</td>
                    <td class="text-center">{{ $report->uom }}</td>
                    <td class="text-right">{{ number_format($report->amount_rp, 0, ',', '.') }}</td>
                    <td class="text-right">
                        {{ $report->amount_us ? number_format($report->amount_us, 2, ',', '.') : '-' }}</td>
                </tr>
            @endforeach
        </tbody>
        <tfoot>
            <tr>
                <td colspan="8" class="text-center">Total:</td>
                <td class="text-right">
                   <i> {{ formatNumber($totals['amount_rp']) }} </i>
                </td>
                <td class="text-right">
                   <i> {{ $totals['amount_us'] ? formatNumber($totals['amount_us'], 2) : '-' }}</i>
                </td>
                
            </tr>
        </tfoot>

        @php
        function formatNumber($number, $decimals = 0) {
            return number_format($number, $decimals, ',', '.');
        }
        @endphp
                
    </table>

    <div class="signature-section">
        <p>Acknowledged by</p>
        <p class="spacing">{{ $signature_name }}</p>    
    </div>
    
</body>

</html>
<style>
    body {
        font-family: 'Franklin Gothic Medium', 'Arial Narrow', Arial, sans-serif
        font-size: 12px;
    }
    .header-info {
        margin-bottom: 20px;
        display: flex; /* Menggunakan Flexbox untuk tata letak horizontal */
        flex-wrap: wrap; /* Membungkus jika ada elemen yang tidak muat dalam satu baris */
        gap: 20px; /* Jarak antar elemen */
    }

    .info-box {
        display: flex; /* Mengatur elemen info-box dalam satu baris */
        margin-bottom: 0; /* Menghapus margin bawah agar lebih rapi */
    }

    .info-box-label {
        font-weight: bold;
        margin-right: 10px; /* Jarak antara label dan nilai */
    }
    table {
        width: 100%;
        border-collapse: collapse;
        margin-bottom: 20px;
    }
    th,
    td {
        border: 1px solid #000;
        padding-top: 10px; /* Jarak atas */
        padding-bottom: 15px; /* Jarak bawah */
        padding-left: 5px; /* Jarak kiri */
        padding-right: 5px; /* Jarak kanan */
        font-size: 10px;
    }
    th,tfoot {
        background-color: #f4f4f4;
        font-weight: bold;
        text-align: center;
    }

    
    .text-right {
        text-align: right;
    }
    .text-center {
        text-align: center;
    }
    .amount {
        text-align: right;
        white-space: nowrap;
    }

    .signature-section {
        text-align: center;
        margin-top: 20px; /* Jarak dari tabel */
        float: right; /* Mengapung ke kanan */
    }

    .signature-line {
        width: 150px;
        border-bottom: 1px solid #000;
        margin: 15px 0 25px 0; /* Jarak atas dan bawah dari garis */
    }

    .spacing {
        margin-top: 50px; /* Ubah nilai ini sesuai kebutuhan */
    }

</style>