<?php
session_start();
include 'db_connection.php';

// Check if admin is logged in
if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit();
}

$message = "";

// Add new admin
if (isset($_POST['add_admin'])) {
    $new_admin = $_POST['new_admin'];
    $password = password_hash($_POST['password'], PASSWORD_BCRYPT); // Hash password

    // Insert the new admin into the database
    $sql = "INSERT INTO admins (username, password) VALUES (?, ?)";
    $stmt = $conn->prepare($sql);
    if ($stmt->execute([$new_admin, $password])) {
        $message = "New admin added successfully!";
    } else {
        $message = "Error adding admin.";
    }
}

// Remove admin
if (isset($_POST['remove_admin'])) {
    $remove_admin = $_POST['remove_admin'];

    // Delete admin from the database
    $sql = "DELETE FROM admins WHERE username = ?";
    $stmt = $conn->prepare($sql);
    if ($stmt->execute([$remove_admin])) {
        $message = "Admin removed successfully!";
    } else {
        $message = "Error removing admin.";
    }
}

// Fetch current admins
$sql = "SELECT username FROM admins";
$stmt = $conn->prepare($sql);
$stmt->execute();
$admins = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Admins</title>
</head>
<body>
<h2>Manage Admins</h2>

<?php if (!empty($message)): ?>
    <p><?php echo $message; ?></p>
<?php endif; ?>

<!-- Add Admin Form -->
<h3>Add New Admin</h3>
<form method="POST">
    <label for="new_admin">New Admin Username:</label>
    <input type="text" name="new_admin" required>
    <label for="password">Password:</label>
    <input type="password" name="password" required>
    <button type="submit" name="add_admin">Add Admin</button>
</form>

<!-- Remove Admin Form -->
<h3>Remove Admin</h3>
<form method="POST">
    <label for="remove_admin">Select Admin to Remove:</label>
    <select name="remove_admin" required>
        <?php foreach ($admins as $admin): ?>
            <option value="<?php echo $admin['username']; ?>"><?php echo $admin['username']; ?></option>
        <?php endforeach; ?>
    </select>
    <button type="submit" name="remove_admin">Remove Admin</button>
</form>

</body>
</html>
