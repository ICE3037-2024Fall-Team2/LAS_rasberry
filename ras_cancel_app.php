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
    $stmt->close();
}

// Fetch all reservations
$sql = "SELECT reservation_id, lab_id, user_id, date, time, verified 
        FROM reservations 
        WHERE date >= CURDATE() 
        ORDER BY date ASC, time ASC";
$result = $conn->query($sql);

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

    <h2>Existing Reservations</h2>
    <table border="1" cellpadding="10">
        <thead>
            <tr>
                <th>Reservation ID</th>
                <th>Lab ID</th>
                <th>User ID</th>
                <th>Date</th>
                <th>Time</th>
                <th>Verified</th>
            </tr>
        </thead>
        <tbody>
            <?php
            // Check if there are any reservations
            if ($result->num_rows > 0) {
                // Loop through and display each reservation
                while ($row = $result->fetch_assoc()) {
                    echo "<tr>";
                    echo "<td>" . htmlspecialchars($row['reservation_id']) . "</td>";
                    echo "<td>" . htmlspecialchars($row['lab_id']) . "</td>";
                    echo "<td>" . htmlspecialchars($row['user_id']) . "</td>";
                    echo "<td>" . htmlspecialchars($row['date']) . "</td>";
                    echo "<td>" . htmlspecialchars($row['time']) . "</td>";
                    echo "<td>" . ($row['verified'] ? 'Yes' : 'No') . "</td>";
                    echo "</tr>";
                }
            } else {
                echo "<tr><td colspan='6'>No upcoming reservations found</td></tr>";
            }
            ?>
        </tbody>
    </table>
</body>
</html>
