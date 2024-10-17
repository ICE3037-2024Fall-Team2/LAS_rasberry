<?php
session_start();

if (!isset($_SESSION['admin_logged_in'])) {
    header("Location: admin_verify.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cancel Appointment</title>
</head>
<body>
    <h2>Cancel Appointment</h2>
    <!-- Appointment cancellation logic here -->
</body>
</html>
