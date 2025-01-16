<!DOCTYPE html>
<html>

<head>
    <div class="page-header">
        <h3>PT. Ecogreen Oleochemicals - Batam Plant</h3>
    </div>
</head>

<body>
    <div class="header-section">
        <div class="title">CAPITAL EXPENDITURE</div>
        <table class="info-table">
            <tr>
                <td width="33%"><strong>Project Description:<strong></td>
                <td width="33%"><strong>Capex Number:<strong></td>
                <td width="33%"><strong>SAP Asset No (CIP):<strong></td>
            </tr>
            <tr>
                <td>{{ $project_desc }}</td>
                <td>{{ $capex_number }}</td>
                <td>{{ $cip_number }}</td>
            </tr>
            <tr>
                <td><b>WBS Number:<b></td>
                <td><b>Budget Type:<b></td>
                <td><b>Amount Budget:<b></td>
            </tr>
            <tr>
                <td>{{ $wbs_number }}</td>
                <td>{{ $budget_type }}</td>
                <td>Rp {{ number_format($amount_budget ?? 0, 0, ',', '.') }}</td>
            </tr>
        </table>
    </div>

    <table>
        <thead>
            <tr>
                <th>NO</th>
                <th>FA Doc</th>
                <th style="width: 50px;">Date</th>
                <th>Settle Doc</th>
                <th>Material</th>
                <th style="width: 250px;">Description</th>
                <th>QTY</th>
                <th>UOM</th>
                <th>Amount (Rp)</th>
                <th>Amount (US$)</th>
            </tr>
        </thead>
        <tbody>
            @php $counter = 1; @endphp
            @foreach ($reports as $report)
                <tr>
                    <td class="text-right">{{ $counter++ }}</td>
                    <td class="text-right">{{ $report->fa_doc }}</td>
                    <td class="text-center">{{ \Carbon\Carbon::parse($report->date)->format('d/m/Y') }}</td>
                    <td class="text-right">{{ $report->settle_doc }}</td>
                    <td class="text-right">{{ $report->material }}</td>
                    <td class="text-start">{{ $report->description }}</td>
                    <td class="text-right">{{ $report->qty }}</td>
                    <td class="text-center">{{ $report->uom }}</td>
                    <td class="text-right">{{ number_format($report->amount_rp, 0, ',', '.') }}</td>
                    <td class="text-right">{{ number_format($report->amount_us, 2, ',', '.') }}</td>
                </tr>
            @endforeach
        </tbody>
        <tfoot>
            <tr>
                <td colspan="8" class="text-center">Total:</td>
                <td class="text-right">
                    <i>{{ number_format($totals['amount_rp'], 0, ',', '.') }}</i>
                </td>
                <td class="text-right">
                    <i>{{ $totals['amount_us'] ? number_format($totals['amount_us'], 2, ',', '.') : '-' }}</i>
                </td>
            </tr>
        </tfoot>

        @php
            function formatNumber($number, $decimals = 0)
            {
                return number_format($number, $decimals, ',', '.');
            }
        @endphp

        @php
            $sortedReports = $reports->sortBy('date');
        @endphp

    </table>
    @php
        $isProject = $wbs_capex && $wbs_capex->wbs_capex === 'Project';
    @endphp
    <div class="signature-container" style="margin-top: 10px; padding: 20px;">
        <table style="width: 100%; border-collapse: collapse;">
            <tr>
                <!-- Kolom 1 - Prepared by (Admin 1) -->
                <td style="width: 25%; padding: 10px; text-align: center; vertical-align: top;">
                    <div style="margin-bottom: 10px;">Prepared by,</div>
                    <p style="margin-bottom: 0;">Digitally Signed</p>
                    <strong style="margin-top: 0;"> by
                        @if ($userRole === 'admin' && $userId == 3)
                            {{ $userName }}
                        @else
                            {{ $approved_by_admin_1 ?? '' }}
                        @endif
                    </strong>
                    <div style="margin-top: 0;">
                        @formatDateTime($approved_at_admin_1)
                    </div>
                    <div style="margin-top: 5px; font-weight: bold;">
                        @if ($userRole === 'admin' && $userId == 3)
                            {{ $userName }}
                        @else
                            {{ $approved_by_admin_1 ?? '' }}
                        @endif
                    </div>
                </td>

                <!-- Kolom 2 - Reviewed by (Admin 2) -->
                <td style="width: 25%; padding: 10px; text-align: center; vertical-align: top;">
                    <div style="margin-bottom: 10px;">Reviewed by,</div>
                    <p style="margin-bottom: 0;">Digitally Signed</p>
                    <strong style="margin-top: 0;"> by
                        @if ($userRole === 'admin' && $userId == 4)
                            {{ $userName }}
                        @else
                            {{ $approved_by_admin_2 ?? '' }}
                        @endif
                    </strong>
                    <div style="margin-top: 0;">
                        @formatDateTime($approved_at_admin_2)
                    </div>
                    <div style="margin-top: 5px; font-weight: bold;">
                        @if ($userRole === 'admin' && $userId == 4)
                            {{ $userName }}
                        @else
                            {{ $approved_by_admin_2 ?? '' }}
                        @endif
                    </div>
                </td>

                <!-- Kolom 3 - Approved by (User) -->
                <td style="width: 25%; padding: 10px; text-align: center; vertical-align: top;">
                    <div style="margin-bottom: 10px;">Acknowledged by,</div>
                    <p style="margin-bottom: 0;">Digitally Signed</p>
                    <strong style="margin-top: 0;"> by
                        @if ($userRole === 'user')
                            {{ $userName }}
                        @else
                            {{ $approved_by_user ?? '' }}
                        @endif
                    </strong>
                    <div style="margin-top: 0;">
                        @formatDateTime($approved_at_user)
                    </div>
                    <div style="margin-top: 5px; font-weight: bold;">
                        @if ($userRole === 'user')
                            {{ $userName }}
                        @else
                            {{ $approved_by_user ?? '' }}
                        @endif
                    </div>
                </td>

                @if ($isProject)
                    <!-- Kolom 4 - Approved by (Engineer) -->
                    <td style="width: 25%; padding: 10px; text-align: center; vertical-align: top;">
                        <div style="margin-bottom: 10px;">Confirm by,</div>
                        <p style="margin-bottom: 0;">Digitally Signed</p>
                        <strong style="margin-top: 0;"> by
                            @if ($userRole === 'engineering')
                                {{ $userName }}
                            @else
                                {{ $approved_by_engineer ?? '' }}
                            @endif
                        </strong>
                        <div style="margin-top: 0;">
                            @formatDateTime($approved_at_engineer)
                        </div>
                        <div style="margin-top: 5px; font-weight: bold;">
                            @if ($userRole === 'engineering')
                                {{ $userName }}
                            @else
                                {{ $approved_by_engineer ?? '' }}
                            @endif
                        </div>
                    </td>
                @endif
            </tr>
        </table>
    </div>

</body>

</html>

<style>
    body {
        font-family: 'Franklin Gothic Medium', 'Arial Narrow', Arial, sans-serif font-size: 8px;
    }

    .signature-container {
        font-family: Arial, sans-serif;
        font-size: 8px;
    }

    .header-section {
        width: 100%;
        margin-bottom: 20px;
    }

    .title {
        font-size: 9pt;
        font-weight: bold;
        margin-bottom: 10px;
    }

    .info-table {
        width: 100%;
        border-collapse: collapse;
        /* Menghilangkan jarak antara border */
        border-spacing: 0;
        /* Menghilangkan jarak antar sel */
    }

    .info-table td {
        font-size: 9pt;
        padding: 2px 5px;
        vertical-align: top;
        border: none;
        /* Menghilangkan border pada setiap sel */

    }

    table {
        width: 100%;
        border-collapse: collapse;
        margin-bottom: 20px;
    }

    th,
    td {
        border: 1px solid #000;
        padding-top: 5px;
        /* Jarak atas */
        padding-bottom: 5px;
        /* Jarak bawah */
        padding-left: 5px;
        /* Jarak kiri */
        padding-right: 5px;
        /* Jarak kanan */
        font-size: 10px;
    }

    th,
    tfoot {
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

    .spacing {
        margin-top: 50px;
        /* Ubah nilai ini sesuai kebutuhan */
    }

    .spacing span {
        margin-right: 8x;
        /* Tambahkan jarak antar elemen */
    }
</style>
