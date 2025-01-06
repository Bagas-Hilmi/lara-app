<!DOCTYPE html>
<html>

<head>
    <title>Approval Notification</title>
</head>

<body>
    <p>Dear Bapak/Ibu {{ $nextApprover->name }},</p>

    <p>
        Mohon bantuan approvalnya untuk lampiran 
            1.form closing
            2.form detail
            3.form acceptance
        <strong>{{ $capexData['capex_number'] }}</strong>
        {{ $capexData['project_desc'] }}
    </p>

    <p>Untuk memberikan persetujuan, mohon klik tombol Approve pada aplikasi untuk melakukan e-sign.</p>

    <p>Atas perhatian dan kerjasama Bapak/Ibu, kami ucapkan terima kasih.</p>

    <p>Regards,<br>
        {{ $user->name }}</p>
        {{ \Carbon\Carbon::now()->isoFormat('dddd, D MMMM YYYY') }}</p>

</body>

</html>
