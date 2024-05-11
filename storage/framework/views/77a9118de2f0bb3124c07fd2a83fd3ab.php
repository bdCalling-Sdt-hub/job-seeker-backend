<html>

<head>
    <title>job content</title>
</head>

<body>
    <h1>Interview Notice</h1>
    <table>
        <thead>
            <tr>
                <th> Job name</th>
                <th>Interview date</th>
                <th>interview time</th>
                <th>Address</th>
                <th>description</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td><?php echo e($jobName); ?></td>
                <td><?php echo e($date); ?></td>
                <td><?php echo e($time); ?></td>
                <td><?php echo e($address); ?></td>
                <td><?php echo e($description); ?></td>
            </tr>
        </tbody>

    </table>
</body>

</html>
<?php /**PATH D:\xampp Software\htdocs\jobs\resources\views/emails/sendMail.blade.php ENDPATH**/ ?>