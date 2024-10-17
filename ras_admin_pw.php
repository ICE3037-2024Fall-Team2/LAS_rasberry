<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Verification</title>
    <style>
        body { text-align: center; margin-top: 100px; font-family: Arial, sans-serif; }
        input { padding: 10px; font-size: 16px; }
        button { padding: 10px 20px; margin-top: 20px; cursor: pointer; }
    </style>
</head>
<body>
    <h2>Admin Password Input</h2>
    <form method="post" action="admin_dashboard.php">
        <input type="password" name="admin_password" placeholder="Enter Admin Password" required>
        <br><br>
        <button type="submit">Submit</button>
    </form>
</body>
</html>
