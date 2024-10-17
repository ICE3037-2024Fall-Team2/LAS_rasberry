<?php
session_start();
include 'db_connection.php';

if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit();
}

$message = "";

// Fetch current appointments
$sql = "SELECT reservation_id, lab_id, user_id, date, time FROM reservations";
$stmt = $conn->prepare($sql);
$stmt->execute();
$appointments = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Cancel appointment logic
if (isset($_POST['confirm_cancel'])) {
    $reservation_id = $_POST['reservation_id'];
    $admin_password = $_POST['admin_password'];

    // Verify admin password
    $sql = "SELECT * FROM admins WHERE username = ? AND password = ?";
    $stmt = $conn->prepare($sql);
    $stmt->execute([$_SESSION['username'], md5($admin_password)]); // Hashing is required here
    $admin = $stmt->fetch();

    if ($admin) {
        // If the password is correct, proceed to cancel the appointment
        $sql = "DELETE FROM reservations WHERE reservation_id = ?";
        $stmt = $conn->prepare($sql);
        if ($stmt->execute([$reservation_id])) {
            $message = "Appointment canceled successfully.";
        } else {
            $message = "Error: Could not cancel the appointment.";
        }
    } else {
        $message = "Incorrect admin password.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cancel Appointment</title>
    <script>
        function selectAppointment(reservationId) {
            document.getElementById('reservation_id').value = reservationId;
            document.getElementById('password-form').style.display = 'block';
        }
    </script>
</head>
<body>
<h2>Cancel Appointments</h2>

<?php if (!empty($message)): ?>
    <p><?php echo $message; ?></p>
<?php endif; ?>

<!-- Display the appointments -->
<ul>
    <?php foreach ($appointments as $appointment): ?>
        <li>
            <?php echo "Lab ID: {$appointment['lab_id']}, User ID: {$appointment['user_id']}, Date: {$appointment['date']}, Time: {$appointment['time']}"; ?>
            <button onclick="selectAppointment(<?php echo $appointment['reservation_id']; ?>)">Cancel</button>
        </li>
    <?php endforeach; ?>
</ul>

<!-- Password confirmation form -->
<div id="password-form" style="display:none;">
    <h3>Confirm Cancellation with Admin Password:</h3>
    <form method="POST">
        <input type="hidden" name="reservation_id" id="reservation_id">
        <label for="admin_password">Admin Password:</label>
        <input type="password" name="admin_password" required>
        <button type="submit" name="confirm_cancel">Confirm Cancel</button>
    </form>
</div>

</body>
</html>
