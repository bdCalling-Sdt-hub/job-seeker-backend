<!DOCTYPE html>
<head>
    <title>Pusher Test</title>
    <script src="https://js.pusher.com/8.2.0/pusher.min.js"></script>
    <script>

        // Enable pusher logging - don't include this in production
        Pusher.logToConsole = true;

        var pusher = new Pusher('ed3fa994e71a7b25af7e', {
            cluster: 'ap2'
        });

        var channel = pusher.subscribe('my-channel');
        channel.bind('my-event', function(data) {
            alert(JSON.stringify(data));
        });
    </script>
</head>
<body>
<h1>Server Running</h1>




</body>
<?php /**PATH D:\xampp Software\htdocs\jobs\resources\views/welcome.blade.php ENDPATH**/ ?>