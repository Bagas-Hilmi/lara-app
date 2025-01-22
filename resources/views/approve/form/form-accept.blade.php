<!-- resources/views/approve/form/form-accept.blade.php -->
<!DOCTYPE html>
<html>

<head>
    <style>
        body {
            font-family: 'Franklin Gothic Medium', 'Arial Narrow', Arial, sans-serif font-size: 8px;
            line-height: 1.4;
        }

        table {
            width: 85%;
            border-collapse: collapse;
            margin: 0 auto;
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
            text-align: center;
            /* Meratakan "X" secara horizontal */
            line-height: 13px;
            /* Mengurangi line-height agar "X" lebih ke atas */
            font-weight: bold;
            /* Membuat "X" lebih tebal (opsional) */
            font-size: 14px;
            /* Ukuran font untuk membuat "X" pas dalam kotak */
            padding-top: 3px;
            /* Memberi sedikit jarak atas agar "X" lebih naik */
        }

        .title {
            text-align: center;
            font-weight: bold;
            font-size: 14px;
            margin-bottom: 20px;
        }

        .signature-container {
            font-family: Arial, sans-serif;
            font-size: 9pt;
        }

        .signature-container td {
            border: 1px solid black;
            padding: 8px;
            text-align: center;
            vertical-align: top;
        }
    </style>
</head>

<body>
    <div class="title">FORM PROJECT ACCEPTANCE CHECKLIST</div>

    <table class="header-table">
        <tr>
            <td colspan="2">
                <table width="85%">
                    <tr>
                        <td>WBS Type</td>
                        <td>:</td>
                        <td colspan="2">{{ $wbs_type ?? '' }}</td>
                    </tr>
                    <tr>
                        <td>CAPEX</td>
                        <td>:</td>
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
                        <td>Start Up</td>
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
                        <td colspan="2">{{$upload_date }}</td>
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
            <td colspan="3">
                <div>WBS-P</div>
            </td>
        </tr>
        <tr>
            <td>Engineering & Production</td>
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
            <td colspan="3">
                <div>WBS-A</div>
            </td>
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

    <div class="signature-container" style="margin-top: 15px; padding: 20px;">
        <table style="width: 50%; border-collapse: collapse; margin: 0 auto; text-align: center;">
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
                    <div style="margin-bottom: 10px;">Approved by,</div>
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
            </tr>
        </table>
    </div>
</body>

</html>
