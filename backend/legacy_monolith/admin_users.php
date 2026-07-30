<?php
session_start();
if (!isset($_SESSION['admin_id'])) {
    header("Location: admin_login.php");
    exit;
}
include '../database/db_connect.php';

// Fetch all users
$users = mysqli_query($conn, "SELECT * FROM users");

// Delete user
if (isset($_GET['delete'])) {
    $user_id = $_GET['delete'];
    mysqli_query($conn, "DELETE FROM users WHERE id='$user_id'");
    echo "<script>alert('User deleted successfully!'); window.location.href='admin_users.php';</script>";
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Users</title>
    <link rel="stylesheet" href="../frontend/css/admin.css">
</head>
<body>
    <header>
        <h1>Manage Users</h1>
        <nav>
            <a href="admin_dashboard.php">Dashboard</a>
            <a href="admin_properties.php">Manage Properties</a>
            <a href="admin_reports.php">View Reports</a>
            <a href="admin_settings.php">Settings</a>
            <a href="admin_logout.php">Logout</a>
        </nav>
    </header>
    <section>
        <h2>Registered Users</h2>
        <table border="1">
            <tr>
                <th>ID</th>
                <th>Name</th>
                <th>Email</th>
                <th>Registered At</th>
                <th>Action</th>
            </tr>
            <?php while ($row = mysqli_fetch_assoc($users)) : ?>
            <tr>
                <td><?php echo $row['id']; ?></td>
                <td><?php echo $row['name']; ?></td>
                <td><?php echo $row['email']; ?></td>
                <td><?php echo $row['created_at']; ?></td>
                <td><a href="admin_users.php?delete=<?php echo $row['id']; ?>" onclick="return confirm('Are you sure?')">Delete</a></td>
            </tr>
            <?php endwhile; ?>
        </table>
    </section>
</body>
</html>