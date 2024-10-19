<?php
session_start();

// Check if the admin is logged in
if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
    header('Location: ras_admin_login.php');
    exit;
}

// Check if password has been verified
if (!isset($_SESSION['pw_verified']) || $_SESSION['pw_verified'] !== true) {
    $_SESSION['redirect_after_pw'] = 'ras_change_admin_list.php';
    header('Location: ras_admin_pw.php');
    exit;
}

// Database connection
require 'db_connection.php';

if (isset($_POST['new_admin_username'])) {
    $new_admin_username = $_POST['new_admin_username'];

    // Update user role to admin
    $stmt = $conn->prepare("UPDATE users SET role = 'admin' WHERE username = ?");
    $stmt->bind_param("s", $new_admin_username);

    if ($stmt->execute()) {
        echo "Admin list updated successfully.";
    } else {
        echo "Failed to update admin list.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Change Admin List</title>
</head>
<body>
    <h2>Change Admin List</h2>
    <form method="POST" action="ras_change_admin_list.php">
        <label for="new_admin_username">New Admin Username:</label>
        <input type="text" id="new_admin_username" name="new_admin_username" required><br>
        <button type="submit">Update Admin</button>
    </form>
</body>
</html>
