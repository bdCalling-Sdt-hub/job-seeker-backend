<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Email Template</title>
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
        <p>Dear <?php echo e($applicant_name); ?></p>
        <p><?php echo e($description); ?></p>
        <p><strong>Job Name:</strong><?php echo e($jobName); ?></p>
        <p><strong>Date :</strong><?php echo e($date); ?></p>
        <p><strong>Time:</strong><?php echo e($time); ?></p>
        <p><strong>Description:</strong><?php echo e($description); ?></p>
        <p><strong>Address:</strong><?php echo e($address); ?></p>
        <?php if($zoom_link): ?>
            <p><strong>Zoom Link:</strong><a href="<?php echo e($zoom_link); ?>">   <?php echo e($zoom_link); ?></a></p>
        <?php endif; ?>
        <p>If you have any questions, feel free to <a href="<?php echo e($company_email); ?>">contact our support team</a>.</p>
        <p>Best regards,</p>
        <p><?php echo e($company_name); ?> Team</p>
    </div>
    <div class="footer">
        <p>&copy; 2024 <?php echo e($company_name); ?>. All rights reserved.</p>
        <p><a href="#">Unsubscribe</a> | <a href="#">Privacy Policy</a></p>
    </div>
</div>
</body>
</html>
<?php /**PATH D:\xampp Software\htdocs\jobs\resources\views/emails/sendMail.blade.php ENDPATH**/ ?>