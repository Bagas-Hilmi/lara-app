<!DOCTYPE html>
<html>
<head>
    <title>Notifikasi Persetujuan CAPEX</title>
</head>
<body>
    <h2>Permintaan Persetujuan CAPEX</h2>
    <p>Halo {{ $nextApprover->name }},</p>
    <p>Ada dokumen CAPEX yang membutuhkan persetujuan Anda.</p>
    
    <h3>Detail CAPEX:</h3>
    <ul>
        <li>ID CAPEX: {{ $capexData->id_capex }}</li>
        <li>WBS: {{ $capexData->wbs_capex }}</li>
    </ul>
    
    <p>Silakan login ke sistem untuk melakukan review dan persetujuan.</p>
    
    <br>
    <p>Terima kasih,</p>
    <p>Tim Sistem CAPEX</p>
</body>
</html>