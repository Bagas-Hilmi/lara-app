<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>PDF Report</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 12px;
            line-height: 1.4;
            margin: 0;
            padding: 0;
        }

        .container {
            width: 100%;
            margin: 20px;
        }

        h1, p {
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

        th, td {
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
                    <td>{{ $total_budget ?? '' }}</td>
                    <td>60,000.00</td>
                    <td>91</td>
                    <td>Yes</td>
                    <td></td>
                </tr>
            </tbody>
        </table>

        <div class="footer">
            <p>Please return to F&A upon completion</p>
        </div>
    </div>
</body>
</html>
