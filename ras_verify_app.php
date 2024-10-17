<?php
session_start();
include 'db_connection.php';

// Check if the user is logged in
if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit();
}

// Placeholder for facial recognition (use actual AI/ML model in production)
$isVerified = false; // Default: not verified

if (isset($_POST['verify'])) {
    // Here, the AI facial recognition logic would be placed
    // For now, we'll simulate verification success
    $isVerified = true; // Simulate successful recognition

    if ($isVerified) {
        // Mark the reservation as verified in the database
        $reservation_id = $_POST['reservation_id'];

        $sql = "UPDATE reservations SET verified = TRUE WHERE reservation_id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->execute([$reservation_id]);

        header("Location: ras_verify_success.php");
    } else {
        header("Location: ras_verify_failed.php");
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Verify Appointment</title>
</head>
<body>
    <h2>Verify Your Appointment</h2>

    <form method="POST">
        <input type="hidden" name="reservation_id" value="ENTER_RESERVATION_ID_HERE"> <!-- Modify to capture the actual reservation ID -->
        <button type="submit" name="verify">Verify</button>
    </form>

    <!-- Simulate camera feed for facial recognition -->
    <p>Simulating camera view for facial recognition...</p>
</body>
</html>
