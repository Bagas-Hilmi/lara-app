<!DOCTYPE html>
<html>
<head>
    <title>Approval Notification</title>
</head>
<body>
    <p>Dear Pak {{ $nextApprover->name }},</p>

    <p>
        Mohon bantuan approvalnya untuk lampiran form closing 
        <strong>{{ $capexData->capex_number }}</strong> 
        {{ $capexData->project_desc }}.
    </p>

    <p>Silahkan klik tombol Approve pada aplikasi untuk melakukan e-sign.</p>

    <p>Terima Kasih.</p>

    <p>Regards,<br>
    {{ $user->name }}</p>
</body>
</html>
