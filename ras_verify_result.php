<?php
// Simulate appointment verification process
$appointment_code = $_POST['appointment_code'];

// Example check (in reality, you'd check this against a database)
if ($appointment_code === '1234') {
    header("Location: ras_verify_success.php");
} else {
    header("Location: ras_verify_failed.php");
}
exit();
?>
