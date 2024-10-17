<?php
session_start();

// You can validate the admin password here. For simplicity, let's assume 'admin123' is the correct password
if ($_POST['admin_password'] !== 'admin123') {
    header("Location: admin_verify.php");
    exit();
}

// Set a session to mark the admin as logged in
$_SESSION['admin_logged_in'] = true;
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
    <style>
        body { text-align: center; margin-top: 100px; font-family: Arial, sans-serif; }
        button { padding: 10px 20px; margin: 20px; font-size: 16px; cursor: pointer; }
    </style>
</head>
<body>
    <h1>Admin Dashboard</h1>
    <button onclick="window.location.href='ras_cancel_appointment.php'">Cancel Appointment</button>
    <button onclick="window.location.href='ras_change_admin_list.php'">Change Admin List</button>
</body>
</html>
