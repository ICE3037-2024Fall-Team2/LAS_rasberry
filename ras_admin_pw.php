<?php
session_start();
include 'db_connection.php';

if (isset($_POST['submit'])) {
    $password = $_POST['password'];

    // Fetch the stored hashed password for the admin
    $sql = "SELECT password FROM admins WHERE username = ?";
    $stmt = $conn->prepare($sql);
    $stmt->execute([$_SESSION['username']]);
    $admin = $stmt->fetch();

    // Verify the provided password against the stored hash
    if ($admin && password_verify($password, $admin['password'])) {
        $_SESSION['is_admin'] = true;
        header("Location: ras_admin_dash.php");
    } else {
        $error_message = "Incorrect password!";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Password</title>
</head>
<body>
    <h2>Admin Password Verification</h2>
    
    <?php if (!empty($error_message)): ?>
        <p><?php echo $error_message; ?></p>
    <?php endif; ?>
    
    <form method="POST">
        <label for="password">Enter Password:</label>
        <input type="password" name="password" required>
        <button type="submit" name="submit">Submit</button>
    </form>
</body>
</html>
