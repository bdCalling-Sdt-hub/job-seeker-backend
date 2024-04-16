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
                <td>{{ $jobName }}</td>
                <td>{{ $date }}</td>
                <td>{{ $time }}</td>
                <td>{{ $address }}</td>
                <td>{{ $description }}</td>
            </tr>
        </tbody>

    </table>
</body>

</html>
