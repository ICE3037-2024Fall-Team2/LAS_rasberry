<?php
session_start();
require 'db_connection.php';

if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
    header('Location: ras_admin_login.php');
    exit;
}

if (isset($_POST['password'])) {
    $username = $_SESSION['username'];
    $password = $_POST['password'];

    // Verify the admin's password again
    $stmt = $conn->prepare("SELECT password FROM users WHERE username = ?");
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();
    $user = $result->fetch_assoc();

    if ($user && password_verify($password, $user['password'])) {
        // Proceed to the action
        $_SESSION['pw_verified'] = true;
        header('Location: ' . $_SESSION['redirect_after_pw']);
        exit;
    } else {
        $error = "Password incorrect";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Confirm Password</title>
</head>
<body>
    <h2>Confirm Password</h2>
    <?php if (isset($error)): ?>
        <p style="color: red;"><?= $error ?></p>
    <?php endif; ?>
    <form method="POST" action="ras_admin_pw.php">
        <label for="password">Password:</label>
        <input type="password" id="password" name="password" required><br>
        <button type="submit">Confirm</button>
    </form>
</body>
</html>
