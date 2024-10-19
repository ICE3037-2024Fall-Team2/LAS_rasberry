<?php
session_start();

// Check if the admin is logged in
if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
    header('Location: ras_admin_login.php');
    exit;
}

// Check if password has been verified
if (!isset($_SESSION['pw_verified']) || $_SESSION['pw_verified'] !== true) {
    $_SESSION['redirect_after_pw'] = 'ras_cancel_app.php';
    header('Location: ras_admin_pw.php');
    exit;
}

// Database connection
require 'db_connection.php';

if (isset($_POST['reservation_id'])) {
    $reservation_id = $_POST['reservation_id'];

    // Delete the reservation
    $stmt = $conn->prepare("DELETE FROM reservations WHERE reservation_id = ?");
    $stmt->bind_param("s", $reservation_id);

    if ($stmt->execute()) {
        echo "Reservation cancelled successfully.";
    } else {
        echo "Failed to cancel the reservation.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Cancel Reservation</title>
</head>
<body>
    <h2>Cancel a Reservation</h2>
    <form method="POST" action="ras_cancel_app.php">
        <label for="reservation_id">Reservation ID:</label>
        <input type="text" id="reservation_id" name="reservation_id" required><br>
        <button type="submit">Cancel Reservation</button>
    </form>
</body>
</html>
