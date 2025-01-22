<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>PDF Report</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 10px;
            line-height: 1.4;
            margin: 0;
            padding: 0;
        }

        .container {
            width: 100%;
            margin: 20px;
        }

        h1,
        p {
            margin: 0;
            padding: 0;
        }

        .header {
            text-align: left;
            margin-bottom: 20px;
        }

        .header p {
            margin: 2px 0;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }

        th[rowspan] {
            border: 1px solid #000;
            vertical-align: middle; /* Memastikan konten di tengah untuk sel dengan rowspan */
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
            text-align: center;
        }

        th {
            background-color: #f2f2f2;
        }

        .footer {
            margin-top: 20px;
        }
    </style>
</head>

<body>
    <div class="container">
        <div class="header">
            <p><strong>PT. ECOGREEN OLEOCHEMICALS</strong></p>
            <p>To : Department Head Concern</p>
            <p>Kindly confirm the status of the following Capex subjected to Close Out</p>
            <p>Up to: {{ \Carbon\Carbon::parse($approved_at_admin_1)->format('M Y') }}</p>
        </div>

        <table>
            <thead>
                <tr>
                    <th rowspan="2">No</th>
                    <th rowspan="2" style="width: 70px;">Project Title</th>
                    <th rowspan="2" style="width: 70px;">Requisitioner</th>
                    <th colspan="4" >Capital Expenditure Request</th>
                    <th rowspan="2">Budget Amount</th>
                    <th rowspan="2">Actual Amount</th>
                    <th rowspan="2">Time Delay (days)</th>
                    <th rowspan="2">Closed Yes/No</th>
                    <th rowspan="2">Reason</th>
                </tr>
                <tr>
                    <th style="width: 80px;">Capex No.</th>
                    <th style="width: 60px;">Start Up</th>
                    <th style="width: 60px;">Exp. Completed</th>
                    <th style="width: 60px;">Closed Date</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>1</td>
                    <td>{{ $project_desc ?? '' }}</td>
                    <td>{{ $requester ?? '' }}</td>
                    <td>{{ $capex_number ?? '' }}</td>
                    <td>{{ \Carbon\Carbon::parse($startup)->format('d M Y') }}</td>
                    <td>{{ \Carbon\Carbon::parse($expected_completed)->format('d M Y') }}</td>
                    <td>{{ \Carbon\Carbon::parse($upload_date)->format('d M Y') }}</td>
                    <td>{{ number_format($total_budget ?? 0, 0, ',', '.') }}</td>
                    <td>{{ number_format($recost_usd, 2, ',', '.') }}</td>
                    <td>{{ $time_delay ?? '' }}</td>
                    <td>-</td>
                    <td>-</td>
                </tr>
            </tbody>
        </table>

        <div class="footer">
            <p>Please return to F&A upon completion</p>
        </div>

        <div class="signature-container" style="margin-top: 10px; padding: 20px;">
            <table style="width: 75%; border-collapse: collapse; margin: 0 auto;">
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

                </tr>
            </table>
        </div>
    </div>
</body>

</html>
