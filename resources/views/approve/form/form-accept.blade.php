<!-- resources/views/approve/form/form-accept.blade.php -->
<!DOCTYPE html>
<html>

<head>
    <style>
        body {
            font-family: 'DejaVu Sans', sans-serif;
            font-size: 12px;
            line-height: 1.4;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        .header-table {
            border: 1px solid black;
            margin-bottom: 20px;
        }

        .header-table td {
            padding: 5px;
        }

        .content-table {
            border: 1px solid black;
            margin-bottom: 20px;
        }

        .content-table td,
        .content-table th {
            border: 1px solid black;
            padding: 5px;
        }

        .checkbox {
            width: 15px;
            height: 15px;
            border: 1px solid black;
            display: inline-block;
            text-align: center; /* Meratakan "X" secara horizontal */
            line-height: 11px;  /* Mengurangi line-height agar "X" lebih ke atas */
            font-weight: bold;  /* Membuat "X" lebih tebal (opsional) */
            font-size: 12px;    /* Ukuran font untuk membuat "X" pas dalam kotak */
            padding-top: 3px;   /* Memberi sedikit jarak atas agar "X" lebih naik */
        }

        .title {
            text-align: center;
            font-weight: bold;
            font-size: 14px;
            margin-bottom: 20px;
        }
    </style>
</head>

<body>
    <div class="title">FORM PROJECT ACCEPTANCE CHECKLIST</div>

    <table class="header-table">
        <tr>
            <td colspan="2">
                <table width="100%">
                    <tr>
                        <td>WBS Type</td>
                        <td >:</td>
                        <td colspan="2">{{ $wbs_type ?? '' }}</td>
                    </tr>
                    <tr>
                        <td>CAPEX</td>
                        <td >:</td>
                        <td colspan="2">{{ $capex_number ?? '' }}</td>
                    </tr>
                    <tr>
                        <td>Project Title</td>
                        <td>:</td>
                        <td colspan="2">{{ $project_desc ?? '' }}</td>
                    </tr>
                    <tr>
                        <td>SAP Asset Number</td>
                        <td>:</td>
                        <td colspan="2">{{ $cip_number ?? '' }}</td>
                    </tr>
                    <tr>
                        <td>WBS Number</td>
                        <td>:</td>
                        <td colspan="2">{{ $wbs_number ?? '' }}</td>
                    </tr>
                    <tr>
                        <td>Date</td>
                        <td>:</td>
                        <td colspan="2">{{ $startup }}</td>
                    </tr>
                    <tr>
                        <td>Expected Completed</td>
                        <td>:</td>
                        <td colspan="2">{{ $expected_completed }}</td>
                    </tr>
                    <tr>
                        <td>Actual Completed</td>
                        <td>:</td>
                        <td colspan="2">{{ $date }}</td>
                    </tr>
                    <tr>
                        <td>Remark</td>
                        <td>:</td>
                        <td colspan="2">{{ $remark }}</td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>

    <table class="content-table">
        <tr>
            <th width="60%">BERITA ACARA PENYELESAIAN CAPEX</th>
            <th width="20%">Yes</th>
            <th width="20%">No</th>
        </tr>
        <tr>
            <td>WBS-P Engineering & Production</td>
            <td align="center"><span class="checkbox">{{ $engineering_production == 1 ? 'X' : '' }}</span></td>
            <td align="center"><span class="checkbox">{{ $engineering_production == 0 ? 'X' : '' }}</span></td>
        </tr>
        <tr>
            <td>Maintenance</td>
            <td align="center"><span class="checkbox">{{ $maintenance == 1 ? 'X' : '' }}</span></td>
            <td align="center"><span class="checkbox">{{ $maintenance == 0 ? 'X' : '' }}</span></td>
        </tr>
        <tr>
            <td>Outstanding Inventory</td>
            <td align="center"><span class="checkbox">{{ $outstanding_inventory == 1 ? 'X' : '' }}</span></td>
            <td align="center"><span class="checkbox">{{ $outstanding_inventory == 0 ? 'X' : '' }}</span></td>
        </tr>
        <tr>
            <td colspan="3"><strong>Outstanding PO</strong></td>
        </tr>
        <tr>
            <td style="padding-left: 20px;">Material</td>
            <td align="center"><span class="checkbox">{{ $material == 1 ? 'X' : '' }}</span></td>
            <td align="center"><span class="checkbox">{{ $material == 0 ? 'X' : '' }}</span></td>
        </tr>
        <tr>
            <td style="padding-left: 20px;">Jasa</td>
            <td align="center"><span class="checkbox">{{ $jasa == 1 ? 'X' : '' }}</span></td>
            <td align="center"><span class="checkbox">{{ $jasa == 0 ? 'X' : '' }}</span></td>
        </tr>
        <tr>
            <td style="padding-left: 20px;">Etc</td>
            <td align="center"><span class="checkbox">{{ $etc == 1 ? 'X' : '' }}</span></td>
            <td align="center"><span class="checkbox">{{ $etc == 0 ? 'X' : '' }}</span></td>
        </tr>
        <tr>
            <td colspan="3"><strong>BERITA ACARA PENERIMAAN BARANG</strong></td>
        </tr>
        <tr>
            <td style="padding-left: 20px;">Barang diterima oleh Warehouse</td>
            <td align="center"><span class="checkbox">{{ $warehouse_received == 1 ? 'X' : '' }}</span></td>
            <td align="center"><span class="checkbox">{{ $warehouse_received == 0 ? 'X' : '' }}</span></td>
        </tr>
        <tr>
            <td style="padding-left: 20px;">Barang diterima oleh User</td>
            <td align="center"><span class="checkbox">{{ $user_received == 1 ? 'X' : '' }}</span></td>
            <td align="center"><span class="checkbox">{{ $user_received == 0 ? 'X' : '' }}</span></td>
        </tr>
        <tr>
            <td style="padding-left: 20px;">Berita Acara</td>
            <td align="center"><span class="checkbox">{{ $berita_acara == 1 ? 'X' : '' }}</span></td>
            <td align="center"><span class="checkbox">{{ $berita_acara == 0 ? 'X' : '' }}</span></td>
        </tr>
    </table>
</body>

</html>