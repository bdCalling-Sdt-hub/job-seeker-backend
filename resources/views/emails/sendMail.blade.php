<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            margin: 0;
            padding: 0;
            font-family: Arial, sans-serif;
        }
        .email-container {
            width: 100%;
            max-width: 600px;
            margin: auto;
            border: 1px solid #e0e0e0;
        }
        .header {
            background-color: #007bff;
            color: white;
            padding: 20px;
            text-align: center;
        }
        .content {
            padding: 20px;
        }
        .footer {
            background-color: #f8f9fa;
            padding: 10px;
            text-align: center;
            font-size: 12px;
            color: #6c757d;
        }
    </style>
</head>
<body>
<div class="email-container">
    <div class="header">
        <h1>Find Worker</h1>
    </div>
    <div class="content">

        <h2>Interview Notice!</h2>
        <p><strong>Dear {{ $applicant_name }}</strong></p>
        <p>{{ $description }}</p>
        <p><strong>Job Name:</strong>{{ $jobName }}</p>
        @if($date)
            <p><strong>Date :</strong>{{ $date }}</p>
        @endif

        @if($time)
            <p><strong>Time:</strong>{{ $time }}</p>
        @endif

        @if($address)
            <p><strong>Address:</strong>{{ $address }}</p>
        @endif

        @if($zoom_link)
            <p><strong>Zoom Link:</strong><a href="{{ $zoom_link }}">   {{ $zoom_link }}</a></p>
        @endif
        <p>If you have any questions, feel free to <a href="{{ $company_email }}">contact our support team</a>.</p>
        <p>Best regards,</p>
        <p>{{ $company_name }} Team</p>
    </div>
    <div class="footer">
        <p>&copy; 2024 {{ $company_name }}. All rights reserved.</p>
        <p><a href="#">Unsubscribe</a> | <a href="#">Privacy Policy</a></p>
    </div>
</div>
</body>
</html>
