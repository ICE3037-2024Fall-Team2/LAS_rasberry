<?php
session_start();

// Redirect if the admin is not logged in
if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
    header('Location: ras_admin_login.php');
    exit;
}

// Display the dashboard
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Admin Dashboard</title>
</head>
<body>
    <h1>Welcome, <?= htmlspecialchars($_SESSION['username']) ?></h1>
    
    <ul>
        <li><a href="ras_cancel_app.php">Cancel Appointment</a></li>
        <li><a href="ras_change_admin_list.php">Change Admin List</a></li>
        <li><a href="logout.php">Logout</a></li>
    </ul>
</body>
</html>
