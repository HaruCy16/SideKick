<?php
session_start();
?>
<!DOCTYPE html>
<html>
<head>
    <title>Session Debug</title>
</head>
<body>
    <h1>Session Information</h1>
    <pre>
<?php
echo "Session ID: " . session_id() . "\n";
echo "Session Status: " . session_status() . "\n";
echo "Session Name: " . session_name() . "\n";
echo "\nSession Data:\n";
echo var_export($_SESSION, true);
echo "\n\nCookies:\n";
echo var_export($_COOKIE, true);
?>
    </pre>
</body>
</html>
