<?php
session_start();
$toastr = isset($_SESSION['toastr']) ? $_SESSION['toastr'] : null;
unset($_SESSION['toastr']);

require 'db_connection.php';
// Check if the login form has been submitted
if (isset($_POST['admin_id']) && isset($_POST['password'])) {
    $id = $_POST['admin_id'];
    $password = $_POST['password'];

    // Query the admin table for admin details
    $stmt = $conn->prepare("SELECT * FROM admin WHERE admin_id = ?");
    $stmt->bind_param("s", $id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $stored_password_hash = $row['password'];

        // Verify the entered password with the stored hashed password
        if (password_verify($password, $stored_password_hash)) {
            // Password is correct, set session variables
            $_SESSION['id'] = $row['admin_id'];
            $_SESSION['username'] = $row['admin_name'];
            $_SESSION['admin_logged_in'] = true;
            header("Location: ras_admin_dash.php"); // redirect to welcome page
            exit();
        } else {
            //echo "Invalid password!";
            //echo "<script>alert('Invalid password!');</script>";
            $_SESSION['toastr'] = array(
                'type' => 'error',
                'message' => 'Invalid password!'
            );
            header("Location: ras_admin_login.php");
        }
    } else {
        //echo "User not found!";
        //echo "<script>alert('User not found!');</script>";
        $_SESSION['toastr'] = array(
            'type' => 'error',
            'message' => 'User not found!'
        );
        header("Location: ras_admin_login.php");
    }
}
$conn->close();

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Login</title>

    <!-- Toastr -->
    <script src="http://code.jquery.com/jquery-1.9.1.min.js"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/2.0.1/css/toastr.css" rel="stylesheet"/>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/2.0.1/js/toastr.js"></script>


    <style>
        .error-message {
            color: red;
            font-weight: bold;
        }
    </style>
</head>
<body>
    <h2>Admin Login</h2>

    <!-- Login form -->
    <form action="ras_admin_login.php" method="post">
        <label for="admin_id">Admin ID:</label>
        <input type="text" name="admin_id" id="admin_id" required><br>

        <label for="password">Password:</label>
        <input type="password" name="password" id="password" required><br>

        <button type="submit">Login</button>
    </form>


    <?php if ($toastr): ?>
    <script type="text/javascript">
        $(document).ready(function() {
            toastr.<?php echo $toastr['type']; ?>('<?php echo $toastr['message']; ?>');
        });
    </script>
    <?php endif; ?>
</body>
</html>
