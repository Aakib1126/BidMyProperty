<?php
session_start();
if (!isset($_SESSION['admin_id'])) {
    header("Location: admin_login.php");
    exit;
}
include '../database/db_connect.php';

$admin_id = $_SESSION['admin_id'];

// Fetch admin details
$admin = mysqli_fetch_assoc(mysqli_query($conn, "SELECT * FROM admins WHERE id='$admin_id'"));

// Update settings
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name = mysqli_real_escape_string($conn, $_POST['name']);
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    
    if (!empty($_POST['password'])) {
        $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
        mysqli_query($conn, "UPDATE admins SET name='$name', email='$email', password='$password' WHERE id='$admin_id'");
    } else {
        mysqli_query($conn, "UPDATE admins SET name='$name', email='$email' WHERE id='$admin_id'");
    }
    
    echo "<script>alert('Settings updated successfully!'); window.location.href='admin_settings.php';</script>";
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Settings</title>
    <link rel="stylesheet" href="../frontend/css/admin.css">
</head>
<body>
    <header>
        <h1>Admin Settings</h1>
        <nav>
            <a href="admin_dashboard.php">Dashboard</a>
            <a href="admin_users.php">Manage Users</a>
            <a href="admin_properties.php">Manage Properties</a>
            <a href="admin_reports.php">View Reports</a>
            <a href="admin_logout.php">Logout</a>
        </nav>
    </header>
    <section>
        <h2>Update Admin Details</h2>
        <form action="admin_settings.php" method="POST">
            <input type="text" name="name" value="<?php echo $admin['name']; ?>" required>
            <input type="email" name="email" value="<?php echo $admin['email']; ?>" required>
            <input type="password" name="password" placeholder="Enter new password (optional)">
            <button type="submit">Update Settings</button>
        </form>
    </section>
</body>
</html>
