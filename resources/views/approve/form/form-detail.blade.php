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
            margin-top: 10px;
        }

        th,
        td {
            border: 1px solid black;
            padding: 5px;
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
            <p>Up to : Nov 2024</p>
        </div>

        <table>
            <thead>
                <tr>
                    <th>No</th>
                    <th>Project Title</th>
                    <th>Requisitioner</th>
                    <th colspan="3">Capital Expenditure Request</th>
                    <th colspan="2">Budget</th>
                    <th>Time</th>
                    <th>Closed</th>
                    <th>Reason</th>
                </tr>
                <tr>
                    <th></th>
                    <th></th>
                    <th></th>
                    <th>Capex No.</th>
                    <th>Start Up</th>
                    <th>Exp. Complet</th>
                    <th>Amount</th>
                    <th>Actual Amount</th>
                    <th>Delay (days)</th>
                    <th>Yes/No</th>
                    <th></th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>1</td>
                    <td>{{ $project_desc ?? '' }}</td>
                    <td>{{ $requester ?? '' }}</td>
                    <td>{{ $capex_number ?? '' }}</td>
                    <td>{{ $startup ?? '' }}</td>
                    <td>{{ $expected_completed ?? '' }}</td>
                    <td>{{ number_format($total_budget ?? 0, 0, ',', '.') }}</td>
                    <td>{{ number_format($recost_usd, 2, ',', '.') }}</td>
                    <td>{{ $days_late ?? '' }}</td>
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
